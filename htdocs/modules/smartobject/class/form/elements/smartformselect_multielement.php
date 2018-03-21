<?php

/**
 * Contains the SmartObjectControl class
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectForm
 */
require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformselectelement.php';

/**
 * Class SmartFormSelect_multiElement
 */
class SmartFormSelect_multiElement extends SmartFormSelectElement
{
    /**
     * SmartFormSelect_multiElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $this->multiple = true;
        parent::__construct($object, $key);
    }
}
