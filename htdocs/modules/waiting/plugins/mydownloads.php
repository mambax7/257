<?php
/**
 * @return array
 */
function b_waiting_mydownloads()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // mydownloads links
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('mydownloads_downloads') . ' WHERE status=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/mydownloads/admin/index.php?op=listNewDownloads';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // mydownloads broken
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('mydownloads_broken'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/mydownloads/admin/index.php?op=listBrokenDownloads';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }
    $ret[] = $block;

    // mydownloads modreq
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('mydownloads_mod'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/mydownloads/admin/index.php?op=listModReq';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }
    $ret[] = $block;

    return $ret;
}
