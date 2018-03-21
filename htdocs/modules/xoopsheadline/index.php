<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

include __DIR__ . '/../../mainfile.php';
xoops_load('XoopsheadlineUtility', $xoopsModule->getVar('dirname'));

$hlman = xoops_getModuleHandler('headline');
$hlid  = (!empty($_GET['id']) && ((int)$_GET['id'] > 0)) ? (int)$_GET['id'] : 0;

$GLOBALS['xoopsOption']['template_main'] = 'xoopsheadline_index.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('headline_display', 1, '='));
$criteria->add(new \Criteria('headline_xml', '', '!='));
switch ((int)$xoopsModuleConfig['sortby']) {
    case 1:
        $criteria->setSort('headline_name');
        $criteria->setOrder('DESC');
        break;
    case 2:
        $criteria->setSort('headline_name');
        $criteria->setOrder('ASC');
        break;
    case 3:
        $criteria->setSort('headline_weight');
        $criteria->setOrder('DESC');
        break;
    case 4:
    default:
        $criteria->setSort('headline_weight');
        $criteria->setOrder('ASC');
        break;
}
$headlines = $hlman->getObjects($criteria);

global $xoopsModule;
$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);;
$moduleDirName = $xoopsModule->getVar('dirname');

$userIsAdmin = (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) ? true : false;
$count       = count($headlines);
for ($i = 0; $i < $count; $i++) {
    $thisId  = $headlines[$i]->getVar('headline_id');
    $editUrl = $userIsAdmin ? "&nbsp;<a href='" . XOOPS_URL . "/modules/{$moduleDirName}/admin/main.php?op=edit&amp;headline_id={$thisId}'><img src='" . $pathIcon16 . "/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>" : '';
    $xoopsTpl->append('feed_sites', ['id' => $thisId, 'name' => $headlines[$i]->getVar('headline_name'), 'editurl' => $editUrl]);
}
$xoopsTpl->assign('lang_headlines', _MD_HEADLINES_HEADLINES);
if (0 == $hlid) {
    $hlid = $headlines[0]->getVar('headline_id');
}
if ($hlid > 0) {
    $headline = $hlman->get($hlid);
    if (is_object($headline)) {
        $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($headline);
        if (!$renderer->renderFeed()) {
            if (2 == $xoopsConfig['debug_mode']) {
                $xoopsTpl->assign('headline', '<p>' . sprintf(_MD_HEADLINES_FAILGET, $headline->getVar('headline_name')) . '<br>' . $renderer->getErrors() . '</p>');
            }
        } else {
            $xoopsTpl->assign('headline', $renderer->getFeed());
        }
    }
}
include XOOPS_ROOT_PATH . '/footer.php';
