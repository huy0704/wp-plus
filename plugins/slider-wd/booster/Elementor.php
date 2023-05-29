<?php

/**
 * Class TWBElementor
 */
class TWBElementor {
  private $booster;
  private $post_published = 0;
  private $post_id = 0;
  private $page_speed_status;

  function __construct( $booster ) {
    $this->booster = $booster;
    add_action('elementor/editor/after_enqueue_scripts', array( $this, 'scripts_styles' ));
    add_action('elementor/documents/register_controls', array( $this,'register_document_controls' ));
  }

  /**
   * Enqueue scripts.
   *
   * @return void
   */
  public function scripts_styles() {
    if ( !$this->post_published ) {
      return;
    }

    wp_enqueue_style('twb-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
    wp_enqueue_style('two_speed_dark_css', $this->booster->plugin_url . '/assets/css/elementor_dark.css', array( 'twb-open-sans', 'elementor-editor-dark-mode' ), TenWebBooster::VERSION);
    if ( $this->booster->cta_button['button_color'] || $this->booster->cta_button['text_color'] ) {
      wp_add_inline_style(TenWebBooster::PREFIX . '-elementor', '.twb-custom-button, .twb-custom-button:hover {background-color: ' . $this->booster->cta_button['button_color'] . ' !important; color: ' . $this->booster->cta_button['text_color'] . ' !important;}');
    }
    wp_enqueue_style(TenWebBooster::PREFIX . '-global', $this->booster->plugin_url . '/assets/css/global.css', array( 'twb-open-sans' ), TenWebBooster::VERSION);

    $required_scripts = array( 'jquery' );
    wp_enqueue_script(TenWebBooster::PREFIX . '-circle', $this->booster->plugin_url . '/assets/js/circle-progress.js', $required_scripts, '1.2.2');
    wp_enqueue_script(TenWebBooster::PREFIX . '-global', $this->booster->plugin_url . '/assets/js/global.js', array('jquery'), TenWebBooster::VERSION);
    wp_localize_script(TenWebBooster::PREFIX . '-global', 'twb', array(
      'title' => $this->booster->cta_button['section_label'],
      'nonce' => wp_create_nonce('twb_nonce'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'plugin_url' => $this->booster->plugin_url,
      'href' => $this->booster->submenu_url,
      'checking' => __('Checking...', 'twb-hidden'),
      'notoptimized' => __('Not optimized', 'twb-hidden'),
    ));
  }

  /**
   * Register additional document controls.
   *
   * @param \Elementor\Core\DocumentTypes\PageBase $document The PageBase document instance.
   */
  public function register_document_controls( $document ) {
    global $post;
    if ( !empty($post) ) {
        $this->post_id = $post->ID;
        if ( get_post_status($this->post_id) == 'publish' ) {
          $this->post_published = 1;
        }
    }

    if ( ! $this->post_published || ! $document instanceof \Elementor\Core\DocumentTypes\PageBase || ! $document::get_property( 'has_elements' ) || !empty($document->get_section_controls('twb_optimize_section')) ) {
      return;
    }

    $section_label = isset($this->booster->cta_button['section_label']) ? $this->booster->cta_button['section_label'] : '';

    \Elementor\Controls_Manager::add_tab('twb_optimize', $section_label);

    $document->start_controls_section(
      'twb_optimize_section',
      [
        'tab' => 'twb_optimize',
      ]
    );
    $this->page_speed_status = TWBLibrary::get_page_speed_status();
    $classname = 'twb_elementor_settings_content twb_optimized';
    if ( $this->page_speed_status == "completed" ) {
        $content = $this->comleted_content();
        $label_html = '<p class="twb_elementor_control_title twb_not_optimized">' . __('Not optimized', 'tenweb-booster') . '</p>';
    } elseif ( $this->page_speed_status == "notstarted" ) {
        $content = $this->notstarted_content();
        $classname = 'twb_elementor_settings_content twb-optimized';
        $label_html = '<p class="twb_elementor_control_title twb_not_optimized">' . __('PageSpeed Score', 'tenweb-booster') . '</p>';
    } elseif ( $this->page_speed_status == 'error' ) {
        $content = $this->error_content();
        $label_html = '<p class="twb_elementor_control_title twb_not_optimized">' . __('Not optimized', 'tenweb-booster') . '</p>';
    } else {
        $content = $this->inprogress_content();
        $label_html = '<p class="twb_elementor_control_title"><span class="twb_inprogress"></span>' . __('Checking...', 'tenweb-booster') . '</p>';
    }
    $content .=  TWBLibrary::dismiss_info_content( $this->booster );
    $document->add_control(
      'twb_raw_html',
      [
        'label' => $label_html,
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => $content,
        'content_classes' => $classname,
      ]
    );

    $document->end_controls_section();
  }

  /**
   * Content html which score counted.
   *
   * @param bool $hide hide content in case of not counted
   *
   * @return string html content
  */
  public function comleted_content( $hide = 0 ) {

    if ( !$this->post_id ) {
      return false;
    }

    $post_id = $this->post_id;
    $page_score = get_post_meta( $post_id, 'two_page_speed' );

    $score = array(
      'mobile_score' => 0,
      'mobile_tti' => 0,
      'desktop_score' => 0,
      'desktop_tti' => 0,
    );

    if ( !empty($page_score) ) {
      $page_score = end($page_score);
      if ( !empty($page_score['previous_score']) && !empty($page_score['previous_score']['mobile_score']) ) {
        $page_score = $page_score['previous_score'];
        $score = array(
          'mobile_score' => $page_score['mobile_score'],
          'mobile_tti' => $page_score['mobile_tti'],
          'desktop_score' => $page_score['desktop_score'],
          'desktop_tti' => $page_score['desktop_tti'],
        );
      }
    }


    $page_title = get_the_title( $post_id );
    $title = sprintf(__('%s page', 'tenweb-booster'), '<i>'.$page_title.'</i>');
    $url = $this->booster->submenu_url;
    ob_start(); ?>
    <script>
      if ( typeof twb_draw_score_circle == 'function') {
        jQuery('.twb-score-circle').each(function () {
          twb_draw_score_circle(this);
        });
      }
    </script>
    <?php
    TWBLibrary::score($score, $url, $post_id, $title, $hide, 40);
    return ob_get_clean();
  }

  /**
   * Content html which score counted with error.
   *
   * @return string html content
  */
  public function error_content() {
    if ( !$this->post_id ) {
      return false;
    }

    $post_id = $this->post_id;
    $page_title = get_the_title( $post_id );
    $title = sprintf(__('%s page', 'tenweb-booster'), '<i>'.$page_title.'</i>');
    $url = $this->booster->submenu_url;

    $score = array(
      'error' => 1,
    );

    ob_start();
    TWBLibrary::score($score, $url, $post_id, $title, 0, 40);
    return ob_get_clean();
  }

  /**
   * Content html which score not counted and started.
   *
   * @return string html content
  */
  public function notstarted_content() {
    if ( !$this->post_id ) {
      return false;
    }

    $post_id = $this->post_id;
    ob_start();
    ?>
    <div class="twb-notoptimized">
      <p class="twb_status_description"><?php _e('PageSpeed score is an essential attribute to your website’s performance. It affects both the user experience and SEO rankings.', 'tenweb-booster') ?></p>
      <div class="twb_check_score_button_cont">
      <a onclick="twb_check_score(this)" data-post_id="<?php echo esc_attr($post_id); ?>"
         data-initiator="admin-bar" target="_blank"
         class="twb_check_score_button"><?php _e('Check PageSpeed Score', 'tenweb-booster') ?></a>
      </div>
    </div>
    <div class="twb-optimized twb-hidden">
      <?php
      echo $this->comleted_content();
      ?>
    </div>
    <div class="twb-optimizing twb-hidden">
      <?php
      echo $this->inprogress_content();
      ?>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Content html which score in progress.
   *
   * @return string html content
  */
  public function inprogress_content() {
    if ( !$this->post_id ) {
      return false;
    }

    $post_id = $this->post_id;
    $page_title = get_the_title( $post_id );
    ob_start();
    ?>
    <div class="twb_admin_bar_menu_content twb-optimizing <?php echo $this->page_speed_status == 'notstarted' ? 'twb-hidden' : ''; ?>">
      <p class="twb_status_description"><?php echo sprintf(__('We are checking the PageSpeed score of your %s page.', 'tenweb-booster'), '<i>'.esc_html($page_title).'</i>'); ?></p>
    </div>
    <?php
    return ob_get_clean();
  }

}
