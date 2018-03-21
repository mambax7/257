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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();

//-----------------------
$xhlHandler = xoops_getModuleHandler('headline', $xoopsModule->getVar('dirname', 'n'));

$totalHls          = $xhlHandler->getCount();
$totalDisplayedHls = $xhlHandler->getCount(new \Criteria('headline_display', 1, '='));
$totalHiddenHls    = $totalHls - $totalDisplayedHls;

$displayedAsBlock = $xhlHandler->getCount(new \Criteria('headline_asblock ', 1, '='));

$adminObject->addInfoBox(_MD_HEADLINES_XOOPSHEADLINECONF);
$adminObject->addInfoBoxLine(sprintf(_MD_HEADLINES_TOTALDISPLAYED, $totalDisplayedHls), '', 'Green');
$adminObject->addInfoBoxLine(sprintf(_MD_HEADLINES_TOTALHIDDEN, $totalHiddenHls), '', 'Red');
$adminObject->addInfoBoxLine(sprintf(_MD_HEADLINES_TOTALHLS, $totalHls), '');
$adminObject->addInfoBoxLine(sprintf(_MD_HEADLINES_TOTALASBLOCK, $displayedAsBlock), '', 'Green');

//----------------------------

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

include __DIR__ . '/admin_footer.php';
