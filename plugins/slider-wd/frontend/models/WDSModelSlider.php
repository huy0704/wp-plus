<?php
class WDSModelSlider {

  public function get_slide_rows_data($id, $order_dir = 'asc') {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE published=1 AND slider_id="'. $id .'" AND image_url<>"" AND image_url NOT LIKE "%images/no-image.png%" ORDER BY `order` ' . esc_sql($order_dir));
    foreach ($rows as $row) {
      $title_dimension = json_decode($row->title);
      if ($title_dimension) {
        $row->att_width = $title_dimension->att_width;
        $row->att_height = $title_dimension->att_height;
        $row->title = $title_dimension->title;
      }
      else {
        $row->att_width = 0;
        $row->att_height = 0;
      }
      $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
      $row->thumb_url = str_replace('{site_url}', site_url(), $row->thumb_url);
    }
    return $rows;
  }

  public function get_slider_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
    if ( !empty($row) ) {
      if ( $row->music_url != '' && file_exists(str_replace('{site_url}', ABSPATH, $row->music_url)) ) {
        $row->music_url = str_replace('{site_url}', site_url(), $row->music_url);
      }
      else {
        $row->music_url = '';
      }
      $row->right_butt_url = str_replace('{site_url}', site_url(), $row->right_butt_url);
      $row->left_butt_url = str_replace('{site_url}', site_url(), $row->left_butt_url);
      $row->right_butt_hov_url = str_replace('{site_url}', site_url(), $row->right_butt_hov_url);
      $row->left_butt_hov_url = str_replace('{site_url}', site_url(), $row->left_butt_hov_url);
      $row->bullets_img_main_url = str_replace('{site_url}', site_url(), $row->bullets_img_main_url);
      $row->bullets_img_hov_url = str_replace('{site_url}', site_url(), $row->bullets_img_hov_url);
      $row->play_butt_url = str_replace('{site_url}', site_url(), $row->play_butt_url);
      $row->play_butt_hov_url = str_replace('{site_url}', site_url(), $row->play_butt_hov_url);
      $row->paus_butt_url = str_replace('{site_url}', site_url(), $row->paus_butt_url);
      $row->paus_butt_hov_url = str_replace('{site_url}', site_url(), $row->paus_butt_hov_url);
      $row->name = WDW_S_Library::esc_data($row->name, 'esc_html');
      $row->published = (int) WDW_S_Library::esc_data($row->published, 'esc_html');
      $row->full_width = (int) WDW_S_Library::esc_data($row->full_width, 'esc_html');
      $row->auto_height = WDW_S_Library::esc_data($row->auto_height, 'esc_html');
      $row->width = WDW_S_Library::esc_data($row->width, 'esc_html');
      $row->height = WDW_S_Library::esc_data($row->height, 'esc_html');
      $row->align = WDW_S_Library::esc_data($row->align, 'esc_html');
      $row->effect = WDW_S_Library::esc_data($row->effect, 'esc_html');
      $row->time_intervval = (int) WDW_S_Library::esc_data($row->time_intervval, 'esc_html');
      $row->autoplay = (int) WDW_S_Library::esc_data($row->autoplay, 'esc_html');
      $row->shuffle = (int) WDW_S_Library::esc_data($row->shuffle, 'esc_html');
      $row->music = (int) WDW_S_Library::esc_data($row->music, 'esc_html');
      // [music_url]
      $row->preload_images = (int) WDW_S_Library::esc_data($row->preload_images, 'esc_html');
      $row->background_color = WDW_S_Library::esc_data($row->background_color, 'esc_html');
      $row->background_transparent = (int) WDW_S_Library::esc_data($row->background_transparent, 'esc_html');
      $row->glb_border_width = (int) WDW_S_Library::esc_data($row->glb_border_width, 'esc_html');
      $row->glb_border_style = WDW_S_Library::esc_data($row->glb_border_style, 'esc_html');
      $row->glb_border_color = WDW_S_Library::esc_data($row->glb_border_color, 'esc_html');
      $row->glb_margin = (int) WDW_S_Library::esc_data($row->glb_margin, 'esc_html');
      $row->glb_box_shadow = WDW_S_Library::esc_data($row->glb_box_shadow, 'esc_html');
      $row->image_right_click = (int) WDW_S_Library::esc_data($row->image_right_click, 'esc_html');
      $row->layer_out_next = (int) WDW_S_Library::esc_data($row->layer_out_next, 'esc_html');
      $row->prev_next_butt = (int) WDW_S_Library::esc_data($row->prev_next_butt, 'esc_html');
      $row->play_paus_butt = (int) WDW_S_Library::esc_data($row->play_paus_butt, 'esc_html');
      $row->navigation = WDW_S_Library::esc_data($row->navigation, 'esc_html');
      $row->rl_butt_style = WDW_S_Library::esc_data($row->rl_butt_style, 'esc_html');
      $row->rl_butt_size = (int) WDW_S_Library::esc_data($row->rl_butt_size, 'esc_html');
      $row->pp_butt_size = (int) WDW_S_Library::esc_data($row->pp_butt_size, 'esc_html');
      $row->butts_color = WDW_S_Library::esc_data($row->butts_color, 'esc_html');
      $row->butts_transparent = (int) WDW_S_Library::esc_data($row->butts_transparent, 'esc_html');
      $row->hover_color = WDW_S_Library::esc_data($row->hover_color, 'esc_html');
      $row->nav_border_width = WDW_S_Library::esc_data($row->nav_border_width, 'esc_html');
      $row->nav_border_style = WDW_S_Library::esc_data($row->nav_border_style, 'esc_html');
      $row->nav_border_color = WDW_S_Library::esc_data($row->nav_border_color, 'esc_html');
      $row->nav_border_radius = WDW_S_Library::esc_data($row->nav_border_radius, 'esc_html');
      $row->nav_bg_color = WDW_S_Library::esc_data($row->nav_bg_color, 'esc_html');
      $row->bull_position = WDW_S_Library::esc_data($row->bull_position, 'esc_html');
      $row->bull_style = WDW_S_Library::esc_data($row->bull_style, 'esc_html');
      $row->bull_size = (int) WDW_S_Library::esc_data($row->bull_size, 'esc_html');
      $row->bull_color = WDW_S_Library::esc_data($row->bull_color, 'esc_html');
      $row->bull_margin = (int) WDW_S_Library::esc_data($row->bull_margin, 'esc_html');
      $row->film_pos = WDW_S_Library::esc_data($row->film_pos, 'esc_html');
      $row->film_thumb_width = (int) WDW_S_Library::esc_data($row->film_thumb_width, 'esc_html');
      $row->film_thumb_height = (int) WDW_S_Library::esc_data($row->film_thumb_height, 'esc_html');
      $row->film_bg_color = WDW_S_Library::esc_data($row->film_bg_color, 'esc_html');
      $row->film_tmb_margin = (int) WDW_S_Library::esc_data($row->film_tmb_margin, 'esc_html');
      $row->film_act_border_width = (int) WDW_S_Library::esc_data($row->film_act_border_width, 'esc_html');
      $row->film_act_border_style = WDW_S_Library::esc_data($row->film_act_border_style, 'esc_html');
      $row->film_act_border_color = WDW_S_Library::esc_data($row->film_act_border_color, 'esc_html');
      $row->film_dac_transparent = (int) WDW_S_Library::esc_data($row->film_dac_transparent, 'esc_html');
      $row->built_in_watermark_type = WDW_S_Library::esc_data($row->built_in_watermark_type, 'esc_html');
      $row->built_in_watermark_position = WDW_S_Library::esc_data($row->built_in_watermark_position, 'esc_html');
      $row->built_in_watermark_size = (int) WDW_S_Library::esc_data($row->built_in_watermark_size, 'esc_html');
      // [built_in_watermark_url]
      $row->built_in_watermark_text = WDW_S_Library::esc_data($row->built_in_watermark_text, 'esc_html');
      $row->built_in_watermark_font_size = (int) WDW_S_Library::esc_data($row->built_in_watermark_font_size, 'esc_html');
      $row->built_in_watermark_font = WDW_S_Library::esc_data($row->built_in_watermark_font, 'esc_html');
      $row->built_in_watermark_color = WDW_S_Library::esc_data($row->built_in_watermark_color, 'esc_html');
      $row->built_in_watermark_opacity = (int) WDW_S_Library::esc_data($row->built_in_watermark_opacity, 'esc_html');
      $row->css = WDW_S_Library::esc_data($row->css, 'esc_html');
      $row->timer_bar_type = WDW_S_Library::esc_data($row->timer_bar_type, 'esc_html');
      $row->timer_bar_size = (int) WDW_S_Library::esc_data($row->timer_bar_size, 'esc_html');
      $row->timer_bar_color = WDW_S_Library::esc_data($row->timer_bar_color, 'esc_html');
      $row->timer_bar_transparent = (int) WDW_S_Library::esc_data($row->timer_bar_transparent, 'esc_html');
      $row->spider_uploader = (int) WDW_S_Library::esc_data($row->spider_uploader, 'esc_html');
      $row->stop_animation = (int) WDW_S_Library::esc_data($row->stop_animation, 'esc_html');
      // [right_butt_url]
      // [left_butt_url]
      // [right_butt_hov_url]
      // [left_butt_hov_url]
      $row->rl_butt_img_or_not = WDW_S_Library::esc_data($row->rl_butt_img_or_not, 'esc_html');
      // [bullets_img_main_url]
      // [bullets_img_hov_url]
      $row->bull_butt_img_or_not = WDW_S_Library::esc_data($row->bull_butt_img_or_not, 'esc_html');
      $row->play_paus_butt_img_or_not = WDW_S_Library::esc_data($row->play_paus_butt_img_or_not, 'esc_html');
      // [play_butt_url]
      // [play_butt_hov_url]
      // [paus_butt_url]
      // [paus_butt_hov_url]
      $row->start_slide_num = (int) WDW_S_Library::esc_data($row->start_slide_num, 'esc_html');
      $row->effect_duration = (int) WDW_S_Library::esc_data($row->effect_duration, 'esc_html');
      $row->carousel = (int) WDW_S_Library::esc_data($row->carousel, 'esc_html');
      $row->carousel_image_counts = (int) WDW_S_Library::esc_data($row->carousel_image_counts, 'esc_html');
      $row->carousel_image_parameters = WDW_S_Library::esc_data($row->carousel_image_parameters, 'esc_html');
      $row->carousel_width = (int) WDW_S_Library::esc_data($row->carousel_width, 'esc_html');
      $row->parallax_effect = (int) WDW_S_Library::esc_data($row->parallax_effect, 'esc_html');
      $row->mouse_swipe_nav = (int) WDW_S_Library::esc_data($row->mouse_swipe_nav, 'esc_html');
      $row->bull_hover = (int) WDW_S_Library::esc_data($row->bull_hover, 'esc_html');
      $row->touch_swipe_nav = (int) WDW_S_Library::esc_data($row->touch_swipe_nav, 'esc_html');
      $row->mouse_wheel_nav = (int) WDW_S_Library::esc_data($row->mouse_wheel_nav, 'esc_html');
      $row->keyboard_nav = (int) WDW_S_Library::esc_data($row->keyboard_nav, 'esc_html');
      $row->possib_add_ffamily = (int) WDW_S_Library::esc_data($row->possib_add_ffamily, 'esc_html');
      $row->show_thumbnail = (int) WDW_S_Library::esc_data($row->show_thumbnail, 'esc_html');
      $row->thumb_size = WDW_S_Library::esc_data($row->thumb_size, 'esc_html');
      $row->fixed_bg = (int) WDW_S_Library::esc_data($row->fixed_bg, 'esc_html');
      $row->smart_crop = (int) WDW_S_Library::esc_data($row->smart_crop, 'esc_html');
      $row->crop_image_position = WDW_S_Library::esc_data($row->crop_image_position, 'esc_html');
      $row->javascript = WDW_S_Library::esc_data($row->javascript, 'esc_html');
      $row->carousel_degree = (int) WDW_S_Library::esc_data($row->carousel_degree, 'esc_html');
      $row->carousel_grayscale = (int) WDW_S_Library::esc_data($row->carousel_grayscale, 'esc_html');
      $row->carousel_transparency = (int) WDW_S_Library::esc_data($row->carousel_transparency, 'esc_html');
      $row->bull_back_act_color = WDW_S_Library::esc_data($row->bull_back_act_color, 'esc_html');
      $row->bull_back_color = WDW_S_Library::esc_data($row->bull_back_color, 'esc_html');
      $row->bull_radius = WDW_S_Library::esc_data($row->bull_radius, 'esc_html');
      $row->possib_add_google_fonts = (int) WDW_S_Library::esc_data($row->possib_add_google_fonts, 'esc_html');
      $row->slider_loop = (int) WDW_S_Library::esc_data($row->slider_loop, 'esc_html');
      $row->hide_on_mobile = (int) WDW_S_Library::esc_data($row->hide_on_mobile, 'esc_html');
      $row->twoway_slideshow = (int) WDW_S_Library::esc_data($row->twoway_slideshow, 'esc_html');
      $row->full_width_for_mobile = (int) WDW_S_Library::esc_data($row->full_width_for_mobile, 'esc_html');
      $row->order_dir = WDW_S_Library::esc_data($row->order_dir, 'esc_html');
    }

    return $row;
  }

  public function get_layers_row_data($slide_id, $id) {
    global $wpdb;
	  $sql_query = "SELECT layer.* FROM " . $wpdb->prefix . "wdslayer as layer INNER JOIN " . $wpdb->prefix . "wdsslide as slide on layer.slide_id=slide.id INNER JOIN " . $wpdb->prefix . "wdsslider as slider on slider.id=slide.slider_id WHERE layer.slide_id = %d OR (slider.id=%d AND layer.static_layer=1) ORDER BY layer.`depth` ASC";
    $rows = $wpdb->get_results($wpdb->prepare($sql_query, $slide_id, $id));
    foreach ($rows as $row) {
      $title_dimension = json_decode($row->title);
      if ($title_dimension) {
        $row->attr_width = $title_dimension->attr_width;
        $row->attr_height = $title_dimension->attr_height;
        $row->title = $title_dimension->title;
      }
      else {
        $row->attr_width = 0;
        $row->attr_height = 0;
      }
      	$row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
		$row->title = WDW_S_Library::esc_data($row->title, 'esc_html');
		$row->attr_width = WDW_S_Library::esc_data($row->attr_width, 'esc_html');
		$row->attr_height = WDW_S_Library::esc_data($row->attr_height, 'esc_html');
		$row->type = WDW_S_Library::esc_data($row->type, 'esc_html');
		$row->depth = (int) WDW_S_Library::esc_data($row->depth, 'esc_html');
		// [text]
		// [link]
		$row->target_attr_layer = WDW_S_Library::esc_data($row->target_attr_layer, 'esc_html');
		$row->left = WDW_S_Library::esc_data($row->left, 'esc_html');
		$row->top = WDW_S_Library::esc_data($row->top, 'esc_html');
		$row->hide_on_mobile = (int) WDW_S_Library::esc_data($row->hide_on_mobile, 'esc_html');;
		$row->start = WDW_S_Library::esc_data($row->start, 'esc_html');
		$row->end = WDW_S_Library::esc_data($row->end, 'esc_html');
		$row->published = (int) WDW_S_Library::esc_data($row->published, 'esc_html');
		$row->color = WDW_S_Library::esc_data($row->color, 'esc_html');
		$row->size = WDW_S_Library::esc_data($row->size, 'esc_html');
		$row->ffamily = WDW_S_Library::esc_data($row->ffamily, 'esc_html');
		$row->fweight = WDW_S_Library::esc_data($row->fweight, 'esc_html');
		$row->padding = WDW_S_Library::esc_data($row->padding, 'esc_html');
		$row->fbgcolor = WDW_S_Library::esc_data($row->fbgcolor, 'esc_html');
		$row->transparent = WDW_S_Library::esc_data($row->transparent, 'esc_html');
		$row->border_width = WDW_S_Library::esc_data($row->border_width, 'esc_html');
		$row->border_style = WDW_S_Library::esc_data($row->border_style, 'esc_html');
		$row->border_color = WDW_S_Library::esc_data($row->border_color, 'esc_html');
		$row->border_radius = WDW_S_Library::esc_data($row->border_radius, 'esc_html');
		$row->shadow = WDW_S_Library::esc_data($row->shadow, 'esc_html');
		// [image_url]
		$row->image_width = WDW_S_Library::esc_data($row->image_width, 'esc_html');
		$row->image_height = WDW_S_Library::esc_data($row->image_height, 'esc_html');
		$row->image_scale = WDW_S_Library::esc_data($row->image_scale, 'esc_html');
		$row->alt = WDW_S_Library::esc_data($row->alt, 'esc_html');
		$row->imgtransparent = WDW_S_Library::esc_data($row->imgtransparent, 'esc_html');
		$row->social_button = WDW_S_Library::esc_data($row->social_button, 'esc_html');
		$row->hover_color = WDW_S_Library::esc_data($row->hover_color, 'esc_html');
		$row->layer_effect_in = WDW_S_Library::esc_data($row->layer_effect_in, 'esc_html');
		$row->layer_effect_out = WDW_S_Library::esc_data($row->layer_effect_out, 'esc_html');
		$row->duration_eff_in = (int) WDW_S_Library::esc_data($row->duration_eff_in, 'esc_html');
		$row->duration_eff_out = (int) WDW_S_Library::esc_data($row->duration_eff_out, 'esc_html');
		$row->hotp_width = WDW_S_Library::esc_data($row->hotp_width, 'esc_html');
		$row->hotp_fbgcolor = WDW_S_Library::esc_data($row->hotp_fbgcolor, 'esc_html');
		$row->hotp_border_width = WDW_S_Library::esc_data($row->hotp_border_width, 'esc_html');
		$row->hotp_border_style = WDW_S_Library::esc_data($row->hotp_border_style, 'esc_html');
		$row->hotp_border_color = WDW_S_Library::esc_data($row->hotp_border_color, 'esc_html');
		$row->hotp_border_radius = WDW_S_Library::esc_data($row->hotp_border_radius, 'esc_html');
		$row->hotp_text_position = WDW_S_Library::esc_data($row->hotp_text_position, 'esc_html');
		$row->google_fonts = WDW_S_Library::esc_data($row->google_fonts, 'esc_html');
		$row->add_class = WDW_S_Library::esc_data($row->add_class, 'esc_html');
		$row->layer_video_loop = WDW_S_Library::esc_data($row->layer_video_loop, 'esc_html');
		$row->youtube_rel_layer_video = WDW_S_Library::esc_data($row->youtube_rel_layer_video, 'esc_html');
		$row->hotspot_animation = WDW_S_Library::esc_data($row->hotspot_animation, 'esc_html');
		$row->layer_callback_list = WDW_S_Library::esc_data($row->layer_callback_list, 'esc_html');
		$row->hotspot_text_display = WDW_S_Library::esc_data($row->hotspot_text_display, 'esc_html');
		$row->hover_color_text = WDW_S_Library::esc_data($row->hover_color_text, 'esc_html');
		$row->text_alignment = WDW_S_Library::esc_data($row->text_alignment, 'esc_html');
		$row->link_to_slide = (int) WDW_S_Library::esc_data($row->link_to_slide, 'esc_html');
		$row->align_layer = (int) WDW_S_Library::esc_data($row->align_layer, 'esc_html');
		$row->static_layer = (int) WDW_S_Library::esc_data($row->static_layer, 'esc_html');
		$row->infinite_in = (int) WDW_S_Library::esc_data($row->infinite_in, 'esc_html');
		$row->infinite_out = (int) WDW_S_Library::esc_data($row->infinite_out, 'esc_html');
		$row->min_size = (int) WDW_S_Library::esc_data($row->min_size, 'esc_html');
    }
    return $rows;
  }

  public function get_layers_by_slider_id_slide_ids($slider_id, $slide_ids) {
    global $wpdb;
    $sql_query = 'SELECT
              `layer`.*
            FROM
              `'. $wpdb->prefix .'wdslayer` AS `layer`
            INNER JOIN `'. $wpdb->prefix .'wdsslide` AS `slide` ON `layer`.`slide_id` = `slide`.`id`
            INNER JOIN `'. $wpdb->prefix .'wdsslider` AS `slider` ON `slider`.`id` = `slide`.`slider_id`
            WHERE
              `layer`.`slide_id` IN ('. implode( ',', $slide_ids ) .')
            OR (
              `slider`.`id` = '. $slider_id .' AND
              `layer`.`static_layer` = 1
            )
            ORDER BY
              `layer`.`depth` ASC
            ';
    $rows = $wpdb->get_results($sql_query);
    $layers = array();
    if ( !empty($rows) ) {
      foreach ($rows as $row) {
        $row->attr_width = 0;
        $row->attr_height = 0;
        $title_dimension = json_decode($row->title);
        if ($title_dimension) {
          $row->title = $title_dimension->title;
        }

        $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
        $layers[$row->slide_id][] = $row;
      }
    }
    return $layers;
  }
}