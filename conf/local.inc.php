<?php

/**
 * Local Config File (local.inc.php) (c) by Jack Szwergold
 *
 * Local Config File is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-02-16, js
 * Version: 2014-02-16, js: creation
 *          2014-02-16, js: development & cleanup
 *
 */

/**************************************************************************************************/
// Define localized defaults.

// Set the base URL path.
if ($_SERVER['SERVER_NAME'] == 'localhost') {
  define('BASE_PATH', '/Mosaic-PHP/');
}
else {
  define('BASE_PATH', '/projects_base/mosaic/');
}

// Site descriptive info.
$SITE_TITLE = 'Mosaic';
$SITE_DESCRIPTION = 'A dynamically generated image mosaic using PHP, the GD graphics library, HTML &amp; CSS.';
$SITE_URL = 'http://www.preworn.com/projects_base/mosaic/';
$SITE_COPYRIGHT = '(c) Copyright ' . date('Y') . ' Jack Szwergold. Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.';
$SITE_LICENSE_CODE = 'CC-BY-NC-SA-4.0';
$SITE_LICENSE = 'This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License (CC-BY-NC-SA-4.0)';
$SITE_ROBOTS = 'noindex, nofollow';
$SITE_VIEWPORT = 'width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes';
$SITE_IMAGE = 'favicons/icon_200x200.png';
$SITE_FB_ADMINS = '504768652';
$SITE_KEYWORD = 'lexicon';
$SITE_DEFAULT_CONTROLLER = 'small';

// Favicon info.
$FAVICONS = array();
$FAVICONS['standard']['rel'] = 'icon';
$FAVICONS['standard']['type'] = 'image/png';
$FAVICONS['standard']['href'] = 'favicons/favicon.ico';
$FAVICONS['opera']['rel'] = 'icon';
$FAVICONS['opera']['type'] = 'image/png';
$FAVICONS['opera']['href'] = 'favicons/speeddial-160px.png';
$FAVICONS['iphone']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['iphone']['href'] = 'favicons/apple-touch-icon-57x57-precomposed.png';
$FAVICONS['iphone4_retina']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['iphone4_retina']['sizes'] = '114x114';
$FAVICONS['iphone4_retina']['href'] = 'favicons/apple-touch-icon-114x114-precomposed.png';
$FAVICONS['ipad']['rel'] = 'apple-touch-icon-precomposed';
$FAVICONS['ipad']['sizes'] = '72x72';
$FAVICONS['ipad']['href'] = 'favicons/apple-touch-icon-72x72-precomposed.png';

// Social media info.
$SOCIAL_MEDIA_INFO = array();
$SOCIAL_MEDIA_INFO['instagram']['short_name'] = 'Instagram';
$SOCIAL_MEDIA_INFO['instagram']['emoji'] = '📸';
$SOCIAL_MEDIA_INFO['instagram']['url'] = 'https://www.instagram.com/jackszwergold/';
$SOCIAL_MEDIA_INFO['instagram']['description'] = 'Check me out on Instagram.';

// Amazon recommendation banner.
$AMAZON_RECOMMENDATION = '';

// Set the page DIVs array.
$PAGE_DIVS_ARRAY = array();
$PAGE_DIVS_ARRAY[] = 'Wrapper';
$PAGE_DIVS_ARRAY[] = 'Core';
$PAGE_DIVS_ARRAY[] = 'Grid';

// Set the page DIV wrapper.
$PAGE_DIV_WRAPPER = 'PixelBoxWrapper';

// Set the JavaScript array.
$JAVASCRIPTS_ITEMS = array();

// Set the link items array.
$LINK_ITEMS = array();
$LINK_ITEMS['style_css']['rel'] = 'stylesheet';
$LINK_ITEMS['style_css']['type'] = 'text/css';
$LINK_ITEMS['style_css']['href'] = 'css/style.css';
$LINK_ITEMS['author']['rel'] = 'author';
$LINK_ITEMS['author']['href'] = 'https://plus.google.com/+JackSzwergold';

// Set the controller and parameter stuff.
$VALID_CONTROLLERS = array('parent', 'child', 'grandchild', 'greatgrandchild');
$DISPLAY_CONTROLLERS = array('parent');
$VALID_GET_PARAMETERS = array('_debug', 'json', 'offset', 'count', 'parent', 'child', 'grandchild', 'greatgrandchild');

?>
