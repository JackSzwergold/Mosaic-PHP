<?php

/**
 * Preworn Index Controller (index.php)
 *
 * Programming: Jack Szwergold <JackSzwergold@gmail.com>
 *
 * Created: 2014-01-20, js
 * Version: 2014-01-20, js: creation
 *          2014-01-20, js: development & cleanup
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.

require_once('common/functions.inc.php');
require_once 'lib/frontendDisplay.class.php';
require_once 'lib/Parsedown.php';
// require_once('lib/processimage.class.php');
require_once('lib/imagemosaic.class.php');

//**************************************************************************************//
// Define the valid arrays.

$VALID_CONTROLLERS = array('portfolio');
$DISPLAY_CONTROLLERS = array('portfolio');
$VALID_GET_PARAMETERS = array('_debug', 'portfolio');
$VALID_CONTENT_TYPES = array('application/json','text/plain','text/html');
$VALID_CHARSETS = array('utf-8','iso-8859-1','cp-1252');

//**************************************************************************************//
// Set config options.

$DEBUG_OUTPUT_JSON = false;

//**************************************************************************************//
// Set an array of mode options.

$mode_options = array();

$mode_options['micro']['width'] = 6;
$mode_options['micro']['height'] = 6;
$mode_options['micro']['block_size'] = 10;
$mode_options['micro']['how_many'] = 25;

$mode_options['tiny']['width'] = 12;
$mode_options['tiny']['height'] = 12;
$mode_options['tiny']['block_size'] = 10;
$mode_options['tiny']['how_many'] = 16;

$mode_options['small']['width'] = 23;
$mode_options['small']['height'] = 23;
$mode_options['small']['block_size'] = 10;
$mode_options['small']['how_many'] = 9;

$mode_options['large']['width'] = 46;
$mode_options['large']['height'] = 46;
$mode_options['large']['block_size'] = 10;
$mode_options['large']['how_many'] = 1;

$mode_options['mega']['width'] = 72;
$mode_options['mega']['height'] = 72;
$mode_options['mega']['block_size'] = 10;
$mode_options['mega']['how_many'] = 1;

//**************************************************************************************//
// Set the mode.

if (FALSE) {
  $mode_keys = array_keys($mode_options);
  shuffle($mode_keys);
  $mode = $mode_keys[0];
}
else {
  $mode = 'large';
}

//**************************************************************************************//
// Set the image directory.

$image_dir = 'images/';

//**************************************************************************************//
// Check if there is an image directory. If not? Exit.

if (!is_dir($image_dir)) {
  die();
}

//**************************************************************************************//
// Process the images in the directory.

$skip_files = array('..', '.', '.DS_Store');
$image_files = scandir($image_dir);
$image_files = array_diff($image_files, $skip_files);

if (empty($image_files)) {
  die('Sorry. No images found.');
}

$raw_image_files = array();
foreach ($image_files as $image_file_key => $image_file_value) {
  $raw_image_files[$image_file_key] = $image_dir . $image_file_value;
}

//**************************************************************************************//
// Shuffle the image files.

shuffle($raw_image_files);

//**************************************************************************************//
// Slice off a sybset of the image files.

$image_files = array_slice($raw_image_files, 0, $mode_options[$mode]['how_many']);

//**************************************************************************************//
// Init the image mosaic class and roll through the images.

$ImageMosaicClass = new ImageMosaic();

foreach ($image_files as $image_file) {
  $ImageMosaicClass->set_image($image_file, $mode_options[$mode]['width'], $mode_options[$mode]['height'], $mode_options[$mode]['block_size']);
  $ImageMosaicClass->debug_mode(FALSE);
  $ImageMosaicClass->row_flip_horizontal(FALSE);
  $ImageMosaicClass->set_row_delimiter(NULL);
  $ImageMosaicClass->set_generate_images(TRUE);
  $ImageMosaicClass->set_overlay_image(TRUE);
  $artworks[$image_file] = $ImageMosaicClass->process_image();
}

//**************************************************************************************//
// Filter out the empty images.

$artworks = array_filter($artworks);

//**************************************************************************************//
// Place the images in <li> tags.

$image_files = array();
foreach($artworks as $image_file => $artwork) {
  $image_files[$image_file] = '<li>'
                            . '<div class="Padding">'
                            . $artwork
                            . '</div><!-- .Padding -->'
                            . '</li>'
                            ;
}
$final_images = sprintf('<ul>%s</ul>', implode('', $image_files));


//**************************************************************************************//
// Init the "frontendDisplay()" class.

$frontendDisplayClass = new frontendDisplay('text/html', 'utf-8', FALSE, FALSE);
$frontendDisplayClass->setViewMode($mode);
$frontendDisplayClass->setPageTitle('Image Mosaic');
$frontendDisplayClass->setPageURL('http://www.preworn.com/mosaic/');
$frontendDisplayClass->setPageCopyright('(c) Copyright ' . date('Y') . ' Jack Szwergold. All rights reserved.');
$frontendDisplayClass->setPageDescription('A dynamically generated image mosaic using PHP, the GD graphics library, HTML &amp; CSS.');
// $frontendDisplayClass->setPageContentMarkdown('index.md');
$frontendDisplayClass->setPageContent($final_images);
$frontendDisplayClass->setPageViewport('width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes');
$frontendDisplayClass->setPageRobots('noindex, nofollow');
$frontendDisplayClass->setJavascripts(array('script/common.js'));
$frontendDisplayClass->initContent();

?>
