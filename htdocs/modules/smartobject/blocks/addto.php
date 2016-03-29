<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function smartobject_addto_show($options)
{
    include_once(XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php');
    include_once(SMARTOBJECT_ROOT_PATH . 'class/smartaddto.php');
    $smartaddto = new SmartAddTo($options[0]);
    $block = $smartaddto->renderForBlock();
    return $block;
}

function smartobject_addto_edit($options)
{
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $form = '';

    $layout_select = new XoopsFormSelect(_MB_SOBJECT_BLOCKS_ADDTO_LAYOUT, 'options[]', $options[0]);
    $layout_select->addOption(0, _MB_SOBJECT_BLOCKS_ADDTO_LAYOUT_OPTION0);
    $layout_select->addOption(1, _MB_SOBJECT_BLOCKS_ADDTO_LAYOUT_OPTION1);
    $layout_select->addOption(2, _MB_SOBJECT_BLOCKS_ADDTO_LAYOUT_OPTION2);
    $layout_select->addOption(3, _MB_SOBJECT_BLOCKS_ADDTO_LAYOUT_OPTION3);
    $form .= $layout_select->getCaption() . ' ' . $layout_select->render() . '<br />';

    return $form;
}

?>