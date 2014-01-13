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
 *
 */

//**************************************************************************************//
// Here is where the magic happens!

class ImageMosaic {

  private $DEBUG_MODE = FALSE;

  private $image_file = FALSE;

  private $height_final = 46;
  private $width_final = 46;

  private $block_size = 10;

  private $cache_path = 'cache/';

  public function __construct() {
  } // __construct

  public function set_image($image_file, $width_final, $height_final, $block_size) {
    $this->image_file = $image_file;
    $this->width_final = $width_final;
    $this->height_final = $height_final;
    $this->block_size = $block_size;
  } // set_image

  // Process the image.
  function process_image () {

  } // process_image

  // Resample the image.
  function resample_image () {

    // Check if the image actually exists.
    if (empty(realpath($this->image_file))) {
      return;
    }

    // Get the source image.
    $image_source = imagecreatefromjpeg($this->image_file);

    // Set the canvas for the processed image.
    $image_processed = imagecreatetruecolor($this->width_final, $this->height_final);

    // Get the image dimensions.
    $this->width_o = imagesx($image_source);
    $this->height_o = imagesy($image_source);

    // Process the image via 'imagecopyresampled'
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $this->width_final, $this->height_final, $this->width_o, $this->height_o);

    return $image_processed;

  } // resample_image

  // Generate the CSS blocks.
  function generate_blocks ($image_file, $image_processed, $flip_rows) {

    $filepath_parts = pathinfo($image_file);
    $json_filename = $this->cache_path . $filepath_parts['filename'] . '.json';

    $pixel_blocks = array();

    // Check if the image actually exists.
    if (!empty(realpath($json_filename))) {
      $pixel_blocks = json_decode(file_get_contents($json_filename));
    }
    else {
    for ($height = 0; $height < $this->height_final; $height++) {

      $pixel_blocks_row = array();
      for ($width = 0;$width <= $this->width_final; $width++) {

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

        if ($width != $this->width_final) {
          $block_dimensions = sprintf('height: %spx; width: %spx;', $this->block_size, $this->block_size);

          if (FALSE) {
            $block_rgb = sprintf('background-color: %s;', $rgb_final);
            $block_style = $block_dimensions . $block_rgb;
          }
          else {
            $block_hex = sprintf('background-color: %s;', $hex_final);
            $block_style = $block_dimensions . $block_hex;
          }

          $pixel_blocks_row[] = sprintf('<div class="PixelBox" style="%s">', $block_style)
                              . '</div><!-- .PixelBox -->' . "\r\n"
                              ;
        }
        if ($width == $this->width_final) {
          // $final_row = array_reverse($pixel_blocks_row);
          $final_row = $flip_rows ? array_reverse($pixel_blocks_row) : $pixel_blocks_row;
          $pixel_blocks[] = implode('', $final_row);
        }

      } // $width loop.

    } // $height loop.

    // Cache the pixel blocks to a JSON file.
    if (!is_dir($this->cache_path)) {
      mkdir($this->cache_path, 0755);
    }

    $file_handle = fopen($json_filename, 'w');
    fwrite($file_handle, json_encode($pixel_blocks));
    fclose($file_handle);
    }

    return $pixel_blocks;

  } // generate_blocks

  // Output the image straight to the browser.
  function render_blocks ($pixel_blocks) {

    $block_container_dimensions = sprintf('width: %spx;', $this->width_final * $this->block_size);

    $ret = sprintf('<div class="PixelBoxConatiner" style="%s">' . "\r\n", $block_container_dimensions)
         . implode('', $pixel_blocks)
         .'</div><!-- .PixelBoxConatiner -->' . "\r\n"
         ;

    return $ret;

  } // render_blocks

  // Output the image straight to the browser.
  function render_image ($image_processed) {

    header('Content-Type: image/jpeg');

    imagejpeg($image_processed, null, 60);

  } // renderImage

} // ImageMosaic

?>