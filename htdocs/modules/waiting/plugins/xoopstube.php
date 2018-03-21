<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module xoopstube                                            #
# alain01 - Frxoops                                                      #
#                                                                        #
# Last modified on 25.04.2013                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_xoopstube()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];
    // xoopstube waiting
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE status=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xoopstube/admin/newvideos.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;
    // xoopstube broken
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_broken'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xoopstube/admin/brokenvideo.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }
    $ret[] = $block;
    // xoopstube modreq
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_mod'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xoopstube/admin/modifications.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }
    $ret[] = $block;

    return $ret;
}
