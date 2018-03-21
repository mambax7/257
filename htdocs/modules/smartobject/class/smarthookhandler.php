<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class SmartHookHandler
 */
class SmartHookHandler
{
    /**
     * SmartHookHandler constructor.
     */
    public function __construct()
    {
    }

    /**
     * Access the only instance of this class
     *
     * @return \SmartHookHandler
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $hook_name
     */
    public function executeHook($hook_name)
    {
        $lower_hook_name = strtolower($hook_name);
        $filename        = SMARTOBJECT_ROOT_PATH . 'include/custom_code/' . $lower_hook_name . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            $function = 'smarthook_' . $lower_hook_name;
            if (function_exists($function)) {
                $function();
            }
        }
    }
}
