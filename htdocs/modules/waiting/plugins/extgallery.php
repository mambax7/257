<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module extgallery                                           #
# Grom - Frxoops                                                         #
#                                                                        #
# Last modified on 21.04.2013                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_extgallery()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];

    // extcal events
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('extgallery_publicphoto') . ' WHERE photo_approved=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/extgallery/admin/photo.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_EVENTS;
    }

    return $block;
}
