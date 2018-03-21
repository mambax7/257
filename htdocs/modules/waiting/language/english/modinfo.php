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
 * @author          Ryuji AMANO <info@ryus.co.jp>
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            http://ryus.co.jp Ryu's Planning
 * @link            https://xoops.org XOOPS
 */
// The name of this module
define('_MI_WAITING_NAME', 'Waiting Contents');
// A brief description of this module
define('_MI_WAITING_DESC', 'Displays a block of waiting contents for 3rd party modules.');
// Names of blocks for this module (Not all module has blocks)
define('_MI_WAITING_BNAME1', 'Waiting');
define('_MI_WAITING_BNAME1_DESC', 'Shows contents waiting for approval');
define('_MI_WAITING_MENU_HOME', 'Home');
define('_MI_WAITING_MENU_ABOUT', 'About');
define('_MI_WAITING_MENU_PLUGINS', 'Plugins');
// Help defines
//define('_MI_WAITING_NAME', "Waiting");
define('_MI_WAITING_HEADER', 'Help:');
define('_MI_WAITING_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_WAITING_BACK_2_ADMIN', 'Back to Administration of ');

//1.06
//Help
//define('_MI_WAITING_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_WAITING_HELP_HEADER', __DIR__.'/help/helpheader.tpl');
//define('_MI_WAITING_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_WAITING_OVERVIEW', 'Overview');

//define('_MI_WAITING_HELP_DIR', __DIR__);

//help multi-page
define('_MI_WAITING_DISCLAIMER', 'Disclaimer');
define('_MI_WAITING_LICENSE', 'License');
define('_MI_WAITING_SUPPORT', 'Support');
