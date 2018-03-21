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
class SmartFormSelectElement extends XoopsFormSelect
{
    public $multiple = false;

    /**
     * SmartFormSelectElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var  = $object->vars[$key];
        $size = isset($var['size']) ? $var['size'] : ($this->multiple ? 5 : 1);

        // Adding the options inside this SelectBox
        // If the custom method is not from a module, than it's from the core
        $control = $object->getControl($key);

        $value = isset($control['value']) ? $control['value'] : $object->getVar($key, 'e');

        parent::__construct($var['form_caption'], $key, $value, $size, $this->multiple);

        if (isset($control['options'])) {
            $this->addOptionArray($control['options']);
        } else {
            // let's find if the method we need to call comes from an already defined object
            if (isset($control['object'])) {
                if (method_exists($control['object'], $control['method'])) {
                    if ($option_array = $control['object']->{$control['method']}()) {
                        // Adding the options array to the XoopsFormSelect
                        $this->addOptionArray($option_array);
                    }
                }
            } else {
                // finding the itemHandler; if none, let's take the itemHandler of the $object
                if (isset($control['itemHandler'])) {
                    if (!$control['module']) {
                        // Creating the specified core object handler
                        $controlHandler = xoops_getHandler($control['itemHandler']);
                    } else {
                        $controlHandler = xoops_getModuleHandler($control['itemHandler'], $control['module']);
                    }
                } else {
                    $controlHandler = $object->handler;
                }

                // Checking if the specified method exists
                if (method_exists($controlHandler, $control['method'])) {
                    // TODO: How could I pass the parameters in the following call ...
                    if ($option_array = $controlHandler->{$control['method']}()) {
                        // Adding the options array to the XoopsFormSelect
                        $this->addOptionArray($option_array);
                    }
                }
            }
        }
    }
}
