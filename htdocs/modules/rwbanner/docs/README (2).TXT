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

[b]DESCRIPTION:[/b]
===============================
Xoops module for managing and showing random banners, that can be used or not together with the default xoops banner system.

The aim of this module is to allow the site administrator to have an organized method, robust and efficient to manage medias and advertising spaces in his site.

[b]CHARACTERISTICS:[/b]
===============================
- Register of Banners.
- Register of banners categories.
- Register of models of banners.
- Register of Tags of banners.
- Register of clients of banners.
- 5 models of exhibition of banners. Including blocks in Ajax.
- All blocks are clonable.
- Block to statisticians of clients and your banners.
- Activation/Deactivation of banners with 1(one) click.
- Inactive Banners can continue in the data base.
- Create Smarty Tag´s for use in its themes, modules, blocks and content.
- Client can verify the situation of its banners when logged in the site.
- The client will be able to make adjustments in its banners (if the site admin to allow)
- Send communication e-mail to clients about your banners statisticians.
- Some random modalities of use .
- You will be able to create sets of banners intercalated by groups.
- Accepted the exposition of more than 1 banner in the blocks.


FEATURES:
- Registering of banners separated by categories and clients. The categories are used to differnciate the size of the banners so that you can create several banner exhibition models. The clients are users registered at your site and when you add a banner you have to associate a banner with one of the registered users.
- 5 block models to show cloneable banners and with several configurations, allowing the administrator choose how many random banners he wants to show, which category should be shown and further, the option to create a table for the banner exhibition having only to select in how many columns they should be shown.
- Statistics block for the client. In this block is shown a table with the main statistics of all the banners related to the client.
- Activation and deactivation option of banners by one click. Only active banners will be shown but the inactive ones continue registered in the database, allowing you to reactivate them at any time.
- Beyond the banners exhibition blocks it is also possible to use the random banner in the theme of your site or in any other page you wish, having only to add the <{$rw_banner}> tag (this feature requires a small hack in the xops core that will be explained in the installation instructions below).
- Statistics page for the client. In the main page of the module is a table with all the statistics of the banners for the logged client. Further, in this page the client has the option to send the banner statistics for his registered e-mail. Página de Estatísticas do Cliente. Na página principal do módulo é exibida uma tabela com todas as estatísticas dos banners and if the administrator allows he will be able to change some information of the banner.- Showing or not random banners in the text of other modules. Now you can show your banners in any module that accepts bbcodes. You can publish banners in news, articles, static pages, including in any forum posts. (this feature requires a small hack in the xops core that will be explained in the installation instructions below).

INSTALLATION:
The installation of this modules is very easy than it follows the installation procedure of all other modules.
O activate the function for the random banner exhibition in the theme of your site you need to perform a small hack in the xoops core, more precisely in the header.php file, located at the root of your site.

To include this back, open the file XOOPS_ROOT_PATH/header.php and in the end of the file add before the } ?>, the code below:

###########################################################################################
#### Hack by rw-banner
#### Create smarty vars to show the banner in the theme of your site and in the templates
###########################################################################################
$mod = XoopsModule::getByDirname("rwbanner");
if ($mod){
  include_once (dirname(__DIR__) .'/include/maketags.php');
}
###########################################################################################
#### End of the Hack by rw-banner
###########################################################################################

Step one is done! You activated the SMARTY TAGS function for the module and now via the admin area of the module you can create, edit, change the status and delete the tags. Every time you want to show your random banner, in the theme or in any module templates you just need to create a tag for the desired advertising model and insert it into the theme.html file or in the template.

To activate the new function for the text banner exhibition you need to perform another hack in the xoops core, in the  XOOPS_ROOT_PATH/class/module.textsanitizer.php file.
To add the hack, open the XOOPS_ROOT_PATH/class/module.textsanitizer.php file and look for the function called xoopsCodeDecode, and in the end of the function you need to add the following code:

#######################################################################################
#### Hack by rw-banner
#### Allow the exhibition of random banners an any module with bbcodes
#######################################################################################
$mod = XoopsModule::getByDirname("rwbanner");
if ($mod){
  include(dirname(__DIR__) .'/include/bbcode.php');
}
#######################################################################################
#### end of the Hack by rw-banner
#######################################################################################

To make it easier to understand, you can find in the docs folder the function xoopsCodeDecode already hacked, you just need to follow the example and apply the hack into your file.
After applying the hack to show your banner in your texts you just need to create the tags in the administration area for the module in the same way you create the tags for exhibition in your theme or module templates.

When you have already created the tags you just need to go the module you want to show the banner and in the text field (I must be able to accept bbcodes!!), add the tag in the same way that you do in your theme. The only difference is in the tags declaration.
Look at the example below:

Examples:
Let's imagine that you have already created and setup in the administration area. To show the banners from this tag in your site you would add::

<{$publicidade}>

But in the body of your news or any othger module that accepts bbcodes you have to add the TAG as follows:

[RW align=center]publicidade[/RW]

The parameter align accpets the values "left" for left alignment, "right"for right alignment and "center" for center alignment.


UPDATES:
The modules had structural changes in relation to the database in relation to the previous verions. If you use any versions prior to 1.3RC we recommend you to unistall the module before installing the new version.
In case you have a version newer than the 1.3RC, delete the prefix('rwbanner_banner' folder and replace it by the folder from the new version. Login as administrator to your site. If you use the hack in header.php file, comment it first. Access via browser the URL "XOOPS_URL/modules/rwbanner/admin/upgrade.php" and the script will update the tables automatically without loosing all the setup you have already defined and all the registered banners. Follow the steps described in the script and your module will be updated.

KNOWN BUGS:
-Banners in HTML code don't have their click counted. This bug is originally from the original xoops banner module. This function is used in our module but with some changes.

THANKS:
I thank all my friends from the XoopsTotal community that helped me with ideas and suggestions for the development of this module, in special to my great friend Wilson and Giba (board of XT) for their great help with their ideias and help in testing the module.

For further information or problem solving for this module access the following site: http://rwbanner.brinfo.com.br

[b]ITS DISPOSAL:[/b]
===============================
We will be inside of the possible always creating examples of as to use rwbanner and thus you will be able to get the biggest possible advantage to use this module.

If you will be using this module, does not leave to visit our site of development to suggest new features.

It also uses to advantage to fill our book of satisfied users and to order its message so that other users whom to desire to use this module can know that it functions and also he is very useful.

Rw_banner Team Leader.

[b]TheRpLima[/b]
