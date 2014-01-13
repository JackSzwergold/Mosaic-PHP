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
// Set an array of images.

$image_files = array();
$image_files[] = 'images/chuck_berry_chess_box_set.jpg';
$image_files[] = 'images/roxy_music_country_life.jpg';
$image_files[] = 'images/black_sabbath_volume_4.jpg';
$image_files[] = 'images/gogos_beauty_and_the_beat.jpg';
$image_files[] = 'images/led_zeppelin_houses_of_the_holy.jpg';
$image_files[] = 'images/la_luz_damp_face.jpg';
$image_files[] = 'images/rush_moving_pictures.jpg';
$image_files[] = 'images/rolling_stones_let_it_bleed.jpg';
$image_files[] = 'images/the_b52s.jpg';

$images_processed = array();

$ImageMosaicClass = new ImageMosaic();

//**************************************************************************************//
// Set an array of mode options.

$mode_options = array();

$mode_options['small']['width'] = 23;
$mode_options['small']['height'] = 23;
$mode_options['small']['block_size'] = 10;

$mode_options['large']['width'] = 46;
$mode_options['large']['height'] = 46;
$mode_options['large']['block_size'] = 10;

//**************************************************************************************//
// Set the mode.

$mode = 'small';

//**************************************************************************************//
// Roll through the images.

foreach ($image_files as $image_file) {
  $ImageMosaicClass->preprocess_image($image_file, $mode_options[$mode]['width'], $mode_options[$mode]['height'], $mode_options[$mode]['block_size']);
  $images_processed[$image_file] = $ImageMosaicClass->resample_image();
}

//**************************************************************************************//
// Shuffle the images.

$artworks = array();
foreach ($images_processed as $image_file => $image_processed) {
  if (FALSE) {
    $ImageMosaicClass->render_image($image_processed);
  }
  else {
    $pixel_blocks = $ImageMosaicClass->generate_blocks($image_file, $image_processed, FALSE);
    $artworks[] = $ImageMosaicClass->render_blocks($pixel_blocks);
  }
}
shuffle($artworks);
$image_files = array();
foreach($artworks as $artwork) {
  $image_files[] = '<li>'
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
