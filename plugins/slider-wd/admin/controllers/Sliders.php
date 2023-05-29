<?php

/**
 * Class SlidersController_wds
 */
class SlidersController_wds {
	/**
	* @var $model
	*/
	private $model;
	/**
	* @var $view
	*/
	private $view;
	/**
	* @var string $page
	*/
	private $page;
	/**
	* @var int $items_per_page
	*/
	private $items_per_page = 20;
	/**
	* @var array $actions
	*/
	private $actions = array();

    /**
     * SlidersController_wds constructor.
     * @param array $args
     */
	public function __construct( $args = array() ) {
		$this->model = $args['model'];
		$this->view = $args['view'];
		$this->page = WDW_S_Library::get('page');
		$this->actions = array(
		  'publish' => array(
			'title' => __('Publish', WDS()->prefix),
			'bulk_action' => __('published', WDS()->prefix),
		  ),
		  'unpublish' => array(
			'title' => __('Unpublish', WDS()->prefix),
			'bulk_action' => __('unpublished', WDS()->prefix),
		  ),
		  'delete' => array(
			'title' => __('Delete', WDS()->prefix),
			'bulk_action' => __('deleted', WDS()->prefix),
		  ),
		  'duplicate' => array(
			'title' => __('Duplicate', WDS()->prefix),
			'bulk_action' => __('duplicated', WDS()->prefix),
		  ),
		  'export' => array(
			'title' => __('Export', WDS()->prefix),
			'bulk_action' => __('export', WDS()->prefix),
		  ),
		  'merge' => array(
			'title' => __('Merge', WDS()->prefix),
			'bulk_action' => __('merged', WDS()->prefix),
		  ),
		);
		$user = get_current_user_id();
		$screen = get_current_screen();
		$option = $screen->get_option('per_page', 'option');
		$this->items_per_page = get_user_meta($user, $option, TRUE);

		if ( empty ($this->items_per_page) || $this->items_per_page < 1 ) {
		  $this->items_per_page = $screen->get_option('per_page', 'default');
		}
	}

  /**
   * Execute.
   */
  public function execute() {
    $task = WDW_S_Library::get('task');
    $id = (int) WDW_S_Library::get('current_id', 0);
    if ( method_exists($this, $task) ) {
      if ( $task != 'edit' && $task != 'display' ) {
        check_admin_referer(WDS()->nonce, WDS()->nonce);
      }
      $action = WDW_S_Library::get('bulk_action', -1);
      if ( $action != -1 ) {
        $this->bulk_action( $action );
      }
      else {		
        $this->$task( $id );
      }
    }
    else {
      $this->display();
    }
  }
  
  /**
   * Bulk actions.
   *
   * @param $task
   */
  public function bulk_action( $task = '' ) {
    $message = 0;
    $successfully_updated = 0;
    $url_arg = array('page' => $this->page, 'task' => 'display');
    $check = WDW_S_Library::get('check', '');
    $all = WDW_S_Library::get('check_all_items', '');
    $all = ($all == 'on' ? TRUE : FALSE);

    if ( method_exists($this, $task) ) {
      if ( $all ) {
        $message = $this->$task(0, TRUE, TRUE);
        $url_arg['message'] = $message;
      }
      else {
        if ( $check ) {
          foreach ( $check as $id => $item ) {
            $message = $this->$task( intval($id), TRUE);
            if ( $message != 2 ) {
              // Increase successfully updated items count, if action doesn't failed.
              $successfully_updated++;
            }
          }
        }
        if ( $successfully_updated ) {
          $message = sprintf(_n('%s item successfully %s.', '%s items successfully %s.', $successfully_updated, WDS()->prefix), $successfully_updated, $this->actions[$task]['bulk_action']);
        }
        $key = ($message === 2 ? 'message' : 'msg');
        $url_arg[$key] = $message;
      }
    }
    WDW_S_Library::redirect( add_query_arg($url_arg, admin_url('admin.php')) );
  }

	/**
	 * Display.
	 */
	public function display() {
		// Set params for view.
		$params = array();
		$params['page'] = $this->page;
		$params['page_title'] = __('Sliders', WDS()->prefix);
		$params['actions'] = $this->actions;
		$params['order'] = WDW_S_Library::get('order', 'asc');
		$params['orderby'] = WDW_S_Library::get('orderby', 'name');
		// To prevent SQL injections.
		$params['order'] = ($params['order'] == 'desc') ? 'desc' : 'asc';
		if ( !in_array($params['orderby'], array( 'name' )) ) {
		  $params['orderby'] = 'id';
		}
		$params['items_per_page'] = $this->items_per_page;
		$page = (int) WDW_S_Library::get('paged', 1);
		$page_num = $page ? ($page - 1) * $params['items_per_page'] : 0;
		$params['page_num'] = $page_num;
		$params['search'] = WDW_S_Library::get('s', '');

		$params['preview_url'] = $this->model->get_slide_preview_post();
		$data = $this->model->get_rows_data($params);
		if ( !empty($data['rows']) ) {
			$ids = array();
			foreach( $data['rows'] as $row ) {
				$ids[] = $row->id; 
			}
			// Get slides more info.
			$params['slides_info'] = $this->model->get_slides_info( array('ids' => $ids) );
		}
		$params['rows']  = $data['rows'];
		$params['total'] = $data['total'];

		$url_arg = array();
		$page_url = add_query_arg( array(
									'page' => $this->page,
									WDS()->nonce => wp_create_nonce(WDS()->nonce),
								  ), admin_url('admin.php') );
		$params['page_url'] = add_query_arg($url_arg, $page_url);
		
		$this->view->display( $params );
	}

  /**
   * Edit.
   *
   * @param int  $id
   * @param bool $reset
   */
	public function edit( $id = 0, $reset = FALSE ) {
		$row = $this->model->get_row_data($id, $reset);
		if ( $id != 0 && empty($row->id) ) {
			WDW_S_Library::redirect( add_query_arg( array('page' => $this->page, 'task' => 'display'), admin_url('admin.php') ) );
		}
		$slides_row = $this->model->get_slides_row_data($id);
		$layers_row = array();
		if ( !empty($slides_row) ) {
			foreach ( $slides_row as $slide_row) {
				$slide_ids[] = $slide_row->id;
			}
			$layers_row = $this->model->get_layers_row_data( $slide_ids );
		}

		$wds_global_options = get_option("wds_global_options", 0);
		$options_values = WDW_S_Library::get_values();
		$global_options = json_decode($wds_global_options);
		$page_title = __('Create new slider', WDS()->prefix);
		$save_btn_name = __('Publish', WDS()->prefix);
		if ( $id ) {
		  $page_title = sprintf(__('Edit slider %s', WDS()->prefix), $row->name);
		  $save_btn_name = __('Update', WDS()->prefix);
		}

		// Set params for view.
		$params = array();
		$params['id'] = $id;
		$params['row'] = $row;
		$params['slides_row'] = $slides_row;
		$params['layers_row'] = $layers_row;
		$params['global_options'] = $global_options;
		$params['options_values'] = $options_values;
		$params['slider_preview_link'] = $this->model->get_slide_preview_post();
		$params['sub_tab_type'] = WDW_S_Library::get('sub_tab', '');
		$params['page_title'] = $page_title;
		$params['save_btn_name'] = $save_btn_name;

		$this->view->edit( $params );
	}

  /**
   * Apply.
   *
   * @param int $id
   */
	public function apply( $id = 0 ) {
		$save = $this->save_slider_db();
		$id = $save['id'];
		$this->save_slide_db( $id );
		// TODO. need works the other version.
		// $this->create_frontend_js_file( $id );
		$this->edit( $id );
	}

  /**
   * Publish.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
	public function publish( $id = 0, $bulk = FALSE, $all = FALSE ) {
		$message = $this->model->publish($id, $all);
		if ( $bulk ) {
			return $message;
		}

		WDW_S_Library::redirect( add_query_arg( array(
									'page' => $this->page,
									'task' => 'display',
									'message' => $message,
								   ), admin_url('admin.php')));
	}

  /**
   * Unpublish.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
	public function unpublish( $id = 0, $bulk = FALSE, $all = FALSE ) {
		$message = $this->model->unpublish( $id, $all );
		if ( $bulk ) {
			return $message;
		}

		WDW_S_Library::redirect( add_query_arg( array(
									'page' => $this->page,
									'task' => 'display',
									'message' => $message,
								   ), admin_url('admin.php')));
	}

  /**
   * Delete.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
	public function delete( $id = 0, $bulk = FALSE, $all = FALSE ) {
		$message = $this->model->delete($id, $all);
		if ( $bulk ) {
			return $message;
		}

		WDW_S_Library::redirect( add_query_arg( array(
									'page' => $this->page,
									'task' => 'display',
									'message' => $message,
								   ), admin_url('admin.php')));
	}

  /**
   * Duplicate.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
	public function duplicate( $id = 0, $bulk = FALSE, $all = FALSE ) {
		$message = $this->model->duplicate($id, $all);
		if ( $bulk ) {
			return $message;
		}

		WDW_S_Library::redirect( add_query_arg( array(
									'page' => $this->page,
									'task' => 'display',
									'message' => $message,
								   ), admin_url('admin.php')));
	}

  /**
   * Reset.
   *
   * @param int $id
   */
	public function reset( $id = 0 ) {
		echo WDW_S_Library::message('Changes must be saved.', 'wd_error');
		$this->edit( $id, TRUE);
	}

  /**
   * Merge sliders.
   *
   * @param int $id
   */
	public function merge( $id = 0 ) {
		$id  = WDW_S_Library::get('select_slider_merge');
		$all = WDW_S_Library::get('check_all_items');
		$message_id = $this->model->merge($id, $all);
		echo WDW_S_Library::message_id($message_id);
		$this->display();
	}

  /**
   * Save slider DB.
   *
   * @param int $id
   *
   * @return array $data
   */
	public function save_slider_db( $id = 0) {
		global $wpdb;
		$allow = TRUE;
		if ( WDS()->is_free && get_option("wds_theme_version") ) {
		  $allow = FALSE;
		}
		$slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : $id);
		$slider_data = (isset($_POST['slider_data']) ? stripslashes($_POST['slider_data']) : '');
		$params_array = json_decode($slider_data, TRUE);
		$del_slide_ids = ( isset($params_array['del_slide_ids_string']) ? explode(',', $params_array['del_slide_ids_string']) : '');
		if ( !empty($del_slide_ids) ) {
		  $slide_ids = array();
		  foreach ( $del_slide_ids as $did ) {
		    if ( isset($did) && is_numeric($did) ) {
		   	 $slide_ids[] = trim(intval($did));
		    }
		  }
		  if ( !empty($slide_ids) ) {
		    $slide_ids = implode(',', $slide_ids);
		    $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'wdsslide WHERE slider_id=' . $slider_id . ' AND id IN (' . $slide_ids . ')');
		  }
		}
		$name = WDW_S_Library::esc_sanitize_data($params_array, 'name', 'sanitize_text_field');
		$published = (int) WDW_S_Library::esc_sanitize_data($params_array, 'published', '', 1);
		$full_width = (int) WDW_S_Library::esc_sanitize_data($params_array, 'full_width', '', 0);
		$auto_height = (int) WDW_S_Library::esc_sanitize_data($params_array, 'auto_height', '', 0);
		$width = (int) WDW_S_Library::esc_sanitize_data($params_array, 'width', '', 900);
		$height = (int) WDW_S_Library::esc_sanitize_data($params_array, 'height', '', 400);
		
		$align = WDW_S_Library::esc_sanitize_data($params_array, 'align', 'sanitize_text_field', 'center');
		$effect = WDW_S_Library::esc_sanitize_data($params_array, 'effect', 'sanitize_text_field', 'fade');
		$time_intervval = (int) WDW_S_Library::esc_sanitize_data($params_array, 'time_intervval', '', 5);
		$autoplay = (int) WDW_S_Library::esc_sanitize_data($params_array, 'autoplay', '', 1);
		$shuffle = (int) WDW_S_Library::esc_sanitize_data($params_array, 'shuffle', '', 0);
		$music = (int) WDW_S_Library::esc_sanitize_data($params_array, 'music', '', 0);
		$music_url = '';
		if ( isset($params_array['music_url']) && WDW_S_Library::validate_audio_file($params_array['music_url']) ) {
		  $music_url = WDW_S_Library::esc_sanitize_data($params_array, 'music_url', 'esc_url');
		  $music_url = str_replace(site_url(), '{site_url}', $music_url);
		}
		$preload_images = (int) WDW_S_Library::esc_sanitize_data($params_array, 'preload_images', '', 1);
		$background_color = WDW_S_Library::esc_sanitize_data($params_array, 'background_color', 'sanitize_text_field', '000000');
		$background_transparent = (int) WDW_S_Library::esc_sanitize_data($params_array, 'background_transparent', '', 100);
		$glb_border_width = (int) WDW_S_Library::esc_sanitize_data($params_array, 'glb_border_width', '', 0);
		$glb_border_style = WDW_S_Library::esc_sanitize_data($params_array, 'glb_border_style', 'sanitize_text_field', 'none');
		$glb_border_color = WDW_S_Library::esc_sanitize_data($params_array, 'glb_border_color', 'sanitize_text_field', '000000');
		$glb_border_radius = WDW_S_Library::esc_sanitize_data($params_array, 'glb_border_radius', 'sanitize_text_field');
		$glb_margin = (int) WDW_S_Library::esc_sanitize_data($params_array, 'glb_margin', '', 0);
		$glb_box_shadow = WDW_S_Library::esc_sanitize_data($params_array, 'glb_box_shadow', 'sanitize_text_field');
		$image_right_click = (int) WDW_S_Library::esc_sanitize_data($params_array, 'image_right_click', '', 0);
		$layer_out_next = (int) WDW_S_Library::esc_sanitize_data($params_array, 'layer_out_next', '', 0);
		$prev_next_butt = (int) WDW_S_Library::esc_sanitize_data($params_array, 'prev_next_butt', '', 1);
		$play_paus_butt = (int) WDW_S_Library::esc_sanitize_data($params_array, 'play_paus_butt', '', 0);
		$navigation = WDW_S_Library::esc_sanitize_data($params_array, 'navigation', 'sanitize_text_field', 'hover');
		$rl_butt_style = WDW_S_Library::esc_sanitize_data($params_array, 'rl_butt_style', 'sanitize_text_field', 'fa-angle');
		$rl_butt_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'rl_butt_size', '', 40);
		$pp_butt_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'pp_butt_size', '', 40);
		$butts_color = WDW_S_Library::esc_sanitize_data($params_array, 'butts_color', 'sanitize_text_field', '000000');
		$butts_transparent = (int) WDW_S_Library::esc_sanitize_data($params_array, 'butts_transparent', '', 100);
		$hover_color = WDW_S_Library::esc_sanitize_data($params_array, 'hover_color', 'sanitize_text_field', 'FFFFFF');
		$nav_border_width = (int) WDW_S_Library::esc_sanitize_data($params_array, 'nav_border_width', '', 0);
		$nav_border_style = WDW_S_Library::esc_sanitize_data($params_array, 'nav_border_style', 'sanitize_text_field', 'none');
		$nav_border_color = WDW_S_Library::esc_sanitize_data($params_array, 'nav_border_color', 'sanitize_text_field', 'FFFFFF');
		$nav_border_radius = WDW_S_Library::esc_sanitize_data($params_array, 'nav_border_radius', 'sanitize_text_field', '20px');
		$nav_bg_color = WDW_S_Library::esc_sanitize_data($params_array, 'nav_bg_color', 'sanitize_text_field', 'FFFFFF');
		$bull_position = WDW_S_Library::esc_sanitize_data($params_array, 'bull_position', 'sanitize_text_field', 'bottom');
		if ( isset($params_array['enable_bullets']) && $params_array['enable_bullets'] == 0 ) {
		  $bull_position = 'none';
		}
		$bull_style = WDW_S_Library::esc_sanitize_data($params_array, 'bull_style', 'sanitize_text_field', 'fa-square-o');
		$bull_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'bull_size', '', 20);
		$bull_color = WDW_S_Library::esc_sanitize_data($params_array, 'bull_color', 'sanitize_text_field', 'FFFFFF');
		$bull_act_color = WDW_S_Library::esc_sanitize_data($params_array, 'bull_act_color', 'sanitize_text_field', 'FFFFFF');
		$bull_margin = ((isset($params_array['bull_margin']) && $allow) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'bull_margin', 'sanitize_text_field', 3) : 3);
		$film_pos = WDW_S_Library::esc_sanitize_data($params_array, 'film_pos', 'sanitize_text_field', 'none');
		if ( isset($params_array['enable_filmstrip']) && $params_array['enable_filmstrip'] == 0 ) {
		  $film_pos = 'none';
		}
		$film_small_screen = (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_small_screen', '', 0);
		$film_thumb_width = (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_thumb_width', '', 100);
		$film_thumb_height = (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_thumb_height', '', 50);
		$film_bg_color = WDW_S_Library::esc_sanitize_data($params_array, 'film_bg_color', 'sanitize_text_field', '000000');
		$film_tmb_margin = (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_tmb_margin', '', 0);
		$film_act_border_width =  (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_act_border_width', '', 0);
		$film_act_border_style = WDW_S_Library::esc_sanitize_data($params_array, 'film_act_border_style', 'sanitize_text_field', 'none');
		$film_act_border_color = WDW_S_Library::esc_sanitize_data($params_array, 'film_act_border_color', 'sanitize_text_field', 'FFFFFF');
		$film_dac_transparent = (int) WDW_S_Library::esc_sanitize_data($params_array, 'film_dac_transparent', '', 50);
		$built_in_watermark_type = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_type', 'sanitize_text_field', 'none');
		$built_in_watermark_position = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_position', 'sanitize_text_field', 'middle-center');
		$built_in_watermark_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_size', '', 15);
		$built_in_watermark_url = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_url', 'esc_url');
		$built_in_watermark_url = str_replace(site_url(), '{site_url}', $built_in_watermark_url);
		$built_in_watermark_text = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_text', 'sanitize_text_field', '10Web.io');
		$built_in_watermark_opacity = (int) WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_opacity', '', 70);
		$built_in_watermark_font_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_font_size', '', 20);
		$built_in_watermark_font = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_font', 'sanitize_text_field');
		$built_in_watermark_color = WDW_S_Library::esc_sanitize_data($params_array, 'built_in_watermark_color', 'sanitize_text_field', 'FFFFFF');
		$css = (isset($params_array['css']) ? wp_filter_nohtml_kses(htmlspecialchars_decode((addslashes($params_array['css'])), ENT_QUOTES)) : '');
		$timer_bar_type = WDW_S_Library::esc_sanitize_data($params_array, 'timer_bar_type', 'sanitize_text_field', 'top');
		if ( isset($params_array['enable_time_bar']) && $params_array['enable_time_bar'] == 0 ) {
		  $timer_bar_type = 'none';
		}
		$timer_bar_size = (int) WDW_S_Library::esc_sanitize_data($params_array, 'timer_bar_size', '', 5);
		$timer_bar_color = WDW_S_Library::esc_sanitize_data($params_array, 'timer_bar_color', 'sanitize_text_field', 'BBBBBB');
		$timer_bar_transparent = (int) WDW_S_Library::esc_sanitize_data($params_array, 'timer_bar_transparent', '', 50);
		$stop_animation = (int) WDW_S_Library::esc_sanitize_data($params_array, 'stop_animation', '', 0);
		$right_butt_url = WDW_S_Library::esc_sanitize_data($params_array, 'right_butt_url', 'esc_url');
		$right_butt_url = str_replace(site_url(), '{site_url}', $right_butt_url);
		$left_butt_url = WDW_S_Library::esc_sanitize_data($params_array, 'left_butt_url', 'esc_url');
		$left_butt_url = str_replace(site_url(), '{site_url}', $left_butt_url);
		$right_butt_hov_url = WDW_S_Library::esc_sanitize_data($params_array, 'right_butt_hov_url', 'esc_url');
		$right_butt_hov_url = str_replace(site_url(), '{site_url}', $right_butt_hov_url);
		$left_butt_hov_url = WDW_S_Library::esc_sanitize_data($params_array, 'left_butt_hov_url', 'esc_url');
		$left_butt_hov_url = str_replace(site_url(), '{site_url}', $left_butt_hov_url);
		$rl_butt_img_or_not = WDW_S_Library::esc_sanitize_data($params_array, 'rl_butt_img_or_not', 'sanitize_text_field', 'style');
		$bullets_img_main_url = WDW_S_Library::esc_sanitize_data($params_array, 'bullets_img_main_url', 'esc_url');
		$bullets_img_main_url = str_replace(site_url(), '{site_url}', $bullets_img_main_url);
		$bullets_img_hov_url = WDW_S_Library::esc_sanitize_data($params_array, 'bullets_img_hov_url', 'esc_url');
		$bullets_img_hov_url = str_replace(site_url(), '{site_url}', $bullets_img_hov_url);
		$bull_butt_img_or_not = WDW_S_Library::esc_sanitize_data($params_array, 'bull_butt_img_or_not', 'sanitize_text_field', 'style');
		$play_paus_butt_img_or_not = WDW_S_Library::esc_sanitize_data($params_array, 'play_paus_butt_img_or_not', 'sanitize_text_field', 'style');
		$play_butt_url = WDW_S_Library::esc_sanitize_data($params_array, 'play_butt_url', 'esc_url');
		$play_butt_url = str_replace(site_url(), '{site_url}', $play_butt_url);
		$play_butt_hov_url = WDW_S_Library::esc_sanitize_data($params_array, 'play_butt_hov_url', 'esc_url');
		$play_butt_hov_url = str_replace(site_url(), '{site_url}', $play_butt_hov_url);
		$paus_butt_url = WDW_S_Library::esc_sanitize_data($params_array, 'paus_butt_url', 'esc_url');
		$paus_butt_url = str_replace(site_url(), '{site_url}', $paus_butt_url);
		$paus_butt_hov_url = WDW_S_Library::esc_sanitize_data($params_array, 'paus_butt_hov_url', 'esc_url');
		$paus_butt_hov_url = str_replace(site_url(), '{site_url}', $paus_butt_hov_url);
		$start_slide_num = (int) WDW_S_Library::esc_sanitize_data($params_array, 'start_slide_num', '', 1);
		$effect_duration = (int) WDW_S_Library::esc_sanitize_data($params_array, 'effect_duration', '', 800);
		$carousel = ((isset($params_array['carousel']) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel', '', 0) : 0);
		$carousel_image_counts = ( (isset($params_array['carousel_image_counts']) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_image_counts', '', 0) : 7 );
		$carousel_image_parameters = ( (isset($params_array['carousel_image_parameters']) && !WDS()->is_free) ? WDW_S_Library::esc_sanitize_data($params_array, 'carousel_image_parameters', '', 0.85) : 0.85 );
		$carousel_fit_containerWidth = ((isset($params_array['carousel_fit_containerWidth']) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_fit_containerWidth', '', 0) : 0);
		$carousel_width = ((isset($params_array['carousel_width']) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_width', '', 1000) : 1000);
		$parallax_effect = ((isset($params_array['parallax_effect']) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'parallax_effect', '', 0) : 0);
		$mouse_swipe_nav = ((isset($params_array['mouse_swipe_nav'])) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'mouse_swipe_nav', '', 0) : 0);
		$bull_hover = (int) WDW_S_Library::esc_sanitize_data($params_array, 'bull_hover', '', 1);
		$touch_swipe_nav = (int) WDW_S_Library::esc_sanitize_data($params_array, 'touch_swipe_nav', '', 1);
		$mouse_wheel_nav = (int) WDW_S_Library::esc_sanitize_data($params_array, 'mouse_wheel_nav', '', 0);
		$keyboard_nav = (int) WDW_S_Library::esc_sanitize_data($params_array, 'keyboard_nav', '', 0);
		$show_thumbnail = (int) WDW_S_Library::esc_sanitize_data($params_array, 'show_thumbnail', '', 0);
		$thumb_size = WDW_S_Library::esc_sanitize_data($params_array, 'thumb_size', '', '0.3');
		$fixed_bg = (int) WDW_S_Library::esc_sanitize_data($params_array, 'fixed_bg', '', 0);
		$smart_crop = (int) WDW_S_Library::esc_sanitize_data($params_array, 'smart_crop', '', 0);
		$crop_image_position = WDW_S_Library::esc_sanitize_data($params_array, 'crop_image_position', 'sanitize_text_field', 'middle-center');
		$javascript = ((isset($params_array['javascript'])) ? $params_array['javascript'] : '');
		$carousel_degree = (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_degree', '', 0);
		$carousel_grayscale = (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_grayscale', '', 0);
		$carousel_transparency = (int) WDW_S_Library::esc_sanitize_data($params_array, 'carousel_transparency', '', 0);
		$bull_back_act_color = WDW_S_Library::esc_sanitize_data($params_array, 'bull_back_act_color', 'sanitize_text_field', '000000');
		$bull_back_color =  WDW_S_Library::esc_sanitize_data($params_array, 'bull_back_color', 'sanitize_text_field', 'CCCCCC');
		$bull_radius = WDW_S_Library::esc_sanitize_data($params_array, 'bull_radius', 'sanitize_text_field', '20px');
		$slider_loop = (int) WDW_S_Library::esc_sanitize_data($params_array, 'slider_loop', '', 1);
		$hide_on_mobile = (int) WDW_S_Library::esc_sanitize_data($params_array, 'hide_on_mobile', '', 0);
		$twoway_slideshow = (int) WDW_S_Library::esc_sanitize_data($params_array, 'twoway_slideshow', '', 0);
		$full_width_for_mobile = (int) WDW_S_Library::esc_sanitize_data($params_array, 'full_width_for_mobile', '', 0);
		$order_dir = WDW_S_Library::esc_sanitize_data($params_array, 'order_dir', 'sanitize_text_field', 'asc');
		$data = array(
		  'name' => $name,
		  'published' => $published,
		  'full_width' => $full_width,
		  'auto_height' => $auto_height,
		  'width' => $width,
		  'height' => $height,
		  'align' => $align,
		  'effect' => $effect,
		  'time_intervval' => $time_intervval,
		  'autoplay' => $autoplay,
		  'shuffle' => $shuffle,
		  'music' => $music,
		  'music_url' => $music_url,
		  'preload_images' => $preload_images,
		  'background_color' => $background_color,
		  'background_transparent' => $background_transparent,
		  'glb_border_width' => $glb_border_width,
		  'glb_border_style' => $glb_border_style,
		  'glb_border_color' => $glb_border_color,
		  'glb_border_radius' => $glb_border_radius,
		  'glb_margin' => $glb_margin,
		  'glb_box_shadow' => $glb_box_shadow,
		  'image_right_click' => $image_right_click,
		  'prev_next_butt' => $prev_next_butt,
		  'play_paus_butt' => $play_paus_butt,
		  'navigation' => $navigation,
		  'rl_butt_style' => $rl_butt_style,
		  'rl_butt_size' => $rl_butt_size,
		  'pp_butt_size' => $pp_butt_size,
		  'butts_color' => $butts_color,
		  'butts_transparent' => $butts_transparent,
		  'hover_color' => $hover_color,
		  'nav_border_width' => $nav_border_width,
		  'nav_border_style' => $nav_border_style,
		  'nav_border_color' => $nav_border_color,
		  'nav_border_radius' => $nav_border_radius,
		  'nav_bg_color' => $nav_bg_color,
		  'bull_position' => $bull_position,
		  'bull_style' => $bull_style,
		  'bull_size' => $bull_size,
		  'bull_color' => $bull_color,
		  'bull_act_color' => $bull_act_color,
		  'bull_margin' => $bull_margin,
		  'film_pos' => $film_pos,
		  'film_small_screen' => $film_small_screen,
		  'film_thumb_width' => $film_thumb_width,
		  'film_thumb_height' => $film_thumb_height,
		  'film_bg_color' => $film_bg_color,
		  'film_tmb_margin' => $film_tmb_margin,
		  'film_act_border_width' => $film_act_border_width,
		  'film_act_border_style' => $film_act_border_style,
		  'film_act_border_color' => $film_act_border_color,
		  'film_dac_transparent' => $film_dac_transparent,
		  'built_in_watermark_type' => $built_in_watermark_type,
		  'built_in_watermark_position' => $built_in_watermark_position,
		  'built_in_watermark_size' => $built_in_watermark_size,
		  'built_in_watermark_url' => $built_in_watermark_url,
		  'built_in_watermark_text' => $built_in_watermark_text,
		  'built_in_watermark_opacity' => $built_in_watermark_opacity,
		  'built_in_watermark_font_size' => $built_in_watermark_font_size,
		  'built_in_watermark_font' => $built_in_watermark_font,
		  'built_in_watermark_color' => $built_in_watermark_color,
		  'css' => $css,
		  'timer_bar_type' => $timer_bar_type,
		  'timer_bar_size' => $timer_bar_size,
		  'timer_bar_color' => $timer_bar_color,
		  'timer_bar_transparent' => $timer_bar_transparent,
		  'layer_out_next' => $layer_out_next,
		  'stop_animation' => $stop_animation,
		  'right_butt_url' => $right_butt_url,
		  'left_butt_url' => $left_butt_url,
		  'right_butt_hov_url' => $right_butt_hov_url,
		  'left_butt_hov_url' => $left_butt_hov_url,
		  'rl_butt_img_or_not' => $rl_butt_img_or_not,
		  'bullets_img_main_url' => $bullets_img_main_url,
		  'bullets_img_hov_url' => $bullets_img_hov_url,
		  'bull_butt_img_or_not' => $bull_butt_img_or_not,
		  'play_paus_butt_img_or_not' => $play_paus_butt_img_or_not,
		  'play_butt_url' => $play_butt_url,
		  'play_butt_hov_url' => $play_butt_hov_url,
		  'paus_butt_url' => $paus_butt_url,
		  'paus_butt_hov_url' => $paus_butt_hov_url,
		  'start_slide_num' => $start_slide_num,
		  'effect_duration' => $effect_duration,
		  'carousel' => $carousel,
		  'carousel_image_counts' => $carousel_image_counts,
		  'carousel_image_parameters' => $carousel_image_parameters,
		  'carousel_fit_containerWidth' => $carousel_fit_containerWidth,
		  'carousel_width' => $carousel_width,
		  'parallax_effect' => $parallax_effect,
		  'mouse_swipe_nav' => $mouse_swipe_nav,
		  'bull_hover' => $bull_hover,
		  'touch_swipe_nav' => $touch_swipe_nav,
		  'mouse_wheel_nav' => $mouse_wheel_nav,
		  'keyboard_nav' => $keyboard_nav,
		  'show_thumbnail' => $show_thumbnail,
		  'thumb_size' => $thumb_size,
		  'fixed_bg' => $fixed_bg,
		  'smart_crop' => $smart_crop,
		  'crop_image_position' => $crop_image_position,
		  'javascript' => $javascript,
		  'carousel_degree' => $carousel_degree,
		  'carousel_grayscale' => $carousel_grayscale,
		  'carousel_transparency' => $carousel_transparency,
		  'bull_back_act_color' => $bull_back_act_color,
		  'bull_back_color' => $bull_back_color,
		  'bull_radius' => $bull_radius,
		  'slider_loop' => $slider_loop,
		  'hide_on_mobile' => $hide_on_mobile,
		  'twoway_slideshow' => $twoway_slideshow,
		  'full_width_for_mobile' => $full_width_for_mobile,
		  'order_dir' => $order_dir,
		);
		if ( !$slider_id ) {
		  $save = $wpdb->insert($wpdb->prefix . 'wdsslider', $data);
		  $slider_id = (int) $wpdb->insert_id;
		  $_POST['current_id'] = $slider_id;
		}
		else {
		  $save = $wpdb->update($wpdb->prefix . 'wdsslider', $data, array('id' => $slider_id));
		}
		$status = 2;
		if ($save !== FALSE) {
			$status = 1;
		}
		$data = array();
		$data['id'] = $slider_id;
		$data['status'] = $status;
		return $data;
	}

  /**
   * Save slide DB.
   *
   * @param int $id
   */
	public function save_slide_db( $id = 0 ) {
		global $wpdb;
		$allow = TRUE;
		if ( WDS()->is_free && get_option("wds_theme_version") ) {
		  $allow = FALSE;
		}
		$slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : $id);
		$save_as_copy = (int) WDW_S_Library::get('save_as_copy', 0);
		if ( !$slider_id ) {
		  $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
		}
		$slides = isset($_POST['slides']) ? $_POST['slides'] : array();
		foreach ($slides as $slide) {
		  $params_array = json_decode(stripslashes($slide), TRUE);
		  $slide_id = (isset($params_array['id']) ? $params_array['id'] : 0);
		  if ( $slide_id ) {
        $del_layer_ids = ( (isset($params_array['slide' . $slide_id . '_del_layer_ids_string']) && !$save_as_copy ) ? explode(',', sanitize_text_field($params_array['slide' . $slide_id . '_del_layer_ids_string'])) : '');
        if ( !empty($del_layer_ids) ) {
          $slide_ids = array();
          foreach ( $del_layer_ids as $lid ) {
            if ( isset($lid) && is_numeric($lid) ) {
              $slide_ids[] = trim(intval($lid));
            }
          }
          if ( !empty($slide_ids) ) {
		  $slide_ids = implode(',', $slide_ids);
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'wdslayer WHERE id IN (' . $slide_ids . ')');
          }
        }
        $title = WDW_S_Library::esc_sanitize_data($params_array, 'title' . $slide_id, 'sanitize_text_field');
        $type = WDW_S_Library::esc_sanitize_data($params_array, 'type' . $slide_id, 'sanitize_text_field');
        $order = WDW_S_Library::esc_sanitize_data($params_array, 'order' . $slide_id, 'sanitize_text_field');
        $published = (int) WDW_S_Library::esc_sanitize_data($params_array, 'published' . $slide_id, '', 0);
        $target_attr_slide = (int) WDW_S_Library::esc_sanitize_data($params_array, 'target_attr_slide' . $slide_id, '', 0);
        $link = ((isset($params_array['link' . $slide_id])) ? WDW_S_Library::esc_sanitize_data($params_array, 'link' . $slide_id, 'sanitize_text_field') : ( ($type == 'video') ? 0 : '') );
        $image_url = WDW_S_Library::esc_sanitize_data($params_array, 'image_url' . $slide_id, 'sanitize_text_field');
        $image_url = str_replace(site_url(), '{site_url}', $image_url);
        $thumb_url = WDW_S_Library::esc_sanitize_data($params_array, 'thumb_url' . $slide_id, 'esc_url');
        $thumb_url = str_replace(site_url(), '{site_url}', $thumb_url);
        $att_width = WDW_S_Library::esc_sanitize_data($params_array, 'att_width' . $slide_id, 'sanitize_text_field');
        $att_height = WDW_S_Library::esc_sanitize_data($params_array, 'att_height' . $slide_id, 'sanitize_text_field');
        $video_duration = WDW_S_Library::esc_sanitize_data($params_array, 'video_duration' . $slide_id, 'sanitize_text_field');
        $youtube_rel_video = ((isset($params_array['youtube_rel_video' . $slide_id]) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'youtube_rel_video' . $slide_id, 'sanitize_text_field', 0) : 0);
        $video_loop = ((isset($params_array['video_loop' . $slide_id]) && !WDS()->is_free) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'video_loop' . $slide_id, '', 1) : 0);
        $mute = ((isset($params_array['mute' . $slide_id])) ? (int) WDW_S_Library::esc_sanitize_data($params_array, 'mute' . $slide_id, '', 1) : 0);
        $fillmode = WDW_S_Library::esc_sanitize_data($params_array, 'fillmode' . $slide_id, 'sanitize_text_field');
        $title_dimension = array();
        $title_dimension['title'] = $title;
        $title_dimension['att_width'] = $att_width;
        $title_dimension['att_height'] = $att_height;
        $title_dimension['video_duration'] = $video_duration;
        $title_dimension = json_encode($title_dimension);
        $data = array(
          'slider_id' => $slider_id,
          'title' => $title_dimension,
          'type' => $type,
          'order' => $order,
          'published' => $published,
          'link' => $link,
          'image_url' => $image_url,
          'thumb_url' => $thumb_url,
          'target_attr_slide' => $target_attr_slide,
          'youtube_rel_video' => $youtube_rel_video,
          'video_loop' => $video_loop,
          'mute' => $mute,
          'fillmode' => $fillmode
        );
        if (strpos($slide_id, 'pr') !== FALSE || $save_as_copy) {
          $wpdb->insert($wpdb->prefix . 'wdsslide', $data);
          $slide_id_pr = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslide');
        }
        else {
          $wpdb->update($wpdb->prefix . 'wdsslide', $data, array('id' => $slide_id));
          $slide_id_pr = $slide_id;
        }
        if ( !WDS()->is_free ) {
          $this->save_layer_db($slide_id, $slide_id_pr, $params_array);
        }
		  }
		}
	}

	public function save_layer_db($slide_id, $slide_id_pr, $params_array) {
		global $wpdb;
		$save_as_copy = WDW_S_Library::get('save_as_copy', 0);
		$layer_ids_string = (isset($params_array['slide' . $slide_id . '_layer_ids_string']) ? rtrim(sanitize_text_field($params_array['slide' . $slide_id . '_layer_ids_string']), ',') : '');
		$layer_id_array = explode(',', $layer_ids_string);
		foreach ($layer_id_array as $layer_id) {
		  if ($layer_id) {
		    $prefix = 'slide' . $slide_id . '_layer' . $layer_id;

			$json_string = (isset($params_array[$prefix . '_json']) ? $params_array[$prefix . '_json'] : '');
			$params_array_layer = json_decode($json_string, TRUE);
			$title = WDW_S_Library::esc_sanitize_data($params_array_layer, 'title', 'sanitize_text_field');
			$type = WDW_S_Library::esc_sanitize_data($params_array_layer, 'type', 'sanitize_text_field');
			$depth = WDW_S_Library::esc_sanitize_data($params_array_layer, 'depth', 'sanitize_text_field');
			$text = ((isset($params_array_layer['text'])) ? stripcslashes($params_array_layer['text']) : '');
			$link = WDW_S_Library::esc_sanitize_data($params_array_layer, 'link', 'sanitize_text_field');
			$target_attr_layer = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'target_attr_layer', 'sanitize_text_field', 0);
			$left = WDW_S_Library::esc_sanitize_data($params_array_layer, 'left', 'sanitize_text_field');
			$top = WDW_S_Library::esc_sanitize_data($params_array_layer, 'top', 'sanitize_text_field');
		     $hide_on_mobile = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hide_on_mobile', 'intval', 0);
		     $start = WDW_S_Library::esc_sanitize_data($params_array_layer, 'start', 'sanitize_text_field');
			$end = WDW_S_Library::esc_sanitize_data($params_array_layer, 'end', 'sanitize_text_field');
			$published = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'published', '',0);
			$color = WDW_S_Library::esc_sanitize_data($params_array_layer, 'color', 'sanitize_text_field');
			$size = WDW_S_Library::esc_sanitize_data($params_array_layer, 'size', 'sanitize_text_field');
			$ffamily = WDW_S_Library::esc_sanitize_data($params_array_layer, 'ffamily', 'sanitize_text_field');
			$fweight = WDW_S_Library::esc_sanitize_data($params_array_layer, 'fweight', 'sanitize_text_field');
			$padding = WDW_S_Library::esc_sanitize_data($params_array_layer, 'padding', 'sanitize_text_field');
			$fbgcolor = WDW_S_Library::esc_sanitize_data($params_array_layer, 'fbgcolor', 'sanitize_text_field');
			$transparent = WDW_S_Library::esc_sanitize_data($params_array_layer, 'transparent', 'sanitize_text_field');
			$border_width = WDW_S_Library::esc_sanitize_data($params_array_layer, 'border_width', 'sanitize_text_field');
			$border_style = WDW_S_Library::esc_sanitize_data($params_array_layer, 'border_style', 'sanitize_text_field');
			$border_color = WDW_S_Library::esc_sanitize_data($params_array_layer, 'border_color', 'sanitize_text_field');
			$border_radius = WDW_S_Library::esc_sanitize_data($params_array_layer, 'border_radius', 'sanitize_text_field');
			$shadow = WDW_S_Library::esc_sanitize_data($params_array_layer, 'shadow', 'sanitize_text_field');
			$image_url = WDW_S_Library::esc_sanitize_data($params_array_layer, 'image_url', 'esc_url');
			$image_url = str_replace(site_url(), '{site_url}', $image_url);
			$image_width = WDW_S_Library::esc_sanitize_data($params_array_layer, 'image_width', 'sanitize_text_field');
			$image_height = WDW_S_Library::esc_sanitize_data($params_array_layer, 'image_height', 'sanitize_text_field');
			$image_scale = WDW_S_Library::esc_sanitize_data($params_array_layer, 'image_scale', 'sanitize_text_field');
			$alt = WDW_S_Library::esc_sanitize_data($params_array_layer, 'alt', 'sanitize_text_field');
			$imgtransparent = WDW_S_Library::esc_sanitize_data($params_array_layer, 'imgtransparent', 'sanitize_text_field');
			$social_button = WDW_S_Library::esc_sanitize_data($params_array_layer, 'social_button', 'sanitize_text_field');
			$hover_color = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hover_color', 'sanitize_text_field');
			$layer_effect_in = WDW_S_Library::esc_sanitize_data($params_array_layer, 'layer_effect_in', 'sanitize_text_field');
			$layer_effect_out = WDW_S_Library::esc_sanitize_data($params_array_layer, 'layer_effect_out', 'sanitize_text_field');
			$duration_eff_in = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'duration_eff_in', '', 3);
			$duration_eff_out = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'duration_eff_out', '', 3);
			$hotp_width = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_width', 'sanitize_text_field');
			$hotp_fbgcolor = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_fbgcolor', 'sanitize_text_field');
			$hotp_border_width = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_border_width', 'sanitize_text_field');
			$hotp_border_style = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_border_style', 'sanitize_text_field');
			$hotp_border_color = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_border_color', 'sanitize_text_field');
			$hotp_border_radius = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_border_radius', 'sanitize_text_field');
			$hotp_text_position = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotp_text_position', 'sanitize_text_field');
			$google_fonts = WDW_S_Library::esc_sanitize_data($params_array_layer, 'google_fonts', 'sanitize_text_field');
			$attr_width = WDW_S_Library::esc_sanitize_data($params_array_layer, 'attr_width', 'sanitize_text_field');
			$attr_height = WDW_S_Library::esc_sanitize_data($params_array_layer, 'attr_height', 'sanitize_text_field');
			$add_class = WDW_S_Library::esc_sanitize_data($params_array_layer, 'add_class', 'sanitize_text_field');
			$layer_video_loop = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'layer_video_loop', 'sanitize_text_field', 0);
			$youtube_rel_layer_video = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'youtube_rel_layer_video', 'sanitize_text_field', 0);
			$hotspot_animation = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotspot_animation', 'sanitize_text_field', 0);
			$layer_callback_list = WDW_S_Library::esc_sanitize_data($params_array_layer, 'layer_callback_list', 'sanitize_text_field');
			$hotspot_text_display = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hotspot_text_display', 'sanitize_text_field');
			$hover_color_text = WDW_S_Library::esc_sanitize_data($params_array_layer, 'hover_color_text', 'sanitize_text_field');
			$text_alignment = WDW_S_Library::esc_sanitize_data($params_array_layer, 'text_alignment', 'sanitize_text_field');
			$link_to_slide = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'link_to_slide', 'sanitize_text_field', 0);
			$align_layer = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'align_layer', 'sanitize_text_field', 0);
			$static_layer = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'static_layer', 'sanitize_text_field', 0);
			$infinite_in = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'infinite_in', 'sanitize_text_field', 1);
			$infinite_out = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'infinite_out', 'sanitize_text_field', 1);
			$min_size = (int) WDW_S_Library::esc_sanitize_data($params_array_layer, 'min_size', 'sanitize_text_field', 11);
			$title_dimension = array();
			$title_dimension['title'] = $title;
			$title_dimension['attr_width'] = $attr_width;		
			$title_dimension['attr_height'] = $attr_height;
			$title_dimension = json_encode($title_dimension);
			if ($title) {
			  if (strpos($layer_id, 'pr_') !== FALSE || $save_as_copy) {
				$save = $wpdb->insert($wpdb->prefix . 'wdslayer', array(
				  'slide_id' => $slide_id_pr,
				  'title' => $title_dimension,
				  'type' => $type,
				  'depth' => $depth,
				  'text' => $text,
				  'link' => $link,
				  'left' => $left,
				  'top' => $top,
				  'hide_on_mobile' => $hide_on_mobile,
				  'start' => $start,
				  'end' => $end,
				  'published' => $published,
				  'color' => $color,
				  'size' => $size,
				  'ffamily' => $ffamily,
				  'fweight' => $fweight,
				  'padding' => $padding,
				  'fbgcolor' => $fbgcolor,
				  'transparent' => $transparent,
				  'border_width' => $border_width,
				  'border_style' => $border_style,
				  'border_color' => $border_color,
				  'border_radius' => $border_radius,
				  'shadow' => $shadow,
				  'image_url' => $image_url,
				  'image_width' => $image_width,
				  'image_height' => $image_height,
				  'image_scale' => $image_scale,
				  'alt' => $alt,
				  'imgtransparent' => $imgtransparent,
				  'social_button' => $social_button,
				  'hover_color' => $hover_color,
				  'layer_effect_in' => $layer_effect_in,
				  'layer_effect_out' => $layer_effect_out,
				  'duration_eff_in' => $duration_eff_in,
				  'duration_eff_out' => $duration_eff_out,
				  'target_attr_layer' => $target_attr_layer,
				  'hotp_width' => $hotp_width,
				  'hotp_fbgcolor' => $hotp_fbgcolor,
				  'hotp_border_width' => $hotp_border_width,
				  'hotp_border_style' => $hotp_border_style,
				  'hotp_border_color' => $hotp_border_color,
				  'hotp_border_radius' => $hotp_border_radius,
				  'hotp_text_position' => $hotp_text_position,
				  'google_fonts' => $google_fonts,
				  'add_class' => $add_class,
				  'layer_video_loop' => $layer_video_loop,
				  'youtube_rel_layer_video' => $youtube_rel_layer_video,
				  'hotspot_animation' => $hotspot_animation,
				  'layer_callback_list' => $layer_callback_list,
				  'hotspot_text_display' => $hotspot_text_display,
				  'hover_color_text' => $hover_color_text,
				  'text_alignment' => $text_alignment,
				  'link_to_slide' => $link_to_slide,
				  'align_layer' => $align_layer,
				  'static_layer' => $static_layer,
				  'infinite_in' => $infinite_in,
				  'infinite_out' => $infinite_out,
				  'min_size' => $min_size,
				));
			  }
			  else {
				$save = $wpdb->update($wpdb->prefix . 'wdslayer', array(
				  'title' => $title_dimension,
				  'type' => $type,
				  'depth' => $depth,
				  'text' => $text,
				  'link' => $link,
				  'left' => $left,
				  'top' => $top,
				  'hide_on_mobile' => $hide_on_mobile,
				  'start' => $start,
				  'end' => $end,
				  'published' => $published,
				  'color' => $color,
				  'size' => $size,
				  'ffamily' => $ffamily,
				  'fweight' => $fweight,
				  'padding' => $padding,
				  'fbgcolor' => $fbgcolor,
				  'transparent' => $transparent,
				  'border_width' => $border_width,
				  'border_style' => $border_style,
				  'border_color' => $border_color,
				  'border_radius' => $border_radius,
				  'shadow' => $shadow,
				  'image_url' => $image_url,
				  'image_width' => $image_width,
				  'image_height' => $image_height,
				  'image_scale' => $image_scale,
				  'alt' => $alt,
				  'imgtransparent' => $imgtransparent,
				  'social_button' => $social_button,
				  'hover_color' => $hover_color,
				  'layer_effect_in' => $layer_effect_in,
				  'layer_effect_out' => $layer_effect_out,
				  'duration_eff_in' => $duration_eff_in,
				  'duration_eff_out' => $duration_eff_out,
				  'target_attr_layer' => $target_attr_layer,
				  'hotp_width' => $hotp_width,
				  'hotp_fbgcolor' => $hotp_fbgcolor,
				  'hotp_border_width' => $hotp_border_width,
				  'hotp_border_style' => $hotp_border_style,
				  'hotp_border_color' => $hotp_border_color,
				  'hotp_border_radius' => $hotp_border_radius,
				  'hotp_text_position' => $hotp_text_position,
				  'google_fonts' => $google_fonts,
				  'add_class' => $add_class,
				  'layer_video_loop' => $layer_video_loop,
				  'youtube_rel_layer_video' => $youtube_rel_layer_video,
				  'hotspot_animation' => $hotspot_animation,
				  'layer_callback_list' => $layer_callback_list,
				  'hotspot_text_display' => $hotspot_text_display,
				  'hover_color_text' => $hover_color_text,
				  'text_alignment' => $text_alignment,
				  'link_to_slide' => $link_to_slide,
				  'align_layer' => $align_layer,
				  'static_layer' => $static_layer,
				  'infinite_in' => $infinite_in,
				  'infinite_out' => $infinite_out,
				  'min_size' => $min_size,
				), array('id' => $layer_id));
			  }
			}
		  }
		}
	}

	public function set_watermark() {
	  // Save before set watermark.
    $save = $this->save_slider_db();
    $slider_id = $save['id'];
    $this->save_slide_db( $slider_id );

    global $wpdb;

    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));
    $slider = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE `id`="%d"', $slider_id));

    switch ($slider->built_in_watermark_type) {
      case 'text': {
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image = str_replace('{site_url}', site_url(), $slider_image);
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
            $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
            $image_name = substr($slider_image_dir, $last_slash_pos);
            $new_image = $dest_dir . '.original/' . $image_name;
            if (!is_dir($dest_dir . '.original')) {
              mkdir($dest_dir . '.original', 0777, TRUE);
            }
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $this->set_text_watermark($slider_image_dir, $slider_image_dir, $slider->built_in_watermark_text, $slider->built_in_watermark_font, $slider->built_in_watermark_font_size, '#' . $slider->built_in_watermark_color, $slider->built_in_watermark_opacity, $slider->built_in_watermark_position);
          }
        }
        break;
      }
      case 'image': {
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image = str_replace('{site_url}', site_url(), $slider_image);
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
            $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
            $image_name = substr($slider_image_dir, $last_slash_pos);
            $new_image = $dest_dir . '.original/' . $image_name;
            if (!is_dir($dest_dir . '.original')) {
              mkdir($dest_dir . '.original', 0777, TRUE);
            }
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $slider->built_in_watermark_url = str_replace('{site_url}', site_url(), $slider->built_in_watermark_url);
            $watermark_image_dir = str_replace(site_url() . '/', ABSPATH, $slider->built_in_watermark_url);
            $this->set_image_watermark($slider_image_dir, $slider_image_dir, $watermark_image_dir, $slider->built_in_watermark_size, $slider->built_in_watermark_size, $slider->built_in_watermark_position);
          }
        }
        break;
      }
      default: {
        break;
      }
    }
  }

	public function reset_watermark() {
	  // Save before reset watermark.
    $save = $this->save_slider_db();
    $slider_id = $save['id'];
    $this->save_slide_db( $slider_id );

    global $wpdb;

    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));

    foreach ($slider_images as $slider_image) {
      if ($slider_image) {
        $slider_image = str_replace('{site_url}', site_url(), $slider_image);
        $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
        $last_slash_pos = strrpos($slider_image_dir, '/') + 1;
        $dest_dir = substr($slider_image_dir, 0, $last_slash_pos);
        $image_name = substr($slider_image_dir, $last_slash_pos);
        $new_image = $dest_dir . '.original/' . $image_name;
        if (file_exists($new_image)) {
          copy($new_image, $slider_image_dir);
        }
        else {
          // For 1.0.1 version.
          $last_dot_pos = strrpos($slider_image_dir, '.');
          $base_name = substr($slider_image_dir, 0, $last_dot_pos);
          $ext = substr($slider_image_dir, strlen($base_name));
          $new_image = $base_name . '-original' . $ext;
          if (file_exists($new_image)) {
            copy($new_image, $slider_image_dir);
          }
        }
      }
    }
  }

	function wds_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		if (strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		}
		else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb;
	}

  function wds_imagettfbboxdimensions($font_size, $font_angle, $font, $text) {
    $box = @ImageTTFBBox($font_size, $font_angle, $font, $text) or die;
    $max_x = max(array($box[0], $box[2], $box[4], $box[6]));
    $max_y = max(array($box[1], $box[3], $box[5], $box[7]));
    $min_x = min(array($box[0], $box[2], $box[4], $box[6]));
    $min_y = min(array($box[1], $box[3], $box[5], $box[7]));
    return array(
      "width"  => ($max_x - $min_x),
      "height" => ($max_y - $min_y)
    );
  }

  function set_text_watermark($original_filename, $dest_filename, $watermark_text, $watermark_font, $watermark_font_size, $watermark_color, $watermark_transparency, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);

    $watermark_transparency = 127 - ((100 - $watermark_transparency) * 1.27);
    @ini_set('memory_limit', '-1');
    list($width, $height, $type) = getimagesize($original_filename);
    $watermark_image = imagecreatetruecolor($width, $height);

    $watermark_color = $this->wds_hex2rgb($watermark_color);
    $watermark_color = imagecolorallocatealpha($watermark_image, $watermark_color[0], $watermark_color[1], $watermark_color[2], $watermark_transparency);
    $watermark_font = WDS()->plugin_dir . '/fonts/' . $watermark_font;
    $watermark_font_size = ($height * $watermark_font_size / 500);
    $watermark_position = explode('-', $watermark_position);
    $watermark_sizes = $this->wds_imagettfbboxdimensions($watermark_font_size, 0, $watermark_font, $watermark_text);

    $top = $height - 5;
    $left = $width - $watermark_sizes['width'] - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = $watermark_sizes['height'] + 5;
        break;
      case 'middle':
        $top = ($height + $watermark_sizes['height']) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_sizes['width']) / 2;
        break;
    }
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagejpeg ($image, $dest_filename, 100);
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imageColorAllocateAlpha($image, 0, 0, 0, 127);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      imageColorAllocateAlpha($watermark_image, 0, 0, 0, 127);
      imagecopy($watermark_image, $image, 0, 0, 0, 0, $width, $height);
      imagettftext($watermark_image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagealphablending($watermark_image, FALSE);
      imagesavealpha($watermark_image, TRUE);
      imagegif($watermark_image, $dest_filename);
      imagedestroy($image);
    }
    imagedestroy($watermark_image);
    @ini_restore('memory_limit');
  }

  function set_image_watermark($original_filename, $dest_filename, $watermark_url, $watermark_height, $watermark_width, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);
    $watermark_url = htmlspecialchars_decode($watermark_url, ENT_COMPAT | ENT_QUOTES);

    list($width, $height, $type) = getimagesize($original_filename);
    list($width_watermark, $height_watermark, $type_watermark) = getimagesize($watermark_url);

    $watermark_width = $width * $watermark_width / 100;
    $watermark_height = $height_watermark * $watermark_width / $width_watermark;
        
    $watermark_position = explode('-', $watermark_position);
    $top = $height - $watermark_height - 5;
    $left = $width - $watermark_width - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = 5;
        break;
      case 'middle':
        $top = ($height - $watermark_height) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_width) / 2;
        break;
    }
    @ini_set('memory_limit', '-1');
    if ($type_watermark == 2) {
      $watermark_image = imagecreatefromjpeg($watermark_url);        
    }
    elseif ($type_watermark == 3) {
      $watermark_image = imagecreatefrompng($watermark_url);
    }
    elseif ($type_watermark == 1) {
      $watermark_image = imagecreatefromgif($watermark_url);      
    }
    else {
      return false;
    }

    $watermark_image_resized = imagecreatetruecolor($watermark_width, $watermark_height);
    imagecolorallocatealpha($watermark_image_resized, 255, 255, 255, 127);
    imagealphablending($watermark_image_resized, FALSE);
    imagesavealpha($watermark_image_resized, TRUE);
    imagecopyresampled ($watermark_image_resized, $watermark_image, 0, 0, 0, 0, $watermark_width, $watermark_height, $width_watermark, $height_watermark);
        
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      if ($dest_filename <> '') {
        imagejpeg ($image, $dest_filename, 100); 
      } else {
        header('Content-Type: image/jpeg');
        imagejpeg($image, null, 100);
      };
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      $tempimage = imagecreatetruecolor($width, $height);
      imagecopy($tempimage, $image, 0, 0, 0, 0, $width, $height);
      imagecopy($tempimage, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagegif($tempimage, $dest_filename);
      imagedestroy($image);
      imagedestroy($tempimage);
    }
    imagedestroy($watermark_image);
    @ini_restore('memory_limit');
  }

  /**
   * Create frontend js file.
   *
   * @param int $id
   *
   * @return mixed
   */
	private function create_frontend_js_file( $id = 0 ) {
		require_once WDS()->plugin_dir . "/admin/models/WDSModelSliders_wds.php";
		$model = new WDSModelSliders_wds();
		return $model->create_frontend_js_file( $id = 0 );
	}

  /**
   * Remove frontend js file.
   *
   * @param  int $id
   */
	private function remove_frontend_js_file( $id = 0 ) {
		$wp_upload_dir = wp_upload_dir();
		if ( is_file($wp_upload_dir['basedir'] . '/slider-wd-scripts/script-' . $id . '.js') ){
			unlink( $wp_upload_dir['basedir'] . '/slider-wd-scripts/script-' . $id . '.js' );
		}
	}
}