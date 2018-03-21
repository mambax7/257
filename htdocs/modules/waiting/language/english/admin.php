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

define('_AM_WAITING_MODNAME', 'Modules');
define('_AM_WAITING_DIRNAME', 'Directory name');// dirname added by cesag
define('_AM_WAITING_STATUS', 'Status');
define('_AM_WAITING_MODACTIVE', 'Module Active');
define('_AM_WAITING_PLUGINLIST', 'Plugin Checker');
define('_AM_WAITING_PLUGINLIST_DESC', '<b>Module:</b> using the original plugin included in the module<br><b>Waiting:</b> using the bundled plugin in Waiting Contents Extensible<br><b>Blank:</b> no plugins found');
// ModuleAdmin
define('_AM_WAITING_MISSING', 'Error: The ModuleAdmin class is missing. Please install the ModuleAdmin Class into /Frameworks (see /docs/readme.txt)');
// Text for Admin footer
define('_AM_WAITING_FOOTER', "<div class='center smallsmall italic pad5'>Waiting is maintained by the <a class='tooltip' rel='external' href='https://xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>");

//1.06
define('_AM_WAITING_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_WAITING_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_WAITING_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_WAITING_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_WAITING_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_WAITING_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_WAITING_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
