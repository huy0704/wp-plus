<?php
class TWBLibrary {

  /**
   * Return button template which styles user can change using arguments
   *
   * @param $object object of TenWebBooster class
   *
   * @return string html data
  */
  public static function twb_button_template( $object ) {
    ob_start();

    ?>
    <a href="<?php echo esc_url($object->submenu_url); ?>" target="_blank"
       class="twb-custom-button <?php echo isset($object->cta_button['class']) ? esc_attr($object->cta_button['class']) : '' ?>">
          <?php echo isset($object->cta_button['label']) ? esc_html($object->cta_button['label']) : esc_html__('Optimize Now', 'tenweb-booster'); ?>
    </a>
    <?php
    return ob_get_clean();
  }

  /**
   * Convert bytes to B, KM, MB, GB, TB, PB.
   *
   * @param $bytes
   * @param $precision
   *
   * @return string
   */
  public static function formatBytes( $bytes, $precision = 2 ) {
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

  /**
   * Convert B, KM, MB, GB, TB, PB to bytes.
   *
   * @param string $from
   *
   * @return array|float|int|string|string[]|null
   */
  public static function convertToBytes( $from ) {
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    $number = substr($from, 0, -2);
    $suffix = strtoupper(substr($from, -2));
    if ( is_numeric(substr($suffix, 0, 1)) ) {
      return preg_replace('/[^\d]/', '', $from);
    }
    $flipped = array_flip($units);

    if ( !isset($flipped[$suffix]) ) {
      return NULL;
    }

    return floatval($number) * (1024 ** $flipped[$suffix]);
  }

  /**
   * Save the page speed score in the post meta.
   *
   * @return void
   */
  public static function check_score($post_id) {
    // Getting front_page placeholder instead of page ID for Home page.
    $url = ($post_id == 'front_page') ? get_home_url() : get_permalink($post_id);
    if ( !$url ) {
      return;
    }

    // Get the page score from DB.
    if ( $post_id == 'front_page' ) {
      $page_score = get_option('two-front-page-speed');
    }
    else {
      $page_score = get_post_meta($post_id, 'two_page_speed', TRUE);
    }
    if ( empty($page_score) ) {
      $page_score = array();
    }
    if ( empty($page_score['previous_score']) ) {
      $page_score['previous_score'] = array();
    }
    // Set the status to in progress.
    $page_score['previous_score']['status'] = 'inprogress';
    if ( $post_id == 'front_page' ) {
      update_option('two-front-page-speed', $page_score);
    }
    else {
      update_post_meta($post_id, 'two_page_speed', $page_score);
    }

    $desktop_score = TWBLibrary::google_check_score($url, 'desktop');
    //$desktop_score = array('desktop_score' => 75, 'desktop_tti' => '1.1');
   // $desktop_score = array('error' => 1);
    $score = $desktop_score;
    $mobile_score = TWBLibrary::google_check_score($url, 'mobile');
   // $mobile_score = array('mobile_score' => 50, 'mobile_tti' => '1.0');
   // $mobile_score = array('error' => 1);
    $score = array_merge($score, $mobile_score);
    $score['date'] = date('d.m.Y h:i:s a', strtotime(current_time('mysql')));
    // Change the status.
    $score['status'] = 'completed';
    $score['shown'] = 0;
    $page_score['previous_score'] = $score;

    // Save the status and score in DB.
    if ( $post_id == 'front_page' ) {
      update_option('two-front-page-speed', $page_score);
    }
    else {
      update_post_meta($post_id, 'two_page_speed', $page_score);
    }

    return json_encode($page_score['previous_score']);
  }

  /**
   * Get the page speed from Google by URL.
   *
   * @param $page_url
   * @param $strategy
   *
   * @return array
   */
  public static function google_check_score( $page_url, $strategy ) {
    $google_api_keys = array(
      'AIzaSyCQmF4ZSbZB8prjxci3GWVK4UWc-Yv7vbw',
      'AIzaSyAgXPc9Yp0auiap8L6BsHWoSVzkSYgHdrs',
      'AIzaSyCftPiteYkBsC2hamGbGax5D9JQ4CzexPU',
      'AIzaSyC-6oKLqdvufJnysAxd0O56VgZrCgyNMHg',
      'AIzaSyB1QHYGZZ6JIuUUce4VyBt5gF_-LwI5Xsk',
    );
    $random_index = array_rand($google_api_keys);
    $key = $google_api_keys[$random_index];
    $url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . $page_url . "&key=" . $key;
    if ( $strategy == "mobile" ) {
      $url .= "&strategy=mobile";
    }
    $response = wp_remote_get($url, array( 'timeout' => 300 ));
    $data = array();
    if ( is_array($response) && !is_wp_error($response) ) {
      $body = $response['body'];
      $body = json_decode($body);
      if ( isset($body->error) ) {
        $data['error'] = 1;
      }
      else {
        $data[$strategy . '_score'] = 100 * $body->lighthouseResult->categories->performance->score;
        $data[$strategy . '_tti'] = rtrim($body->lighthouseResult->audits->interactive->displayValue, 's');
      }
    }
    else {
      $data['error'] = 1;
    }

    return $data;
  }

  /**
   * @param $score
   * @param $url
   * @param $post_id
   * @param $title
   * @param $hidden bool need to hidden class or no
   * @param $size int size of circle in speed score
   *
   * @return void
   */
  public static function score( $score, $url = '', $post_id = 0, $title = '', $hidden = 1, $size = 30 ) {
    $error = empty($score['error']) ? 0 : 1;
    if (empty($score) || $error) {
      $score = array(
        'desktop_score' => 0,
        'desktop_tti' => '',
        'mobile_score' => 0,
        'mobile_tti' => '',
        'date' => '',
        'status' => 'notstarted',
      );
    }
    $title = ($title != '') ? 'of ' . $title : '';
    ?>
    <div class="twb-score-container <?php echo $hidden ? 'twb-hidden' : '' ?>" data-id="<?php echo esc_attr($post_id); ?>">
      <div class="twb-score-title"><?php echo sprintf(__('PageSpeed score %s', 'tenweb-booster'), strip_tags($title, "<i>")); ?></div>
      <div class="twb-score">
        <div class="twb-score-mobile">
          <div class="twb-score-circle"
               data-id="mobile"
               data-thickness="2"
               data-size="<?php echo esc_attr($size); ?>"
               data-score="<?php echo esc_attr($score['mobile_score']); ?>"
               data-tti="<?php echo esc_attr($score['mobile_tti']); ?>">
            <span class="twb-score-circle-animated"></span>
          </div>
          <div class="twb-score-text">
            <span class="twb-score-text-name"><?php _e('Mobile score', 'tenweb-booster'); ?></span>
            <span class="twb-load-text-time"><?php _e('Load time: ', 'tenweb-booster'); ?><span
                class="twb-load-time"></span>s</span>
          </div>
        </div>
        <div class="twb-score-mobile twb-score-mobile-overlay twb-score-overlay <?php echo esc_html($error ? '' : 'twb-hidden'); ?>">
          <div class="twb-reload" onclick="twb_check_score(this)" data-post_id="<?php echo $post_id; ?>"></div>
        </div>
        <div class="twb-score-desktop">
          <div class="twb-score-circle"
               data-id="desktop"
               data-thickness="2"
               data-size="<?php echo esc_attr($size); ?>"
               data-score="<?php echo esc_attr($score['desktop_score']); ?>"
               data-tti="<?php echo esc_attr($score['desktop_tti']); ?>">
            <span class="twb-score-circle-animated"></span>
          </div>
          <div class="twb-score-text">
            <span class="twb-score-text-name"><?php _e('Desktop score', 'tenweb-booster'); ?></span>
            <span class="twb-load-text-time"><?php _e('Load time: ', 'tenweb-booster'); ?><span
                class="twb-load-time"></span>s</span>
          </div>
        </div>
        <div class="twb-score-desktop twb-score-desktop-overlay twb-score-overlay <?php echo esc_html($error ? '' : 'twb-hidden'); ?>">
          <div class="twb-reload" onclick="twb_check_score(this)" data-post_id="<?php echo $post_id; ?>"></div>
        </div>
      </div>
      <?php
      if ( $url ) {
        ?>
      <div class="twb-score-bottom"><a target="_balnk" href="<?php echo esc_url($url); ?>"><?php _e('Optimize now', 'tenweb-booster'); ?></a></div>
        <?php
      }
      ?>
    </div>
    <?php
  }

  public static function dismiss_info_content( $booster, $hidden = FALSE ) {
    $link = add_query_arg(array('twb_dismiss' => 1), $booster->submenu_url);
    ob_start();
    ?>
    <div class="<?php echo ($hidden ? 'twb-dismiss-container twb-hidden' : 'twb-dismiss-info'); ?>">
      <p><?php echo sprintf(__("You can hide this element from the %s", "tenweb-booster"), "<a href='" . esc_url($link) . "' target='_blank'>" . __('settings', "tenweb-booster") . "</a>"); ?></p>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Get status which return if score counted = 2, not counted = 0, inprogress = 1
   *
   * @return string
   */
  public static function get_page_speed_status() {
    global $post;
    if ( empty($post) ) {
      return false;
    }

    $post_id = $post->ID;
    $page_score = get_post_meta( $post_id, 'two_page_speed' );
    $page_score = end($page_score);

    if ( isset($page_score['previous_score']) ) {
      if ( isset( $page_score['previous_score']['error'] ) && $page_score['previous_score']['error'] == "1" ) {
        return 'error';
      } elseif ( isset( $page_score['previous_score']['status'] ) && $page_score['previous_score']['status'] == "inprogress" ) {
        return 'inprogress';
      } elseif( isset( $page_score['previous_score']['status'] ) && $page_score['previous_score']['status'] == "completed" ) {
        return 'completed';
      }
    }
    return 'notstarted';
  }
}


