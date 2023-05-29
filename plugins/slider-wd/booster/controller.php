<?php

/**
 * Class SpeedController_twb
 */
class BoosterController {
  private $booster;
  private $model;
  private $view;

  const PlUGIN_FILE = 'tenweb-speed-optimizer/tenweb_speed_optimizer.php';

  const PLUGIN_ZIP = 'https://downloads.wordpress.org/plugin/tenweb-speed-optimizer.latest-stable.zip';

  private $google_api_keys = array(
    'AIzaSyCQmF4ZSbZB8prjxci3GWVK4UWc-Yv7vbw',
    'AIzaSyAgXPc9Yp0auiap8L6BsHWoSVzkSYgHdrs',
    'AIzaSyCftPiteYkBsC2hamGbGax5D9JQ4CzexPU',
    'AIzaSyC-6oKLqdvufJnysAxd0O56VgZrCgyNMHg',
    'AIzaSyB1QHYGZZ6JIuUUce4VyBt5gF_-LwI5Xsk'
  );

  public function __construct($booster) {
    $this->booster = $booster;
  }

  /**
   * Execute.
   */
  public function execute($task = '', $params = array()) {
    require_once($this->booster->plugin_dir . '/model.php');
    $this->model = new BoosterModel();
    require_once($this->booster->plugin_dir . '/view.php');
    $this->view = new BoosterView();
    if ( !$task ) {
      $task = isset($_GET['task']) ? sanitize_text_field($_GET['task']) : (isset($_POST['task']) ? sanitize_text_field($_POST['task']) : '');
    }
    if ( $task != 'display' && method_exists($this, $task) ) {
      $this->$task($params);
    }
    else {
      $this->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    $params = array();
    $params['twb_dismiss'] = isset($_GET['twb_dismiss']) ? intval($_GET['twb_dismiss']) : 0;
    $params['submenu_section_optimize_images'] = $this->booster->page['section_optimize_images'];
    $params['section_booster_title'] = $this->booster->page['section_booster_title'];
    $params['section_booster_desc'] = $this->booster->page['section_booster_desc'];
    $params['section_booster_success_title'] = $this->booster->page['section_booster_success_title'];
    $params['section_booster_success_desc'] = $this->booster->page['section_booster_success_desc'];
    $params['section_analyze_desc'] = $this->booster->page['section_analyze_desc'];
    $params['submenu_section_analyze'] = $this->booster->page['section_analyze'];
    $params['booster_plugin_status'] = $this->booster->booster_plugin_status;
    $params['status'] = $this->booster->status;
    $params['booster_is_connected'] = $this->booster->booster_is_connected;
    $params['tenweb_is_paid'] = $this->booster->is_paid;
    $params['is_plugin'] = $this->booster->is_plugin;
    $params['submenu_parent_slug'] = $this->booster->submenu['parent_slug'];
    $params['slug'] = $this->booster->slug;
    $params['show_cta'] = $this->booster->show_cta;
    $params['show_cta_option'] = $this->booster->show_cta_option;

    $params['images_count'] = $this->get_images_count();
    $params['images_total_size'] = get_option('twb_images_total_size', '0 KB');

    $params['page_is_public'] = TRUE;

    $twb_speed_score = get_option('twb_speed_score');
    $data = array(
      'url' => get_home_url(),
      'desktop_score' => 0,
      'desktop_loading_time' => 0,
      'mobile_score' => 0,
      'mobile_loading_time' => 0,
      'last_analyzed_time' => '',
    );
    if ( !empty($twb_speed_score) && !empty($twb_speed_score['last']) && !empty($twb_speed_score['last']['url']) ) {
      $last_url = $twb_speed_score['last']['url'];
      if ( !empty($twb_speed_score[$last_url]['desktop_score'])
        && !empty($twb_speed_score[$last_url]['desktop_loading_time'])
        && !empty($twb_speed_score[$last_url]['mobile_score'])
        && !empty($twb_speed_score[$last_url]['mobile_loading_time'])
        && !empty($twb_speed_score[$last_url]['last_analyzed_time']) ) {
        $data = array(
          'url' => $last_url,
          'desktop_score' => $twb_speed_score[$last_url]['desktop_score'],
          'desktop_loading_time' => $twb_speed_score[$last_url]['desktop_loading_time'],
          'mobile_score' => $twb_speed_score[$last_url]['mobile_score'],
          'mobile_loading_time' => $twb_speed_score[$last_url]['mobile_loading_time'],
          'last_analyzed_time' => $twb_speed_score[$last_url]['last_analyzed_time'],
        );
      }
    }

    $params['twb_speed_score'] = $data;
    $domain_id = get_site_option('tenweb_domain_id');
    $params['dashboard_booster_url'] = '';
    if ( defined("TENWEB_DASHBOARD") && !empty($domain_id) ) {
      $params['dashboard_booster_url'] = trim(TENWEB_DASHBOARD, '/') . '/websites/' . $domain_id . '/booster/frontend';
    }

    $params['pages_compressed'] = $this->get_pages_compressed($params);

    $params['twb_dismiss'] = (isset($_GET['twb_dismiss']) && $_GET['twb_dismiss'] == 1) ? $_GET['twb_dismiss'] : 0;

    $this->view->display($params);
  }

  /**
   * Run function as ajax action and keep optimized images data in DB option
   * Endpoint work once a day to prevent page loading time
   */
  public function set_compressed_pages() {
    $twb_optimized_pages = get_transient("twb_optimized_pages");

    if ( !empty($twb_optimized_pages) || !defined('TENWEBIO_MANAGER_PREFIX') ) {
      die;
    }

    $workspace_id = (int) get_site_option(TENWEBIO_MANAGER_PREFIX . '_workspace_id', 0);
    $domain_id = (int) get_option(TENWEBIO_MANAGER_PREFIX . '_domain_id', 0);
    if ( $this->booster->booster_is_connected ||
      $this->booster->is_paid ||
      $workspace_id == 0 ||
      $domain_id == 0 ||
      !defined('TENWEBIO_API_URL')) {
      die;
    }

    $url = TENWEBIO_API_URL . "/compress/workspaces/" . $workspace_id . "/domains/" . $domain_id."/stat";
    $access_token = get_site_option('tenweb_access_token');

    $args = array(
      'timeout'     => 50,
      'headers'     => array(
        "accept" => "application/x.10weboptimizer.v3+json",
        "authorization" => "Bearer " . $access_token,
      ),
    );
    $response = wp_remote_get($url, $args);
    if ( is_array($response) && !is_wp_error($response) ) {
      $body = json_decode( $response['body'], 1 );
      if ( isset($body['status']) && $body['status'] == 200 ) {
        $data = $body['data'];
        $total_not_compressed_images_size = isset($data['not_compressed']['total_size']) ? $data['not_compressed']['total_size'] : 0;
        $total_not_compressed_images_size = TWBLibrary::formatBytes($total_not_compressed_images_size);
        $total_not_compressed_images_count = intval($data['not_compressed']['full'] + $data['not_compressed']['thumbs'] + $data['not_compressed']['other']);

        $pages_compressed_api = $data['pages_compressed'];

        $twb_optimized_pages = array();
        if ( class_exists('\TenWebOptimizer\OptimizerUtils') ) {
          $twb_optimized_pages = \TenWebOptimizer\OptimizerUtils::getCriticalPages();
        }

        $total_compressed_images = 0;
        $i = 0;
        $pages_compressed = array();
        /* Adding new pages which are optimized but haven't images and endpoint doesn't have that pages in response */
        foreach ( $twb_optimized_pages as $page_id => $val ) {
          /* Permalink text for front_page should be Homepage */
          $pages_compressed[$i]['page_id'] = $page_id;
          if( $page_id == 'front_page' ) {
            $pages_compressed[$i]['permalink'] = 'Homepage';
          } else {
            $pages_compressed[$i]['permalink'] = $val['url'];
          }

          /* Finding value with page_id key in the array from endpoint to get images_count */
          $found = current(array_filter($pages_compressed_api,  function($item) use($page_id) {
            return isset($item['page_id']) && $page_id == $item['page_id'];
          }));

          if( !empty($found) ) {
            $pages_compressed[$i]['images_count'] = $found['images_count'];
            $total_compressed_images += $found['images_count'];
          } else {
            $pages_compressed[$i]['images_count'] = 0;
          }
          $i++;
        }

        $data['pages'] = $pages_compressed;
        $data['compressed_pages_count'] = count($pages_compressed);
        $data['total_compressed_images'] = (int) $total_compressed_images;
        $data['total_not_compressed_images_size'] = $total_not_compressed_images_size;
        $data['total_not_compressed_images_count'] = $total_not_compressed_images_count;
        if ( $total_compressed_images != 0 || $total_not_compressed_images_size != 0 ) {
          set_transient( 'twb_optimized_pages', $data, DAY_IN_SECONDS );
        }
      }
    }
    die;
  }

  /**
   * Update show CTA value.
   *
   * @return void
   */
  public function set_show_cta() {
    $show_cta = isset($_POST['show_cta']) ? intval($_POST['show_cta']) : 0;
    update_option("twb_show_cta", $show_cta);
    die();
  }

  /**
   * Get copmpressed pages/images data from DB.
   *
   * @param $params array
   *
   * @return array
   */
  public function get_pages_compressed( $params ) {
    $return_data = array(
      'pages' => array(),
      'total_compressed_images' => 0,
      'total_not_compressed_images_size' => '0 B',
      'total_not_compressed_images_count' => 0,
      'compressed_pages_count' => 0,
    );

    $twb_optimized_pages = get_transient("twb_optimized_pages");
    if ( !empty($twb_optimized_pages) ) {
      return $twb_optimized_pages;
    } else {
      /* in case of returned data is 0 just get from db last data or run PG functionality to get count/size */
      $images_total_size = get_option('twb_images_total_size');
      if ( !empty($images_total_size) ) {
        $return_data['total_not_compressed_images_size'] = $images_total_size;
        $return_data['total_not_compressed_images_count'] = $params['images_count'];
      }
    }

    return $return_data;
  }

  /**
   * Get total size of all images․
   */
  public function get_total_size_of_images() {
    $total = get_option('twb_images_total_size');
    if ( empty($total) ) {
      $total = 0;
      $allowed_types = array(
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg',
        'image/png'
      );
      $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => NULL,
        'post_parent' => NULL, // any parent
      );
      $attachments = get_posts($args);
      if ( $attachments ) {
        foreach ( $attachments as $post ) {
          if ( !in_array($post->post_mime_type, $allowed_types) ) {
            continue;
          }
          $meta = wp_get_attachment_metadata($post->ID);
          if ( isset($meta['filesize']) ) {
            $filesize = $meta['filesize'];
          }
          else {
            $path = get_attached_file($post->ID);
            if ( !file_exists($path) ) {
              continue;
            }
            $filesize = filesize($path);
          }
          $total += $filesize;
        }
      }
      $total = TWBLibrary::formatBytes( $total );
      update_option('twb_images_total_size', $total, 1);
    }

    echo json_encode(array("size" => $total));
    die;
  }

  /**
   * Top banner.
   */
  public function top_banner($params = array()) {
    if ( $this->booster->is_paid === FALSE ) {
      $booster_is_active = ( $this->booster->booster_is_connected && $this->booster->booster_plugin_status == 2 ) ? TRUE : FALSE;
      if ( $booster_is_active ) {
        if ( !isset($params['title']) ) {
          $params['title'] = __('Get 10Web Booster Pro', 'tenweb-booster');
        }
        if ( !isset($params['desc']) ) {
          $params['desc'] = __('Automatically optimize the entire website with all images.', 'tenweb-booster');
        }
        if ( !isset($params['notice']) ) {
          $params['notice'] = FALSE;
        }
        $params['button'] = array(
          'name' => __('Upgrade', 'tenweb-booster'),
          'url' => "https://my.10web.io/websites/" . get_site_option('tenweb_domain_id') . "/booster/frontend?pricing=1",
          'target' => 'target="_blank"',
        );
      }
      else {
        if ( !isset($params['title']) ) {
          $single = __('%s image can be optimized', 'tenweb-booster');
          $plural = __('%s images can be optimized', 'tenweb-booster');
          $images_count = $this->get_images_count();
          $params['title'] = wp_sprintf(_n($single, $plural, $images_count, 'tenweb-booster'), $images_count);
        }
        if ( !isset($params['desc']) ) {
          $params['desc'] = __('Improve PageSpeed optimization by optimizing your website.', 'tenweb-booster');
        }
        if ( !isset($params['notice']) ) {
          $params['notice'] = __('Heavy images negatively affect your website load time and PageSpeed optimization.', 'tenweb-booster');
        }
        $params['button'] = array(
          'name' => __('Optimize now', 'tenweb-booster'),
          'url' => add_query_arg(array( 'page' => TenWebBooster::PREFIX . '_' . $this->booster->slug ), admin_url('admin.php')),
          'target' => '',
        );
      }

      $this->view->top_banner($params);
    }
  }

  /**
   * Install booster plugin
   */
  public function install_booster() {
    $activated = $this->install_plugin();
    // To change the plugin status on Dashboard.
    if (class_exists('\Tenweb_Authorization\Helper')
      && method_exists('\Tenweb_Authorization\Helper', "check_site_state") ) {
      \Tenweb_Authorization\Helper::check_site_state(true);
    }
    // activate_plugin function returns null when the plugin activated successfully.
    if ( is_null($activated) ) {
      $this->booster = $this->booster->set_booster_data();
    }
    $params = array();
    $params['booster_plugin_status'] = $this->booster->booster_plugin_status;
    $params['status'] = $this->booster->status;
    $params['is_plugin'] = $this->booster->is_plugin;
    $params['tenweb_is_paid'] = $this->booster->is_paid;
    $params['slug'] = $this->booster->slug;
    $domain_id = get_site_option('tenweb_domain_id');
    $params['dashboard_booster_url'] = '';
    if ( defined("TENWEB_DASHBOARD") && !empty($domain_id) ) {
      $params['dashboard_booster_url'] = trim(TENWEB_DASHBOARD, '/') . '/websites/' . $domain_id . '/booster/frontend';
    }
    $params['submenu_parent_slug'] = $this->booster->submenu['parent_slug'];
    $params['section_booster_title'] = $this->booster->page['section_booster_title'];
    $params['section_booster_desc'] = $this->booster->page['section_booster_desc'];
    $params['section_booster_success_title'] = $this->booster->page['section_booster_success_title'];
    $params['section_booster_success_desc'] = $this->booster->page['section_booster_success_desc'];
    $this->view->header($params);
    die;
  }

  /**
   * Install/activate the plugin.
   *
   * @param $status
   *
   * @return bool|int|true|WP_Error|null
   */
  private function install_plugin() {
    $activated = FALSE;
    if ( $this->booster->booster_plugin_status == 0 ) {
      include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
      wp_cache_flush();
      $upgrader = new Plugin_Upgrader();
      $installed = $upgrader->install(self::PLUGIN_ZIP);
    }
    else {
      $installed = TRUE;
    }

    if ( !is_wp_error( $installed ) && $installed ) {
      $activated = activate_plugin(self::PlUGIN_FILE);
    }

    return $activated;
  }

  /**
   * Get optimized media count.
   *
   * @return int
   */
  public function get_images_count() {
    $allowed_types = array('image/jpeg','image/jpg','image/png','image/gif','image/webp','image/svg');
    // Get all wp-media attachments.
    $attachments = wp_count_attachments();
    $total = 0;
    if ( !empty($attachments) ) {
      foreach ( $attachments as $key => $attachment ) {
        if ( in_array($key, $allowed_types) ) {
          $total += $attachment;
        }
      }
    }

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_plugin_active('photo-gallery/photo-gallery.php') ) {
      // Get all Photo gallery media.
      global $wpdb;
      $row = $wpdb->get_row('SELECT count(id) AS qty FROM `' . $wpdb->prefix . 'bwg_file_paths` WHERE is_dir = 0');
      if ( !empty($row) && !empty($row->qty) ) {
        $total += intval($row->qty);
      }
    }

    return intval($total);
  }

  /**
   * Sign up to the Dashboard.
   *
   * @return bool
   */
  public function sign_up_dashboard() {
    $email = isset($_POST['twb_email']) ? sanitize_email($_POST['twb_email']) : '';
    $parent_slug = isset($_POST['parent_slug']) ? sanitize_text_field($_POST['parent_slug']) : '';
    $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
    $is_plugin = isset($_POST['is_plugin']) ? sanitize_text_field($_POST['is_plugin']) : TRUE;


    $body_data = array(
      'email' => $email,
      'first_name' => '10Webber',
      'last_name' => rand( 1000, 9999 ),
      'service_key' => 'gTcjslfqqBFFwJKBnFgQYhkQEJpplLaDKfj',
    );

    if ( $is_plugin ) {
      switch ( $parent_slug ) {
        case "manage_fm":
          $body_data['product_id'] = '95';
          break;
        case "wdi_feeds":
          $body_data['product_id'] = '43';
          break;
        case "info_ffwd":
          $body_data['product_id'] = '93';
          break;
        case "sliders_wds":
          $body_data['product_id'] = '97';
          break;
        case "galleries_bwg":
          $body_data['product_id'] = '101';
          break;
        default:
          $body_data['product_slug'] = "plugin_" . $slug;
      }
    } else {
      $body_data['product_slug'] = "theme_" . $slug;
    }

    $args = array(
      'method'      => 'POST',
      'headers'     => array(
        'Content-Type'  => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept'        => 'application/x.10webcore.v1+json'
      ),
      'body'        => $body_data,
    );
    $url = 'https://core.10web.io/api/checkout/signup-via-magic-link';
    $result = wp_remote_post( $url, $args );
    ob_clean();
    if ( !empty($result) && isset( $result['body']) ) {
      $result = $result['body'];
    } else {
      echo json_encode( array('status' => 'error' ) );
      die;
    }

    $result = json_decode($result, 1);
    if ( class_exists('\TenWebOptimizer\OptimizerUtils') ) {
      $args = '';
      if ( isset($result['status']) && isset($result['data']['magic_data']) && $result['status'] == "ok" ) {
        $args = array( 'magic_data' => $result['data']['magic_data'] );
      }
      elseif ( isset($result['error']) && $result['error']['status_code'] == "422" ) {
        $args = array( 'login_request_plugin' => $this->booster->slug );
      }
      if ( $args ) {
        $connect_link = \TenWebOptimizer\OptimizerUtils::get_tenweb_connection_link('login', $args);
        echo json_encode(array( 'status' => 'success', 'booster_connect_url' => $connect_link ));
        die();
      }
    }
    echo json_encode( array('status' => 'error') );
    die();
  }

  /**
   * Connect to dashboard.
   *
   * @return void
   */
  public function connect_to_dashboard() {
    if ( class_exists('\TenWebOptimizer\OptimizerUtils') ) {
      $twb_connect_link = \TenWebOptimizer\OptimizerUtils::get_tenweb_connection_link('login');
      echo json_encode(array( 'status' => 'success', 'booster_connect_url' => $twb_connect_link ));
    }
    else {
      echo json_encode(array( 'status' => 'error' ) );
    }
    die;
  }

  /**
   * Get Google page speed score.
   */
  public function get_google_page_speed() {
    $last_api_key_index = isset($_POST['last_api_key_index']) ? sanitize_text_field($_POST['last_api_key_index']) : '';

    $url = isset($_POST['twb_url']) ? sanitize_url($_POST['twb_url']) : '';

    /* Check if url hasn't http or https add */
    if ( strpos($url, 'http') !== 0 ){
      if ( isset($_SERVER['HTTPS']) ) {
        $url = 'https://'.$url;
      } else {
        $url = 'http://'.$url;
      }
    }

    /* Check if the url is valid */
    if ( !filter_var($url, FILTER_VALIDATE_URL) ) {
      echo json_encode(array('error' => 1)); die;
    }

    $post_id = url_to_postid($url);
    $home_url = get_home_url();
    if ( $post_id !== 0 && get_post_status( $post_id ) != 'publish' && rtrim($url, "/") != rtrim($home_url, "/") ) {
      echo json_encode( array('error' => 1, 'msg' => esc_html__('This page is not public. Please publish the page to check the score.', 'tenweb-booster')) );
      die;
    }

    if ( $last_api_key_index != '' ) {
      /* remove array value as this key already used and no need to try again during the retry */
      unset($this->google_api_keys[$last_api_key_index]);
    }
    $random_index = array_rand( $this->google_api_keys );
    $random_api_key = $this->google_api_keys[$random_index];
    $result = $this->twb_google_speed_cron( $url, 'desktop', $random_api_key );
    if ( !empty($result['error']) || empty($result)) {
      /* Case when retry already done and $last_api_key_index is not empty */
      if ( $last_api_key_index != '' ) {
        echo json_encode(array( 'error' => 1 ));
      }
      else {
        echo json_encode(array( 'error' => 1, 'last_api_key_index' => $random_index ));
      }
      die;
    }
    $score['desktop'] = $result['score']*100;
    $score['desktop_loading_time'] = $result['loading_time'];

    $result = $this->twb_google_speed_cron( $url, 'mobile', $random_api_key );
    if ( !empty($result['error']) || empty($result) ) {
      /* Case when retry already done and $last_api_key_index is not empty */
      if ( $last_api_key_index != '' ) {
        echo json_encode(array( 'error' => 1 ));
      }
      else {
        echo json_encode(array( 'error' => 1, 'last_api_key_index' => $random_index ));
      }
      die;
    }
    $score['mobile'] = $result['score']*100;
    $score['mobile_loading_time'] = $result['loading_time'];

    $nowdate = current_time( 'mysql' );
    $nowdate = date('d.m.Y h:i:s a', strtotime($nowdate));

    $data = get_option('twb_speed_score');
    $data[$url] = array(
      'desktop_score' => $score['desktop'],
      'desktop_loading_time' => $score['desktop_loading_time'],
      'mobile_score' => $score['mobile'],
      'mobile_loading_time' => $score['mobile_loading_time'],
      'last_analyzed_time' => $nowdate,
      'error' => 0
    );
    $data['last'] = array(
      'url' => $url
    );
    update_option('twb_speed_score', $data, 1);

    $twb_hompage_optimized = get_option('twb_hompage_optimized');
    if ( rtrim($url, "/") == rtrim($home_url, "/") && !empty($twb_hompage_optimized) && $twb_hompage_optimized == 1 ) {
      update_option('twb_hompage_optimized', 2);
    }
    ob_clean();
    echo json_encode(array(
                       'desktop_score' => esc_html($score['desktop']),
                       'desktop_loading_time' => esc_html($score['desktop_loading_time']),
                       'mobile_score' => esc_html($score['mobile']),
                       'mobile_loading_time' => esc_html($score['mobile_loading_time']),
                       'last_analyzed_time' => esc_html($nowdate),
                     ));
    die;
  }

  /**
   * Remote get action to get google speed score
   *
   * @param $page_url string which is url of the page which speed should counted
   * @param $strategy string parameter which get desktop or mobile
   *
   * @return array
   */
  public function twb_google_speed_cron( $page_url, $strategy,  $key = 'AIzaSyCQmF4ZSbZB8prjxci3GWVK4UWc-Yv7vbw') {
    $url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . $page_url . "&key=".$key;
    $url = ( $strategy == "mobile" ) ? $url . "&strategy=mobile" : $url;

    $response = wp_remote_get($url, array('timeout' => 300));
    $data = array();

    if (is_array($response) && !is_wp_error($response)) {

      $body = $response['body'];
      $body = json_decode($body);
      if (isset($body->error) ) {
        $data['error'] = 1;
      } else {
        $data['score'] = $body->lighthouseResult->categories->performance->score;
        $data['loading_time'] = $body->lighthouseResult->audits->interactive->displayValue;
      }
    } else {
      $data['error'] = 1;
    }
    return $data;
  }
}
