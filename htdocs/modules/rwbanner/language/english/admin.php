<?php
/*
                                  RW-Banner
                          Copyright (c) 2006 BrInfo
                          <http://www.brinfo.com.br>
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting
  source code which is considered copyrighted (c) material of the
  original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/
/**
 * Administration Language Defines
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     The SmartFactory <www.smartfactory.ca>
 * @package  ::    rwbanner
 *
 */
/**#@+
 * Used in ./admin/index.php
 */
//admin/index.php
define('_AM_RWBANNER_MSG1', 'Status changed successfully!');
define('_AM_RWBANNER_MSG2', 'Banner changed successfully!');
define('_AM_RWBANNER_MSG8', 'Banner added successfully!');
define('_AM_RWBANNER_MSG101', 'Banner deleted successfully!');
define('_AM_RWBANNER_MSG10', 'There was a problem adding the banner.');
define('_AM_RWBANNER_MSG11', 'There was a problem editing the banner.');
define('_AM_RWBANNER_MSG3', 'Are you sure to remove this category and all its banners?');
define('_AM_RWBANNER_MSG4', 'Category changed successfully!');
define('_AM_RWBANNER_MSG5', 'Category added successfully!');
define('_AM_RWBANNER_MSG102', 'Category an its banners deleted successfully!');
define('_AM_RWBANNER_MSG6', 'There was a problem adding the category.');
define('_AM_RWBANNER_MSG7', 'There was a problem editing the category.');
//define('_AM_RWBANNER_MSG12', 'Select a block model first.');
//define('_AM_RWBANNER_MSG13', 'Block added successfully! Insert the code below in the template in the exact place you want the block to be shown.');
//define('_AM_RWBANNER_MSG14', 'Block changed successfully!');
//define('_AM_RWBANNER_MSG15', 'Are you sure to delete this block?');
//define('_AM_RWBANNER_MSG16', 'Block deleted successfully!');
define('_AM_RWBANNER_MSG17', 'Default upload folder for banners created successfully!');
define('_AM_RWBANNER_MSG18', 'There was an error creating the default upload folder for the banners! Please, try again.');
define('_AM_RWBANNER_MSG19', 'Are you sure you want to delete this banner?');
define('_AM_RWBANNER_MSG20', 'Are you sure you want to remove this tag?<br>WARNING: By deleting the tag from the database, also remove it from the templates it was being used to avoid any problems.');
define('_AM_RWBANNER_MSG21', 'Tag deleted successfully!');
define('_AM_RWBANNER_MSG22', 'Tag added successfully!');
define('_AM_RWBANNER_MSG23', 'There was a problem adding the tag.');
define('_AM_RWBANNER_MSG24', 'Tag changed successfully!');
define('_AM_RWBANNER_MSG25', 'There was a problem editing the tag.');
/**#@-*/
define('_AM_RWBANNER_TITLE1', 'Registered banners ');
define('_AM_RWBANNER_TITLE2', 'ID');
define('_AM_RWBANNER_TITLE3', 'CLIENT');
define('_AM_RWBANNER_TITLE4', 'CATEGORY');
define('_AM_RWBANNER_TITLE5', 'IMPRESSIONS');
define('_AM_RWBANNER_TITLE6', 'IMP. LEFT');
define('_AM_RWBANNER_TITLE7', 'CLICKS');
define('_AM_RWBANNER_TITLE8', '% OF CLICKS');
define('_AM_RWBANNER_TITLE9', 'DATE CREATED');
define('_AM_RWBANNER_TITLE10', 'STATUS');
define('_AM_RWBANNER_TITLE11', 'OPTIONS');
//define('_AM_RWBANNER_TITLE12', 'Registered categories');
define('_AM_RWBANNER_TITLE13', 'TITLE');
define('_AM_RWBANNER_TITLE14', 'NUM. BANNERS');
//define('_AM_RWBANNER_TITLE15', 'MODULE');
//define('_AM_RWBANNER_TITLE16', 'Clients with banners');
define('_AM_RWBANNER_TITLE17', 'CLIENT');
define('_AM_RWBANNER_TITLE18', 'CONTACT');
define('_AM_RWBANNER_TITLE19', 'EMAIL');
define('_AM_RWBANNER_TITLE20', 'BANNERS');
define('_AM_RWBANNER_TITLE39', 'CLICKS LEFT.');
//define('_AM_RWBANNER_TITLE40', 'Template Blocks');
//define('_AM_RWBANNER_TITLE41', 'Select the block model you want to insert in your templates');
//define('_AM_RWBANNER_TITLE42', 'Select a block');
//define('_AM_RWBANNER_TITLE43', 'DESCRIPTION');
//define('_AM_RWBANNER_TITLE44', 'COLUMN NR.');
//define('_AM_RWBANNER_TITLE45', 'REFRESH TIME (ms)');
//define('_AM_RWBANNER_TITLE46', 'Style');
//define('_AM_RWBANNER_TITLE47', 'Insert here the CSS for the block. Pay attention, in the field there is already a CSS model for a default block, don\'t change the name of the classes, simply adapt them to your needs.');
//define('_AM_RWBANNER_TITLE48', 'Insert here a brief description of the block. We recommend to write here the template with which it is used and further information that can show clearly the purpose of the block.');
//define('_AM_RWBANNER_TITLE49', 'INCLUSION ID');
define(
    '_AM_RWBANNER_TITLE50',
       'Click on the button "Generate" to create the inclusion code for the block. This code should be inserted in the template, in the exact place where the banner block will be shown. Remember that the color, position, font and size formats depend on the CSS chosen by you in this screen.'
);
define('_AM_RWBANNER_TITLE51', 'Generate inclusion code');
//define('_AM_RWBANNER_TITLE52', 'FILE');
//define('_AM_RWBANNER_TITLE53', 'The time has to be set in milliseconds. E.g.: If you want to change the banners every 10 seconds, insert the value of 10000 milliseconds. For 30 seconds, 30000 milliseconds and so on.');
define('_AM_RWBANNER_TITLE99', 'EXPIRATION DATE');

define('_AM_RWBANNER_TAG_TITLE01', 'ID');
define('_AM_RWBANNER_TAG_TITLE02', 'TITLE');
define('_AM_RWBANNER_TAG_TITLE03', 'SMARTY TAG');
define('_AM_RWBANNER_TAG_TITLE04', 'CATEGORY');
define('_AM_RWBANNER_TAG_TITLE05', 'TOTAL BANNERS');
define('_AM_RWBANNER_TAG_TITLE06', 'TOTAL COLUMNS');
define('_AM_RWBANNER_TAG_TITLE07', 'STATUS');
define('_AM_RWBANNER_TAG_TITLE08', 'Add new tag');
define('_AM_RWBANNER_TAG_TITLE09', 'Title:');
define('_AM_RWBANNER_TAG_TITLE10', 'Category:');
define('_AM_RWBANNER_TAG_TITLE11', 'Number of Banners:');
define('_AM_RWBANNER_TAG_TITLE12', 'Number of Columns:');
define('_AM_RWBANNER_TAG_TITLE13', 'All categories');
define('_AM_RWBANNER_TAG_TITLE14', 'Smarty Tag:');
define('_AM_RWBANNER_TAG_TITLE15', 'WARNING: This will be name for the created tag, therefore you cannot use spaces, accents or any special chars. If you want to combine words separate each word by an underline(_).');
define('_AM_RWBANNER_TAG_TITLE16', 'Show only in the module:');
define('_AM_RWBANNER_TAG_TITLE17', 'All modules');
define('_AM_RWBANNER_TAG_TITLE18', 'Tag status:');
define('_AM_RWBANNER_TAG_TITLE19', 'MODULES');
define('_AM_RWBANNER_TAG_TITLE20', 'NOTES:');
define('_AM_RWBANNER_TAG_TITLE21', 'We recommend to enter in this field all the places where the tag was used so that when you do not want to continue using it, it\'ll be easy to remove it.');
define('_AM_RWBANNER_TAG_TITLE22', 'Banner code:');
define('_AM_RWBANNER_TAG_TITLE23', 'In case you do not want that this TAG shows random banners, place in this field the Code of the banner that you want to show and it will be the only shown.');

define('_AM_RWBANNER_TAG_STATUS1', 'Tag Active');
define('_AM_RWBANNER_TAG_STATUS2', 'Tag Inactive');

define('_AM_RWBANNER_VALUE_BTN1', 'Add Banner');
define('_AM_RWBANNER_VALUE_BTN2', 'View Banner');
define('_AM_RWBANNER_VALUE_BTN3', 'Edit Banner');
define('_AM_RWBANNER_VALUE_BTN4', 'Remove Banner');
define('_AM_RWBANNER_VALUE_BTN5', 'Add Category');
define('_AM_RWBANNER_VALUE_BTN6', 'Edit Category');
define('_AM_RWBANNER_VALUE_BTN7', 'Remove Category');
define('_AM_RWBANNER_VALUE_BTN8', 'Add Client');
//define('_AM_RWBANNER_VALUE_BTN9', 'Edit Category');
//define('_AM_RWBANNER_VALUE_BTN10', 'Add');
//define('_AM_RWBANNER_VALUE_BTN11', 'Template blocks');
define('_AM_RWBANNER_VALUE_BTN12', 'Add Tag');

define('_AM_RWBANNER_BANNER_STATUS1', 'Banner Active');
define('_AM_RWBANNER_BANNER_STATUS2', 'Banner Inactive');
define('_AM_RWBANNER_BANNER_STATUS3', 'Waiting on Admin Approval');
//define('_AM_RWBANNER_BLOCK_STATUS1', 'Active');
//define('_AM_RWBANNER_BLOCK_STATUS2', 'Inactive');

define('_AM_RWBANNER_BANNER_EXIBREST', 'Unlimited');

//define('_AM_RWBANNER_CATEG_ALLMOD', 'All');
define('_AM_RWBANNER_NO_CATEG', 'Define Category');

define('_AM_RWBANNER_PREF', 'Preferences');
//define('_AM_RWBANNER_OPTS', 'Blocks / Groups');
//define('_AM_RWBANNER_GOMOD', 'Go to module');
define('_AM_RWBANNER_ABOUT', 'About the module');
define('_AM_RWBANNER_DEMO', 'Support');
define('_AM_RWBANNER_MODADMIN', '- General Admin:');
define('_AM_RWBANNER_INDEX', 'Index');

define('_AM_RWBANNER_LIST_BANNER', 'List of all registered banners');
define('_AM_RWBANNER_LIST_BANNER_DESC', 'List of banners registered in the system. With this list you can have a brief statistic of all banners as well as edit or delete them. To change the display order of the banners click on the columns name.');
define('_AM_RWBANNER_LIST_CATEG', 'List of registered categories');
define('_AM_RWBANNER_LIST_CATEG_DESC', 'List of all registered categories in the system. With this list you can edit and delete the categories.');
define('_AM_RWBANNER_LIST_USERS', 'List all clients with banners');
define('_AM_RWBANNER_LIST_USERS_DESC', 'List of all registered users that have banners registered in the system.');
define('_AM_RWBANNER_LIST_TAG', 'Registered Smarty Tags list');
define(
    '_AM_RWBANNER_LIST_TAG_DESC',
       'List of all registered smarty tags in the system. These tags are automatically created when the site is started and shows the blocks in your site\'s theme or in the templates from the modules according to your defined configuration. To show the banners in the templates or in the theme edit the desired file including the tag in the exact place where the banners should be shown. You can deactivate the tags at any time clicking on the icon in the status column but remember that only the active tags will show banners.<br><fieldset><p style=\'color:#FF7F27; text-align: center;\'><b>NOTICE:</b> To activate the TAGS BBcode function it is necessary to hack the XOOPS core, to learn how to apply this hack read the README.TXT file</p></fieldset>'
);

define('_AM_RWBANNER_NODIR', 'The default upload folder for the banners does not exist or is inaccessible.<br><span style="color:red">Default folder: "%s"</span> <a href="main.php?op=criardir">Create folder</a>');

define('_AM_RWBANNER_TOTAL_BANNER_LEG', 'Total Registered Banners:');
define('_AM_RWBANNER_NO_USERS', 'There are no clients with banners');

//admin/edita.php
//define('_AM_RWBANNER_MSG5_ED', 'All the fields to be filled are mandatory!');

//define('_AM_RWBANNER_TITLE21', 'Edit Banner #');
define('_AM_RWBANNER_TITLE22', 'Client:');
define('_AM_RWBANNER_TITLE23', 'Category:');
define('_AM_RWBANNER_TITLE24', 'Display number:');
define('_AM_RWBANNER_TITLE500', 'Click number:');
define('_AM_RWBANNER_TITLE5001', 'Display period (in days):');
define('_AM_RWBANNER_TITLE25', 'Image(URL):');
define('_AM_RWBANNER_TITLE26', 'Link(URL):');
define('_AM_RWBANNER_TITLE27', 'Use HTML?');
define('_AM_RWBANNER_TITLE28', 'HTML code:');
define('_AM_RWBANNER_TITLE29', 'Target:');
define('_AM_RWBANNER_TITLE51_ED', 'Upload Banner:');
define('_AM_RWBANNER_TITLE5000', 'Notes:');
define('_AM_RWBANNER_TITLE5000_DESC', 'In this field the client defined some details about the banner and its display, analyze the content of this field to define the publicity scheme for this banner.');

//define('_AM_RWBANNER_VALUE_BTN10_ED', 'Send');

//admin/editacateg.php
//define('_AM_RWBANNER_MSG6_EDC', 'This category has registered banners, don\'t forget to edit them, by changing the size so that they are compatible with the new category setup, otherwise, there can be problems with the banner display!');

define('_AM_RWBANNER_TITLE30', 'Edit Category #');
define('_AM_RWBANNER_TITLE31', 'Title:');
define('_AM_RWBANNER_TITLE32', 'Banner width:');
define('_AM_RWBANNER_TITLE33', 'Banner height:');
//define('_AM_RWBANNER_TITLE34', 'Attach to module:');

//admin/banners.php
//define('_AM_RWBANNER_TITLE35', 'Add new banner');
//define('_AM_RWBANNER_TITLE36', 'Display number:');

//admin/categories.php
define('_AM_RWBANNER_TITLE37', 'By choosing one or more modules in this option, you define that all the banners generated by this tag will be shown in the selected module.');
define('_AM_RWBANNER_TITLE38', 'Add new category');

/**#@+
 * @ignore
 */
//admin/myblocksadmin.php
//define('_AM_RWBANNER_BLOCKS', 'Blocks');
//define('_AM_RWBANNER_BLOCKSINFO', 'You can setup the block here or in the system module. Here you can also have the option to clone a block.');
//define('_AM_RWBANNER_GROUPS', 'Groups');
//define('_AM_RWBANNER_GROUPSINFO', 'Module setup and block permissions for each group');
//define('_AM_RWBANNER_POSITION', 'Position');

define('_AM_RWBANNER_BTN_OP1', 'Add');
define('_AM_RWBANNER_BTN_OP2', 'Edit');
/**#@-*/
define('_AM_RWBANNER_BTN_OP3', 'Unlimited');
define('_AM_RWBANNER_BTN_OP4', 'Submit');

//admin/upgrade.php
define('_AM_RWBANNER_UPGRADEFAILED', 'There was an error during the update process! Please try again!');
//define('_AM_RWBANNER_UPGRADEFAILED1', 'Error while creating the banners table!');
//define('_AM_RWBANNER_UPGRADEFAILED2', 'Error while creating the category table!');
define('_AM_RWBANNER_UPGRADEFAILED3', 'Error while creating the TAGS table!');

define('_AM_RWBANNER_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_RWBANNER_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_RWBANNER_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");

define('_AM_RWBANNER_UPGRADECOMPLETE', 'Tables have been updated! Follow the steps below to activate all the module resources and after that click on the following link.');
define('_AM_RWBANNER_UPGRADECOMPLETE1', 'The code to show the banner in the theme and templates on your site was changed, you have to update the old code in the following file ".XOOPS_ROOT_PATH."/header.php using the code below:');
define(
    '_AM_RWBANNER_UPGRADECOMPLETE2',
       'The module has a new feature: banner serving in text from other modules, allowing you to show your banners in any module that accepts bbcodes such as the news, articles, including forums. To activate this new feature, you have to hack the ".XOOPS_ROOT_PATH."/class/module.textsanitizer.php file.<br>The hack has to be applied in the end of the xoopsCodeDecode function. To make it easier, take a look at the the file: function_bbcode_xoops2015.txt in the docs folder in this module.'
);

define('_AM_RWBANNER_UPDATEMODULE', 'Update Templates and Blocks');

define('_AM_RWBANNER_IMPORT', 'Import');
define('_AM_RWBANNER_IMPORT_TITLE', 'Import selected banners from the XOOPS System Banners into RW-Banner ');
define('_AM_RWBANNER_IMPORT_TITLE1', 'RW-Banner Category');
define('_AM_RWBANNER_IMPORT_TITLE2', 'RW-Banner Client');
define('_AM_RWBANNER_IMPORT_TITLE3', 'Import?');
define('_AM_RWBANNER_SUCCESS_IMPORT', 'Import successful. Please edit the imported banners and configure them correctly. ');
define('_AM_RWBANNER_FAIL_IMPORT', 'There were problems during the Import process! Please review it.');

// 1.51 & 1.52
//define('_AM_RWBANNER_PERMISSIONS', 'Permissions');

//define('_AM_RWBANNER_ACTIVERIGHTS', 'Admin Rights');
//define('_AM_RWBANNER_ACCESSRIGHTS', 'Access Rights');
define('_AM_RWBANNER_UNLIMIT', 'Unlimited');

define('_AM_RWBANNER_SELECT_CAT', 'Please select a category.');
define('_AM_RWBANNER_ENTER_TTL', 'Please enter a title.');
define('_AM_RWBANNER_ENTER_WIDTH', 'Please enter a valid width.');
define('_AM_RWBANNER_ENTER_HEIGHT', 'Please enter a valid height.');
define('_AM_RWBANNER_ENTER_IMAGE', 'You must set an image for the banner. Place the url of an image in the field or select file to upload.');

define('_AM_RWBANNER_DEL_CAT', 'Delete a category');
define('_AM_RWBANNER_CAT_DELETED', 'Category Deleted Successfully');
define('_AM_RWBANNER_CAT_NOT_DELETED', 'Category Could Not Be Deleted:');
define('_AM_RWBANNER_RUSUREDEL', 'Are you sure you want to delete the \'%s\' category?');

define('_AM_RWBANNER_CAT_WIDTH', 'CAT. WIDTH');
define('_AM_RWBANNER_CAT_HEIGHT', 'CAT. HEIGHT');
define('_AM_RWBANNER_SHOW_CAT', 'Show Banners in Category');

define('_AM_RWBANNER_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_RWBANNER_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_RWBANNER_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_RWBANNER_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
