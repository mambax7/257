<?php
/**
 * uninstall.php - cleanup on module uninstall
 *
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            https://xoops.org XOOPS
 */

/**
 * @internal {Make sure you PROTECT THIS FILE}
 */

use \XoopsModules\Xsitemap;

if ((!defined('XOOPS_ROOT_PATH'))
    || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !$GLOBALS['xoopsUser']->isAdmin()
) {
    exit('Restricted access' . PHP_EOL);
}



/**
 *
 * Function to perform before module uninstall
 *
 * @param XoopsModule $module
 *
 * @return bool true if successfully executed, false if not
 */
function xoops_module_pre_uninstall_xsitemap(\XoopsModule $module)
{
    return true;
}

/**
 *
 * Function to complete upon module uninstall
 *
 * @param XoopsModule $module
 *
 * @return bool true if successfully executed uninstall of module, false if not
 */
function xoops_module_uninstall_xsitemap(\XoopsModule $module)
{
    //    return true;
    $moduleDirName = $module->getVar('dirname');
    $helper      = \Xmf\Module\Helper::getHelper($moduleDirName);
    /** @var Xsitemap\Utility $utility */
    $utility = new Xsitemap\Utility();

//    if (!class_exists($utility)) {
//        xoops_load('utility', $moduleDirName);
//    }

    $success = true;
    $helper->loadLanguage('admin');

    //------------------------------------------------------------------
    // Remove xSitemap uploads folder (and all subfolders) if they exist
    //------------------------------------------------------------------

    $old_directories = [$GLOBALS['xoops']->path("uploads/{$moduleDirName}")];
    foreach ($old_directories as $old_dir) {
        $dirInfo = new \SplFileInfo($old_dir);
        if ($dirInfo->isDir()) {
            // The directory exists so delete it
            if (false === $utility::rrmdir($old_dir)) {
                $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_DEL_PATH, $old_dir));
                $success = false;
            }
        }
        unset($dirInfo);
    }
    //------------------------------------------------------------------
    // Remove xsitemap.xml from XOOPS root folder if it exists
    //------------------------------------------------------------------
    $xmlfile = $GLOBALS['xoops']->path('xsitemap.xml');
    if (is_file($xmlfile)) {
        if (false === ($delOk = unlink($xmlfile))) {
            $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_REMOVE, $xmlfile));
        }
    }
    return $success && $delOk;
}
