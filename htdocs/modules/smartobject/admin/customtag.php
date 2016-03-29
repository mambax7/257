<?php

/**
* $Id: customtag.php 159 2007-12-17 16:44:05Z malanciault $
* Module: Class_Booking
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editcustomtag($showmenu = false, $customtagid = 0, $clone=false)
{
    global $smartobject_customtag_handler;

    $customtagObj = $smartobject_customtag_handler->get($customtagid);

    if (!$clone && !$customtagObj->isNew()){

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

include_once("admin_header.php");
smart_loadLanguageFile('smartobject', 'customtag');

include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
include_once SMARTOBJECT_ROOT_PATH."class/customtag.php";
$smartobject_customtag_handler = xoops_getmodulehandler('customtag');

$indexAdmin = new ModuleAdmin();


$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
    case "mod":

        $customtagid = isset($_GET['customtagid']) ? intval($_GET['customtagid']) : 0 ;

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('customtag.php');

        editcustomtag(true, $customtagid);
        break;

    case "clone":

        $customtagid = isset($_GET['customtagid']) ? intval($_GET['customtagid']) : 0 ;

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('customtag.php');

        editcustomtag(true, $customtagid, true);
        break;

    case "addcustomtag":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_customtag_handler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_CUSTOMTAGS_CREATED, _AM_SOBJECT_CUSTOMTAGS_MODIFIED);
        break;

    case "del":

        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_customtag_handler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('customtag.php');
        $indexAdmin->addItemButton(_AM_SOBJECT_CUSTOMTAGS_CREATE, 'customtag.php?op=mod', 'add' , '');
        echo $indexAdmin->renderButton('left', '');

        //smart_adminMenu(2, _AM_SOBJECT_CUSTOMTAGS);

        smart_collapsableBar('createdcustomtags', _AM_SOBJECT_CUSTOMTAGS, _AM_SOBJECT_CUSTOMTAGS_DSC);

        include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
        $objectTable = new SmartObjectTable($smartobject_customtag_handler);
        $objectTable->addColumn(new SmartObjectColumn('name', 'left', 150, 'getCustomtagName'));
        $objectTable->addColumn(new SmartObjectColumn('description', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('language', 'center', 150));

//      $objectTable->addCustomAction('getCreateItemLink');
//      $objectTable->addCustomAction('getCreateAttributLink');

//      $objectTable->addIntroButton('addcustomtag', 'customtag.php?op=mod', _AM_SOBJECT_CUSTOMTAGS_CREATE); //mb button


/*
        $criteria_upcoming = new CriteriaCompo();
        $criteria_upcoming->add(new Criteria('start_date', time(), '>'));
        $objectTable->addFilter(_AM_SOBJECT_FILTER_UPCOMING, array(
                                    'key' => 'start_date',
                                    'criteria' => $criteria_upcoming
        ));

        $criteria_last7days = new CriteriaCompo();
        $criteria_last7days->add(new Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
        $criteria_last7days->add(new Criteria('start_date', time(), '<'));
        $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST7DAYS, array(
                                    'key' => 'start_date',
                                    'criteria' => $criteria_last7days
        ));

        $criteria_last30days = new CriteriaCompo();
        $criteria_last30days->add(new Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
        $criteria_last30days->add(new Criteria('start_date', time(), '<'));
        $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST30DAYS, array(
                                    'key' => 'start_date',
                                    'criteria' => $criteria_last30days
        ));
*/
        $objectTable->addQuickSearch(array('title', 'summary', 'description'));
        $objectTable->addCustomAction('getCloneLink');

        $objectTable->render();

        echo "<br />";
        smart_close_collapsable('createdcustomtags');
        echo "<br>";

        break;
}

//smart_modFooter();
//xoops_cp_footer();
include_once 'admin_footer.php';