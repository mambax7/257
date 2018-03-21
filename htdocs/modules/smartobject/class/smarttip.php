<?php

/**
 *
 * Module: SmartObject
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

class SmartTip
{
    public $id;
    public $caption;
    public $message;
    public $visible;
    public $_tpl;

    /**
     * SmartTip constructor.
     * @param      $id
     * @param      $caption
     * @param      $message
     * @param bool $visible
     */
    public function __construct($id, $caption, $message, $visible = false)
    {
        $this->id      = $id;
        $this->caption = $caption;
        $this->message = $message;
        $this->visible = $visible;
        $this->_tpl    = new \XoopsTpl();
    }

    /**
     * @param  bool $outputNow
     * @return mixed|string|void
     */
    public function render($outputNow = true)
    {
        $aTip = [
            'id'      => $this->id,
            'caption' => $this->caption,
            'message' => $this->message,
            'visible' => $this->visible ? 'block' : 'none'
        ];
        $this->_tpl->assign('tip', $aTip);
        if ($outputNow) {
            $this->_tpl->display('db:smartobject_tip.tpl');
        } else {
            return $this->_tpl->fetch('db:smartobject_tip.tpl');
        }
    }
}
