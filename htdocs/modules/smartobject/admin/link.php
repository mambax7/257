<?php
//

// 2012-01-01 K.OHWADA
// PHP 5.3: Assigning the return value of new by reference is now deprecated.

/**
 * Id: link.php 159 2007-12-17 16:44:05Z malanciault
 * Module: SmartShop
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

require_once __DIR__ . '/admin_header.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectlink.php';
$adminObject = \Xmf\Module\Admin::getInstance();

$smartobjectLinkHandler = xoops_getModuleHandler('link');

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {

    case 'del':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectLinkHandler);
        $controller->handleObjectDeletion(_AM_SOBJECT_SENT_LINK_DELETE_CONFIRM);

        break;

    case 'view':
        $linkid  = isset($_GET['linkid']) ? $_GET['linkid'] : 0;
        $linkObj = $smartobjectLinkHandler->get($linkid);

        if ($linkObj->isNew()) {
            redirect_header(SMARTOBJECT_URL . 'admin/link.php', 3, _AM_SOBJECT_LINK_NOT_FOUND);
        }

        smart_xoops_cp_header();

        //smart_adminMenu(1, _AM_SOBJECT_SENT_LINK_DISPLAY);

        smart_collapsableBar('sentlinks', _AM_SOBJECT_SENT_LINK_DISPLAY, _AM_SOBJECT_SENT_LINK_DISPLAY_INFO);

        require_once XOOPS_ROOT_PATH . '/class/template.php';

        // ---
        // 2012-01-01 PHP 5.3: Assigning the return value of new by reference is now deprecated.
        //      $xoopsTpl = new \XoopsTpl();
        $xoopsTpl = new \XoopsTpl();
        //---

        $xoopsTpl->assign('link', $linkObj->toArray());
        $xoopsTpl->display('db:smartobject_sentlink_display.tpl');

        echo '<br>';
        smart_close_collapsable('sentlinks');
        echo '<br>';

        break;

    default:

        smart_xoops_cp_header();

        $adminObject->displayNavigation(basename(__FILE__));

        //smart_adminMenu(1, _AM_SOBJECT_SENT_LINKS);

        smart_collapsableBar('sentlinks', _AM_SOBJECT_SENT_LINKS, _AM_SOBJECT_SENT_LINKS_INFO);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new SmartObjectTable($smartobjectLinkHandler, null, ['delete']);
        $objectTable->addColumn(new SmartObjectColumn('date'));
        $objectTable->addColumn(new SmartObjectColumn(_AM_SOBJECT_SENT_LINKS_FROM, $align = 'left', $width = false, 'getFromInfo'));
        $objectTable->addColumn(new SmartObjectColumn(_AM_SOBJECT_SENT_LINKS_TO, $align = 'left', $width = false, 'getToInfo'));
        $objectTable->addColumn(new SmartObjectColumn('link'));

        $objectTable->addCustomAction('getViewItemLink');

        $objectTable->setDefaultSort('date');
        $objectTable->setDefaultOrder('DESC');

        $objectTable->render();

        echo '<br>';
        smart_close_collapsable('sentlinks');
        echo '<br>';

        break;
}

//smart_modFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
