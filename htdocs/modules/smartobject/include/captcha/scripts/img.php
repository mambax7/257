<?php
/**
 * Image Creation script
 *
 * D.J.
 */

include dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/mainfile.php';
error_reporting(0);
$xoopsLogger->activated = false;

if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote(XOOPS_URL, '/') . '/', $_SERVER['HTTP_REFERER'])) {
    exit();
}

/**
 * Class XoopsCaptchaImageHandler
 */
class XoopsCaptchaImageHandler
{
    public $config = [];
    //var $mode = "gd"; // GD or bmp
    public $code;
    public $invalid = false;

    public $font;
    public $spacing;
    public $width;
    public $height;

    /**
     * XoopsCaptchaImageHandler constructor.
     */
    public function __construct()
    {
        if (empty($_SESSION['XoopsCaptcha_name'])) {
            $this->invalid = true;
        }

        if (!extension_loaded('gd')) {
            $this->mode = 'bmp';
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
                    $this->mode = 'bmp';
                    break;
                }
            }
        }
    }

    /**
     * Loading configs from CAPTCHA class
     * @param array $config
     */
    public function setConfig($config = [])
    {
        // Loading default preferences
        $this->config = $config;
    }

    public function loadImage()
    {
        $this->createCode();
        $this->setCode();
        $this->createImage();
    }

    /**
     * Create Code
     */
    public function createCode()
    {
        if ($this->invalid) {
            return;
        }

        if ('bmp' === $this->mode) {
            $this->config['num_chars'] = 4;
            $this->code                = mt_rand(pow(10, $this->config['num_chars'] - 1), (int)str_pad('9', $this->config['num_chars'], '9'));
        } else {
            $this->code = substr(md5(uniqid(mt_rand(), 1)), 0, $this->config['num_chars']);
            if (!$this->config['casesensitive']) {
                $this->code = strtoupper($this->code);
            }
        }
    }

    public function setCode()
    {
        if ($this->invalid) {
            return;
        }

        $_SESSION['XoopsCaptcha_sessioncode'] = (string)$this->code;
        $maxAttempts                          = (int)(@$_SESSION['XoopsCaptcha_maxattempts']);

        // Increase the attempt records on refresh
        if (!empty($maxAttempts)) {
            $_SESSION['XoopsCaptcha_attempt_' . $_SESSION['XoopsCaptcha_name']]++;
            if ($_SESSION['XoopsCaptcha_attempt_' . $_SESSION['XoopsCaptcha_name']] > $maxAttempts) {
                $this->invalid = true;
            }
        }
    }

    /**
     * @param  string $file
     * @return string|void
     */
    public function createImage($file = '')
    {
        if ($this->invalid) {
            header('Content-type: image/gif');
            readfile(XOOPS_ROOT_PATH . '/images/subject/icon2.gif');

            return;
        }

        if ('bmp' === $this->mode) {
            return $this->createImageBmp();
        } else {
            return $this->createImageGd();
        }
    }

    /**
     *  Create CAPTCHA iamge with GD
     *  Originated from DuGris' SecurityImage
     * @param string $file
     */
    //  --------------------------------------------------------------------------- //
    // Class: SecurityImage 1.5                                                    //
    // Author: DuGris aka L. Jen <http://www.dugris.info>                           //
    // Email: DuGris@wanadoo.fr                                                    //
    // Licence: GNU                                                                 //
    // Project: XOOPS Project                                                   //
    //  --------------------------------------------------------------------------- //
    public function createImageGd($file = '')
    {
        $this->loadFont();
        $this->setImageSize();

        $this->oImage = imagecreatetruecolor($this->width, $this->height);
        $background   = imagecolorallocate($this->oImage, 255, 255, 255);
        imagefilledrectangle($this->oImage, 0, 0, $this->width, $this->height, $background);

        switch ($this->config['background_type']) {
            default:
            case 0:
                $this->drawBars();
                break;

            case 1:
                $this->drawCircles();
                break;

            case 2:
                $this->drawLines();
                break;

            case 3:
                $this->drawRectangles();
                break;

            case 4:
                $this->drawEllipses();
                break;

            case 5:
                $this->drawPolygons();
                break;

            case 100:
                $this->createFromFile();
                break;
        }
        $this->drawBorder();
        $this->drawCode();

        if (empty($file)) {
            header('Content-type: image/jpeg');
            imagejpeg($this->oImage);
        } else {
            imagejpeg($this->oImage, XOOPS_ROOT_PATH . '/' . $this->config['imagepath'] . '/' . $file . '.jpg');
        }
        imagedestroy($this->oImage);
    }

    /**
     * @param         $name
     * @param  string $extension
     * @return array
     */
    public function _getList($name, $extension = '')
    {
        $items = [];
        /*
         if (@ require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.ini.php") {
         load_functions("cache");
         if ($items = mod_loadCacheFile("captcha_{$name}", "captcha")) {
         return $items;
         }
         }
         */
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        $file_path = $this->config['rootpath'] . "/{$name}";
        $files     = XoopsLists::getFileListAsArray($file_path);
        foreach ($files as $item) {
            if (empty($extension) || preg_match("/(\.{$extension})$/i", $item)) {
                $items[] = $item;
            }
        }
        if (function_exists('mod_createCacheFile')) {
            mod_createCacheFile($items, "captcha_{$name}", 'captcha');
        }

        return $items;
    }

    public function loadFont()
    {
        $fonts      = $this->_getList('fonts', 'ttf');
        $this->font = $this->config['rootpath'] . '/fonts/' . $fonts[array_rand($fonts)];
    }

    public function setImageSize()
    {
        $MaxCharWidth  = 0;
        $MaxCharHeight = 0;
        $oImage        = imagecreatetruecolor(100, 100);
        $text_color    = imagecolorallocate($oImage, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
        $FontSize      = $this->config['fontsize_max'];
        for ($Angle = -30; $Angle <= 30; ++$Angle) {
            for ($i = 65; $i <= 90; ++$i) {
                $CharDetails   = imageftbbox($FontSize, $Angle, $this->font, chr($i), []);
                $_MaxCharWidth = abs($CharDetails[0] + $CharDetails[2]);
                if ($_MaxCharWidth > $MaxCharWidth) {
                    $MaxCharWidth = $_MaxCharWidth;
                }
                $_MaxCharHeight = abs($CharDetails[1] + $CharDetails[5]);
                if ($_MaxCharHeight > $MaxCharHeight) {
                    $MaxCharHeight = $_MaxCharHeight;
                }
            }
        }
        imagedestroy($oImage);

        $this->height  = $MaxCharHeight + 2;
        $this->spacing = (int)(($this->config['num_chars'] * $MaxCharWidth) / $this->config['num_chars']);
        $this->width   = ($this->config['num_chars'] * $MaxCharWidth) + ($this->spacing / 2);
    }

    /**
     * Return random background
     *
     * @return null|string
     */
    public function loadBackground()
    {
        $RandBackground = null;
        if ($backgrounds = $this->_getList('backgrounds', '(gif|jpg|png)')) {
            $RandBackground = $this->config['rootpath'] . '/backgrounds/' . $backgrounds[array_rand($backgrounds)];
        }

        return $RandBackground;
    }

    /**
     * Draw Image background
     */
    public function createFromFile()
    {
        if ($RandImage = $this->loadBackground()) {
            $ImageType = @getimagesize($RandImage);
            switch (@$ImageType[2]) {
                case 1:
                    $BackgroundImage = imagecreatefromgif($RandImage);
                    break;

                case 2:
                    $BackgroundImage = imagecreatefromjpeg($RandImage);
                    break;

                case 3:
                    $BackgroundImage = imagecreatefrompng($RandImage);
                    break;
            }
        }
        if (!empty($BackgroundImage)) {
            imagecopyresized($this->oImage, $BackgroundImage, 0, 0, 0, 0, imagesx($this->oImage), imagesy($this->oImage), imagesx($BackgroundImage), imagesy($BackgroundImage));
            imagedestroy($BackgroundImage);
        } else {
            $this->drawBars();
        }
    }

    /**
     * Draw Code
     */
    public function drawCode()
    {
        for ($i = 0; $i < $this->config['num_chars']; ++$i) {
            // select random greyscale colour
            $text_color = imagecolorallocate($this->oImage, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

            // write text to image
            $Angle = mt_rand(10, 30);
            if ($i % 2) {
                $Angle = mt_rand(-10, -30);
            }

            // select random font size
            $FontSize = mt_rand($this->config['fontsize_min'], $this->config['fontsize_max']);

            $CharDetails = imageftbbox($FontSize, $Angle, $this->font, $this->code[$i], []);
            $CharHeight  = abs($CharDetails[1] + $CharDetails[5]);

            // calculate character starting coordinates
            $posX = ($this->spacing / 2) + ($i * $this->spacing);
            $posY = 2 + ($this->height / 2) + ($CharHeight / 4);

            imagefttext($this->oImage, $FontSize, $Angle, $posX, $posY, $text_color, $this->font, $this->code[$i], []);
        }
    }

    /**
     * Draw Border
     */
    public function drawBorder()
    {
        $rgb          = mt_rand(50, 150);
        $border_color = imagecolorallocate($this->oImage, $rgb, $rgb, $rgb);
        imagerectangle($this->oImage, 0, 0, $this->width - 1, $this->height - 1, $border_color);
    }

    /**
     * Draw Circles background
     */
    public function drawCircles()
    {
        for ($i = 1; $i <= $this->config['background_num']; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imagefilledellipse($this->oImage, mt_rand(0, $this->width - 10), mt_rand(0, $this->height - 3), mt_rand(10, 20), mt_rand(20, 30), $randomcolor);
        }
    }

    /**
     * Draw Lines background
     */
    public function drawLines()
    {
        for ($i = 0; $i < $this->config['background_num']; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw Rectangles background
     */
    public function drawRectangles()
    {
        for ($i = 1; $i <= $this->config['background_num']; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imagefilledrectangle($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw Bars background
     */
    public function drawBars()
    {
        for ($i = 0; $i <= $this->height;) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, 0, $i, $this->width, $i, $randomcolor);
            $i += 2.5;
        }
        for ($i = 0; $i <= $this->width;) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageline($this->oImage, $i, 0, $i, $this->height, $randomcolor);
            $i += 2.5;
        }
    }

    /**
     * Draw Ellipses background
     */
    public function drawEllipses()
    {
        for ($i = 1; $i <= $this->config['background_num']; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            imageellipse($this->oImage, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $randomcolor);
        }
    }

    /**
     * Draw polygons background
     */
    public function drawPolygons()
    {
        for ($i = 1; $i <= $this->config['background_num']; ++$i) {
            $randomcolor = imagecolorallocate($this->oImage, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
            $coords      = [];
            for ($j = 1; $j <= $this->config['polygon_point']; ++$j) {
                $coords[] = mt_rand(0, $this->width);
                $coords[] = mt_rand(0, $this->height);
            }
            imagefilledpolygon($this->oImage, $coords, $this->config['polygon_point'], $randomcolor);
        }
    }

    /**
     *  Create CAPTCHA iamge with BMP
     *  TODO
     * @param  string $file
     * @return string
     */
    public function createImageBmp($file = '')
    {
        $image = '';

        if (empty($file)) {
            header('Content-type: image/bmp');
            echo $image;
        } else {
            return $image;
        }
    }
}

$config       = @include __DIR__ . '/../config.php';
$imageHandler = new \XoopsCaptchaImageHandler();
$imageHandler->setConfig($config);
$imageHandler->loadImage();
