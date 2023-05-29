<?php
class WDSViewSlider {

  private $model;

  public function __construct( $model ) {
    $this->model = $model;
    if ( !WDW_S_Library::elementor_is_active() ) {
        wp_enqueue_style(WDS()->prefix . '_frontend');
        wp_enqueue_script(WDS()->prefix . '_frontend');
      }
    }

  public function display( $id, $from_shortcode = 0, $wds = 0 ) {
	  require_once(WDS()->plugin_dir . '/framework/WDW_S_Library.php');
    if ( !WDS()->is_free ) {
      require_once(WDS()->plugin_dir . '/framework/WDW_S_LibraryEmbed.php');
    }
    $slider_row = $this->model->get_slider_row_data($id);
    if ( !$slider_row ) {
      echo WDW_S_Library::message(__('There is no slider selected or the slider was deleted.', WDS()->prefix), 'wd_error');
      return;
    }
    if ( !$slider_row->published ) {
      return;
    }

    $bull_position = $slider_row->bull_position;
    $bull_style_active = str_replace('-o', '', $slider_row->bull_style);
    $bull_style_deactive = $slider_row->bull_style;
    $order_dir = isset($slider_row->order_dir) ? $slider_row->order_dir : 'asc';
    $slide_rows = $this->model->get_slide_rows_data( $id, $order_dir );
    if (!$slide_rows) {
      echo WDW_S_Library::message(__('There are no slides in this slider.', WDS()->prefix), 'wd_error');
      return;
    }

	  $no_video_image = WDS()->plugin_url . '/images/no-video.png';
    $image_width = $slider_row->width;
    $image_height = $slider_row->height;
    $slides_count = count($slide_rows);
    $enable_slideshow_shuffle = $slider_row->shuffle;
    $enable_prev_next_butt = $slider_row->prev_next_butt;
    $show_thumbnail = isset($slider_row->show_thumbnail) ? $slider_row->show_thumbnail : 0;
    $enable_play_paus_butt = $slider_row->play_paus_butt;
    $enable_slideshow_music = $slider_row->music;
    $slideshow_music_url = $slider_row->music_url;
    $filmstrip_direction = ($slider_row->film_pos == 'right' || $slider_row->film_pos == 'left') ? 'vertical' : 'horizontal';
    $filmstrip_position = $slider_row->film_pos;
    $filmstrip_small_screen = $slider_row->film_small_screen;
    $filmstrip_thumb_margin_hor = $slider_row->film_tmb_margin;
    if ($filmstrip_position != 'none') {
      if ($filmstrip_direction == 'horizontal') {
        $filmstrip_width = $slider_row->film_thumb_width;
        $filmstrip_height = $slider_row->film_thumb_height;
      }
      else {
        $filmstrip_width = $slider_row->film_thumb_width;
        $filmstrip_height = $slider_row->film_thumb_height;
      }
    }
    else {
      $filmstrip_width = 0;
      $filmstrip_height = 0;
    }

    if ($slider_row->start_slide_num == 0) {
      $slide_ids = array();
      foreach ($slide_rows as $slide_row) {
        $slide_ids[] += $slide_row->id;
      }
      $current_image_id = $slide_ids[array_rand($slide_ids)];
    }
    else {
      if ($slider_row->start_slide_num > 0 && $slider_row->start_slide_num <= $slides_count) {
        $start_slide_num = $slider_row->start_slide_num - 1;
      }
      else {
        $start_slide_num = 0;
      }
      $current_image_id = ($slide_rows ? $slide_rows[$start_slide_num]->id : 0);
    }
    global $wp;
    $current_url = add_query_arg($wp->query_string, '', home_url($wp->request));

    $carousel = isset($slider_row->carousel) ? $slider_row->carousel : FALSE;
    $preload_images = $slider_row->carousel ? FALSE : $slider_row->preload_images;
    $layers_rows = array();
    foreach ($slide_rows as $slide_row) {
      $layers_rows[$slide_row->id] = $this->model->get_layers_row_data($slide_row->id, $id);
    }
    // Add incline scripts.
    $style = WDW_S_Library::create_css( $id, $slider_row, $slide_rows, $layers_rows, $wds );
    /*wp_add_inline_style('wds_frontend', $style);*/
    echo '<style id="wd-slider-' . $wds .'">' . $style . '</style>';
    ?>
	  <div id="wds_container1_<?php echo $wds; ?>" class="wds_slider_cont" data-wds="<?php echo $wds; ?>">
      <div class="wds_loading">
        <div class="wds_loading_img"></div>
      </div>
      <div id="wds_container2_<?php echo $wds; ?>">
        <div class="wds_slideshow_image_wrap_<?php echo $wds; ?>">
          <?php
          if ($filmstrip_position != 'none' && $slides_count > 1) {
            ?>
          <div class="wds_slideshow_filmstrip_container_<?php echo $wds; ?> wds_slideshow_filmstrip_container" <?php if($filmstrip_small_screen > 0) { ?> data-small_screen="<?php echo $filmstrip_small_screen ?>" <?php } ?>>
            <div class="wds_slideshow_filmstrip_left_<?php echo $wds; ?>"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?>"></i></div>
            <div class="wds_slideshow_filmstrip_<?php echo $wds; ?>">
              <div class="wds_slideshow_filmstrip_thumbnails_<?php echo $wds; ?>">
                <?php
                foreach ($slide_rows as $key => $slide_row) {
                  if ($slide_row->id == $current_image_id) {
                    $current_pos = $key * (($filmstrip_direction == 'horizontal' ? $filmstrip_width : $filmstrip_height) + $filmstrip_thumb_margin_hor);
                    $current_key = $key;
                  }
                  if ($slide_row->type == 'video') {
                    $video_thumb_url = is_numeric($slide_row->thumb_url) ? (wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) : '' ): $slide_row->thumb_url;
					$thumb_url = empty($video_thumb_url) ? $no_video_image : $video_thumb_url;
                  }
                  else {
                    $thumb_url = $slide_row->thumb_url;
                  }
                ?>
                <div id="wds_filmstrip_thumbnail_<?php echo $key; ?>_<?php echo $wds; ?>" class="wds_slideshow_filmstrip_thumbnail_<?php echo $wds; ?> <?php echo (($slide_row->id == $current_image_id) ? 'wds_slideshow_thumb_active_' . $wds : 'wds_slideshow_thumb_deactive_' . $wds); ?>">
                  <div onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), '<?php echo $key; ?>', wds_data_<?php echo $wds; ?>);
					  <?php if ($carousel) { ?>
					  wds_carousel[<?php echo $wds; ?>].shift(jQuery('.wds_slider_car_image<?php echo $wds; ?>[data-image-id=<?php echo $slide_row->id; ?>]'));
					  <?php } ?>"
                       data-image-id="<?php echo $slide_row->id; ?>"
                       data-image-key="<?php echo $key; ?>"
                       class="wds_slideshow_filmstrip_thumbnail_img_<?php echo $wds; ?>"
                       style="background-image: url('<?php echo addslashes(htmlspecialchars_decode($thumb_url, ENT_QUOTES)); ?>');"></div>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="wds_slideshow_filmstrip_right_<?php echo $wds; ?>"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>"></i></div>
          </div>
            <?php
          }
          ?>
          <div id="wds_slideshow_image_container_<?php echo $wds; ?>" class="wds_slideshow_image_container_<?php echo $wds; ?> wds_slideshow_image_container">
            <?php
              if ($bull_position != 'none' && $slides_count > 1) {
                ?>
              <div class="wds_slideshow_dots_container_<?php echo $wds; ?>" onmouseleave="wds_hide_thumb(<?php echo $wds; ?>)">
                <div class="wds_slideshow_dots_thumbnails_<?php echo $wds; ?>">
                  <?php
                  foreach ($slide_rows as $key => $slide_row) {
                    if ($slider_row->bull_butt_img_or_not == 'style') {
                      ?>
					  <i id="wds_dots_<?php echo $key; ?>_<?php echo $wds; ?>"
						 class="wds_slideshow_dots_<?php echo $wds; ?> fa <?php echo (($slide_row->id == $current_image_id) ? $bull_style_active . ' wds_slideshow_dots_active_' . $wds : $bull_style_deactive . ' wds_slideshow_dots_deactive_' . $wds); ?>"
						 <?php echo  $show_thumbnail == 1 ? 'onmouseover="wds_show_thumb(' . $key . ', ' . $wds . ')"' : ''; ?>
						 onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), '<?php echo $key; ?>', wds_params[<?php echo $wds; ?>].wds_data);
						  <?php if ($carousel) { ?>
							wds_carousel[<?php echo $wds; ?>].shift(jQuery('.wds_slider_car_image<?php echo $wds; ?>[data-image-id=<?php echo $slide_row->id; ?>]'));
						  <?php } ?>">
					  </i>
                      <?php
                    }
                    else {
                      ?>
                  <span id="wds_dots_<?php echo $key; ?>_<?php echo $wds; ?>"
                        class="wds_slideshow_dots_<?php echo $wds; ?> <?php echo (($slide_row->id == $current_image_id) ? ' wds_slideshow_dots_active_' . $wds : ' wds_slideshow_dots_deactive_' . $wds); ?>"
                        <?php echo  $show_thumbnail == 1 ? 'onmouseover="wds_show_thumb(' . $key . ', ' . $wds .')"' : ''; ?> 
                        onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), '<?php echo $key; ?>', wds_params[<?php echo $wds; ?>].wds_data);
						<?php if ($carousel) { ?>
							wds_carousel[<?php echo $wds; ?>].shift(jQuery('.wds_slider_car_image<?php echo $wds; ?>[data-image-id=<?php echo $slide_row->id; ?>]'));
						<?php } ?>">
                    <?php echo ($slider_row->bull_butt_img_or_not == 'text') ? '&nbsp;' . $slide_row->title . '&nbsp;' : ''; ?>
                  </span>
                      <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <?php 
              if ($show_thumbnail == 1) {
                ?>
              <div class="wds_bulframe_<?php echo $wds; ?>"></div>
                <?php 
              }
              ?>
                <?php
              }
              if ($slider_row->timer_bar_type == 'top' ||  $slider_row->timer_bar_type == 'bottom') {
                ?>
                <div class="wds_line_timer_container_<?php echo $wds; ?>"><div class="wds_line_timer_<?php echo $wds; ?>"></div></div>			
                <?php
              }
              elseif ($slider_row->timer_bar_type != 'none') {
                ?>
                <div class="wds_circle_timer_container_<?php echo $wds; ?>">
                  <div class="wds_circle_timer_<?php echo $wds; ?>">
                    <div class="wds_circle_timer_parts_<?php echo $wds; ?>">
                      <div class="wds_circle_timer_part_<?php echo $wds; ?>">
                        <div class="wds_circle_timer_small_parts_<?php echo $wds; ?> wds_animated" style="border-radius:100% 0% 0% 0%;" id="top_left_<?php echo $wds; ?>"></div>
                        <div class="wds_circle_timer_small_parts_<?php echo $wds; ?> wds_animated" style="border-radius:0% 0% 0% 100%;z-index:150;" id="bottom_left_<?php echo $wds; ?>"></div>
                      </div>
                      <div class="wds_circle_timer_part_<?php echo $wds; ?>">
                        <div class="wds_circle_timer_small_parts_<?php echo $wds; ?> wds_animated" style="border-radius:0% 100% 0% 0%;" id="top_right_<?php echo $wds; ?>"></div>
                        <div class="wds_circle_timer_small_parts_<?php echo $wds; ?> wds_animated" style="border-radius:0% 0% 100% 0%;" id="bottom_right_<?php echo $wds; ?>"></div>
                      </div>
                    </div>
                    <div class="wds_circle_timer_center_cont_<?php echo $wds; ?>">
                       <div class="wds_circle_timer_center_<?php echo $wds; ?>">
                        <div></div>
                       </div> 
                    </div>					
                  </div>
                </div>
                <?php
              }
              ?>			
            <div class="wds_slide_container_<?php echo $wds; ?>" id="wds_slide_container_<?php echo $wds; ?>">
              <div class="wds_slide_bg_<?php echo $wds; ?>">
                <div class="wds_slider_<?php echo $wds; ?>">
                <?php
                foreach ($slide_rows as $key => $slide_row) {
                  $is_video = $slide_row->type;
                  $is_instagram_image = preg_match('/INSTAGRAM_IMAGE/', $slide_row->type) == 1 ? TRUE : FALSE;
                  if ($slide_row->id == $current_image_id) {
                    if ($is_video != "image") {
                      $play_pause_button_display = 'none';
                    }
                    else {
                      $play_pause_button_display = '';
                      $current_image_url = $slide_row->image_url;
                      $current_image_url = addslashes(htmlspecialchars_decode($current_image_url, ENT_QUOTES));
                    }
                    $current_key = $key;
                    $image_div_num = '';
                  }
                  else {
                    $image_div_num = '_second';
                  }
                  $share_image_url = urlencode($is_video != 'image' ? $slide_row->thumb_url : $slide_row->image_url);
                  $share_url = add_query_arg(array('action' => 'WDSShare', 'image_id' => $slide_row->id, 'curr_url' => $current_url), admin_url('admin-ajax.php'));
                  ?>
                  <span 
                  <?php if ($carousel) { ?>
                    onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), '<?php echo $key; ?>', wds_params[<?php echo $wds; ?>].wds_data); wds_carousel[<?php echo $wds; ?>].shift(this);"
				  <?php } ?>
					class="wds_slider_car_image<?php echo $wds; ?> wds_slideshow_image<?php echo $image_div_num; ?>_spun_<?php echo $wds; ?>" id="wds_image_id_<?php echo $wds; ?>_<?php echo $slide_row->id; ?>"
                    data-image-id="<?php echo $slide_row->id; ?>"
                    data-image-key="<?php echo $key; ?>">
                    <span class="wds_slideshow_image_spun1_<?php echo $wds; ?>">
                      <span class="wds_slideshow_image_spun2_<?php echo $wds; ?>">
                        <?php 
                        if ($is_video == 'image' || $is_instagram_image) {
                          ?>
                        <span data-img-id="wds_slideshow_image<?php echo $image_div_num; ?>_<?php echo $wds; ?>"
                             class="wds_slideshow_image_<?php echo $wds; ?>"
                             onclick="<?php echo $slide_row->link ? 'wds_slide_redirect_link(event, \'' . $slide_row->link . '\', \'' . ($slide_row->target_attr_slide ? '_blank' : '_self') . '\')' : ''; ?>"
                              <?php if($slider_row->effect === 'zoomFade') { ?>
                              style="<?php echo $slide_row->link ? 'cursor: pointer;' : ''; ?>"
                              <?php } else { ?>
                              style="<?php echo $slide_row->link ? 'cursor: pointer;' : ''; ?><?php echo ((!$preload_images || $image_div_num == '') ? "background-image: url('" . ($is_instagram_image ? "//instagram.com/p/" . $slide_row->image_url . "/media/?size=l" : addslashes(htmlspecialchars_decode ($slide_row->image_url,ENT_QUOTES))) . "');" : ""); ?>"
                              <?php } ?>
                             data-image-id="<?php echo $slide_row->id; ?>"
                             data-image-key="<?php echo $key; ?>">
                          <?php
                        }
                        elseif ($is_video == 'video' && !WDS()->is_free) {
                          $thumb_url = is_numeric($slide_row->thumb_url) ? (wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) : '' ): $slide_row->thumb_url;
						?>
                        <span data-img-id="wds_slideshow_image<?php echo $image_div_num; ?>_<?php echo $wds; ?>"
                             class="wds_slideshow_image_<?php echo $wds; ?>"
                             data-image-id="<?php echo $slide_row->id; ?>"
                             data-image-key="<?php echo $key; ?>">
                          <span style="display:<?php echo ($slide_row->link)?'block':'none'; ?> " class="wds_play_btn_cont" onclick="wds_video_play_pause(<?php echo $wds; ?>, wds_slide_<?php echo $wds; ?>_<?php echo $slide_row->id; ?>)" >
                              <span class="wds_bigplay_<?php echo $wds; ?> <?php echo ($slide_row->target_attr_slide)? 'wds_hide':'' ?>"></span>
                          </span>
                          <video poster="<?php echo WDS()->plugin_url . '/images/blank.gif' ?>" style="background-image: url('<?php echo !empty($thumb_url) ? $thumb_url : $no_video_image ?>');" <?php echo isset($slide_row->video_loop) && $slide_row->video_loop == 1 ? 'loop' : ''; ?> <?php echo $slide_row->link == '1' ? "controls": ""; ?> <?php if(isset($slide_row->mute)) { echo $slide_row->mute == '1' ? 'muted' : ''; }?> id="wds_slide_<?php echo $wds; ?>_<?php echo $slide_row->id; ?>">
                            <source src="<?php echo $slide_row->image_url; ?>" type="video/mp4" id="wds_source<?php echo $slide_row->id; ?>">
                          </video>
                          <?php
                        }
                        elseif ( !WDS()->is_free ) {
                          $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/', $slide_row->type) == 1 ? TRUE : FALSE;
                          if ($is_embed_instagram_post) {
                            $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $filmstrip_width : 0);
                            $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $filmstrip_height : 0);
                            if ($post_height < $post_width + 88) {
                              $post_width = $post_height - 88; 
                            }
                            else {
                              $post_height = $post_width + 88;
                            }
                            $embed_style = "border-style: none; width: " . $post_width . "px; height: " . $post_height . "px; vertical-align: middle; display: inline-block; position: relative;";
                          }
                          else {
                            $embed_style = "border-style: none; width: inherit; height: inherit; vertical-align: middle; display: table-cell;";
                          }
                          ?>
                        <span data-img-id="wds_slideshow_image<?php echo $image_div_num; ?>_<?php echo $wds; ?>" class="wds_slideshow_video_<?php echo $wds; ?>" data-image-id="<?php echo $slide_row->id; ?>"
                       	 data-image-key="<?php echo $key; ?>">
                          <?php
                          if ($carousel) {
                            ?>
                          <span class="wds_video_hide<?php echo $wds; ?>"></span>
                            <?php
                          }
                          $video_autoplay = ($key == $current_key && $slide_row->target_attr_slide) ? 1 : 0;
                          $video_loop = isset($slide_row->video_loop) ? $slide_row->video_loop : 0;
                          $youtube_rel_video = isset($slide_row->youtube_rel_video) ? $slide_row->youtube_rel_video : 0;
                          WDW_S_LibraryEmbed::display_embed($slide_row->type, $slide_row->image_url, array('class' => "wds_video_frame_" . $wds, "data-wds" => $wds, 'allowfullscreen' => "allowfullscreen", 'style' => $embed_style), $video_autoplay, $video_loop, "wds_image_id_" . $wds . "_" . $slide_row->id . "_iframe", $youtube_rel_video);
                          ?>
                          <?php
                        }
                        if (isset($layers_rows[$slide_row->id]) && !empty($layers_rows[$slide_row->id])) {
                          foreach ($layers_rows[$slide_row->id] as $layer_key => $layer) {
                            if ($layer->published) {
                              $prefix = 'wds_' . $wds . '_slide' . $slide_row->id . '_layer' . $layer->id;
                              $left_percent = $slider_row->width ? 100 * $layer->left / $slider_row->width : 0;
                              $top_percent = $slider_row->height ? 100 * $layer->top / $slider_row->height : 0;
                              $video_width_percent = $slider_row->width ? 100 * $layer->image_width / $slider_row->width : 0;
                              $video_height_percent = $slider_row->height ? 100 * $layer->image_height / $slider_row->height : 0;
                              $layer_add_class = isset($layer->add_class) ? $layer->add_class : '';
                              $link_to_slide = (isset($layer->link_to_slide)) ? $layer->link_to_slide : 0;
                              $layer_callback_list = (isset($layer->layer_callback_list) && ($layer->layer_callback_list != '')) ? "wds_callbackItems('" . $wds . "', '" . $layer->layer_callback_list . "', '" . $link_to_slide . "');" : '';
                              $hotspot_text_display = (isset($layer->hotspot_text_display) && $layer->hotspot_text_display == 'click') ? 'click' : 'hover';
                              switch ($layer->type) {
                                case 'text': {
                                  ?>
                                <span class="wds_layer_<?php echo $layer->id; ?>" data-class="<?php echo $layer_add_class; ?>" data-type="wds_text_parent" data-row-key="<?php echo $key;?>" data-layer-key="<?php echo $layer_key;?>" id="<?php echo $prefix; ?>" data-left-percent="<?php echo $left_percent ?>" data-wds-fsize="<?php echo $layer->size; ?>" data-wds-fmin-size="<?php echo $layer->min_size; ?>"
                                      style="<?php echo $layer->image_width ? 'width: ' . $layer->image_width . '%; ' : ''; ?>
                                             <?php echo $layer->image_height ? 'height: ' . $layer->image_height . '%; ' : ''; ?>
                                             word-wrap: <?php echo ($layer->image_scale ? 'break-all' : 'normal'); ?>;
                                             text-align: initial;
                                             <?php echo $layer->link || $layer_callback_list ? 'cursor: pointer; ' : ''; ?>
                                             opacity: 1;
                                             filter: 'Alpha(opacity=100)';
                                             display: inline-block;
                                             position: absolute;
                                             left: <?php echo $left_percent; ?>%;
                                             top: <?php echo $top_percent; ?>%;
                                             z-index: <?php echo $layer->depth; ?>;
                                             color: #<?php echo $layer->color; ?>;
                                             font-family: <?php echo $layer->ffamily; ?>;
                                             font-weight: <?php echo $layer->fweight; ?>;
                                             background-color: <?php echo WDW_S_Library::spider_hex2rgba($layer->fbgcolor, (100 - $layer->transparent) / 100); ?>;
                                             border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                             border-radius: <?php echo $layer->border_radius; ?>;
                                             box-shadow: <?php echo $layer->shadow; ?>;
                                             text-align: <?php echo $layer->text_alignment; ?>"
                                      onclick="<?php echo $layer->link ? 'window.open(\'' . $layer->link . '\', \'' . ($layer->target_attr_layer ? '_blank' : '_self') . '\');' : $layer_callback_list; ?>event.stopPropagation();"><?php echo str_replace(array("\r\n", "\r", "\n"), "<br>", $from_shortcode ? do_shortcode($layer->text) : $layer->text); ?></span>
                                  <?php
                                  break;
                                }
                                case 'image': {
                                  if ( WDS()->is_free ) {
                                    break;
                                  }
                                  ?>
                                <img class="wds_layer_<?php echo $layer->id; ?>" data-class="<?php echo $layer_add_class; ?>" id="<?php echo $prefix; ?>" src="<?php echo $layer->image_url; ?>"
                                     style="<?php echo $layer->link || $layer_callback_list ? 'cursor: pointer; ' : ''; ?>
                                            opacity: <?php echo number_format((100 - $layer->imgtransparent) / 100, 2, ".", ""); ?>;
                                            filter: Alpha(opacity=<?php echo 100 - $layer->imgtransparent; ?>);
                                            position: absolute;
                                            left: <?php echo $left_percent; ?>%;
                                            top: <?php echo $top_percent; ?>%;
                                            z-index: <?php echo $layer->depth; ?>;
                                            border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                            border-radius: <?php echo $layer->border_radius; ?>;
                                            box-shadow: <?php echo $layer->shadow; ?>"
                                     onclick="<?php echo $layer->link ? 'window.open(\'' . $layer->link . '\', \'' . ($layer->target_attr_layer ? '_blank' : '_self') . '\');' : $layer_callback_list; ?>event.stopPropagation();"
                                     data-wds-scale="<?php echo $layer->image_scale; ?>"
                                     data-wds-image-width="<?php echo $layer->image_width; ?>"
                                     data-wds-image-height="<?php echo $layer->image_height; ?>"
                                     data-wds-image-top="<?php echo $top_percent; ?>"
                                     alt="<?php echo $layer->alt; ?>"
                                     title="<?php echo $layer->alt; ?>" />
                                  <?php
                                  break;
                                }
                                case 'video': {
                                  if ( WDS()->is_free ) {
                                    break;
                                  }
                                  $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/', $layer->alt) == 1 ? TRUE :FALSE;
                                  if ($is_embed_instagram_post) {
                                    $post_width = $layer->image_width;
                                    $post_height = $layer->image_height;
                                    if ($post_height < $post_width + 88) {
                                      $post_width = $post_height - 88; 
                                    }
                                    else {
                                     $post_height = $post_width + 88;
                                    }
                                    $layer_embed_style = "border-style: none; width: " . $post_width . "px; height: " . $post_height . "px; vertical-align: middle; display: inline-block; position: relative;";
                                  }
                                  else {
                                    $layer_embed_style = "border-style: none;";
                                  }
                                  ?>
                                <span class="wds_layer_<?php echo $layer->id; ?>" data-class="<?php echo $layer_add_class; ?>" id="<?php echo $prefix; ?>" data-wds-fsize="<?php echo $layer->size; ?>"
                                      style="<?php echo $layer->image_width ? 'width: ' . $video_width_percent . '%; ' : ''; ?>
                                             <?php echo $layer->image_height ? 'height: ' . $video_height_percent . '%; ' : ''; ?>
                                             position: absolute;
                                             overflow: hidden;
                                             left: <?php echo $left_percent; ?>%;
                                             top: <?php echo $top_percent; ?>%;
                                             z-index: <?php echo $layer->depth; ?>;
                                             border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                             border-radius: <?php echo $layer->border_radius; ?>;
                                             box-shadow: <?php echo $layer->shadow; ?>">
                                  <?php echo WDW_S_LibraryEmbed::display_embed($layer->alt, $layer->link, array('class' => "wds_video_layer_frame_" . $wds, "data-wds" => $wds, 'allowfullscreen' => "allowfullscreen", 'style' => $layer_embed_style), 0, 0, $prefix . "_iframe", (isset($layer->image_scale) && $layer->image_scale == "on" ? 1 : 0)); ?>
                                </span>
                                  <?php
                                  break;
                                }
                                case 'upvideo': {
                                  if ( WDS()->is_free ) {
                                    break;
                                  }
                                  $layer_image_url = wp_get_attachment_url(get_post_thumbnail_id($layer->image_url)) ? wp_get_attachment_url(get_post_thumbnail_id($layer->image_url)) : '';
                                  ?>
                                <span class="wds_layer_<?php echo $layer->id; ?>" data-class="<?php echo $layer_add_class; ?>" id="<?php echo $prefix; ?>" data-wds-fsize="<?php echo $layer->size; ?>"
                                      style="<?php echo $layer->image_width ? 'width: ' . $video_width_percent . '%; ' : ''; ?>
                                             <?php echo $layer->image_height ? 'height: ' . $video_height_percent . '%; ' : ''; ?>
                                             position: absolute;
                                             overflow: hidden;
                                             left: <?php echo $left_percent; ?>%;
                                             top: <?php echo $top_percent; ?>%;
                                             z-index: <?php echo $layer->depth; ?>;
                                             border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                             border-radius: <?php echo $layer->border_radius; ?>;
                                             box-shadow: <?php echo $layer->shadow; ?>">
								   <span style="display:<?php echo ($layer->target_attr_layer) ? 'block' : 'none'; ?> " class="wds_play_btn_cont" onclick="wds_video_play_pause_layer(event,<?php echo  $wds ; ?>,<?php echo $slide_row->id ; ?>,<?php echo  $layer->id; ?>)">
								   <span style="display:<?php echo ($layer->image_scale == 'on') ? 'none' : 'block'; ?> " class="wds_bigplay_layer" id="wds_bigplay_layer_<?php echo $wds . '_' . $slide_row->id . '_layer_' . $layer->id; ?>" onclick="wds_video_play_pause_layer(event,<?php echo  $wds ; ?>,<?php echo $slide_row->id ; ?>,<?php echo  $layer->id; ?>)"></span>
								   </span>
                                  <video poster="<?php echo WDS()->plugin_url . '/images/blank.gif' ?>"
                                    style="background-image: url('<?php echo $layer->image_scale != 'on' ? $layer_image_url : ''; ?>'); -webkit-background-size: cover; -moz-background-size: cover;  -o-background-size: cover; background-size: cover;"
                                    <?php echo $layer->layer_video_loop ? "loop": ""; ?>
                                    <?php echo $layer->target_attr_layer == '1' ? "controls ": ""; ?>
                                    id="<?php echo 'wds_slide_' . $wds . '_' . $slide_row->id . '_layer_' . $layer->id; ?>">
                                    <source src="<?php echo $layer->link; ?>" type="video/mp4" id="wds_source<?php echo $layer->id; ?>">
                                  </video>
                                </span>
                                  <?php
                                  break;
                                }
                                case 'social': {
                                  if ( WDS()->is_free ) {
                                    break;
                                  }
                                  ?>
                                  <?php
                                  switch ($layer->social_button) {
                                    case 'facebook': {
                                      ?>
                                  <a class="wds_share_a" onclick="event.stopPropagation();" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', WDS()->prefix); ?>">
                                      <?php
                                      break;
                                    }
                                    case 'twitter': {
                                      ?>
                                  <a class="wds_share_a" onclick="event.stopPropagation();" href="https://twitter.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Twitter', WDS()->prefix); ?>">
                                      <?php
                                      break;
                                    }
                                    case 'pinterest': {
                                      ?>
                                  <a class="wds_share_a" onclick="event.stopPropagation();" href="http://pinterest.com/pin/create/button/?s=100&url=<?php echo urlencode($share_url); ?>&media=<?php echo $share_image_url; ?>&description=<?php echo urlencode($slide_row->title); ?>" target="_blank" title="<?php echo __('Share on Pinterest', WDS()->prefix); ?>">
                                      <?php
                                      break;
                                    }
                                    case 'tumblr': {
                                      ?>
                                  <a class="wds_share_a" onclick="event.stopPropagation();" href="https://www.tumblr.com/share/photo?source=<?php echo $share_image_url; ?>&caption=<?php echo urlencode($slide_row->title); ?>&clickthru=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Tumblr', WDS()->prefix); ?>">
                                      <?php
                                      break;
                                    }
                                    default: {
                                      ?><a><?php
                                      break;
                                    }
                                  }
                                  ?>
                                    <i id="<?php echo $prefix; ?>" class="wds_layer_<?php echo $layer->id; ?> fa fa-<?php echo $layer->social_button; ?>" data-class="<?php echo $layer_add_class; ?>" data-wds-fsize="<?php echo $layer->size; ?>"
                                       style="opacity: <?php echo number_format((100 - $layer->transparent) / 100, 2, ".", ""); ?>;
                                              filter: Alpha(opacity=<?php echo 100 - $layer->transparent; ?>);
                                              position: absolute;
                                              left: <?php echo $left_percent; ?>%;
                                              top: <?php echo $top_percent; ?>%;
                                              z-index: <?php echo $layer->depth; ?>;
                                              color: #<?php echo $layer->color; ?>;"></i>
                                  </a>
                                  <?php
                                  break;
                                }
                                case 'hotspots': {
                                  if ( WDS()->is_free ) {
                                    break;
                                  }
                                  ?>
                                  <span id="<?php echo $prefix; ?>_div"
                                       class="hotspot_container wds_layer_<?php echo $layer->id; ?>_div"
                                       data-type="hotspot"
                                       data-class="<?php echo $layer_add_class; ?>"
                                       data-text-position="<?php echo $layer->hotp_text_position; ?>"
                                       style="width: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px; 
                                              height: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                              z-index: <?php echo $layer->depth; ?>;
                                              position: absolute;
                                              left: <?php echo $left_percent; ?>%;
                                              top: <?php echo $top_percent; ?>%;
                                              display: inline-block;">
                                    <span class="wds_layer_<?php echo $layer->id; ?> wds_layer"
                                          id="<?php echo $prefix; ?>_round"
                                          data-displaytype="<?php echo $hotspot_text_display; ?>"
                                          data-width="<?php echo $layer->hotp_width ? $layer->hotp_width  : 20; ?>"
                                          data-border-width="<?php echo $layer->hotp_border_width; ?>"
                                          style="top: 0;
                                                 left: 0;
                                                 cursor: pointer;
                                                 width: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px; 
                                                 height: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                 border-radius: <?php echo $layer->hotp_border_radius ? $layer->hotp_border_radius : '20px'; ?>;
                                                 border: <?php echo $layer->hotp_border_width; ?>px <?php echo $layer->hotp_border_style; ?> #<?php echo $layer->hotp_border_color; ?>;
                                                 background-color: #<?php echo $layer->hotp_fbgcolor ? $layer->hotp_fbgcolor : "ffffff";?>;
                                                 z-index: <?php echo $layer->depth; ?>;
                                                 position: absolute;
                                                 display: block;
                                                 opacity: 1 !important;"
                                          onclick="<?php echo $layer->link ? 'window.open(\'' . $layer->link . '\', \'' . ($layer->target_attr_layer ? '_blank' : '_self') . '\');' : $layer_callback_list; ?>event.stopPropagation();">	
                                    </span> 
                                    <span class="wds_layer_<?php echo $layer->id; ?>"
                                          id="<?php echo $prefix; ?>_round_effect"
                                          data-width="<?php echo $layer->hotp_width ? $layer->hotp_width  : 20; ?>"
                                          data-border-width="<?php echo $layer->hotp_border_width; ?>"
                                          style="top: 0;
                                                 left: 0;
                                                 width: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px; 
                                                 height: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                 border-radius: <?php echo $layer->hotp_border_radius ? $layer->hotp_border_radius : '20px'; ?>;
                                                 border: <?php echo $layer->hotp_border_width; ?>px <?php echo $layer->hotp_border_style; ?> transparent;
                                                 background: rgba(0, 0, 0, 0.360784);
                                                 position: absolute;
                                                 padding: 0;
												<?php if (isset($layer->hotspot_animation) && $layer->hotspot_animation) { ?>
                                                 animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                 -moz-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                 -webkit-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                 -o-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
												<?php } ?>">
                                    </span>
                                    <span class="wds_layer_<?php echo $layer->id; ?> wds_hotspot_text"
                                          id="<?php echo $prefix; ?>"
                                          data-type="hotspot_text"
                                          data-width="<?php echo $layer->image_width; ?>"
                                          data-height="<?php echo $layer->image_height; ?>"
                                          data-hotp-orig-width="<?php echo $layer->image_width ? $layer->image_width  : ''; ?>"
                                          data-hotp-orig-height="<?php echo $layer->image_height ? $layer->image_height : ''; ?>"
                                          data-fsize="<?php echo $layer->size; ?>"
                                          data-fmin-size="<?php echo $layer->min_size; ?>"
                                          style="display: none;
                                          word-wrap: <?php echo ($layer->image_scale ? 'break-all':'normal'); ?>;
                                          <?php echo $layer->image_width ? 'width: ' . $layer->image_width . 'px; ' : 'white-space: nowrap;'; ?>
                                          <?php echo $layer->image_height ? 'height: ' . $layer->image_height . 'px; ' : ''; ?>
                                          position: absolute;
                                          z-index: <?php echo $layer->depth; ?>;
                                          color: #<?php echo $layer->color; ?>;
                                          font-family: <?php echo $layer->ffamily; ?>;
                                          font-weight: <?php echo $layer->fweight; ?>;
                                          background-color: <?php echo WDW_S_Library::spider_hex2rgba($layer->fbgcolor, (100 - $layer->transparent) / 100); ?>;
                                          border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                          border-radius: <?php echo $layer->border_radius; ?>;
                                          box-shadow: <?php echo $layer->shadow; ?>;
                                          text-align: <?php echo $layer->text_alignment; ?>">
                                      <?php echo str_replace(array("\r\n", "\r", "\n"), "<br>", $layer->text); ?>
                                      <span id="<?php echo $prefix; ?>_before" class="hotspot_text_before"></span>
                                     </span>  
                                  </span>
                                  <?php
                                  break;
                                }
                                default:
                                  break;
                              }
                            }
                          }
                        }
                        ?>
                        </span>
                      </span>
                    </span>
                  </span>
                  <?php
                }
                ?>
                <input type="hidden" id="wds_current_image_key_<?php echo $wds; ?>" value="<?php echo $current_key; ?>" />
                </div>
              </div>
            </div>
            <?php
              if ($enable_prev_next_butt && $slides_count > 1) {
                ?>
              <div class="wds_btn_cont wds_contTableCell">
                <div class="wds_btn_cont wds_contTable">
                  <span class="wds_btn_cont wds_contTableCell" style="position: relative; text-align: left;">
                    <span class="wds_left_btn_cont">
                      <span class="wds_left-ico_<?php echo $wds; ?>" onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), 0 <= (parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()) - wds_iterator_wds(<?php echo $wds; ?>)) ? (parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()) - wds_iterator_wds(<?php echo $wds; ?>)) % wds_params[<?php echo $wds; ?>].wds_data.length : wds_params[<?php echo $wds; ?>].wds_data.length - 1, wds_data_<?php echo $wds; ?>, false, 'left'); return false;">
                        <?php
                        if ($slider_row->rl_butt_img_or_not == 'style') {
                          ?>
                          <i class="fa <?php echo $slider_row->rl_butt_style; ?>-left"></i>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                   </span>
                </div>
              </div>
              <div class="wds_btn_cont wds_contTableCell">
                <div class="wds_btn_cont wds_contTable">
                  <span class="wds_btn_cont wds_contTableCell" style="position: relative; text-align: right;">
                    <span class="wds_right_btn_cont">
                      <span class="wds_right-ico_<?php echo $wds; ?>" onclick="wds_change_image('<?php echo $wds; ?>', parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()), (parseInt(jQuery('#wds_current_image_key_<?php echo $wds; ?>').val()) + wds_iterator_wds(<?php echo $wds; ?>)) % wds_params[<?php echo $wds; ?>].wds_data.length, wds_params[<?php echo $wds; ?>].wds_data, false, 'right'); return false;">
                        <?php
                        if ($slider_row->rl_butt_img_or_not == 'style') {
                          ?>
                          <i class="fa <?php echo $slider_row->rl_butt_style; ?>-right"></i>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                  </span>
                </div>
              </div>
              <?php
              }
              if ($enable_play_paus_butt && $slides_count > 1) {
                ?>
              <div class="wds_btn_cont wds_contTableCell">
                <div class="wds_btn_cont wds_contTable">
                  <span class="wds_btn_cont wds_contTableCell" style="position: relative; text-align: center;">
                    <span class="wds_pp_btn_cont" <?php echo $slide_rows[$current_key]->type == 'video' || strpos('EMBED', $slide_rows[$current_key]->type) !== false ? 'style="display:none"' : '';?>>
                      <span id="wds_slideshow_play_pause_<?php echo $wds; ?>" style="display: <?php echo $play_pause_button_display; ?>;" <?php echo ($slider_row->play_paus_butt_img_or_not != 'style') ? 'class="wds_ctrl_btn_' . $wds . ' wds_slideshow_play_pause_' . $wds . ' fa fa-play"' : ''; ?>>
                        <?php
                        if ($slider_row->play_paus_butt_img_or_not == 'style') {
                          ?>
                        <i class="wds_ctrl_btn_<?php echo $wds; ?> wds_slideshow_play_pause_<?php echo $wds; ?> fa fa-play"></i>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                  </span>
                </div>
              </div>
              <?php
              }
            ?>
          </div>
          <?php
          if ($enable_slideshow_music) {
            ?>
            <audio id="wds_audio_<?php echo $wds; ?>" src="<?php echo $slideshow_music_url; ?>" loop volume="1.0"></audio>
            <?php 
          }
          ?>
        </div>
      </div>
    </div>
	<?php
		$minify_plugin = WDS()->check_minify_plugins();
		// Add inline scripts.
		$script = WDW_S_Library::create_js( $slider_row, $slide_rows, $layers_rows, $wds, $current_key );
		if ( ! WDW_S_Library::elementor_is_active() ) {
			if ( function_exists('wp_add_inline_script') && ! $minify_plugin ) { // Since WordPress 4.5.0
        $included = wp_add_inline_script('wds_frontend', $script, 'before');
        if ( !$included ) {
          wp_add_inline_script('jquery', $script, 'before');
        }
			}
			else {
				echo '<script id="wd-slider-' . $wds .'">' . $script . '</script>';
			}
		}
		else {
			echo '<script id="wd-slider-' . $wds .'">' . $script . '
				jQuery(document).ready(function () {
					wds_slider_ready();
					' . ( (!WDS()->is_free) ? 'onYouTubeIframeAPIReady()' : '' ) . '
				});
			</script>';
		}
    if ( $from_shortcode ) {
      return;
    }
    else {
      die();
    }
  }
}
