<?

/**
 * ImageMosaic Class (imagemosaic.class.php)
 *
 * Programming: Jack Szwergold <JackSzwergold@gmail.com>
 *
 * Created: 2014-01-11 js
 * Version: 2014-01-11, js: creation
 *          2014-01-11, js: development & cleanup
 *          2014-01-12, js: more development & adding new sample images
 *          2014-01-14, js: moving onto creating actual pixelated images.
 *
 */

//**************************************************************************************//
// Here is where the magic happens!

class ImageMosaic {

  private $DEBUG_MODE = FALSE;

  private $image_file = FALSE;

  private $height_resample = 46;
  private $width_resample = 46;

  private $block_size = 10;
  private $overlay_tile = 'css/brick.png';

  private $cache_path = array('json' => 'cache_data/', 'gif' => 'cache_media/', 'jpeg' => 'cache_media/', 'png' => 'cache_media/');
  private $image_types = array('gif', 'jpeg', 'png');
  private $image_quality = array('gif' => 100, 'jpeg' => 100, 'png' => 9);

  public function __construct() {
  } // __construct


  public function set_image($image_file, $width_resample, $height_resample, $block_size) {

    $this->image_file = $image_file;
    $this->width_resample = $width_resample;
    $this->height_resample = $height_resample;
    $this->block_size = $block_size;

  } // set_image


  // Process the filename.
  function create_filename ($filename = '', $extension = '') {

    // Process the filename.
    $filepath_parts = pathinfo($filename);

    $ret_array = array();
    $ret_array[] = $filepath_parts['filename'];
    $ret_array[] = $this->width_resample;
    $ret_array[] = $this->height_resample;
    $ret_array[] = $this->block_size;

    $ret = $this->cache_path[$extension] . implode('-', $ret_array) . '.' . $extension;

    return $ret;

  } // create_filename


  // Process the image.
  function process_image () {

    // Check if the image actually exists.
    if (empty(realpath($this->image_file))) {
      return;
    }

    // Process the JSON filename.
    $json_filename = $this->create_filename($this->image_file, 'json');

    // Check if the image actually exists.
    if (FALSE && !empty(realpath($json_filename))) {
      $pixel_blocks = json_decode(file_get_contents($json_filename));
      $ret = $this->render_pixel_box_container($pixel_blocks);
    }
    else {
      $image_processed = $this->resample_image();
      $pixel_blocks = $this->generate_pixel_boxes($this->image_file, $image_processed, FALSE);

      // If the cache directory doesn’t exist, create it.
      if (!is_dir($this->cache_path['json'])) {
        mkdir($this->cache_path['json'], 0755);
      }

      // Cache the pixel blocks to a JSON file.
      $file_handle = fopen($json_filename, 'w');
      fwrite($file_handle, json_encode((object) $pixel_blocks, JSON_PRETTY_PRINT));
      fclose($file_handle);
    }

    $ret = !empty($pixel_blocks) ? $this->render_pixel_box_container($pixel_blocks) : '';

    return $ret;

  } // process_image


  // Resample the image.
  function resample_image () {

    // Get the source image.
    $image_source = imagecreatefromjpeg($this->image_file);

    // Set the canvas for the processed image.
    $image_processed = imagecreatetruecolor($this->width_resample, $this->height_resample);

    // Get the image dimensions.
    $this->width_source = imagesx($image_source);
    $this->height_source = imagesy($image_source);

    // Process the image via 'imagecopyresampled'
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $this->width_resample, $this->height_resample, $this->width_source, $this->height_source);

    $this->pixelate_image($image_processed);

    // Get rid of the image to free up memory.
    imagedestroy($image_source);

    return $image_processed;

  } // resample_image


  // Pixelate the image.
  function pixelate_image ($image_source) {

    $pixelate_x = 10;
    $pixelate_y = 10;

    // Calculate the final width & final height
    $width_pixelate = $this->width_resample * $this->block_size;
    $height_pixelate = $this->height_resample * $this->block_size;

    // Set the canvas for the processed image.
    $image_processed = imagecreatetruecolor($width_pixelate, $height_pixelate);
    imagefill($image_processed, 0, 0, IMG_COLOR_TRANSPARENT);
    imagealphablending($image_processed, true);
    imagesavealpha($image_processed, true);

    // Process the image via 'imagecopyresampled'
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $width_pixelate, $height_pixelate, $this->width_resample, $this->height_resample);

    // Set the titled overlay element.
    $tiled_overlay = imagecreatetruecolor($pixelate_x, $pixelate_y);
    imagealphablending($tiled_overlay, true);
    imagesavealpha($tiled_overlay, true);

    imagefilledrectangle($tiled_overlay, 0, 0, $pixelate_x, $pixelate_y, IMG_COLOR_TILED);
    $tiled_overlay = imagecreatefrompng($this->overlay_tile);

    // imagealphablending($tiled_overlay_raw, true);
    // imagesavealpha($tiled_overlay_raw, true);
    // imagecopy($tiled_overlay, $tiled_overlay_raw, 0, 0, 0, 0, $pixelate_x, $pixelate_y);

    imagealphablending($image_processed, true);
    imagesavealpha($image_processed, true);


    // Generate the image pixels.
    for ($height_y = 0; $height_y <= $height_pixelate; $height_y += $pixelate_y + 1) {
      for ($width_x = 0; $width_x <= $width_pixelate; $width_x += $pixelate_x + 1) {

        $box_x = ($width_x + $pixelate_x);
        $box_y = ($height_y + $pixelate_y);

        $rgb = imagecolorsforindex($image_processed, imagecolorat($image_processed, $width_x, $height_y));
        $color = imagecolorclosest($image_processed, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($image_processed, $width_x, $height_y, $box_x, $box_y, $color);

        if (FALSE) {
          // imagecopy($image_processed, $tiled_overlay, $width_x, $height_y, $width_x + $pixelate_x, $height_y + $pixelate_y, 10, 10);
          imagecopymerge($image_processed, $tiled_overlay, $width_x, $height_y, $box_x, $box_y, $pixelate_x, $pixelate_y, 30);
          // imagecopymerge($image_processed, 0, 0, 0, 0, 10, 10, 75);
        }

      }  // width loop.
    }  // height loop.

    // Place an overlay on the image.
    if (FALSE) {
      imagealphablending($image_processed, true);
      imagesettile($image_processed, $tiled_overlay);
      imagefilledrectangle($image_processed, 0, 0, $width_pixelate, $height_pixelate, IMG_COLOR_TILED);
    }

    imagedestroy($tiled_overlay);

    // Save the images.
    $image_filenames = array();
    foreach ($this->image_types as $image_type) {

      // If the cache directory doesn’t exist, create it.
      if (!is_dir($this->cache_path[$image_type])) {
        mkdir($this->cache_path[$image_type], 0755);
      }

      // Process the filename.
      $filename = $this->create_filename($this->image_file, $image_type);

      // Generate the image files.
      if ($image_type == 'gif' && empty(realpath($filename))) {
        imagegif($image_processed, $filename, $this->image_quality['gif']);
      }
      else if ($image_type == 'jpeg' && empty(realpath($filename))) {
        imagejpeg($image_processed, $filename, $this->image_quality['jpeg']);
      }
      else if ($image_type == 'png' && empty(realpath($filename))) {
        imagepng($image_processed, $filename, $this->image_quality['png']);
        
      }
    }
    
    imagepng($image_processed, 'test.png', $this->image_quality['png']);

    // Get rid of the image to free up memory.
    imagedestroy($image_processed);

  } // pixelate_image


  // Generate the pixel boxes.
  function generate_pixel_boxes ($image_file, $image_processed, $flip_rows) {

    $pixel_blocks = array();

    for ($height = 0; $height < $this->height_resample; $height++) {

      $pixel_blocks_row = array();
      for ($width = 0; $width <= $this->width_resample; $width++) {

        $rgb = imagecolorat($image_processed, $width, $height);
        $red = ($rgb >> 16) & 0xFF;
        $green = ($rgb >> 8) & 0xFF;
        $blue = $rgb & 0xFF;
        $clear_class = '';

        $rgb_array = array();
        $rgb_array['red'] = intval($red * 1);
        $rgb_array['green'] = intval($green * 1);
        $rgb_array['blue'] = intval($blue * 1);

        $rgb_final = sprintf('rgb(%s)', implode(',', $rgb_array));
        $hex_final = sprintf("#%02X%02X%02X", $rgb_array['red'], $rgb_array['green'], $rgb_array['blue']);

        if ($width != $this->width_resample) {
          $block_dimensions = sprintf('height: %spx; width: %spx;', $this->block_size, $this->block_size);

          if (FALSE) {
            $block_rgb = sprintf('background-color: %s;', $rgb_final);
            $block_style = $block_dimensions . ' ' . $block_rgb;
          }
          else {
            $block_hex = sprintf('background-color: %s;', $hex_final);
            $block_style = $block_dimensions . ' ' . $block_hex;
          }

          $pixel_blocks_row[] = sprintf('<div class="PixelBox" style="%s">', $block_style)
                              . '</div><!-- .PixelBox -->' . "\r\n"
                              ;
        }
        if ($width == $this->width_resample) {
          // $final_row = array_reverse($pixel_blocks_row);
          $final_row = $flip_rows ? array_reverse($pixel_blocks_row) : $pixel_blocks_row;
          $pixel_blocks[] = $final_row;
        }

      } // $width loop.

    } // $height loop.

    // Get rid of the image to free up memory.
    imagedestroy($image_processed);

    return $pixel_blocks;

  } // generate_pixel_boxes


  // Render the pixel boxes into a container.
  function render_pixel_box_container ($pixel_blocks) {

    $rows = array();
    foreach ($pixel_blocks as $pixel_block_row_key => $pixel_block_row_value) {
      $rows[] = implode('', $pixel_block_row_value);
    }

    $block_container_dimensions = sprintf('width: %spx;', $this->width_resample * $this->block_size);

    $ret = sprintf('<div class="PixelBoxConatiner" style="%s">' . "\r\n", $block_container_dimensions)
         . implode('', $rows)
         .'</div><!-- .PixelBoxConatiner -->' . "\r\n"
         ;

    return $ret;

  } // render_pixel_box_container


  // Rendethe image straight to the browser.
  function render_image ($image_processed) {

    header('Content-Type: image/jpeg');

    imagejpeg($image_processed, null, 60);

  } // renderImage


} // ImageMosaic

?>