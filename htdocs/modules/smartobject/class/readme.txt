/**
 * Installation procedure of the smartObejct Framework
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectCore
 */

In order to install and use the SmartObject Framework, please follow these instructions :

1- Copy the folder class/smartobject into yoursite.com/class/

2- Edit the file include/common.php and locate these lines :

    // #################### Include site-wide lang file ##################
    if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/global.php") ) {
        include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/global.php";
    } else {
        include_once XOOPS_ROOT_PATH."/language/english/global.php";
    }

Juste BEFORE these lines, please include this :

    // #################### Include SmartObject Framework ##################
    include_once XOOPS_ROOT_PATH."/class/smartobject/smartloader.php";

We have included an example of the modified file in include/common.php

Please report any bugs on The SmartFactory (http://smartfactory.ca)


.:: marcan aka Marc-André Lanciault ::.
.:: Open Source :: smartfactory.ca::.
.:: Professionnal :: inboxsolutions.net ::.