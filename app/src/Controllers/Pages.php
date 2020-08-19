<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Controllers;


use Azonmedia\Utilities\ArrayUtil;
use Azonmedia\Utilities\GeneralUtil;
use Guzaba2\Authorization\CurrentUser;
use Guzaba2\Http\Method;
use GuzabaPlatform\Cms\Models\Page;
use GuzabaPlatform\Cms\Models\PageContent;
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
            //no - it needs a custom controller because it must show the content revision history
            //there can be a simplified CMS option where the last revision is always published and it is loaded when the page is opened
            //it is best on page click to open the last revision this used can access and to have a separate icon for listing revisions
            //a dedicated controller is needed because it needs to return not only the page data but also the content (the last accessible revision)
            '/admin/cms/page/{page_uuid}/revisions/' => [
                Method::HTTP_GET => [self::class, 'page_revisions'],
            ],
            '/admin/cms/page/{page_uuid}/revision/{page_content_uuid}' => [ //the page_uuid is not really needed to access the revision but for future error reporting may be better to have it
                Method::HTTP_GET => [self::class, 'page_revision'],
            ],
        ],
        'services'      => [
            'CurrentUser'
        ]
    ];

    protected const CONFIG_RUNTIME = [];

    public const PAGE_GROUP_SEPARATOR = '/';

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

    public function page_revisions(string $page_uuid): ResponseInterface
    {
        $Page = new Page($page_uuid);
        $content_revisions = $Page->get_content_revisions_data();
        $struct = [];
        $struct['content_revisions'] = $content_revisions;
        return self::get_structured_ok_response($struct);
    }

    public function page_revision(string $page_uuid, string $page_content_uuid): ResponseInterface
    {
        $Page = new Page($page_uuid);//the page & page_uuid are not really needed but for structuring API is better to have it
        $PageContent = new PageContent($page_content_uuid);
        return self::get_structured_ok_response($PageContent);
    }

    /**
     * Returns all pages and pages groups in multidimensional array
     * @return ResponseInterface
     */
    public function get_all_pages(): ResponseInterface
    {
        $pages = Page::get_data_by( [] );
        $groups = PageGroup::get_data_by( [], 0, 0, false, 'parent_page_group_id' );
        $data = [];

        $FindPageGroup = static function(int $page_group_id) use (&$FindPageGroup, $groups): array
        {
            
        };

        foreach ($groups as $group) {
            if ($group['parent_group_id']) {
                
            } else {
                $data[$group['page_group_name']] = [];
            }
        }
    }

    /**
     * Returns all pages in two-dimensional array by page groups.
     * If the page groups are nested in more levels these are flattened to a single group and names separated by self::PAGE_GROUP_SEPARATOR
     * @return ResponseInterface
     */
    public function get_pages_for_navigation(): ResponseInterface
    {

    }
}