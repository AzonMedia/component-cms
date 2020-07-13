<?php
declare(strict_types=1);


namespace GuzabaPlatform\Cms\Models;

use Azonmedia\Utilities\GeneralUtil;
use Guzaba2\Authorization\Exceptions\PermissionDeniedException;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Orm\ActiveRecordCollection;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Orm\Interfaces\ValidationFailedExceptionInterface;
use Guzaba2\Orm\Store\Sql\Mysql;
use GuzabaPlatform\Platform\Application\BaseActiveRecord;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use Guzaba2\Translator\Translator as t;

/**
 * Class NavigationLink
 * @package GuzabaPlatform\Navigation\Models
 * @property int page_group_id
 * @property ?int parent_page_group_id
 * @property string page_group_name
 */
class PageGroup extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table' => 'cms_page_groups',
        'route' => '/admin/cms/page-group',//to be used for editing and deleting
    ];

    protected const CONFIG_RUNTIME = [];

    public function get_path(): array
    {
        $path = [];
        $PageGroup = $this;
        do {
            $path[] = $PageGroup->page_group_name;
            $PageGroup = $this->get_parent_page_group();
        } while ($PageGroup);
        return $path;
    }

    public function get_parent_page_group(): ?self
    {
        $ret = NULL;
        if ($this->parent_page_group_id) {
            $ret = new static($this->parent_page_group_id);
        }
        return $ret;
    }

    /**
     * To be used when the paret page group is to be set from public source (front-end)
     * Otherwise parent_page_group_id can be used
     * @var null
     */
    public ?string $parent_page_group_uuid = NULL;

    protected function _after_read(): void
    {
        if ($this->parent_page_group_id) {
            $ParentPageGroup = new static($this->parent_page_group_id);
            $this->parent_page_group_uuid = $ParentPageGroup->get_uuid();
        }
    }

    protected function _before_write(): void
    {

        if (!$this->parent_page_group_id) {
            if ($this->parent_page_group_uuid) {
                if (GeneralUtil::is_uuid($this->parent_page_group_uuid)) {
                    try {
                        $ParentPageGroup = new static($this->parent_page_group_uuid);
                        $this->parent_page_group_id = $ParentPageGroup->get_id();
                    } catch (RecordNotFoundException $Exception) {
                        throw new ValidationFailedException($this, 'parent_page_group_uuid', sprintf(t::_('There is no page group with the provided UUID %s.'), $this->parent_page_group_uuid) );
                    } catch (PermissionDeniedException $Exception) {
                        throw new ValidationFailedException($this, 'parent_page_group_uuid', sprintf(t::_('You are not allowed to read the page group with UUID %s.'), $this->parent_page_group_uuid) );
                    }
                } else {
                    throw new ValidationFailedException($this, 'parent_page_group_uuid', sprintf(t::_('The provided parent page group UUID %s is not a valid UUID.'), $this->parent_page_group_uuid) );
                }
            } else {
                $this->parent_page_group_id = NULL;
            }
        }
    }

    protected function _validate_parent_page_group_id(): ?ValidationFailedExceptionInterface
    {
        if ($this->parent_page_group_id !== NULL) {
            try {
                $ParentPageGroup = new static($this->parent_page_group_id);
            } catch (RecordNotFoundException $Exception) {
                return new ValidationFailedException($this, 'parent_page_group_id', sprintf(t::_('The provided parent_page_group_id %1$s does not exist.'), $this->parent_page_group_id ));
            }
        }
        return NULL;
    }





}