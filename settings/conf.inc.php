<?php

/******************************************************************************/
//   ____             __ _
//  / ___|___  _ __  / _(_) __ _
// | |   / _ \| '_ \| |_| |/ _` |
// | |__| (_) | | | |  _| | (_| |
//  \____\___/|_| |_|_| |_|\__, |
//                         |___/
//
// The core, application specific config stuff.
/******************************************************************************/

/******************************************************************************/
// Load the local config and bootstrap items.
require_once('bootstrap.php');
require_once('local.php');

/******************************************************************************/
// TODO: Generate a nonce if we are using metatag based Content-Security-Policy.
// $NONCE = base64_encode(random_bytes(20));
$NONCE = bin2hex(openssl_random_pseudo_bytes(32));

/******************************************************************************/
// Set the HTML templating options.
// $TEMPLATE_FRAMEWORK = 'bootstrap-4.6';
$TEMPLATE_FRAMEWORK = 'bootstrap-5.3';

/**************************************************************************************************/
// Define the defaults.
$VALID_CONTENT_TYPES = array('application/vnd.api+json', 'application/json','text/plain','text/html');
$VALID_CHARSETS = array('utf-8','iso-8859-1','cp-1252');

/**************************************************************************************************/
// Site descriptive info.
$SITE_TITLE = 'Mosaic';
$SITE_DESCRIPTION = 'A dynamically generated image mosaic using PHP, the GD graphics library, HTML &amp; CSS.';
$SITE_URL = 'http://www.szwergold.com/projects/mosaic/';
$SITE_COPYRIGHT = '(c) Copyright ' . date('Y') . ' Jack Szwergold. Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.';
$SITE_LICENSE_CODE = 'CC-BY-NC-SA-4.0';
$SITE_LICENSE = 'This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License (CC-BY-NC-SA-4.0)';
$SITE_ROBOTS = 'noindex, nofollow';
$SITE_VIEWPORT = 'width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes';
$SITE_IMAGE = 'favicons/icon_200x200.png';
$SITE_FB_ADMINS = '504768652';
$SITE_KEYWORD = 'lexicon';
$SITE_DEFAULT_CONTROLLER = 'small';

/**************************************************************************************************/
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

/**************************************************************************************************/
// Social media info.
$SOCIAL_MEDIA_INFO = array();
$SOCIAL_MEDIA_INFO['instagram']['short_name'] = 'Instagram';
$SOCIAL_MEDIA_INFO['instagram']['emoji'] = '📸';
$SOCIAL_MEDIA_INFO['instagram']['url'] = 'https://www.instagram.com/jackszwergold/';
$SOCIAL_MEDIA_INFO['instagram']['description'] = 'Check me out on Instagram.';

/**************************************************************************************************/
// Amazon recommendation banner.
$AMAZON_RECOMMENDATION = '';

/**************************************************************************************************/
// Set the page DIVs array.
$PAGE_DIVS_ARRAY = array();
$PAGE_DIVS_ARRAY[] = 'Wrapper';
$PAGE_DIVS_ARRAY[] = 'Core';
$PAGE_DIVS_ARRAY[] = 'Grid';

/**************************************************************************************************/
// Set the page DIV wrapper.
$PAGE_DIV_WRAPPER = 'PixelBoxWrapper';

/**************************************************************************************************/
// Set the JavaScript array.
$JAVASCRIPTS_ITEMS = array();

/**************************************************************************************************/
// Set the link items array.
$LINK_ITEMS = array();
$LINK_ITEMS['style_css']['rel'] = 'stylesheet';
$LINK_ITEMS['style_css']['type'] = 'text/css';
$LINK_ITEMS['style_css']['href'] = 'css/style.css';
$LINK_ITEMS['author']['rel'] = 'author';
$LINK_ITEMS['author']['href'] = 'https://plus.google.com/+JackSzwergold';

/**************************************************************************************************/
// Set the controller and parameter stuff.
$VALID_CONTROLLERS = array('parent', 'child', 'grandchild', 'greatgrandchild');
$DISPLAY_CONTROLLERS = array('parent');
$VALID_GET_PARAMETERS = array('_debug', 'json', 'offset', 'count', 'parent', 'child', 'grandchild', 'greatgrandchild');

?>
