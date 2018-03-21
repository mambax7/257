<?php
/**
 * @return array
 */
function b_waiting_eguide()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];

    // eguide
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('eguide') . ' WHERE status=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/eguide/admin/index.php?op=events';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }

    return $block;
}
