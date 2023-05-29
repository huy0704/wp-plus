<?php
class WDW_S_Library {
  /**
   * Get request value.
   *
   * @param string $key
   * @param string $default_value
   * @param bool $esc_html
   *
   * @return string|array
   */
  public static function get($key, $default_value = '', $esc_html = true) {
    if (isset($_GET[$key])) {
      $value = $_GET[$key];
    }
    elseif (isset($_POST[$key])) {
      $value = $_POST[$key];
    }
    elseif (isset($_REQUEST[$key])) {
      $value = $_REQUEST[$key];
    }
    else {
      $value = $default_value;
    }
    if (is_array($value)) {
      array_walk_recursive($value, array('self', 'validate_data'), $esc_html);
    }
    else {
      self::validate_data($value, $esc_html);
    }
    return $value;
  }

  public static function esc_sanitize_data( $post, $key, $sanitize_function = '', $default_value = '', $params = array() ) {
    $value = $default_value;
    if ( isset($post[$key]) ) {
      $value = $post[$key];
      if (!empty($sanitize_function) && function_exists($sanitize_function)) {
        $value = $sanitize_function($value);
      }
    }

    return $value;
  }

  public static function esc_data( $value, $esc_function = '' ) {
    $value = stripslashes($value);
    if (!empty($esc_function) && function_exists($esc_function)) {
      $value = $esc_function($value);
    }

    return $value;
  }

   /**
   * Validate data.
   *
   * @param $value
   * @param $esc_html
   */
  private static function validate_data(&$value, $esc_html) {
    $value = stripslashes($value);
    if ($esc_html) {
      $value = esc_html($value);
    }
  }

  /**
   * Generate message container by message id or directly by message.
   *
   * @param int $message_id
   * @param string $message If message_id is 0
   * @param string $type
   *
   * @return mixed|string|void
   */
  public static function message_id($message_id, $message = '', $type = 'updated') {
    if ($message_id) {
      switch ( $message_id ) {
        case 1: {
          $message = __('Item Succesfully Saved.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 2: {
          $message = __('Error. Please install plugin again.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 3: {
          $message = __('Item Succesfully Deleted.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 4: {
          $message = __("You can't delete default theme", WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 5: {
          $message = __('Items Succesfully Deleted.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 6: {
          $message = __('You must select at least one item.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 7: {
          $message = __('The item is successfully set as default.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 8: {
          $message = __('Options Succesfully Saved.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 9: {
          $message = __('Item Succesfully Published.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 10: {
          $message = __('Items Succesfully Published.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 11: {
          $message = __('Item Succesfully Unpublished.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 12: {
          $message = __('Items Succesfully Unpublished.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 13: {
          $message = __('Ordering Succesfully Saved.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 14: {
          $message = __('A term with the name provided already exists.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 15: {
          $message = __('Name field is required.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 16: {
          $message = __('The slug must be unique.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 17: {
          $message = __('Changes must be saved.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 18: {
          $message = __('You must set watermark type.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 19: {
          $message = __('Watermark Succesfully Set.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 20: {
          $message = __('Watermark Succesfully Reset.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 21: {
          $message = __('Settings Succesfully Reset.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 22: {
          $message = __('Items Succesfully Set.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 23: {
          $message = __('Slider successfully imported.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        case 24: {
          $message = __('Unexpected error occurred.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
        case 25: {
          $message = __('You can include only posts with featured image.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
		case 26: {
          $message = __('Item Succesfully Duplicated.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
		case 27: {
          $message = __('You should select at least 2 sliders to merge them.', WDS()->prefix);
          $type = 'wd_error';
          break;
        }
		case 28: {
          $message = __('The selected items are merged as a new slider.', WDS()->prefix);
          $type = 'wd_updated';
          break;
        }
        default: {
          $message = '';
          break;
        }
      }
    }

    if ( $message ) {
      ob_start();
      ?><div style="width: 99%;"><div class="<?php echo $type; ?> inline">
      <p>
        <strong><?php echo $message; ?></strong>
      </p>
      </div></div><?php
      $message = ob_get_clean();
    }

    return $message;
  }

  public static function message($message, $type) {
    return '<div style="width: 99%" class="spider_message"><div class="' . $type . '"><p><strong>'. $message .'</strong></p></div></div>';
  }

  /**
   * Ordering.
   *
   * @param        $id
   * @param        $orderby
   * @param        $order
   * @param        $text
   * @param        $page_url
   * @param string $additional_class
   *
   * @return string
   */
  public static function ordering($id, $orderby, $order, $text, $page_url, $additional_class = '') {
    $class = array(
      ($orderby == $id ? 'sorted': 'sortable'),
      $order,
      $additional_class,
      'col_' . $id,
    );
    $order = (($orderby == $id) && ($order == 'asc')) ? 'desc' : 'asc';
    ob_start();
    ?>
    <th id="order-<?php echo $id; ?>" class="<?php echo implode(' ', $class); ?>">
      <a href="<?php echo add_query_arg( array('orderby' => $id, 'order' => $order), $page_url ); ?>"
         title="<?php _e('Click to sort by this item', WDS()->prefix); ?>">
        <span><?php echo $text; ?></span><span class="sorting-indicator"></span>
      </a>
    </th>
    <?php
    return ob_get_clean();
  }
// TODO. old version.
  public static function search($search_by, $search_value, $form_id) {
    $search_position = ($form_id == 'posts_form') ? 'alignleft' : 'alignright';
    ?>
    <div class="<?php echo $search_position; ?> actions" style="clear: both;">
      <script>
        function spider_search(event) {
          if (typeof event != 'undefined') {
            var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
            if (keyCode != 13) {
              return false;
            }
          }
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
          if (typeof event != 'undefined') {
            if (event.preventDefault) {
              event.preventDefault();
            }
            else {
              event.returnValue = false;
            }
          }
        }
        function spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("category_id")) {
            document.getElementById("category_id").value = -1;
          }
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="alignleft actions">
        <input type="search"
               id="search_value"
               name="search_value"
               value="<?php echo esc_html($search_value); ?>"
               onkeypress="spider_search(event)" />
        <input type="button" value="<?php _e('Search', WDS()->prefix); ?>" onclick="spider_search()" class="button" />
      </div>
    </div>
    <?php
  }
  
  public static function html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {       
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo $form_id; ?>').submit();
      }
      function check_enter_key(e) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <div class="tablenav-pages" style="text-align:right">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items;
          _e(' item', WDS()->prefix);
          echo (($count_items == 1) ? '' : __('s', WDS()->prefix));
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="<?php _e('Go to the first page', WDS()->prefix); ?>" href="javascript:spider_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="<?php _e('Go to the previous page', WDS()->prefix); ?>" href="javascript:spider_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="<?php _e('Go to the next page', WDS()->prefix); ?>" href="javascript:spider_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="<?php _e('Go to the last page', WDS()->prefix); ?>" href="javascript:spider_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
      }
      function check_enter_key(e) { 	  
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          return false;
        }
       return true;		 
      }
    </script>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
            echo $count_items;
            _e('item', WDS()->prefix);
            echo (($count_items == 1) ? '' : __('s', WDS()->prefix));
       }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="<?php _e('Go to the first page', WDS()->prefix); ?>" onclick="spider_page(<?php echo $page_number; ?>,-2)">«</a>
        <a class="<?php echo $prev_page; ?>" title="<?php _e('Go to the previous page', WDS()->prefix); ?>" onclick="spider_page(<?php echo $page_number; ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="<?php _e('Go to the next page', WDS()->prefix); ?>" onclick="spider_page(<?php echo $page_number; ?>,1)">›</a>
        <a class="<?php echo $last_page ?>" title="<?php _e('Go to the last page', WDS()->prefix); ?>" onclick="spider_page(<?php echo $page_number; ?>,2)">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
  }

  public static function spider_hex2rgb($colour) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
  }

  public static function spider_hex2rgba($colour, $transparent = 1) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . number_format($transparent, 2, ".", "") . ')';
  }
// TODO remove this and rename all to redirect.
  public static function spider_redirect($url) {
    $url = html_entity_decode(wp_nonce_url($url, 'nonce_wd', 'nonce_wd'));
    ?>
    <script>
      window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }

  public static function verify_nonce($page){
    $nonce_verified = FALSE;
    if (isset($_GET['nonce_wd']) && wp_verify_nonce($_GET['nonce_wd'], $page)) {
      $nonce_verified = TRUE;
    }
    if (!$nonce_verified) {
      die('Sorry, your nonce did not verify.');
    }
  }

  /**
   * Get google fonts dynamically.
   *
   * @return array
   */
  public static function get_google_fonts() {
    // Get google fonts.
    $api_key = 'AIzaSyCmC0Yr_RUaqeQAPvasFhFer1ixJQ6N0Ag';
    $response = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?key=' . $api_key);

    $google_fonts = array();
    if ( !is_wp_error($response) && isset($response["body"]) ) {
      $response_body = json_decode($response["body"], TRUE);
      foreach ( $response_body['items'] as $item ) {
        $google_fonts[$item['family']] = $item['family'];
      }
    }

    return $google_fonts;
  }

  /**
   * No items.
   *
   * @param $title
   * @param $colspan_count
   *
   * @return string
   **/
  public static function no_items($title, $colspan_count = 0) {
    $title = ($title != '') ? strtolower($title) : 'items';
    ob_start();
    ?><tr class="no-items">
    <td class="colspanchange" <?php echo $colspan_count ? 'colspan="' . $colspan_count . '"' : ''?>><?php echo sprintf(__('No %s found.', WDS()->prefix), $title); ?></td>
    </tr><?php
    return ob_get_clean();
  }

  public static function get_font_families() {
    $wds_global_options = get_option("wds_global_options", 0);
    $global_options = json_decode($wds_global_options);
    $possib_add_ffamily = isset($global_options->possib_add_ffamily) ? $global_options->possib_add_ffamily : '';
    $font_families = array(
      'arial' => 'Arial',
      'lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' =>'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    if ($possib_add_ffamily != '') {
      $possib_add_ffamily = explode("*WD*", $possib_add_ffamily);
      foreach($possib_add_ffamily as $possib_add_value) {
        if ($possib_add_value) {
          $font_families[strtolower($possib_add_value)] = $possib_add_value;
        }
      }
    }
    return $font_families;
  }

  public static function validate_audio_file( $tmp ) {
    if ( !empty($tmp) ) {
      if ( preg_match('/^(http|https):\/\/([a-z0-9-]\.+)*/i', $tmp) ) {
        // check external link
        $pos = strpos($tmp, site_url());
        if ( $pos === FALSE ) {
          $ext = substr(strrchr($tmp, '.'), 1);
          switch ( $ext ) {
            case 'aac':
            case 'm4a':
            case 'f4a':
            case 'mp3':
            case 'ogg':
            case 'oga':
              return TRUE;
              break;
            default:
              return FALSE;
              break;
          }
        }
        else {
          if ( !class_exists('finfo') ) {
            return TRUE;
          }
          $allowed = array(
            'audio/mp4', // .aac,.m4a,.f4a,
            'audio/mpeg',
            'audio/x-mpeg',
            'audio/mpeg3',
            'audio/x-mpeg-3', // mp3
            'audio/ogg' // .ogg,.oga
          );
          $filePath = str_replace(site_url() . '/', ABSPATH, $tmp);
          if ( file_exists($filePath) ) {
            $filePath = str_replace(site_url() . '/', ABSPATH, $tmp);
            if ( file_exists($filePath) ) {
              $info = new finfo(FILEINFO_MIME);
              $type = $info->buffer(file_get_contents($tmp));
              if ( !empty($type) ) {
                $mime_t = explode(';', $type);
                $mime_type = $mime_t[0];
                // check to see if REAL MIME type is inside $allowed array
                if ( !empty($mime_type) && in_array($mime_type, $allowed) ) {
                  return TRUE;
                }
              }
            }
          }
        }
      }
    }

    return FALSE;
  }

  /**
   * Return option values.
   *
   * @return array
   */
  public static function get_values() {
    $values = array();
    $values['aligns'] = array(
      'left' => __('Left', WDS()->prefix),
      'center' => __('Center', WDS()->prefix),
      'right' => __('Right', WDS()->prefix),
    );
    $values['border_styles'] = array(
      'none' => __('None', WDS()->prefix),
      'solid' => __('Solid', WDS()->prefix),
      'dotted' => __('Dotted', WDS()->prefix),
      'dashed' => __('Dashed', WDS()->prefix),
      'double' => __('Double', WDS()->prefix),
      'groove' => __('Groove', WDS()->prefix),
      'ridge' => __('Ridge', WDS()->prefix),
      'inset' => __('Inset', WDS()->prefix),
      'outset' => __('Outset', WDS()->prefix),
    );
    $values['button_styles'] = array(
      'fa-chevron' => __('Chevron', WDS()->prefix),
      'fa-angle' => __('Angle', WDS()->prefix),
      'fa-angle-double' => __('Double', WDS()->prefix),
    );
    $values['bull_styles'] = array(
      'fa-circle-o' => __('Circle O', WDS()->prefix),
      'fa-circle' => __('Circle', WDS()->prefix),
      'fa-minus' => __('Minus', WDS()->prefix),
      'fa-square-o' => __('Square O', WDS()->prefix),
      'fa-square' => __('Square', WDS()->prefix),
    );
    $values['font_families'] = WDW_S_Library::get_font_families();
    $values['google_fonts'] = WDW_S_Library::get_google_fonts();
    $values['font_weights'] = array(
      'lighter' => __('Lighter', WDS()->prefix),
      'normal' => __('Normal', WDS()->prefix),
      'bold' => __('Bold', WDS()->prefix),
    );
    $values['social_buttons'] = array(
      'facebook' => __('Facebook', WDS()->prefix),
      'twitter' => __('Twitter', WDS()->prefix),
      'pinterest' => __('Pinterest', WDS()->prefix),
      'tumblr' => __('Tumblr', WDS()->prefix),
    );
    $values['effects'] = array(
      'none' => __('None', WDS()->prefix),
      'zoomFade' => __('Zoom Fade', WDS()->prefix),
      'parallelSlideH' => __('Parallel Slide Horizontal', WDS()->prefix),
      'parallelSlideV' => __('Parallel Slide Vertical', WDS()->prefix),
      'slic3DH' => __('Slice 3D Horizontal', WDS()->prefix),
      'slic3DV' => __('Slice 3D Vertical', WDS()->prefix),
      'slicR3DH' => __('Slice 3D Horizontal Random', WDS()->prefix),
      'slicR3DV' => __('Slice 3D Vertical Random', WDS()->prefix),
      'blindR' => __('Blind', WDS()->prefix),
      'tilesR' => __('Tiles', WDS()->prefix),
      'blockScaleR' => __('Block Scale Random', WDS()->prefix),
      'cubeH' => __('Cube Horizontal', WDS()->prefix),
      'cubeV' => __('Cube Vertical', WDS()->prefix),
      'cubeR' => __('Cube Random', WDS()->prefix),
      'fade' => __('Fade', WDS()->prefix),
      'sliceH' => __('Slice Horizontal', WDS()->prefix),
      'sliceV' => __('Slice Vertical', WDS()->prefix),
      'slideH' => __('Slide Horizontal', WDS()->prefix),
      'slideV' => __('Slide Vertical', WDS()->prefix),
      'scaleOut' => __('Scale Out', WDS()->prefix),
      'scaleIn' => __('Scale In', WDS()->prefix),
      'blockScale' => __('Block Scale', WDS()->prefix),
      'kaleidoscope' => __('Kaleidoscope', WDS()->prefix),
      'fan' => __('Fan', WDS()->prefix),
      'blindH' => __('Blind Horizontal', WDS()->prefix),
      'blindV' => __('Blind Vertical', WDS()->prefix),
      'random' => __('Random', WDS()->prefix),
      '3Drandom' => __('3D Random', WDS()->prefix),
    );
    $values['layer_effects_in'] = array(
      'none' => __('None', WDS()->prefix),
      'bounce' => __('Bounce', WDS()->prefix),
      'flash' => __('Flash', WDS()->prefix),
      'pulse' => __('Pulse', WDS()->prefix),
      'rubberBand' => __('RubberBand', WDS()->prefix),
      'shake' => __('Shake', WDS()->prefix),
      'swing' => __('Swing', WDS()->prefix),
      'tada' => __('Tada', WDS()->prefix),
      'wobble' => __('Wobble', WDS()->prefix),
      'hinge' => __('Hinge', WDS()->prefix),

      'lightSpeedIn' => __('LightSpeedIn', WDS()->prefix),
      'rollIn' => __('RollIn', WDS()->prefix),

      'bounceIn' => __('BounceIn', WDS()->prefix),
      'bounceInDown' => __('BounceInDown', WDS()->prefix),
      'bounceInLeft' => __('BounceInLeft', WDS()->prefix),
      'bounceInRight' => __('BounceInRight', WDS()->prefix),
      'bounceInUp' => __('BounceInUp', WDS()->prefix),

      'fadeIn' => __('FadeIn', WDS()->prefix),
      'fadeInDown' => __('FadeInDown', WDS()->prefix),
      'fadeInDownBig' => __('FadeInDownBig', WDS()->prefix),
      'fadeInLeft' => __('FadeInLeft', WDS()->prefix),
      'fadeInLeftBig' => __('FadeInLeftBig', WDS()->prefix),
      'fadeInRight' => __('FadeInRight', WDS()->prefix),
      'fadeInRightBig' => __('FadeInRightBig', WDS()->prefix),
      'fadeInUp' => __('FadeInUp', WDS()->prefix),
      'fadeInUpBig' => __('FadeInUpBig', WDS()->prefix),

      'flip' => __('Flip', WDS()->prefix),
      'flipInX' => __('FlipInX', WDS()->prefix),
      'flipInY' => __('FlipInY', WDS()->prefix),

      'rotateIn' => __('RotateIn', WDS()->prefix),
      'rotateInDownLeft' => __('RotateInDownLeft', WDS()->prefix),
      'rotateInDownRight' => __('RotateInDownRight', WDS()->prefix),
      'rotateInUpLeft' => __('RotateInUpLeft', WDS()->prefix),
      'rotateInUpRight' => __('RotateInUpRight', WDS()->prefix),

      'zoomIn' => __('ZoomIn', WDS()->prefix),
      'zoomInDown' => __('ZoomInDown', WDS()->prefix),
      'zoomInLeft' => __('ZoomInLeft', WDS()->prefix),
      'zoomInRight' => __('ZoomInRight', WDS()->prefix),
      'zoomInUp' => __('ZoomInUp', WDS()->prefix),
    );
    $values['layer_effects_out'] = array(
      'none' => __('None', WDS()->prefix),
      'bounce' => __('Bounce', WDS()->prefix),
      'flash' => __('Flash', WDS()->prefix),
      'pulse' => __('Pulse', WDS()->prefix),
      'rubberBand' => __('RubberBand', WDS()->prefix),
      'shake' => __('Shake', WDS()->prefix),
      'swing' => __('Swing', WDS()->prefix),
      'tada' => __('Tada', WDS()->prefix),
      'wobble' => __('Wobble', WDS()->prefix),
      'hinge' => __('Hinge', WDS()->prefix),

      'lightSpeedOut' => __('LightSpeedOut', WDS()->prefix),
      'rollOut' => __('RollOut', WDS()->prefix),

      'bounceOut' => __('BounceOut', WDS()->prefix),
      'bounceOutDown' => __('BounceOutDown', WDS()->prefix),
      'bounceOutLeft' => __('BounceOutLeft', WDS()->prefix),
      'bounceOutRight' => __('BounceOutRight', WDS()->prefix),
      'bounceOutUp' => __('BounceOutUp', WDS()->prefix),

      'fadeOut' => __('FadeOut', WDS()->prefix),
      'fadeOutDown' => __('FadeOutDown', WDS()->prefix),
      'fadeOutDownBig' => __('FadeOutDownBig', WDS()->prefix),
      'fadeOutLeft' => __('FadeOutLeft', WDS()->prefix),
      'fadeOutLeftBig' => __('FadeOutLeftBig', WDS()->prefix),
      'fadeOutRight' => __('FadeOutRight', WDS()->prefix),
      'fadeOutRightBig' => __('FadeOutRightBig', WDS()->prefix),
      'fadeOutUp' => __('FadeOutUp', WDS()->prefix),
      'fadeOutUpBig' => __('FadeOutUpBig', WDS()->prefix),

      'flip' => __('Flip', WDS()->prefix),
      'flipOutX' => __('FlipOutX', WDS()->prefix),
      'flipOutY' => __('FlipOutY', WDS()->prefix),

      'rotateOut' => __('RotateOut', WDS()->prefix),
      'rotateOutDownLeft' => __('RotateOutDownLeft', WDS()->prefix),
      'rotateOutDownRight' => __('RotateOutDownRight', WDS()->prefix),
      'rotateOutUpLeft' => __('RotateOutUpLeft', WDS()->prefix),
      'rotateOutUpRight' => __('RotateOutUpRight', WDS()->prefix),

      'zoomOut' => __('ZoomOut', WDS()->prefix),
      'zoomOutDown' => __('ZoomOutDown', WDS()->prefix),
      'zoomOutLeft' => __('ZoomOutLeft', WDS()->prefix),
      'zoomOutRight' => __('ZoomOutRight', WDS()->prefix),
      'zoomOutUp' => __('ZoomOutUp', WDS()->prefix),
    );
    $values['hotp_text_positions'] = array(
      'top' => __('Top', WDS()->prefix),
      'left' => __('Left', WDS()->prefix),
      'bottom' => __('Bottom', WDS()->prefix),
      'right' => __('Right', WDS()->prefix),
    );
    $values['slider_callbacks'] = array(
      'onSliderI' => __('On slider Init', WDS()->prefix),
      'onSliderCS' => __('On slide change start', WDS()->prefix),
      'onSliderCE' => __('On slide change end', WDS()->prefix),
      'onSliderPlay' => __('On slide play', WDS()->prefix),
      'onSliderPause' => __('On slide pause', WDS()->prefix),
      'onSliderHover' => __('On slide hover', WDS()->prefix),
      'onSliderBlur' => __('On slide blur', WDS()->prefix),
      'onSliderR' => __('On slider resize', WDS()->prefix),
      'onSwipeS' => __('On swipe start', WDS()->prefix),
    );
    $values['layer_callbacks'] = array(
      '' => __('Select action', WDS()->prefix),
      'SlidePlay' => __('Play', WDS()->prefix),
      'SlidePause' => __('Pause', WDS()->prefix),
      'SlidePlayPause' => __('Play/Pause', WDS()->prefix),
      'SlideNext' => __('Next slide', WDS()->prefix),
      'SlidePrevious' => __('Previous slide', WDS()->prefix),
      'SlideLink' => __('Link to slide', WDS()->prefix),
      'PlayMusic' => __('Play music', WDS()->prefix),
    );
    $values['text_alignments'] = array(
      'left' => __('Left', WDS()->prefix),
      'center' => __('Center', WDS()->prefix),
      'right' => __('Right', WDS()->prefix)
    );
	$values['slider_fillmode_option'] = array(
		'fill' => __('Fill', WDS()->prefix),
		'fit' => __('Fit', WDS()->prefix),
		'stretch' => __('Stretch', WDS()->prefix),
		'center' => __('Center', WDS()->prefix),
		'tile' => __('Tile', WDS()->prefix)
	);
    $values['built_in_watermark_fonts'] = array();
    foreach (scandir(path_join(WDS()->plugin_dir, 'fonts')) as $filename) {
      if (strpos($filename, '.') === 0) {
        continue;
      }
      else {
        $values['built_in_watermark_fonts'][] = $filename;
      }
    }

    return $values;
  }

   /**
   * Get slider by id.
   *
   * @param  int      $id
   * @return object   $slider
   */
	public static function get_slider_by_id( $id ) {
		require_once WDS()->plugin_dir . "/frontend/models/WDSModelSlider.php";
		$model = new WDSModelSlider();
		$slider = $model->get_slider_row_data( $id );
		return $slider;
	}

	/**
	* Get slides by slider id.
	*
	* @param  int      $slider_id
	* @param  string   $order
	* @return object   $slides
    */
	public static function get_slides_by_slider_id( $id , $order) {
		require_once WDS()->plugin_dir . "/frontend/models/WDSModelSlider.php";
		$model = new WDSModelSlider();
		$slider = $model->get_slide_rows_data( $id , $order );
		return $slider;
	}

	/**
	* Get layers by slider id and slide_ids.
	*
	* @param  int      $slider_id
	* @param  array    $slide_ids
	* @return object   $layers
    */
	public static function get_layers_by_slider_id_slide_ids( $slider_id, $slide_ids ) {
		require_once WDS()->plugin_dir . "/frontend/models/WDSModelSlider.php";
		$model = new WDSModelSlider();
		$layers = $model->get_layers_by_slider_id_slide_ids( $slider_id, $slide_ids );
		return $layers;
	}

   /**
   * Create frontend js file.
   *
   * @param int    $slider_id
   * @return bool   $bool
   */
	public static function create_frontend_js_file( $slider_id ) {
		global $wpdb;
		$wp_upload_dir = wp_upload_dir();
		if ( !is_dir($wp_upload_dir['basedir'] . '/slider-wd-scripts')) {
			mkdir($wp_upload_dir['basedir'] . '/slider-wd-scripts', 0755);
		}
		$error = false;
		$bool  = false;
		$slider = array();
		$slides = array();
		$layers_rows = array();
		// Get slider.
		$slider = WDW_S_Library::get_slider_by_id( $slider_id );
		if ( !empty($slider) ) {
			// Get slider slides.
			$order_dir = isset($slider->order_dir) ? $slider->order_dir : 'asc';
			$slides = WDW_S_Library::get_slides_by_slider_id( $slider_id, $order_dir );
			if ( !empty($slides) ) {
				foreach ( $slides as $slide ) {
					$slide_ids[] = $slide->id;
				}
				// Get slider slides layers.
				$layers_rows = WDW_S_Library::get_layers_by_slider_id_slide_ids( $slider_id, $slide_ids );
			}
		}
		$content = WDW_S_Library::create_js( $slider, $slides, $layers_rows );
		$file = $wp_upload_dir['basedir'] . '/slider-wd-scripts/script-' . $slider_id . '.js';
		$file_put = file_put_contents($file, $content);
		if ( is_file($file) ) {
			$bool = true;
		}
		return $bool;
	}

  /**
   *
   * @param array   $slider
   * @param array      $slides
   * @param array      $layers_rows
   * @param int    $wds
   * @return string $js_content
   *
   */
  public static function create_js( $slider, $slides, $layers_rows, $wds, $current_key ) {
    $image_right_click = $slider->image_right_click;
    $callback_items = isset($slider->javascript) ? json_decode(htmlspecialchars_decode($slider->javascript, ENT_COMPAT | ENT_QUOTES), TRUE) : array();
    $bull_hover = isset($slider->bull_hover) ? $slider->bull_hover : 1;
    $bull_position = $slider->bull_position;
    $bull_style_active = str_replace('-o', '', $slider->bull_style);
    $bull_style_deactive = $slider->bull_style;

    $image_width = $slider->width;
    $image_height = $slider->height;
    $slides_count = count($slides);
    $slideshow_effect = $slider->effect == 'zoomFade' ? 'fade' : $slider->effect;
    $slideshow_interval = $slider->time_intervval;

    $enable_slideshow_shuffle = $slider->shuffle;
    $enable_prev_next_butt = $slider->prev_next_butt;
    $mouse_swipe_nav = isset($slider->mouse_swipe_nav) ? $slider->mouse_swipe_nav : 0;
    $touch_swipe_nav = isset($slider->touch_swipe_nav) ? $slider->touch_swipe_nav : 1;
    $mouse_wheel_nav = isset($slider->mouse_wheel_nav) ? $slider->mouse_wheel_nav : 0;
    $keyboard_nav = isset($slider->keyboard_nav) ? $slider->keyboard_nav : 0;
    $enable_play_paus_butt = $slider->play_paus_butt;

    if (!$enable_prev_next_butt && !$enable_play_paus_butt) {
      $enable_slideshow_autoplay = 1;
    }
    else {
      $enable_slideshow_autoplay = $slider->autoplay;
    }

    $autoplay = 0;
    if ($enable_slideshow_autoplay && !$enable_play_paus_butt && ($slides_count > 1)) {
      $autoplay = 1;
    }

    $navigation = 4000;
    if ($slider->navigation == 'always') {
      $navigation = 0;
    }

    $enable_slideshow_music = $slider->music;
    $slideshow_music_url = $slider->music_url;
    $filmstrip_direction = ($slider->film_pos == 'right' || $slider->film_pos == 'left') ? 'vertical' : 'horizontal';
    $filmstrip_position = $slider->film_pos;
    $filmstrip_thumb_margin_hor = $slider->film_tmb_margin;
    if ($filmstrip_position != 'none') {
      if ($filmstrip_direction == 'horizontal') {
        $filmstrip_width = $slider->film_thumb_width;
        $filmstrip_height = $slider->film_thumb_height;
      }
      else {
        $filmstrip_width = $slider->film_thumb_width;
        $filmstrip_height = $slider->film_thumb_height;
      }
    }
    else {
      $filmstrip_width = 0;
      $filmstrip_height = 0;
    }
    $left_or_top = 'left';
    $width_or_height = 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }

    $slide_ids = array();
    foreach ($slides as $slide) {
      $slide_ids[] = $slide->id;
    }

    if ($slider->start_slide_num == 0) {
      $current_image_id = $slide_ids[array_rand($slide_ids)];
      $start_slide_num = array_search($current_image_id, $slide_ids);
    }
    else {
      if ($slider->start_slide_num > 0 && $slider->start_slide_num <= $slides_count) {
        $start_slide_num = $slider->start_slide_num - 1;
      }
      else {
        $start_slide_num = 0;
      }
    }
    $parallax_effect = $slider->parallax_effect;
    $carousel = isset($slider->carousel) ? $slider->carousel : FALSE;
    $auto_height = isset($slider->auto_height) ? $slider->auto_height : FALSE;
    $carousel_image_parameters = $slider->carousel_image_parameters;
    $carousel_image_counts = $slider->carousel_image_counts;
    $carousel_fit_containerWidth = $slider->carousel_fit_containerWidth;
    $carousel_width = $slider->carousel_width;
    $preload_images = $slider->carousel ? FALSE : $slider->preload_images;

    $smart_crop = isset($slider->smart_crop) ? $slider->smart_crop : 0;
    $crop_image_position = isset($slider->crop_image_position) ? $slider->crop_image_position : 'center center';
    $carousel_degree = isset($slider->carousel_degree) ? $slider->carousel_degree : 0;
    $carousel_grayscale = isset($slider->carousel_grayscale) ? $slider->carousel_grayscale : 0;
    $carousel_transparency = isset($slider->carousel_transparency) ? $slider->carousel_transparency : 0;
    $slider_loop = isset($slider->slider_loop) ? $slider->slider_loop : 1;
    $twoway_slideshow = isset($slider->twoway_slideshow) ? (int) $slider->twoway_slideshow : 0;
    $fixed_bg = (isset($slider->fixed_bg) && !$carousel) ? $slider->fixed_bg : 0;
    $current_image_url = '';
    ob_start();
    ?>
    var wds_glb_margin_<?php echo $wds; ?> = parseInt(<?php echo $slider->glb_margin; ?>);
    var wds_data_<?php echo $wds; ?> = [];
    var wds_event_stack_<?php echo $wds; ?> = [];
    var wds_clear_layers_effects_in_<?php echo $wds; ?> = [];
    var wds_clear_layers_effects_out_<?php echo $wds; ?> = [];
    var wds_clear_layers_effects_out_before_change_<?php echo $wds; ?> = [];
    <?php if ( $slider->layer_out_next ) { ?>
      var wds_duration_for_change_<?php echo $wds; ?> = 500;
      var wds_duration_for_clear_effects_<?php echo $wds; ?> = 530;
    <?php } else { ?>
      var wds_duration_for_change_<?php echo $wds; ?> = 0;
      var wds_duration_for_clear_effects_<?php echo $wds; ?> = 0;
    <?php }
    foreach ($slides as $key => $slide_row) {
      ?>
      wds_clear_layers_effects_in_<?php echo $wds; ?>["<?php echo $key; ?>"] = [];
      wds_clear_layers_effects_out_<?php echo $wds; ?>["<?php echo $key; ?>"] = [];
      wds_clear_layers_effects_out_before_change_<?php echo $wds; ?>["<?php echo $key; ?>"] = [];
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"] = [];
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["id"] = "<?php echo $slide_row->id; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["image_url"] = "<?php echo addslashes(htmlspecialchars_decode($slide_row->image_url, ENT_QUOTES)); ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["thumb_url"] = "<?php echo addslashes(htmlspecialchars_decode($slide_row->thumb_url, ENT_QUOTES)); ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["is_video"] = "<?php echo $slide_row->type; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["slide_layers_count"] = 0;
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["target_attr_slide"] = "<?php echo $slide_row->target_attr_slide; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["link"] = "<?php echo $slide_row->link; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["bull_position"] = "<?php echo $bull_position; ?>";

      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["width"] = "<?php echo $slide_row->att_width; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["height"] = "<?php echo $slide_row->att_height; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["fillmode"] = "<?php echo $slide_row->fillmode; ?>";
      wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["image_thumb_url"] = "<?php echo is_numeric($slide_row->thumb_url) ? (wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) : WDS()->plugin_url . '/images/no-video.png' ): htmlspecialchars_decode($slide_row->thumb_url,ENT_QUOTES) ?>";
      <?php
      if (isset($layers_rows[$slide_row->id]) && !empty($layers_rows[$slide_row->id])) {
        foreach ($layers_rows[$slide_row->id] as $layer_key => $layer) {
          if (!isset($layer->align_layer)) {
            $layer->align_layer = 0;
          }
          if (!isset($layer->infinite_in)) {
            $layer->infinite_in = 1;
          }
          if (!isset($layer->infinite_out)) {
            $layer->infinite_out = 1;
          }
          if (!isset($layer->min_size)) {
            $layer->min_size = 11;
          }
          ?>
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_id"] = "<?php echo $layer->id; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_layer_effect_in"] = "<?php echo $layer->layer_effect_in; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_duration_eff_in"] = "<?php echo $layer->duration_eff_in; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_layer_effect_out"] = "<?php echo $layer->layer_effect_out; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_duration_eff_out"] = "<?php echo $layer->duration_eff_out; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_social_button"] = "<?php echo $layer->social_button; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_start"] = "<?php echo $layer->start; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_end"] = "<?php echo $layer->end; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_type"] = "<?php echo $layer->type; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_video_autoplay"] = "<?php echo $layer->image_scale; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_controls"] = "<?php echo $layer->target_attr_layer; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_attr_width"] = "<?php echo $layer->attr_width; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_attr_height"] = "<?php echo $layer->attr_height; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_align_layer"] = "<?php echo $layer->align_layer; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["slide_layers_count"] ++;
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_infinite_in"] = "<?php echo $layer->infinite_in; ?>";
          wds_data_<?php echo $wds; ?>["<?php echo $key; ?>"]["layer_<?php echo $layer_key; ?>_infinite_out"] = "<?php echo $layer->infinite_out; ?>";
          <?php
        }
      }
    }
    ?>
    var wds_global_btn_<?php echo $wds; ?> = "right";
    var wds_trans_in_progress_<?php echo $wds; ?> = false;
    var video_is_playing_<?php echo $wds; ?> = false;
    var iframe_message_sent_<?php echo $wds; ?> = 0;
    var iframe_message_received_<?php echo $wds; ?> = 0;
    var wds_transition_duration_<?php echo $wds; ?> = <?php echo $slider->effect_duration; ?>;
    var youtube_iframes_<?php echo $wds; ?> = [];
    var youtube_iframes_ids_<?php echo $wds; ?> = [];
    if (<?php echo $slideshow_interval; ?> < 4) {
    if (<?php echo $slideshow_interval; ?> != 0) {
    wds_transition_duration_<?php echo $wds; ?> = (<?php echo $slideshow_interval; ?> * 1000) / 4;
    }
    }
    var wds_playInterval_<?php echo $wds; ?>;
    var progress = 0;
    var bottom_right_deggree_<?php echo $wds; ?>;
    var bottom_left_deggree_<?php echo $wds; ?>;
    var top_left_deggree_<?php echo $wds; ?>;
    var curent_time_deggree_<?php echo $wds; ?> = 0;
    var circle_timer_animate_<?php echo $wds; ?>;

    /* Stop autoplay.*/
    window.clearInterval(wds_playInterval_<?php echo $wds; ?>);
    var wds_current_key_<?php echo $wds; ?> = '<?php echo (isset($current_key) ? $current_key : ''); ?>';
    var wds_current_filmstrip_pos_<?php echo $wds; ?> = wds_current_key_<?php echo $wds; ?> * jQuery(".wds_slideshow_filmstrip_thumbnails_<?php echo $wds; ?>").<?php echo $width_or_height; ?>() / <?php echo $slides_count; ?>;
    var callback_items = new Array();
    var wds_param = {
        wds : <?php echo $wds; ?>,
        carousel : <?php echo $carousel; ?>,
        autoplay : <?php echo $autoplay; ?>,
        youtube_iframes_ids : youtube_iframes_ids_<?php echo $wds; ?>,
        youtube_iframes : youtube_iframes_<?php echo $wds; ?>,
        wds_data : wds_data_<?php echo $wds; ?>,
        wds_trans_in_progress : wds_trans_in_progress_<?php echo $wds; ?>,
        wds_event_stack : wds_event_stack_<?php echo $wds; ?>,
        wds_current_key : wds_current_key_<?php echo $wds; ?>,
        enable_slideshow_autoplay : <?php echo $enable_slideshow_autoplay; ?>,
        twoway_slideshow : <?php echo $twoway_slideshow; ?>,
        wds_global_btn_wds : wds_global_btn_<?php echo $wds; ?>,
        wds_playInterval : wds_playInterval_<?php echo $wds; ?>,
        preload_images : '<?php echo $preload_images; ?>',
        wds_clear_layers_effects_out_before_change : wds_clear_layers_effects_out_before_change_<?php echo $wds; ?>,
        wds_clear_layers_effects_out : wds_clear_layers_effects_out_<?php echo $wds; ?>,
        layer_out_next :  <?php echo $slider->layer_out_next; ?>,
        timer_bar_type : '<?php echo $slider->timer_bar_type; ?>',
        bull_butt_img_or_not : '<?php echo $slider->bull_butt_img_or_not; ?>',
        wds_transition_duration : wds_transition_duration_<?php echo $wds; ?>,
        bull_style_active : '<?php echo $bull_style_active; ?>',
        bull_style_deactive : '<?php echo $bull_style_deactive; ?>',
        width_or_height : '<?php echo $width_or_height; ?>',
        circle_timer_animate : circle_timer_animate_<?php echo $wds; ?>,
        filmstrip_position : '<?php echo $filmstrip_position; ?>',
        slides_count : <?php echo $slides_count; ?>,
        bull_position : '<?php echo $bull_position; ?>',
        parallax_effect : '<?php echo $parallax_effect; ?>',
        wds_clear_layers_effects_in : wds_clear_layers_effects_in_<?php echo $wds; ?>,
        slider_effect : '<?php echo $slider->effect; ?>',
        fixed_bg : '<?php echo $fixed_bg; ?>',
        smart_crop : '<?php echo $smart_crop; ?>',
        crop_image_position : '<?php echo $crop_image_position; ?>',
        left_or_top : '<?php echo $left_or_top; ?>',
        outerWidth_or_outerHeight : '<?php echo $outerWidth_or_outerHeight; ?>',
        slideshow_interval : '<?php echo $slideshow_interval; ?>',
        slider_loop : '<?php echo $slider_loop; ?>',
        wds_play_pause_state : 0,
        curent_time_deggree : curent_time_deggree_<?php echo $wds; ?>,
        enable_slideshow_music : <?php echo $enable_slideshow_music; ?>,
        slideshow_music_url : '<?php echo $slideshow_music_url; ?>',
        wds_duration_for_change : wds_duration_for_change_<?php echo $wds; ?>,
        enable_slideshow_shuffle : <?php echo $enable_slideshow_shuffle; ?>,
        wds_slideshow_effect : 'wds_<?php echo $slideshow_effect; ?>',
        glb_border_radius : '<?php echo $slider->glb_border_radius; ?>',
        wds_current_filmstrip_pos : wds_current_filmstrip_pos_<?php echo $wds; ?>,
        callback_items : <?php echo json_encode($callback_items); ?>,
        full_width_for_mobile : <?php echo $slider->full_width_for_mobile ?>,
        full_width : <?php echo $slider->full_width ?>,
        wds_glb_margin : 'wds_glb_margin_<?php echo $wds; ?>',
        glb_margin : <?php echo $slider->glb_margin ?>,
        image_width : <?php echo $image_width ?>,
        image_height : <?php echo $image_height ?>,
        filmstrip_direction : '<?php echo $filmstrip_direction ?>',
        filmstrip_width : <?php echo $filmstrip_width ?>,
        filmstrip_height : <?php echo $filmstrip_height ?>,
        auto_height : <?php echo $auto_height ?>,
        carousel_width : <?php echo $carousel_width ?>,
        stop_animation : <?php echo $slider->stop_animation ?>,
        filmstrip_thumb_margin_hor : <?php echo $filmstrip_thumb_margin_hor ?>,
        image_right_click : <?php echo $image_right_click ?>,
        iframe_message_received : 'iframe_message_received_<?php echo $wds; ?>',
        video_is_playing : video_is_playing_<?php echo $wds; ?>,
        mouse_wheel_nav : <?php echo $mouse_wheel_nav; ?>,
        mouse_swipe_nav : <?php echo $mouse_swipe_nav; ?>,
        touch_swipe_nav : <?php echo $touch_swipe_nav; ?>,
        keyboard_nav : <?php echo $keyboard_nav; ?>,
        start_slide_num : <?php echo $start_slide_num; ?>,
        start_slide_num_car : <?php echo $slider->start_slide_num; ?>,
        wds_duration_for_clear_effects : wds_duration_for_clear_effects_<?php echo $wds; ?>,
        carousel_image_counts : <?php echo $carousel_image_counts; ?>,
        carousel_image_parameters : '<?php echo $carousel_image_parameters; ?>',
        carousel_fit_containerWidth : <?php echo $carousel_fit_containerWidth; ?>,
        carousel_degree : <?php echo $carousel_degree; ?>,
        carousel_grayscale : <?php echo $carousel_grayscale; ?>,
        carousel_transparency : <?php echo $carousel_transparency; ?>,
        navigation : <?php echo $navigation; ?>,
        bull_hover : <?php echo $bull_hover; ?>,
        current_image_url : '<?php echo $current_image_url; ?>',
    };
    if ( typeof wds_params == "undefined" ) {
      var wds_params = [];
    }
    wds_params[<?php echo $wds; ?>] = wds_param;
    <?php
    $js_content = ob_get_clean();

    return $js_content;
  }


  /**
   * @param $id
   * @param $slider_row
   * @param $slide_rows
   * @param $wds
   * @return string
   */
  public static function create_css( $id, $slider_row, $slide_rows, $layers_rows, $wds ) {
    $wds_global_options = get_option("wds_global_options", 0);
    $global_options = json_decode($wds_global_options);
    $loading_gif = isset($global_options->loading_gif) ? $global_options->loading_gif : 0;

    $resolutions = array(320, 480, 640, 768, 800, 1024, 1366, 1824, 3000);
    $bull_hover = isset($slider_row->bull_hover) ? $slider_row->bull_hover : 1;
    $bull_position = $slider_row->bull_position;

    $image_width = $slider_row->width;
    $image_height = $slider_row->height;

    $slides_count = count($slide_rows);

    $circle_timer_size = (2 * $slider_row->timer_bar_size - 2) * 2;

    $enable_slideshow_shuffle = $slider_row->shuffle;
    $mouse_swipe_nav = isset($slider_row->mouse_swipe_nav) ? $slider_row->mouse_swipe_nav : 0;
    $thumb_size = isset($slider_row->thumb_size) ? floatval($slider_row->thumb_size) : '0.3';
    if ($slider_row->navigation == 'always') {
      $navigation = 0;
      $pp_btn_opacity = 1;
    }
    else {
      $navigation = 4000;
      $pp_btn_opacity = 0;
    }
    $filmstrip_direction = ($slider_row->film_pos == 'right' || $slider_row->film_pos == 'left') ? 'vertical' : 'horizontal';
    $filmstrip_position = $slider_row->film_pos;
    if ($filmstrip_position != 'none') {
      if ($filmstrip_direction == 'horizontal') {
        $filmstrip_width = $slider_row->film_thumb_width;
        $filmstrip_height = $slider_row->film_thumb_height;
        $filmstrip_width_in_percent = 100 / count($slide_rows);
        $filmstrip_height_in_percent = 100 * $filmstrip_height / ($image_height + $filmstrip_height);
        $filmstrip_container_width_in_percent = 100 * $filmstrip_width / $image_width * count($slide_rows);
        $filmstrip_container_height_in_percent = 100;
      }
      else {
        $filmstrip_width = $slider_row->film_thumb_width;
        $filmstrip_height = $slider_row->film_thumb_height;
        $filmstrip_width_in_percent = 100 * $filmstrip_width / ($image_width + $filmstrip_width);
        $filmstrip_height_in_percent = 100 / count($slide_rows);
        $filmstrip_container_width_in_percent = 100;
        $filmstrip_container_height_in_percent = 100 * $filmstrip_height / $image_height * count($slide_rows);
      }
    }
    else {
      $filmstrip_width_in_percent = 0;
      $filmstrip_height_in_percent = 0;
      $filmstrip_container_width_in_percent = 0;
      $filmstrip_container_height_in_percent = 0;
    }
    $left_or_top = 'left';
    $width_or_height = 'width';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
    }

    $carousel = isset($slider_row->carousel) ? $slider_row->carousel : FALSE;
    $smart_crop = isset($slider_row->smart_crop) ? $slider_row->smart_crop : 0;
    $crop_image_position = isset($slider_row->crop_image_position) ? $slider_row->crop_image_position : 'center center';
    $hide_on_mobile = (isset($slider_row->hide_on_mobile) ? $slider_row->hide_on_mobile : 0);
    $full_width_for_mobile = isset($slider_row->full_width_for_mobile) ? (int) $slider_row->full_width_for_mobile : 0;
    $film_small_screen = (isset($slider_row->film_small_screen) ? $slider_row->film_small_screen : 0);
      ob_start();
      if ($film_small_screen) {
      ?>
      @media screen and (max-width: <?php echo intval($film_small_screen-1); ?>px){
      #wds_container1_<?php echo $wds; ?> .wds_slideshow_filmstrip_container {
        display: none!important;
      }
      #wds_container1_<?php echo $wds; ?> .wds_slideshow_image_container {
        width: 100%!important;
        height: 100%!important;
        left: 0px!important;
        top: 0px!important;
      }
      }
      <?php
      }
  ?>
      .wds_slider_<?php echo $wds; ?> video::-webkit-media-controls-panel {
        display: none!important;
        -webkit-appearance: none;
      }
      .wds_slider_<?php echo $wds; ?> video::--webkit-media-controls-play-button {
        display: none!important;
        -webkit-appearance: none;
      }
      .wds_slider_<?php echo $wds; ?> video::-webkit-media-controls-start-playback-button {
        display: none!important;
        -webkit-appearance: none;
      }
      .wds_bigplay_<?php echo $wds; ?>,
      .wds_slideshow_image_<?php echo $wds; ?>,
      .wds_slideshow_video_<?php echo $wds; ?> {
        display: block;
      }
      .wds_bulframe_<?php echo $wds; ?> {
        display: none;
        background-image: url('');
        margin: 0px;
        position: absolute;
        z-index: 3;
        -webkit-transition: left 1s, right 1s;
        transition: left 1s, right 1s;
        width: <?php echo floatval(100 * $thumb_size); ?>%;
        height: <?php echo floatval(100 * $thumb_size) ?>%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> {
        margin: <?php echo $slider_row->glb_margin; ?>px <?php echo ($slider_row->full_width == '1') ? 0 : ''; ?>;
        text-align: <?php echo $slider_row->align; ?>;
        visibility: hidden;
      <?php echo ($slider_row->full_width == '1') ? 'position: relative; z-index: 1;' : ''; ?>
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_wrap_<?php echo $wds; ?>,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_wrap_<?php echo $wds; ?> * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_wrap_<?php echo $wds; ?> {
        background-color: <?php echo WDW_S_Library::spider_hex2rgba($slider_row->background_color, (100 - $slider_row->background_transparent) / 100); ?>;
        border-width: <?php echo $slider_row->glb_border_width; ?>px;
        border-style: <?php echo $slider_row->glb_border_style; ?>;
        border-color: #<?php echo $slider_row->glb_border_color; ?>;
        border-radius: <?php echo $slider_row->glb_border_radius; ?>;
        border-collapse: collapse;
        display: inline-block;
        position: <?php echo ($slider_row->full_width == '1') ? 'absolute' : 'relative'; ?>;
        text-align: center;
        width: 100%;
      <?php
    if (!$carousel && $slider_row->full_width != '2') {
        ?>
        max-width: <?php echo $image_width; ?>px;
      <?php
    }
    ?>
        box-shadow: <?php echo $slider_row->glb_box_shadow; ?>;
        overflow: hidden;
        z-index: 0;
      }
	  
	  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_<?php echo $wds; ?> {
        width: 100%;
		height: 100%;
        float: none !important;
        padding: 0 !important;
        margin: 0 !important;
        vertical-align: middle;
		}
	
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_<?php echo $wds; ?> video {
        padding: 0 !important;
        margin: 0 !important;
        vertical-align: middle;
        background-position: center center;
        background-repeat: no-repeat;
      }
		#wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_container_<?php echo $wds; ?> {
        display: /*table*/block;
        position: absolute;
        text-align: center;
        vertical-align: middle;
        <?php if ($filmstrip_position != 'none') {
          echo $filmstrip_position .':'. ($filmstrip_direction == 'horizontal' ? $filmstrip_height_in_percent : $filmstrip_width_in_percent ) .'%;';
		} ?>
        width: <?php echo 100 - ($filmstrip_direction == 'vertical' ? $filmstrip_width_in_percent : 0); ?>%;
        height: <?php echo 100 - ($filmstrip_direction == 'horizontal' ? $filmstrip_height_in_percent : 0); ?>%;
      }
      <?php
      foreach ($resolutions as $key => $resolution) {
        if ($key) {
          $prev_resolution = $resolutions[$key - 1] + 1;
        }
        else {
          $prev_resolution = 0;
        }

        $media_slide_height = ($image_width > $resolution) ? ($image_height * $resolution) / $image_width : $image_height;
        $media_bull_size = ((int) ($resolution / 26) > $slider_row->bull_size) ? $slider_row->bull_size : (int) ($resolution / 26);
        $media_bull_margin = ($slider_row->bull_margin > 2 && $resolution < 481) ? 2 : $slider_row->bull_margin;
        $media_bull_size_cont = $media_bull_size + $media_bull_margin * ($slider_row->bull_butt_img_or_not == 'text' ? 4 : 2);
        $media_pp_butt_size = ((int) ($resolution / 16) > $slider_row->pp_butt_size) ? $slider_row->pp_butt_size : (int) ($resolution / 16);
        $media_rl_butt_size = ((int) ($resolution / 16) > $slider_row->rl_butt_size) ? $slider_row->rl_butt_size : (int) ($resolution / 16);
        ?>
      @media only screen and (min-width: <?php echo $prev_resolution; ?>px) and (max-width: <?php echo $resolution; ?>px) {
        .wds_bigplay_<?php echo $wds; ?>,
        .wds_bigplay_layer {
          position: absolute;
          width: <?php echo $media_pp_butt_size; ?>px;
          height: <?php echo $media_pp_butt_size; ?>px;
          background-image: url('<?php echo WDS()->plugin_url ?>/images/button/button3/2/1.png');
          background-position: center center;
          background-repeat: no-repeat;
          background-size: cover;
          transition: background-image 0.2s ease-out;
          -ms-transition: background-image 0.2s ease-out;
          -moz-transition: background-image 0.2s ease-out;
          -webkit-transition: background-image 0.2s ease-out;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          margin: auto
        }
        .wds_bigplay_<?php echo $wds; ?>:hover,
        .wds_bigplay_layer:hover {
          background: url('<?php echo WDS()->plugin_url ?>/images/button/button3/2/2.png') no-repeat;
          width: <?php echo $media_pp_butt_size; ?>px;
          height: <?php echo $media_pp_butt_size; ?>px;
          background-position: center center;
          background-repeat: no-repeat;
          background-size: cover;
        }
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_thumbnails_<?php echo $wds; ?> {
          height: <?php echo $media_bull_size_cont; ?>px;
          width: <?php echo $media_bull_size_cont * $slides_count; ?>px;
        }
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_<?php echo $wds; ?> {
          font-size: <?php echo $media_bull_size; ?>px;
          margin: <?php echo $media_bull_margin; ?>px;
        <?php
        if ($slider_row->bull_butt_img_or_not != 'text') {
          ?>
          width: <?php echo $media_bull_size; ?>px;
          height: <?php echo $media_bull_size; ?>px;
        <?php
      }
      else {
        ?>
          padding: <?php echo $media_bull_margin; ?>px;
          height: <?php echo $media_bull_size + 2 * $media_bull_margin; ?>px;
        <?php
      }
      ?>
        }
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_pp_btn_cont {
          font-size: <?php echo $media_pp_butt_size; ?>px;
          height: <?php echo $media_pp_butt_size; ?>px;
          width: <?php echo $media_pp_butt_size; ?>px;
        }
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left_btn_cont,
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right_btn_cont {
          height: <?php echo $media_rl_butt_size; ?>px;
          font-size: <?php echo $media_rl_butt_size; ?>px;
          width: <?php echo $media_rl_butt_size; ?>px;
        }
      }
      <?php
    }
    ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_video_<?php echo $wds; ?> {
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
        height: 100%;
        width: 100%;
        vertical-align: middle;
        display: inline-block;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?> {
        color: #<?php echo $slider_row->butts_color; ?>;
        cursor: pointer;
        position: relative;
        z-index: 13;
        width: inherit;
        height: inherit;
        font-size: inherit;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>:hover {
        color: #<?php echo $slider_row->hover_color; ?>;
        cursor: pointer;
      }
      <?php
      if ($slider_row->play_paus_butt_img_or_not != 'style') {
        ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_play_pause_<?php echo $wds; ?>.fa-pause:before,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_play_pause_<?php echo $wds; ?>.fa-play:before {
        content: "";
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-play {
        background-image: url('<?php echo addslashes(htmlspecialchars_decode ($slider_row->play_butt_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-play:before {
        content: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->play_butt_hov_url, ENT_QUOTES)); ?>');
        width: 0;
        height: 0;
        visibility: hidden;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-play:hover {
        background-image: url('<?php echo addslashes(htmlspecialchars_decode ($slider_row->play_butt_hov_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-pause{
        background-image: url('<?php echo addslashes(htmlspecialchars_decode ($slider_row->paus_butt_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-pause:before {
        content: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->paus_butt_hov_url, ENT_QUOTES)); ?>');
        width: 0;
        height: 0;
        visibility: hidden;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?>.fa-pause:hover {
        background-image: url('<?php echo addslashes(htmlspecialchars_decode ($slider_row->paus_butt_hov_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      }
      <?php
    }
    ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?>,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?> {
        background-color: <?php echo WDW_S_Library::spider_hex2rgba($slider_row->nav_bg_color, (100 - $slider_row->butts_transparent) / 100); ?>;
        border-radius: <?php echo $slider_row->nav_border_radius; ?>;
        border: <?php echo $slider_row->nav_border_width; ?>px <?php echo $slider_row->nav_border_style; ?> #<?php echo $slider_row->nav_border_color; ?>;
        border-collapse: separate;
        color: #<?php echo $slider_row->butts_color; ?>;
        left: 0;
        top: 0;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        cursor: pointer;
        line-height: 0;
        width: inherit;
        height: inherit;
        font-size: inherit;
        position: absolute;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?> {
        left: -<?php echo $navigation; ?>px;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?> {
        left: <?php echo $navigation; ?>px;
      }
      <?php
      if ($slider_row->rl_butt_img_or_not != 'style') {
        ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?> {
        left: -<?php echo $navigation; ?>px;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->left_butt_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?>:before {
        content: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->left_butt_hov_url, ENT_QUOTES)); ?>');
        width: 0;
        height: 0;
        visibility: hidden;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?>:hover {
        left: -<?php echo $navigation; ?>px;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->left_butt_hov_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?> {
        left: <?php echo $navigation; ?>px;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->right_butt_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?>:before {
        content: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->right_butt_hov_url, ENT_QUOTES)); ?>');
        width: 0;
        height: 0;
        visibility: hidden;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?>:hover {
        left: <?php echo $navigation; ?>px;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->right_butt_hov_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      }
      <?php
    }
    ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_slideshow_play_pause_<?php echo $wds; ?> {
        opacity: <?php echo $pp_btn_opacity; ?>;
        filter: "Alpha(opacity=<?php echo $pp_btn_opacity * 100; ?>)";
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_left-ico_<?php echo $wds; ?>:hover,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_right-ico_<?php echo $wds; ?>:hover {
        color: #<?php echo $slider_row->hover_color; ?>;
        cursor: pointer;
      }

      /* Filmstrip*/
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_container_<?php echo $wds; ?> {
        background-color: #<?php echo $slider_row->film_bg_color; ?> !important;
        display: block;
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $filmstrip_height_in_percent . '%' : '100%'); ?>;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? '100%' : $filmstrip_width_in_percent . '%'); ?>;
        z-index: 10105;
      <?php echo $filmstrip_position; ?>: 0;
        overflow: hidden;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_<?php echo $wds; ?> {
        overflow: hidden;
        <?php if ($slider_row->film_pos == 'left') {
          ?>padding-right: <?php echo $slider_row->film_tmb_margin; ?>px;<?php
        }
        elseif ($slider_row->film_pos == 'right') {
          ?>padding-left: <?php echo $slider_row->film_tmb_margin; ?>px;<?php
        }
        elseif ($slider_row->film_pos == 'top') {
          ?>padding-bottom: <?php echo $slider_row->film_tmb_margin; ?>px;
          left: <?php echo ($filmstrip_container_width_in_percent < 100 ? ((100 - $filmstrip_container_width_in_percent) / 2) . '%' : '0');?>;<?php
        }
        elseif ($slider_row->film_pos == 'bottom') {
          ?>padding-top: <?php echo $slider_row->film_tmb_margin; ?>px;
          left: <?php echo ($filmstrip_container_width_in_percent < 100 ? ((100 - $filmstrip_container_width_in_percent) / 2) . '%' : '0');?>;<?php
        }
        ?>
        position: absolute;
        height: <?php echo ($filmstrip_direction == 'horizontal' ? '100%' : $filmstrip_container_height_in_percent . '%'); ?>;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $filmstrip_container_width_in_percent . '%' : '100%'); ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_thumbnails_<?php echo $wds; ?> {
        height: 100%;
      <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: 100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_thumbnail_<?php echo $wds; ?> {
        position: relative;
        background: none;
        float: left;
        height: <?php echo $filmstrip_direction == 'horizontal' ? '100%' : $filmstrip_height_in_percent . '%'; ?>;
        padding: <?php echo $filmstrip_direction == 'horizontal' ? '0 0 0 ' . $slider_row->film_tmb_margin . 'px' : $slider_row->film_tmb_margin . 'px 0 0'; ?>;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $filmstrip_width_in_percent . '%' : '100%'); ?>;
        overflow: hidden;
      <?php
      if ($mouse_swipe_nav) {
      ?>
        cursor: -moz-grab;
        cursor: -webkit-grab;
        cursor: grab;
      <?php
      }
      else {
      ?>
        cursor: pointer;
      <?php
      }
      ?>
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_thumbnail_<?php echo $wds; ?> :active{
      <?php
      if ($mouse_swipe_nav) {
      ?>
        cursor: -moz-grab;
        cursor: -webkit-grab;
        cursor: grab;
      <?php
      }
      else {
      ?>
        cursor: inherit;
      <?php
      }
      ?>
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_filmstrip_thumbnail_0_<?php echo $wds; ?> {
      <?php echo $filmstrip_direction == 'horizontal' ? 'margin-left: 0' : 'margin-top: 0'; ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_thumb_active_<?php echo $wds; ?> div {
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $slider_row->film_act_border_width; ?>px <?php echo $slider_row->film_act_border_style; ?> #<?php echo $slider_row->film_act_border_color; ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_thumb_deactive_<?php echo $wds; ?> {
        opacity: <?php echo number_format((100 - $slider_row->film_dac_transparent) / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo 100 - $slider_row->film_dac_transparent; ?>);
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_thumbnail_img_<?php echo $wds; ?> {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
        padding: 0 !important;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        width: 100%;
        height: 100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_left_<?php echo $wds; ?>,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_right_<?php echo $wds; ?> {
        background-color: rgba(0, 0, 0, 0);
        cursor: pointer;
        display: table;
        vertical-align: middle;
      <?php echo $width_or_height; ?>: 20px;
        z-index: 10000;
        position: absolute;
      <?php echo ($filmstrip_direction == 'horizontal' ? 'height: 100%' : 'width: 100%') ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_left_<?php echo $wds; ?> {
      <?php echo $left_or_top; ?>: 0;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_right_<?php echo $wds; ?> {
      <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_left_<?php echo $wds; ?> i,
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_filmstrip_right_<?php echo $wds; ?> i {
        color: #fff;
        display: table-cell;
        font-size: 30px;
        vertical-align: middle;
        opacity: 0;
        filter: Alpha(opacity=0);
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_none_selectable_<?php echo $wds; ?> {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slide_container_<?php echo $wds; ?> {
        display: table-cell;
        margin: 0 auto;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
        overflow: hidden;
        cursor: <?php echo $mouse_swipe_nav ? '-moz-grab' : 'inherit'; ?>;
        cursor: <?php echo $mouse_swipe_nav ? '-webkit-grab' : 'inherit'; ?>;
        cursor: <?php echo $mouse_swipe_nav ? 'grab' : 'inherit'; ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slide_container_<?php echo $wds; ?>:active {
        cursor: <?php echo $mouse_swipe_nav ? '-moz-grabbing' : 'inherit'; ?>;
        cursor: <?php echo $mouse_swipe_nav ? '-webkit-grabbing' : 'inherit'; ?>;
        cursor: <?php echo $mouse_swipe_nav ? 'grabbing' : 'inherit'; ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slide_bg_<?php echo $wds; ?> {
        margin: 0 auto;
        width: /*inherit*/100%;
        height: /*inherit*/100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slider_<?php echo $wds; ?> {
        height: /*inherit*/100%;
        width: /*inherit*/100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_spun_<?php echo $wds; ?> {
        width: /*inherit*/100%;
        height: /*inherit*/100%;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        z-index: 2;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_second_spun_<?php echo $wds; ?> {
        width: /*inherit*/100%;
        height: /*inherit*/100%;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_grid_<?php echo $wds; ?> {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_gridlet_<?php echo $wds; ?> {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      /* Dots.*/
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_container_<?php echo $wds; ?> {
        opacity: <?php echo $bull_hover; ?>;
        filter: "Alpha(opacity=<?php echo $bull_hover * 100; ?>)";
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_container_<?php echo $wds; ?> {
        display: block;
        overflow: hidden;
        position: absolute;
        width: 100%;
      <?php echo $bull_position; ?>: 0;
        /*z-index: 17;*/
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_thumbnails_<?php echo $wds; ?> {
        left: 0px;
        font-size: 0;
        margin: 0 auto;
        position: relative;
        z-index: 999;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_<?php echo $wds; ?> {
        display: inline-block;
        position: relative;
        color: #<?php echo $slider_row->bull_color; ?>;
        cursor: pointer;
        z-index: 17;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_active_<?php echo $wds; ?> {
        color: #<?php echo $slider_row->bull_act_color; ?>;
        opacity: 1;
        filter: Alpha(opacity=100);
      <?php
      if ($slider_row->bull_butt_img_or_not != 'style' && $slider_row->bull_butt_img_or_not != 'text') {
        ?>
        display: inline-block;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->bullets_img_main_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      <?php
    }
    else if ($slider_row->bull_butt_img_or_not == 'text') {
      ?>
        background-color: #<?php echo $slider_row->bull_back_act_color; ?>;
        border-radius: <?php echo $slider_row->bull_radius; ?>;
      <?php
    }
    ?>
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_dots_deactive_<?php echo $wds; ?> {
      <?php
      if ($slider_row->bull_butt_img_or_not != 'style' && $slider_row->bull_butt_img_or_not != 'text') {
        ?>
        display: inline-block;
        background-image: url('<?php echo addslashes(htmlspecialchars_decode($slider_row->bullets_img_hov_url, ENT_QUOTES)); ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-in;
        -ms-transition: background-image 0.2s ease-in;
        -moz-transition: background-image 0.2s ease-in;
        -webkit-transition: background-image 0.2s ease-in;
      <?php
    }
    else if ($slider_row->bull_butt_img_or_not == 'text') {
      ?>
        background-color: #<?php echo $slider_row->bull_back_color; ?>;
        border-radius: <?php echo $slider_row->bull_radius; ?>;
      <?php
    }
    ?>
      }
      <?php
      if ($slider_row->timer_bar_type == 'top' || $slider_row->timer_bar_type == 'bottom') {
        ?>
      /* Line timer.*/
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_line_timer_container_<?php echo $wds; ?> {
        display: block;
        position: absolute;
        overflow: hidden;
      <?php echo $slider_row->timer_bar_type; ?>: 0;
        z-index: 16;
        width: 100%;
        height: <?php echo $slider_row->timer_bar_size; ?>px;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_line_timer_<?php echo $wds; ?> {
        z-index: 17;
        width: 0;
        height: <?php echo $slider_row->timer_bar_size; ?>px;
        background: #<?php echo $slider_row->timer_bar_color; ?>;
        opacity: <?php echo number_format((100 - $slider_row->timer_bar_transparent) / 100, 2, ".", ""); ?>;
        filter: alpha(opacity=<?php echo 100 - $slider_row->timer_bar_transparent; ?>);
      }
      <?php
    }
    elseif ($slider_row->timer_bar_type != 'none') {
      ?>
      /* Circle timer.*/
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_line_timer_container_<?php echo $wds; ?> {
        display: block;
        position: absolute;
        overflow: hidden;
      <?php echo $slider_row->timer_bar_type; ?>: 0;
        z-index: 16;
        width: 100%;
        height: <?php echo $circle_timer_size; ?>px;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_circle_timer_container_<?php echo $wds; ?> {
        display: block;
        position: absolute;
        overflow: hidden;
        z-index: 16;
        width: 100%;
      <?php switch ($slider_row->timer_bar_type) {
      case 'circle_top_right': echo 'top: 0px; text-align:right;'; break;
      case 'circle_top_left': echo 'top: 0px; text-align:left;'; break;
      case 'circle_bot_right': echo 'bottom: 0px; text-align:right;'; break;
      case 'circle_bot_left': echo 'bottom: 0px; text-align:left;'; break;
      default: 'top: 0px; text-align:right;';
       } ?>
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_circle_timer_container_<?php echo $wds; ?> .wds_circle_timer_<?php echo $wds; ?> {
        display: inline-block;
        width: <?php echo $circle_timer_size; ?>px;
        height: <?php echo $circle_timer_size; ?>px;
        position: relative;
        opacity: <?php echo number_format((100 - $slider_row->timer_bar_transparent) / 100, 2, ".", ""); ?>;
        filter: alpha(opacity=<?php echo 100 - $slider_row->timer_bar_transparent; ?>);
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_circle_timer_container_<?php echo $wds; ?> .wds_circle_timer_<?php echo $wds; ?> .wds_circle_timer_parts_<?php echo $wds; ?> {
        display: table;
        width: 100%;
        height: 100%;
        border-radius: 100%;
        position: relative;
      }
      .wds_circle_timer_part_<?php echo $wds; ?> {
        display: table-cell;
        width: 50%;
        height: 100%;
        overflow: hidden !important;
      }
      .wds_circle_timer_small_parts_<?php echo $wds; ?> {
        display: block;
        width: 100%;
        height: 50%;
        background: #<?php echo $slider_row->timer_bar_color; ?>;
        position: relative;
      }
      .wds_circle_timer_center_cont_<?php echo $wds; ?> {
        display: table;
        width: <?php echo $circle_timer_size; ?>px;
        height: <?php echo $circle_timer_size; ?>px;
        position: absolute;
        text-align: center;
        top:0px;
        vertical-align:middle;
      }
      .wds_circle_timer_center_<?php echo $wds; ?> {
        display: table-cell;
        width: 100%;
        height: 100%;
        text-align: center;
        line-height: 0px !important;
        vertical-align: middle;
      }
      .wds_circle_timer_center_<?php echo $wds; ?> div {
        display: inline-block;
        width: <?php echo $circle_timer_size / 2 - 2; ?>px;
        height: <?php echo $circle_timer_size / 2 - 2; ?>px;
        background-color: #FFFFFF;
        border-radius: 100%;
        z-index: 300;
        position: relative;
      }

      <?php
    }
    ?>
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slide_container_<?php echo $wds; ?> {
        height: /*inherit*/100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_spun1_<?php echo $wds; ?> {
        display: table;
        width: <?php echo $carousel ? "100%" : "/*inherit*/100%"; ?>;
        height: <?php echo $carousel ? "100%" : "/*inherit*/100%"; ?>;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_spun2_<?php echo $wds; ?> {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        overflow: hidden;
        height: /*inherit*/100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_video_layer_frame_<?php echo $wds; ?> {
        max-height: 100%;
        max-width: 100%;
        width: 100%;
        height: 100%;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_video_hide<?php echo $wds; ?> {
        width: 100%;
        height: 100%;
        position:absolute;
      }
      #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slider_car_image<?php echo $wds; ?> {
        overflow: hidden;
      }
      #wds_container1_<?php echo $wds; ?> .wds_loading_img {
        background-image: url('<?php echo WDS()->plugin_url ?>/images/loading/<?php echo $loading_gif; ?>.gif');
      }
      <?php
      if ($hide_on_mobile) {
        ?>
      @media screen and (max-width: <?php echo intval($hide_on_mobile-1); ?>px){
        #wds_container1_<?php echo $wds; ?> {
          display: none;
        }
      }
      <?php
    }
    if ($full_width_for_mobile) {
      ?>
      @media screen and (max-width: <?php echo intval($full_width_for_mobile-1); ?>px) {
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> {
          margin:<?php echo $slider_row->glb_margin; ?>px 0;
          position: relative;
        }
        #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_slideshow_image_wrap_<?php echo $wds; ?> {
          position: absolute;
        }
      }
      <?php
    }
		echo html_entity_decode($slider_row->css);
		foreach ($slide_rows as $key => $slide_row) {
          $fillmode = 'fill';
          if ( !empty($slider_row->bg_fit) ) {
            if ( $slider_row->bg_fit == 'cover') {
              $fillmode = 'fill';
            }
            if ( $slider_row->bg_fit == '100% 100%') {
                $fillmode = 'stretch';
            }
            if ( $slider_row->bg_fit == 'contain') {
              $fillmode = 'fit';
            }
          }
          $slide_row->fillmode = empty($slide_row->fillmode) ? $fillmode : $slide_row->fillmode;
          $background_size = 'cover';
			if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'fit') { 
				$background_size = 'contain';
			}
			?>
			#wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_image_id_<?php echo $wds .'_'. $slide_row->id; ?> .wds_slideshow_image_<?php echo $wds; ?> {
        <?php
        if ( !empty($slide_row->fillmode) && $slide_row->fillmode == 'fill') {
          $bg_pos = array('center', 'center');
          if ( $slider_row->smart_crop ) {
            $bg_pos = explode(" ", $slider_row->crop_image_position);
          }
          ?>
					background-size: cover;
					background-position: <?php echo $bg_pos[0]; ?> <?php echo $bg_pos[1]; ?>;
					background-repeat: no-repeat;
          <?php
        }
        ?>
				<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'fit') { ?>
					background-size: contain;
					background-position: center center;
					background-repeat: no-repeat;
				<?php } ?>
				<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'stretch') { ?>
					background-size: 100% 100%;
					background-position: 100% 100%;
					background-repeat: no-repeat;
				<?php } ?>
				<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'center') { ?>
					background-size: unset;
					background-position: center center;
					background-repeat: no-repeat;
				<?php } ?>
				<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'tile') { ?>
					background-size: unset;
					background-position: unset;
					background-repeat: repeat;
				<?php } ?>
			}			
			#wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_image_id_<?php echo $wds .'_'. $slide_row->id; ?> .wds_slideshow_image_<?php echo $wds; ?> > video {
				background-size: <?php echo $background_size ?>;
			}
			<?php
		  if (isset($layers_rows[$slide_row->id]) && !empty($layers_rows[$slide_row->id])) {
			foreach ($layers_rows[$slide_row->id] as $key => $layer) {
			  if ($layer->published) {				
				$prefix = 'wds_' . $wds . '_slide' . $slide_row->id . '_layer' . $layer->id;
				$hide_on_mobile = (isset($layer->hide_on_mobile) ? $layer->hide_on_mobile : 0);
				if ($hide_on_mobile) { ?>
					@media screen and (max-width: <?php echo intval($hide_on_mobile-1); ?>px){
						#wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?>,
					  #wds_container1_<?php echo $wds; ?> .hotspot_container {
							display: none !important;
						}
					}
				<?php
				}
				switch ($layer->type) {
				  case 'text': {
					?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?> {
						font-size: <?php echo $layer->size; ?>px;
						line-height: 1.25em;
						padding: <?php echo $layer->padding; ?>;
					  }
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?>{
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?>:hover {
						 color: #<?php echo $layer->hover_color_text; ?> !important;
						}
						<?php
					  break;
					}
					case 'image': {
					  ?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?>{
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
					  <?php
					  break;
					}
					case 'video': {
					  ?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?> {
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
					  <?php
					  break;
					}
					case 'upvideo': {
					  ?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?> {
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
					  <?php
					  break;
					}
					case 'social': {
					  ?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?> {
						font-size: <?php echo $layer->size; ?>px;
						padding: <?php echo $layer->padding; ?>;
					  }
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?> {
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?>:hover {
						 color: #<?php echo $layer->hover_color; ?> !important;
						 }
						<?php
					  break;
					}
					case 'hotspots': {
					  ?>
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #<?php echo $prefix; ?> {
						font-size: <?php echo $layer->size; ?>px;
						line-height: 1.25em;
						padding: <?php echo $layer->padding; ?>;
					  }
					  #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> #wds_<?php echo $wds; ?>_slide1_layer1_div .wds_layer_<?php echo $layer->id; ?>_div {
						opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
						filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
					  }
            #wds_container1_<?php echo $wds; ?> #wds_container2_<?php echo $wds; ?> .wds_layer_<?php echo $layer->id; ?>_div{
            opacity: <?php echo ($layer->layer_effect_in != 'none') ? '0 !important' : '1'; ?>;
            filter: "Alpha(opacity=<?php echo ($layer->layer_effect_in != 'none') ? '0' : '100'; ?>)" !important;
            }
						<?php
					  break;
					}
					default:
					  break;
				  }
				}
			}
		}
	}
    $css_content = ob_get_clean();

    return $css_content;
  }

  public static function global_options_defults() {
    $global_options = array(
      'default_layer_fweight'          => 'normal',
      'default_layer_start'            => 1000,
      'default_layer_effect_in'        => 'none',
      'default_layer_duration_eff_in'  => 1000,
      'default_layer_infinite_in'      => 1,
      'default_layer_end'              => 3000,
      'default_layer_effect_out'       => 'none',
      'default_layer_duration_eff_out' => 1000,
      'default_layer_infinite_out'     => 1,
      'default_layer_add_class'        => '',
      'default_layer_ffamily'          => 'arial',
      'default_layer_google_fonts'     => 0,
      'loading_gif'                    => 0,
      'register_scripts'               => 0,
      'spider_uploader'                => 0,
      'possib_add_ffamily'             => '',
      'possib_add_ffamily_google'      => '',
      'permission'                     => 'manage_options',
    );
    return $global_options;
  }

  /**
   * Redirect.
   *
   * @param $url
   */
  public static function redirect($url) {
    $url = html_entity_decode(wp_nonce_url($url, WDS()->nonce, WDS()->nonce));
    ?>
    <script>
		window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }

  /**
	* Clean page prefix.
	*
	* @param  string $str
	* @return string $str
	*/
	public static function clean_page_prefix( $str = '' ) {
		$str = str_replace('_' . WDS()->prefix, '', $str);
		$str = ucfirst($str);
		return $str;
	}

	/**
	* Get shortcode data.
	*
	* @return json $data
	*/
	public static function get_shortcode_data() {
		global $wpdb;
		$rows = $wpdb->get_results('SELECT `id`, `name` FROM `' . $wpdb->prefix . 'wdsslider` ORDER BY `name` ASC');
		$data = array();
		$data['shortcode_prefix'] = WDS()->prefix;
		$data['inputs'][] = array(
								'type' => 'select',
								'id' => WDS()->prefix . '_id',
								'name' => WDS()->prefix . '_id',
								'shortcode_attibute_name' => 'id',
								'options'  => $rows,
							);
		return json_encode($data);
	}

  /**
   * Check if is preview of Elementor builder.
   *
   * @return bool
   */
  public static function elementor_is_active() {
    if ( in_array(self::get('action', ''), array(
        'elementor',
        'elementor_ajax',
      )) || self::get('elementor-preview', '') ) {

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Generate top bar.
   *
   * @return string Top bar html.
   */
  public static function topbar() {
    $page = isset($_GET['page']) ? esc_html($_GET['page']) : '';
    $user_guide_link = 'https://help.10web.io/hc/en-us/articles/';
    $show_guide_link = true;
    $show_head = false;
    switch ($page) {
      case 'sliders_wds': {
        $user_guide_link .= '360016084351-Creating-Sliders-on-WordPress';
        break;
      }
      case 'goptions_wds': {
        $user_guide_link .= '360016084831-Editing-General-Options';
        break;
      }
      case 'demo_sliders_wds': {
        $user_guide_link .= '360015864972-Importing-and-Exporting-Sliders';
        break;
      }
      case 'licensing_wds': {
        $user_guide_link .= '360015864212-Introducing-WordPress-Slider';
        break;
      }
      default: {
        return '';
        break;
      }
    }
    $user_guide_link .= '?utm_source=slider&utm_medium=free_plugin';
    $support_forum_link = 'https://wordpress.org/support/plugin/slider-wd/#new-post';
    $premium_link = 'https://10web.io/plugins/wordpress-slider/?utm_source=slider&utm_medium=free_plugin';
    wp_enqueue_style(WDS()->prefix . '-roboto');
    wp_enqueue_style(WDS()->prefix . '-pricing');
    ob_start();
    ?>
    <div class="wrap">
      <h1 class="wd-head-notice">&nbsp;</h1>
      <div class="wd-topbar-container">
        <?php
        if (WDS()->is_free) {
          ?>
          <div class="wd-topbar wd-topbar-content">
            <div class="wd-topbar-content-container">
              <div class="wd-topbar-content-title">
                <?php _e('Slider by 10Web Premium', WDS()->prefix); ?>
              </div>
              <div class="wd-topbar-content-body">
                <?php _e('Get multi-layer slides, new stunning effects, social media embedding and so much more.', WDS()->prefix); ?>
              </div>
            </div>
            <div class="wd-topbar-content-button-container">
              <a href="<?php echo $premium_link; ?>" target="_blank" class="wd-topbar-upgrade-button"><?php _e( 'Upgrade', WDS()->prefix ); ?></a>
            </div>
          </div>
          <?php
        }
        ?>
        <div class="wd-topbar_cont">
          <div class="wd-topbar wd-topbar-links wd-topbar_user_guid">
            <div class="wd-topbar-links-container">
              <?php if ( $show_guide_link ) { ?>
                <a href="<?php echo $user_guide_link; ?>" target="_blank" class="wd-topbar_user_guid">
                  <div class="wd-topbar-links-item">
                    <?php _e('User guide', WDS()->prefix); ?>
                  </div>
                </a>
                <?php
              }?>
            </div>
          </div>
          <?php
          if (WDS()->is_free) {
            ?>
            <div class="wd-topbar wd-topbar-links wd-topbar_support_forum">
              <div class="wd-topbar-links-container">
                <a href="<?php echo $support_forum_link; ?>" target="_blank" class="wd-topbar_support_forum">
                  <div class="wd-topbar-links-item">
                    <img src="<?php echo WDS()->plugin_url . '/css/images/help.svg'; ?>" class="help_icon" />
                    <?php _e('Ask a question', WDS()->prefix); ?>
                  </div>
                </a>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      <?php if ( $show_head ) {
        $menus = array(
          'manage' => array(
            'href' => add_query_arg( array('page' => 'manage' . WDS()->menu_postfix ), admin_url('admin.php')),
            'name' => __('Forms', WDS()->prefix)
          ),
          'addons' => array(
            'href' => add_query_arg( array('page' => 'addons' . WDS()->menu_postfix ), admin_url('admin.php')),
            'name' => __('Add-ons', WDS()->prefix)
          ),
          'pricing' => array(
            'href' => add_query_arg( array('page' => 'pricing' . WDS()->menu_postfix ), admin_url('admin.php')),
            'name' => __('Premium Version', WDS()->prefix) .' <span class="wd-upgrade">' . __('Upgrade', WDS()->prefix) . '</span>'
          ),
        );
        ?>
        <style>#wpbody-content>div:not(.wrap), .wrap .notice:not(#wd_bp_notice_cont) { display: none; }</style>
        <div class="wd-head">
          <div><img src="<?php echo WDS()->plugin_url . '/images/FormMaker.png'; ?>"></div>
          <ul class="wd-breadcrumbs">
            <?php
            foreach ( $menus as $key => $item ) {
              if ( !WDS()->is_free && $key == 'pricing' ) {
                continue;
              }
              ?>
              <li class="wd-breadcrumb-item">
                <a class="wd-breadcrumb-item-link<?php echo ( $key == $page ) ? ' wd-breadcrumb-item-link-active' : ''; ?>" href="<?php echo $item['href']; ?>"><?php echo $item['name']; ?></a>
              </li>
              <?php
            }
            ?>
          </ul>
        </div>
      <?php }	?>
    </div>
    <?php
    echo ob_get_clean();
  }

  /**
   * Get unique number for view.
   *
   * @return int
   */
  public static function unique_number() {
    $use_random_number = (WDW_S_Library::elementor_is_active()) ? TRUE : FALSE;
    if ( $use_random_number ) {
      return mt_rand();
    }
    else {
      global $wds;
      $unique = $wds;
      $wds++;

      return $unique;
    }
  }

  /**
   * Import slider using path of the file
   *
   * @param $zipfilepath string
   *
   * @return array
   */
  public static function twbb_wds_import( $zipfilepath ) {
    if( ! file_exists( $zipfilepath ) ) {
      return false;
    }
    $data_slider_id = array();
    $dest_dir = ABSPATH . WDS()->upload_dir;
    $filename = basename($zipfilepath);
    if ( ! file_exists( $dest_dir ) ) {
      mkdir( $dest_dir, 0777, true );
    }
    if(copy( $zipfilepath, $dest_dir.'/'.$filename )) {
      $data_slider_id = self::wds_import_zip_action( $dest_dir, $filename );
    }
    return $data_slider_id;
  }

  /**
   * Read ZIP file and insert to DB
   *
   * @param $dest_dir string
   * @param $filename string
   *
   * @return array
   */
  public static function wds_import_zip_action( $dest_dir, $filename ) {
    $flag = FALSE;
    $data_slider_id = array();
    if ( !class_exists('\PhpZip\ZipFile') ) {
      include_once WDS()->plugin_dir . "/library/vendor/autoload.php";
    }
    $zip_file = new \PhpZip\ZipFile();

    try {
      $zip_file->openFile($dest_dir . '/' . $filename);
    } catch( \PhpZip\Exception\ZipException $e ) {
      return $flag;
    }

    if(!is_dir($dest_dir . '/import')) {
      mkdir($dest_dir . '/import', 0777, TRUE);
    }
    if(!is_dir($dest_dir . '/import/arrows')) {
      mkdir($dest_dir . '/import/arrows', 0777, TRUE);
    }
    if(!is_dir($dest_dir . '/import/arrows/thumb')) {
      mkdir($dest_dir . '/import/arrows/thumb', 0777, TRUE);
    }
    if(!is_dir($dest_dir . '/import/arrows/.original')) {
      mkdir($dest_dir . '/import/arrows/.original', 0777, TRUE);
    }
    if(!is_dir($dest_dir . '/import/.original')) {
      mkdir($dest_dir . '/import/.original', 0777, TRUE);
    }
    if(!is_dir($dest_dir . '/import/thumb')) {
      mkdir($dest_dir . '/import/thumb', 0777, TRUE);
    }

    $upload_dir = wp_upload_dir();
    $dest_url = $upload_dir['baseurl'] . '/slider-wd/';

    foreach( $zip_file as $file_name => $content ) {
      if( strripos( $file_name, ".xml" ) ) {
        $flag = self::wds_import_xml_file( $content, $dest_url, $data_slider_id );
      }
      else {
        self::wds_import_image_file( $file_name, $content, $dest_dir );
      }

    }

    $zip_file->close();
    unlink($dest_dir . '/' . $filename);
    if($flag) {
      return $data_slider_id;
    }
    return $flag;
  }

  /**
   *
   * Function for xml files
   * $buf is the file content
   * $dest_url is the file upload directory
   * @param string $buf
   * @param string $dest_url
   *
   * @return boolean
   **/
  private static function wds_import_xml_file( $buf, $dest_url, &$data_slider_id ){
    global $wpdb;
    $flag = FALSE;

    $buf = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~', '$1', $buf);
    $xml = simplexml_load_string($buf);
    $slider_fields = array();
    $slide_fields = array();
    $slides = array();
    $layer_fields = array();
    $layers = array();
    $sliders = $xml->slider;
    foreach($sliders as $slider) {
      $old_slider_id = WDW_S_Library::xml_attribute($slider->id, 'value');
      foreach($slider as $key_slider => $value_slider) {
        $flag = TRUE;
        if(strpos($value_slider["value"], WDS()->site_url_buttons_placeholder) === 0) {
          $slider_fields[$key_slider] = trim(str_replace(WDS()->site_url_buttons_placeholder, $dest_url . '/import/arrows/', $value_slider["value"]));
          $slider_fields[$key_slider] = trim(str_replace(site_url(), '{site_url}', $slider_fields[$key_slider]));
        } elseif(strpos($value_slider["value"], WDS()->site_url_placeholder) === 0) {
          $slider_fields[$key_slider] = trim(str_replace(WDS()->site_url_placeholder, site_url(), $value_slider["value"]));
          $slider_fields[$key_slider] = trim(str_replace(site_url(), '{site_url}', $slider_fields[$key_slider]));
        } elseif($key_slider != "slide" && $key_slider != "id") {
          $slider_fields[$key_slider] = trim($value_slider["value"]);
        } elseif($key_slider == "slide") {
          foreach($value_slider->children() as $key_slide => $slide) {
            if($key_slide != "layer" && $key_slide != "id") {
              $slide_fields[$key_slide] = trim($slide["value"]);
            } elseif($key_slide == "layer") {
              foreach($slide->children() as $key_layer => $layer) {
                if($key_layer != "id") {
                  $layer_fields[$key_layer] = isset($layer["value"]) ? trim($layer["value"]) : trim($layer);
                }
              }
              array_push($layers, $layer_fields);
            }
          }
          array_push($slides, array("slide" => $slide_fields, "layers" => $layers));
          $layers = array();
        }
      }
      // Column doesn't exist in DB
      unset($slider_fields['bg_fit']);
      $wpdb->insert($wpdb->prefix . 'wdsslider', $slider_fields);
      $slider_id = $wpdb->insert_id;
      $data_slider_id[$old_slider_id] = $slider_id;
      foreach($slides as $slide) {
        $slide["slide"]["slider_id"] = $slider_id;
        if(strpos($slide["slide"]["image_url"], WDS()->site_url_placeholder) === 0) {
          $slide["slide"]["image_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/', $slide["slide"]["image_url"]));
          $slide["slide"]["image_url"] = trim(str_replace(site_url(), '{site_url}', $slide["slide"]["image_url"]));
        }
        if(strpos($slide["slide"]["thumb_url"], WDS()->site_url_placeholder) === 0) {
          $slide["slide"]["thumb_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/thumb/', $slide["slide"]["thumb_url"]));
          $slide["slide"]["thumb_url"] = trim(str_replace(site_url(), '{site_url}', $slide["slide"]["thumb_url"]));
        }
        $wpdb->insert($wpdb->prefix . 'wdsslide', $slide["slide"]);
        $slide_id = $wpdb->insert_id;
        foreach($slide["layers"] as $layer) {
          $layer["slide_id"] = $slide_id;
          if(strpos($layer["image_url"], WDS()->site_url_placeholder) === 0) {
            $layer["image_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/', $layer["image_url"]));
            $layer["image_url"] = trim(str_replace(site_url(), '{site_url}', $layer["image_url"]));
          }
          $wpdb->insert($wpdb->prefix . 'wdslayer', $layer);
        }
      }
      $slides = array();
    }

    return $flag;
  }

  /**
   *
   * Function for images
   * $zip_name is the name of the zip file name
   * $zip_r is the file content
   * $dest_dir is the file directory
   * @param string $zip_name
   * @param string $zip_r
   * @param string $dest_dir
   *
   **/
  private static function wds_import_image_file( $zip_name, $zip_r, $dest_dir ){

    if(strpos($zip_name, 'featured_') === 0) {
      $zip_name = str_replace('featured_', '', $zip_name);
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/thumb/', $zip_name);
      if($handlethumb = fopen($dest_dir . '/import/thumb/' . $zip_name, "w")) {
        fwrite($handlethumb, $zip_r);
        fclose($handlethumb);
      }
    }

    if(strpos($zip_name, 'thumb_') === 0) {
      $zip_name = str_replace('thumb_', '', $zip_name);
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/thumb/', $zip_name);
      if($handlethumb = fopen($dest_dir . '/import/thumb/' . $zip_name, "w")) {
        fwrite($handlethumb, $zip_r);
        fclose($handlethumb);
      }
    } elseif(strpos($zip_name, WDS()->site_url_buttons_placeholder . '_thumb_') === 0) {
      $zip_name = str_replace(WDS()->site_url_buttons_placeholder . '_thumb_', '', $zip_name);
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/arrows/thumb/', $zip_name);
      if($handlethumb = fopen($dest_dir . '/import/arrows/thumb/' . $zip_name, "w")) {
        fwrite($handlethumb, $zip_r);
        fclose($handlethumb);
      }
    } elseif(strpos($zip_name, WDS()->site_url_buttons_placeholder) === 0) {
      $zip_name = str_replace(WDS()->site_url_buttons_placeholder, '', $zip_name);
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/arrows/.original/', $zip_name);
      if($handleorg = fopen($dest_dir . '/import/arrows/.original/' . $zip_name, "w")) {
        fwrite($handleorg, $zip_r);
        fclose($handleorg);
      }
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/arrows/', $zip_name);
      if($handleorg = fopen($dest_dir . '/import/arrows/' . $zip_name, "w")) {
        fwrite($handleorg, $zip_r);
        fclose($handleorg);
      }
    } else {
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/.original/', $zip_name);
      $file_name = $dest_dir . '/import/.original/' . $zip_name;
      if($handleorg = fopen($file_name, "w")) {
        fwrite($handleorg, $zip_r);
        fclose($handleorg);
      }
      $zip_name = self::get_unique_file_name($zip_name, $dest_dir . '/import/', $zip_name);
      $file_name = $dest_dir . '/import/' . $zip_name;
      if($handleorg = fopen($file_name, "w")) {
        fwrite($handleorg, $zip_r);
        fclose($handleorg);
      }
    }
  }


  public static function get_unique_file_name( $filename, $foldername, $zip_name ) {
    if (file_exists($foldername . $filename)) {
      $p = 1;
      $fileName1 = $zip_name;
      while (file_exists($foldername . $fileName1)) {
        $to = strrpos($fileName1, '.');
        $fileName1 = substr($fileName1, 0, $to) . '(' . $p . ')' . substr($fileName1, $to);
        $p++;
      }
      $zip_name = $fileName1;
    }
    return $zip_name;
  }

	private static function xml_attribute( $object, $attribute ) {
		if(isset($object[$attribute]))
		return (string) $object[$attribute];
	}
}
