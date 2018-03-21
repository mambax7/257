<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module adslight                                             #
#                                                                        #
# iLuc - Frxoops                                                         #
#                                                                        #
#                                                                        #
# Last modified on 09.05.2010                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_adslight()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];
    $result  = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='No'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/adslight/admin/validate_ads.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }

    return $block;
}
