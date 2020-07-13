<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Controllers;


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
            '/admin/cms/{page_group_uuid}' => [
                Method::HTTP_GET => [self::class, 'main']
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];

        if (!$page_group_uuid) {
            $page_group_uuid = NULL;
        }

        $struct['page_groups'] = PageGroups::get_by_page_group_uuid($page_group_uuid);
        //$struct['pages'] = \GuzabaPlatform\Cms\Models\Pages::get_by_page_group_uuid($page_group_uuid);

        return self::get_structured_ok_response($struct);

    }
}