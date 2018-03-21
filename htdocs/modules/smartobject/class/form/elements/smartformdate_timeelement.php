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
class SmartFormDate_timeElement extends XoopsFormDateTime
{
    /**
     * SmartFormDate_timeElement constructor.
     * @param mixed $object
     * @param mixed $key
     */
    public function __construct($object, $key)
    {
        parent::__construct($object->vars[$key]['form_caption'], $key, 15, $object->getVar($key, 'e'));
    }
}
