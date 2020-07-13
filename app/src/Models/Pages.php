<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Models;


use Guzaba2\Base\Base;

class Pages extends Base
{

    public static function get_by_group(?int $page_group_id, int $offset = 0, int $limit = 0): array
    {
        return Page::get_data_by( ['page_group_id' => $page_group_id], $offset, $limit, $use_like = FALSE, 'page_name');
    }
}