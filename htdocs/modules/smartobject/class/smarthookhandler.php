<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SmartHookHandler {

    function SmartHookHandler() {

    }

    /**
     * Access the only instance of this class
     *
     * @return  object
     *
     * @static
     * @staticvar   object
     */
    function &getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new SmartHookHandler();
        }
        return $instance;
    }

    function executeHook($hook_name) {
        $lower_hook_name = strtolower($hook_name);
        $filename = SMARTOBJECT_ROOT_PATH . 'include/custom_code/' . $lower_hook_name . '.php';
        if (file_exists($filename)) {
            include_once($filename);
            $function = 'smarthook_' . $lower_hook_name;
            if (function_exists($function)) {
                $function();
            }
        }
    }

}
?>