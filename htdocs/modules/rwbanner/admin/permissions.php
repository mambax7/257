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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 */

use  XoopsModules\Rwbanner;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$myts = \MyTextSanitizer::getInstance();

global $xoopsDB, $xoopsModule;
$module_id = $xoopsModule->getVar('mid');

$title_of_form = 'Permission form for viewing categories';
$perm_desc     = 'Select categories that each group is allowed to view';

//publisher_cpHeader();
//publisher_adminMenu(3, _AM_PUBLISHER_PERMISSIONS);

// View Categories permissions
$item_list_view = [];
$block_view     = [];

$result_view = $xoopsDB->query('SELECT cod, titulo FROM ' . $xoopsDB->prefix('rwbanner_categorias') . ' ');
if ($xoopsDB->getRowsNum($result_view)) {
    $form_submit = new \XoopsGroupPermForm($title_of_form, $module_id, 'category_read', '', 'admin/permissions.php');
    while (false !== ($myrow_view = $xoopsDB->fetchArray($result_view))) {
        $form_submit->addItem($myrow_view['cod'], $myts->displayTarea($myrow_view['titulo']));
    }
    echo $form_submit->render();
} else {
    echo _AM_PUBLISHER_MD_RWBANNER_NOPERMSSET;
}

$title_of_form2 = 'Submit Permissions';

// Submit Categories permissions
echo "<br>\n";

$result_view = $xoopsDB->query('SELECT cod, titulo FROM ' . $xoopsDB->prefix('rwbanner_categorias') . ' ');
if ($xoopsDB->getRowsNum($result_view)) {
    $form_submit = new \XoopsGroupPermForm($title_of_form2, $module_id, 'category_submit', '', 'admin/permissions.php');
    while (false !== ($myrow_view = $xoopsDB->fetchArray($result_view))) {
        $form_submit->addItem($myrow_view['cod'], $myts->displayTarea($myrow_view['titulo']));
    }
    echo $form_submit->render();
} else {
    echo _AM_PUBLISHER_MD_RWBANNER_NOPERMSSET;
}

require_once __DIR__ . '/admin_footer.php';
