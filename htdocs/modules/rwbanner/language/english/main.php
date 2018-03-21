<?php
//  ------------------------------------------------------------------------ //
//                                  RW-Banner                                //
//                    Copyright (c) 2006 BrInfo                              //
//                     <http://www.brinfo.com.br>                            //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author: Rodrigo Pereira Lima (BrInfo - Soluções Web)                      //
// Site: http://www.brinfo.com.br                                            //
// Project: RW-Banner                                                        //
// Descrição: Sistema de gerenciamento de mídias publicitárias               //
// ------------------------------------------------------------------------- //

//index.php
define('_MD_RWBANNER_NOPERM1', 'You don\'t have any banners registered at your site therefore you don\'t have permissions to access this area!');
define('_MD_RWBANNER_MSG_INDEX_NOUSER', 'To use the advertising services of %s you need to be a registered user.
   This procedure aims to have a better control of our partners and a better management of our advertising media.<br><br>
   If you are already registered at our site, click <a href=\"%s\">here</a> and login to fill out the banner registration
   form and join our advertising system. If you are not registered at our site, click <a href=\"%s\">here</a> and
   register yourself. After you have registered on our site you will be able to join our advertising system.');
define('_MD_RWBANNER_MSG_INDEX_OLAUSER', 'Hello %s, welcome to the Advertising System on %s.<br><br>');
define('_MD_RWBANNER_MSG_INDEX_NOBANNER', 'You don\'t have any banners registered at our site. If you wish to take part in our advertising system, please fill out the banner registration form by clicking <a href="inser.php">here</a>.');
define('_MD_RWBANNER_MSG_INDEX_NOBANNER1', 'Self registration of new banners is disabled at the moment, if you want to include a banner in our system, please contact the site administrator.');
//define('_MD_RWBANNER_CARREG', 'Loading...');
define('_MD_RWBANNER_MSG_INDEX_CADBANNER', 'To add new banners to the system click <a href="inser.php">here</a>');
define('_MD_RWBANNER_MSG_NEWBANNER', 'After you have registered a banner it will be analyzed by the staff in charge. One of the staff members will get in touch with you to define the final details about the banner. If the banner is approved, it will be activated and will be shown on the site.');
define('_MD_RWBANNER_TITLE1', 'Your registered banners');
define('_MD_RWBANNER_TITLE2', 'ID ');
define('_MD_RWBANNER_TITLE3', 'CATEGORY');
define('_MD_RWBANNER_TITLE4', 'IMPRESSIONS');
define('_MD_RWBANNER_TITLE5', 'IMP. LEFT');
define('_MD_RWBANNER_TITLE6', 'CLICKS');
define('_MD_RWBANNER_TITLE7', '% OF CLICKS');
define('_MD_RWBANNER_TITLE8', 'CREATION DATE');
define('_MD_RWBANNER_TITLE9', 'STATUS');
define('_MD_RWBANNER_TITLE10', 'OPTIONS');
define('_MD_RWBANNER_TITLE16', 'CLICKS LEFT.');

//define('_MD_RWBANNER_STATUS1', 'Banner Active');
//define('_MD_RWBANNER_STATUS2', 'Banner Inactive');

define('_MD_RWBANNER_EXIBREST', 'Unlimited');

define('_MD_EMAIL_STATS', 'Send statistics via email');

//define('_MD_RWBANNER_TITLE11', 'Statistics for the banner #');
//define('_MD_RWBANNER_TITLE12', 'Image:');
//define('_MD_RWBANNER_TITLE13', 'Link:');
//define('_MD_RWBANNER_TITLE14', 'Change Link');
//define('_MD_RWBANNER_TITLE15', 'Send the statistics of this banner per e-mail');

define('_MD_RWBANNER_BUTTON1', 'Order');
define('_MD_RWBANNER_BUTTON2', 'Banners per page');

//include/function.php
define('_MD_RWBANNER_SUBJECT_EMAILSTATS', 'Statistics for your banner at our site');
define('_MD_RWBANNER_BODY1_EMAILSTATS', 'Below are the complete statistics of your banner at our site');
define('_MD_RWBANNER_BODY2_EMAILSTATS', 'Client\'s Name:');
define('_MD_RWBANNER_BODY3_EMAILSTATS', 'Banner Code:');
define('_MD_RWBANNER_BODY4_EMAILSTATS', 'Banner Image:');
define('_MD_RWBANNER_BODY5_EMAILSTATS', 'Banner Link:');
define('_MD_RWBANNER_BODY6_EMAILSTATS', 'Acquired Impressions:');
define('_MD_RWBANNER_BODY7_EMAILSTATS', 'Used Impressions:');
define('_MD_RWBANNER_BODY8_EMAILSTATS', 'Impressions Left:');
define('_MD_RWBANNER_BODY9_EMAILSTATS', 'Clicks Received:');
define('_MD_RWBANNER_BODY10_EMAILSTATS', 'Percentage of Clicks:');
define('_MD_RWBANNER_BODY11_EMAILSTATS', 'Acquired Clicks:');
define('_MD_RWBANNER_BODY12_EMAILSTATS', 'Clicks Left:');
define('_MD_RWBANNER_BODY13_EMAILSTATS', 'Registration Date:');
define('_MD_RWBANNER_BODY14_EMAILSTATS', 'Acquisition Period:');
define('_MD_RWBANNER_BODY15_EMAILSTATS', 'End of the Period:');
define('_MD_RWBANNER_BODY16_EMAILSTATS', 'Report generated on:');
define('_MD_RWBANNER_BODY17_EMAILSTATS', 'days');

define('_MD_RWBANNER_MSG_SUCESS_EMAILSTATS', 'The statistics of your banner were send successfully to your e-mail registered at our site.');
define('_MD_RWBANNER_MSG_FAIL_EMAILSTATS', 'There was an error trying to send the statistics to your e-mail. Please, try later again.');

//inser.php
define('_MD_RWBANNER_BTN_OP1', 'Add');
define('_MD_RWBANNER_BTN_OP2', 'Edit');
define('_MD_RWBANNER_BTN_OP3', 'Unlimited');

define('_MD_RWBANNER_TITLE24', 'Display number:');
define('_MD_RWBANNER_TITLE500', 'Number of clicks:');
define('_MD_RWBANNER_TITLE5001', 'Display Period (in days):');
define('_MD_RWBANNER_TITLE25', 'Image(URL):');
define('_MD_RWBANNER_TITLE26', 'Link(URL):');
define('_MD_RWBANNER_TITLE27', 'Use HTML?');
define('_MD_RWBANNER_TITLE28', 'HTML Code:');
//define('_MD_RWBANNER_TITLE29', 'Target:');
define('_MD_RWBANNER_TITLE51_ED', 'Upload Banner:');
define('_MD_RWBANNER_TITLE5000', 'Notes:');
define('_MD_RWBANNER_TITLE5000_DESC', 'Write in here any notes about your banner, tell the Admin of the site where you want your banner to be shown, how often, etc, define everything that is necessary for the display of your banner.');

define('_MD_RWBANNER_VALUE_BTN1', 'Add new Banner');
define('_MD_RWBANNER_VALUE_BTN3', 'Edit Banner');
define('_MD_RWBANNER_VALUE_BTN10_ED', 'Send');

define('_MD_RWBANNER_MSG2', 'Banner changed successfully!!');
define('_MD_RWBANNER_MSG8', 'Banner added successfully!!');
define('_MD_RWBANNER_MSG10', 'There was a problem adding the banner.');
define('_MD_RWBANNER_MSG11', 'There was a problem editing the banner.');

define('_MD_RWBANNER_TAG_ERROR', '<div style="color: #FE2626;">There is something wrong with the banner display</div>');
define('_MD_RWBANNER_TAG_ERROR1', "<div style='color: #FE2626;'>The TAG <b>\' %s\'< /b> level exists, before you use a TAG it should be registered by the administrator.</div>");

define('_MD_RWBANNER_SORT_CLICKS', 'Clicks');
define('_MD_RWBANNER_SORT_ID', 'ID');
define('_MD_RWBANNER_SORT_DATE', 'Registration');
define('_MD_RWBANNER_SORT_IMPRESSIONS', 'Impressions');

define('_MD_RWBANNER_YES', 'Yes');
