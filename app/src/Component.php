<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms;

use Guzaba2\Base\Exceptions\RunTimeException;
use GuzabaPlatform\Components\Base\BaseComponent;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInitializationInterface;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInterface;

/**
 * Class Component
 * @package Azonmedia\Tags
 */
class Component extends BaseComponent implements ComponentInterface, ComponentInitializationInterface
{

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'FrontendRouter',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    protected const COMPONENT_NAME = "CMS";
    //https://components.platform.guzaba.org/component/{vendor}/{component}
    protected const COMPONENT_URL = 'https://components.platform.guzaba.org/component/guzaba-platform/cms';
    //protected const DEV_COMPONENT_URL//this should come from composer.json
    protected const COMPONENT_NAMESPACE = 'GuzabaPlatform\\Cms';
    protected const COMPONENT_VERSION = '0.0.1';//TODO update this to come from the Composer.json file of the component
    protected const VENDOR_NAME = 'Azonmedia';
    protected const VENDOR_URL = 'https://azonmedia.com';
    protected const ERROR_REFERENCE_URL = 'https://github.com/AzonMedia/component-cms/tree/master/docs/ErrorReference/';

    /**
     * Must return an array of the initialization methods (method names or description) that were run.
     * @return array
     * @throws RunTimeException
     */
    public static function run_all_initializations() : array
    {
        self::register_routes();
        return ['register_routes'];
    }


    /**
     * @throws RunTimeException
     */
    public static function register_routes() : void
    {
        $FrontendRouter = self::get_service('FrontendRouter');
        $additional = [
            'name'  => 'CMS',
            'meta' => [
                'in_navigation' => TRUE, //to be shown in the admin navigation
                //'additional_template' => '@GuzabaPlatform.Cms/CmsNavigationHook.vue',//here the list of classes will be expanded
            ],
        ];
        $FrontendRouter->{'/admin'}->add('cms', '@GuzabaPlatform.Cms/CmsAdmin.vue' ,$additional);

        $additional = [
            'name'  => 'CMS Page Group',
        ];
        //$FrontendRouter->{'/admin'}->add('cms/*', '@GuzabaPlatform.Cms/CmsAdmin.vue', $additional);// use with this.$route.params.pathMatch
        $FrontendRouter->{'/admin'}->add('cms/:page_group_uuid', '@GuzabaPlatform.Cms/CmsAdmin.vue', $additional);// use with this.$route.params.page_group_uuid

        $additional = [
            'name'  => 'CMS Page',
        ];
        //$FrontendRouter->{'/admin'}->add('cms/*', '@GuzabaPlatform.Cms/CmsAdmin.vue', $additional);// use with this.$route.params.pathMatch
        $FrontendRouter->{'/admin'}->add('cms/page/:page_uuid', '@GuzabaPlatform.Cms/CmsPageAdmin.vue', $additional);// use with this.$route.params.page_group_uuid
    }
}