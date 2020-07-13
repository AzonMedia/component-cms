<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Models;


use Guzaba2\Base\Base;

class Pages extends Base
{

    public static function get_by_page_group_id(?int $page_group_id, int $offset = 0, int $limit = 0): array
    {
        return Page::get_data_by( ['page_group_id' => $page_group_id], $offset, $limit, $use_like = FALSE, 'page_name');
    }
    public static function get_by_page_group_uuid(?string $parent_page_group_uuid, int $offset = 0, int $limit = 0): array
    {
        if ($parent_page_group_uuid) {
            $PageGroup = new PageGroup($parent_page_group_uuid);
            $parent_page_group_id = $PageGroup->get_id();
        } else {
            $parent_page_group_id = NULL;
        }
        return static::get_by_page_group_id($parent_page_group_id, $offset, $limit);
    }

}