<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Xsitemap;

// require_once __DIR__ . '/../class/Utility.php';

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !$GLOBALS['xoopsUser']->IsAdmin()
) {
    exit('Restricted access' . PHP_EOL);
}

/**
 *
 * Prepares system prior to attempting to update module
 *
 * @param XoopsModule $module
 *
 * @return bool true if successfully ready to update module, false if not
 */
function xoops_module_pre_update_xsitemap(\XoopsModule $module)
{
    /** @var Xsitemap\Helper $helper */
    /** @var Xsitemap\Utility $utility */
    $moduleDirName = basename(dirname(__DIR__));
    $helper       = Xsitemap\Helper::getInstance();
    $utility      = new Xsitemap\Utility();

    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);
    return $xoopsSuccess && $phpSuccess;
}

/**
 *
 * Functions to upgrade from previous version of the module
 *
 * @param XoopsModule $module
 * @param int|null        $previousVersion
 * @return bool true if successfully updated module, false if not
 * @internal param int $curr_version version number of module currently installed
 *
 */
function xoops_module_update_xsitemap(\XoopsModule $module, $previousVersion = null)
{
    /*======================================================================
        //----------------------------------------------------------------
        // Remove xSitemap uploads folder (and all subfolders) if they exist
        //----------------------------------------------------------------*
        $utility = ucfirst($moduleDirName) . 'Utility';
        if (!class_exists($utility)) {
            xoops_load('utility', $moduleDirName);
        }

        // Recursively delete directories
        $xsUploadDir = realpath(XOOPS_UPLOAD_PATH . "/" . $module->dirname());
        $success = $utility::rrmdir($xsUploadDir);
        if (true !== $success) {
            \Xmf\Language::load('admin', $module->dirname());
            $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_DEL_PATH, $xsUploadDir));
        }
        return $success;
    ======================================================================*/

    $moduleDirName = basename(dirname(__DIR__));
    $capsDirName   = strtoupper($moduleDirName);

    /** @var Xsitemap\Helper $helper */
    /** @var Xsitemap\Utility $utility */
    /** @var Xsitemap\Common\Configurator $configurator */
    $helper  = Xsitemap\Helper::getInstance();
    $utility = new Xsitemap\Utility();
    $configurator = new Xsitemap\Common\Configurator();

    //-----------------------------------------------------------------------
    // Upgrade for Xsitemap < 1.54
    //-----------------------------------------------------------------------

    $success = true;

    $helper->loadLanguage('modinfo');
    $helper->loadLanguage('admin');

    if ($previousVersion < 154) {
        //----------------------------------------------------------------
        // Remove previous css & images directories since they've been relocated to ./assets
        // Also remove uploads directories since they're no longer used
        //----------------------------------------------------------------
        $old_directories = [
            $helper->path('css/'),
            $helper->path('js/'),
            $helper->path('images/'),
            XOOPS_UPLOAD_PATH . '/' . $module->dirname()
        ];
        foreach ($old_directories as $old_dir) {
            $dirInfo = new \SplFileInfo($old_dir);
            if ($dirInfo->isDir()) {
                // The directory exists so delete it
                if (false === $utility::rrmdir($old_dir)) {
                    $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_DEL_PATH, $old_dir));
                    return false;
                }
            }
            unset($dirInfo);
        }

        //-----------------------------------------------------------------------
        // Remove ./template/*.html (except index.html) files since they've
        // been replaced by *.tpl files
        // Note: this will also remove /template/xsitemap_style.html since it's no longer used
        //-----------------------------------------------------------------------
        $path       = $helper->path('templates/');
        $unfiltered = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $iterator   = new RegexIterator($unfiltered, "/.*\.html/");
        foreach ($iterator as $name => $fObj) {
            if ($fObj->isFile() && ('index.html' !== $fObj->getFilename())) {
                if (false === ($success = unlink($fObj->getPathname()))) {
                    $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_REMOVE, $fObj->getPathname()));
                    return false;
                }
            }
        }

        //-----------------------------------------------------------------------
        // Now remove a some misc files that were renamed or deprecated
        //-----------------------------------------------------------------------
        $oldFiles = [
            $helper->path('include/install.php'),
            $helper->path('class/module.php'),
            $helper->path('class/menu.php')
        ];
        foreach ($oldFiles as $file) {
            if (is_file($file)) {
                if (false === ($delOk = unlink($file))) {
                    $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_REMOVE, $file));
                }
                $success = $success && $delOk;
            }
        }
    }
    return $success;
}
