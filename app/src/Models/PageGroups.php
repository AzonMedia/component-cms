<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Models;


use Guzaba2\Base\Base;

class PageGroups extends Base
{
    public static function get_by_group(?int $parent_page_group_id, int $offset = 0, int $limit = 0): array
    {
        return Page::get_data_by( ['parent_page_group_id' => $parent_page_group_id], $offset, $limit, $use_like = FALSE, 'page_group_name');
    }
}