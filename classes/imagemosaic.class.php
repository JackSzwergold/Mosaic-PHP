<?

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
// Here is where the magic happens!

class ImageMosaic {

  private $DEBUG_MODE = FALSE;

  private $image = FALSE;

  private $height_final = 46;
  private $width_final = 46;

  private $box_size = 10;

  public function __construct() {
  } // __construct

  public function set_image($image, $height_final, $width_final, $box_size) {
    $this->image = $image;
    $this->height_final = $height_final;
    $this->width_final = $width_final;
    $this->box_size = $box_size;
  } // __construct

  // Output the image straight to the browser.
  function resample_image () {

    // Get the source image.
    $image_source = imagecreatefromjpeg($this->image);

    // Set the canvas for the processed image.
    $image_processed = imagecreatetruecolor($this->width_final, $this->height_final);

    // Get the image dimensions.
    $this->width_o = imagesx($image_source);
    $this->height_o = imagesy($image_source);

    // Process the image via 'imagecopyresampled'
    imagecopyresampled($image_processed, $image_source, 0, 0, 0, 0, $this->width_final, $this->height_final, $this->width_o, $this->height_o);

    return $image_processed;

  } // resample_image

  // Output the image straight to the browser.
  function generate_blocks ($image_processed) {

    $pixel_blocks = array();

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

        $rgb_final = implode(',', $rgb_array);

        if ($width != $this->width_final) {
          $block_rgb = sprintf('background-color:rgb(%s);', $rgb_final);
          $block_style = "float: left; display: inline; position: relative; height: ". $this->box_size ."px; width: ". $this->box_size ."px; margin: 0; padding: 0; border: 0;" . $block_rgb;
          $pixel_blocks_row[] = sprintf('<div style="%s"></div>' . "\r\n", $block_style);
        }
        if ($width == $this->width_final) {
          // $final_row = array_reverse($pixel_blocks_row);
          $final_row = $pixel_blocks_row;
          $pixel_blocks[] = implode('', $final_row);
        }

      } // $width loop.

    } // $height loop.

    return $pixel_blocks;

  } // generate_blocks

  // Output the image straight to the browser.
  function render_image ($image_processed) {

    header('Content-Type: image/jpeg');
    imagejpeg($image_processed, null, 60);

  } // renderImage

  // Output the image straight to the browser.
  function render_blocks ($pixel_blocks) {

    $ret = '<div style="float: left; position: relative; display: inline; background-color: black; width: ' . $this->width_final * $this->box_size . 'px;">'
         . implode('', $pixel_blocks)
         .'</div>'
         ;

    return $ret;

  } // renderImage

} // ImageMosaic

?>