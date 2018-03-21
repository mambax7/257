<?php
/*
* Smarty plugin
* http://devcodepro.com/view/12/1/Smarty-Remove-BBCode
-----------------------------------------------------
* File: modifier.removebb.php
* Type: modifier
* Name: removebb
* Version: 1.2
* Date: 2016-11-10
* Purpose: Remove BBCode From String
* Install: Drop into the plugin directory.
* Author: Ronald
* Example Usage {$m.foo|removebb}
-------------------------------------------------------------
*/

/**
 * smarty_modifier_removebb()
 *
 * @param mixed $string
 * @return mixed
 */

//libs/plugins/modifier.removebb.php

function smarty_modifier_removebb($string)
{
    $find    = '|[[/!]*?[^[]]*?]|si';
    $replace = '';

    return preg_replace($find, $replace, $string);
}
