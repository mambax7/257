<?php

/**
 *
 * Module: SmartClone
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

function smartobject_plugin_xcgal()
{
    global $xoopsConfig;

    $pluginInfo                              = [];
    $pluginInfo['items']['album']['caption'] = 'Album';
    $pluginInfo['items']['item']['url']      = 'thumbnails.php?album=%u';
    $pluginInfo['items']['item']['request']  = 'album';

    return $pluginInfo;
}
