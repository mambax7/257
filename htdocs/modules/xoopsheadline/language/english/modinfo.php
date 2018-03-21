<?php
/**
 * XoopsHeadline module
 * Description: Module Info Language file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright ::  The XOOPS Project (https://xoops.org)
 * @license   ::    GNU GPL (http://www.gnu.org/licenses/gpl-2.0.html/)
 * @package   ::    xoopsheadline
 * @subpackage:: admin
 * @since     ::      1.10
 * @author    ::     onokazu, et al.
 **/

// The name of this module
define('_MI_HEADLINES_NAME', 'Headlines');

// A brief description of this module
define('_MI_HEADLINES_DESC', 'Displays RSS/XML Newsfeed from other sites');

// Names of blocks for this module (Not all module has blocks)
define('_MI_HEADLINES_BNAME', 'Headlines');

// Names of admin menu items & their descriptions
define('_MI_HEADLINES_MENU_ADMININDEX', 'Home');
define('_MI_HEADLINES_MENU_ADMINHL', 'List Headlines');
define('_MI_HEADLINES_MENU_ADMINABOUT', 'About');
define('_MI_HEADLINES_MENU_ADMININDEX_DESC', '');
define('_MI_HEADLINES_MENU_ADMINHL_DESC', 'Display the headline entries');
define('_MI_HEADLINES_MENU_ADMINABOUT_DESC', 'Display information about this module');

// Config Option items
define('_MI_HEADLINES_SORTORDER', 'Order to display headlines');
define('_MI_HEADLINES_SORTORDERDSC', 'This is the order the headlines will be shown in display and blocks');
define('_MI_HEADLINES_SORT1', 'Site Name DESC');
define('_MI_HEADLINES_SORT2', 'Site Name ASC');
define('_MI_HEADLINES_SORT3', 'Weight DESC');
define('_MI_HEADLINES_SORT4', 'Weight ASC');

//1.12
//Help
define('_MI_HEADLINES_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_HEADLINES_HELP_HEADER', __DIR__.'/help/helpheader.tpl');
define('_MI_HEADLINES_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_HEADLINES_OVERVIEW', 'Overview');

//define('_MI_HEADLINES_HELP_DIR', __DIR__);

//help multi-page
define('_MI_HEADLINES_DISCLAIMER', 'Disclaimer');
define('_MI_HEADLINES_LICENSE', 'License');
define('_MI_HEADLINES_SUPPORT', 'Support');
