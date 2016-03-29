<?php

/**
* $Id: currency.php 159 2007-12-17 16:44:05Z malanciault $
* Module: Class_Booking
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editclass($showmenu = false, $currencyid = 0)
{
    global $smartobject_currency_handler;

    $currencyObj = $smartobject_currency_handler->get($currencyid);

    if (!$currencyObj->isNew()){

        if ($showmenu) {
            //smart_adminMenu(5, _AM_SOBJECT_CURRENCIES . " > " . _AM_SOBJECT_EDITING);
        }
        smart_collapsableBar('currencyedit', _AM_SOBJECT_CURRENCIES_EDIT, _AM_SOBJECT_CURRENCIES_EDIT_INFO);

        $sform = $currencyObj->getForm(_AM_SOBJECT_CURRENCIES_EDIT, 'addcurrency');
        $sform->display();
        smart_close_collapsable('currencyedit');
    } else {
        if ($showmenu) {
            //smart_adminMenu(5, _AM_SOBJECT_CURRENCIES . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        smart_collapsableBar('currencycreate', _AM_SOBJECT_CURRENCIES_CREATE, _AM_SOBJECT_CURRENCIES_CREATE_INFO);
        $sform = $currencyObj->getForm(_AM_SOBJECT_CURRENCIES_CREATE, 'addcurrency');
        $sform->display();
        smart_close_collapsable('currencycreate');
    }
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
include_once SMARTOBJECT_ROOT_PATH."class/currency.php";
$smartobject_currency_handler = xoops_getmodulehandler('currency');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
    case "mod":
        $currencyid = isset($_GET['currencyid']) ? intval($_GET['currencyid']) : 0 ;

        smart_xoops_cp_header();

        editclass(true, $currencyid);
        break;

    case "updateCurrencies":

        if (!isset($_POST['SmartobjectCurrency_objects']) || count($_POST['SmartobjectCurrency_objects']) == 0) {
            redirect_header($smart_previous_page, 3, _AM_SOBJECT_NO_RECORDS_TO_UPDATE);
            exit;
        }

        if (isset($_POST['default_currency'])) {
            $new_default_currency = $_POST['default_currency'];
            $sql = 'UPDATE ' . $smartobject_currency_handler->table . ' SET default_currency=0';
            $smartobject_currency_handler->query($sql);
            $sql = 'UPDATE ' . $smartobject_currency_handler->table . ' SET default_currency=1 WHERE currencyid=' . $new_default_currency;
            $smartobject_currency_handler->query($sql);
        }

        /*
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('currencyid', '(' . implode(', ', $_POST['SmartobjectCurrency_objects']) . ')', 'IN'));
        $currenciesObj = $smartobject_currency_handler->getObjects($criteria, true);

        foreach($currenciesObj as $currencyid=>$currencyObj) {
            //$bookingObj->setVar('attended', isset($_POST['attended_' . $bookingid]) ? intval($_POST['attended_' . $bookingid]) : 0);
            $smartobject_currency_handler->insert($currencyObj);
        }
        */
        redirect_header($smart_previous_page, 3, _AM_SOBJECT_RECORDS_UPDATED);
        exit;

        break;

    case "addcurrency":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_currency_handler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_CURRENCIES_CREATED, _AM_SOBJECT_CURRENCIES_MODIFIED, SMARTOBJECT_URL . 'admin/currency.php');

        break;

    case "del":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartobject_currency_handler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();

        //smart_adminMenu(5, _AM_SOBJECT_CURRENCIES);

        smart_collapsableBar('createdcurrencies', _AM_SOBJECT_CURRENCIES, _AM_SOBJECT_CURRENCIES_DSC);

        include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
        $objectTable = new SmartObjectTable($smartobject_currency_handler);
        $objectTable->addColumn(new SmartObjectColumn('name', 'left', false, 'getCurrencyLink'));
        $objectTable->addColumn(new SmartObjectColumn('rate', 'center', 150));
        $objectTable->addColumn(new SmartObjectColumn('iso4217', 'center', 150));
        $objectTable->addColumn(new SmartObjectColumn('default_currency', 'center', 150, 'getDefault_currencyControl'));

        $objectTable->addIntroButton('addcurrency', 'currency.php?op=mod', _AM_SOBJECT_CURRENCIES_CREATE);

        $objectTable->addActionButton('updateCurrencies', _SUBMIT, _AM_SOBJECT_CURRENCY_UPDATE_ALL);

        $objectTable->render();

        echo "<br />";
        smart_close_collapsable('createdcurrencies');
        echo "<br>";

        break;
}

//smart_modFooter();
//xoops_cp_footer();
include_once 'admin_footer.php';