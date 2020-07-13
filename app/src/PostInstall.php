<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use GuzabaPlatform\Installer\Installer;
use GuzabaPlatform\Installer\Interfaces\PostInstallHookInterface;

/**
 * Class PostInstall
 * @package GuzabaPlatform\Cms
 * Installs the NPM dependencies
 * Install the DB tables
 *
 */
class PostInstall implements PostInstallHookInterface
{
    public static function post_install_hook(Installer $Installer, InstalledRepositoryInterface $Repo, PackageInterface $Package) : void
    {
        $package_install_dir = $Installer->getInstallPath($Package);
        $guzaba_platform_dir = realpath($package_install_dir.'/../../..');

        //install the NPM dependencies
        $public_src_dir = $guzaba_platform_dir.'/app/public_src';
        $cwd = getcwd();
        chdir($public_src_dir);
        `npm install vue-quill-editor --save`;
        chdir($cwd);

        //install DB tables

    }
}
