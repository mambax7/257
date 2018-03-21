<?php
/**
 *
 */
$admin_mydirname = basename(dirname(dirname(__DIR__)));

// Admin Module Name  Headlines
define('_AM_HEADLINES_DBUPDATED', 'Database Updated Successfully!');
define('_AM_HEADLINES_HEADLINES', 'Headlines Configuration');
//define('_AM_HEADLINES_HLMAIN','Headline Main');
define('_AM_HEADLINES_SITENAME', 'Site Name');
define('_AM_HEADLINES_URL', 'URL');
define('_AM_HEADLINES_ORDER', 'Order');
define('_AM_HEADLINES_ENCODING', 'RSS Encoding');
define('_AM_HEADLINES_CACHETIME', 'Cache Time');
define('_AM_HEADLINES_CACHEFL', 'Flush Cache');
define('_AM_HEADLINES_CACHEUPD', 'Cache Updated');
define('_AM_HEADLINES_UPDATE', 'Update');
define('_AM_HEADLINES_MAINSETT', 'Main Page Settings');
define('_AM_HEADLINES_BLOCKSETT', 'Block Settings');
define('_AM_HEADLINES_DISPLAY', 'Display in main page');
define('_AM_HEADLINES_DISPIMG', 'Display image');
define('_AM_HEADLINES_DISPFULL', 'Display in full view');
define('_AM_HEADLINES_DISPMAX', 'Max items to display');
define('_AM_HEADLINES_ASBLOCK', 'Display in block');
define('_AM_HEADLINES_ADDHEADL', 'Add Headline');
define('_AM_HEADLINES_URLEDFXML', 'URL of RDF/RSS file');
define('_AM_HEADLINES_EDITHEADL', 'Edit Headline');
define('_AM_HEADLINES_WANTDEL', 'Are you sure you want to delete headline for %s?');
define('_AM_HEADLINES_WANTFLUSH', 'Are you sure you want to flush the %s headline cache?');
define('_AM_HEADLINES_INVALIDID', 'Invalid ID');
define('_AM_HEADLINES_OBJECTNG', 'Object does not exist');
define('_AM_HEADLINES_FAILUPDATE', 'Failed saving data to database for headline %s');
define('_AM_HEADLINES_FAILUPDELETE', 'Failed deleting data from database for headline %s');
define('_AM_HEADLINES_FAILFLUSH', 'Failed attempting to flush the cache for %s');
define('_AM_HEADLINES_SHOW', 'Show Feed');
define('_AM_HEADLINES_HIDE', 'Hide Feed');
define('_AM_HEADLINES_ACTIONS', 'Actions');

// Text for Admin footer
define('_AM_HEADLINES_ADMIN_FOOTER', "<div class='center smallsmall italic pad5'><strong>{$admin_mydirname}</strong> is maintained by the <a class='tooltip' rel='external' href='https://xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>");

//1.12
define('_AM_HEADLINES_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_HEADLINES_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_HEADLINES_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_HEADLINES_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_HEADLINES_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_HEADLINES_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_HEADLINES_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
