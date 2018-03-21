<?php

/**
 * Containing the class to manage meta informations of the SmartObject framework
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectCore
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class MetaGen is a class providing some methods to dynamically and automatically customize Meta Tags information
 * @author The SmartFactory <www.smartfactory.ca>
 */
class SmartMetaGen
{
    public $_myts;

    public $_title;
    public $_original_title;
    public $_keywords;
    public $_meta_description;
    public $_categoryPath;
    public $_description;
    public $_minChar = 4;

    /**
     * SmartMetaGen constructor.
     * @param      $title
     * @param bool $keywords
     * @param bool $description
     * @param bool $categoryPath
     */
    public function __construct($title, $keywords = false, $description = false, $categoryPath = false)
    {
        $this->_myts = \MyTextSanitizer::getInstance();
        $this->setCategoryPath($categoryPath);
        $this->setTitle($title);
        $this->setDescription($description);

        if (!$keywords) {
            $keywords = $this->createMetaKeywords();
        }

        /*      $myts = \MyTextSanitizer::getInstance();
         if (method_exists($myts, 'formatForML')) {
         $keywords = $myts->formatForML($keywords);
         $description = $myts->formatForML($description);
         }
         */
        $this->setKeywords($keywords);
    }

    /**
     * Return true if the string is length > 0
     *
     * @credit psylove
     *
     * @var    string $string Chaine de caract�re
     * @return boolean
     */
    public function emptyString($var)
    {
        return (strlen($var) > 0);
    }

    /**
     * Create a title for the short_url field of an article
     *
     * @credit psylove
     *
     * @var    string      $title title of the article
     * @param  bool|string $withExt
     * @return string      sort_url for the article
     */
    public function generateSeoTitle($title = '', $withExt = true)
    {
        // Transformation de la chaine en minuscule
        // Codage de la chaine afin d'éviter les erreurs 500 en cas de caractères imprévus
        $title = rawurlencode(strtolower($title));

        // Transformation des ponctuations
        $pattern = [
            '/%09/', // Tab
            '/%20/', // Space
            '/%21/', // !
            '/%22/', // "
            '/%23/', // #
            '/%25/', // %
            '/%26/', // &
            '/%27/', // '
            '/%28/', // (
            '/%29/', // )
            '/%2C/', // ,
            '/%2F/', // /
            '/%3A/', // :
            '/%3B/', // ;
            '/%3C/', // <
            '/%3D/', // =
            '/%3E/', // >
            '/%3F/', // ?
            '/%40/', // @
            '/%5B/', // [
            '/%5C/', // \
            '/%5D/', // ]
            '/%5E/', // ^
            '/%7B/', // {
            '/%7C/', // |
            '/%7D/', // }
            '/%7E/', // ~
            "/\./" // .
        ];
        $rep_pat = [
            '-',
            '-',
            '-',
            '-',
            '-',
            '-100',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-at-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-'
        ];
        $title   = preg_replace($pattern, $rep_pat, $title);

        // Transformation des caractères accentués
        $pattern = [
            '/%B0/', // °
            '/%E8/', // è
            '/%E9/', // é
            '/%EA/', // ê
            '/%EB/', // ë
            '/%E7/', // ç
            '/%E0/', // à
            '/%E2/', // â
            '/%E4/', // ä
            '/%EE/', // î
            '/%EF/', // ï
            '/%F9/', // ù
            '/%FC/', // ü
            '/%FB/', // û
            '/%F4/', // ô
            '/%F6/', // ö
        ];
        $rep_pat = ['-', 'e', 'e', 'e', 'e', 'c', 'a', 'a', 'a', 'i', 'i', 'u', 'u', 'u', 'o', 'o'];
        $title   = preg_replace($pattern, $rep_pat, $title);

        $tableau = explode('-', $title); // Transforme la chaine de caract�res en tableau
        $tableau = array_filter($tableau, [$this, 'emptyString']); // Supprime les chaines vides du tableau
        $title   = implode('-', $tableau); // Transforme un tableau en chaine de caract�res s�par� par un tiret

        if (count($title) > 0) {
            if ($withExt) {
                $title .= '.html';
            }

            return $title;
        } else {
            return '';
        }
    }

    /**
     * @param $document
     * @return mixed
     */
    public function html2text($document)
    {
        return smart_html2text($document);
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        global $xoopsModule, $xoopsModuleConfig;
        $this->_title          = $this->html2text($title);
        $this->_title          = $this->purifyText($this->_title);
        $this->_original_title = $this->_title;

        $moduleName = $xoopsModule->getVar('name');

        $titleTag = [];

        $show_mod_name_breadcrumb = isset($xoopsModuleConfig['show_mod_name_breadcrumb']) ? $xoopsModuleConfig['show_mod_name_breadcrumb'] : true;

        if ($moduleName && $show_mod_name_breadcrumb) {
            $titleTag['module'] = $moduleName;
        }

        if (isset($this->_title) && ('' !== $this->_title) && (strtoupper($this->_title) != strtoupper($moduleName))) {
            $titleTag['title'] = $this->_title;
        }

        if (isset($this->_categoryPath) && ('' !== $this->_categoryPath)) {
            $titleTag['category'] = $this->_categoryPath;
        }

        $ret = isset($titleTag['title']) ? $titleTag['title'] : '';

        if (isset($titleTag['category']) && '' !== $titleTag['category']) {
            if ('' !== $ret) {
                $ret .= ' - ';
            }
            $ret .= $titleTag['category'];
        }
        if (isset($titleTag['module']) && '' !== $titleTag['module']) {
            if ('' !== $ret) {
                $ret .= ' - ';
            }
            $ret .= $titleTag['module'];
        }
        $this->_title = $ret;
    }

    /**
     * @param $keywords
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
    }

    /**
     * @param $categoryPath
     */
    public function setCategoryPath($categoryPath)
    {
        $categoryPath        = $this->html2text($categoryPath);
        $this->_categoryPath = $categoryPath;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        if (!$description) {
            global $xoopsModuleConfig;
            if (isset($xoopsModuleConfig['module_meta_description'])) {
                $description = $xoopsModuleConfig['module_meta_description'];
            }
        }

        $description = $this->html2text($description);
        $description = $this->purifyText($description);

        $description = preg_replace("/([^\r\n])\r\n([^\r\n])/", "\\1 \\2", $description);
        $description = preg_replace("/[\r\n]*\r\n[\r\n]*/", "\r\n\r\n", $description);
        $description = preg_replace('/[ ]* [ ]*/', ' ', $description);
        $description = stripslashes($description);

        $this->_description      = $description;
        $this->_meta_description = $this->createMetaDescription();
    }

    public function createTitleTag()
    {
    }

    /**
     * @param               $text
     * @param  bool         $keyword
     * @return mixed|string
     */
    public function purifyText($text, $keyword = false)
    {
        return smart_purifyText($text, $keyword);
    }

    /**
     * @param  int $maxWords
     * @return string
     */
    public function createMetaDescription($maxWords = 100)
    {
        $words = [];
        $words = explode(' ', $this->_description);

        // Only keep $maxWords words
        $newWords = [];
        $i        = 0;

        while ($i < $maxWords - 1 && $i < count($words)) {
            $newWords[] = $words[$i];
            ++$i;
        }
        $ret = implode(' ', $newWords);

        return $ret;
    }

    /**
     * @param $text
     * @param $minChar
     * @return array
     */
    public function findMetaKeywords($text, $minChar)
    {
        $keywords = [];

        $text = $this->purifyText($text);
        $text = $this->html2text($text);

        $text = preg_replace("/([^\r\n])\r\n([^\r\n])/", "\\1 \\2", $text);
        $text = preg_replace("/[\r\n]*\r\n[\r\n]*/", "\r\n\r\n", $text);
        $text = preg_replace('/[ ]* [ ]*/', ' ', $text);
        $text = stripslashes($text);
        $text =

        $originalKeywords = preg_split('/[^a-zA-Z\'"-]+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($originalKeywords as $originalKeyword) {
            $secondRoundKeywords = explode("'", $originalKeyword);
            foreach ($secondRoundKeywords as $secondRoundKeyword) {
                if (strlen($secondRoundKeyword) >= $minChar) {
                    if (!in_array($secondRoundKeyword, $keywords)) {
                        $keywords[] = trim($secondRoundKeyword);
                    }
                }
            }
        }

        return $keywords;
    }

    /**
     * @return string
     */
    public function createMetaKeywords()
    {
        global $xoopsModuleConfig;
        $keywords = $this->findMetaKeywords($this->_original_title . ' ' . $this->_description, $this->_minChar);
        if (isset($xoopsModuleConfig) && isset($xoopsModuleConfig['moduleMetaKeywords'])
            && '' !== $xoopsModuleConfig['moduleMetaKeywords']) {
            $moduleKeywords = explode(',', $xoopsModuleConfig['moduleMetaKeywords']);
            $keywords       = array_merge($keywords, $moduleKeywords);
        }

        /* Commenting this out as it may cause problem on XOOPS ML websites
         $return_keywords = array();

         // Cleaning for duplicate keywords
         foreach ($keywords as $keyword) {
         if (!in_array($keyword, $keywords)) {
         $return_keywords[] = trim($keyword);
         }
         }*/

        // Only take the first 90 keywords
        $newKeywords = [];
        $i           = 0;
        while ($i < 90 - 1 && isset($keywords[$i])) {
            $newKeywords[] = $keywords[$i];
            ++$i;
        }
        $ret = implode(', ', $newKeywords);

        return $ret;
    }

    public function autoBuildMeta_keywords()
    {
    }

    public function buildAutoMetaTags()
    {
        global $xoopsModule, $xoopsModuleConfig;

        $this->_keywords         = $this->createMetaKeywords();
        $this->_meta_description = $this->createMetaDescription();
        $this->_title            = $this->createTitleTag();
    }

    public function createMetaTags()
    {
        global $xoopsTpl, $xoTheme;

        if (is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', $this->_keywords);
            $xoTheme->addMeta('meta', 'description', $this->_description);
            $xoTheme->addMeta('meta', 'title', $this->_title);
        } else {
            $xoopsTpl->assign('xoops_meta_keywords', $this->_keywords);
            $xoopsTpl->assign('xoops_meta_description', $this->_description);
        }
        $xoopsTpl->assign('xoops_pagetitle', $this->_title);
    }
}
