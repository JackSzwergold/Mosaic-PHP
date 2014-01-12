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
  $ImageMosaicClass->set_image($image, 46, 46, 10);
  $images_processed[] = $ImageMosaicClass->resample_image();
}

//**************************************************************************************//
// Output the data.

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
echo implode('', $artworks);

?>
