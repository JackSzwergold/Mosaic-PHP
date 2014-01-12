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

$image = 'images/micro_earth.jpg';
$image = 'images/roxy_music_country_life.jpg';

$ImageMosaicClass = new ImageMosaic($image, 46, 46, 10);
$image_processed = $ImageMosaicClass->resample_image();


//**************************************************************************************//
// Output the data.

if (FALSE) {
  $ImageMosaicClass->render_image($image_processed);
}
else {
  $pixel_blocks = $ImageMosaicClass->generate_blocks($image_processed);
  $ImageMosaicClass->render_blocks($pixel_blocks);
}

?>
