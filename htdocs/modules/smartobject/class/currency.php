<?php
// $Id: currency.php 159 2007-12-17 16:44:05Z malanciault $
// ------------------------------------------------------------------------ //
//               XOOPS - PHP Content Management System                      //
//                   Copyright (c) 2000 XOOPS.org                           //
//                      <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: http://www.xoops.org/                                               //
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

class SmartobjectCurrency extends SmartObject {

    var $_modulePlugin=false;

    function SmartobjectCurrency() {
        $this->quickInitVar('currencyid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('iso4217', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_ISO4217, _CO_SOBJECT_CURRENCY_ISO4217_DSC);
        $this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_NAME);
        $this->quickInitVar('symbol', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_SYMBOL);
        $this->quickInitVar('rate', XOBJ_DTYPE_FLOAT, true, _CO_SOBJECT_CURRENCY_RATE, '', '1.0');
        $this->quickInitVar('default_currency', XOBJ_DTYPE_INT, false, _CO_SOBJECT_CURRENCY_DEFAULT, '', false);

        $this->setControl('symbol', array(
                                        'name' => 'text',
                                        'size' => '15',
                                        'maxlength' => '15'
                                        ));

        $this->setControl('iso4217', array(
                                        'name' => 'text',
                                        'size' => '5',
                                        'maxlength' => '5'
                                        ));

        $this->setControl('rate', array(
                                        'name' => 'text',
                                        'size' => '5',
                                        'maxlength' => '5'
                                        ));

        $this->setControl('rate', array(
                                        'name' => 'text',
                                        'size' => '5',
                                        'maxlength' => '5'
                                        ));

        $this->hideFieldFromForm('default_currency');
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('rate', 'default_currency'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function getCurrencyLink() {
        $ret = $this->getVar('name', 'e');
        return $ret;
    }

    function getCode() {
        $ret = $this->getVar('iso4217', 'e');
        return $ret;
    }

    function rate() {
        return smart_currency($this->getVar('rate', 'e'));
    }

    function default_currency() {
        if ($this->getVar('default_currency', 'e') == true) {
            return _YES;
        } else {
            return _NO;
        }
    }

    function getDefault_currencyControl() {
        $radio_box = '<input name="default_currency" value="' . $this->getVar('currencyid') . '" type="radio"';
        if ($this->getVar('default_currency', 'e')) {
            $radio_box .= 'checked="checked"';
        }
        $radio_box .= '>';
        return $radio_box;
    }

}
class SmartobjectCurrencyHandler extends SmartPersistableObjectHandler {

    function SmartobjectCurrencyHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'currency', 'currencyid', 'name', '', 'smartobject');
    }

    function getCurrencies() {
        $currenciesObj = $this->getObjects(null, true);
        return $currenciesObj;
    }
}
?>