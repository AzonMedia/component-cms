<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Controllers;


use Azonmedia\Utilities\ArrayUtil;
use Azonmedia\Utilities\GeneralUtil;
use Guzaba2\Authorization\CurrentUser;
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
        'services'      => [
            'CurrentUser'
        ]
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        /** @var CurrentUser $CurrentUser */
        $CurrentUser = self::get_service('CurrentUser');
        $date_time_format = $CurrentUser->get()->get_date_time_format();
        $struct['page_groups'] = ArrayUtil::frontify(PageGroups::get_by_page_group_uuid($page_group_uuid),  $date_time_format );
        $struct['pages'] = ArrayUtil::frontify( \GuzabaPlatform\Cms\Models\Pages::get_by_page_group_uuid($page_group_uuid), $date_time_format );
        if ($page_group_uuid) {
            $PageGroup = new PageGroup($page_group_uuid);
            $struct['page_group_path'] = $PageGroup->get_path();
        } else {
            $struct['page_group_path'] = [];
        }

        return self::get_structured_ok_response($struct);
    }

    public function pages(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        /** @var CurrentUser $CurrentUser */
        $CurrentUser = self::get_service('CurrentUser');
        $date_time_format = $CurrentUser->get()->get_date_time_format();
        $struct['pages'] = ArrayUtil::frontify( \GuzabaPlatform\Cms\Models\Pages::get_by_page_group_uuid($page_group_uuid), $date_time_format );
        return self::get_structured_ok_response($struct);
    }

    public function page_groups(?string $page_group_uuid = NULL): ResponseInterface
    {
        $struct = [];
        /** @var CurrentUser $CurrentUser */
        $CurrentUser = self::get_service('CurrentUser');
        $date_time_format = $CurrentUser->get()->get_date_time_format();
        $struct['page_groups'] = ArrayUtil::frontify(PageGroups::get_by_page_group_uuid($page_group_uuid),  $date_time_format );
        return self::get_structured_ok_response($struct);
    }
}