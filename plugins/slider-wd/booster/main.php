<?php
class TenWebBooster {
  const VERSION = '1.0.0';
  const PREFIX = 'twb';
  const BOOSTER_PlUGIN_FILE = 'tenweb-speed-optimizer/tenweb_speed_optimizer.php';

  public $submenu = array(
    'parent_slug' => FALSE,
    'title' => 'Speed & Image Optimization',
    'icon' => '',
  );
  public $page = array(
    'section_booster_title' => 'Optimize images and boost PageSpeed score',
    'section_booster_desc' => 'Use the free 10Web Booster plugin to automatically optimize your images and boost PageSpeed score.',
    'section_booster_success_title' => 'Optimize all images',
    'section_booster_success_desc' => 'Speed up the entire website',
    'section_optimize_images' => TRUE,
    'section_analyze' => TRUE,
    'section_analyze_desc' => 'Speed up your website and increase PageSpeed score by optimizing all images.',
  );

  public $cta_button = array(
    'section_label' => 'PageSpeed score',
    'label' => 'Check PageSpeed score',
    'button_color' => '',
    'text_color' => '',
    'class' => 'twb-green-button',
    );
  private $admin_bar = TRUE;
  private $gutenberg = TRUE;
  private $elementor = TRUE;
  public $show_cta_option = TRUE;
  public $show_cta = TRUE;
  private $list = TRUE;

  public $slug = '';
  public $plugin_dir = '';
  public $plugin_url = '';
  public $booster_plugin_status = 0; //0-not installed, 1-not active, 2-active
  public $booster_is_connected = FALSE;
  public $is_paid = FALSE;
  public $subscription_id = FALSE;
  public $status = 'not_installed';
  /* is booster SDK called from plugin or theme */
  public $is_plugin = TRUE;
  public $submenu_url = '';

  public function __construct($params = array()) {
    $this->define_params($params);
    $this->change_defaults($params);
    $this->set_booster_data();
    $this->add_actions();
  }

  private function add_actions() {
    add_action('init', array( $this, 'register_meta' ));
    add_action('admin_enqueue_scripts', array( $this, 'register_admin_scripts' ));
    add_action('wp_enqueue_scripts', array( $this, 'register_scripts' ));
    if ( $this->submenu['parent_slug'] || !$this->is_plugin ) {
      add_action('admin_menu', array( $this, 'add_submenu' ));
    }
    add_action('wp_ajax_' . self::PREFIX, array( $this, 'admin_ajax' ));
    add_action('wp_ajax_twb_check_score', array( $this, 'check_score' ));
    add_action('wp_ajax_twb_notif_check', array( $this, 'notif_check' ));

    /* Booster is inactive and (CTAs are enabled from options or option desabled at all).*/
    if ( $this->booster_plugin_status != 2 && ($this->show_cta || !$this->show_cta_option) ) {
      if ( $this->admin_bar ) {
        add_action('admin_bar_menu', array( $this, 'admin_bar_menu' ), 100);
      }

      if ( $this->elementor ) {
        require_once('Elementor.php');
        new TWBElementor($this);
      }

      if ( $this->gutenberg ) {
        require_once('Gutenberg.php');
        new TWBGutenberg($this);
      }

      if ( $this->list && !class_exists('TWBBWGList') ) {
        require_once('List.php');
        $this->list = TWBList($this);
      }
    }
  }

  public function admin_ajax() {
    $speed_ajax_nonce = isset($_POST['speed_ajax_nonce']) ? sanitize_text_field($_POST['speed_ajax_nonce']) : '';
    if ( !wp_verify_nonce($speed_ajax_nonce, 'speed_ajax_nonce') ){
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb' );
    if ( !in_array($page, $allowed_pages) ) {
      return;
    }
    $this->admin_page();
  }

  public function check_score() {
    $twb_nonce = isset($_POST['twb_nonce']) ? sanitize_text_field($_POST['twb_nonce']) : '';
    if ( !wp_verify_nonce($twb_nonce, 'twb_nonce') ){
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb_check_score' );
    if ( !in_array($page, $allowed_pages) ) {
      return;
    }
    $post_id = isset($_POST["post_id"]) ? sanitize_text_field($_POST["post_id"]) : 0;

    echo TWBLibrary::check_score($post_id);
    die();
  }

  /* Ajax action which is checking if score count in progress/complete to show notification */
  public function notif_check() {
    $twb_nonce = isset($_POST['twb_nonce']) ? sanitize_text_field($_POST['twb_nonce']) : '';
    if ( !wp_verify_nonce($twb_nonce, 'twb_nonce') ){
      die('Permission Denied.');
    }

    if ( !isset($_POST['action']) ) {
      return;
    }
    $page = sanitize_text_field($_POST['action']);
    $allowed_pages = array( 'twb_notif_check' );
    if ( !in_array($page, $allowed_pages) ) {
      return;
    }

    require_once('AdminBar.php');
    new TWBAdminBar('', $this);
  }

  private function define_params($params) {
    require_once('TWBLibrary.php');
    $submenu_icon_styles = array(
      'background-color: #22B339',
      'border-radius: 20px',
      'display: inline-block',
      'height: 6px',
      'margin: 0 0 1px 3px',
      'width: 6px',
    );
    $this->submenu['icon'] = '<span class="' . self::PREFIX . '-submenu-icon" style="' . implode(';', $submenu_icon_styles) . '"></span>';
    if ( isset($params['page']['slug']) ) {
      $this->slug = $params['page']['slug'];
    }
    else {
      if ( isset($params['is_plugin']) && !$params['is_plugin'] ) {
        $path = explode('/themes/', get_template_directory());
        $this->slug = isset($path[1]) ? $path[1] : 'theme';
      }
      else {
        $this->slug = str_replace(array( 'booster', '/' ), array( '', '' ), plugin_basename(dirname(__FILE__)));
      }
    }
    $admin_page = admin_url((isset($params['is_plugin']) && !$params['is_plugin']) ? 'themes.php' : 'admin.php');
    $this->submenu_url = add_query_arg( array('page' => TenWebBooster::PREFIX . '_' . $this->slug), $admin_page );
  }

  private function change_defaults( $params = array()) {
    foreach ( $params as $key => $param ) {
      if ( isset($this->$key) ) {
        if ( is_array($param) ) {
          foreach ( $param as $par_key => $par ) {
            $param[$par_key] = sanitize_text_field($par);
          }
          $this->$key = array_merge($this->$key, $param);
        }
        else {
          $this->$key = sanitize_text_field($param);
        }
      }
    }
  }

  public function register_scripts( ) {
    $required_scripts = array( 'jquery' );
    $required_styles = array( 'twb-open-sans' );
    wp_register_style('twb-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
    wp_register_style(self::PREFIX . '-global', $this->plugin_url . '/assets/css/global.css', $required_styles, self::VERSION);
    if ( $this->cta_button['button_color'] || $this->cta_button['text_color'] ) {
      $cutom_css = "
        #wp-admin-bar-booster-top-button a.ab-item, 
        #wp-admin-bar-twb_adminbar_info .twb_admin_bar_menu_header:not(.twb_not_optimized), 
        #wp-admin-bar-booster-top-button a.ab-item:hover,
        #wpadminbar:not(.mobile) .ab-top-menu>#wp-admin-bar-booster-top-button:hover>.ab-item {
          background-color: " . $this->cta_button['button_color'] . " !important; 
          color: " . $this->cta_button['text_color'] . " !important;
      }";
      wp_add_inline_style(self::PREFIX . '-global', $cutom_css);
    }
    wp_register_script(self::PREFIX . '-circle', $this->plugin_url . '/assets/js/circle-progress.js', $required_scripts, '1.2.2');
    array_push($required_scripts, self::PREFIX . '-circle');
    wp_register_script(self::PREFIX . '-global', $this->plugin_url . '/assets/js/global.js', $required_scripts, self::VERSION);
    wp_localize_script(self::PREFIX . '-global', 'twb', array(
      'nonce' => wp_create_nonce('twb_nonce'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'plugin_url' => $this->plugin_url,
      'href' => $this->submenu_url,
    ));
  }

  public function add_submenu() {
    if ( $this->is_plugin ) {
      add_submenu_page(
        $this->submenu['parent_slug'],
        $this->submenu['title'],
        $this->submenu['title'] . $this->submenu['icon'],
        'manage_options',
        self::PREFIX . '_' . $this->slug,
        array($this, 'admin_page'),
        1
      );
    }
    else {
      add_theme_page(
        $this->submenu['title'],
        $this->submenu['title'] . $this->submenu['icon'],
        'manage_options',
        self::PREFIX . '_' . $this->slug,
        array($this, 'admin_page')
      );
    }
  }

  /**
   * Display page.
   */
  public function admin_page() {
    require_once($this->plugin_dir . '/controller.php');
    $controller = new BoosterController($this);
    $controller->execute();
  }

  /**
   * Top banner.
   *
   * @param $params
   */
  public function top_banner($params = array()) {
    require_once($this->plugin_dir . '/controller.php');
    $controller = new BoosterController($this);
    $controller->execute('top_banner', $params);
  }

  /**
   * Admin bar menu.
   *
   * @param $wp_admin_bar
   */
  public function admin_bar_menu( $wp_admin_bar ) {
    require_once('AdminBar.php');
    new TWBAdminBar($wp_admin_bar, $this);
  }

  /**
   * Set the data for Booster.
   *
   * @return void
   */
  public function set_booster_data() {
    $this->show_cta = get_option("twb_show_cta", TRUE);
    $this->subscription_id = get_transient('tenweb_subscription_id');
    $this->booster_plugin_status = $this->get_booster_status();

    if ( ( defined('TENWEB_CONNECTED_SPEED') &&
        class_exists('\Tenweb_Authorization\Login') &&
        \Tenweb_Authorization\Login::get_instance()->check_logged_in() &&
        \Tenweb_Authorization\Login::get_instance()->get_connection_type() == TENWEB_CONNECTED_SPEED ) ||
      ( defined('TENWEB_SO_HOSTED_ON_10WEB') && TENWEB_SO_HOSTED_ON_10WEB ) ) {
      $this->booster_is_connected = TRUE;

      if ( method_exists('\TenWebOptimizer\OptimizerUtils', 'is_paid_user')
        && TenWebOptimizer\OptimizerUtils::is_paid_user() ) {
        $this->is_paid = TRUE;
      }
    }

    if ( $this->booster_plugin_status == 2 ) {
      if ( $this->booster_is_connected ) {
        $this->status = 'connected';
      }
      else {
        if ( $this->subscription_id ) {
          $this->status = 'connect';
        } else {
          $this->status = 'sign_up';
        }
      }
    }
    return $this;
  }

  /**
   * Check the plugin status.
   *
   * @return int 0-not installed, 1-not active, 2-active
   */
  private function get_booster_status() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_plugin_active(self::BOOSTER_PlUGIN_FILE) ) {
      return 2;
    }
    elseif ( $this->is_plugin_installed() ) {
      return 1;
    }
    else {
      return 0;
    }
  }

  /**
   * Check if the plugin already installed.
   *
   * @param string $slug plugin's slug
   *
   * @return bool
   */
  private function is_plugin_installed() {
    if ( ! function_exists( 'get_plugins' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $all_plugins = get_plugins();

    return !empty($all_plugins[self::BOOSTER_PlUGIN_FILE]);
  }

  public function register_admin_scripts() {
    $required_scripts = array( 'jquery' );
    $required_styles = array('twb-open-sans');
    wp_register_style('twb-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
    wp_register_style(self::PREFIX . '-global', $this->plugin_url . '/assets/css/global.css', $required_styles, self::VERSION);

    wp_register_script(self::PREFIX . '-circle', $this->plugin_url . '/assets/js/circle-progress.js', $required_scripts, '1.2.2');
    array_push($required_scripts, self::PREFIX . '-circle');
    wp_register_script(self::PREFIX . '-global', $this->plugin_url . '/assets/js/global.js', $required_scripts, self::VERSION);
    wp_localize_script(self::PREFIX . '-global', 'twb', array(
      'nonce' => wp_create_nonce('twb_nonce'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'cta_button' => $this->cta_button,
      'href' => $this->submenu_url,
    ));
    wp_register_style(self::PREFIX, $this->plugin_url . '/assets/css/speed.css', $required_styles, self::VERSION);
    wp_register_style(self::PREFIX . '-top-banner', $this->plugin_url . '/assets/css/top_banner.css', $required_styles, self::VERSION);
    wp_register_script(self::PREFIX . '-script', $this->plugin_url . '/assets/js/speed.js', $required_scripts, self::VERSION);
    wp_localize_script(self::PREFIX . '-script', 'twb', array(
      'install_button_text' => __('Install 10Web Booster plugin', 'tenweb-booster'),
      'activate_button_text' => __('Activate 10Web Booster plugin', 'tenweb-booster'),
      'wrong_email' => __('Please enter a valid email address.', 'tenweb-booster'),
      'empty_email' => __('Email field should not be empty.', 'tenweb-booster'),
      'wrong_domain_url' => __('Please enter a URL from your domain.', 'tenweb-booster'),
      'wrong_url' => __('Please enter correct URL.', 'tenweb-booster'),
      'enter_page_url' => __('Please enter a Page URL.', 'tenweb-booster'),
      'page_is_not_public' => __('This page is not public. Please publish the page to check the score.', 'tenweb-booster'),
      'sign_up' => __('Sign up', 'tenweb-booster'),
      'connect' => __('Connect', 'tenweb-booster'),
      'home_url' => get_home_url(),
      'home_speed_status' => $this->check_home_speed_status(),
      'analyze_button_text' => __('Analyze', 'tenweb-booster'),
      'something_wrong' => __('Something went wrong, please try again.', 'tenweb-booster'),
      'speed_ajax_nonce' => wp_create_nonce('speed_ajax_nonce'),
      'compressed_pages_status' => $this->compressed_pages_status(),
      'nonce' => wp_create_nonce('twb_nonce'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'cta_button' => $this->cta_button,
      'href' => $this->submenu_url,
    ));
  }

  /**
   * Check if data of optimized pages/images kept in DB
   * Using to send localized status to js and run ajax if needed to get data from endpoint
   *
   * @return bool
  */
  public function compressed_pages_status() {
    $data = get_transient( 'twb_optimized_pages' );
    if( !empty( $data) ) {
      return 1;
    }
    return 0;
  }

  public function check_home_speed_status() {
    $twb_hompage_optimized = get_option('twb_hompage_optimized');
    /* Case when homepage optimized but score not updated */
    if ( !empty($twb_hompage_optimized) && $twb_hompage_optimized == 1 ) {
      return 0;
    }
    $twb_speed_score = get_option('twb_speed_score');
    if ( !empty($twb_speed_score) && isset($twb_speed_score['last']) && isset($twb_speed_score['last']['url']) ) {
      $url = $twb_speed_score['last']['url'];
      if ( isset($twb_speed_score[$url]) && $twb_speed_score[$url]['desktop_score'] && $twb_speed_score[$url]['mobile_score'] ) {
        return array(
          'desktop_score' => $twb_speed_score[$url]['desktop_score'],
          'mobile_score' => $twb_speed_score[$url]['mobile_score'],
        );
      }
    }

    return 0;
  }

  public function register_meta() {
    $allowed_post_types = array('post', 'page');
    foreach ($allowed_post_types as $type) {
      register_post_meta($type, 'two_page_speed', [
        'show_in_rest' => array(
          'schema' => array(
            'type' => 'object',
            'properties' => array(
              'previous_score' => array(
                'type' => 'object',
                'properties' => array(
                  'desktop_score' => array(
                    'type' => 'number',
                  ),
                  'desktop_tti' => array(
                    'type' => 'string',
                  ),
                  'mobile_score' => array(
                    'type' => 'number',
                  ),
                  'mobile_tti' => array(
                    'type' => 'string',
                  ),
                  'date' => array(
                    'type' => 'string',
                  ),
                ),
              ),
              'current_score' => array(
                'type' => 'object',
                'properties' => array(
                  'desktop_score' => array(
                    'type' => 'number',
                  ),
                  'desktop_tti' => array(
                    'type' => 'string',
                  ),
                  'mobile_score' => array(
                    'type' => 'number',
                  ),
                  'mobile_tti' => array(
                    'type' => 'string',
                  ),
                  'date' => array(
                    'type' => 'string',
                  ),
                ),
              ),
            ),
          ),
        ),
        'single' => TRUE,
        'type' => 'object'
      ]);
    }
  }
}
