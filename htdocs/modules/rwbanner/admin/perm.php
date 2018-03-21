<?php
//

use  XoopsModules\Rwbanner;

require_once __DIR__ . '/admin_header.php';
// Функции модуля
include __DIR__ . '/../include/functions.php';

// Admin Gui
$adminObject = \Xmf\Module\Admin::getInstance();

// Подключаем форму прав
require_once $GLOBALS['xoops']->path('class/xoopsform/grouppermform.php');

// Заголовок админки
xoops_cp_header();
// Меню
//loadModuleAdminMenu( 3, _AM_INSTRUCTION_BC_PERM );
$xoopsTpl->assign('insNavigation', $adminObject->displayNavigation(basename(__FILE__)));

$permission = instr_CleanVars($_REQUEST, 'permission', 1, 'int');
//$permission = isset( $_POST['permission'] ) ? (int)( $_POST['permission'] ): 1;
$selected                  = ['', '', ''];
$selected[$permission - 1] = ' selected';

//
$xoopsTpl->assign('insSelected', $selected);

$moduleId = $xoopsModule->getVar('mid');

switch ($permission) {
    // Права на просмотр
    case 1:
        $formTitle             = _AM_INSTRUCTION_PERM_VIEW;
        $permissionName        = 'instruction_view';
        $permissionDescription = _AM_INSTRUCTION_PERM_VIEW_DSC;
        break;
    // Права на добавление
    case 2:
        $formTitle             = _AM_INSTRUCTION_PERM_SUBMIT;
        $permissionName        = 'instruction_submit';
        $permissionDescription = _AM_INSTRUCTION_PERM_SUBMIT_DSC;
        break;
    // Права на редактирование
    case 3:
        $formTitle             = _AM_INSTRUCTION_PERM_EDIT;
        $permissionName        = 'instruction_edit';
        $permissionDescription = _AM_INSTRUCTION_PERM_EDIT_DSC;
        break;
}

// Права
$permissionsForm = new \XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/perm.php?permission=' . $permission);

$sql    = 'SELECT cid, pid, title FROM ' . $xoopsDB->prefix('instruction_cat') . ' ORDER BY title';
$result = $xoopsDB->query($sql);
if ($result) {
    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $permissionsForm->addItem($row['cid'], $row['title'], $row['pid']);
    }
}

//echo $permissionsForm->render();
$xoopsTpl->assign('insFormPerm', $permissionsForm->render());
//
unset($permissionsForm);

// Выводим шаблон
$GLOBALS['xoopsTpl']->display('db:instruction_admin_perm.tpl');
// Текст внизу админки
require_once __DIR__ . '/admin_footer.php';
// Подвал админки
xoops_cp_footer();
