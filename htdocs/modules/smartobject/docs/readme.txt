README FIRST

SmartObject Framework to be used with new SmartModules such as SmartPartner 2.0 RC,
SmartContent, SmartDynamic, SmartShop, etc...

This framework allow easy management of any kind of objects. It easily manages all the common operations
like adding, editing, deleting, listing, sorting, filtering objects.

How to install SmartObject
==========================

Please see the install.txt file

Implementing SmartObject Adsense feature on your site
=====================================================

In order for the SmartObject Adsense management feature to work, 3 modifications need to be done in the code.

1- include/common.php
=====================

Edit the file include/common.php and locate these lines, around line 159

    // #################### Include text sanitizer ##################
    include_once XOOPS_ROOT_PATH."/class/module.textsanitizer.php";

After these lines insert the following code :

    /**
     * Hack by marcan <INBOX>
     * Adding SmartObject Adsense Feature
     */
    include_once(XOOPS_ROOT_PATH . "/modules/smartobject/include/adsense.php");
    /**
     * End of Hack by marcan <INBOX>
     * Adding SmartObject Adsense Feature
     */

2- header.php
=============

Edit the file header.php in the root of your XOOPS site and locate these lines, around line 75

    /**
     * @var xos_opal_Theme
     */
    $xoTheme =& $xoopsThemeFactory->createInstance( array(
        'contentTemplate' => @$xoopsOption['template_main'],
    ) );
    $xoopsTpl =& $xoTheme->template;

After these lines insert the following code :

    /**
     * Hack by marcan <INBOX>
     * Adding SmartObject Adsense Feature
     */
    smart_adsense_initiate_smartytags();
    /**
     * End of Hack by marcan <INBOX>
     * Adding SmartObject Adsense Feature
     */

3- class/module.textsanitizer.php
=================================

Edit the file class/module.textsanitizer.php in the root of your XOOPS site and locate these lines, around line 292

        $text = $this->makeClickable($text);
        if ($smiley != 0) {
            // process smiley
            $text = $this->smiley($text);
        }
        if ($xcode != 0) {

After these lines insert the following code :

        /**
         * Hack by marcan <INBOX>
         * Adding SmartObject Adsense Feature
         */
        if (function_exists('smart_sanitizeAdsenses')) {
            $text = smart_sanitizeAdsenses($text);
        }
        /**
         * End of Hack by marcan <INBOX>
         * Adding SmartObject Adsense Feature
         */


Implementing SmartObject CustomTags feature on your site
=====================================================

In order for the SmartObject CustomTags feature to work, 3 modifications need to be done in the code.

1- include/common.php
=====================

Edit the file include/common.php and locate these lines, around line 277

    } elseif (!empty($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $xoopsConfig['theme_set_allowed'])) {

        $xoopsConfig['theme_set'] = $_SESSION['xoopsUserTheme'];
    }

After these lines insert the following code :

    /**
     * Hack by marcan <INBOX>
     * Adding SmartObject Custom tag Feature
     */
    include_once(XOOPS_ROOT_PATH . "/modules/smartobject/include/customtag.php");
    /**
     * End of Hack by marcan <INBOX>
     * Adding SmartObject Custom tag Feature
     */

2- header.php
=============

Edit the file header.php in the root of your XOOPS site and locate these lines, around line 75

    /**
     * @var xos_opal_Theme
     */
    $xoTheme =& $xoopsThemeFactory->createInstance( array(
        'contentTemplate' => @$xoopsOption['template_main'],
    ) );
    $xoopsTpl =& $xoTheme->template;

After these lines insert the following code :

    /**
     * Hack by marcan <INBOX>
     * Adding SmartObject Custom tag Feature
     */
    smart_customtag_initiate();
    /**
     * End of Hack by marcan <INBOX>
     * Adding SmartObject Custom tag Feature
     */

3- class/module.textsanitizer.php
=================================

Edit the file class/module.textsanitizer.php in the root of your XOOPS site and locate these lines, around line 292

        $text = $this->makeClickable($text);
        if ($smiley != 0) {
            // process smiley
            $text = $this->smiley($text);
        }
        if ($xcode != 0) {


After these lines insert the following code :

        /**
         * Hack by marcan <INBOX>
         * Adding SmartObject Custom tag Feature
         */
        if (function_exists('smart_sanitizeCustomtags')) {
            $text = smart_sanitizeCustomtags($text);
        }
        /**
         * End of Hack by marcan <INBOX>
         * Adding SmartObject Custom tag Feature
         */


Support and Feedback
====================

We encourage you to visit XOOPS Community forums to get and offer support for this module. Feedback as also always appreciated. A dedicated forum for The SmartFactory modules is available here:
https://xoops.org/modules/newbb
