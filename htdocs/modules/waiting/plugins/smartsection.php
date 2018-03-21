<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module smartsection                                         #
#                                                                        #
# Author                                                                 #
# flying.tux     -   flying.tux@gmail.com                                #
#                                                                        #
# Last modified on 21.04.2005                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_smartsection()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // smartsection submitted
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('smartsection_items') . ' WHERE status=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/smartsection/admin/index.php?statussel=1';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_SUBMITTED;
    }
    $ret[] = $block;

    return $ret;
}
