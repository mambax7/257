<?php
// not tested
/**
 * @return array
 */
function b_waiting_agendax()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];

    // agenda-x events
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('agendax_events') . ' WHERE approved=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/agendax/admin/index.php?listNewLinks';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_EVENTS;
    }

    return $block;
}
