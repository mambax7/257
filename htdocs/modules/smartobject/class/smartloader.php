<?php
/**
 * Loader for the SmartObject framework
 *
 * This file is responible for including some main files used by the smartobject framework.
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectCore
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php';

/**
 * Include other classes used by the SmartObject
 */
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecthandler.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobject.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectsregistry.php';

/**
 * Including SmartHook feature
 */

require_once SMARTOBJECT_ROOT_PATH . 'class/smarthookhandler.php';
$smarthookHandler = SmartHookHandler::getInstance();

if (!class_exists('smartmetagen')) {
    require_once SMARTOBJECT_ROOT_PATH . 'class/smartmetagen.php';
}
//$smartobjectConfig = smart_getModuleConfig('smartobject');
