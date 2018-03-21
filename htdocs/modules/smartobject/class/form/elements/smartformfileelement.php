<?php

/**
 * Class SmartFormFileElement
 */
class SmartFormFileElement extends XoopsFormFile
{
    public $object;
    public $key;

    /**
     * SmartFormFileElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $this->object = $object;
        $this->key    = $key;
        parent::__construct($object->vars[$key]['form_caption'], $key, isset($object->vars[$key]['form_maxfilesize']) ? $object->vars[$key]['form_maxfilesize'] : 0);
        $this->setExtra(' size=50');
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $ret = '';
        if ('' !== $this->object->getVar($this->key)) {
            $ret .= '<div>' . _CO_SOBJECT_CURRENT_FILE . $this->object->getVar($this->key) . '</div>';
        }

        $ret .= "<div><input type='hidden' name='MAX_FILE_SIZE' value='" . $this->getMaxFileSize() . "'>
                <input type='file' name='" . $this->getName() . "' id='" . $this->getName() . "'" . $this->getExtra() . ">
                <input type='hidden' name='smart_upload_file[]' id='smart_upload_file[]' value='" . $this->getName() . "'></div>";

        return $ret;
    }
}
