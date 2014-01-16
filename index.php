<?php

/**
 * ImageMosaic Class (imagemosaic.class.php)
 *
 * Programming: Jack Szwergold <JackSzwergold@gmail.com>
 *
 * Created: 2014-01-11 js
 * Version: 2014-01-11, js: creation
 *          2014-01-11, js: development & cleanup
 *          2014-01-12, js: more development & adding new sample images
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.
// require_once('classes/processimage.class.php');
require_once('classes/imagemosaic.class.php');

//**************************************************************************************//
// Set an array of mode options.

$mode_options = array();

$mode_options['micro']['width'] = 6;
$mode_options['micro']['height'] = 6;
$mode_options['micro']['block_size'] = 10;
$mode_options['micro']['how_many'] = 20;

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
$mode_options['large']['how_many'] = 2;

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
  $mode = 'mega';
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
  $artworks[$image_file] = $ImageMosaicClass->debug_mode(FALSE);
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
$final_images = implode('', $image_files);

//**************************************************************************************//
// Set the content in the wrapper area.

$wrapper = '<div class="Wrapper">'
         . '<div class="Padding">'

         . '<div class="Content">'
         . '<div class="Padding">'

         . '<div class="Section">'
         . '<div class="Padding">'
         . '<div class="Middle">'

         . '<div class="Core">'
         . '<div class="Padding">'

         . '<div class="Grid">'
         . '<div class="Padding">'

         . '<ul>'
         . $final_images
         . '</ul>'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Grid -->'

         . '</div><!-- .Middle -->'
         . '</div><!-- .Padding -->'
         . '</div><!-- .Section -->'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Core -->'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Content -->'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Wrapper -->'
         ;

//**************************************************************************************//
// Set the view wrapper.

$body = sprintf('<div class="%sView">', $mode)
      . $wrapper
      . sprintf('</div><!-- .%sView -->', $mode)
      ;

//**************************************************************************************//
// Return the output.

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
   . '<html xmlns="http://www.w3.org/1999/xhtml">'
   . '<head>'

   . '<title></title>'
   . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'
   . '<meta name="description" content="" />'
   . '<meta name="copyright" content="" />'
   . '<meta name="robots" content="index,follow" />'
   . '<link rel="stylesheet" href="css/style.css" type="text/css" />'

   . '</head>'
   . '<body>'
   . $body
   . '</body>'
   . '</html>'
   ;

?>
