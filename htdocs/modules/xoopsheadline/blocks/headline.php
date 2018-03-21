<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

xoops_load('XoopsheadlineUtility', 'xoopsheadline');

/**
 * @param $options
 * @return array
 */
function b_xoopsheadline_show($options)
{
    global $xoopsConfig;
    $hlDir = basename(dirname(__DIR__));

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($hlDir);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    $block    = [];
    $hlman    = xoops_getModuleHandler('headline', 'xoopsheadline');
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('headline_asblock', 1, '='));
    switch ($moduleConfig['sortby']) {
        case 1:
            $criteria->setSort('headline_name');
            $criteria->setOrder('DESC');
            break;
        case 2:
            $criteria->setSort('headline_name');
            $criteria->setOrder('ASC');
            break;
        case 3:
            $criteria->setSort('headline_weight');
            $criteria->setOrder('DESC');
            break;
        case 4:
        default:
            $criteria->setSort('headline_weight');
            $criteria->setOrder('ASC');
            break;
    }
    $headlines = $hlman->getObjects($criteria);
    $count     = count($headlines);
    for ($i = 0; $i < $count; $i++) {
        $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($headlines[$i]);
        if (!$renderer->renderBlock()) {
            if (2 == $xoopsConfig['debug_mode']) {
                $block['feeds'][] = sprintf(_MD_HEADLINES_FAILGET, $headlines[$i]->getVar('headline_name')) . '<br>' . $renderer->getErrors();
            }
            continue;
        }
        $block['feeds'][] =& $renderer->getBlock();
    }

    return $block;
}
