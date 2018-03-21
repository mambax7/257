<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            https://xoops.org XOOPS
 */

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$aboutAdmin = \Xmf\Module\Admin::getInstance();

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayAbout('WKFZBRBGMYKCA', false);

require_once __DIR__ . '/admin_footer.php';
