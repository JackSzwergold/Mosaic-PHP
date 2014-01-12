<?php

/**
 * ImageMosaic Class (imagemosaic.class.php)
 *
 * Programming: Jack Szwergold <JackSzwergold@gmail.com>
 *
 * Created: 2014-01-11 js
 * Version: 2014-01-11, js: creation
 *          2014-01-11, js: development & cleanup
 *
 */

//**************************************************************************************//
// Require the basic configuration settings & functions.
// require_once('classes/processimage.class.php');
require_once('classes/imagemosaic.class.php');

//**************************************************************************************//
// Init the "geocodingClass()" class.

$images = array();
$images[] = 'images/chuck_berry_chess_box_set.jpg';
$images[] = 'images/roxy_music_country_life.jpg';
$images[] = 'images/black_sabbath_volume_4.jpg';
$images[] = 'images/gogos_beauty_and_the_beat.jpg';
$images[] = 'images/led_zeppelin_houses_of_the_holy.jpg';
$images[] = 'images/la_luz_damp_face.jpg';

$images_processed = array();

$ImageMosaicClass = new ImageMosaic();

foreach ($images as $image) {
  // $ImageMosaicClass->set_image($image, 46, 46, 10);
  $ImageMosaicClass->set_image($image, 23, 23, 10);
  $images_processed[] = $ImageMosaicClass->resample_image();
}

//**************************************************************************************//
// Shuffle the covers.

$artworks = array();
foreach ($images_processed as $image_processed) {
  if (FALSE) {
    $ImageMosaicClass->render_image($image_processed);
  }
  else {
    $pixel_blocks = $ImageMosaicClass->generate_blocks($image_processed);
    $artworks[] = $ImageMosaicClass->render_blocks($pixel_blocks);

  }
}
shuffle($artworks);
$covers = array();
foreach($artworks as $artwork) {
  $covers[] = '<li>'
            . '<div class="Padding">'
            . $artwork
            . '</div><!-- .Padding -->'
            . '</li>'
            ;
}
$final_covers = implode('', $covers);

//**************************************************************************************//
// Output the data.

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
   . '<html xmlns="http://www.w3.org/1999/xhtml">'
   . '<html lang="en">'
   . '<head>'

   . '<meta charset="utf-8" />'
   . '<title></title>'
   . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'
   . '<meta name="description" content="" />'
   . '<meta name="copyright" content="" />'
   . '<meta name="robots" content="index,follow" />'
   . '<link rel="stylesheet" href="css/style.css" type="text/css" />'

   . '</head>'

   . '<body>'

   . '<div class="Wrapper">'
   . '<div class="Padding">'
   . '<div class="rootPage">'

   . '<div class="Content">'
   . '<div class="Padding">'

   . '<div class="Core">'
   . '<div class="Padding">'

   . '<div class="Grid">'
   . '<div class="Padding">'

   . '<ul>'
   . $final_covers
   . '</ul>'

   . '</div><!-- .Padding -->'
   . '</div><!-- .Grid -->'

   . '</div><!-- .Padding -->'
   . '</div><!-- .Core -->'

   . '</div><!-- .Padding -->'
   . '</div><!-- .Content -->'

   . '</div><!-- .rootPage -->'
   . '</div><!-- .Padding -->'
   . '</div><!-- .Wrapper -->'

   . '</body>'
   . '</html>'
   ;

?>
