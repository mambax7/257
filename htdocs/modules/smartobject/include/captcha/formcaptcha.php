<?php
/**
 * Adding CAPTCHA
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through XoopsFormCaptcha::setMode(), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * D.J.
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php';

/*
 * Usage
 *
 * For form creation:
 * 1 Add [require_once XOOPS_ROOT_PATH."/Frameworks/captcha/formcaptcha.php";] to class/xoopsformloader.php, OR add to the file that uses CAPTCHA before calling XoopsFormCaptcha
 * 2 Add form element where proper: $xoopsform->addElement(new \XoopsFormCaptcha($caption, $name, $skipmember, ...);
 *
 * For verification:
 *   if (@require_once XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php") {
 *      $xoopsCaptcha = XoopsCaptcha::getInstance();
 *      if (! $xoopsCaptcha->verify() ) {
 *          echo $xoopsCaptcha->getMessage();
 *          ...
 *      }
 *  }
 *
 */

/**
 * Class XoopsFormCaptcha
 */
class XoopsFormCaptcha extends XoopsFormElement
{
    public $_captchaHandler;

    /**
     * @param string  $caption        Caption of the form element, default value is defined in captcha/language/
     * @param string  $name           Name for the input box
     * @param boolean $skipmember     Skip CAPTCHA check for members
     * @param int     $numchar        Number of characters in image mode, and input box size for text mode
     * @param int     $minfontsize    Minimum font-size of characters in image mode
     * @param int     $maxfontsize    Maximum font-size of characters in image mode
     * @param int     $backgroundtype Background type in image mode: 0 - bar; 1 - circle; 2 - line; 3 - rectangle; 4 - ellipse; 5 - polygon; 100 - generated from files
     * @param int     $backgroundnum  Number of background images in image mode
     *
     */
    public function __construct(
        $caption = '',
        $name = 'xoopscaptcha',
        $skipmember = null,
        $numchar = null,
        $minfontsize = null,
        $maxfontsize = null,
        $backgroundtype = null,
        $backgroundnum = null
    ) {
        if (!class_exists('XoopsCaptcaha')) {
            require_once SMARTOBJECT_ROOT_PATH . '/include/captcha/captcha.php';
        }

        $this->_captchaHandler = XoopsCaptcha::getInstance();
        $this->_captchaHandler->init($name, $skipmember, $numchar, $minfontsize, $maxfontsize, $backgroundtype, $backgroundnum);
        if (!$this->_captchaHandler->active) {
            $this->setHidden();
        } else {
            $caption = !empty($caption) ? $caption : $this->_captchaHandler->getCaption();
            $this->setCaption($caption);
        }
    }

    /**
     * @param $name
     * @param $val
     * @return bool
     */
    public function setConfig($name, $val)
    {
        return $this->_captchaHandler->setConfig($name, $val);
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        if (!$this->isHidden()) {
            return $this->_captchaHandler->render();
        }
    }
}
