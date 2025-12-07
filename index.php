<?php

/**
 * Index Controller (index.php) (c) by Jack Szwergold
 *
 * Index Controller is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
 *
 * Created: 2014-01-20, js
 * Version: 2014-01-20, js: creation
 *          2014-01-20, js: development & cleanup
 *          2014-02-16, js: adding configuration settings
 *          2014-02-16, js: adding controller logic
 *          2014-02-17, js: setting a 'base'
 *          2014-03-02, js: adding a better page URL
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.
require_once('settings/conf.inc.php');
require_once(BASE_FILEPATH . '/common/functions.inc.php');
require_once(BASE_FILEPATH . '/lib/frontendDisplay.class.php');
require_once(BASE_FILEPATH . '/lib/frontendDisplayHelper.class.php');
require_once(BASE_FILEPATH . '/lib/requestFiltering.class.php');

//**************************************************************************************//
// Manage the request filering stuff.
$requestFilteringClass = new requestFiltering();
$params = $requestFilteringClass->process_parameters();

$JSON_MODE = $requestFilteringClass->process_json_mode($params);
$DEBUG_MODE = $requestFilteringClass->process_debug_mode($params);
$page_query_string_append = $requestFilteringClass->process_query_string_append(array('json' => $JSON_MODE, '_debug' => $DEBUG_MODE));

$url_parts = $requestFilteringClass->process_url_parts($params);
$controller = $requestFilteringClass->process_controllers($url_parts);
$page_base = $requestFilteringClass->process_page_base($controller);

//**************************************************************************************//
// Now deal with the front end display helper class related stuff.
$frontendDisplayHelperClass = new frontendDisplayHelper();
$frontendDisplayHelperClass->setController($controller);
$frontendDisplayHelperClass->setPageBaseSuffix($page_query_string_append);
$frontendDisplayHelperClass->setCount(array_key_exists('count', $params) ? $params['count'] : 1);
$frontendDisplayHelperClass->initContent($DEBUG_MODE);

$VIEW_MODE = $frontendDisplayHelperClass->getViewMode();
$html_content = $frontendDisplayHelperClass->getHTMLContent();

//**************************************************************************************//
// Init the front end display class and set other things.
$frontendDisplayClass = new frontendDisplay();
$frontendDisplayClass->setViewMode($VIEW_MODE, TRUE);
$frontendDisplayClass->setPageContent($html_content);
$frontendDisplayClass->setJavaScriptItems($JAVASCRIPTS_ITEMS);
$frontendDisplayClass->setLinkItems($LINK_ITEMS);

//**************************************************************************************//
// Init the core content and set the header and footer items.
$frontendDisplayClass->initCoreContent();

//**************************************************************************************//
// Init and display the final content.
$frontendDisplayClass->initHTMLContent();


// /******************************************************************************/
// // Handle the substitution map stuff.
// $substitution_map = array();
// $substitution_map['[[BASE_URL]]'] = BASE_URL;
// $substitution_map['[[BASE_URI]]'] = BASE_URI;
// $substitution_map['[[NONCE]]'] = $NONCE;

// /******************************************************************************/
// // Load the full page HTML template.
// $full_page_html = file_get_contents(BASE_FILEPATH . '/html_includes/' . $TEMPLATE_FRAMEWORK . '/page_body_template.html');

// /******************************************************************************/
// // Build the final content based on the content and the substitution map.
// $full_page_html = strtr($full_page_html, $substitution_map);

// /******************************************************************************/
// // Render the final HTML content.
// echo $full_page_html;
// exit();

//**************************************************************************************//
// Init and display the final content.
$content_type = 'text/html';
$charset = 'utf-8';
header(sprintf('Content-Type: %s; charset=%s', $content_type, $charset));
echo $frontendDisplayClass->html_content;
exit();

?>
