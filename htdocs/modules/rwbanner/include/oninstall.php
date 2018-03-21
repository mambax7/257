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

use XoopsModules\Rwbanner;

//require_once __DIR__ . '/setup.php';

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_rwbanner(\XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    /** @var Rwbanner\Utility $utility */
    $utility     = new Rwbanner\Utility();
    //check for minimum XOOPS version
    if (!$utility::checkVerXoops($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$utility::checkVerPhp($module)) {
        return false;
    }

    $mod_tables =& $module->getInfo('tables');
    foreach ($mod_tables as $table) {
        $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
    }

    return true;
}

/**
 *
 * Performs tasks required during installation of the module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_rwbanner(\XoopsModule $module)
{
    require_once  __DIR__ . '/../../../mainfile.php';
    require_once  __DIR__ . '/../include/config.php';

    $moduleDirName = basename(dirname(__DIR__));
    $helper = Rwbanner\Helper::getInstance();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');

    $configurator = new Rwbanner\Common\Configurator();
    /** @var Rwbanner\Utility $utility */
    $utility     = new Rwbanner\Utility();

    // default Permission Settings ----------------------
    global $xoopsModule;
    $moduleId     = $xoopsModule->getVar('mid');
    $moduleId2    = $helper->getModule()->mid();
    $gpermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $gpermHandler->addRight($moduleDirName . '_approve', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }
    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $xoopsDB->queryF($sql);

    return true;
}

//======================================================

$indexFile = 'index.html';
$blankFile = $GLOBALS['xoops']->path('modules/randomquote/assets/images/icons/blank.gif');

//Creation du dossier "uploads" pour le module à la racine du site
$module_uploads = $GLOBALS['xoops']->path('uploads/randomquote');
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, $GLOBALS['xoops']->path('uploads/randomquote/index.html'));

//Creation du fichier citas dans uploads
$module_uploads = $GLOBALS['xoops']->path('uploads/randomquote/citas');
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, $GLOBALS['xoops']->path('uploads/randomquote/citas/index.html'));
