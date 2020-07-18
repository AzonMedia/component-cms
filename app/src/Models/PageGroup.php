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
 * @property null|int parent_page_group_id
 * @property string page_group_name
 */
class PageGroup extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table' => 'cms_page_groups',
        'route' => '/admin/cms/page-group',//to be used for editing and deleting
    ];

    protected const CONFIG_RUNTIME = [];


    /**
     * To be used when the parent page group is to be set from public source (front-end)
     * Otherwise parent_page_group_id can be used
     * @var ?string
     */
    public ?string $parent_page_group_uuid = NULL;

    /**
     * Returns an associative array with page group UUID=>name with the path to this page group
     * @return array
     */
    public function get_path(): array
    {
        $path = [];
        $PageGroup = $this;
        do {
            $path[$PageGroup->get_uuid()] = $PageGroup->page_group_name;
            $PageGroup = $this->get_parent_page_group();
        } while ($PageGroup);
        $path = array_reverse($path);
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

    protected function _validate_page_group_name(): ?ValidationFailedExceptionInterface
    {
        if (!$this->page_group_name) {
            return new ValidationFailedException($this, 'page_group_name', sprintf(t::_('There is no page_group_name provided.')));
        }
        //check for a sibling (page group at the same level) with the same name
        try {
            $PageGroup = new static(['parent_page_group_id' => $this->parent_page_group_id, 'page_group_name' => $this->page_group_name]);
            return new ValidationFailedException($this, 'page_group_name', sprintf(t::_('There is already a Page Group named "%s" at the same level.'), $this->page_group_name) );
        } catch (RecordNotFoundException $Exception) {
            //it is OK
        } catch (PermissionDeniedException $Exception) {
            //if exception is thrown this is a side channel leak - exposes the information that there is already a page group with the same name but not accessible
            //SECURITY
            //return new ValidationFailedException();
            //so instead of forbidding this it can be allowed (having two page groups with the same name is a UI problem not a technical one)
        }

        return NULL;
    }

    protected function _before_delete(): void
    {
        //delete all contained page groups
        $page_groups = PageGroup::get_by( ['parent_page_group_id' => $this->get_id()] );
        foreach ($page_groups as $PageGroup) {
            $PageGroup->delete();
        }

        //delete all contained pages
        $pages = Page::get_by( ['page_group_id' => $this->get_id()] );
        foreach ($pages as $Page) {
            $Page->delete();
        }
    }



}