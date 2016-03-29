<?php

/**
 * Contains the SmartObjectControl class
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: smartformautocompleteelement.php 159 2007-12-17 16:44:05Z malanciault $
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectForm
 */
class SmartFormAutocompleteElement extends SmartAutocompleteElement {
    function SmartFormAutocompleteElement($object, $key) {

        $var = $object->vars[$key];
        $control = $object->controls[$key];
        $form_maxlength = isset($control['maxlength']) ? $control['maxlength'] : (isset($var['maxlength']) ? $var['maxlength'] : 255);

        $form_size = isset($control['size']) ? $control['size'] : 50;
        $this->SmartAutocompleteElement($var['form_caption'], $key, $form_size, $form_maxlength, $object->getVar($key, 'e'));
    }
}
?>