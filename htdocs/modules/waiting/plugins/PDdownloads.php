<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module PDdownloads                                          #
#                                                                        #
# Author                                                                 #
# flying.tux     -   flying.tux@gmail.com                                #
#                                                                        #
# Last modified on 21.04.2005                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_PDdownloads()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // PDdownloads waiting
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('PDdownloads_downloads') . ' WHERE status=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/PDdownloads/admin/newdownloads.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // PDdownloads broken
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('PDdownloads_broken'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/PDdownloads/admin/brokendown.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }
    $ret[] = $block;

    // PDdownloads modreq
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('PDdownloads_mod'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/PDdownloads/admin/modifications.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }
    $ret[] = $block;

    return $ret;
}
