<?php

function wds_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.2') == -1) {
   // Add spider uploader option.
   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `spider_uploader` tinyint(1) NOT NULL DEFAULT 0");
  }
   if (version_compare($version, '1.0.4') == -1) {
    // Add stop animation on hover and link target options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `stop_animation` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `target_attr_slide` tinyint(1) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `target_attr_layer` tinyint(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.0.5') == -1) {
    // Add right/left button image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `right_butt_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/arrow/arrow11/1/2.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `left_butt_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/arrow/arrow11/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `right_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/arrow/arrow11/1/4.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `left_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/arrow/arrow11/1/3.png' . "'");
    // Whether to display right/left buttons by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `rl_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
    // Add bullets image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bullets_img_main_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/bullet/bullet1/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bullets_img_hov_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/bullet/bullet1/1/2.png' . "'");
    // Whether to display bullets by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
  }
  if (version_compare($version, '1.0.6') == -1) {
    // Add play/pause button image/hover image url.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_butt_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/button/button4/1/1.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `paus_butt_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/button/button4/1/3.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/button/button4/1/2.png' . "'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `paus_butt_hov_url` varchar(255) NOT NULL DEFAULT '" . WDS()->plugin_url . '/images/button/button4/1/4.png' . "'");
    // Whether to display play/pause buttons by image or not.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `play_paus_butt_img_or_not` varchar(8) NOT NULL DEFAULT 'style'");
  }
  if (version_compare($version, '1.0.8') == -1) {
    // Start slider with slide.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `start_slide_num` int(4) NOT NULL DEFAULT 1");
    // Transition effect duration.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `effect_duration` int(6) NOT NULL DEFAULT 800");
  }
  if (version_compare($version, '1.0.11') == -1) {
    // Carousel view options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_image_counts` int(4) NOT NULL DEFAULT 7");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_image_parameters` varchar(8) NOT NULL DEFAULT 0.85");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_fit_containerWidth` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_width` int(4) NOT NULL DEFAULT 1000");
  }
   if (version_compare($version, '1.0.23') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_width` int(4) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_fbgcolor`  varchar(8) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_width` int(4) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_style` varchar(16) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_color` varchar(8) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_border_radius` varchar(32) NOT NULL");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotp_text_position` varchar(6) NOT NULL");
  }
  if (version_compare($version, '1.0.24') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslide` CHANGE `type` `type` varchar(128)");
  }
  if (version_compare($version, '1.0.26') == -1) {
    // Parallax_effect.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `parallax_effect` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.32') == -1) {
    // Mouse swipe navigation.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `mouse_swipe_nav` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.33') == -1) {
    // Show bullets on hover.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_hover` int(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.0.34') == -1) {
    // Google fonts.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `google_fonts` int(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.0.40') == -1) {
    // Navigation methods.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `touch_swipe_nav` tinyint(1) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `mouse_wheel_nav` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `keyboard_nav` tinyint(1) NOT NULL DEFAULT 0");   
  }
  if (version_compare($version, '1.0.41') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslide` CHANGE `title` `title` longtext");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` CHANGE `title` `title` longtext");
  }
  if (version_compare($version, '1.0.42') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `possib_add_ffamily` varchar(255) NOT NULL DEFAULT ''");
  }
  if (version_compare($version, '1.0.43') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `show_thumbnail` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `thumb_size` varchar(8) NOT NULL DEFAULT '0.3'");
  }
  if (version_compare($version, '1.0.47') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `fixed_bg` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.2') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `smart_crop` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `crop_image_position` varchar(16) NOT NULL DEFAULT 'center center'");
  }
  if (version_compare($version, '1.1.7') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `javascript`  text NOT NULL DEFAULT ''");
  }
  if (version_compare($version, '1.1.12') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_degree` int(4) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_grayscale` int(4) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `carousel_transparency` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.14') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_back_act_color` varchar(8) NOT NULL DEFAULT '000000'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_back_color` varchar(8) NOT NULL DEFAULT 'CCCCCC'");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `bull_radius` varchar(32) NOT NULL DEFAULT '20px'");
  }
  if (version_compare($version, '1.1.20') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `add_class` varchar(127) NOT NULL DEFAULT ''");  
  }
  if (version_compare($version, '1.1.26') == -1) { 
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `video_loop` tinyint(1) NOT NULL DEFAULT 0");  
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `youtube_rel_video` tinyint(1) NOT NULL DEFAULT 0"); 
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `layer_video_loop` tinyint(1) NOT NULL DEFAULT 0"); 
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `youtube_rel_layer_video` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotspot_animation` tinyint(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.1.27') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `possib_add_google_fonts` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `possib_add_ffamily_google` varchar(255) NOT NULL DEFAULT ''");    
  }
  if (version_compare($version, '1.1.28') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `slider_loop` tinyint(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.1.29') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `layer_callback_list` varchar(32) NOT NULL DEFAULT ''");
  }
  if (version_compare($version, '1.1.32') == -1) {
     $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hotspot_text_display` varchar(8) NOT NULL DEFAULT 'hover'");
  }
  if (version_compare($version, '1.1.41') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `hover_color_text` varchar(8) NOT NULL DEFAULT ''");
  }
  if (version_compare($version, '1.1.49') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `text_alignment` varchar(8) NOT NULL DEFAULT 'center'");
  }
  if (version_compare($version, '1.1.52') == -1) { 
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `hide_on_mobile` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.54') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` CHANGE `layer_effect_in` `layer_effect_in` varchar(32)");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` CHANGE `layer_effect_out` `layer_effect_out` varchar(32)");
  }
  if (version_compare($version, '1.1.58') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `link_to_slide` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.60') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `twoway_slideshow` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.61') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `align_layer` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.62') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `static_layer` tinyint(1) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.67') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `full_width_for_mobile` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.1.68') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `infinite_in` int(4) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `infinite_out` int(4) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.1.70') == -1) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `order_dir` varchar(4) NOT NULL DEFAULT 'asc'");
  }
  if ( version_compare($version, '1.1.74') == -1 ) {
    $sliders = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdsslider');
    if ( $wpdb->last_error || is_null($sliders) ) {
      return;
    }

    $wds_register_scripts = get_option("wds_register_scripts");
    $loading_gif = get_option("wds_loading_gif", 0);
    delete_option("wds_loading_gif");
    delete_option("wds_register_scripts");

    $possib_add_ffamily = array();
    $possib_add_ffamily_google = array();
    foreach ( $sliders as $slider ) {
      $possib_add_ffamily = array_merge($possib_add_ffamily, explode("*WD*", $slider->possib_add_ffamily));
      $possib_add_ffamily_google = array_merge($possib_add_ffamily_google, explode("*WD*", $slider->possib_add_ffamily_google));
      $spider_uploader = $slider->spider_uploader;
    }
    $possib_add_ffamily = array_unique($possib_add_ffamily);
    $font_family = implode("*WD*", $possib_add_ffamily);
    $possib_add_ffamily_google = array_unique($possib_add_ffamily_google);
    $google_font = implode("*WD*", $possib_add_ffamily_google);
    $global_options = WDW_S_Library::global_options_defults();
    $global_options['loading_gif'] = $loading_gif;
    $global_options['register_scripts'] = $wds_register_scripts;
    $global_options['spider_uploader'] = $spider_uploader;
    $global_options['possib_add_ffamily'] = $font_family;
    $global_options['possib_add_ffamily_google'] = $google_font;
    $global_options = json_encode($global_options);
    update_option('wds_global_options', $global_options);
  }
  if (version_compare($version, '1.1.77') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `min_size` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.2.0') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ALTER `infinite_in` SET DEFAULT 1;");
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ALTER `infinite_out` SET DEFAULT 1;");
  }
  if (version_compare($version, '1.2.2') == -1) {
	$wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `fillmode` varchar(10) NOT NULL DEFAULT '';");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `auto_height` tinyint(1) NOT NULL DEFAULT 0");
    $query = "SELECT * FROM " . $wpdb->prefix . "wdsslider";
    $sliders = $wpdb->get_results( $query );
    if ( !empty($sliders) ) {
      foreach ( $sliders as $slider ) {
        $fillmode = 'fill';
        if( !empty($slider->bg_fit) ) {
          if ( $slider->bg_fit == 'cover') {
            $fillmode = 'fill';
          }
          if ( $slider->bg_fit == '100% 100%') {
            $fillmode = 'stretch';
          }
          if ( $slider->bg_fit == 'contain') {
            $fillmode = 'fit';
          }
        }
        $wpdb->query("UPDATE " . $wpdb->prefix . "wdsslide SET `fillmode`='" . $fillmode . "' WHERE `slider_id`='" . $slider->id . "'");
      }
    }
  }
  if (version_compare($version, '1.2.30') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslider` ADD `film_small_screen` int(4) NOT NULL DEFAULT 0");
  }
  if (version_compare($version, '1.2.33') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdsslide` ADD `mute` tinyint(1) NOT NULL DEFAULT 1");
  }
  if (version_compare($version, '1.2.42') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "wdslayer` ADD `hide_on_mobile` int(4) NOT NULL DEFAULT 0");
  }

  return;
}
