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
class SmartFormAutocompleteElement extends SmartAutocompleteElement
{
    /**
     * SmartFormAutocompleteElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var            = $object->vars[$key];
        $control        = $object->controls[$key];
        $form_maxlength = isset($control['maxlength']) ? $control['maxlength'] : (isset($var['maxlength']) ? $var['maxlength'] : 255);

        $form_size = isset($control['size']) ? $control['size'] : 50;
        parent::__construct($var['form_caption'], $key, $form_size, $form_maxlength, $object->getVar($key, 'e'));
    }
}
