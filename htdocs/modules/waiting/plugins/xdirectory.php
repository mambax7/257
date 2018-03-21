<?php
// This code is not tested
/**
 * @return array
 */
function b_waiting_xdirectory()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // xdirectory links
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xdir_links') . ' WHERE status=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xdirectory/admin/index.php?op=listNewLinks';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_LINKS;
    }
    $ret[] = $block;

    // xdirectory broken
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xdir_broken'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xdirectory/admin/index.php?op=listBrokenLinks';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }
    $ret[] = $block;

    // xdirectory modreq
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xdir_mod'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/xdirectory/admin/index.php?op=listModReq';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }
    $ret[] = $block;

    return $ret;
}
