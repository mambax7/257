<?php

/**
 *
 * Module: SmartClone
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

function smartobject_plugin_smartsection()
{
    $pluginInfo = [];

    $pluginInfo['items']['item']['caption'] = 'Article';
    $pluginInfo['items']['item']['url']     = 'item.php?itemid=%u';
    $pluginInfo['items']['item']['request'] = 'itemid';

    $pluginInfo['items']['category']['caption'] = 'Category';
    $pluginInfo['items']['category']['url']     = 'category.php?categoryid=%u';
    $pluginInfo['items']['category']['request'] = 'categoryid';

    return $pluginInfo;
}
