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
class SmartAutocompleteElement extends XoopsFormText
{
    public $_include_file;

    /**
     * SmartAutocompleteElement constructor.
     * @param string $caption
     * @param string $name
     * @param int    $include_file
     * @param int    $size
     * @param string $maxlength
     * @param string $value
     */
    public function __construct($caption, $name, $include_file, $size, $maxlength, $value = '')
    {
        $this->_include_file = $include_file;
        parent::__construct($caption, $name, $size, $maxlength, $value);
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $ret = "<input type='text' name='" . $this->getName() . "' id='" . $this->getName() . "' size='" . $this->getSize() . "' maxlength='" . $this->getMaxlength() . "' value='" . $this->getValue() . "'" . $this->getExtra() . '>';

        $ret .= '   <div class="smartobject_autocomplete_hint" id="smartobject_autocomplete_hint' . $this->getName() . '"></div>

    <script type="text/javascript">
        new Ajax.Autocompleter("' . $this->getName() . '","smartobject_autocomplete_hint' . $this->getName() . '","' . $this->_include_file . '?key=' . $this->getName() . '");
    </script>';

        return $ret;
    }
}
