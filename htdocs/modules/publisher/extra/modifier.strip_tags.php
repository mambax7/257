<?php
/**
 * Smarty plugin
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty strip_tags modifier plugin
 *
 * Type:     modifier<br>
 * Name:     strip_tags<br>
 * Purpose:  strip html tags from text
 * @link     http://smarty.php.net/manual/en/language.modifier.strip.tags.php
 *          strip_tags (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author   Jordon Mears <jordoncm at gmail dot com>
 * @author   Tekin Birdüzen <t.birduezen at web-coding dot eu>
 *
 * @version  3.0
 *
 * @param string
 * @param boolean optional
 * @param string  optional
 * @return string
 */
function smarty_modifier_strip_tags($string)
{
    // HTML5 selfclosing tags
    $selfclosingTags = ['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

    /**
     * Find out how many arguments we have and
     * initialize the needed variables
     */
    switch (func_num_args()) {
        case 1:
            $replace_with_space = true;
            break;
        case 2:
            $arg = func_get_arg(1);
            if (1 === $arg || true === $arg || '1' === $arg || 'true' === $arg) {
                // for full legacy support || $arg === 'false' should be included
                $replace_with_space = ' ';
                $allowed_tags       = '';
            } elseif (0 === $arg || false === $arg || '0' === $arg || 'false' === $arg) {
                // for full legacy support || $arg === 'false' should be removed
                $replace_with_space = '';
                $allowed_tags       = '';
            } else {
                $replace_with_space = ' ';
                $allowed_tags       = $arg;
            }
            break;
        case 3:
            $replace_with_space = func_get_arg(1) ? ' ' : '';
            $allowed_tags       = func_get_arg(2);
            break;
    }
    if (strlen($allowed_tags)) {
        // Allowed tags are set
        $allowed_tags = str_replace([' />', '/>', '>'], '', $allowed_tags);

        // This is to delete the allowed selfclosing tags from the list
        $tagArray        = explode('<', substr($allowed_tags, 1));
        $selfClosing     = array_intersect($tagArray, $selfclosingTags);
        $selfclosingTags = array_diff($selfclosingTags, $tagArray);
        $allowed_tags    = implode('|', array_diff($tagArray, $selfClosing));

        unset($tagArray, $selfClosing);
    }

    // Let's get rid of the selfclosing tags first
    if (count($selfclosingTags)) {
        $string = preg_replace('/<(' . implode('|', $selfclosingTags) . ')\s?[^>]*?\/?>/is', $replace_with_space, $string);
    }

    // And now the other tags
    if (strlen($allowed_tags)) {
        while (preg_match("/<(?!({$allowed_tags}))([a-z][a-z1-5]+)\s?[^>]*?>(.*?)<\/\\2>/is", $string)) {
            $string = preg_replace("/<(?!({$allowed_tags}))([a-z][a-z1-5]+)\s?[^>]*?>(.*?)<\/\\2>/is", '$3' . $replace_with_space, $string);
        }
    } else {
        // Absolutely no tags allowed
        while (preg_match("/<([a-z][a-z1-5]+)\s?[^>]*?>(.*?)<\/\\1>/is", $string)) {
            $string = preg_replace("/<([a-z][a-z1-5]+)\s?[^>]*?>(.*?)<\/\\1>/is", '$2' . $replace_with_space, $string);
        }
    }

    return $string;
}

/* vim: set expandtab: */

/* usage:

{$string|strip_tags} strips all tags and replaces them with a space
{$string|strip_tags:false} strips all tags without replacing them with a space
{$string|strip_tags:'<b><br>'} strips all tags except b and br tags and replaces them with a space
{$string|strip_tags:false:'<b><br>'} strips all tags except b and br tags without replacing them with a space
*/
