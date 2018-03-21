<?php

/**
 *
 * Module: SmartClone
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

function smartobject_plugin_smartpartner()
{
    $pluginInfo = [];

    $pluginInfo['items']['partner']['caption'] = 'Partner';
    $pluginInfo['items']['partner']['url']     = 'partner.php?partnerid=%u';
    $pluginInfo['items']['partner']['request'] = 'partnerid';

    $pluginInfo['items']['category']['caption'] = 'Category';
    $pluginInfo['items']['category']['url']     = 'index.php?view_category_id=%u';
    $pluginInfo['items']['category']['request'] = 'view_category_id';

    return $pluginInfo;
}
