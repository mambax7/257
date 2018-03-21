<?php

/**
 *
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $ratingid
 */

function editclass($showmenu = false, $ratingid = 0)
{
    global $smartobjectRatingHandler;

    $ratingObj = $smartobjectRatingHandler->get($ratingid);

    if (!$ratingObj->isNew()) {
        if ($showmenu) {
            //smart_adminMenu(4, _AM_SOBJECT_RATINGS . " > " . _AM_SOBJECT_EDITING);
        }
        smart_collapsableBar('ratingedit', _AM_SOBJECT_RATINGS_EDIT, _AM_SOBJECT_RATINGS_EDIT_INFO);

        $sform = $ratingObj->getForm(_AM_SOBJECT_RATINGS_EDIT, 'addrating');
        $sform->display();
        smart_close_collapsable('ratingedit');
    } else {
        $ratingObj->hideFieldFromForm(['item', 'itemid', 'uid', 'date', 'rate']);

        if (isset($_POST['op'])) {
            $controller = new SmartObjectController($smartobjectRatingHandler);
            $controller->postDataToObject($ratingObj);

            if ('changedField' === $_POST['op']) {
                switch ($_POST['changedField']) {
                    case 'dirname':
                        $ratingObj->showFieldOnForm(['item', 'itemid', 'uid', 'date', 'rate']);
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

require_once __DIR__ . '/admin_header.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/rating.php';
$smartobjectRatingHandler = xoops_getModuleHandler('rating');
$indexAdmin               = \Xmf\Module\Admin::getInstance();

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':
    case 'changedField':

        $ratingid = isset($_GET['ratingid']) ? (int)$_GET['ratingid'] : 0;

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $ratingid);
        break;

    case 'addrating':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectRatingHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_RATINGS_CREATED, _AM_SOBJECT_RATINGS_MODIFIED, SMARTOBJECT_URL . 'admin/rating.php');

        break;

    case 'del':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectRatingHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        //smart_adminMenu(4, _AM_SOBJECT_RATINGS);

        smart_collapsableBar('createdratings', _AM_SOBJECT_RATINGS, _AM_SOBJECT_RATINGS_DSC);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new SmartObjectTable($smartobjectRatingHandler);
        $objectTable->addColumn(new SmartObjectColumn('name', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('dirname', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('item', 'left', false, 'getItemValue'));
        $objectTable->addColumn(new SmartObjectColumn('date', 'center', 150));
        $objectTable->addColumn(new SmartObjectColumn('rate', 'center', 40, 'getRateValue'));

        //      $objectTable->addCustomAction('getCreateItemLink');
        //      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addrating', 'rating.php?op=mod', _AM_SOBJECT_RATINGS_CREATE);
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

        $objectTable->render();

        echo '<br>';
        smart_close_collapsable('createdratings');
        echo '<br>';

        break;
}

//smart_modFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
