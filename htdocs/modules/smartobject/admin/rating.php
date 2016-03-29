<?php

/**
* $Id: rating.php 159 2007-12-17 16:44:05Z malanciault $
* Module: Class_Booking
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editclass($showmenu = false, $ratingid = 0)
{
    global $smartobject_rating_handler;

    $ratingObj = $smartobject_rating_handler->get($ratingid);

    if (!$ratingObj->isNew()){

        if ($showmenu) {
            //smart_adminMenu(4, _AM_SOBJECT_RATINGS . " > " . _AM_SOBJECT_EDITING);
        }
        smart_collapsableBar('ratingedit', _AM_SOBJECT_RATINGS_EDIT, _AM_SOBJECT_RATINGS_EDIT_INFO);

        $sform = $ratingObj->getForm(_AM_SOBJECT_RATINGS_EDIT, 'addrating');
        $sform->display();
        smart_close_collapsable('ratingedit');
    } else {
        $ratingObj->hideFieldFromForm(array('item', 'itemid', 'uid', 'date', 'rate'));

        if (isset($_POST['op'])) {
            $controller = new SmartObjectController($smartobject_rating_handler);
            $controller->postDataToObject($ratingObj);

            if ($_POST['op'] == 'changedField') {
                switch($_POST['changedField']) {
                    case 'dirname' :
                        $ratingObj->showFieldOnForm(array('item', 'itemid', 'uid', 'date', 'rate'));
                    break;
                }
            }
        }

        if ($showmenu) {
            //smart_adminMenu(4, _AM_SOBJECT_RATINGS . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        smart_collapsableBar('ratingcreate', _AM_SOBJECT_RATINGS_CREATE, _AM_SOBJECT_RATINGS_CREATE_INFO);
        $sform = $ratingObj->getForm(_AM_SOBJECT_RATINGS_CREATE, 'addrating');
        $sform->display();
        smart_close_collapsable('ratingcreate');
    }


}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
include_once SMARTOBJECT_ROOT_PATH."class/rating.php";
$smartobject_rating_handler = xoops_getmodulehandler('rating');
$indexAdmin = new ModuleAdmin();

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
    case "mod":
    case "changedField";

        $ratingid = isset($_GET['ratingid']) ? intval($_GET['ratingid']) : 0 ;

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('rating.php');

        editclass(true, $ratingid);
        break;

    case "addrating":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_rating_handler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_RATINGS_CREATED, _AM_SOBJECT_RATINGS_MODIFIED, SMARTOBJECT_URL . 'admin/rating.php');

        break;

    case "del":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_rating_handler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        echo $indexAdmin->addNavigation('rating.php');

        //smart_adminMenu(4, _AM_SOBJECT_RATINGS);

        smart_collapsableBar('createdratings', _AM_SOBJECT_RATINGS, _AM_SOBJECT_RATINGS_DSC);

        include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
        $objectTable = new SmartObjectTable($smartobject_rating_handler);
        $objectTable->addColumn(new SmartObjectColumn('name', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('dirname', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('item', 'left', false, 'getItemValue'));
        $objectTable->addColumn(new SmartObjectColumn('date', 'center', 150));
        $objectTable->addColumn(new SmartObjectColumn('rate', 'center', 40, 'getRateValue'));

//      $objectTable->addCustomAction('getCreateItemLink');
//      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addrating', 'rating.php?op=mod', _AM_SOBJECT_RATINGS_CREATE);
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

        $objectTable->render();

        echo "<br />";
        smart_close_collapsable('createdratings');
        echo "<br>";

        break;
}

//smart_modFooter();
//xoops_cp_footer();
include_once 'admin_footer.php';