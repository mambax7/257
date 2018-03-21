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
class SmartFormTextElement extends XoopsFormText
{
    /**
     * SmartFormTextElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var = $object->vars[$key];

        if (isset($object->controls[$key])) {
            $control        = $object->controls[$key];
            $form_maxlength = isset($control['maxlength']) ? $control['maxlength'] : (isset($var['maxlength']) ? $var['maxlength'] : 255);
            $form_size      = isset($control['size']) ? $control['size'] : 50;
        } else {
            $form_maxlength = 255;
            $form_size      = 50;
        }

        parent::__construct($var['form_caption'], $key, $form_size, $form_maxlength, $object->getVar($key, 'e'));
    }
}
