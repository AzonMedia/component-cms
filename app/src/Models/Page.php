<?php
declare(strict_types=1);


namespace GuzabaPlatform\Cms\Models;

use Azonmedia\Utilities\GeneralUtil;
use Guzaba2\Authorization\Exceptions\PermissionDeniedException;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Orm\ActiveRecordCollection;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Orm\Store\Sql\Mysql;
use GuzabaPlatform\Platform\Application\BaseActiveRecord;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use Guzaba2\Translator\Translator as t;

/**
 * Class NavigationLink
 * @package GuzabaPlatform\Navigation\Models
 * @property int page_id
 * @property ?int page_group_id
 * @property string page_name
 */
class Page extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table' => 'cms_pages',
        'route' => '/admin/cms/page',//to be used for editing and deleting
    ];

    protected const CONFIG_RUNTIME = [];


    /**
     * To be used when the page group is to be set from public source (front-end)
     * Otherwise page_group_id can be used
     * @var ?string
     */
    public ?string $page_group_uuid = NULL;

    /**
     * Used to provide the content to the PageContent object
     * @var string
     */
    public string $page_content = '';

    protected string $original_page_content = '';

    protected function _after_read(): void
    {
        //get the latest version for which the user has permission to read
        if (!$this->is_new()) {
            $contents = PageContent::get_data_by( ['page_id' => $this->page_id], $offset = 0,$limit = 1, $use_like = FALSE, $sort_by = 'page_content_id', $sort_desc = TRUE );
            if (isset($contents[0])) {
                $this->original_page_content = $this->page_content = $contents[0]['page_content_data'];
            }
        }

        if ($this->page_group_id) {
            $PageGroup = new static($this->page_group_id);
            $this->page_group_uuid = $PageGroup->get_uuid();
        }
    }

    public static function create(?int $page_group_id, string $page_name, string $page_content): self
    {
        $Page = new static();
        $Page->page_group_id = $page_group_id;
        $Page->page_name = $page_name;
        $Page->page_content = $page_content;
        $Page->write();
        return $Page;
    }

    protected function _before_write(): void
    {
        if (!$this->page_group_id) {
            if ($this->page_group_uuid) {
                if (GeneralUtil::is_uuid($this->page_group_uuid)) {
                    try {
                        $PageGroup = new static($this->page_group_uuid);
                        $this->page_group_id = $PageGroup->get_id();
                    } catch (RecordNotFoundException $Exception) {
                        throw new ValidationFailedException($this, 'page_group_uuid', sprintf(t::_('There is no page group with the provided UUID %s.'), $this->page_group_uuid) );
                    } catch (PermissionDeniedException $Exception) {
                        throw new ValidationFailedException($this, 'page_group_uuid', sprintf(t::_('You are not allowed to read the page group with UUID %s.'), $this->page_group_uuid) );
                    }
                } else {
                    throw new ValidationFailedException($this, 'page_group_uuid', sprintf(t::_('The provided page group UUID %s is not a valid UUID.'), $this->page_group_uuid) );
                }
            } else {
                $this->page_group_id = NULL;
            }
        }
    }

    protected function _after_write(): void
    {
        //create a new page content only if there was a change
        if ($this->page_content !== $this->original_page_content || $this->was_new()) {
            $PageContent = PageContent::create($this->page_id, $this->page_content);
        }
    }

    protected function _before_delete(): void
    {
        //try to delete all content records before deleting the page
        //this will try to delete all the content records to which it has access
        //if there are remaining content records the deletion of the Page will fail due to the Foreign Key contraint restrict
        $contents = PageContent::get_data_by( ['page_id' => $this->page_id] );
        foreach ($contents as $PageContent) {
            $PageContent->delete();
        }
    }
}