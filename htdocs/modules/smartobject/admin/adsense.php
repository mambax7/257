<?php

/**
* $Id: adsense.php 159 2007-12-17 16:44:05Z malanciault $
* Module: Class_Booking
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editclass($showmenu = false, $adsenseid = 0, $clone=false)
{
    global $smartobject_adsense_handler;

    $adsenseObj = $smartobject_adsense_handler->get($adsenseid);

    if (!$clone && !$adsenseObj->isNew()){

        if ($showmenu) {
            //smart_adminMenu(3, _AM_SOBJECT_ADSENSES . " > " . _AM_SOBJECT_EDITING);
        }
        smart_collapsableBar('adsenseedit', _AM_SOBJECT_ADSENSES_EDIT, _AM_SOBJECT_ADSENSES_EDIT_INFO);

        $sform = $adsenseObj->getForm(_AM_SOBJECT_ADSENSES_EDIT, 'addadsense');
        $sform->display();
        smart_close_collapsable('adsenseedit');
    } else {
        $adsenseObj->setVar('adsenseid', 0);
        $adsenseObj->setVar('tag', '');

        if ($showmenu) {
            //smart_adminMenu(3, _AM_SOBJECT_ADSENSES . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        smart_collapsableBar('adsensecreate', _AM_SOBJECT_ADSENSES_CREATE, _AM_SOBJECT_ADSENSES_CREATE_INFO);
        $sform = $adsenseObj->getForm(_AM_SOBJECT_ADSENSES_CREATE, 'addadsense', false, false, false, true);
        $sform->display();
        smart_close_collapsable('adsensecreate');
    }
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
include_once SMARTOBJECT_ROOT_PATH."class/adsense.php";
$smartobject_adsense_handler = xoops_getmodulehandler('adsense');
smart_loadLanguageFile('smartobject', 'adsense');
$indexAdmin = new ModuleAdmin();

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
    case "mod":

        $adsenseid = isset($_GET['adsenseid']) ? intval($_GET['adsenseid']) : 0 ;

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('adsense.php');

        editclass(true, $adsenseid);
        break;

    case "clone":

        $adsenseid = isset($_GET['adsenseid']) ? intval($_GET['adsenseid']) : 0 ;

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('adsense.php');

        editclass(true, $adsenseid, true);
        break;

    case "addadsense":
        if(@include_once SMARTOBJECT_ROOT_PATH . "include/captcha/captcha.php") {
            $xoopsCaptcha = XoopsCaptcha::instance();
            if(! $xoopsCaptcha->verify() ) {
                redirect_header('javascript:history.go(-1);', 3, $xoopsCaptcha->getMessage());
                exit;
            }
        }
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_adsense_handler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_ADSENSES_CREATED, _AM_SOBJECT_ADSENSES_MODIFIED);
        break;

    case "del":

        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_adsense_handler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('adsense.php');

        //smart_adminMenu(3, _AM_SOBJECT_ADSENSES);

        smart_collapsableBar('createdadsenses', _AM_SOBJECT_ADSENSES, _AM_SOBJECT_ADSENSES_DSC);

        include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
        $objectTable = new SmartObjectTable($smartobject_adsense_handler);
        $objectTable->addColumn(new SmartObjectColumn('description', 'left'));
        $objectTable->addColumn(new SmartObjectColumn(_AM_SOBJECT_ADSENSE_TAG, 'center', 200, 'getXoopsCode'));

//      $objectTable->addCustomAction('getCreateItemLink');
//      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addadsense', 'adsense.php?op=mod', _AM_SOBJECT_ADSENSES_CREATE);
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
        smart_close_collapsable('createdadsenses');
        echo "<br>";

        break;
}

//smart_modFooter();
//xoops_cp_footer();
include_once 'admin_footer.php';