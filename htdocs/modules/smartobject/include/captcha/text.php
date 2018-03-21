<?php

/**
 * Text form for CAPTCHA
 *
 * D.J.
 */
class XoopsCaptchaText
{
    public $config = [];
    public $code;

    /**
     * XoopsCaptchaText constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return XoopsCaptchaText
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
     * Loading configs from CAPTCHA class
     * @param array $config
     */
    public function loadConfig($config = [])
    {
        // Loading default preferences
        $this->config = $config;
    }

    public function setCode()
    {
        $_SESSION['XoopsCaptcha_sessioncode'] = (string)$this->code;
    }

    /**
     * @return string
     */
    public function render()
    {
        $form = $this->loadText() . "&nbsp;&nbsp; <input type='text' name='" . $this->config['name'] . "' id='" . $this->config['name'] . "' size='" . $this->config['num_chars'] . "' maxlength='" . $this->config['num_chars'] . "' value=''>";
        $rule = constant('XOOPS_CAPTCHA_RULE_TEXT');
        if (!empty($rule)) {
            $form .= "&nbsp;&nbsp;<small>{$rule}</small>";
        }

        $this->setCode();

        return $form;
    }

    /**
     * @return string
     */
    public function loadText()
    {
        $val_a = mt_rand(0, 9);
        $val_b = mt_rand(0, 9);
        if ($val_a > $val_b) {
            $expression = "{$val_a} - {$val_b} = ?";
            $this->code = $val_a - $val_b;
        } else {
            $expression = "{$val_a} + {$val_b} = ?";
            $this->code = $val_a + $val_b;
        }

        return "<span style='font-style: normal; font-weight: bold; font-size: 100%; font-color: #333; border: 1px solid #333; padding: 1px 5px;'>{$expression}</span>";
    }
}
