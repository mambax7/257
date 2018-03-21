<?php

/**
 *
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $adsenseid
 * @param bool $clone
 */

function editclass($showmenu = false, $adsenseid = 0, $clone = false)
{
    global $smartobjectAdsenseHandler;

    $adsenseObj = $smartobjectAdsenseHandler->get($adsenseid);

    if (!$clone && !$adsenseObj->isNew()) {
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

require_once __DIR__ . '/admin_header.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/adsense.php';
$smartobjectAdsenseHandler = xoops_getModuleHandler('adsense');
smart_loadLanguageFile('smartobject', 'adsense');
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

        $adsenseid = isset($_GET['adsenseid']) ? (int)$_GET['adsenseid'] : 0;

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $adsenseid);
        break;

    case 'clone':

        $adsenseid = isset($_GET['adsenseid']) ? (int)$_GET['adsenseid'] : 0;

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $adsenseid, true);
        break;

    case 'addadsense':
        if (@require_once SMARTOBJECT_ROOT_PATH . 'include/captcha/captcha.php') {
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header('javascript:history.go(-1);', 3, $xoopsCaptcha->getMessage());
            }
        }
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectAdsenseHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_ADSENSES_CREATED, _AM_SOBJECT_ADSENSES_MODIFIED);
        break;

    case 'del':

        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectAdsenseHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        smart_xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));

        //smart_adminMenu(3, _AM_SOBJECT_ADSENSES);

        smart_collapsableBar('createdadsenses', _AM_SOBJECT_ADSENSES, _AM_SOBJECT_ADSENSES_DSC);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new SmartObjectTable($smartobjectAdsenseHandler);
        $objectTable->addColumn(new SmartObjectColumn('description', 'left'));
        $objectTable->addColumn(new SmartObjectColumn(_AM_SOBJECT_ADSENSE_TAG, 'center', 200, 'getXoopsCode'));

        //      $objectTable->addCustomAction('getCreateItemLink');
        //      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addadsense', 'adsense.php?op=mod', _AM_SOBJECT_ADSENSES_CREATE);
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
        smart_close_collapsable('createdadsenses');
        echo '<br>';

        break;
}

//smart_modFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
