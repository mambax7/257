<?php
/*
 * ****************************************************************************
 * xsitemap - MODULE FOR XOOPS CMS
 * Copyright (c) Urbanspaceman (http://www.takeaweb.it)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module: xsitemap
 *
 * @package    module\Xsitemap\language
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since      1.00
 */

//Menu
define('_AM_XSITEMAP_MANAGER_INDEX', 'Index');

define('_AM_XSITEMAP_THEREARE_PLUGIN_ONLINE', "There are <span class='green bold'>%s</span> Active Plugins");
define('_AM_XSITEMAP_THEREARE_PLUGIN_OFFLINE', "There are <span class='red bold'>%s</span> Inactive Plugins");
define('_AM_XSITEMAP_THEREARE_PLUGIN', "There are <span class='bold'>%s</span> total Plugins in database");
define('_AM_XSITEMAP_PLUGIN_ONLINE_NAMES', "<span class='bold'>Active Plugins:</span> %s");
define('_AM_XSITEMAP_PLUGIN_OFFLINE_NAMES', "<span class='bold'>Inactive Plugins:</span> %s");

define('_AM_XSITEMAP_MANAGER_ABOUT', 'About');
define('_AM_XSITEMAP_MANAGER_PREFERENCES', 'Preferences');
define('_AM_XSITEMAP_MANAGER_UPDATE', 'Update');
define('_AM_XSITEMAP_MANAGER_PERMISSIONS', 'Permissions');

//Index
define('_AM_XSITEMAP_MANAGER_PLUGIN', 'Plugin');

//General
define('_AM_XSITEMAP_BYTES_WRITTEN', '%s bytes written to file.');
define('_AM_XSITEMAP_FORMOK', 'Successfully registered');
define('_AM_XSITEMAP_FORMDELOK', 'Successfully eliminated');
define('_AM_XSITEMAP_FORMSUREDEL', "Are you sure you want to delete : <span style='color: red; font-weight: bold;'> %s </span>");
define('_AM_XSITEMAP_FORMSURERENEW', "Are you sure you want to update : <span style='color: red; font-weight: bold;'> %s </span>");
define('_AM_XSITEMAP_FORMUPLOAD', 'Upload');
define('_AM_XSITEMAP_FORMIMAGE_PATH', 'Files in %s');
define('_AM_XSITEMAP_FORMACTION', 'Action');
define('_AM_XSITEMAP_OFF', 'Inactive');
define('_AM_XSITEMAP_ON', 'Active');
define('_AM_XSITEMAP_CLICK_TO', 'Click to %s plugin');
define('_AM_XSITEMAP_EDIT', 'Edit');
define('_AM_XSITEMAP_DELETE', 'Delete');
define('_AM_XSITEMAP_CREATE_PLUGIN', 'Create Plugin');
define('_AM_XSITEMAP_CREATE', 'Create');
define('_AM_XSITEMAP_PLUGIN_ADD', 'Add a plugin');
define('_AM_XSITEMAP_PLUGIN_EDIT', 'Editing a plugin');
define('_AM_XSITEMAP_PLUGIN_ID', 'Id');
define('_AM_XSITEMAP_PLUGIN_NAME', 'Module name');
define('_AM_XSITEMAP_PLUGIN_MOD_VERSION', 'Version');
define('_AM_XSITEMAP_PLUGIN_MOD_TABLE', "Table of categories of the module (eg for module News Table is 'topics') ");
define('_AM_XSITEMAP_PLUGIN_CAT_ID', "Field for the main category (eg for the module News field is 'topic_id')");
define('_AM_XSITEMAP_PLUGIN_CAT_PID', "Field for the parent category (eg for the module News field is 'topic_pid')");
define('_AM_XSITEMAP_PLUGIN_CAT_NAME', "Field for the category name (eg for the module News field is 'topic_title')");
define('_AM_XSITEMAP_PLUGIN_WEIGHT', "Sort <br> (eg to sort by title the categories of news module, enter 'topic_title')");
define('_AM_XSITEMAP_PLUGIN_CALL', "Call (the call is the path that is invoked by the link in the site map. For the news module eg 'index.php?Storytopic=')");
define('_AM_XSITEMAP_PLUGIN_SUBMITTER', 'Author');
define('_AM_XSITEMAP_PLUGIN_DATE_CREATED', 'Created');
define('_AM_XSITEMAP_PLUGIN_ONLINE', 'Online');

define('_AM_XSITEMAP_PLUGIN_MOD_TABLE_SHORT', 'Category Table');
define('_AM_XSITEMAP_PLUGIN_CAT_ID_SHORT', 'ID main category');
define('_AM_XSITEMAP_PLUGIN_CAT_PID_SHORT', 'ID SubCategories');
define('_AM_XSITEMAP_PLUGIN_CAT_NAME_SHORT', 'Category name');
define('_AM_XSITEMAP_PLUGIN_WEIGHT_SHORT', 'Sorted');
define('_AM_XSITEMAP_PLUGIN_CALL_SHORT', 'Call');

//Permissions
define('_AM_XSITEMAP_PERMISSIONS_ACCESS', 'Allowed to see');
define('_AM_XSITEMAP_PERMISSIONS_SUBMIT', 'Permission to send');

//About.php
define('_AM_XSITEMAP_ABOUT_RELEASEDATE', 'Release Date');
define('_AM_XSITEMAP_ABOUT_AUTHOR', 'Author');
define('_AM_XSITEMAP_ABOUT_CREDITS', 'Credits');
define('_AM_XSITEMAP_ABOUT_CREDITS_TEXT', "The graphics Xsitemap is based on the stylesheet and the images created by <a href='http://www.astuteo.com'> ASTUTEO </a>");
define('_AM_XSITEMAP_ABOUT_TNX', 'Acknowledgments');
define('_AM_XSITEMAP_ABOUT_TNX_TEXT', 'I would to thank <b>chanoir</b> and <b>GIJoe</b> for having developed the original modules SITEMAP that inspired this new <b>XSITEMAP</b>. I would also give thanks to <b>trabis</b> and < b>Alessandro</b> for the help given during the development stages, I also thank the <b>Team Development Module</b> for creating the <b>TDMCreate</b> module that has been of fundamental importance for the realization of <b>XSITEMAP</b>');
define('_AM_XSITEMAP_ABOUT_README', 'Information');
define('_AM_XSITEMAP_ABOUT_MANUAL', 'Help');
define('_AM_XSITEMAP_ABOUT_LICENSE', 'License');
define('_AM_XSITEMAP_ABOUT_MODULE_STATUS', 'Status');
define('_AM_XSITEMAP_ABOUT_MODULE_PLUGIN', 'Forms supported');
define('_AM_XSITEMAP_ABOUT_MODULE_DESC', 'Description');
define('_AM_XSITEMAP_ABOUT_MODULE_DESC_TEXT', 'Module to display the Sitemap');
define('_AM_XSITEMAP_ABOUT_WEBSITE', 'Website');
define('_AM_XSITEMAP_ABOUT_AUTHOR_NAME', 'Author');
define('_AM_XSITEMAP_ABOUT_AUTHOR_WORD', "Author's word");
define('_AM_XSITEMAP_ABOUT_CHANGELOG', 'Change Log');
define('_AM_XSITEMAP_ABOUT_MODULE_INFO', 'About the module');
define('_AM_XSITEMAP_ABOUT_AUTHOR_INFO', 'About the author');
define('_AM_XSITEMAP_ABOUT_DISCLAIMER', 'Disclaimer');
define('_AM_XSITEMAP_ABOUT_DISCLAIMER_TEXT', 'GPL - No warranty');
define('_AM_XSITEMAP_ABOUT_BY', "Powered by <a href ='http://www.takeaweb.it'> TAKEAWEB </a>");

//add by urbanspaceman 22/08/2009
define('_AM_XSITEMAP_ABOUT_TRANSLATION', 'Translation');
define('_AM_XSITEMAP_ABOUT_TRANSLATION_TEXT', '<ul><li>Italiano : Urbanspaceman</li><li>PortgueseBr : Artsgeral</li><li>English : dbman</li></ul>');

//add by urbanspaceman 26/08/2009
//xml.php
define('_AM_XSITEMAP_XML', 'XML');
define('_AM_XSITEMAP_MANAGER_XML', 'Manage XML');
define('_AM_XSITEMAP_XML_LASTUPD', 'Last updated');
define('_AM_XSITEMAP_XML_LOCATION', 'File Location');
define('_AM_XSITEMAP_XML_FILE_SIZE', 'File Size');
define('_AM_XSITEMAP_UPDATE_XML', 'Update XML file');
define('_AM_XSITEMAP_XML_UPDATE', 'XML file updated successfully');
define('_AM_XSITEMAP_XML_ERROR_UPDATE', 'Error during the update of XML file');
define('_AM_XSITEMAP_XML_VIEW_XML', 'View XML file');

// Error Msgs
define('_AM_XSITEMAP_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('_AM_XSITEMAP_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_XSITEMAP_ERROR_BAD_REMOVE', 'Could not delete %s');
define('_AM_XSITEMAP_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_XSITEMAP_ERROR_NO_PLUGIN', 'Could not load plugin');

//1.52

// About.php
define('_AM_XSITEMAP_ABOUT_UPDATEDATE', 'Updated: ');
define('_AM_XSITEMAP_ABOUT_DESCRIPTION', 'Description: ');
define('_AM_XSITEMAP_ADMIN_ABOUT', 'About');

// Text for Admin footer
define('_AM_XSITEMAP_FOOTER', "<div class='center smallsmall italic pad5'>xsitemap is maintained by the <a class='tooltip' rel='external' href='https://xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>");

define('_AM_XSITEMAP_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_XSITEMAP_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_XSITEMAP_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_XSITEMAP_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_XSITEMAP_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
