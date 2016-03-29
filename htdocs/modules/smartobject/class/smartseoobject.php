<?php

/**
 * Contains the basis classes for managing any objects derived from SmartObjects
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: smartseoobject.php 159 2007-12-17 16:44:05Z malanciault $
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectCore
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";
/**
 * SmartObject base SEO-enabled class
 *
 * Base class representing a single SmartObject with "search engine optimisation" capabilities
 *
 * @package SmartObject
 * @author marcan <marcan@smartfactory.ca>
 * @link http://smartfactory.ca The SmartFactory
 */
class SmartSeoObject extends SmartObject {
    function SmartSeoObject() {
        $this->initCommonVar("meta_keywords");
        $this->initCommonVar("meta_description");
        $this->initCommonVar("short_url");
        $this->seoEnabled = true;
    }

    /**
     * Return the value of the short_url field of this object
     *
     * @return string
     */
    function short_url()
    {
        return $this->getVar('short_url');
    }

    /**
     * Return the value of the meta_keywords field of this object
     *
     * @return string
     */
    function meta_keywords()
    {
        return $this->getVar('meta_keywords');
    }

    /**
     * Return the value of the meta_description field of this object
     *
     * @return string
     */
    function meta_description()
    {
        return $this->getVar('meta_description');
    }
}