<?php

require_once XOOPS_ROOT_PATH . '/modules/popnupblog/popnupblog.php';

/**
 * @return array
 */
function b_waiting_popnupblog()
{
    $result                  = [];
    $result['adminlink']     = XOOPS_URL . '/modules/popnupblog/admin/index.php';
    $result['pendingnum']    = popnupblog::getApplicationNum();
    $result['lang_linkname'] = _PI_WAITING_WAITINGS;

    return $result;
}
