<div id="help-template" class="outer">
    <h1 class="head">Help:
        <a class="ui-corner-all tooltip" href="<{$xoops_url}>/modules/<{$smarty.const._MI_WFL_DIRNAME}>/admin/index.php"
           title="Back to the administration of <{$smarty.const._MI_WFL_NAME}>"> <{$smarty.const._MI_WFL_NAME}>
            <img src="<{xoAdminIcons home.png}>"
                 alt="Back to the Administration of <{$smarty.const._MI_WFL_NAME}>">
        </a></h1>

    <h4 class="odd">Preferences</h4><br>
    First we pay a visit to the Preferences of WF-Links to configure the module.<br><br><br><b>Link popular
    count</b><br>Set here the amount of hits needed for a link for setting it as
    being a popular link.<br>Default: 100<br><br><b>Links popular and new</b> <br>Here you can set how a link will be
    displayed as popular and new.<br>There are the following
    options:<br><br>
    <ul>
        <li>Display as icons</li>
        <li>Display as text</li>
        <li>Do not display</li>
    </ul>
    <br>Default: Display as icons<br><br><b>Links days new</b><br>The number of days a link status will be considered as
    new.<br>Default: 10<br><br><b>Links days updated</b><br>The
    amount of days a link status will be considered as updated.<br>Default: 10<br><br><b>Link listing count</b><br>Number
    of links to display in each category listing.<br>Default:
    10<br><br><b>Admin index links count</b><br>Number of new links to display in module admin area.<br>Default:
    10<br><br><b>Default link order</b><br>Select the default
    order for the link listings.<br><br>
    <ul>
        <li>Title</li>
        <li>Submission date</li>
        <li>Rating</li>
        <li>Popularity</li>
        <li>Country</li>
    </ul>
    <br>Each option can be set to ascending (A) or descending (D).<br>Default: Title (A)<br><br><b>Sort categories
    by</b><br>Select how categories and sub-categories are
    sorted.<br><br>
    <ul>
        <li>Title</li>
        <li>Weight</li>
    </ul>
    <br>Default: Title<br><br><b>Sub-Categories</b><br>Select Yes to display sub-categories. Selecting No will hide
    sub-categories from the listings.<br>Default: No<br><br><b>Editor
    to use (admin)</b><br>Select the editor to use for admin side.<br>If you have a 'simple' install (e.g you use only
    XOOPS core editor class, provided in the standard xoops core package),
    then you can just select DHTML and Compact.<br>When selecting DHTML than this might be overruled by the setting
    Default Editor in ImpressCMS Preferences.<br>Default: DHTML<br><br><b>Editor
    to use (user)</b><br>Select the editor to use for user side.<br>If you have a 'simple' install (e.g you use only
    XOOPS core editor class, provided in the standard xoops core package), then
    you can just select DHTML and Compact.<br>When selecting DHTML than this might be overruled by the setting Default
    Editor in ImpressCMS Preferences.<br>Default: DHTML<br><br><b>Display
    screenshot images</b><br>Select Yes to display screenshot images for each link item.<br>Default: Yes<br><br><b>Use
    thumb nails</b><br>Supported link types: JPG, GIF, PNG.<br>WF-Links
    will use thumb nails for images. Set to No to use orginal image if the server does not support this option.<br>Default:
    No<br><br><b>Update thumbnails</b><br>If selected Thumbnail
    images will be updated at each page render, otherwise the first thumbnail image will be used regardless.<br>Default:
    Yes<br><br><b>Thumb nail quality</b><br>Quality:<br><br>
    <ul>
        <li>Lowest: 0</li>
        <li>Highest: 100</li>
    </ul>
    <br>Default: 100<br><br><b>Keep image aspect ratio</b><br>Default: No<br><br><b>Image display width</b><br>Display
    width (px) for screenshot image.<br>Default: 100<br><br><b>Image
    display height</b><br>Display height (px) for screenshot image.<br>Default: 79<br><br><b>Upload size (KB)</b><br>Maximum
    link size permitted with link uploads.<br>Default:
    200000<br><br><b>Upload image width</b><br>Maximum image width (px) permitted when uploading image links<br>Default:
    600<br><br><b>Upload image height</b><br>Maximum
    image height (px) permitted when uploading image links.<br>Default: 600<br><br><b>Use auto screenshot</b><br>This
    will automatically create a screenshot based on the url. This
    overrules uploaded screenshots and might not work for all websites.<br>Default: No<br><br><b>Main image
    directory</b><br>Enter the url without a trailing slash.<br>Default:
    modules/wflinks/images<br><br><b>Screenshots upload directory</b><br>Enter the url without a trailing slash.<br>Default:
    uploads/images/screenshots<br><br><b>Category image
    upload directory</b><br>Enter the url without a trailing slash.<br>Default: uploads/images/category<br><br><b>Country
    flag image directory</b><br>Enter the url without a
    trailing slash.<br>Default: uploads/images/flags/flags_small<br><br><b>Timestamp</b><br>Default timestamp for
    WF-links.<br>Here you can configure how the date is formatted. This
    setting is not for the WF-Links blocks.<br>See also the <a href="http://php.net/manual/en/function.date.php"
                                                               target="_blank">PHP Manual.</a><br>Default: <em>D,
    d-M-Y</em><br><br><b>Timestamp
    administration</b><br>Default admininstration timestamp for WF-Links.<br>Here you can configure how the date is
    formatted for the WF-Links administration.<br>See also the
    <a href="http://php.net/manual/en/function.date.php" target="_blank">PHP Manual.</a><br>Default: <em>D, d-M-Y -
    G:i</em><br><br><b>Set total amount of characters for
    description</b><br>Set total amount of characters for description in category view.<br>Default: 200<br><br><b>Enter
    max. characters for meta keywords</b><br>This is maximum
    amount of characters that can be used for meta keywords.<br>See <a
        href="http://en.wikipedia.org/wiki/Meta_element#The_keywords_attribute" target="_blank">Wikipedia </a>for more
    information.<br>Default:
    255<br><br><b>Show other links submitted by Submitter</b><br>Select Yes if other links of the submitter will be
    displayed.<br>Default: Yes<br><br><b>Show Quick View
    option</b><br>Select Yes to turn the Quick View option on.<br>Default: No<br><br><b>Show Social Bookmarks</b><br>Select
    Yes if you want Social Bookmark icons to be displayed
    under article.<br>Default: Yes<br><br><b>Show Google PageRank&trade;</b><br>Select Yes if you want Google PageRank&trade;
    to be displayed.<br>Default: Yes<br><br><b>User can
    submit Tags</b><br>Select Yes if user is allowed to submit tags.<br>Note: The Tag module needs to be installed
    otherwise the form doesn't show in the submit form.<br>Default:
    No<br><br><b>Use address and map options</b><br>Select Yes to use the address and maps feature in submit forms.<br>Default:
    Yes<br><br><b>Print page footer</b><br>Footer
    that will be printed for each link.<br>Default: <website_url><br><br><b>Logo print url</b><br>Url of the logo
    that will be printed at the top of the page.<br>Default: <website_url>/modules/wflinks/assets/images/logo-en.gif<br><br><b>Show
    disclaimer before user submission</b><br>Before a user can submit a link show the entry regulations.<br>Default:
    No<br><br><b>Enter submission disclaimer text</b><br>Default:
    We have the right, but not the obligation to monitor and review submissions submitted by users, in the forums. We
    shall not be responsible for any of the content of these messages. We further
    reserve the right, to delete, move or edit submissions that the we, in its exclusive discretion, deems abusive,
    defamatory, obscene or in violation of any Copyright or Trademark laws or otherwise
    objectionable.<br><br><b>Show disclaimer before user link</b><br>Show link regulations before open a link.<br>Default:
    No<br><br><b>Enter link disclaimer text</b><br>Default:
    The links on this site are provided as is without warranty either expressed or implied. linkloaded files should be
    checked for possible virus infection using the most up-to-date detection and
    security packages. If you have a question concerning a particular piece of software, feel free to contact the
    developer. We refuse liability for any damage or loss resulting from the use or misuse
    of any software offered from this site for linkloading. If you have any doubt at all about the safety and operation
    of software made available to you on this site, do not linkload it. Contact us
    if you have questions concerning this disclaimer.<br><br><b>Copyright notice</b><br>Select to display a copyright
    notice on link page.<br>Default: Yes<br><br><b>Select
    forum</b><br>Select the forum you have installed and will be used by WF-Links.<br><br>
    <ul>
        <li>Newbb</li>
        <li>IPB Forum</li>
        <li>PHPBB Module</li>
        <li>Newbbex</li>
    </ul>
    <br>Default: Newbb<br><br><b>Comment rules</b><br><br>
    <ul>
        <li>Disable comments</li>
        <li>Comments are always approved</li>
        <li>Comments by registered users are always approved</li>
        <li>All comments need to be approved by administrators</li>
    </ul>
    <br>Default: Comments are always approved<br><br><b>Allow anonymous post in comments</b><br>Default: No<br><br><b>Enable
    notification</b><br>This module allows users to
    be notified when certain events occur. Select if users should be presented with notification options in a Block
    (Block-style), within the module (Inline-style), or both. For block-style
    notification, the Notification Options block must be enabled for this module.<br><br>
    <ul>
        <li>Disable notification</li>
        <li>Enable only block-style</li>
        <li>Enable only inline-syle</li>
        <li>Enable notification (both styles)</li>
    </ul>
    <br>Default: Enable notification (both styles)<br><br><b>Enable specific events</b><br>Select which notification
    events to which your users may subscribe.<br><br>
    <ul>
        <li>Global : New category</li>
        <li>Global : Modify link requested</li>
        <li>Global : Broken link submitted</li>
        <li>Global : Link submitted</li>
        <li>Global : New link</li>
        <li>Category : Link Submitted</li>
        <li>Category : New Link</li>
        <li>Category : Bookmark</li>
        <li>Link : Comment added</li>
        <li>Link : Comment submitted</li>
        <li>Link : Bookmark</li>
    </ul>
    <br>Default: All selected<br><br> <br>Click the Go! button to save the preferences in the database.
</div>
