<?php
/**
 * @return array
 */
function b_waiting_extcal()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];

    // extcal events
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('extcal_event') . ' WHERE event_approved=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/extcal/admin/index.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_EVENTS;
    }

    return $block;
}
