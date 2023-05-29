<?php

class WDSElementor extends \Elementor\Widget_Base {
  /**
   * Get widget name.
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'wds-elementor';
  }

  /**
   * Get widget title.
   *
   * @return string Widget title.
   */
  public function get_title() {
    return __('Slider', WDS()->prefix);
  }

  /**
   * Get widget icon.
   *
   * @return string Widget icon.
   */
  public function get_icon() {
    return 'twbb-slider-wd twbb-widget-icon';
  }

  /**
   * Get widget categories.
   *
   * @return array Widget categories.
   */
  public function get_categories() {
    return [ 'tenweb-plugins-widgets' ];
  }

  /**
   * Register widget controls.
   */
  protected function _register_controls() {
    $this->start_controls_section(
      'general',
      [
        'label' => __('Slider', WDS()->prefix),
      ]
    );

    if($this->get_id() !== null){
      $settings = $this->get_init_settings();
    }
    $wds_edit_link = add_query_arg(array( 'page' => 'sliders_' . WDS()->prefix ), admin_url('admin.php'));
    if(isset($settings) && isset($settings["sliders"]) && intval($settings["sliders"])>0){
      $wds_id = intval($settings["sliders"]);
      $wds_edit_link = add_query_arg(array( 'page' => 'sliders_' . WDS()->prefix, 'task'=>'edit', 'current_id'=>$wds_id ), admin_url('admin.php'));
    }

    $sliders = wds_get_sliders();
    $sliders[0] = __('Select a Slider', WDS()->prefix);
    $this->add_control(
      'sliders',
      [
        'label_block' => TRUE,
        'show_label' => FALSE,
        'description' => __('Select the slider to display.', WDS()->prefix) . ' <a target="_blank" href="' . $wds_edit_link . '">' . __('Edit slider', WDS()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 0,
        'options' => $sliders,
      ]
    );

    $this->end_controls_section();
  }
  /**
   * Render widget output on the frontend.
   */
  protected function render() {
    $settings = $this->get_settings_for_display();

    if ( doing_filter('wd_seo_sitemap_images') || doing_filter('wpseo_sitemap_urlimages') ) {
      WDW_S_Sitemap::instance()->shortcode($settings['sliders']);
    }
    else {
      echo WDS()->front_end($settings['sliders']);
    }
  }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new WDSElementor());
