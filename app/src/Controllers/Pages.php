<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Controllers;


use Azonmedia\Utilities\ArrayUtil;
use Azonmedia\Utilities\GeneralUtil;
use Guzaba2\Http\Method;
use GuzabaPlatform\Cms\Models\PageGroup;
use GuzabaPlatform\Cms\Models\PageGroups;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;

class Pages extends BaseController
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin/cms' => [
                Method::HTTP_GET => [self::class, 'main']
            ],
            '/admin/cms/page-groups' => [
                Method::HTTP_GET => [self::class, 'page_groups'] //not used by the front end
            ],
            '/admin/cms/pages' => [
                Method::HTTP_GET => [self::class, 'pages'] // not used by the front end
            ],
            '/admin/cms/{page_group_uuid}' => [
                Method::HTTP_GET => [self::class, 'main']
            ],
            //for retreiving a single page the route set in the Page model is used
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        $struct['page_groups'] = ArrayUtil::remove_columns_by_name(PageGroups::get_by_page_group_uuid($page_group_uuid), '/.*_id/' );
        $struct['pages'] = ArrayUtil::remove_columns_by_name( \GuzabaPlatform\Cms\Models\Pages::get_by_page_group_uuid($page_group_uuid) , '/.*_id/' );
        return self::get_structured_ok_response($struct);
    }

    public function pages(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        $struct['pages'] = \GuzabaPlatform\Cms\Models\Pages::get_by_page_group_uuid($page_group_uuid);
        return self::get_structured_ok_response($struct);
    }

    public function page_groups(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        $struct['page_groups'] = PageGroups::get_by_page_group_uuid($page_group_uuid);
        return self::get_structured_ok_response($struct);
    }
}