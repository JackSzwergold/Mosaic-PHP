<?php

/**
 * ImageMosaic Class (imagemosaic.class.php)
 *
 * Programming: Jack Szwergold <JackSzwergold@gmail.com>
 *
 * Created: 2014-01-11, js
 * Version: 2014-01-11, js: creation
 *          2014-01-11, js: development & cleanup
 *          2014-01-12, js: more development & adding new sample images
 *          2014-01-14, js: moving onto creating actual pixelated images.
 *          2014-01-16, js: More improvements including actual image generation.
 *          2014-01-16, js: getting pure JSON saved instead of plain DIVs.
 *          2014-01-18, js: adjustments to allow for additional image orientations.
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
  $ImageMosaicClass->flip_horizontal(FALSE);
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

         . '<div class="Core">'
         . '<div class="Padding">'

         . '<div class="Grid">'
         . '<div class="Padding">'

         . '<div class="PixelBoxWrapper">'

         . '<ul>'
         . $final_images
         . '</ul>'

         . '</div><!-- .PixelBoxWrapper -->'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Grid -->'

         . '</div><!-- .Padding -->'
         . '</div><!-- .Core -->'

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
// Set the favicons.

$favicons = array();

$favicons[] = '<!-- Opera Speed Dial Favicon -->'
            . '<link rel="icon" type="image/png" href="favicons/speeddial-160px.png" />'
            ;

$favicons[] = '<!-- Standard Favicon -->'
            . '<link rel="icon" type="image/x-icon" href="favicons/favicon.ico" />'
            ;

$favicons[] = '<!-- For iPhone 4 Retina display: -->'
            . '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="favicons/apple-touch-icon-114x114-precomposed.png" />'
            ;

$favicons[] = '<!-- For iPad: -->'
            . '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="favicons/apple-touch-icon-72x72-precomposed.png" />'
            ;

$favicons[] = '<!-- For iPhone: -->'
            . '<link rel="apple-touch-icon-precomposed" href="favicons/apple-touch-icon-57x57-precomposed.png" />'
            ;

//**************************************************************************************//
// Doctype.

if (FALSE) {
  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  $html = '<html xmlns="http://www.w3.org/1999/xhtml">';
  $meta_copyright = '<meta name="copyright" content="(c) copyright ' . date('Y') . ' jack szwergold. all rights reserved." />';
}
else {
  $doctype = '<!DOCTYPE html>';
  $html = '<html lang="en">';
  $meta_copyright = '<meta name="dcterms.rightsHolder" content="(c) copyright ' . date('Y') . ' jack szwergold. all rights reserved.">';
}

//**************************************************************************************//
// Return the output.

echo $doctype
   . $html
   . '<head>'
   . '<title>image mosaic</title>'
   . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'
   . '<meta name="description" content="a dynamically generated image mosaic using php, the gd graphics libarary, html &amp; css" />'
   . $meta_copyright
   . '<meta property="og:title" content="image mosaic" />'
   . '<meta property="og:description" content="a dynamically generated image mosaic using php, the gd graphics libarary, html &amp; css" />'
   . '<meta property="og:type" content="website" />'
   . '<meta property="og:locale" content="en_US" />'
   . '<meta property="og:url" content="http://www.preworn.com/mosaic/" />'
   . '<meta property="og:site_name" content="preworn" />'
   . '<meta property="og:image" content="http://www.preworn.com/mosaic/favicons/speeddial-160px.png" />'
   . '<meta name="robots" content="noindex,nofollow" />'
   . '<meta name="viewport" content="width=device-width, initial-scale=0.65, maximum-scale=2, minimum-scale=0.65, user-scalable=yes" />'
   . '<link rel="stylesheet" href="css/style.css" type="text/css" />'

   . join('', $favicons)

   . '<script src="script/json2.js" type="text/javascript"></script>'
   . '<script type="text/javascript" src="script/jquery/jquery-1.10.2.min.js"></script>'
   . '<script type="text/javascript" src="script/jquery/jquery.noconflict.js"></script>'
   . '<script type="text/javascript" src="script/common.js"></script>'

   . '</head>'
   . '<body>'
   . $body
   . '</body>'
   . '</html>'
   ;

?>
