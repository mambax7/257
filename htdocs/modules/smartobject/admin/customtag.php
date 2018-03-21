<?php

/**
 *
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $customtagid
 * @param bool $clone
 */

function editcustomtag($showmenu = false, $customtagid = 0, $clone = false)
{
    global $smartobjectCustomtagHandler;

    $customtagObj = $smartobjectCustomtagHandler->get($customtagid);

    if (!$clone && !$customtagObj->isNew()) {
        if ($showmenu) {
            //smart_adminMenu(2, _AM_SOBJECT_CUSTOMTAGS . " > " . _AM_SOBJECT_EDITING);
        }
        smart_collapsableBar('customtagedit', _AM_SOBJECT_CUSTOMTAGS_EDIT, _AM_SOBJECT_CUSTOMTAGS_EDIT_INFO);

        $sform = $customtagObj->getForm(_AM_SOBJECT_CUSTOMTAGS_EDIT, 'addcustomtag');
        $sform->display();
        smart_close_collapsable('customtagedit');
    } else {
        $customtagObj->setVar('customtagid', 0);
        $customtagObj->setVar('tag', '');

        if ($showmenu) {
            //smart_adminMenu(2, _AM_SOBJECT_CUSTOMTAGS . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        smart_collapsableBar('customtagcreate', _AM_SOBJECT_CUSTOMTAGS_CREATE, _AM_SOBJECT_CUSTOMTAGS_CREATE_INFO);
        $sform = $customtagObj->getForm(_AM_SOBJECT_CUSTOMTAGS_CREATE, 'addcustomtag');
        $sform->display();
        smart_close_collapsable('customtagcreate');
    }
}

require_once __DIR__ . '/admin_header.php';
smart_loadLanguageFile('smartobject', 'customtag');

require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/customtag.php';
$smartobjectCustomtagHandler = xoops_getModuleHandler('customtag');

$adminObject = \Xmf\Module\Admin::getInstance();

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':

        $customtagid = isset($_GET['customtagid']) ? (int)$_GET['customtagid'] : 0;

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        editcustomtag(true, $customtagid);
        break;

    case 'clone':

        $customtagid = isset($_GET['customtagid']) ? (int)$_GET['customtagid'] : 0;

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        editcustomtag(true, $customtagid, true);
        break;

    case 'addcustomtag':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectCustomtagHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_CUSTOMTAGS_CREATED, _AM_SOBJECT_CUSTOMTAGS_MODIFIED);
        break;

    case 'del':

        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectCustomtagHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_SOBJECT_CUSTOMTAGS_CREATE, 'customtag.php?op=mod', 'add', '');
        $adminObject->displayButton('left', '');

        //smart_adminMenu(2, _AM_SOBJECT_CUSTOMTAGS);

        smart_collapsableBar('createdcustomtags', _AM_SOBJECT_CUSTOMTAGS, _AM_SOBJECT_CUSTOMTAGS_DSC);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new SmartObjectTable($smartobjectCustomtagHandler);
        $objectTable->addColumn(new SmartObjectColumn('name', 'left', 150, 'getCustomtagName'));
        $objectTable->addColumn(new SmartObjectColumn('description', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('language', 'center', 150));

        //      $objectTable->addCustomAction('getCreateItemLink');
        //      $objectTable->addCustomAction('getCreateAttributLink');

        //      $objectTable->addIntroButton('addcustomtag', 'customtag.php?op=mod', _AM_SOBJECT_CUSTOMTAGS_CREATE); //mb button

        /*
                $criteria_upcoming = new \CriteriaCompo();
                $criteria_upcoming->add(new \Criteria('start_date', time(), '>'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_UPCOMING, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_upcoming
                ));

                $criteria_last7days = new \CriteriaCompo();
                $criteria_last7days->add(new \Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
                $criteria_last7days->add(new \Criteria('start_date', time(), '<'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST7DAYS, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_last7days
                ));

                $criteria_last30days = new \CriteriaCompo();
                $criteria_last30days->add(new \Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
                $criteria_last30days->add(new \Criteria('start_date', time(), '<'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST30DAYS, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_last30days
                ));
        */
        $objectTable->addQuickSearch(['title', 'summary', 'description']);
        $objectTable->addCustomAction('getCloneLink');

        $objectTable->render();

        echo '<br>';
        smart_close_collapsable('createdcustomtags');
        echo '<br>';

        break;
}

//smart_modFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
