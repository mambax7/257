<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class SmartFormCheckElement
 */
class SmartFormCheckElement extends XoopsFormCheckBox
{
    /**
     *
     * /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        if (count($this->getOptions()) > 1 && '[]' !== substr($this->getName(), -2, 2)) {
            $newname = $this->getName() . '[]';
            $this->setName($newname);
        }
        foreach ($this->getOptions() as $value => $name) {
            $ret .= "<input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
            if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
                $ret .= ' checked';
            }
            $ret .= $this->getExtra() . '>' . $name . '<br>';
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function renderValidationJS()
    {
        $js .= 'var hasSelections = false;';
        //sometimes, there is an implicit '[]', sometimes not
        $eltname = $this->getName();
        if (false === strpos($eltname, '[')) {
            $js .= "for (var i = 0; i < myform['{$eltname}[]'].length; i++) {
                if (myform['{$eltname}[]'][i].checked) {
                    hasSelections = true;
                }

            }
            if (hasSelections === false) {
                window.alert(\"{$eltmsg}\"); myform['{$eltname}[]'][0].focus(); return false; }\n";
        } else {
            $js .= "for (var i = 0; i < myform['" . $eltname . "'].length; i++) {
                if (myform['{$eltname}'][i].checked) {
                    hasSelections = true;
                }

            }
            if (hasSelections === false) {
                window.alert(\"{$eltmsg}\"); myform['{$eltname}'][0].focus(); return false; }\n";
        }

        return $js;
    }
}
