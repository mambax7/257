<?php
/**
 * Smarty plugin
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty SEO URL Friendly modifier plugin
 *
 * Type:     modifier<br>
 * Name:     seo<br>
 * Purpose:  SEO URL Friendly
 * Usage:    {-$title|seo:" ":"-":"first"-}
 *
 * @link   http://smarty.php.net/manual/en/language.modifier.seo.php replace (Smarty online manual)
 * @author Willy Mularto <me at sangprabv dot web dot id>
 * @param string $string    input string
 * @param string $search    text to search for
 * @param string $delimiter replacement text
 * @param string $case      uppercase/lowercase/ucfirst
 * @return string
 */
function smarty_modifier_seo($string, $search, $delimiter, $case)
{
    /*Replace any non latin chars*/
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    /*Remove any non alpha numeric*/
    $string = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $string);
    /*Remove any double delimiter*/
    $string = preg_replace('/[[:blank:]]+/', "$delimiter", $string);
    /*Remove any delimiter at the beginning of string if any*/
    $string = ltrim($string, "$delimiter");
    /*Remove any delimiter at the end of string if any*/
    $string = rtrim($string, "$delimiter");

    switch ($case) {
        case 'upper':
            return strtoupper($string);
        case 'lower':
            return strtolower($string);
        case 'first':
            return ucfirst($string);
        default:
            return $string;
    }
}
