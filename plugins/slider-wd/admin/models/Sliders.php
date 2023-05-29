<?php

/**
 * Class SlidersModel_wds
 */
class SlidersModel_wds {
  /**
   * Get slides row data.
   *
   * @param $slider_id
   *
   * @return array
   */
	public function get_slides_row_data($slider_id) {
		global $wpdb;
		$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id));
		if ( !$rows ) {
		  $rows = array();
		}
		else {
      foreach ( $rows as $row ) {
        $row->image_url = $row->image_url ? str_replace('{site_url}', site_url(), $row->image_url) : WDS()->plugin_url . '/images/no-image.png';
        $row->thumb_url = $row->thumb_url ? str_replace('{site_url}', site_url(), $row->thumb_url) : WDS()->plugin_url . '/images/no-image.png';
        $title_dimension = json_decode($row->title);
        if ( $title_dimension ) {
          $row->att_width = isset($title_dimension->att_width) ? $title_dimension->att_width : 0;
          $row->att_height = isset($title_dimension->att_height) ? $title_dimension->att_height : 0;
          $row->video_duration = isset($title_dimension->video_duration) ? $title_dimension->video_duration : 0;
          $row->title = isset($title_dimension->title) ? $title_dimension->title : '';
        }
        else {
          $row->att_width = 0;
          $row->att_height = 0;
          $row->video_duration = 0;
        }
        $row->title = WDW_S_Library::esc_data($row->title, 'esc_html');
		  }
		}
		return $rows;
	}

  /**
   * Get layers row data.
   *
   * @param array $slide_ids
   *
   * @return mixed
   */
	public function get_layers_row_data( $slide_ids = array() ) {
		global $wpdb;
		$rows = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdslayer` WHERE `slide_id` IN (' . implode(',', $slide_ids) . ') ORDER BY `depth` ASC');
		$data = array();
		if ( !empty($rows) ) {
			foreach ( $rows as $row ) {
			  $row->image_url = $row->image_url ? str_replace('{site_url}', site_url(), $row->image_url) : WDS()->plugin_url . '/images/no-image.png';
			  $title_dimension = json_decode($row->title);
        if ( $title_dimension ) {
          $row->attr_width = $title_dimension->attr_width;
          $row->attr_height = $title_dimension->attr_height;
          $row->title = $title_dimension->title;
			  }
        else {
          $row->attr_width = 0;
          $row->attr_height = 0;
			  }
			  foreach ( $row as $key => $field ) {
			    if ( $key != 'text' && $key != 'link' && $key != 'image_url' ) {
            $row->$key = WDW_S_Library::esc_data($field, 'esc_html');
          }
        }

			  $data[$row->slide_id][] = $row;
			}
		}
		return $data;
	}

  /**
   * Get rows data.
   *
   * @param array $params
   *
   * @return array
   */
	public function get_rows_data( $params= array() ) {
		$order = $params['order'];
		$orderby = $params['orderby'];
		$page_per = $params['items_per_page'];
		$page_num = $params['page_num'];
		$search = $params['search'];

		global $wpdb;
		$where = !empty($search) ? ' WHERE `name` LIKE "%' . $search . '%"' : '';

		$query  = 'SELECT * FROM ' . $wpdb->prefix . 'wdsslider' . $where;
		$query .= ' ORDER BY `' . $orderby . '` ' . $order;
		$query .= ' LIMIT ' . $page_num . ',' . $page_per;
		$rows = $wpdb->get_results($query);
		$query1 = 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'wdsslider' . $where;
		$total = $wpdb->get_var($query1);
		$data = array();
		$data['rows']  = $rows;
		$data['total'] = $total;
		return $data;
	}

  /**
   *  Get row data.
   *
   * @param $id
   * @param $reset
   *
   * @return stdClass
   */
	public function get_row_data( $id, $reset) {
		global $wpdb;
		if ( $id != 0 && !$reset ) {
		  $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
      if ( $row ) {
        $row->enable_bullets = $row->bull_position == 'none' ? 0 : 1;
        $row->enable_filmstrip = $row->film_pos == 'none' ? 0 : 1;
        $row->film_small_screen = (isset($row->film_small_screen)) ? $row->film_small_screen : 0;
        $row->enable_time_bar = $row->timer_bar_type == 'none' ? 0 : 1;
        $row->music_url = str_replace('{site_url}', site_url(), $row->music_url);
        $row->built_in_watermark_url = str_replace('{site_url}', site_url(), $row->built_in_watermark_url);
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

        foreach ( $row as $key => $field ) {
          if ( $key != 'music_url'
            && $key != 'built_in_watermark_url'
            && $key != 'right_butt_url'
            && $key != 'left_butt_url'
            && $key != 'right_butt_hov_url'
            && $key != 'left_butt_hov_url'
            && $key != 'bullets_img_main_url'
            && $key != 'bullets_img_hov_url'
            && $key != 'play_butt_url'
            && $key != 'play_butt_hov_url'
            && $key != 'paus_butt_url'
            && $key != 'paus_butt_hov_url' ) {
            $row->$key = WDW_S_Library::esc_data($field, 'esc_html');
          }
        }
		  }
		}
		else {
		  $row = new stdClass();
		  if ($reset && $id) {
			  $row = $wpdb->get_row($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
		  }
		  else {
			  $row->name = '';
		  }
		  $row->id = $id;
		  $row->width = 900;
		  $row->height = 400;
		  $row->full_width = 2;
		  $row->auto_height = 0;
		  $row->align = 'center';
		  $row->effect = 'fade';
		  $row->published = 1;
		  $row->time_intervval = 5;
		  $row->autoplay = 1;
		  $row->shuffle = 0;
		  $row->music = 0;
		  $row->music_url = '';
		  $row->preload_images = 1;
		  $row->background_color = '000000';
		  $row->background_transparent = 100;
		  $row->glb_border_width = 0;
		  $row->glb_border_style = 'none';
		  $row->glb_border_color = '000000';
		  $row->glb_border_radius = '';
		  $row->glb_margin = 0;
		  $row->glb_box_shadow = '';
		  $row->image_right_click = 0;
		  $row->layer_out_next = 0;
		  $row->prev_next_butt = 1;
		  $row->play_paus_butt = 0;
		  $row->navigation = 'hover';
		  $row->rl_butt_style = 'fa-angle';
		  $row->rl_butt_size = 40;
		  $row->pp_butt_size = 40;
		  $row->butts_color = '000000';
		  $row->hover_color = '000000';
		  $row->nav_border_width = 0;
		  $row->nav_border_style = 'none';
		  $row->nav_border_color = 'FFFFFF';
		  $row->nav_border_radius = '20px';
		  $row->nav_bg_color = 'FFFFFF';
		  $row->butts_transparent = 100;
		  $row->enable_bullets = 1;
		  $row->bull_position = 'bottom';
		  $row->bull_style = 'fa-square-o';
		  $row->bull_size = 20;
		  $row->bull_color = 'FFFFFF';
		  $row->bull_act_color = 'FFFFFF';
		  $row->bull_margin = 3;
		  $row->enable_filmstrip = 0;
		  $row->film_small_screen = 0;
		  $row->film_pos = 'none';
		  $row->film_thumb_width = 100;
		  $row->film_thumb_height = 50;
		  $row->film_bg_color = '000000';
		  $row->film_tmb_margin = 0;
		  $row->film_act_border_width = 0;
		  $row->film_act_border_style = 'none';
		  $row->film_act_border_color = 'FFFFFF';
		  $row->film_dac_transparent = 50;
		  $row->enable_time_bar = 1;
		  $row->timer_bar_type = 'top';
		  $row->timer_bar_size = 5;
		  $row->timer_bar_color = 'BBBBBB';
		  $row->timer_bar_transparent = 50;
		  $row->built_in_watermark_type = 'none';
		  $row->built_in_watermark_position = 'middle-center';
		  $row->built_in_watermark_size = 15;
		  $row->built_in_watermark_url = WDS()->plugin_url . '/images/watermark.png';
		  $row->built_in_watermark_text = '10Web.io';
		  $row->built_in_watermark_font_size = 20;
		  $row->built_in_watermark_font = '';
		  $row->built_in_watermark_color = 'FFFFFF';
		  $row->built_in_watermark_opacity = 70;
		  $row->stop_animation = 0;
		  $row->css = '';
		  $row->right_butt_url = WDS()->plugin_url . '/images/arrow/arrow11/1/2.png';
		  $row->left_butt_url = WDS()->plugin_url . '/images/arrow/arrow11/1/1.png';
		  $row->right_butt_hov_url = WDS()->plugin_url . '/images/arrow/arrow11/1/4.png';
		  $row->left_butt_hov_url = WDS()->plugin_url . '/images/arrow/arrow11/1/3.png';
		  $row->rl_butt_img_or_not = 'style';
		  $row->bullets_img_main_url = WDS()->plugin_url . '/images/bullet/bullet1/1/1.png';
		  $row->bullets_img_hov_url = WDS()->plugin_url . '/images/bullet/bullet1/1/2.png';
		  $row->bull_butt_img_or_not = 'style';
		  $row->play_paus_butt_img_or_not = 'style';
		  $row->play_butt_url = WDS()->plugin_url . '/images/button/button4/1/1.png';
		  $row->play_butt_hov_url = WDS()->plugin_url . '/images/button/button4/1/2.png';
		  $row->paus_butt_url = WDS()->plugin_url . '/images/button/button4/1/3.png';
		  $row->paus_butt_hov_url = WDS()->plugin_url . '/images/button/button4/1/4.png';
		  $row->start_slide_num = 1;
		  $row->effect_duration = 800;
		  $row->carousel = 0;
		  $row->carousel_image_counts = 7;
		  $row->carousel_image_parameters = 0.85;
		  $row->carousel_fit_containerWidth = 0;
		  $row->carousel_width = 1000;
		  $row->parallax_effect = 0;
		  $row->mouse_swipe_nav = 0;
		  $row->bull_hover = 1;
		  $row->touch_swipe_nav = 1;
		  $row->mouse_wheel_nav = 0;
		  $row->keyboard_nav = 0;
		  $row->possib_add_ffamily = '';
		  $row->show_thumbnail = 0;
		  $row->thumb_size = '0.3';
		  $row->fixed_bg = 0;
		  $row->smart_crop = 0;
		  $row->crop_image_position = 'center center';
		  $row->javascript = '';
		  $row->carousel_degree = 0;
		  $row->carousel_grayscale = 0;
		  $row->carousel_transparency = 0;
		  $row->bull_back_act_color = '000000';
		  $row->bull_back_color = 'CCCCCC';
		  $row->bull_radius = '20px';
		  $row->possib_add_google_fonts = 0;
		  $row->possib_add_ffamily_google = '';
		  $row->slider_loop = 1;
		  $row->hide_on_mobile = 0;
		  $row->twoway_slideshow = 0;
		  $row->full_width_for_mobile = 0;
		  $row->order_dir = 'asc';
		}
		return $row;
	}

	/**
	* Create Preview Slider post.
	*
	* @return string $guid
	*/
	public function get_slide_preview_post() {
		$post_type = 'wds-slider';
		$row = get_posts(array( 'post_type' => $post_type ));
		if ( !empty($row[0]) ) {
		  return get_post_permalink($row[0]->ID);
		}
		else {
		  $post_params = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_content' => '[SliderPreview]',
			'post_title' => 'Preview',
			'post_type' => 'wds-slider',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_parent' => 0,
			'menu_order' => 0,
			'import_id' => 0,
		  );
		  // Create new post by type.
		  $insert_id = wp_insert_post($post_params);
		  if ( !is_wp_error($insert_id) ) {
			flush_rewrite_rules();
			return get_post_permalink($insert_id);
		  }
		  else {
			return "";
		  }
		}
	}

	/**
	* Publish.
	*
	* @param      $id
	* @param bool $all
	*
	* @return int
	*/
	public function publish( $id, $all = FALSE ) { 
		global $wpdb;
		$where = ($all ? '' : ' WHERE id=' . $id);
		$updated = $wpdb->query('UPDATE `' . $wpdb->prefix . 'wdsslider` SET `published` = 1' . $where);

		$message_id = 2;
		if ( $updated !== FALSE ) {
		  $message_id = 9;
		}
		return $message_id;
	}

	/**
	* Unpublish.
	*
	* @param      $id
	* @param bool $all
	*
	* @return int
	*/
	public function unpublish( $id, $all = FALSE ) { 
		global $wpdb;
		$where = ($all ? '' : ' WHERE id=' . $id);
		$updated = $wpdb->query('UPDATE `' . $wpdb->prefix . 'wdsslider` SET `published` = 0' . $where);

		$message_id = 2;
		if ( $updated !== FALSE ) {
		  $message_id = 11;
		}
		return $message_id;
	}

	/**
	* Delete.
	*
	* @param      $id
	* @param bool $all
	*
	* @return int
	*/
	public function delete( $id, $all = FALSE ) {
		global $wpdb;
		$where = ($all ? '' : ' WHERE `id` = ' . $id);
		$slide_where = ($all ? '' : ' WHERE `t1`.`slider_id` = ' . $id);
		
		$delete = $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'wdsslider ' . $where);	
		if ($delete) {			
			$wpdb->query('DELETE t1.*, t2.* FROM `' . $wpdb->prefix . 'wdsslide` AS `t1` LEFT JOIN ' . $wpdb->prefix . 'wdslayer AS `t2` ON `t1`.`id` = `t2`.`slide_id` ' . $slide_where);
			// TODO. need works the other version.
			// $this->remove_frontend_js_file( $id );
		}
		$message_id = 2;
		if ( $delete ) {
			$message_id = 3;
			if ( $all ) {
				$message_id = 5;
			}		
		}
		return $message_id;
	}

	/**
	* Duplicate.
	*
	* @param      $id
	* @param bool $all
	*
	* @return int
	*/
	public function duplicate( $id, $all = FALSE ) { 
		global $wpdb;
		$where = ($all ? '' : ' WHERE `id` = ' . $id);

		$sliders = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdsslider`' . $where);
		if ( !empty($sliders) ) {
			foreach ( $sliders as $slider ) {
				$slider_ids[] = $slider->id;
				unset($slider->id);
				$sliders_data[$id] = $slider;
			}
			// Get slides by slider ids.
			$slides = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdsslide` WHERE `slider_id` IN(' . implode(',', $slider_ids) . ')');
			if ( !empty($slides)) {
				$slides_data = array();
				foreach ( $slides as $slide ) {
					$id = $slide->id;
					$slider_id = $slide->slider_id;
					$slide_ids[] = $slide->id;
					unset($slide->id);
					unset($slide->slider_id);
					$slides_data[$slider_id][$id] = $slide;
				}
				// Get layers by slide ids.
				$layers = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdslayer` WHERE `slide_id` IN (' . implode(',', $slide_ids) . ')');
				$layers_data = array();
				if ( !empty($layers) ) {
					foreach ( $layers as $layer ) {
						$slide_id = $layer->slide_id;
						unset($layer->id);
						unset($layer->slide_id);
						$layers_data[$slide_id][] = $layer;
					}
				}
			}
			// Insert slider data.
			$slider_save = $this->insert_slides( array('sliders_data' => $sliders_data, 'slides_data' => $slides_data, 'layers_data' => $layers_data) );
		}
		
		$message_id = 2;
		if ( $slider_save ) {
			$message_id = 26;
		}
		return $message_id;
	}

	/**
	* Merge.
	*
	* @param      $id
	* @param bool $all
	*
	* @return int
	*/
	public function merge( $id, $all = FALSE ) { 
		global $wpdb;
		$checkds = WDW_S_Library::get('check');
		if ( !empty($checkds) ) {
			if ( isset($checkds[$id]) ) {
				unset($checkds[$id]);
			}
			$ids[] = intval($id);
			foreach ( $checkds as $k => $v ){
				$ids[] = intval($k);
			}
		}
		$str_ids = implode( ',', $ids );
		$where = ($all ? '' : ' WHERE `id` IN (' . $str_ids . ')');
		$sliders = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdsslider` '. $where .' ORDER BY FIELD(`id`,' . $str_ids . ');');
		if ( !empty($sliders) ) {
			$name = "Merged sliders of ";
			foreach ( $sliders as $slider ) {
				$name .= $slider->name .', ';
				$slider_ids[$slider->id] = $slider;
			}

			if ( !empty($slider_ids[$id]) ) {
				$slider_data = $slider_ids[$id];
				unset($slider_data->id);
				$slider_data->name = rtrim($name, ', ');
				$sliders_data[$id] = $slider_data;
				$str_slider_ids = implode( ',', array_keys($slider_ids) );
				$slides = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdsslide` WHERE `slider_id` IN ('. $str_slider_ids . ') ORDER BY FIELD(`slider_id`,' . $str_slider_ids . ')');

				$order_slide = 1;
				$slides_data = array();
				foreach ( $slides as $slide ) {
					$slide_id = $slide->id;
					$slide_ids[] = $slide_id;
					unset($slide->id);
					unset($slide->slider_id);
					$slide->order = $order_slide;
					$slides_data[$id][$slide_id] = $slide;
					$order_slide++;
				}
				// Get layers by slide ids.
				$layers = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'wdslayer` WHERE `slide_id` IN (' . implode(',', $slide_ids) . ') ORDER BY `slide_id` ASC');
				$layers_data = array();
				if ( !empty($layers) ) {
					foreach ( $layers as $layer ) {
						$slide_id = $layer->slide_id;
						unset($layer->id);
						unset($layer->slide_id);
						$layers_data[$slide_id][] = $layer;
					}
				}
				// Insert slider data.
				$slider_save = $this->insert_slides( array('sliders_data' => $sliders_data, 'slides_data' => $slides_data, 'layers_data' => $layers_data) );
			}
			
		}
		$message_id = 27;
		if ( $slider_save ) {
			$message_id = 28;
		}
		return $message_id;
	}

	/**
	 * Insert slides.
	 *
	 * @param  array $params
	 * @return mixed
	 */
	public function insert_slides( $params = array() ) {
		global $wpdb;
		$sliders_data  = $params['sliders_data'];
		$slides_data   = $params['slides_data'];
		$layers_data   = $params['layers_data'];

		if ( !empty($sliders_data) ) {			
			foreach ( $sliders_data as $slider_id => $slider ) { 
				$slider_save = $wpdb->insert($wpdb->prefix . 'wdsslider', (array) $slider);
				$new_slider_id = $wpdb->insert_id;

				if ( !empty($slides_data[$slider_id]) ) {
					foreach ( $slides_data[$slider_id] as $slid_id => $slide) {
						
						$slide->slider_id = $new_slider_id;
						$slid_save = $wpdb->insert($wpdb->prefix . 'wdsslide', (array) $slide);
						$new_slide_id = $wpdb->insert_id;
					
						if ( !empty($layers_data[$slid_id]) ) {
							foreach ( $layers_data[$slid_id] as $layer ) {
								$layer->slide_id = $new_slide_id;
								$layer_save = $wpdb->insert($wpdb->prefix . 'wdslayer', (array) $layer);
							}
						}
					}
				}
			}
			return $new_slider_id;
		}
		return FALSE;
	}

	/**
	 * Get slides info.
	 *
     * @param array $params
     * @return array
     */
	public function get_slides_info( $params = array() ) { 
		global $wpdb;
		$ids = $params['ids'];
		$rows = $wpdb->get_results('
							SELECT
								`slider_id`,
								COUNT(*) AS `count`				
							FROM
								' . $wpdb->prefix . 'wdsslide 
							WHERE
								`slider_id` IN (' . implode(',', $ids) . ') 
								AND `image_url` <> "" 
							AND `image_url` NOT LIKE "%images/no-image.png%"
							GROUP BY `slider_id`
						');
		$images_count = array();
		if ( !empty($rows) ) {
			foreach ( $rows as $row ) {
				$images_count[$row->slider_id] = $row->count;
			}
		}

		$rows = $wpdb->get_results('
							SELECT 
								`slider_id`,
								`thumb_url`, 
								`type`
							FROM 
								' . $wpdb->prefix . 'wdsslide 
							WHERE
								`slider_id` IN ('. implode(',', $ids) .')
							ORDER BY `order` ASC
						');

		$preview_thumb_url = WDS()->plugin_url . '/images/no-image.png';
		if ( $rows ) {
			foreach ( $rows as $row ) {
				$preview_thumb_image_url = ( $row->type == 'video' && ctype_digit($row->thumb_url) ) ? ( wp_get_attachment_url(get_post_thumbnail_id($row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($row->thumb_url)) : WDS()->plugin_url . '/images/no-video.png') : $row->thumb_url;
				if ($preview_thumb_image_url) {
					$preview_thumb_image_url = str_replace('{site_url}', site_url(), $preview_thumb_image_url);
				}
				$thumbs[$row->slider_id] =$preview_thumb_image_url;
			}
		}
		
		$data = array();
		foreach ( $ids as $id ) {
			$data[$id] = array (
							'count' => !empty($images_count[$id]) ? $images_count[$id] : 0,
							'preview_thumb' => !empty($thumbs[$id]) ? $thumbs[$id] : $preview_thumb_url
						);
		}
		return $data;
	}

	/*
	* Create frontend js file.
	*
	* @param int int
	* @return bool
	*/
	public function create_frontend_js_file( $id ) {
		$create_js = WDW_S_Library::create_frontend_js_file( $id );
		global $wpdb;
		$update = $wpdb->update( $wpdb->prefix . 'wdsslider', array('jsversion' => rand()), array('id' => $id) );
		return $update;
	}
}