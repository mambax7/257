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
require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformuploadelement.php';

/**
 * Class SmartFormImageUploadElement
 */
class SmartFormImageUploadElement extends SmartFormUploadElement
{
    /**
     * SmartFormImageUploadElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $this->SmartFormFileElement($object, $key);
        // Override name for upload purposes
        $this->setName('upload_' . $key);
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        return "<input type='hidden' name='MAX_FILE_SIZE' value='" . $this->getMaxFileSize() . "'>
        <input type='file' name='" . $this->getName() . "' id='" . $this->getName() . "'" . $this->getExtra() . ">
        <input type='hidden' name='smart_upload_image[]' id='smart_upload_image[]' value='" . $this->getName() . "'>";
    }
}
