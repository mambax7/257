<?php
/**
 * @return array
 */
function b_waiting_tutorials()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = [];

    // tutorials
    $myts = \MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('tutorials') . ' WHERE status=0 OR status=2 ORDER BY date');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/tutorials/admin/index.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }

    return $block;
}
