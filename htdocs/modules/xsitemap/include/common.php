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
 * @author     XOOPS Development Team
 */

use XoopsModules\Xsitemap;

include __DIR__ . '/../preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper   = strtoupper($moduleDirName); //$capsDirName

/** @var \XoopsDatabase $db */
/** @var Xsitemap\Helper $helper */
/** @var Xsitemap\Utility $utility */
$db      = \XoopsDatabaseFactory::getDatabaseConnection();
$helper  = Xsitemap\Helper::getInstance();
$utility = new Xsitemap\Utility();
//$configurator = new Xsitemap\Common\Configurator();

$helper->loadLanguage('common');

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    define($moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__)));
    define($moduleDirNameUpper . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_URL', XOOPS_URL . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_IMAGE_URL', constant($moduleDirNameUpper . '_URL') . '/assets/images/');
    define($moduleDirNameUpper . '_IMAGE_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/assets/images');
    define($moduleDirNameUpper . '_ADMIN_URL', constant($moduleDirNameUpper . '_URL') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/admin/');
    define($moduleDirNameUpper . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($moduleDirNameUpper . '_DIRNAME'));
    define($moduleDirNameUpper . '_ADMIN', constant($moduleDirNameUpper . '_URL') . '/admin/index.php');
    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}

/*
if (!defined('XSITEMAP_MODULE_PATH')) {
    define('XSITEMAP_DIRNAME', basename(dirname(__DIR__)));
    define('XSITEMAP_URL', XOOPS_URL . '/modules/' . XSITEMAP_DIRNAME);
    define('XSITEMAP_IMAGE_URL', XSITEMAP_URL . '/assets/images/');
    define('XSITEMAP_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . XSITEMAP_DIRNAME);
    define('XSITEMAP_IMAGE_PATH', XSITEMAP_ROOT_PATH . '/assets/images');
    define('XSITEMAP_ADMIN_URL', XSITEMAP_URL . '/admin/');
    define('XSITEMAP_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . XSITEMAP_DIRNAME);
    define('XSITEMAP_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . XSITEMAP_DIRNAME);
}
*/

$helper->loadLanguage('common');

//require_once XSITEMAP_ROOT_PATH . '/class/Utility.php';
//require_once XSITEMAP_ROOT_PATH . '/include/constants.php';
//require_once XSITEMAP_ROOT_PATH . '/include/seo_functions.php';
//require_once XSITEMAP_ROOT_PATH . '/class/metagen.php';
//require_once XSITEMAP_ROOT_PATH . '/class/session.php';
//require_once XSITEMAP_ROOT_PATH . '/class/xoalbum.php';
//require_once XSITEMAP_ROOT_PATH . '/class/request.php';


$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$debug = false;

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

// Local icons path
$GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
$GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);

//module handlers

/** @var Xsitemap\PluginHandler $pluginHandler */
$pluginHandler = $helper->getHandler('Plugin');
