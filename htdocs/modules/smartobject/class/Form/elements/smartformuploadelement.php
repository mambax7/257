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
class SmartFormUploadElement extends XoopsFormFile
{
    /**
     * @param $object
     * @param $key
     */
    public function SmartFormFileElement($object, $key)
    {
        parent::__construct(_CO_SOBJECT_UPLOAD, $key, isset($object->vars[$key]['form_maxfilesize']) ? $object->vars[$key]['form_maxfilesize'] : 0);
        $this->setExtra(' size=50');
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
                <input type='hidden' name='smart_upload_file[]' id='smart_upload_file[]' value='" . $this->getName() . "'>";
    }
}
