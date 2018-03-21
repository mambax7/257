<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Publisher
 *
 * @copyright    The XOOPS Project (https://xoops.org)
 * @license      GNU GPL (http://www.gnu.org/licenses/gpl-2.0.html/)
 * @package      Publisher
 * @since        1.0
 * @author       Mage, Mamba
 */

use XoopsModules\Publisher;
use XoopsModules\Publisher\versioncontrol;

require_once __DIR__ . '/admin_header.php';
//require_once dirname(__DIR__) . '/class/Utility.php';

xoops_cp_header();
$helper = Publisher\Helper::getInstance();
$helper->loadLanguage('main');
$adminObject = \Xmf\Module\Admin::getInstance();

/*
foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
    Publisher\Utility::createFolder($uploadFolders[$i]);
    $adminObject->addConfigBoxLine($uploadFolders[$i], 'folder');
    //    $adminObject->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

//copy blank.png files, if needed
$file = PUBLISHER_ROOT_PATH . '/assets/images/blank.png';
foreach (array_keys($copyFiles) as $i) {
    $dest = $copyFiles[$i] . '/blank.png';
    Publisher\Utility::copyFile($file, $dest);
}
*/


$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once __DIR__ . '/../testdata/index.php';

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');

    //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');

    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------


require_once __DIR__ . '/VersionControl.php';
require_once __DIR__ . '/VersionControlMySQL.php';


/*
*   define a random content-object containing:
*       - a unique object-identifier (like a unique id, a unique string, or what ever)
*       - some data as array (e.g. an title, and description and some text)
*       - an editor id, who created the object (e.g. an editorial journalist)
*       - an comment as a description of the content
*/
$sUniqueObjectIdentifier = md5('asdfiupweoiraoidfjasoldfjaef');
$aObjectData    =   [
    2,'a',
    3,'b',
    4,'c'
];
$iEditorId  =   1;
$sComment   =   'this is a random content object';


/*
*   now initiate a new versioncontrol-MYSQL object
*/
$oVC = new VersionControlMySQL();
try {
    /*
    *   try to submit the content-object to the repository
    */
    $oVC->submitVersion($sUniqueObjectIdentifier, $aObjectData, $iEditorId, $sComment);
} catch (ObjectVersionAlreadyExistsException $e) {
    /*
    *   the first time, this error will NOT occur.
    *   after the content-object has been submitted to the repository,
    *   the versioncontrol will throw the error "Object Already Exists",
    *   which means nothing more than, "there is already an identical object in the repository"
    */

    // return the latest version of our content-object
    echo 'This is the latest version available in the versioncontrol-repository:<br/>';
    print_r(
        $oVC->getLatestVersion($sUniqueObjectIdentifier)
    );
} catch (Exception $e2) {
    /*
    *   another error occured
    */
    echo $e2->getMessage();
}


require_once __DIR__ . '/admin_footer.php';
