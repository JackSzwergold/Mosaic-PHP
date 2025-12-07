<?php

/**
 * Frontend Display Class (frontendDisplay.class.php) (c) by Jack Szwergold
 *
 * Frontend Display Class is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
 *
 * Created: 2014-01-22, js
 * Version: 2014-01-22, js: creation
 *          2014-01-22, js: development & cleanup
 *          2014-01-23, js: refinements
 *          2014-02-17, js: setting a 'base'
 *          2014-02-27, js: adding a page URL
 *          2015-05-10, js: adding DIV wrapper class & id
 *          2015-05-11, js: setting dynamic DIV wrapper creation
 *          2016-06-09, js: major reshuffling to get footers, headers, content and ads working
 *
 */

//**************************************************************************************//
// The beginnings of a frontend display class.

class frontendDisplay {

  private $DEBUG_MODE = FALSE;

  public $content;
  public $html_content;

  public $link_items = array();

  private $view_mode = NULL;
  private $view_div = NULL;

  //**************************************************************************************//
  // Set the constructor.
  public function __construct() {
  } // __construct

  //**************************************************************************************//
  // Set the page mode.
  function setViewMode($view_mode = null, $view_div = false) {
    $this->view_mode = $view_mode;
    $this->view_div = $view_div;
  } // setViewMode

  //**************************************************************************************//
  // Init the core content.
  function initCoreContent($response_header = null) {
    $this->buildCoreContent();
  } // initCoreContent

  //**************************************************************************************//
  // Init the content.
  function initHTMLContent($response_header = null) {
    $this->buildHTMLContent();
  } // initHTMLContent

  //**************************************************************************************//
  // Build the core content.
  function buildCoreContent() {
    if (!empty($this->html_content)) {
      $this->html_content = $this->html_content;
    } // if
  } // buildCoreContent

  //**************************************************************************************//
  // Build the HTML content.
  function buildHTMLContent() {

    if (!empty($this->html_content)) {

      //**********************************************************************************//
      // Set the CSS.
      $css_array = $this->setHeaderLinkArray($this->link_items);

      //**********************************************************************************//
      // Set the view wrapper.
      if (!empty($this->view_mode) && $this->view_div) {
        $body =
            sprintf('<div class="%sView">', $this->view_mode)
          . $this->setWrapper($this->html_content)
          . sprintf('</div><!-- .%sView -->', $this->view_mode)
          ;
      } // if
      else {
        $body = $this->setWrapper($this->html_content);
      } // else

      //**********************************************************************************//
      // Set the HTML content class.
      $this->html_content = join('', $css_array) . $body;

    } // if

  } // buildHTMLContent

  //**************************************************************************************//
  // Set the header link stuff.
  function setHeaderLinkArray($array = array()) {
    $ret = array();
    if (empty($array)) {
      return $ret;
    } // if
    foreach ($array as $array_type => $array_parts) {
      $parts = array();
      foreach ($array_parts as $key => $value) {
        if ($key == 'href') {
          if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $value = BASE_URL . $value;
          } // if
        } // if
        $parts[] = $key . '="' . $value . '"';
      } // foreach
      // $ret[$array_type] = sprintf('<!-- %s link_items -->', $type);
      $ret[$array_type] = sprintf('<link %s>', join(' ', $parts));
    } // foreach
    return $ret;
  } // setHeaderLinkArray

  //**************************************************************************************//
  // Set the wrapper.
  function setWrapper($body = null) {

    $body_div_stuff = array();
    $body_div_close_stuff = array();

    if (!empty($this->page_div_wrapper_class)) {
      $body_div_stuff[] = sprintf('class="%s"', $this->page_div_wrapper_class);
      $body_div_close_stuff[] = sprintf('.%s', $this->page_div_wrapper_class);
    }

    if (!empty($this->page_div_wrapper_id)) {
      $body_div_stuff[] = sprintf('id="%s"', $this->page_div_wrapper_id);
    }

    if (!empty($this->page_div_wrapper_class) || (!empty($this->page_div_wrapper_class) && !empty($this->page_div_wrapper_id))) {
      $body = sprintf('<div %s>', implode(' ', $body_div_stuff))
            . $body
            . sprintf('</div><!-- %s -->', implode(' ', $body_div_close_stuff))
            ;
    }

    //************************************************************************************//
    // Set the wrapper divs.
    $div_opening = $div_closing = '';
    if (!empty($this->page_div_wrappper_array)) {
      $div_opening = '<div class="' . implode('">' . "\n" . '<div class="', $this->page_div_wrappper_array) . '">';
      $div_closing = '</div><!-- .' . implode('-->' . "\n" . '</div><!-- .', array_reverse($this->page_div_wrappper_array)) . ' -->';
    }

    $ret = (!empty($nameplate) ? $nameplate : '')
         . $div_opening
         . $body
         . $div_closing
         ;

    return $ret;

  } // setWrapper

} // frontendDisplay

?>
