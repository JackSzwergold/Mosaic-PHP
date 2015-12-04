<?php

/**
 * Frontend Display Helpers (frontendDisplayHelpers.php) (c) by Jack Szwergold
 *
 * Frontend Display Helpers is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2015-11-10, js
 * Version: 2015-11-10, js: creation
 *          2015-11-10, js: development
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.

require_once BASE_FILEPATH . '/lib/Mosaic.class.php';

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
  $VIEW_MODE = $mode_keys[0];
}
else {
  $VIEW_MODE = 'large';
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

$skip_files = array('..', '.', '.DS_Store','ignore');
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
// Slice off a subset of the image files.

$image_files = array_slice($raw_image_files, 0, $mode_options[$VIEW_MODE]['how_many']);

//**************************************************************************************//
// Init the image mosaic class and roll through the images.

$ImageMosaicClass = new ImageMosaic();

// Init the items array.
$items = array();

// Loop through the image files array.
foreach ($image_files as $image_file) {

  // Set the options for the image processing.
  $ImageMosaicClass->set_image($image_file, $mode_options[$VIEW_MODE]['width'], $mode_options[$VIEW_MODE]['height'], $mode_options[$VIEW_MODE]['block_size']);
  $ImageMosaicClass->debug_mode(FALSE);
  $ImageMosaicClass->row_flip_horizontal(FALSE);
  $ImageMosaicClass->set_row_delimiter(NULL);
  $ImageMosaicClass->set_generate_images(TRUE);
  $ImageMosaicClass->set_overlay_image(TRUE);

  // Set the options for the image processing.
  $processed_image = $ImageMosaicClass->process_image();
  $items[$image_file]['blocks'] = $processed_image['blocks'];
  $items[$image_file]['json'] = $processed_image['json'];

} // foreach

//**************************************************************************************//
// Use 'array_filter' to filter out the empty images.

$items = array_filter($items);

//**************************************************************************************//
// Place the images in <li> tags.

// Init the image item and related json array.
$image_item = $image_json = array();

// Loop through the artworks array.
foreach ($items as $file => $image) {

  // Set the image item array value.
  $image_item[$file] = sprintf('<li><div class="Padding">%s</div><!-- .Padding --></li>', $image['blocks']);

  // Set the image json array value.
  $image_json[$file] = $image['json'];

} // foreach

// Set the body content.
$body_content = sprintf('<ul>%s</ul>', implode('', $image_item));

// Set the JSON content.
$json_content = implode(',' . "\r\n", $image_json);

?>