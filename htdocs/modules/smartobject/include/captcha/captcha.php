<?php

/**
 * CAPTCHA class For XOOPS
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through XoopsFormCaptcha::setConfig("mode", $mode), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * D.J.
 */
class XoopsCaptcha
{
    public $active = true;
    public $mode   = 'text';    // potential values: image, text
    public $config = [];

    public $message = []; // Logging error messages

    /**
     * XoopsCaptcha constructor.
     */
    public function __construct()
    {
        // Loading default preferences
        $this->config = @include __DIR__ . '/config.php';

        $this->setMode($this->config['mode']);
    }

    /**
     * @return XoopsCaptcha
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
     * @param $name
     * @param $val
     * @return bool
     */
    public function setConfig($name, $val)
    {
        if ('mode' === $name) {
            $this->setMode($val);
        } elseif (isset($this->$name)) {
            $this->$name = $val;
        } else {
            $this->config[$name] = $val;
        }

        return true;
    }

    /**
     * Set CAPTCHA mode
     *
     * For future possible modes, right now force to use text or image
     *
     * @param string $mode if no mode is set, just verify current mode
     */
    public function setMode($mode = null)
    {
        if (!empty($mode) && in_array($mode, ['text', 'image'])) {
            $this->mode = $mode;

            if ('image' !== $this->mode) {
                return;
            }
        }

        // Disable image mode
        if (!extension_loaded('gd')) {
            $this->mode = 'text';
        } else {
            $required_functions = [
                'imagecreatetruecolor',
                'imagecolorallocate',
                'imagefilledrectangle',
                'imagejpeg',
                'imagedestroy',
                'imageftbbox'
            ];
            foreach ($required_functions as $func) {
                if (!function_exists($func)) {
                    $this->mode = 'text';
                    break;
                }
            }
        }
    }

    /**
     * Initializing the CAPTCHA class
     * @param string $name
     * @param null   $skipmember
     * @param null   $num_chars
     * @param null   $fontsize_min
     * @param null   $fontsize_max
     * @param null   $background_type
     * @param null   $background_num
     */
    public function init(
        $name = 'xoopscaptcha',
        $skipmember = null,
        $num_chars = null,
        $fontsize_min = null,
        $fontsize_max = null,
        $background_type = null,
        $background_num = null
    ) {
        // Loading RUN-TIME settings
        foreach (array_keys($this->config) as $key) {
            if (isset(${$key}) && null !== ${$key}) {
                $this->config[$key] = ${$key};
            }
        }
        $this->config['name'] = $name;

        // Skip CAPTCHA for member if set
        if ($this->config['skipmember'] && is_object($GLOBALS['xoopsUser'])) {
            $this->active = false;
        }
    }

    /**
     * Verify user submission
     * @param  null $skipMember
     * @return bool
     */
    public function verify($skipMember = null)
    {
        $sessionName = @$_SESSION['XoopsCaptcha_name'];
        $skipMember  = (null === $skipMember) ? @$_SESSION['XoopsCaptcha_skipmember'] : $skipMember;
        $maxAttempts = (int)(@$_SESSION['XoopsCaptcha_maxattempts']);

        $is_valid = false;

        // Skip CAPTCHA for member if set
        if (is_object($GLOBALS['xoopsUser']) && !empty($skipMember)) {
            $is_valid = true;
        // Kill too many attempts
        } elseif (!empty($maxAttempts) && $_SESSION['XoopsCaptcha_attempt_' . $sessionName] > $maxAttempts) {
            $this->message[] = XOOPS_CAPTCHA_TOOMANYATTEMPTS;

        // Verify the code
        } elseif (!empty($_SESSION['XoopsCaptcha_sessioncode'])) {
            $func     = $this->config['casesensitive'] ? 'strcmp' : 'strcasecmp';
            $is_valid = !$func(trim(@$_POST[$sessionName]), $_SESSION['XoopsCaptcha_sessioncode']);
        }

        if (!empty($maxAttempts)) {
            if (!$is_valid) {
                // Increase the attempt records on failure
                $_SESSION['XoopsCaptcha_attempt_' . $sessionName]++;
                // Log the error message
                $this->message[] = XOOPS_CAPTCHA_INVALID_CODE;
            } else {

                // reset attempt records on success
                $_SESSION['XoopsCaptcha_attempt_' . $sessionName] = null;
            }
        }
        $this->destroyGarbage(true);

        return $is_valid;
    }

    /**
     * @return mixed|string
     */
    public function getCaption()
    {
        return defined('XOOPS_CAPTCHA_CAPTION') ? constant('XOOPS_CAPTCHA_CAPTION') : '';
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return implode('<br>', $this->message);
    }

    /**
     * Destory historical stuff
     * @param  bool $clearSession
     * @return bool
     */
    public function destroyGarbage($clearSession = false)
    {
        require_once __DIR__ . '/' . $this->mode . '.php';
        $class          = 'XoopsCaptcha' . ucfirst($this->mode);
        $captchaHandler = new $class();
        if (method_exists($captchaHandler, 'destroyGarbage')) {
            $captchaHandler->loadConfig($this->config);
            $captchaHandler->destroyGarbage();
        }

        if ($clearSession) {
            $_SESSION['XoopsCaptcha_name']        = null;
            $_SESSION['XoopsCaptcha_skipmember']  = null;
            $_SESSION['XoopsCaptcha_sessioncode'] = null;
            $_SESSION['XoopsCaptcha_maxattempts'] = null;
        }

        return true;
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $form = '';

        if (!$this->active || empty($this->config['name'])) {
            return $form;
        }

        $_SESSION['XoopsCaptcha_name']        = $this->config['name'];
        $_SESSION['XoopsCaptcha_skipmember']  = $this->config['skipmember'];
        $maxAttempts                          = $this->config['maxattempt'];
        $_SESSION['XoopsCaptcha_maxattempts'] = $maxAttempts;
        /*
         if (!empty($maxAttempts)) {
         $_SESSION['XoopsCaptcha_maxattempts_'.$_SESSION['XoopsCaptcha_name']] = $maxAttempts;
         }
         */

        // Fail on too many attempts
        if (!empty($maxAttempts) && @$_SESSION['XoopsCaptcha_attempt_' . $this->config['name']] > $maxAttempts) {
            $form = XOOPS_CAPTCHA_TOOMANYATTEMPTS;
        // Load the form element
        } else {
            $form = $this->loadForm();
        }

        return $form;
    }

    /**
     * @return mixed
     */
    public function loadForm()
    {
        require_once __DIR__ . '/' . $this->mode . '.php';
        $class          = 'XoopsCaptcha' . ucfirst($this->mode);
        $captchaHandler = new $class();
        $captchaHandler->loadConfig($this->config);

        $form = $captchaHandler->render();

        return $form;
    }
}
