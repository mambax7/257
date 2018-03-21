<?php
/**
 * @return array
 */
function b_waiting_yomi()
{
    $log_path      = XOOPS_ROOT_PATH . '/modules/yomi/log/';
    $log_file      = 'ys4_temp.cgi';
    $lang_linkname = 'Yomi';
    $block         = [];
    $cTemp         = 0;
    $fp            = fopen("{$log_path}{$log_file}", 'r');

    while ($tmp = fgets($fp, 4096)) {
        ++$cTemp;
    }
    fclose($fp);

    if (0 !== $cTemp) {
        $block = [
            'adminlink'     => XOOPS_URL . '/modules/yomi/admin.php',
            'pendingnum'    => $cTemp,
            'lang_linkname' => _PI_WAITING_WAITINGS
        ];
    }

    return $block;
}
