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
 *          2014-01-16, js: More improvements including actual image generation.
 *          2014-01-16, js: getting pure JSON saved instead of plain DIVs.
 *
 */

//**************************************************************************************//
// Here is where the magic happens!

class ImageMosaic {

  private $DEBUG_MODE = FALSE;

  private $image_file = FALSE;

  private $height_resampled = 46;
  private $width_resampled = 46;

  private $block_size_x = 10;
  private $block_size_y = 10;

  private $overlay_tile = 'css/brick.png';

  private $flip_horizontal = FALSE;

  private $cache_path = array('json' => 'cache_data/', 'gif' => 'cache_media/', 'jpeg' => 'cache_media/', 'png' => 'cache_media/');
  private $image_types = array('gif', 'jpeg', 'png');
  private $image_quality = array('gif' => 100, 'jpeg' => 100, 'png' => 9);

  public function __construct() {
  } // __construct


  public function debug_mode($DEBUG_MODE) {

    $this->DEBUG_MODE = $DEBUG_MODE;

  } // debug_mode


  public function flip_horizontal($flip_horizontal) {

    $this->flip_horizontal = $flip_horizontal;

  } // flip_horizontal


  public function set_image($image_file, $width_resampled, $height_resampled, $block_size) {

    $this->image_file = $image_file;
    $this->width_resampled = $width_resampled;
    $this->height_resampled = $height_resampled;
    $this->block_size_x = $block_size;
    $this->block_size_y = $block_size;

  } // set_image


  // Create the filename.
  function create_filename ($filename = '', $extension = '') {

    // Process the filename.
    $filepath_parts = pathinfo($filename);

    $ret_array = array();
    $ret_array[] = $filepath_parts['filename'];
    $ret_array[] = $this->width_resampled;
    $ret_array[] = $this->height_resampled;
    $ret_array[] = $this->block_size_x;
    $ret_array[] = $this->block_size_y;
    $ret_array[] = $this->flip_horizontal ? 'h_flip' : '';

    $ret_array = array_filter($ret_array);

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

    // Check if the image json actually exists.
    $pixel_array = $this->cache_manager($json_filename);

    // If the pixels array is empty, then we need to generate & cache the data.
    if (!$this->DEBUG_MODE && empty($pixel_array)) {
      $image_processed = $this->resample_image();
      $pixel_array = $this->generate_pixels($this->image_file, $image_processed, FALSE);
      $this->cache_manager($json_filename, $pixel_array);
      // $this->pixelate_image($image_processed);
      $this->pixelate_image_json($image_processed);
    }

    // Process the pixel_array
    $blocks = array();
    foreach ($pixel_array as $pixel_row) {
      if ($this->flip_horizontal) {
        $pixel_row = array_reverse($pixel_row);
      }
      foreach ($pixel_row as $pixel) {
        $blocks[] = $this->generate_pixel_boxes($pixel);
      }
    }

    $ret = '';
    if (!empty($blocks)) {
      $ret = $this->render_pixel_box_container($blocks);
    }

    return $ret;

  } // process_image


  // Manage caching.
  function cache_manager ($json_filename, $pixel_array = null) {

    if (!empty($pixel_array)) {

      // If the cache directory doesn’t exist, create it.
      if (!is_dir($this->cache_path['json'])) {
        mkdir($this->cache_path['json'], 0755);
      }

      // Cache the pixel blocks to a JSON file.
      $file_handle = fopen($json_filename, 'w');
      fwrite($file_handle, json_encode((object) $pixel_array, JSON_PRETTY_PRINT));
      fclose($file_handle);

      return FALSE;

    }
    else if (!empty(realpath($json_filename))) {
      return json_decode(file_get_contents($json_filename), TRUE);
    }

    return FALSE;

  } // process_image


  // Resample the image.
  function resample_image () {

    // Get the source image.
    $image_source = imagecreatefromjpeg($this->image_file);

    // Set the canvas for the processed image.
    $image_processed = imagecreatetruecolor($this->width_resampled, $this->height_resampled);

    // Get the image dimensions.
    $this->width_source = imagesx($image_source);
    $this->height_source = imagesy($image_source);

    // Process the image via 'imagecopyresampled'
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $this->width_resampled, $this->height_resampled, $this->width_source, $this->height_source);

    // Get rid of the image to free up memory.
    imagedestroy($image_source);

    return $image_processed;

  } // resample_image


  // Pixelate the image via JSON data.
  function pixelate_image_json ($image_source) {

    // Process the JSON filename.
    $json_filename = $this->create_filename($this->image_file, 'json');

    $pixel_array = $this->cache_manager($json_filename);

    // Calculate the final width & final height
    $width_pixelate = $this->width_resampled * $this->block_size_x;
    $height_pixelate = $this->height_resampled * $this->block_size_y;

    // Set the canvas for the processed image & resample the source image.
    $image_processed = imagecreatetruecolor($width_pixelate, $height_pixelate);
    imagefill($image_processed, 0, 0, IMG_COLOR_TRANSPARENT);

    // Process the pixel_array
    $blocks = array();
    foreach ($pixel_array as $position_y => $pixel_row) {
      $box_y = ($position_y * $this->block_size_y);
      foreach ($pixel_row as  $position_x => $pixel) {
        $box_x = ($position_x * $this->block_size_x);
        $color = imagecolorclosest($image_processed, $pixel['red'], $pixel['green'], $pixel['blue']);
        imagefilledrectangle($image_processed, $box_x, $box_y, ($box_x + $this->block_size_x), ($box_y + $this->block_size_y), $color);
      }
    }

    // Place a tiled overlay on the image.
    if ($this->flip_horizontal) {
      imageflip($image_processed, IMG_FLIP_HORIZONTAL);
    }

    // Place a tiled overlay on the image.
    $tiled_overlay = imagecreatefrompng($this->overlay_tile);
    imagealphablending($image_processed, true);
    imagesettile($image_processed, $tiled_overlay);
    imagefilledrectangle($image_processed, 0, 0, $width_pixelate, $height_pixelate, IMG_COLOR_TILED);
    imagedestroy($tiled_overlay);

    // Save the images.
    $image_filenames = array();
    foreach ($this->image_types as $image_type) {

      // If the cache directory doesn’t exist, create it.
      if (!is_dir($this->cache_path[$image_type])) {
        mkdir($this->cache_path[$image_type], 0755);
      }

      // Process the filename & generate the image files.
      $filename = $this->create_filename($this->image_file, $image_type);
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

    if ($this->DEBUG_MODE) {
      imagepng($image_processed, 'zzz_debug.png', $this->image_quality['png']);
    }

    imagedestroy($image_processed);

  } // pixelate_image_json


  // Pixelate the image.
  function pixelate_image ($image_source) {

    // Calculate the final width & final height
    $width_pixelate = $this->width_resampled * $this->block_size_x;
    $height_pixelate = $this->height_resampled * $this->block_size_y;

    // Set the canvas for the processed image & resample the source image.
    $image_processed = imagecreatetruecolor($width_pixelate, $height_pixelate);
    imagefill($image_processed, 0, 0, IMG_COLOR_TRANSPARENT);
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $width_pixelate, $height_pixelate, $this->width_resampled, $this->height_resampled);

    // Loop through the origina image, get a color and then create a new box/rectangle based on that box.
    $box_x = $box_y = 0;
    for ($position_y = 0; $position_y <= $this->height_resampled; $position_y += 1) {
      $box_y = ($position_y * $this->block_size_y);
      for ($position_x = 0; $position_x <= $this->width_resampled; $position_x += 1) {
        $box_x = ($position_x * $this->block_size_x);
        $rgb = imagecolorsforindex($image_processed, imagecolorat($image_source, $position_x, $position_y));
        $color = imagecolorclosest($image_processed, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($image_processed, $box_x, $box_y, ($box_x + $this->block_size_x), ($box_y + $this->block_size_y), $color);
      }  // width loop.
    }  // height loop.

    // Place a tiled overlay on the image.
    if ($this->flip_horizontal) {
      imageflip($image_processed, IMG_FLIP_HORIZONTAL);
    }

    // Place a tiled overlay on the image.
    $tiled_overlay = imagecreatefrompng($this->overlay_tile);
    imagealphablending($image_processed, true);
    imagesettile($image_processed, $tiled_overlay);
    imagefilledrectangle($image_processed, 0, 0, $width_pixelate, $height_pixelate, IMG_COLOR_TILED);
    imagedestroy($tiled_overlay);

    // Save the images.
    $image_filenames = array();
    foreach ($this->image_types as $image_type) {

      // If the cache directory doesn’t exist, create it.
      if (!is_dir($this->cache_path[$image_type])) {
        mkdir($this->cache_path[$image_type], 0755);
      }

      // Process the filename & generate the image files.
      $filename = $this->create_filename($this->image_file, $image_type);
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

    if ($this->DEBUG_MODE) {
      imagepng($image_processed, 'zzz_debug.png', $this->image_quality['png']);
    }

    imagedestroy($image_processed);

  } // pixelate_image


  // Generate the pixel boxes.
  function generate_pixel_boxes ($rgb_array) {

    // $rgb_final = sprintf('rgb(%s)', implode(',', $rgb_array));
    $hex_final = sprintf("#%02X%02X%02X", $rgb_array['red'], $rgb_array['green'], $rgb_array['blue']);

    $block_dimensions = sprintf('height: %spx; width: %spx;', $this->block_size_x, $this->block_size_y);

    if (FALSE) {
      $block_rgb = sprintf('background-color: %s;', $rgb_final);
      $block_style = $block_dimensions . ' ' . $block_rgb;
    }
    else {
      $block_hex = sprintf('background-color: %s;', $hex_final);
      $block_style = $block_dimensions . ' ' . $block_hex;
    }

    $ret = sprintf('<div class="PixelBox" style="%s">', $block_style)
         . '</div><!-- .PixelBox -->' . "\r\n"
         ;

    return $ret;

  } // generate_pixel_boxes


  // Generate the pixels.
  function generate_pixels ($image_file, $image_processed) {

    $ret = array();

    for ($height = 0; $height < $this->height_resampled; $height++) {

      $rows = array();
      for ($width = 0; $width <= $this->width_resampled; $width++) {

        $color_index = imagecolorat($image_processed, $width, $height);

        if (FALSE) {
          $rgb_array = array();

          $red = ($color_index >> 16) & 0xFF;
          $green = ($color_index >> 8) & 0xFF;
          $blue = $color_index & 0xFF;

          $rgb_array['red'] = intval($red);
          $rgb_array['green'] = intval($green);
          $rgb_array['blue'] = intval($blue);
        }
        else {
          $rgb_array = imagecolorsforindex($image_processed, $color_index);
        }

        if ($width != $this->width_resampled) {
          $rows[] = $rgb_array;
        }

        if ($width == $this->width_resampled) {
          $ret[] = $rows;
        }

      } // $width loop.

    } // $height loop.

    // Get rid of the image to free up memory.
    imagedestroy($image_processed);

    return $ret;

  } // generate_pixels


  // Render the pixel boxes into a container.
  function render_pixel_box_container ($blocks) {

   $css_width = $this->width_resampled * $this->block_size_x;
   $css_height = $this->height_resampled * $this->block_size_y;

    $block_container_dimensions = sprintf('width: %spx; height: %spx;', $css_width, $css_height);

    $ret = sprintf('<div class="PixelBoxConatiner" style="%s">' . "\r\n", $block_container_dimensions)
         . implode('', $blocks)
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