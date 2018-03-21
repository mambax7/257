<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

/**
 * !
 * Example
 *
 * require_once __DIR__ . '/uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new SmartUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
 * if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
 * if (!$uploader->upload()) {
 * echo $uploader->getErrors();
 * } else {
 * echo '<h4>File uploaded successfully!</h4>'
 * echo 'Saved as: ' . $uploader->getSavedFileName() . '<br>';
 * echo 'Full path: ' . $uploader->getSavedDestination();
 * }
 * } else {
 * echo $uploader->getErrors();
 * }
 */

/**
 * Upload Media files
 *
 * Example of usage:
 * <code>
 * require_once __DIR__ . '/uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new SmartUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
 * if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
 *            if (!$uploader->upload()) {
 *               echo $uploader->getErrors();
 *            } else {
 *               echo '<h4>File uploaded successfully!</h4>'
 *               echo 'Saved as: ' . $uploader->getSavedFileName() . '<br>';
 *               echo 'Full path: ' . $uploader->getSavedDestination();
 *            }
 * } else {
 *            echo $uploader->getErrors();
 * }
 * </code>
 *
 * @license GNU
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @link    http://smartfactory.ca The SmartFactory
 * @package SmartObject
 */
mt_srand((double)microtime() * 1000000);

require_once XOOPS_ROOT_PATH . '/class/uploader.php';

/**
 * Class SmartUploader
 */
class SmartUploader extends XoopsMediaUploader
{
    public $ext;
    public $dimension;

    /**
     * No admin check for uploads
     */
    public $noAdminSizeCheck;

    /**
     * Constructor
     *
     * @param string    $uploadDir
     * @param array|int $allowedMimeTypes
     * @param int       $maxFileSize
     * @param int       $maxWidth
     * @param int       $maxHeight
     * @internal param int $cmodvalue
     */
    public function __construct($uploadDir, $allowedMimeTypes = 0, $maxFileSize, $maxWidth = 0, $maxHeight = 0)
    {
        parent::__construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth, $maxHeight);
    }

    /**
     * @param $value
     */
    public function noAdminSizeCheck($value)
    {
        $this->noAdminSizeCheck = $value;
    }

    /**
     * Is the file the right size?
     *
     * @return bool
     */
    public function checkMaxFileSize()
    {
        if ($this->noAdminSizeCheck) {
            return true;
        }
        if ($this->mediaSize > $this->maxFileSize) {
            return false;
        }

        return true;
    }
}
