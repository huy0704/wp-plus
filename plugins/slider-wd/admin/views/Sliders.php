<?php

/**
 * Class SlidersView_wds
 */
class SlidersView_wds extends AdminView_wds {

    /**
	 * Display.
	 *
     * @param array $params
     */
	public function display( $params = array() ) {
		ob_start();
		echo $this->body($params);
		$form_attr = array(
		  'id' => WDS()->prefix . '_sliders_form',
		  'name' => WDS()->prefix . '_sliders',
		  'class' => WDS()->prefix . '_sliders wd-form',
		  'action' => add_query_arg(array( 'page' => 'sliders_' . WDS()->prefix ), 'admin.php'),
		);
		echo $this->form(ob_get_clean(), $form_attr);
	}

    /**
	 * Body.
	 *
     * @param array $params
     */
	public function body( $params = array() ) {
		echo $this->title(array(
                        'title' => $params['page_title'],
                        'title_class' => 'wd-header',
                        'add_new_button' => array(
                        'href' => add_query_arg( array(
                                                    'page' => $params['page'],
                                                    'task' => 'edit',
												), admin_url('admin.php')),
                        ),
                      ));
		echo $this->search();
		?>
		<div class="tablenav top">
		  <?php
		  echo $this->bulk_actions($params['actions'], TRUE);
		  echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']);
		  ?>
		</div>
		<table class="adminlist table table-striped wp-list-table widefat fixed pages media">
			<thead>
				<td id="cb" class="column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Title', WDS()->prefix); ?></label>
					<input id="check_all" type="checkbox">
				</td>
				<?php echo WDW_S_Library::ordering('name', $params['orderby'], $params['order'], __('Title', WDS()->prefix), $params['page_url'], 'column-primary'); ?>
				<th class="col-slides-count"><?php _e('Slides count', WDS()->prefix); ?></th>
				<th class="col-shortcode"><?php _e('Shortcode', WDS()->prefix); ?></th>
				<th class="col-function"><?php _e('PHP function', WDS()->prefix); ?></th>
			</thead>
			<tbody>
			<?php 
				if ( $params['rows'] ) {
					foreach ( $params['rows'] as $row ) {
						$alternate = (!isset($alternate) || $alternate == '') ? 'class="alternate"' : '';
						$edit_url = add_query_arg(array(
										'page' => $params['page'],
										'task' => 'edit',
										'current_id' => $row->id,
									), admin_url('admin.php'));
						$publish_url = add_query_arg(array(
										'task' => ($row->published ? 'unpublish' : 'publish'),
										'current_id' => $row->id,
									), $params['page_url']);
						$delete_url = add_query_arg( array( 
										'task' => 'delete',
										'current_id' => $row->id
										), $params['page_url']
									);
						
						$preview_url = add_query_arg( array(
											'slider_id' => $row->id
											), $params['preview_url']
										);
						$images_count  = !empty($params['slides_info'][$row->id]['count']) ? $params['slides_info'][$row->id]['count'] : 0;
						$preview_image = !empty($params['slides_info'][$row->id]['preview_thumb']) ? $params['slides_info'][$row->id]['preview_thumb'] : WDS()->plugin_url . '/images/no-image.png';
					?>
						<tr id="tr_<?php echo $row->id; ?>" <?php echo $alternate; ?>>
							<th class="check-column">
							  <input type="checkbox" id="check_<?php echo $row->id; ?>" name="check[<?php echo $row->id; ?>]" onclick="spider_check_all(this)" />
							</th>
							<td class="column-primary column-title" data-colname="<?php _e('Title', WDS()->prefix); ?>">
							  <strong class="has-media-icon">
								<a href="<?php echo $edit_url; ?>">
								  <span class="media-icon image-icon">
									<img class="preview-image" title="<?php echo $row->name; ?>" src="<?php echo $preview_image; ?>" width="60" height="60" />
								  </span>
								  <?php echo $row->name; ?>
								</a>
								<?php if ( !$row->published ) { ?>
								  — <span class="post-state"><?php _e('Unpublished', WDS()->prefix); ?></span>
								<?php } ?>
							  </strong>
							  <div class="row-actions">
								<span><a href="<?php echo $edit_url; ?>"><?php _e('Edit', WDS()->prefix); ?></a> |</span>
								<span><a href="<?php echo $publish_url; ?>"><?php echo($row->published ? __('Unpublish', WDS()->prefix) : __('Publish', WDS()->prefix)); ?></a> |</span>
								<span class="trash"><a onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', WDS()->prefix)); ?>')) {return false;}" href="<?php echo $delete_url; ?>"><?php _e('Delete', WDS()->prefix); ?></a> |</span>
								<span><a href="<?php echo $preview_url; ?>" target="_blank"><?php _e('Preview', WDS()->prefix); ?></a></span>
							  </div>
							  <button class="toggle-row" type="button">
								<span class="screen-reader-text"><?php _e('Show more details', WDS()->prefix); ?></span>
							  </button>
							</td>
							<td data-colname="<?php _e('Slides count', WDS()->prefix); ?>"><?php echo $images_count; ?></td>
							<td data-colname="<?php _e('Shortcode', WDS()->prefix); ?>">
							  <input type="text" value='[wds id="<?php echo $row->id; ?>"]' onclick="spider_select_value(this)" size="11" readonly="readonly" />
							</td>
							<td data-colname="<?php _e('PHP function', WDS()->prefix); ?>">
							  <input type="text" value="&#60;?php if( function_exists('wd_slider') ) { wd_slider(<?php echo $row->id; ?>); } ?&#62;" onclick="spider_select_value(this)" size="17" readonly="readonly" />
							</td>
						</tr>
					<?php
					}
				}
				else {
					echo WDW_S_Library::no_items('sliders', 5);
				}
			?>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<?php echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']); ?>
		</div>
        <?php if ( !empty($params['rows']) ) { ?>
			<div class="wds_opacity_merge" onclick="jQuery('.wds_opacity_merge').hide();jQuery('.wds_merge').hide();"></div>
			<div class="wds_merge">
				<select class="select_icon select_icon_320" style="width:200px" name="select_slider_merge" id="select_slider_merge" style="margin-bottom: 6px;">
					<option><?php _e('-select-', WDS()->prefix); ?></option>
					<?php foreach ( $params['rows'] as $row ) { ?>
					<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
					<?php } ?>
				</select>
				<input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'merge');"  value="<?php _e('Merge', WDS()->prefix); ?>" />
				<input type="button" class="button-secondary" onclick="jQuery('.wds_merge').hide();jQuery('.wds_opacity_merge').hide(); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
				<p class="description"><?php _e('Select slider to use settings from.', WDS()->prefix); ?></p>
			</div>
		<?php } ?>
		<div class="wds_opacity_export" onclick="jQuery('.wds_opacity_export').hide();jQuery('.wds_exports').hide();"></div>
		<div class="wds_exports">
			<input type="checkbox" name="imagesexport" id="imagesexport" checked="checked" />
			<label for="imagesexport"><?php _e('Check the box to export the images included within sliders', WDS()->prefix); ?></label>
			<a class="button-secondary wds_export" type="button" href="<?php echo add_query_arg(array('action' => 'WDSExport'), admin_url('admin-ajax.php')); ?>" onclick="wds_get_checked()"><?php _e('Export', WDS()->prefix); ?></a>
			<input type="button" class="button-secondary" onclick="jQuery('.wds_exports').hide();jQuery('.wds_opacity_export').hide(); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
		</div>
	<?php
		global $wp_version;
		if (version_compare($wp_version, '4','<')) {
		?>
		<style>
			#wpwrap {
				background-color: #F1F1F1;
			}
			@media  screen and (max-width: 640px) {
				.buttons_div input {
				  width: 31%;
				  font-size: 10px;
				}
				.tablenav{
				  height:auto
				}
				#wpcontent {
				  margin-left: 40px !important
				}
				.alignleft  {
				  display:none;
				}
			}
		</style>
		<?php
		}
	}

    /**
	 * Edit.
	 *
     * @param array $params
     */
  public function edit( $params = array() ) {
    $id = $params['id'];
    $row = $params['row'];
    $slides_row = $params['slides_row'];
    $layers_row = $params['layers_row'];
    $global_options = $params['global_options'];
    $options_values = $params['options_values'];
    $slider_preview_link = $params['slider_preview_link'];
    $spider_uploader = isset($global_options->spider_uploader) ? $global_options->spider_uploader : 0;
    $page_title = $params['page_title'];
    $save_btn_name = $params['save_btn_name'];
    $sub_tab_type = $params['sub_tab_type'];
    // Get options values.
    $aligns = $options_values['aligns'];
    $border_styles = $options_values['border_styles'];
    $button_styles = $options_values['button_styles'];
    $bull_styles = $options_values['bull_styles'];
    $font_families = $options_values['font_families'];
    $google_fonts = $options_values['google_fonts'];
    $font_weights = $options_values['font_weights'];
    $social_buttons = $options_values['social_buttons'];
    $effects = $options_values['effects'];
    $layer_effects_in = $options_values['layer_effects_in'];
    $layer_effects_out = $options_values['layer_effects_out'];
    $hotp_text_positions = $options_values['hotp_text_positions'];
    $slider_callbacks = $options_values['slider_callbacks'];
    $layer_callbacks = $options_values['layer_callbacks'];
    $text_alignments = $options_values['text_alignments'];
    $built_in_watermark_fonts = $options_values['built_in_watermark_fonts'];
    $slider_fillmode_option = $options_values['slider_fillmode_option'];
    $free_effects = array('none', 'fade', 'sliceH', 'fan', 'scaleIn');
    $fv = (WDS()->is_free && get_option("wds_theme_version") ? TRUE : FALSE);

	  $query_url = add_query_arg(array('action' => 'addImage', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif'), admin_url('admin-ajax.php'));
    $query_url = wp_nonce_url($query_url, 'addImage', WDS()->nonce);
    $slide_ids_string = '';    
    ?>
    <div class="spider_message_cont"></div>
    <div class="spider_load">
      <div class="spider_load_cont"></div>
      <div class="spider_load_icon"><img class="spider_ajax_loading" src="<?php echo WDS()->plugin_url . '/images/ajax_loader_back.gif'; ?>"></div>
    </div>
    <form class="wrap wds_form wds-check-change_form js" method="post" id="sliders_form" action="admin.php?page=sliders_wds">
      <h1 class="hidden"></h1>
      <?php wp_nonce_field(WDS()->nonce, WDS()->nonce); ?>
      <span class="slider-icon"></span>
      <h2 class="wds_default"><?php echo $page_title; ?></h2>
      <div class="buttons_conteiner">
        <h1 class="wp-heading-inline"><?php _e('Slider Title', WDS()->prefix); ?></h1>
        <input type="text" id="name" name="name" value="<?php echo $row->name; ?>" size="20" class="wds_requried" data-name="<?php _e('Slider title', WDS()->prefix); ?>" />
        <div class="wds_buttons">
          <button class="button button-primary button-large" onclick="spider_set_input_value('task', 'apply'); if(!wds_spider_ajax_save('sliders_form', event)) return false;">
            <?php echo $save_btn_name; ?>
          </button>
          <button class="button button-large" <?php echo ($id == 0) ? 'disabled="disabled"' : 'onclick="window.open(\''. add_query_arg( array('slider_id' => $id), $slider_preview_link ) .'\', \'_blank\'); return false;"'; ?>><?php _e('Preview', WDS()->prefix); ?></button>
          <button class="button button-secondary button-large wd-hidden reset-all-settings" onclick="wds_reset(event); return false;"><?php _e('Reset all settings', WDS()->prefix); ?></button>
        </div>
      </div>
      <div>
        <div class="tab_conteiner">
          <div class="tab_button_wrap slides_tab_button_wrap" onclick="wds_change_tab(this, 'wds_slides_box')" >
            <a class="wds_button-secondary wds_slides" href="#">
              <span tab_type="slides" class="wds_tab_label"><span class="dashicons dashicons-format-gallery"></span><?php _e('Slides', WDS()->prefix); ?></span>
            </a>
          </div>
          <div class="tab_button_wrap settings_tab_button_wrap" onclick="wds_change_tab(this, 'wds_settings_box')">
            <a class="wds_button-secondary wds_settings" href="#">
              <span tab_type="settings" class="wds_tab_label"><span class="dashicons dashicons-admin-generic"></span><?php _e('Settings', WDS()->prefix); ?></span>
            </a>
          </div>
          <div class="tab_button_wrap howto_tab_button_wrap <?php echo (!$row->id) ? 'hide' : ''; ?>" onclick="wds_change_tab(this, 'wds_howto_box')">
              <a class="wds_button-secondary wds_howto" href="#">
                <span tab_type="howto" class="wds_tab_label"><span class="dashicons dashicons-editor-help"></span><?php _e('How to use', WDS()->prefix); ?></span>
              </a>
          </div>
        </div>
        <!--------------Settings tab----------->
        <div class="wds_box wds_settings_box">
          <div class="clear"></div>
        <div class="wds_nav_tabs">
          <div class="wds_menu_icon" onclick="jQuery('.wds_nav_tabs ul').slideToggle(500);"></div>
            <ul>
              <li tab_type="global" onclick="wds_change_nav(this, 'wds_nav_global_box')">
                <a href="#"><?php _e('Global', WDS()->prefix); ?></a>
              </li>
              <li tab_type="carousel" onclick="wds_change_nav(this, 'wds_nav_carousel_box')">
                <a href="#"><?php _e('Carousel', WDS()->prefix); ?></a>
              </li>
              <li tab_type="navigation" onclick="wds_change_nav(this, 'wds_nav_navigation_box')" >
                <a href="#"><?php _e('Navigation', WDS()->prefix); ?></a>
              </li>
              <li tab_type="bullets" onclick="wds_change_nav(this, 'wds_nav_bullets_box')" >
                <a href="#"><?php _e('Bullets', WDS()->prefix); ?></a>
              </li>
              <li tab_type="filmstrip" onclick="wds_change_nav(this, 'wds_nav_filmstrip_box')" >
                <a href="#"><?php _e('Filmstrip', WDS()->prefix); ?></a>
              </li>
              <li tab_type="timer_bar" onclick="wds_change_nav(this, 'wds_nav_timer_bar_box')" >
                <a href="#"><?php _e('Timer bar', WDS()->prefix); ?></a>
              </li>
              <li tab_type="watermark" onclick="wds_change_nav(this, 'wds_nav_watermark_box')" >
                <a href="#"><?php _e('Watermark', WDS()->prefix); ?></a>
              </li>
              <li tab_type="css" onclick="wds_change_nav(this, 'wds_nav_css_box')" >
                <a href="#"><?php _e('CSS', WDS()->prefix); ?></a>
              </li>
              <li tab_type="callbacks" onclick="wds_change_nav(this, 'wds_nav_callbacks_box')" >
                <a href="#"><?php _e('Slider Callbacks', WDS()->prefix); ?></a>
              </li>
            </ul>
          </div>
          <div>
            <div class="wds_nav_box wds_nav_global_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <input type="radio" onclick="hide_dimmension_ratio()" id="full_width2" name="full_width" <?php echo (($row->full_width == '2' || $row->full_width == '') ? 'checked="checked"' : ''); ?> value="2" />
                        <label  for="full_width2"><?php _e('Boxed layout', WDS()->prefix); ?></label>
                        <input type="radio" onclick="hide_dimmension_ratio()" id="full_width1" name="full_width" <?php echo (($row->full_width == '1') ? 'checked="checked"' : ''); ?> value="1" />
                        <label  for="full_width1"><?php _e('Full width', WDS()->prefix); ?></label>
                        <input type="radio" onclick="hide_dimmension_ratio()" id="full_width0" name="full_width" <?php echo (($row->full_width == '0') ? 'checked="checked"' : ''); ?> value="0" />
                        <label  for="full_width0"><?php _e('Custom', WDS()->prefix); ?></label>
                        <p class="description full_width_desc" id="full_width2_desc"><?php _e('With Boxed layout, the slideshow will take the 100% width of its parent container.', WDS()->prefix); ?></p>
                        <p class="description full_width_desc" id="full_width1_desc"><?php _e('The slider will take the full width of the page. Height will be applied based on the ratio of dimensions or auto height option.', WDS()->prefix); ?></p>
                        <p class="description full_width_desc" id="full_width0_desc"><?php _e('This option lets you specify custom dimensions for your slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="auto_height">
                        <label class="wd-label"><?php _e('Auto height', WDS()->prefix); ?></label>
                        <input type="radio" onclick="hide_dimmension_ratio()" id="auto_height1" name="auto_height" <?php echo (($row->auto_height) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="auto_height1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" onclick="hide_dimmension_ratio()" id="auto_height0" name="auto_height" <?php echo (($row->auto_height) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="auto_height0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('The slider will take the full height of the screen.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="dimensions">
                        <label class="wd-label"><?php _e('Dimensions', WDS()->prefix); ?></label>
                        <input type="text" name="width" id="width" value="<?php echo $row->width; ?>" class="spider_int_input" onchange="wds_whr('width')" onkeypress="return spider_check_isnum(event)" /> x
                        <input type="text" name="height" id="height" value="<?php echo $row->height; ?>" class="spider_int_input" onchange="wds_whr('height')" onkeypress="return spider_check_isnum(event)" /> px
                        <input type="text" name="ratio" id="ratio" value="" class="spider_int_input" onchange="wds_whr('ratio')" onkeypress="return spider_check_isnum(event)" title = "<?php _e('The slider height will be applied based on the ratio of dimensions.', WDS()->prefix); ?>"/><?php _e(' ratio', WDS()->prefix); ?>
                        <p class="description"><?php _e('Maximum width and height for slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="effect"><?php _e('Effect', WDS()->prefix); ?></label>
                        <select class="select_icon select_icon_320" name="effect" id="effect">
                          <?php
                          foreach ($effects as $key => $effect) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (WDS()->is_free && !in_array($key, $free_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> <?php if ($row->effect == $key) echo 'selected="selected"'; ?>><?php echo $effect; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <p class="description"><?php _e('Select the effect which will be applied when navigating through slides.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="effect_duration"><?php _e('Еffect duration', WDS()->prefix); ?></label>
                        <input type="text" id="effect_duration" name="effect_duration" value="<?php echo $row->effect_duration; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> ms
                        <p class="description"><?php _e('Set the duration for the effect.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
                        <input type="text" id="hide_on_mobile" name="hide_on_mobile" value="<?php echo $row->hide_on_mobile; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('Hide slider when screen size is smaller than this value.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="full_width_for_mobile"><?php _e('Full width on small screens', WDS()->prefix); ?></label>
                        <input type="text" id="full_width_for_mobile" name="full_width_for_mobile" value="<?php echo $row->full_width_for_mobile; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('The slider will have full width when screen size is smaller than this value.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_smart_crop">
                        <label class="wd-label"><?php _e('Smart Crop', WDS()->prefix); ?></label>
                        <input onClick="wds_enable_disable('', 'tr_crop_pos', 'smart_crop1')" type="radio" id="smart_crop1" name="smart_crop" <?php echo (($row->smart_crop) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="smart_crop1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input onClick="wds_enable_disable('none', 'tr_crop_pos', 'smart_crop0')" type="radio" id="smart_crop0" name="smart_crop" <?php echo (($row->smart_crop) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="smart_crop0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('To use Smart Crop, please edit your slides and make sure Fillmode is set to Fill in Slide Options.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_crop_pos">
                        <label class="wd-label" for="smart_crop"><?php _e('Crop Image Position', WDS()->prefix); ?></label>
                        <table class="wds_position_table">
                          <tbody>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="left top" name="crop_image_position" <?php if ($row->crop_image_position == "left top") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="center top" name="crop_image_position" <?php if ($row->crop_image_position == "center top") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="right top" name="crop_image_position" <?php if ($row->crop_image_position == "right top") echo 'checked="checked"'; ?> ></td>
                            </tr>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="left center" name="crop_image_position" <?php if ($row->crop_image_position == "left center") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="center center" name="crop_image_position" <?php if ($row->crop_image_position == "center center") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="right center" name="crop_image_position" <?php if ($row->crop_image_position == "right center") echo 'checked="checked"'; ?> ></td>
                            </tr>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="left bottom" name="crop_image_position" <?php if ($row->crop_image_position == "left bottom") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="center bottom" name="crop_image_position" <?php if ($row->crop_image_position == "center bottom") echo 'checked="checked"'; ?> ></td>
                              <td class="wds_position_td"><input type="radio" value="right bottom" name="crop_image_position" <?php if ($row->crop_image_position == "right bottom") echo 'checked="checked"'; ?> ></td>
                            </tr>
                          </tbody>
                        </table>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Fixed background:', WDS()->prefix); ?></label>
                        <input type="radio" id="fixed_bg1" name="fixed_bg" <?php echo (($row->fixed_bg) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="fixed_bg1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="fixed_bg0" name="fixed_bg" <?php echo (($row->fixed_bg) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="fixed_bg0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Slides order direction:', WDS()->prefix); ?></label>
                        <input type="radio" id="order_dir1" name="order_dir" <?php echo checked('asc', $row->order_dir); ?> value="asc" />
                        <label for="order_dir1"><?php _e('Ascending', WDS()->prefix); ?></label>
                        <input type="radio" id="order_dir0" name="order_dir" <?php checked('desc', $row->order_dir); ?> value="desc" />
                        <label for="order_dir0"><?php _e('Descending', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
                        <label class="wd-label"><?php _e('Parallax Effect', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="parallax_effect1" name="parallax_effect" <?php echo (($row->parallax_effect) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="parallax_effect1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="parallax_effect0" name="parallax_effect" <?php echo (($row->parallax_effect) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="parallax_effect0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('The direction of the movement, as well as the layer moving pace depend on the z-index value.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Smart Load', WDS()->prefix); ?></label>
                        <input type="radio" id="preload_images1" name="preload_images" <?php echo (($row->preload_images) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="preload_images1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="preload_images0" name="preload_images" <?php echo (($row->preload_images) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="preload_images0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Turn this option on to have faster loading for the first few images and process the rest meanwhile.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Right click protection', WDS()->prefix); ?></label>
                        <input type="radio" name="image_right_click" id="image_right_click_1" value="1" <?php if ($row->image_right_click) echo 'checked="checked"'; ?> />
                        <label for="image_right_click_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="image_right_click" id="image_right_click_0" value="0" <?php if (!$row->image_right_click) echo 'checked="checked"'; ?> />
                        <label for="image_right_click_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Disable right-click on slider images.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
                        <label class="wd-label"><?php _e('Layer out on next', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" name="layer_out_next" id="layer_out_next_1" value="1" <?php if ($row->layer_out_next) echo 'checked="checked"'; ?> />
                        <label for="layer_out_next_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" name="layer_out_next" id="layer_out_next_0" value="0" <?php if (!$row->layer_out_next) echo 'checked="checked"'; ?> />
                        <label for="layer_out_next_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable this option to have the layer effect out regardless of the timing between the hit to the next slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                        <input type="radio" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="published1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="published0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Autoplay', WDS()->prefix); ?></label>
                        <input type="radio" id="autoplay1" name="autoplay" <?php echo (($row->autoplay) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="autoplay1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="autoplay0" name="autoplay" <?php echo (($row->autoplay) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="autoplay0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable this option to autoplay the slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="time_intervval"><?php _e('Time Interval', WDS()->prefix); ?></label>
                        <input type="text" id="time_intervval" name="time_intervval" value="<?php echo $row->time_intervval; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> sec.
                        <p class="description"><?php _e('Set the time interval between the slides when autoplay is on.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Enable loop', WDS()->prefix); ?></label>
                        <input type="radio" id="slider_loop1" name="slider_loop" <?php echo (($row->slider_loop) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="slider_loop1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="slider_loop0" name="slider_loop" <?php echo (($row->slider_loop) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="slider_loop0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Shuffle', WDS()->prefix); ?></label>
                        <input type="radio" id="shuffle1" name="shuffle" <?php echo (($row->shuffle) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="shuffle1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="shuffle0" name="shuffle" <?php echo (($row->shuffle) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="shuffle0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable this setting to have the slides change in random order during autoplay.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Two way slideshow', WDS()->prefix); ?></label>
                        <input type="radio" id="twoway_slideshow1" name="twoway_slideshow" <?php echo (($row->twoway_slideshow) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="twoway_slideshow1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="twoway_slideshow0" name="twoway_slideshow" <?php echo (($row->twoway_slideshow) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="twoway_slideshow0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('If the user switches to previous slide, the slideshow starts to go backwards during autoplay.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Stop on hover', WDS()->prefix); ?></label>
                        <input type="radio" id="stop_animation1" name="stop_animation" <?php echo (($row->stop_animation) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="stop_animation1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="stop_animation0" name="stop_animation" <?php echo (($row->stop_animation) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="stop_animation0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('The option works when autoplay is on.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="start_slide_num"><?php _e('Start with slide', WDS()->prefix); ?></label>
                        <input type="text" name="start_slide_num" id="start_slide_num" value="<?php echo $row->start_slide_num; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" />
                        <p class="description"><?php _e('The slider will start from the specified slide. Set the value to 0 for random.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Music', WDS()->prefix); ?></label>
                        <input type="radio" id="music1" name="music" <?php echo (($row->music) ? 'checked="checked"' : ''); ?> value="1" onClick="wds_enable_disable('', 'tr_music_url', 'music1')" />
                        <label for="music1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="music0" name="music" <?php echo (($row->music) ? '' : 'checked="checked"'); ?> value="0" onClick="wds_enable_disable('none', 'tr_music_url', 'music0')" />
                        <label for="music0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('You can have music/audio track playback with the slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_music_url">
                        <label class="wd-label" for="music_url"><?php _e('Music url', WDS()->prefix); ?></label>
                        <input type="text" id="music_url" name="music_url" size="39" value="<?php echo $row->music_url; ?>" style="display:inline-block;" />
                        <input id="add_music_url" class="button button-secondary" type="button" onclick="wds_media_uploader('music', event, false); return false;" value="<?php _e('Add music', WDS()->prefix); ?>" />
                        <p class="description"><?php _e('Only .aac,.m4a,.f4a,.mp3,.ogg,.oga formats are supported.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="align"><?php _e('Slider alignment', WDS()->prefix); ?></label>
                        <select class="select_icon select_icon_320" name="align" id="align">
                          <?php
                          foreach ($aligns as $key => $align) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (($row->align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <p class="description"><?php _e('Set the alignment of the slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="background_color"><?php _e('Background color', WDS()->prefix); ?></label>
                        <input type="text" name="background_color" id="background_color" value="<?php echo $row->background_color; ?>" class="color" onchange="jQuery('div[id^=\'wds_preview_image\']').css({backgroundColor: wds_hex_rgba(jQuery(this).val(), 100 - jQuery('#background_transparent').val())})" />
                        <input id="background_transparent" name="background_transparent" class="spider_int_input" type="text" onchange="jQuery('div[id^=\'wds_preview_image\']').css({backgroundColor: wds_hex_rgba(jQuery('#background_color').val(), 100 - jQuery(this).val())})" onkeypress="return spider_check_isnum(event)" value="<?php echo $row->background_transparent; ?>" /> %
                        <p class="description"><?php _e('Transparency Value must be between 0 and 100.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="glb_border_width"><?php _e('Border', WDS()->prefix); ?></label>
                        <input type="text" name="glb_border_width" id="glb_border_width" value="<?php echo $row->glb_border_width; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <select class="select_icon select_icon_320" name="glb_border_style" id="glb_border_style">
                          <?php
                          foreach ($border_styles as $key => $border_style) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (($row->glb_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <input type="text" name="glb_border_color" id="glb_border_color" value="<?php echo $row->glb_border_color; ?>" class="color" />
                        <p class="description"><?php _e('Set the border width, type and the color.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="glb_border_radius"><?php _e('Border radius', WDS()->prefix); ?></label>
                        <input type="text" name="glb_border_radius" id="glb_border_radius" value="<?php echo $row->glb_border_radius; ?>" class="spider_char_input" />
                        <p class="description"><?php _e('Use CSS type values (e.g. 4px).', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="glb_margin"><?php _e('Margin', WDS()->prefix); ?></label>
                        <input type="text" name="glb_margin" id="glb_margin" value="<?php echo $row->glb_margin; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('Set a margin for the slider.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="glb_box_shadow"><?php _e('Shadow', WDS()->prefix); ?></label>
                        <input type="text" name="glb_box_shadow" id="glb_box_shadow" value="<?php echo $row->glb_box_shadow; ?>" class="spider_box_input" />
                        <p class="description"><?php _e('Use CSS type values (e.g. 10px 10px 5px #888888).', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_carousel_box<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <?php
                      if ( WDS()->is_free ) {
                        echo WDW_S_Library::message_id(0, __('Carousel is disabled in free version.', WDS()->prefix), 'error');
                      }
                      ?>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Carousel:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="carousel1" name="carousel" <?php echo (($row->carousel) ? 'checked="checked"' : ''); ?> value="1" onClick="showhide_for_carousel_fildes(1)"/>
                        <label for="carousel1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="carousel0" name="carousel" <?php echo (($row->carousel) ? '' : 'checked="checked"'); ?> value="0" onClick="showhide_for_carousel_fildes(0)"/>
                        <label for="carousel0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Use this option to activate Carousel feature. Note, that the effects you have selected in Global settings for your slider will not apply.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_image_counts"><?php _e('Number of images for carousel:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" id="carousel_image_counts" name="carousel_image_counts" value="<?php echo $row->carousel_image_counts; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" />
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_image_parameters"><?php _e('Carousel image ratio:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" id="carousel_image_parameters" name="carousel_image_parameters" value="<?php echo $row->carousel_image_parameters; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" />
                        <p class="description"><?php _e('The value must be between 0 and 1.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Container fit:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="carousel_fit_containerWidth1" name="carousel_fit_containerWidth" <?php echo (($row->carousel_fit_containerWidth) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="carousel_fit_containerWidth1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="carousel_fit_containerWidth0" name="carousel_fit_containerWidth" <?php echo (($row->carousel_fit_containerWidth) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="carousel_fit_containerWidth0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_width"><?php _e('Fixed width:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" id="carousel_width" name="carousel_width" value="<?php echo $row->carousel_width; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_degree"><?php _e('Background image angle:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" id="carousel_degree" name="carousel_degree" value="<?php echo $row->carousel_degree; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> deg
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_grayscale"><?php _e('Background image grayscale:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="carousel_grayscale" id="carousel_grayscale" value="<?php echo $row->carousel_grayscale; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/>%
                        <p class="description"><?php _e('You can change the color scheme for background images to grayscale. Values must be between 0 to 100', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="carousel_transparency"><?php _e('Background image transparency:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="carousel_transparency" id="carousel_transparency" value="<?php echo $row->carousel_transparency; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/>%
                        <p class="description"><?php _e('You can set transparency level for background images. Values should be between 0 to 100', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_navigation_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Next / Previous buttons:', WDS()->prefix); ?></label>
                        <input type="radio" name="prev_next_butt" id="prev_next_butt_1" value="1" <?php if ($row->prev_next_butt) echo 'checked="checked"'; ?> />
                        <label for="prev_next_butt_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="prev_next_butt" id="prev_next_butt_0" value="0" <?php if (!$row->prev_next_butt) echo 'checked="checked"'; ?> />
                        <label for="prev_next_butt_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable this option to display Previous and Next buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Mouse swipe navigation:', WDS()->prefix); ?></label>
                        <input type="radio" name="mouse_swipe_nav" id="mouse_swipe_nav_1" value="1" <?php if ($row->mouse_swipe_nav) echo 'checked="checked"'; ?> />
                        <label for="mouse_swipe_nav_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="mouse_swipe_nav" id="mouse_swipe_nav_0" value="0" <?php if (!$row->mouse_swipe_nav) echo 'checked="checked"'; ?> />
                        <label for="mouse_swipe_nav_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Touch swipe navigation:', WDS()->prefix); ?></label>
                        <input type="radio" name="touch_swipe_nav" id="touch_swipe_nav_1" value="1" <?php if ($row->touch_swipe_nav) echo 'checked="checked"'; ?> />
                        <label for="touch_swipe_nav_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="touch_swipe_nav" id="touch_swipe_nav_0" value="0" <?php if (!$row->touch_swipe_nav) echo 'checked="checked"'; ?> />
                        <label for="touch_swipe_nav_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Mouse wheel navigation:', WDS()->prefix); ?></label>
                        <input type="radio" name="mouse_wheel_nav" id="mouse_wheel_nav_1" value="1" <?php if ($row->mouse_wheel_nav) echo 'checked="checked"'; ?> />
                        <label for="mouse_wheel_nav_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="mouse_wheel_nav" id="mouse_wheel_nav_0" value="0" <?php if (!$row->mouse_wheel_nav) echo 'checked="checked"'; ?> />
                        <label for="mouse_wheel_nav_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Keyboard navigation:', WDS()->prefix); ?></label>
                        <input type="radio" name="keyboard_nav" id="keyboard_nav_1" value="1" <?php if ($row->keyboard_nav) echo 'checked="checked"'; ?> />
                        <label for="keyboard_nav_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="keyboard_nav" id="keyboard_nav_0" value="0" <?php if (!$row->keyboard_nav) echo 'checked="checked"'; ?> />
                        <label for="keyboard_nav_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Show Navigation buttons:', WDS()->prefix); ?></label>
                        <input type="radio" name="navigation" id="navigation_1" value="hover" <?php if ($row->navigation == 'hover') echo 'checked="checked"'; ?> />
                        <label for="navigation_1"><?php _e('On hover', WDS()->prefix); ?></label>
                        <input type="radio" name="navigation" id="navigation_0" value="always" <?php if ($row->navigation == 'always' ) echo 'checked="checked"'; ?> />
                        <label for="navigation_0"><?php _e('Always', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Image for Next / Previous buttons:', WDS()->prefix); ?></label>
                        <input type="radio" name="rl_butt_img_or_not" id="rl_butt_img_or_not_our" value="our" <?php if ($row->rl_butt_img_or_not == 'our') echo 'checked="checked"'; ?> onClick="image_for_next_prev_butt('our')" />
                        <label for="rl_butt_img_or_not_our"><?php _e('Default', WDS()->prefix); ?></label>
                        <input type="radio" name="rl_butt_img_or_not" id="rl_butt_img_or_not_cust" value="custom" <?php if ($row->rl_butt_img_or_not == 'custom') echo 'checked="checked"'; ?> onClick="image_for_next_prev_butt('custom')" />
                        <label for="rl_butt_img_or_not_cust"><?php _e('Custom', WDS()->prefix); ?></label>
                        <input type="radio" name="rl_butt_img_or_not" id="rl_butt_img_or_not_style" value="style" <?php if ($row->rl_butt_img_or_not == 'style') echo 'checked="checked"'; ?> onClick="image_for_next_prev_butt('style')" />
                        <label for="rl_butt_img_or_not_style"><?php _e('Styled', WDS()->prefix); ?></label>
                        <input type="hidden" id="right_butt_url" name="right_butt_url" value="<?php echo $row->right_butt_url; ?>" />
                        <input type="hidden" id="right_butt_hov_url" name="right_butt_hov_url" value="<?php echo $row->right_butt_hov_url; ?>" />
                        <input type="hidden" id="left_butt_url" name="left_butt_url" value="<?php echo $row->left_butt_url; ?>" />
                        <input type="hidden" id="left_butt_hov_url" name="left_butt_hov_url" value="<?php echo $row->left_butt_hov_url; ?>" />
                        <p class="description"><?php _e('You can select to use default navigation buttons or to upload custom icons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="right_left_butt_style">
                        <label class="wd-label" for="rl_butt_style"><?php _e('Next / Previous buttons style:', WDS()->prefix); ?></label>
                        <div style="display: table;">
                          <div style="display: table-cell; vertical-align: middle;">
                            <select class="select_icon select_icon_320" name="rl_butt_style" id="rl_butt_style" onchange="change_rl_butt_style(jQuery(this).val())">
                            <?php
                            foreach ($button_styles as $key => $button_style) {
                              ?>
                              <option value="<?php echo $key; ?>" <?php echo (($row->rl_butt_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $button_style; ?></option>
                              <?php
                            }
                            ?>
                            </select>
                          </div>
                          <div style="display: table-cell; vertical-align: middle; background-color: rgba(229, 229, 229, 0.62); text-align: center;">
                            <i id="wds_left_style" class="fa <?php echo $row->rl_butt_style; ?>-left" style="color: #<?php echo $row->butts_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                            <i id="wds_right_style" class="fa <?php echo $row->rl_butt_style; ?>-right" style="color: #<?php echo $row->butts_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                          </div>
                        </div>
                        <p class="description"><?php _e('Choose the style of the button you prefer to have as navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="right_butt_upl">
                        <label class="wd-label"><?php _e('Upload buttons images:', WDS()->prefix); ?></label>
                        <div style="display: table;">
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('nav_left_but', event, false); return false;" value="<?php _e('Previous Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'nav_left_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Previous Button', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Previous Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('nav_left_hov_but', event, false); return false;" value="<?php _e('Previous Button Hover', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'nav_left_hov_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Previous Button Hover', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Previous Button Hover', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('nav_right_but', event, false); return false;" value="<?php _e('Next Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'nav_right_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Next Button', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Next Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('nav_right_hov_but', event, false); return false;" value="<?php _e('Next Button Hover', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'nav_right_hov_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Next Button Hover', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Next Button Hover', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="width:100px; display: table-cell; vertical-align: middle; text-align: center;background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;" class="display_block">
                            <img id="left_butt_img" src="<?php echo $row->left_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="right_butt_img" src="<?php echo $row->right_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="left_butt_hov_img" src="<?php echo $row->left_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="right_butt_hov_img" src="<?php echo $row->right_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;" class="display_block wds_reverse_cont">
                            <input type="button" class="button button-small wds_reverse" onclick="wds_change_custom_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                        <script>
                          var wds_rl_butt_type = [];
                          var rl_butt_dir = '<?php echo WDS()->plugin_url . '/images/arrow/'; ?>';
                          var type_cur_fold = '1';
                          <?php
                          $folder_names = scandir(WDS()->plugin_dir . '/images/arrow');
                          $cur_fold_name = '';
                          $cur_type_key = '';
                          $cur_color_key = '';
                          $cur_sub_fold_names = array();
                          array_splice($folder_names, 0, 2);
                          $flag = FALSE;
                          foreach ($folder_names as $type_key => $folder_name) {
                            if (is_dir(WDS()->plugin_dir . '/images/arrow/' . $folder_name)) {
                              ?>
                              wds_rl_butt_type["<?php echo $type_key; ?>"] = [];
                              wds_rl_butt_type["<?php echo $type_key; ?>"]["type_name"] = "<?php echo $folder_name; ?>";
                              <?php
                              if ($row->left_butt_url != '') {
                                /* Getting current button's type folder and color folder.*/
                                $check_cur_fold = explode('/' , $row->left_butt_url);
                                if (in_array($folder_name, $check_cur_fold)) {
                                  $flag = TRUE;
                                  $cur_fold_name = $folder_name;
                                  $cur_type_key = $type_key;
                                  $cur_sub_fold_names = scandir(WDS()->plugin_dir . '/images/arrow/' . $cur_fold_name);
                                  array_splice($cur_sub_fold_names, 0, 2);
                                  ?>
									                type_cur_fold = '<?php echo $cur_type_key;?>';
                                  <?php
                                }
                              }
                              $sub_folder_names = scandir( WDS()->plugin_dir . '/images/arrow/' . $folder_name);
                              array_splice($sub_folder_names, 0, 2);
                              foreach ($sub_folder_names as $color_key => $sub_folder_name) {
                                if (is_dir(WDS()->plugin_dir . '/images/arrow/' . $folder_name . '/' . $sub_folder_name)) {
                                  if ($cur_fold_name == $folder_name) {
                                    /* Getting current button's color key.*/
                                    if (in_array($sub_folder_name, $check_cur_fold)) {
                                      $cur_color_key = $color_key;
                                    }
                                  }
                                  ?>
                                  wds_rl_butt_type["<?php echo $type_key; ?>"]["<?php echo $color_key; ?>"] = "<?php echo $sub_folder_name; ?>";
                                  <?php
                                }
                              }
                            }
                            else {
                              ?>
                              console.log('<?php echo $folder_name . " is not a directory."; ?>');
                              <?php
                            }
                          }
                          ?>
                        </script>
                      </span>
                      <span class="wd-group" id="right_left_butt_select">
                        <label class="wd-label" for="right_butt_url"><?php _e('Choose buttons:', WDS()->prefix); ?></label>
                        <div style="display: table; margin-bottom: 14px;">
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <div style="display: block; width: 122px;" class="default_buttons">
                              <div class="spider_choose_option" onclick="wds_choose_option(this)">
                                <div  class="spider_option_main_title"><?php _e('Choose group', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color: #1E8CBE"></i></div>
                              </div>
                              <div class="spider_options_cont">
                              <?php
                              foreach ($folder_names as $type_key => $folder_name) {
                                ?>
                                <div class="spider_option_cont wds_rl_butt_groups" value="<?php echo $type_key; ?>" <?php echo (($cur_type_key == $type_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_rl_butt_type(this)">
                                  <div  class="spider_option_cont_title">
                                    <?php _e('Group', WDS()->prefix); echo '-' . ++$type_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img">
                                    <img class="src_top_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="src_top_right" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="src_bottom_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="src_bottom_right" style="display: inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                <?php
                              }
                              if (!$flag) {
                                /* Folder doesn't exist.*/
                                ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                <?php
                              }
                              ?>
                              </div>
                            </div>
                          </div>
                          <div style="display:table-cell;vertical-align: middle;" class="display_block">
                            <div style="display: block; width: 122px; margin-left: 12px;" class="default_buttons">
                              <div class="spider_choose_option" onclick="<?php echo (WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\')' : 'wds_choose_option_color(this)'); ?>">
                                <div  class="spider_option_main_title"><?php _e('Choose color', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color:#1E8CBE"></i></div>
                              </div>
                              <div class="spider_options_color_cont">
                                <?php
                                foreach ($cur_sub_fold_names as $color_key => $cur_sub_fold_name) {
                                  ?>
                                <div class="spider_option_cont wds_rl_butt_col_groups" value="<?php echo $color_key; ?>" <?php echo (($cur_color_key == $color_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_rl_butt_color(this,<?php echo $cur_type_key; ?>)">
                                  <div  class="spider_option_cont_title" >
                                    <?php _e('Color', WDS()->prefix); echo '-' . ++$color_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img">
                                    <img class="src_col_top_left" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="src_col_top_right" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="src_col_bottom_left" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="src_col_bottom_right" style="display:inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                  <?php
                                }
                                if (!$flag) {
                                  /* Folder doesn't exist.*/
                                  ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                  <?php
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                          <div style="width:100px; display: table-cell; vertical-align: middle; text-align: center;" class="display_block">
                            <div style=" display: block; margin-left: 12px; vertical-align: middle; text-align: center;background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;" class="play_buttons_cont">
                            <img id="rl_butt_img_l" src="<?php echo $row->left_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="rl_butt_img_r" src="<?php echo $row->right_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="rl_butt_hov_img_l" src="<?php echo $row->left_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="rl_butt_hov_img_r" src="<?php echo $row->right_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            </div>
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;">
                            <input type="button" class="button button-small wds_reverse" onclick="change_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                        <p class="description"><?php _e('Choose the type and color of navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="right_left_butt_size">
                        <label class="wd-label" for="rl_butt_size"><?php _e('Next / Previous buttons size:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="rl_butt_size" id="rl_butt_size" value="<?php echo $row->rl_butt_size; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Set the size of Next and Previous buttons.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Play / Pause button:', WDS()->prefix); ?></label>
                        <input type="radio" name="play_paus_butt" id="play_paus_butt_1" value="1" <?php if ($row->play_paus_butt) echo 'checked="checked"'; ?> />
                        <label for="play_paus_butt_1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" name="play_paus_butt" id="play_paus_butt_0" value="0" <?php if (!$row->play_paus_butt) echo 'checked="checked"'; ?> />
                        <label for="play_paus_butt_0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Select this option to display Play and Pause buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Image for Play / Pause buttons:', WDS()->prefix); ?></label>
                        <input type="radio" name="play_paus_butt_img_or_not" id="play_pause_butt_img_or_not_our" value="our" <?php if ($row->play_paus_butt_img_or_not == 'our') echo 'checked="checked"'; ?> onClick="image_for_play_pause_butt('our')" />
                        <label for="play_pause_butt_img_or_not_our"><?php _e('Default', WDS()->prefix); ?></label>
                        <input type="radio" name="play_paus_butt_img_or_not" id="play_pause_butt_img_or_not_cust" value="custom" <?php if ($row->play_paus_butt_img_or_not == 'custom') echo 'checked="checked"'; ?> onClick="image_for_play_pause_butt('custom')" />
                        <label for="play_pause_butt_img_or_not_cust"><?php _e('Custom', WDS()->prefix); ?></label>
                        <input type="radio" name="play_paus_butt_img_or_not" id="play_pause_butt_img_or_not_select" value="style" <?php if ($row->play_paus_butt_img_or_not == 'style') echo 'checked="checked"'; ?> onClick="image_for_play_pause_butt('style')" />
                        <label for="play_pause_butt_img_or_not_select"><?php _e('Styled', WDS()->prefix); ?></label>
                        <input type="hidden" id="play_butt_url" name="play_butt_url" value="<?php echo $row->play_butt_url; ?>" />
                        <input type="hidden" id="play_butt_hov_url" name="play_butt_hov_url" value="<?php echo $row->play_butt_hov_url; ?>" />
                        <input type="hidden" id="paus_butt_url" name="paus_butt_url" value="<?php echo $row->paus_butt_url; ?>" />
                        <input type="hidden" id="paus_butt_hov_url" name="paus_butt_hov_url" value="<?php echo $row->paus_butt_hov_url; ?>" />
                        <p class="description"><?php _e('You can use default Play and Pause buttons or to upload custom icons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="play_pause_butt_style">
                        <label class="wd-label" for="pp_butt_style"><?php _e('Play / Pause buttons style:', WDS()->prefix); ?></label>
                        <div style="display: table-cell; vertical-align: middle; background-color: rgba(229, 229, 229, 0.62); text-align: center;">
                          <i id="wds_play_style" class="fa fa-play" style="color: #<?php echo $row->butts_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                          <i id="wds_paus_style" class="fa fa-pause" style="color: #<?php echo $row->butts_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                        </div>
                      </span>
                      <span class="wd-group" id="play_pause_butt_cust">
                        <label class="wd-label"><?php _e('Upload buttons images:', WDS()->prefix); ?></label>
                        <div style="display: table;">
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('play_but', event, false); return false;" value="<?php _e('Play Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'play_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Play Button', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Play Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('play_hov_but', event, false); return false;" value="<?php _e('Play Button Hover', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'play_hov_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Play Button Hover', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Play Button Hover', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('paus_but', event, false); return false;" value="<?php _e('Pause Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'paus_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Pause Button', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Pause Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('paus_hov_but', event, false); return false;" value="<?php _e('Pause Button Hover', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'paus_hov_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Pause Button Hover', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Pause Button Hover', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="width:100px; display: table-cell; vertical-align: middle; text-align: center;background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;" class="display_block">
                            <img id="play_butt_img" src="<?php echo $row->play_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="paus_butt_img" src="<?php echo $row->paus_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="play_butt_hov_img" src="<?php echo $row->play_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="paus_butt_hov_img" src="<?php echo $row->paus_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;" class="display_block wds_reverse_cont">
                            <input type="button" class="button button-small wds_reverse" onclick="wds_change_play_paus_custom_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                        <script>
                          var wds_pp_butt_type = [];
                          var pp_butt_dir = '<?php echo WDS()->plugin_url . '/images/button/'; ?>';
                          var pp_type_cur_fold = '1';
                          <?php
                          $folder_names = scandir(WDS()->plugin_dir . '/images/button');
                          $butt_cur_fold_name = '';
                          $butt_cur_type_key = '';
                          $butt_cur_color_key = '';
                          $butt_cur_sub_fold_names = array();
                          array_splice($folder_names, 0, 2);
                          $flag = FALSE;
                          foreach ($folder_names as $type_key => $folder_name) {
                            if (is_dir(WDS()->plugin_dir . '/images/button/' . $folder_name)) {
                              ?>
                              wds_pp_butt_type["<?php echo $type_key; ?>"] = [];
                              wds_pp_butt_type["<?php echo $type_key; ?>"]["type_name"] = "<?php echo $folder_name; ?>";
                              <?php
                              if ($row->play_butt_url != '') {
                                /* Getting current button's type folder and color folder.*/
                                $check_butt_cur_fold = explode('/' , $row->play_butt_url);
                                if (in_array($folder_name, $check_butt_cur_fold)) {
                                  $flag = TRUE;
                                  $butt_cur_fold_name = $folder_name;
                                  $butt_cur_type_key = $type_key;
                                  $butt_cur_sub_fold_names = scandir(WDS()->plugin_dir . '/images/button/' . $butt_cur_fold_name);
                                  array_splice($butt_cur_sub_fold_names, 0, 2);
                                  ?>
                                  pp_type_cur_fold = '<?php echo $butt_cur_type_key;?>';
                                  <?php
                                }
                              }
                              $sub_folder_names = scandir( WDS()->plugin_dir . '/images/button/' . $folder_name);
                              array_splice($sub_folder_names, 0, 2);
                              foreach ($sub_folder_names as $color_key => $sub_folder_name) {
                                if (is_dir(WDS()->plugin_dir . '/images/button/' . $folder_name . '/' . $sub_folder_name)) {
                                  if ($butt_cur_fold_name == $folder_name) {
                                    /* Getting current button's color key.*/
                                    if (in_array($sub_folder_name, $check_butt_cur_fold)) {
                                      $butt_cur_color_key = $color_key;
                                    }
                                  }
                                  ?>
                                  wds_pp_butt_type["<?php echo $type_key; ?>"]["<?php echo $color_key; ?>"] = "<?php echo $sub_folder_name; ?>";
                                  <?php
                                }
                              }
                            }
                            else {
                              ?>
                              console.log('<?php echo $folder_name . " is not a directory."; ?>');
                              <?php
                            }
                          }
                          ?>
                        </script>
                      </span>
                      <span class="wd-group" id="play_pause_butt_select">
                        <label class="wd-label" for="right_butt_url"><?php _e('Choose buttons:', WDS()->prefix); ?></label>
                        <div style="display: table; margin-bottom: 14px;">
                          <div style="display: table-cell; vertical-align: middle;" class="display_block" >
                            <div style="display: block; width: 122px;" class="default_buttons">
                              <div class="spider_choose_option" onclick="wds_choose_pp_option(this)">
                                <div class="spider_option_main_title"><?php _e('Choose group', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color: #1E8CBE"></i></div>
                              </div>
                              <div class="spider_pp_options_cont">
                              <?php
                              foreach ($folder_names as $type_key => $folder_name) {
                                ?>
                                <div class="spider_option_cont wds_pp_butt_groups" value="<?php echo $type_key; ?>" <?php echo (($butt_cur_type_key == $type_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_play_paus_butt_type(this)">
                                  <div  class="spider_option_cont_title">
                                    <?php _e('Group', WDS()->prefix);  echo '-' . ++$type_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img">
                                    <img class="pp_src_top_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_top_right" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_bottom_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_bottom_right" style="display: inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                <?php
                              }
                              if (!$flag) {
                                /* Folder doesn't exist.*/
                                ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                <?php
                              }
                              ?>
                              </div>
                            </div>
                          </div>
                          <div style="display:table-cell;vertical-align: middle;" class="display_block">
                            <div style="display: block; width: 122px; margin-left: 12px;" class="default_buttons">
                              <div class="spider_choose_option" onclick="<?php echo (WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\')' : 'wds_choose_pp_option_color(this)'); ?>">
                                <div  class="spider_option_main_title"><?php _e('Choose color', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color:#1E8CBE"></i></div>
                              </div>
                              <div class="spider_pp_options_color_cont">
                                <?php
                                foreach ($butt_cur_sub_fold_names as $color_key => $cur_sub_fold_name) {
                                  ?>
                                <div class="spider_option_cont wds_pp_butt_col_groups" value="<?php echo $color_key; ?>" <?php echo (($butt_cur_color_key == $color_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_play_paus_butt_color(this, <?php echo $cur_type_key; ?>)">
                                  <div  class="spider_option_cont_title" >
                                    <?php _e('Color', WDS()->prefix); echo '-' . ++$color_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img">
                                    <img class="pp_src_col_top_left" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_col_top_right" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_col_bottom_left" style="display:inline-block; width: 14px; height: 14px;" />
                                    <img class="pp_src_col_bottom_right" style="display:inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                  <?php
                                }
                                if (!$flag) {
                                  /* Folder doesn't exist.*/
                                  ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                  <?php
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                          <div style="width:100px; display: table-cell; vertical-align: middle; text-align: center;" class="display_block">
                            <div style=" display: block; margin-left: 12px; vertical-align: middle; text-align: center;background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;" class="play_buttons_cont">
                              <img id="pp_butt_img_play" src="<?php echo $row->play_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                              <img id="pp_butt_img_paus" src="<?php echo $row->paus_butt_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                              <img id="pp_butt_hov_img_play" src="<?php echo $row->play_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                              <img id="pp_butt_hov_img_paus" src="<?php echo $row->paus_butt_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            </div>
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;">
                            <input type="button" class="button button-small wds_reverse" onclick="change_play_paus_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                        <p class="description"><?php _e('Choose the type and color of navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="play_pause_butt_size">
                        <label class="wd-label" for="pp_butt_size"><?php _e('Play / Pause button size:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="pp_butt_size" id="pp_butt_size" value="<?php echo $row->pp_butt_size; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Set the size of Play and Pause buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="tr_butts_color">
                        <label class="wd-label" for="butts_color"><?php _e('Buttons color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="butts_color" id="butts_color" value="<?php echo $row->butts_color; ?>" class="color" onchange="jQuery('#wds_left_style,#wds_right_style,#wds_play_style,#wds_paus_style').css({color: '#' + jQuery(this).val()})" />
                        <p class="description"><?php _e('Select a color for the navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="tr_hover_color">
                        <label class="wd-label" for="hover_color"><?php _e('Hover color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="hover_color" id="hover_color" value="<?php echo $row->hover_color; ?>" class="color" />
                        <p class="description"><?php _e('Select a hover color for the navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
                        <label class="wd-label" for="nav_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="nav_border_width" id="nav_border_width" value="<?php echo $row->nav_border_width; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320"  name="nav_border_style" id="nav_border_style">
                          <?php
                          foreach ($border_styles as $key => $border_style) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (($row->nav_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="nav_border_color" id="nav_border_color" value="<?php echo $row->nav_border_color; ?>" class="color" />
                        <p class="description"><?php _e('Select the type, size and the color of border for the navigation buttons.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
                        <label class="wd-label" for="nav_border_radius"><?php _e('Border radius:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="nav_border_radius" id="nav_border_radius" value="<?php echo $row->nav_border_radius; ?>" class="spider_char_input" />
                        <p class="description"><?php _e('Use CSS type values (e.g. 4px).', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
                        <label class="wd-label" for="nav_bg_color"><?php _e('Background color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="nav_bg_color" id="nav_bg_color" value="<?php echo $row->nav_bg_color; ?>" class="color" />
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="butts_transparent" id="butts_transparent" value="<?php echo $row->butts_transparent; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                        <p class="description"><?php _e('Transparency Value must be between 0 and 100.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_bullets_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Enable bullets:', WDS()->prefix); ?></label>
                        <input type="radio" id="enable_bullets1" name="enable_bullets" <?php echo (($row->enable_bullets) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="enable_bullets1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="enable_bullets0" name="enable_bullets" <?php echo (($row->enable_bullets) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="enable_bullets0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable navigation bullets with this option.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Show bullets:', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_hover" id="bull_hover_0" value="0" <?php if ($row->bull_hover == 0) echo 'checked="checked"'; ?> />
                        <label for="bull_hover_0"><?php _e('On hover', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_hover" id="bull_hover_1" value="1" <?php if ($row->bull_hover == 1) echo 'checked="checked"'; ?> />
                        <label for="bull_hover_1"><?php _e('Always', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('You can display navigation bullets always or only when hovered.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Show thumbnail on bullet hover:', WDS()->prefix); ?></label>
                        <input onClick="wds_enable_disable('', 'tr_thumb_size', 'show_thumbnail1')" type="radio" id="show_thumbnail1" name="show_thumbnail" <?php echo (($row->show_thumbnail) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="show_thumbnail1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input onClick="wds_enable_disable('none', 'tr_thumb_size', 'show_thumbnail0')" type="radio" id="show_thumbnail0" name="show_thumbnail" <?php echo (($row->show_thumbnail) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="show_thumbnail0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"></p>
                      </span>
                      <span class="wd-group" id="tr_thumb_size">
                        <label class="wd-label" for="wds_thumb_size"><?php _e('Thumbnail Size:', WDS()->prefix); ?></label>
                        <input onblur="wds_check_number()" type="number" step="0.1" min="0" max="1" id="wds_thumb_size" name="wds_thumb_size" size="15" value="<?php echo $row->thumb_size; ?>" style="display:inline-block;" />
                        <p class="description"><?php _e('Value must be between 0 to 1.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Position:', WDS()->prefix); ?></label>
                        <select class="select_icon select_icon_320" name="bull_position" id="bull_position">
                          <option value="top" <?php echo (($row->bull_position == "top") ? 'selected="selected"' : ''); ?>><?php _e('Top', WDS()->prefix); ?></option>
                          <option value="bottom" <?php echo (($row->bull_position == "bottom") ? 'selected="selected"' : ''); ?>><?php _e('Bottom', WDS()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e('Select the position for navigation bullets.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Bullets type:', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_butt_img_or_not" id="bull_butt_img_or_not_our" value="our" <?php if ($row->bull_butt_img_or_not == 'our') echo 'checked="checked"'; ?> onClick="image_for_bull_butt('our')" />
                        <label for="bull_butt_img_or_not_our"><?php _e('Default', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_butt_img_or_not" id="bull_butt_img_or_not_cust" value="custom" <?php if ($row->bull_butt_img_or_not == 'custom') echo 'checked="checked"'; ?> onClick="image_for_bull_butt('custom')" />
                        <label for="bull_butt_img_or_not_cust"><?php _e('Custom', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_butt_img_or_not" id="bull_butt_img_or_not_stl" value="style" <?php if ($row->bull_butt_img_or_not == 'style') echo 'checked="checked"'; ?> onClick="image_for_bull_butt('style')" />
                        <label for="bull_butt_img_or_not_stl"><?php _e('Styled', WDS()->prefix); ?></label>
                        <input type="radio" name="bull_butt_img_or_not" id="bull_butt_img_or_not_txt" value="text" <?php if ($row->bull_butt_img_or_not == 'text') echo 'checked="checked"'; ?> onClick="image_for_bull_butt('text')" />
                        <label for="bull_butt_img_or_not_txt"><?php _e('Text', WDS()->prefix); ?></label>
                        <input type="hidden" id="bullets_img_main_url" name="bullets_img_main_url" value="<?php echo $row->bullets_img_main_url; ?>" />
                        <input type="hidden" id="bullets_img_hov_url" name="bullets_img_hov_url" value="<?php echo $row->bullets_img_hov_url; ?>" />
                        <p class="description"></p>
                      </span>
                      <span class="wd-group" id="bullets_style">
                        <label class="wd-label" for="bull_style"><?php _e('Bullet style:', WDS()->prefix); ?></label>
                        <div style="display: table;">
                          <div style="display: table-cell; vertical-align: middle;">
                            <select class="select_icon select_icon_320" name="bull_style" id="bull_style" onchange="change_bull_style(jQuery(this).val())">
                              <?php
                              foreach ($bull_styles as $key => $bull_style) {
                                ?>
                                <option value="<?php echo $key; ?>" <?php echo (($row->bull_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $bull_style; ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                          <div style="display: table-cell; vertical-align: middle; background-color: rgba(229, 229, 229, 0.62); text-align: center;">
                            <i id="wds_act_bull_style" class="fa <?php echo str_replace('-o', '', $row->bull_style); ?>" style="color: #<?php echo $row->bull_act_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                            <i id="wds_deact_bull_style" class="fa <?php echo $row->bull_style; ?>" style="color: #<?php echo $row->bull_color; ?>; display: inline-block; font-size: 40px; width: 40px; height: 40px;"></i>
                          </div>
                        </div>
                        <p class="description"><?php _e('Choose the style for the bullets.', WDS()->prefix); ?></p>
                        <script>
                          var wds_blt_img_type = [];
                          var blt_img_dir = '<?php echo WDS()->plugin_url . '/images/bullet/'; ?>';
                          var bull_type_cur_fold = '1';
                          <?php
                          $folder_names = scandir(WDS()->plugin_dir . '/images/bullet');
                          $bull_cur_fold_name = '';
                          $bull_cur_type_key = '';
                          $bull_cur_color_key = '';
                          $bull_cur_sub_fold_names = array();
                          array_splice($folder_names, 0, 2);
                          $flag = FALSE;
                          foreach ($folder_names as $type_key => $folder_name) {
                            if (is_dir(WDS()->plugin_dir . '/images/bullet/' . $folder_name)) {
                              ?>
                              wds_blt_img_type["<?php echo $type_key; ?>"] = [];
                              wds_blt_img_type["<?php echo $type_key; ?>"]["type_name"] = "<?php echo $folder_name; ?>";
                              <?php
                              if ($row->bullets_img_main_url != '') {
                                /* Getting current button's type folder and color folder.*/
                                $check_bull_cur_fold = explode('/' , $row->bullets_img_main_url);
                                if (in_array($folder_name, $check_bull_cur_fold)) {
                                  $flag = TRUE;
                                  $bull_cur_fold_name = $folder_name;
                                  $bull_cur_type_key = $type_key;
                                  $bull_cur_sub_fold_names = scandir(WDS()->plugin_dir . '/images/bullet/' . $bull_cur_fold_name);
                                  array_splice($bull_cur_sub_fold_names, 0, 2);
                                  ?>
                              bull_type_cur_fold = '<?php echo $bull_cur_type_key;?>';
                                  <?php
                                }
                              }
                              $sub_folder_names = scandir(WDS()->plugin_dir . '/images/bullet/' . $folder_name);
                              array_splice($sub_folder_names, 0, 2);
                              foreach ($sub_folder_names as $color_key => $sub_folder_name) {
                                if (is_dir(WDS()->plugin_dir . '/images/bullet/' . $folder_name . '/' . $sub_folder_name)) {
                                  if ($bull_cur_fold_name == $folder_name) {
                                    /* Getting current button's color key.*/
                                    if (in_array($sub_folder_name, $check_bull_cur_fold)) {
                                      $bull_cur_color_key = $color_key;
                                    }
                                  }
                                  ?>
                                  wds_blt_img_type["<?php echo $type_key; ?>"]["<?php echo $color_key; ?>"] = "<?php echo $sub_folder_name; ?>";
                                  <?php
                                }
                              }
                            }
                            else {
                              ?>
                              console.log('<?php echo $folder_name . " is not a directory."; ?>');
                              <?php
                            }
                          }
                          ?>
                        </script>
                      </span>
                      <span class="wd-group" id="bullets_images_cust">
                        <label class="wd-label"><?php _e('Upload buttons images:', WDS()->prefix); ?></label>
                        <div style="display: table;">
                          <div style="display: table-cell; vertical-align: middle;">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('bullets_main_but', event, false); return false;" value="<?php _e('Active Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'bullets_main_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="Add Image" onclick="return false;">
                            <?php _e('Active Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="display: table-cell; vertical-align: middle;">
                            <?php
                            if (!$spider_uploader) {
                              ?>
                            <input class="button button-secondary wds_ctrl_btn_upload" type="button" onclick="wds_media_uploader('bullets_hov_but', event, false); return false;" value="<?php _e('Inactive Button', WDS()->prefix); ?>" />
                              <?php
                            }
                            else {
                              ?>
                            <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'bullets_hov_but', 'dir' => '/arrows', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview wds_ctrl_btn_upload" title="<?php _e('Inactive Button', WDS()->prefix); ?>" onclick="return false;">
                            <?php _e('Inactive Button', WDS()->prefix); ?>
                            </a>
                              <?php
                            }
                            ?>
                          </div>
                          <div style="width:100px; display: table-cell; vertical-align: middle; text-align: center;background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;">
                            <img id="bull_img_main" src="<?php echo $row->bullets_img_main_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                            <img id="bull_img_hov" src="<?php echo $row->bullets_img_hov_url; ?>" style="display:inline-block; width: 40px; height: 40px;" />
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;">
                            <input type="button" class="button button-small wds_reverse" onclick="wds_change_bullets_custom_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                      </span>
                      <span class="wd-group" id="bullets_images_select">
                        <label class="wd-label" for="bullets_images_url"><?php _e('Choose buttons:', WDS()->prefix); ?></label>
                        <div style="display: table; margin-bottom: 14px;">
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <div style="display: block; width: 122px;" class="default_buttons">
                              <div class="spider_choose_option" onclick="wds_choose_bull_option(this)">
                                <div class="spider_option_main_title"><?php _e('Choose group', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color: #1E8CBE;"></i></div>
                              </div>
                              <div class="spider_bull_options_cont">
                              <?php
                              foreach ($folder_names as $type_key => $folder_name) {
                                ?>
                                <div class="spider_option_cont wds_bull_butt_groups" value="<?php echo $type_key; ?>" <?php echo (($bull_cur_type_key == $type_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_bullets_images_type(this)">
                                  <div class="spider_option_cont_title" style="width: 64%;">
                                    <?php _e('Group', WDS()->prefix);  echo '-' . ++$type_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img">
                                    <img class="bull_src_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="bull_src_right" style="display: inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                <?php
                              }
                              if (!$flag) {
                                /* Folder doesn't exist.*/
                                ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                <?php
                              }
                              ?>
                              </div>
                            </div>
                          </div>
                          <div style="display: table-cell; vertical-align: middle;" class="display_block">
                            <div style="display: block; width: 122px; margin-left: 12px;">
                              <div class="spider_choose_option" onclick="<?php echo (WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\')' : 'wds_choose_bull_option_color(this)'); ?>">
                                <div class="spider_option_main_title"><?php _e('Choose color', WDS()->prefix); ?></div>
                                <div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg" style="color: #1E8CBE;"></i></div>
                              </div>
                              <div class="spider_bull_options_color_cont">
                                <?php
                                foreach ($bull_cur_sub_fold_names as $color_key => $cur_sub_fold_name) {
                                  ?>
                                <div class="spider_option_cont wds_bull_butt_col_groups" value="<?php echo $color_key; ?>" <?php echo (($bull_cur_color_key == $color_key) ? 'selected="selected" style="background-color: #3399FF;"' : ''); ?> onclick="change_bullets_images_color(this, <?php echo $bull_cur_type_key; ?>)">
                                  <div  class="spider_option_cont_title" style="width: 64%;">
                                    <?php echo _e('Color', WDS()->prefix); '-' . ++$color_key; ?>
                                  </div>
                                  <div class="spider_option_cont_img" style="width: 22%;">
                                    <img class="bull_col_src_left" style="display: inline-block; width: 14px; height: 14px;" />
                                    <img class="bull_col_src_right" style="display: inline-block; width: 14px; height: 14px;" />
                                  </div>
                                </div>
                                  <?php
                                }
                                if (!$flag) {
                                  /* Folder doesn't exist.*/
                                  ?>
                                <div class="spider_option_cont" value="0" selected="selected" disabled="disabled"><?php _e('Custom', WDS()->prefix); ?></div>
                                  <?php
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                          <div style="width: 100px; display: table-cell; vertical-align: middle; text-align: center;">
                            <div style="display: block; vertical-align: middle; margin-left: 12px; text-align: center; background-color: rgba(229, 229, 229, 0.62); padding-top: 4px; border-radius: 3px;">
                              <img id="bullets_img_main" src="<?php echo $row->bullets_img_main_url; ?>" style="display: inline-block; width: 40px; height: 40px;" />
                              <img id="bullets_img_hov" src="<?php echo $row->bullets_img_hov_url; ?>" style="display: inline-block; width: 40px; height: 40px;" />
                            </div>
                          </div>
                          <div style="display: table-cell; text-align: center; vertical-align: middle;">
                            <input type="button" class="button button-small wds_reverse" onclick="change_bullets_src()" value="<?php _e('Reverse', WDS()->prefix); ?>" />
                          </div>
                        </div>
                        <p class="description"><?php _e('Choose the type and color for the bullets.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group" id="bullet_size">
                        <label class="wd-label" for="bull_size"><?php _e('Size:', WDS()->prefix); ?></label>
                        <input type="text" name="bull_size" id="bull_size" value="<?php echo $row->bull_size; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Set the size of navigation bullets.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="bull_color"><?php _e('Color:', WDS()->prefix); ?></label>
                        <input type="text" name="bull_color" id="bull_color" value="<?php echo $row->bull_color; ?>" class="color" onchange="jQuery('#wds_deact_bull_style').css({color: '#' + jQuery(this).val()})" />
                        <p class="description"><?php _e('Select the color for navigation bullets.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="bullets_act_color">
                        <label class="wd-label" for="bull_act_color"><?php _e('Active color:', WDS()->prefix); ?></label>
                        <input type="text" name="bull_act_color" id="bull_act_color" value="<?php echo $row->bull_act_color; ?>" class="color" onchange="jQuery('#wds_act_bull_style').css({color: '#' + jQuery(this).val()})" />
                        <p class="description"><?php _e('Select the color for the bullet, which is currently displaying a corresponding image.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="bullets_back_act_color">
                        <label class="wd-label" for="bull_back_act_color"><?php _e('Active Background color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="bull_back_act_color" id="bull_back_act_color" value="<?php echo $row->bull_back_act_color; ?>" class="color" onchange="jQuery('#wds_back_act_bull_text').css({color: '#' + jQuery(this).val()})" />
                        <p class="description"><?php _e('Select the background color for the bullet, which is currently displaying a corresponding image.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="bullets_back_color">
                        <label class="wd-label" for="bull_back_color"><?php _e('Background color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="bull_back_color" id="bull_back_color" value="<?php echo $row->bull_back_color; ?>" class="color" onchange="jQuery('#wds_back_bull_text').css({color: '#' + jQuery(this).val()})" />
                        <p class="description"><?php _e('Select the background color for the bullet...', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>" id="bullets_radius">
                        <label class="wd-label" for="bull_radius"><?php _e('Border radius:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="bull_radius" id="bull_radius" value="<?php echo $row->bull_radius; ?>" class="spider_char_input" />
                        <p class="description"><?php _e('Use CSS type values (e.g. 4px).', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="bullet_margin">
                        <label class="wd-label" for="bull_margin"><?php _e('Margin:', WDS()->prefix); ?></label>
                        <input type="text" name="bull_margin" id="bull_margin" value="<?php echo $row->bull_margin; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Set the margin for navigation bullets.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_filmstrip_box<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <?php
                      if ( WDS()->is_free ) {
                        echo WDW_S_Library::message_id(0, __('This functionality is disabled in free version.', WDS()->prefix), 'error');
                      }
                      ?>
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Enable filmstrip:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="enable_filmstrip1" name="enable_filmstrip" <?php echo (($row->enable_filmstrip) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="enable_filmstrip1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="radio" id="enable_filmstrip0" name="enable_filmstrip" <?php echo (($row->enable_filmstrip) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="enable_filmstrip0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Enable this option to display thumbnails of the slides in a filmstrip.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="filmstrip_small_screen">
                        <label class="wd-label" for="film_small_screen"><?php _e('Hide filmstrip on small screens:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_small_screen" id="film_small_screen" value="<?php echo $row->film_small_screen; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Hide slider filmstrip when screen size is smaller than this value.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="filmstrip_position">
                        <label class="wd-label"><?php _e('Position:', WDS()->prefix); ?></label>
                        <select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" name="film_pos" id="film_pos">
                          <option value="top" <?php echo (($row->film_pos == "top") ? 'selected="selected"' : ''); ?>><?php _e('Top', WDS()->prefix); ?></option>
                          <option value="right" <?php echo (($row->film_pos == "right") ? 'selected="selected"' : ''); ?>><?php _e('Right', WDS()->prefix); ?></option>
                          <option value="bottom" <?php echo (($row->film_pos == "bottom") ? 'selected="selected"' : ''); ?>><?php _e('Bottom', WDS()->prefix); ?></option>
                          <option value="left" <?php echo (($row->film_pos == "left") ? 'selected="selected"' : ''); ?>><?php _e('Left', WDS()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e('Set the position of the filmstrip.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="filmstrip_size">
                        <label class="wd-label" for="film_thumb_width"><?php _e('Thumbnail dimensions:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_thumb_width" id="film_thumb_width" value="<?php echo $row->film_thumb_width; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> x
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_thumb_height" id="film_thumb_height" value="<?php echo $row->film_thumb_height; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('Define the maximum width and heigth of the filmstrip thumbnails.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="film_bg_color"><?php _e('Background color:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_bg_color" id="film_bg_color" value="<?php echo $row->film_bg_color; ?>" class="color" />
                        <p class="description"><?php _e('Select the background color for the filmstrip.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group" id="filmstrip_thumb_margin">
                        <label class="wd-label" for="film_tmb_margin"><?php _e('Thumbnail separator:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_tmb_margin" id="film_tmb_margin" value="<?php echo $row->film_tmb_margin; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <p class="description"><?php _e('Set the size of the separator for thumbnails.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="film_act_border_width"><?php _e('Active border:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_act_border_width" id="film_act_border_width" value="<?php echo $row->film_act_border_width; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                        <select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" name="film_act_border_style" id="film_act_border_style">
                          <?php
                          foreach ($border_styles as $key => $border_style) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (($row->film_act_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_act_border_color" id="film_act_border_color" value="<?php echo $row->film_act_border_color; ?>" class="color"/>
                        <p class="description"><?php _e('The thumbnail for the currently displayed image will have a border. You can set its size, type and color.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="film_dac_transparent"><?php _e('Inactive transparency:', WDS()->prefix); ?></label>
                        <input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" name="film_dac_transparent" id="film_dac_transparent" value="<?php echo $row->film_dac_transparent; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                        <p class="description"><?php _e('You can set a transparency level for the inactive filmstrip items which must be between 0 to 100..', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_timer_bar_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label"><?php _e('Enable timer bar:', WDS()->prefix); ?></label>
                        <input type="radio" id="enable_time_bar1" name="enable_time_bar" <?php echo (($row->enable_time_bar) ? 'checked="checked"' : ''); ?> value="1" />
                        <label for="enable_time_bar1"><?php _e('Yes', WDS()->prefix); ?></label>
                        <input type="radio" id="enable_time_bar0" name="enable_time_bar" <?php echo (($row->enable_time_bar) ? '' : 'checked="checked"'); ?> value="0" />
                        <label for="enable_time_bar0"><?php _e('No', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('You can add a bar, which displays the time left untill the slider switches to the next slide on autoplay.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="timer_bar_type"><?php _e('Type:', WDS()->prefix); ?></label>
                        <select class="select_icon select_icon_320" name="timer_bar_type" id="timer_bar_type">
                          <option value="top" <?php echo (($row->timer_bar_type == "top") ? 'selected="selected"' : ''); ?>><?php _e('Line top', WDS()->prefix); ?></option>
                          <option value="bottom" <?php echo (($row->timer_bar_type == "bottom") ? 'selected="selected"' : ''); ?>><?php _e('Line Bottom', WDS()->prefix); ?></option>
                          <option value="circle_top_left" <?php echo (($row->timer_bar_type == "circle_top_left") ? 'selected="selected"' : ''); ?>><?php _e('Circle top left', WDS()->prefix); ?></option>
                          <option value="circle_top_right" <?php echo (($row->timer_bar_type == "circle_top_right") ? 'selected="selected"' : ''); ?>><?php _e('Circle top right', WDS()->prefix); ?></option>
                          <option value="circle_bot_left" <?php echo (($row->timer_bar_type == "circle_bot_left") ? 'selected="selected"' : ''); ?>><?php _e('Circle bottom left', WDS()->prefix); ?></option>
                          <option value="circle_bot_right" <?php echo (($row->timer_bar_type == "circle_bot_right") ? 'selected="selected"' : ''); ?>><?php _e('Circle bottom right', WDS()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e('Choose the type of the timer bar to be used within the slider.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label" for="timer_bar_size"><?php _e('Size:', WDS()->prefix); ?></label>
                        <input type="text" name="timer_bar_size" id="timer_bar_size" value="<?php echo $row->timer_bar_size; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('Define the height of the timer bar.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group">
                        <label class="wd-label" for="timer_bar_color"><?php _e('Color:', WDS()->prefix); ?></label>
                        <input type="text" name="timer_bar_color" id="timer_bar_color" value="<?php echo $row->timer_bar_color; ?>" class="color" />
                        <input type="text" name="timer_bar_transparent" id="timer_bar_transparent" value="<?php echo $row->timer_bar_transparent; ?>" class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                        <p class="description"><?php _e('Transparency Value must be between 0 and 100.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_watermark_box">
              <div class="wd_updated">
                <p><?php _e('Please note that the <b>Fill</b> and <b>Contain</b> options will work fine with <b>Watermark</b> option regardless of the image dimensions, whereas for the <b>Cover</b> option you should have the image identical to the size set in the <b>Dimensions</b> settings. 
                If you have uploaded the image with another dimension, you will need to resize the image and upload it again.', WDS()->prefix); ?>
                </p>
              </div>
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group" id="tr_built_in_watermark_type">
                        <label class="wd-label"><?php _e('Watermark type:', WDS()->prefix); ?></label>
                        <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_none" value="none" <?php if ($row->built_in_watermark_type == 'none') echo 'checked="checked"'; ?> onClick="wds_built_in_watermark('watermark_type_none')" />
                        <label for="built_in_watermark_type_none"><?php _e('None', WDS()->prefix); ?></label>
                        <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_text" value="text" <?php if ($row->built_in_watermark_type == 'text') echo 'checked="checked"'; ?> onClick="wds_built_in_watermark('watermark_type_text')" onchange="preview_built_in_watermark()" />
                        <label for="built_in_watermark_type_text"><?php _e('Text', WDS()->prefix); ?></label>
                        <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_image" value="image" <?php if ($row->built_in_watermark_type == 'image') echo 'checked="checked"'; ?> onClick="wds_built_in_watermark('watermark_type_image')" onchange="preview_built_in_watermark()" />
                        <label for="built_in_watermark_type_image"><?php _e('Image', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Choose the kind of watermark you would like to use.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_url">
                        <label class="wd-label" for="built_in_watermark_url"><?php _e('Watermark url:', WDS()->prefix); ?></label>
                        <input type="text" id="built_in_watermark_url" name="built_in_watermark_url" style="width: 68%;" value="<?php echo $row->built_in_watermark_url; ?>" style="display:inline-block;" onchange="preview_built_in_watermark()" />
                        <?php
                        if (!$spider_uploader) {
                          ?>
                        <input id="wat_img_add_butt" class="button button-secondary" type="button" onclick="wds_media_uploader('watermark', event, false); return false;" value="<?php _e('Add Image', WDS()->prefix); ?>" />
                          <?php
                        }
                        else {
                          ?>
                        <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'watermark', 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview" title="<?php _e('Add Image', WDS()->prefix); ?>" onclick="return false;">
                         <?php _e('Add Image', WDS()->prefix); ?>
                        </a>
                          <?php
                        }
                        ?>
                        <p class="description"><?php _e('Only .png format is supported.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_text">
                        <label class="wd-label" for="built_in_watermark_text"><?php _e('Watermark text:', WDS()->prefix); ?></label>
                        <input type="text" name="built_in_watermark_text" id="built_in_watermark_text" style="width: 100%;" value="<?php echo $row->built_in_watermark_text; ?>" onchange="preview_built_in_watermark()" onkeypress="preview_built_in_watermark()" />
                        <p class="description"><?php _e('Write the text of the watermark. It will be displayed on the slides.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_size">
                        <label class="wd-label" for="built_in_watermark_size"><?php _e('Watermark size:', WDS()->prefix); ?></label>
                        <input type="text" name="built_in_watermark_size" id="built_in_watermark_size" value="<?php echo $row->built_in_watermark_size; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" /> %
                        <p class="description"><?php _e('Enter size of watermark in percents according to image.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_font_size">
                        <label class="wd-label" for="built_in_watermark_font_size"><?php _e('Watermark font size:', WDS()->prefix); ?></label>
                        <input type="text" name="built_in_watermark_font_size" id="built_in_watermark_font_size" value="<?php echo $row->built_in_watermark_font_size; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" onkeypress="return spider_check_isnum(event)" /> px
                        <p class="description"><?php _e('Specify the font size of the watermark.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_font">
                        <label class="wd-label" for="built_in_watermark_font"><?php _e('Watermark font style:', WDS()->prefix); ?></label>
                        <select class="select_icon select_icon_320" name="built_in_watermark_font" id="built_in_watermark_font" style="width:150px;" onchange="preview_built_in_watermark()">
                          <?php
                          foreach ($built_in_watermark_fonts as $watermark_font) {
                          ?>
                          <option value="<?php echo $watermark_font; ?>" <?php if ($row->built_in_watermark_font == $watermark_font) echo 'selected="selected"'; ?>><?php echo $watermark_font; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                        <?php
                          foreach ($built_in_watermark_fonts as $watermark_font) {
                          ?>
                          <style>
                          @font-face {
                            font-family: <?php echo 'wds_' . str_replace('.ttf', '', $watermark_font); ?>;
                            src: url("<?php echo WDS()->plugin_url . '/fonts/' . $watermark_font; ?>");
                           }
                          </style>
                          <?php
                          }
                        ?>
                        <p class="description"><?php _e('Specify the font family for the watermark text.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_color">
                        <label class="wd-label" for="built_in_watermark_color"><?php _e('Watermark color:', WDS()->prefix); ?></label>
                        <input type="text" name="built_in_watermark_color" id="built_in_watermark_color" value="<?php echo $row->built_in_watermark_color; ?>" class="color" onchange="preview_built_in_watermark()" />
                        <input type="text" name="built_in_watermark_opacity" id="built_in_watermark_opacity" value="<?php echo $row->built_in_watermark_opacity; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" /> %
                        <p class="description"><?php _e('Transparency Value must be between 0 and 100.', WDS()->prefix); ?></p>
                      </span>
                      <span class="wd-group" id="tr_built_in_watermark_position">
                        <label class="wd-label"><?php _e('Watermark position:', WDS()->prefix); ?></label>
                        <table class="wds_position_table">
                          <tbody>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="top-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="top-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="top-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                            </tr>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="middle-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="middle-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="middle-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                            </tr>
                            <tr>
                              <td class="wds_position_td"><input type="radio" value="bottom-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="bottom-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              <td class="wds_position_td"><input type="radio" value="bottom-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                            </tr>
                          </tbody>
                        </table>
                        <p class="description"><?php _e('Select the position of the watermark.', WDS()->prefix); ?></p>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <input class="button button-secondary" type="button" onclick="spider_set_input_value('task', 'set_watermark'); wds_spider_ajax_save('sliders_form', event);" value="<?php _e('Set Watermark', WDS()->prefix); ?>" />
                        <input class="button button-secondary" type="button" onclick="spider_set_input_value('task', 'reset_watermark'); wds_spider_ajax_save('sliders_form', event);" value="<?php _e('Reset Watermark', WDS()->prefix); ?>" />
                      </span>
                      <span class="wd-group">
                        <span id="preview_built_in_watermark" style='display:table-cell; background-image:url("<?php echo WDS()->plugin_url . '/images/watermark_preview.jpg'?>"); background-size:100% 100%;width:400px;height:400px;padding-top: 4px; position:relative;'></span>
                      </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_css_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group">
                        <label class="wd-label" for="css"><?php _e('Css:', WDS()->prefix); ?></label>
                        <p class="description"><?php _e('Write additional CSS code to apply custom styles to the slider.', WDS()->prefix); ?></p>
                        <textarea id="css" name="css" rows="15" style="width: 50%;"><?php echo html_entity_decode($row->css); ?></textarea>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wds_nav_box wds_nav_callbacks_box">
              <div class="wd-table">
                <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                  <div class="wd-box-section">
                    <div class="wd-box-content">
                      <span class="wd-group callback_label_options">
                        <label class="wd-label" for="css"><?php _e('Add new callback:', WDS()->prefix); ?></label>
                        <div style="vertical-align: middle;">
                          <select class="select_icon select_icon_320" name="callback_list" id="callback_list">
                            <?php
                            $callback_items = json_decode(htmlspecialchars_decode($row->javascript), TRUE);
                            foreach ($slider_callbacks as $key => $slider_callback) {
                              ?>
                              <option value="<?php echo $key; ?>" <?php echo (($row->javascript == $key) ? 'selected="selected"' : ''); ?> <?php echo (isset($callback_items[$key]))? "style='display:none'" : ""; ?>><?php echo $slider_callback; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <button type="button" id="add_callback" class="action_buttons add_callback" onclick="add_new_callback(jQuery(this).closest('span'),jQuery(this).closest('span').find('select'));"></button>
                          <p class="description"></p>
                        </div>
                      </span>
                      <span class="wd-group">
                        <?php
                        if (is_array($callback_items) && count($callback_items)) {
                          foreach ($callback_items as $key => $callback_item) {
                            ?>
                          <div class="callbeck-item">
                            <span class="wd-label"><?php echo $slider_callbacks[$key]; ?></span>
                            <textarea class="callbeck-textarea" name="<?php echo $key; ?>"><?php echo $callback_item; ?></textarea>
                            <button type="button" id="remove_callback" class="action_buttons remove_callback" onclick="remove_callback_item(this);"><?php _e('Remove', WDS()->prefix); ?></button>
                          </div>
                            <?php
                          }
                        }
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--------------Slides tab----------->
        <div class="wds_box wds_slides_box meta-box-sortables">
          <div class="clear"></div>
          <div class="bgcolor wds_tabs wbs_subtab aui-sortable">
            <h2 class="titles wd-slides-title"><?php _e('Slides', WDS()->prefix); ?></h2>
            <?php
            $slides_name = array();
            foreach ( $slides_row as $key => $slide_row ) {
              $slides_name[$slide_row->id] = $slide_row->title;
              ?>
              <div id="wds_subtab_wrap<?php echo $slide_row->id; ?>" class="wds_subtab_wrap connectedSortable">
                <div id="wbs_subtab<?php echo $slide_row->id; ?>" class="tab_link <?php echo (((($id == 0 || !$sub_tab_type) || (strpos($sub_tab_type, 'pr') !== FALSE)) && $key == 0) || ('slide' . $slide_row->id == $sub_tab_type)) ? 'wds_sub_active' : ''; ?>">
                  <div style='background-image:url("<?php echo $slide_row->type != 'image' ? ($slide_row->type == 'video' && ctype_digit($slide_row->thumb_url) ? (wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) : WDS()->plugin_url . '/images/no-video.png') : $slide_row->thumb_url) : $slide_row->thumb_url ?>");background-position: center' class="tab_image" id="wds_tab_image<?php echo $slide_row->id; ?>" data-id="<?php echo $slide_row->id; ?>">
                    <div class="tab_buttons">
                      <div class="handle_wrap">
                        <div class="handle" title="<?php _e('Drag to re-order', WDS()->prefix); ?>"></div>
                      </div>
                      <div class="wds_tab_title_wrap">
                        <input type="text" id="title<?php echo $slide_row->id; ?>" name="title<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->title; ?>" class="wds_tab_title" tab_type="slide<?php echo $slide_row->id; ?>" data-id="<?php echo $slide_row->id; ?>" onchange="wds_set_slide_title('<?php echo $slide_row->id; ?>');" />
                      </div>
                      <input type="hidden" name="order<?php echo $slide_row->id; ?>" id="order<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->order; ?>" />
                    </div>
                    <div class="wds_overlay" >
                      <div id="hover_buttons">
                        <span class="wds_change_thumbnail" onclick="wds_media_uploader_add_slide(event, '<?php echo $slide_row->id; ?>', false); return false;" title="<?php _e('Edit Image', WDS()->prefix); ?>" value="<?php _e('Edit Image', WDS()->prefix); ?>"></span>
                        <span class="wds_slide_dublicate" title="<?php _e('Duplicate Slide', WDS()->prefix); ?>" onclick="wds_duplicate_slide('<?php echo $slide_row->id; ?>');"></span>
                        <span class="wds_tab_remove" title="<?php _e('Remove Slide', WDS()->prefix); ?>" onclick="wds_remove_slide('<?php echo $slide_row->id; ?>')"></span>
                        <input type="hidden" name="order<?php echo $slide_row->id; ?>" id="order<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->order; ?>" />
                        <span class="wds_clear"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
            <div class="wds_subtab_wrap new_tab_image">
              <div class="new_tab_link" onclick="wds_media_uploader_add_slide(event)" title="<?php _e('Add Slide(s)', WDS()->prefix); ?>"><p id="add_slide_text"><?php _e('Add Slide(s)', WDS()->prefix); ?></p></div>
            </div>
            <div class="wds_clear"></div>
          </div>
          <table>
            <?php
            echo $this->wds_textLayerTemplates( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families );
            echo $this->wds_imageLayerTemplates( $query_url, $spider_uploader, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles );
            echo $this->wds_videoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles );
            echo $this->wds_upvideoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles );
            echo $this->wds_hotspotLayerTemplate( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families, $hotp_text_positions );
            echo $this->wds_socialLayerTemplate( $social_buttons, $layer_effects_in, $layer_effects_out );
            ?>
          </table>
			<?php
			foreach ($slides_row as $key => $slide_row) {
				$type_video = false;
				if( $slide_row->type == 'video' || $slide_row->type == 'EMBED_OEMBED_YOUTUBE_VIDEO'|| $slide_row->type == 'EMBED_OEMBED_VIMEO_VIDEO' ) {
					$type_video = true;
				}
				$fillmode = 'fill';
				if ( !empty($row->bg_fit) ) {
					if ( $row->bg_fit == 'cover') {
						$fillmode = 'fill';
					}
					if ( $row->bg_fit == '100% 100%') {
						$fillmode = 'stretch';
					}
					if ( $row->bg_fit == 'contain') {
						$fillmode = 'fit';
					}
				}
				$slide_row->fillmode = empty($slide_row->fillmode) ? $fillmode : $slide_row->fillmode;
				?>
				<div class="wds_box <?php echo (((($id == 0 || !$sub_tab_type) || (strpos($sub_tab_type, 'pr') !== FALSE)) && $key == 0) || ('slide' . $slide_row->id == $sub_tab_type)) ? 'wds_sub_active' : ''; ?> wds_slide<?php echo $slide_row->id; ?>">
					<input type="hidden" name="type<?php echo $slide_row->id; ?>" id="type<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->type; ?>" />
					<input type="hidden" name="wds_video_type<?php echo $slide_row->id; ?>" id="wds_video_type<?php echo $slide_row->id; ?>" />
                    <table class="ui-sortable<?php echo $slide_row->id; ?>">
                      <tbody>
						<tr>
                          <td>
                            <div class="postbox closed">
                              <button class="button-link handlediv" type="button" aria-expanded="true">
                                <span class="screen-reader-text"><?php _e('Toggle panel:', WDS()->prefix); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                              </button>
                              <h2 class="hndle">
                                <span><?php _e('Slide options', WDS()->prefix); ?></span>
                              </h2>
                              <div class="inside">
                                <div class="wd-table">
                                  <div class="wd-table-col wd-table-col-50 wd-table-col-left">
                                    <div class="wd-box-section">
                                      <div class="wd-box-content">
																				<div class="wd-group">
																					<label class="wd-label" for="fillmode<?php echo $slide_row->id; ?>"><?php _e('Fillmode:', WDS()->prefix); ?></label>
																					<div id="wds_fillmode_option-<?php echo $slide_row->id; ?>" class="wds_fillmode_option">
																						<div style="width: 210px; position: relative;">
																							<div class="spider_choose_option" onclick="wds_choose_option(this)">
																							<div  class="spider_option_main_title"><?php echo !empty($slide_row->fillmode) ? $slide_row->fillmode : __('Fill', WDS()->prefix); ?></div>
																							<div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg"></i></div>
																							</div>
																							<div class="spider_options_cont <?php echo ($type_video) ? 'type_video' :'';?>">
																							<?php foreach ($slider_fillmode_option as $key_option => $option) { ?>
																							<div class="spider_option_cont <?php echo ((!empty($slide_row->fillmode) && $slide_row->fillmode == $key_option) ? 'selected' : ''); ?>" value="<?php echo $key_option;?>" onclick="wds_change_fillmode_type(this,<?php echo $slide_row->id; ?>)">
																								<div id="wds_fillmode_option_title-<?php echo $slide_row->id; ?>"class="spider_option_cont_title" data-title="<?php echo $key_option; ?>"><?php echo $option; ?></div>
																								<div id="wds_fillmode_option_img-<?php echo $slide_row->id; ?>" class="spider_option_cont_img">
																								<img src="<?php echo WDS()->plugin_url . '/images/fillmode/' . $key_option . '.png'; ?>" />
																								</div>
																							</div>
																							<?php }	?>
																							</div>
																						</div>
																					</div>
																					<div id="wds_fillmode_preview-<?php echo $slide_row->id; ?>" class="wds_fillmode_preview <?php echo ( empty($slide_row->fillmode) ? 'hide' :'' );?>">
																						<img src="<?php echo WDS()->plugin_url . '/images/fillmode/' . $slide_row->fillmode. '.png'; ?>">
																						<input type="hidden" name="fillmode<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->fillmode; ?>">
																					</div>
																					<div class="clear"></div>
																					<p class="description"><?php _e('Change the appearance of the slide background.', WDS()->prefix); ?></p>
																				</div>
                                        <div class="wd-group">
                                          <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                                          <input type="radio" id="published<?php echo $slide_row->id; ?>1" name="published<?php echo $slide_row->id; ?>" <?php echo (($slide_row->published) ? 'checked="checked"' : ''); ?> value="1" />
																					<label for="published<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
																					<input type="radio" id="published<?php echo $slide_row->id; ?>0" name="published<?php echo $slide_row->id; ?>" <?php echo (($slide_row->published) ? '' : 'checked="checked"'); ?> value="0" />
																					<label for="published<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"></p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="wd-table-col wd-table-col-50 wd-table-col-right">
                                    <div class="wd-box-section">
                                      <div class="wd-box-content">
                                        <div class="wd-group" id="controls<?php echo $slide_row->id; ?>" <?php echo $slide_row->type == 'video' ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label"><?php _e('Controls', WDS()->prefix); ?></label>
                                          <input type="radio" onClick="wds_enable_disable('', 'autoplay<?php echo $slide_row->id; ?>', 'controls<?php echo $slide_row->id; ?>1')"  id="controls<?php echo $slide_row->id; ?>1" name="controls<?php echo $slide_row->id; ?>" <?php echo (($slide_row->link == '1' || empty($slide_row->link) )  ? 'checked="checked"' : ''); ?> value="1" />
                                          <label for="controls<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
                                          <input type="radio" onClick="wds_enable_disable('none', 'autoplay<?php echo $slide_row->id; ?>', 'controls<?php echo $slide_row->id; ?>0')" id="controls<?php echo $slide_row->id; ?>0" name="controls<?php echo $slide_row->id; ?>" <?php echo (($slide_row->link == "0") ? 'checked="checked"' : '' ); ?> value="0" />
                                          <label for="controls<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"></p>
                                        </div>
                                        <div class="wd-group" id="autoplay<?php echo $slide_row->id; ?>" <?php echo (($slide_row->type == 'video' && $slide_row->link == '1') || $slide_row->type == 'EMBED_OEMBED_YOUTUBE_VIDEO'|| $slide_row->type == 'EMBED_OEMBED_VIMEO_VIDEO') ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label"><?php _e('Autoplay', WDS()->prefix); ?></label>
                                          <input type="radio" id="autoplay<?php echo $slide_row->id; ?>1" name="wds_slide_autoplay<?php echo $slide_row->id; ?>" <?php echo (($slide_row->target_attr_slide) ? 'checked="checked"' : ''); ?> value="1" />
                                          <label for="autoplay<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
                                          <input type="radio" id="autoplay<?php echo $slide_row->id; ?>0" name="wds_slide_autoplay<?php echo $slide_row->id; ?>" <?php echo (($slide_row->target_attr_slide) ? '' : 'checked="checked"'); ?> value="0" />
                                          <label for="autoplay<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"> <b><?php _e('Important! ', WDS()->prefix); ?></b><?php _e('Some browsers only support autoplay in case videos are muted. Therefore, Slider by 10Web will automatically mute them on these browsers to trigger video autoplay.', WDS()->prefix); ?></p>
                                        </div>
                                        <div class="wd-group" id="youtube_rel_video<?php echo $slide_row->id; ?>" <?php echo $slide_row->type == 'EMBED_OEMBED_YOUTUBE_VIDEO' ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label"><?php _e('Youtube related video', WDS()->prefix); ?></label>
                                          <input type="radio" id="youtube_rel_video<?php echo $slide_row->id; ?>1" name="youtube_rel_video<?php echo $slide_row->id; ?>" <?php echo (($slide_row->youtube_rel_video) ? 'checked="checked"' : ''); ?> value="1" />
                                          <label for="youtube_rel_video<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
                                          <input type="radio" id="youtube_rel_video<?php echo $slide_row->id; ?>0" name="youtube_rel_video<?php echo $slide_row->id; ?>" <?php echo (($slide_row->youtube_rel_video) ? '' : 'checked="checked"'); ?> value="0" />
                                          <label for="youtube_rel_video<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"></p>
                                        </div>
                                        <div class="wd-group" id="video_loop<?php echo $slide_row->id; ?>" <?php echo ($slide_row->type == 'video' || $slide_row->type == 'EMBED_OEMBED_VIMEO_VIDEO') ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label"><?php _e('Video Loop', WDS()->prefix); ?></label>
                                          <input type="radio" id="video_loop<?php echo $slide_row->id; ?>1" name="video_loop<?php echo $slide_row->id; ?>" <?php echo (($slide_row->video_loop) ? 'checked="checked"' : ''); ?> value="1" />
                                          <label for="video_loop<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
                                          <input type="radio" id="video_loop<?php echo $slide_row->id; ?>0" name="video_loop<?php echo $slide_row->id; ?>" <?php echo (($slide_row->video_loop) ? '' : 'checked="checked"'); ?> value="0" />
                                          <label for="video_loop<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"></p>
                                        </div>
                                        <div class="wd-group" id="mute<?php echo $slide_row->id; ?>" <?php echo ($slide_row->type == 'video' || $slide_row->type == 'EMBED_OEMBED_VIMEO_VIDEO') ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label"><?php _e('Mute', WDS()->prefix); ?></label>
                                          <input type="radio" id="mute<?php echo $slide_row->id; ?>1" name="mute<?php echo $slide_row->id; ?>" <?php echo (($slide_row->mute) ? 'checked="checked"' : ''); ?> value="1" />
                                          <label for="mute<?php echo $slide_row->id; ?>1"><?php _e('Yes', WDS()->prefix); ?></label>
                                          <input type="radio" id="mute<?php echo $slide_row->id; ?>0" name="mute<?php echo $slide_row->id; ?>" <?php echo (($slide_row->mute) ? '' : 'checked="checked"'); ?> value="0" />
                                          <label for="mute<?php echo $slide_row->id; ?>0"><?php _e('No', WDS()->prefix); ?></label>
                                          <p class="description"></p>
                                        </div>
                                        <div class="wd-group" id="trlink<?php echo $slide_row->id; ?>" <?php echo $slide_row->type == 'image' ? '' : 'style="display: none;"'; ?>>
                                          <label class="wd-label" for="link<?php echo $slide_row->id; ?>"><?php _e('Link the slide to:', WDS()->prefix); ?></label><input class="wds_external_link" id="link<?php echo $slide_row->id; ?>" type="text" value="<?php echo esc_attr($slide_row->link); ?>" name="link<?php echo $slide_row->id; ?>" /><input id="target_attr_slide<?php echo $slide_row->id; ?>" type="checkbox" name="target_attr_slide<?php echo $slide_row->id; ?>" <?php echo (($slide_row->target_attr_slide) ? 'checked="checked"' : ''); ?> value="1" /><label for="target_attr_slide<?php echo $slide_row->id; ?>"><?php _e('Open in a new window', WDS()->prefix); ?></label>
                                          <p class="description"><?php _e('You can add a URL, to which the users will be redirected upon clicking on the slide. Use http:// and https:// for external links.', WDS()->prefix); ?></p>
                                       </div>
									                     <div class="wd-group">
                                          <?php
                                          if ( !$spider_uploader ) {
                                            ?>
                                          <input type="button" class="button button-secondary" id="button_image_url<?php echo $slide_row->id; ?>" onclick="wds_media_uploader('<?php echo 'add_update_thumbnail__' . $slide_row->id; ?>', event, false); return false;" value="<?php _e('Edit Thumbnail', WDS()->prefix); ?>" />
                                            <?php
                                          }
                                          else {
                                            ?>
                                          <a class="button button-secondary thickbox thickbox-preview" href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'add_update_thumbnail', 'slide_id' => $slide_row->id, 'TB_iframe' => '1'), $query_url); ?>" title="<?php _e('Edit Thumbnail', WDS()->prefix); ?>" onclick="return false;">
                                           <?php _e('Edit Thumbnail', WDS()->prefix); ?>
                                          </a>
                                            <?php
                                          }
                                          ?>
                                          <p class="description"><?php _e('Note, that thumbnail will be used in the filmstrip only.', WDS()->prefix); ?></p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="bgcolor">
                          <td colspan="4">
                            <h2 class="titles wds_slide-title-<?php echo $slide_row->id; ?>"><?php echo $slide_row->title; ?></h2>
                            <div class="wds-preview-overflow">
                            <div id="wds_preview_wrapper_<?php echo $slide_row->id; ?>" class="wds_preview_wrapper" style="width: <?php echo $row->width; ?>px; height: <?php echo $row->height; ?>px;">
                              <div class="wds_preview">
                                <div id="wds_preview_image<?php echo $slide_row->id; ?>" class="wds_preview_image<?php echo $slide_row->id; ?> wds_preview_image"
                                     style='
											width: inherit;
                                            height: inherit;
											background-color: <?php echo WDW_S_Library::spider_hex2rgba($row->background_color, (100 - $row->background_transparent) / 100); ?>;
                                            background-image: url("<?php echo $slide_row->type != 'image' ? ($slide_row->type == 'video' && ctype_digit($slide_row->thumb_url) ? (wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($slide_row->thumb_url)) : WDS()->plugin_url . '/images/no-video.png') : $slide_row->thumb_url) : $slide_row->image_url . '?date=' . date('Y-m-d H:i:s'); ?>");
									   <?php
                     if ( !empty($slide_row->fillmode) && $slide_row->fillmode == 'fill' ) {
                       $bg_pos = array('center', 'center');
                       if ( $row->smart_crop ) {
                         $bg_pos = explode(" ", $row->crop_image_position);
                       }
									     ?>
											background-size: cover;
											background-position: <?php echo $bg_pos[0]; ?> <?php echo $bg_pos[1]; ?>;
											background-repeat: no-repeat;
										<?php } ?>
										<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'fit') { ?>
											background-size: contain;
											background-position: center center;
											background-repeat: no-repeat;
										<?php } ?>
										<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'stretch') { ?>
											background-size: 100% 100%;
											background-position: 100% 100%;
											background-repeat: no-repeat;
										<?php } ?>
										<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'center') { ?>
											background-size: unset;
											background-position: center center;
											background-repeat: no-repeat;
										<?php } ?>
										<?php if( !empty($slide_row->fillmode) && $slide_row->fillmode == 'tile') { ?>
											background-size: unset;
											background-position: unset;
											background-repeat: repeat;
										<?php } ?>'>
                                <?php
                                if ( !empty($layers_row[$slide_row->id]) ) {
                                  foreach ($layers_row[$slide_row->id] as $key => $layer) {
                                    $prefix = 'slide' . $slide_row->id . '_layer' . $layer->id;
                                    $fonts = (isset($layer->google_fonts) && $layer->google_fonts) ? $google_fonts : $font_families;
                                    $hotspot_text_display = (isset($layer->hotspot_text_display) && $layer->hotspot_text_display == 'click') ? 'click' : 'hover';
                                    switch ($layer->type) {
                                      case 'text': {
                                        ?>
                                        <span id="<?php echo $prefix; ?>" class="wds_draggable_<?php echo $slide_row->id; ?> wds_draggable ui-draggable" data-type="wds_text_parent" onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 1)"
                                              style="<?php echo $layer->image_width ? 'width: ' . $layer->image_width . '%; ' : ''; ?><?php echo $layer->image_height ? 'height: ' . $layer->image_height . '%; ' : ''; ?>word-break: <?php echo ($layer->image_scale ? 'break-all' : 'normal'); ?>; display: inline-block; position: absolute; left: <?php echo $layer->left; ?>px; top: <?php echo $layer->top; ?>px; z-index: <?php echo $layer->depth; ?>; color: #<?php echo $layer->color; ?>; font-size: <?php echo $layer->size; ?>px; line-height: 1.25em; font-family: <?php echo $fonts[$layer->ffamily]; ?>; font-weight: <?php echo $layer->fweight; ?>; padding: <?php echo $layer->padding; ?>; background-color: <?php echo WDW_S_Library::spider_hex2rgba($layer->fbgcolor, (100 - $layer->transparent) / 100); ?>; border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>; border-radius: <?php echo $layer->border_radius; ?>; box-shadow: <?php echo $layer->shadow; ?>; text-align: <?php echo $layer->text_alignment; ?>"><?php echo str_replace(array("\r\n", "\r", "\n"), "<br>", $layer->text); ?></span>
                                        <?php
                                        break;
                                      }
                                      case 'image': {
                                        ?>
                                        <img id="<?php echo $prefix; ?>" class="wds_draggable_<?php echo $slide_row->id; ?> wds_draggable ui-draggable" onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 1)" src="<?php echo $layer->image_url; ?>"
                                             style="opacity: <?php echo (100 - $layer->imgtransparent) / 100; ?>; filter: Alpha(opacity=<?php echo 100 - $layer->imgtransparent; ?>); position: absolute; left: <?php echo $layer->left; ?>px; top: <?php echo $layer->top; ?>px; z-index: <?php echo $layer->depth; ?>; border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>; border-radius: <?php echo $layer->border_radius; ?>; box-shadow: <?php echo $layer->shadow; ?>; " />
                                        <?php
                                        break;
                                      }
                                      case 'video':
                                      case 'upvideo': {
                                        ?>
                                        <img id="<?php echo $prefix; ?>" class="wds_draggable_<?php echo $slide_row->id; ?> wds_draggable ui-draggable" onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 1)" src="<?php echo $layer->type == 'upvideo' ? (wp_get_attachment_url(get_post_thumbnail_id($layer->image_url)) ? wp_get_attachment_url(get_post_thumbnail_id($layer->image_url)) : WDS()->plugin_url . '/images/no-video.png') : $layer->image_url ?>"
                                             style="max-width: <?php echo $layer->image_width; ?>px; width: <?php echo $layer->image_width; ?>px; max-height: <?php echo $layer->image_height; ?>px; height: <?php echo $layer->image_height; ?>px; position: absolute; left: <?php echo $layer->left; ?>px; top: <?php echo $layer->top; ?>px; z-index: <?php echo $layer->depth; ?>; border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>; border-radius: <?php echo $layer->border_radius; ?>; box-shadow: <?php echo $layer->shadow; ?>;" />
                                        <?php
                                        break;
                                      }
                                      case 'social': {
                                        ?>
                                        <i id="<?php echo $prefix; ?>" class="wds_draggable_<?php echo $slide_row->id; ?> wds_draggable fa fa-<?php echo $layer->social_button; ?> ui-draggable" onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 1)"
                                           style="opacity: <?php echo (100 - $layer->transparent) / 100; ?>; filter: Alpha(opacity=<?php echo 100 - $layer->transparent; ?>); position: absolute; left: <?php echo $layer->left; ?>px; top: <?php echo $layer->top; ?>px; z-index: <?php echo $layer->depth; ?>; color: #<?php echo $layer->color; ?>; font-size: <?php echo $layer->size; ?>px; line-height: <?php echo $layer->size; ?>px; padding: <?php echo $layer->padding; ?>; "></i>
                                        <?php
                                        break;
                                      }
                                      case 'hotspots': {
                                        ?>
                                        <span id="<?php echo $prefix; ?>_div"
                                             data-text-position="<?php echo $layer->hotp_text_position; ?>"
                                             class="hotspot_container wds_draggable_<?php echo $slide_row->id; ?> wds_draggable ui-draggable"
                                             onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 1)"
                                             style="width: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                    height: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                    z-index: <?php echo $layer->depth; ?>;
                                                    position: absolute;
                                                    left: <?php echo $layer->left ? $layer->left : 20; ?>px;
                                                    top: <?php echo $layer->top ? $layer->top : 20; ?>px;
                                                    display: inline-block;">
                                          <span class="wds_layer_<?php echo $layer->id; ?> wds_layer"
                                                data-displaytype="<?php echo $hotspot_text_display; ?>"
                                                id="<?php echo $prefix; ?>_round"
                                                style="top: 0;
                                                       left: 0;
                                                       width: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                       height: <?php echo $layer->hotp_width ? $layer->hotp_width : 20; ?>px;
                                                       border-radius: <?php echo $layer->hotp_border_radius ? $layer->hotp_border_radius : '20px'; ?>;
                                                       border: <?php echo $layer->hotp_border_width; ?>px <?php echo $layer->hotp_border_style; ?> #<?php echo $layer->hotp_border_color; ?>;
                                                       background-color: #<?php echo $layer->hotp_fbgcolor ? $layer->hotp_fbgcolor : "ffffff"; ?>;
                                                       z-index: <?php echo $layer->depth; ?>;
                                                       position: absolute;
                                                       display: block;
                                                       opacity: 1 !important;">
                                          </span>
                                          <span class="wds_layer_<?php echo $layer->id; ?>"
                                                id="<?php echo $prefix; ?>_round_effect"
                                                wds_fsize="<?php echo $layer->size; ?>"
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
                                                       animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite ;
                                                       -moz-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                       -webkit-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                       -o-animation: point-anim 1.5s ease <?php echo mt_rand(0, 300) / 100; ?>s infinite;
                                                       <?php } ?>">
                                          </span>
                                          <span id="<?php echo $prefix; ?>"
                                          data-type="hotspot_text"
                                                class="wds_hotspot_text"
                                                style="<?php echo $layer->image_width ? 'width: ' . $layer->image_width . 'px; ' : 'white-space: nowrap;'; ?>
                                                       <?php echo $layer->image_height ? 'height: ' . $layer->image_height . 'px; ' : ''; ?>
                                                       position: absolute;
                                                       word-break: <?php echo ($layer->image_scale ?'break-all':'normal'); ?>;
                                                       display: none;
                                                       z-index: <?php echo $layer->depth; ?>;
                                                       color: #<?php echo $layer->color; ?>;
                                                       font-size: <?php echo $layer->size; ?>px;
                                                       line-height: 1.25em;
                                                       font-family: <?php echo $fonts[$layer->ffamily]; ?>;
                                                       font-weight: <?php echo $layer->fweight; ?>;
                                                       padding: <?php echo $layer->padding; ?>;
                                                       background-color: <?php echo WDW_S_Library::spider_hex2rgba($layer->fbgcolor, (100 - $layer->transparent) / 100); ?>;
                                                       border: <?php echo $layer->border_width; ?>px <?php echo $layer->border_style; ?> #<?php echo $layer->border_color; ?>;
                                                       border-radius: <?php echo $layer->border_radius; ?>; box-shadow: <?php echo $layer->shadow; ?>; text-align: <?php echo $layer->text_alignment; ?>">
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
                                ?>
                                </div>
                              </div>
                            </div>
                            </div>
                            <input type="hidden" id="image_url<?php echo $slide_row->id; ?>" name="image_url<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->image_url; ?>" />
                            <input type="hidden" id="thumb_url<?php echo $slide_row->id; ?>" name="thumb_url<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->thumb_url; ?>" />
                            <input type="hidden" id="post_id<?php echo $slide_row->id; ?>" name="post_id<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->thumb_url; ?>" />
                            <input type="hidden" id="video_duration<?php echo $slide_row->id; ?>" name="video_duration<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->video_duration; ?>" />
                            <input type="hidden" id="att_width<?php echo $slide_row->id; ?>" name="att_width<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->att_width; ?>" />
                            <input type="hidden" id="att_height<?php echo $slide_row->id; ?>" name="att_height<?php echo $slide_row->id; ?>" value="<?php echo $slide_row->att_height; ?>" />
                          </td>
                        </tr>
                        <tr class="bgcolor">
                          <td colspan="4">
                            <h2 class="titles"><?php _e('Layers', WDS()->prefix); ?></h2>
                            <div id="layer_add_buttons">
                              <div class="layer_add_buttons_wrap">
                                <button class="action_buttons add_text_layer button-small<?php echo !$fv ? "" : " wds_free_button"; ?>"  onclick="<?php echo $fv ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_layer('text', '" . $slide_row->id . "')"; ?>; return false;"><?php _e('Add Text Layer', WDS()->prefix); ?></button>
                              </div>
                              <?php
                              if (!$spider_uploader) {
                                ?>
                                <div class="layer_add_buttons_wrap">
                                  <button class="action_buttons add_image_layer button-small<?php echo !WDS()->is_free ? "" : " wds_free_button"; ?>" onclick="<?php echo WDS()->is_free ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_layer('image', '" . $slide_row->id . "', '')"; ?>; return false;"><?php _e('Add Image Layer', WDS()->prefix); ?></button>
                                </div>
                                <?php
                              }
                              else {
                                ?>
                                <div class="layer_add_buttons_wrap">
                                  <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'add_layer', 'slide_id' => $slide_row->id, 'TB_iframe' => '1'), $query_url); ?>" class="action_buttons add_image_layer button-small thickbox thickbox-preview<?php echo !$fv ? "" : " wds_free_button"; ?>"  title="<?php _e('Add Image layer', WDS()->prefix); ?>" onclick="return false;">
                                    <?php _e('Add Image layer', WDS()->prefix); ?>
                                  </a>
                                </div>
                                <?php
                              }
                              ?>
                              <div class="layer_add_buttons_wrap">
                                <input type="button" class="action_buttons add_video_layer button-small<?php echo !WDS()->is_free ? "" : " wds_free_button"; ?>" id="button_video_url<?php echo $slide_row->id; ?>"  onclick="<?php echo WDS()->is_free ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_layer('upvideo', '" . $slide_row->id . "')"; ?>; return false;" value="<?php _e('Add Video Layer', WDS()->prefix); ?>" />
                              </div>
                              <div class="layer_add_buttons_wrap">
                                <input type="button" class="action_buttons add_embed_layer  button-small<?php echo !WDS()->is_free ? "" : " wds_free_button"; ?>"  onclick="<?php echo WDS()->is_free ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_video('" . $slide_row->id . "', 'video_layer')"; ?>" value="<?php _e('Embed Media Layer', WDS()->prefix); ?>" />
                              </div>
                              <div class="layer_add_buttons_wrap">
                                <button class="action_buttons add_social_layer button-small<?php echo !WDS()->is_free ? "" : " wds_free_button"; ?>" onclick="<?php echo WDS()->is_free ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_layer('social', '" . $slide_row->id . "')"; ?>; return false;"><?php _e('Social Button Layer', WDS()->prefix); ?></button>
                              </div>
                              <div class="layer_add_buttons_wrap">
                                <button class="action_buttons add_hotspot_layer button-small<?php echo !WDS()->is_free ? "" : " wds_free_button"; ?>" onclick="<?php echo WDS()->is_free ? "alert('". addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) ."')" : "wds_add_layer('hotspots', '" . $slide_row->id . "')"; ?>; return false;" ><?php _e('Add Hotspot Layer', WDS()->prefix); ?></button>
                              </div>
                              <div class="clear"></div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <?php
                      $layer_ids_string = '';
					  if ( !empty($layers_row[$slide_row->id]) ) {
                        foreach ($layers_row[$slide_row->id] as $key => $layer) {
                          $prefix = 'slide' . $slide_row->id . '_layer' . $layer->id;
                          ?>
                          <tbody class="layer_table_count" id="<?php echo $prefix; ?>_tbody">
                            <tr class="wds_layer_head_tr">
                              <td class="wds_layer_head" colspan="4">
								<div class="wds_layer_left">
									<div class="layer_handle handle connectedSortable" title="Drag to re-order"></div>
									<span class="wds_layer_label" onclick="wds_showhide_layer('<?php echo $prefix; ?>_tbody', 0)"><input id="<?php echo $prefix; ?>_title" name="<?php echo $prefix; ?>_title" type="text" class="wds_layer_title"   value="<?php echo $layer->title; ?>" title="<?php _e('Layer title', WDS()->prefix); ?>" /></span>
								</div>
								<div class="wds_layer_right">
									<span class="wds_layer_remove" onclick="wds_delete_layer('<?php echo $slide_row->id; ?>', '<?php echo $layer->id; ?>'); " title="Delete layer"></span>
									<span class="wds_layer_dublicate" onclick="wds_add_layer('<?php echo $layer->type; ?>', '<?php echo $slide_row->id; ?>', '', 1, 0); wds_duplicate_layer('<?php echo $layer->type; ?>', '<?php echo $slide_row->id; ?>', '<?php echo $layer->id; ?>'); " title="<?php _e('Duplicate layer', WDS()->prefix); ?>"></span>
									<input id="<?php echo $prefix; ?>_depth" class="wds_layer_depth spider_int_input" type="text" onchange="change_zindex(this,'<?php echo $prefix; ?>'); " onkeypress="return spider_check_isnum(event); " value="<?php echo $layer->depth; ?>" prefix="<?php echo $prefix; ?>" name="<?php echo $prefix; ?>_depth" title="z-index" />
								</div>
								<div class="wds_clear"></div>
                              </td>
                            </tr>
                            <?php
                            switch ($layer->type) {
                              /*--------text layer----------*/
                              case 'text': {
                                echo $this->wds_textLayerTemplates( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              /*--------image layer----------*/
                              case 'image': {
                                echo $this->wds_imageLayerTemplates( $query_url, $spider_uploader, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              /*--------video layer----------*/
                              case 'video': {
                                echo $this->wds_videoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              case 'upvideo': {
                                echo $this->wds_upvideoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              /*--------social button layer----------*/
                              case 'social': {
                                echo $this->wds_socialLayerTemplate( $social_buttons, $layer_effects_in, $layer_effects_out, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              /*--------Hotspot layer----------*/
                              case 'hotspots': {
                                echo $this->wds_hotspotLayerTemplate( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families, $hotp_text_positions, $slide_row->id, $prefix, $layer );
                                break;
                              }
                              default:
                                break;
                            }
                            ?>
                          </tbody>
                          <?php
                          $layer_ids_string .= $layer->id . ',';
                        }
                      }
                      ?>
                    </table>
                    <input id="slide<?php echo $slide_row->id; ?>_layer_ids_string" name="slide<?php echo $slide_row->id; ?>_layer_ids_string" type="hidden" value="<?php echo $layer_ids_string; ?>" />
                    <input id="slide<?php echo $slide_row->id; ?>_del_layer_ids_string" name="slide<?php echo $slide_row->id; ?>_del_layer_ids_string" type="hidden" value="" />
                  </div>
                    <script>
                      jQuery(document).ready(function() {
                        image_for_next_prev_butt('<?php echo $row->rl_butt_img_or_not; ?>');
                        image_for_bull_butt('<?php echo $row->bull_butt_img_or_not; ?>');
                        image_for_play_pause_butt('<?php echo $row->play_paus_butt_img_or_not; ?>');
                        showhide_for_carousel_fildes('<?php echo $row->carousel; ?>');
                        wds_whr('width');
                        wds_drag_layer('<?php echo $slide_row->id; ?>');
                        wds_layer_weights('<?php echo $slide_row->id; ?>');
                        <?php
                         if ( !empty($layers_row[$slide_row->id]) ) {
                          foreach ($layers_row[$slide_row->id] as $key => $layer) {
                            if ($layer->type == 'image') {
                              $prefix = 'slide' . $slide_row->id . '_layer' . $layer->id;
                              ?>
                                wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>');
                              <?php
                            }
                            if ($layer->type == 'text') {
                              $prefix = 'slide' . $slide_row->id . '_layer' . $layer->id;
                              ?>
                                jQuery('#<?php echo $prefix; ?>').hover(
                                  function() {
                                    jQuery(this).css({
                                      color: '#' + jQuery('#<?php echo $prefix; ?>_hover_color_text').val()
                                    });
                                  },
                                  function() {
                                    jQuery(this).css({
                                      color: '#' + jQuery('#<?php echo $prefix; ?>_color').val()
                                    });
                                  }
                                );
                              <?php
                            }
                            if ($layer->type == 'social') {
                              $prefix = 'slide' . $slide_row->id . '_layer' . $layer->id;
                              ?>
                                jQuery('#<?php echo $prefix; ?>').hover(
                                  function() {
                                    jQuery(this).css({
                                      color: '#' + jQuery('#<?php echo $prefix; ?>_hover_color').val()
                                    });
                                  },
                                  function() {
                                    jQuery(this).css({
                                      color: '#' + jQuery('#<?php echo $prefix; ?>_color').val()
                                    });
                                  }
                                );
                              <?php
                            }
                          }
                        }
                        ?>
                      });
                    </script>
                    <?php
                    $slide_ids_string .= $slide_row->id . ',';
			}
			?>
			<script>
			jQuery(document).ready(function() {
				wds_slider_fillmode_option = <?php echo json_encode($slider_fillmode_option); ?>
			});
			</script>
        </div>
        <div class="wds_box wds_howto_box meta-box-sortables">
            <div class="clear"></div>
            <div class="bgcolor wds_tabs aui-sortable">
              <h2 class="titles wd-slides-title"><?php _e('How to use', WDS()->prefix); ?></h2>
              <div class="wds_howto_container">
                <div class="wds_howto_content">
                  <h2><?php _e('Shortcode', WDS()->prefix); ?></h2>
                  <h4><?php _e('Copy and paste this shortcode into your posts or pages:', WDS()->prefix); ?></h4>
                  <input type="text" class="wds_howto_shortcode" value='[wds id="<?php echo $row->id; ?>"]' onclick="spider_select_value(this)" size="11" readonly="readonly" />
                </div>
                <div class="wds_howto_content">
                  <h2><?php _e('Page or Post editor', WDS()->prefix); ?></h2>
                  <h4><?php _e('Insert it into an existing post with the button:', WDS()->prefix); ?></h4>
                  <img src="<?php echo WDS()->plugin_url . '/images/sliderwdpng/wp-publish.png'; ?>" alt="<?php _e('Post editor', WDS()->prefix); ?>" />
                </div>
                <div class="wds_howto_content">
                  <h2><?php _e('PHP code', WDS()->prefix); ?></h2>
                  <h4><?php _e('Copy and paste the PHP code into your template file:', WDS()->prefix); ?></h4>
                  <input type="text" class="wds_howto_phpcode" value="&#60;?php wd_slider(<?php echo $row->id; ?>); ?&#62;" onclick="spider_select_value(this)" size="17" readonly="readonly" />
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="wds_task_cont">
	    	<input id="task" name="task" type="hidden" value="" />
        <input id="current_id" name="current_id" type="hidden" value="<?php echo $row->id; ?>" />
        <input id="save_as_copy" name="save_as_copy" type="hidden" value="" />
        <input id="slide_ids_string" name="slide_ids_string" type="hidden" value="<?php echo $slide_ids_string; ?>" />
        <input id="del_slide_ids_string" name="del_slide_ids_string" type="hidden" value="" />
        <input id="nav_tab" name="nav_tab" type="hidden" value="<?php echo WDW_S_Library::get('nav_tab', 'global'); ?>" />
        <input id="tab" name="tab" type="hidden" value="<?php echo WDW_S_Library::get('tab', 'slides'); ?>" />
        <input id="sub_tab" name="sub_tab" type="hidden" value="<?php echo $sub_tab_type; ?>" />
        <script>
          var spider_uploader_ = <?php echo $spider_uploader; ?>;
        </script>
      </div>
      <script>
        var fv = '<?php echo $fv; ?>';
        var ajax_url = "<?php echo wp_nonce_url(admin_url('admin-ajax.php'), '', 'nonce_wd'); ?>";
        var uploader_href = '<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'add_update_slide', 'slide_id' => 'slideID', 'layer_id' => 'layerID', 'TB_iframe' => '1'), $query_url); ?>';
        var WD_S_URL = '<?php echo WDS()->plugin_url; ?>';
        jQuery(document).ready(function() {
          wds_onload();
        });
        jQuery("#sliders_form").on("click", "a", function(e) {
          e.preventDefault();
        });
        jQuery(".wds_tab_title").keyup(function (e) {
          var code = e.which;
          if (code == 13) {
            jQuery(".wds_sub_active .wds_tab_title").blur();
            jQuery(".wds_tab_title_wrap").removeClass("wds_sub_active");
          }
        });
        var plugin_dir = '<?php echo WDS()->plugin_url . "/images/sliderwdpng/"; ?>';
      </script>
      <div class="opacity_add_video wds_opacity_video wds_opacity_export opacity_wp_editor"
           onclick="jQuery('.opacity_add_video').hide();
                    jQuery('.opacity_add_image_url').hide();
                    jQuery('.wds_exports').hide();
                    jQuery('.wds_editor').hide();">
      </div>
      <div class="wds_exports">
        <input type="checkbox" name="imagesexport" id="imagesexport" value="<?php _e('Images export', WDS()->prefix); ?>" checked="checked" />
        <label for="imagesexport"><?php _e('Check the box to export the images included within sliders', WDS()->prefix); ?></label>
        <input class="button-secondary" type="button" id="wds_export_btn" data-href="<?php echo add_query_arg(array('action' => 'WDSExport'), admin_url('admin-ajax.php')); ?>" onclick="spider_set_input_value('task', 'export_on'); wds_spider_ajax_save('sliders_form', event); jQuery('.wds_exports').hide(); jQuery('.wds_opacity_export').hide();" value="<?php _e('Export', WDS()->prefix); ?>" />
        <input type="button"  class="button-secondary" onclick="jQuery('.wds_exports').hide(); jQuery('.wds_opacity_export').hide(); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
      </div>
      <div id="add_embed" class="opacity_add_video wds_add_video">
        <input type="text" id="embed_url" name="embed_url" value="" />
        <input class="button button-primary" type="button" onclick="if (wds_get_embed_info(jQuery('#embed_url').val())) {jQuery('.opacity_add_video').hide();} jQuery('#embed_url').val(''); return false;" value="<?php _e('Add', WDS()->prefix); ?>" />
        <input class="button" type="button" onclick="jQuery('.opacity_add_video').hide(); jQuery('#embed_url').val(''); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
        <div class="spider_description">
          <?php _e('Enter YouTube, Vimeo, Instagram, Flickr or Dailymotion URL here.', WDS()->prefix); ?>
        </div>
        <div class="spider_description">
          <p><?php _e('<b>Youtube</b> URL example:', WDS()->prefix); ?> <i>https://www.youtube.com/watch?v=xebpM_-GwG0</i></p>
          <p><?php _e('<b>Vimeo</b> URL example:', WDS()->prefix); ?> <i>https://vimeo.com/69726973</i></p>
          <p><?php _e('<b>Instagram</b> URL example:', WDS()->prefix); ?> <i>https://instagram.com/p/4o65J9QNDm</i>.<br /><?php _e('Add', WDS()->prefix); ?> "<i style="text-decoration:underline;"><?php _e('post', WDS()->prefix); ?></i>" <?php _e('to the end of URL if you want to embed the whole Instagram post, not only its content.', WDS()->prefix); ?></p>
          <p><?php _e('<b>Flickr</b> URL example:', WDS()->prefix); ?> <i>https://www.flickr.com/photos/powerpig/18780957662/in/photostream/</i></p>
          <p><?php _e('<b>Dailymotion</b> URL example:', WDS()->prefix); ?> <i>http://www.dailymotion.com/video/x2w0jzl_cortoons-tv-tropty-episodio-2_fun</i></p>
        </div>
      </div>
      <div class="wds_editor">
        <div class="wds_editor_insert_btn">
          <input type="button" class="button button-primary" onclick="wds_insert_html()" value="<?php _e('Insert', WDS()->prefix); ?>" />
          <input type="button" class="button button-secondary" onclick="jQuery('.opacity_wp_editor').hide();jQuery('.wds_editor').hide(); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
        </div>
        <?php
        wp_editor('', 'template_text', array('teeny' => TRUE, 'textarea_name' => 'template_text', 'media_buttons' => FALSE, 'textarea_rows' => 5,'quicktags' => FALSE));
        ?>
        <input type="hidden" id="current_prefix" value="" />
      </div>
    </form>
    <?php
  }

  /**
   * Image layer template
   */
  function wds_imageLayerTemplates( $query_url, $spider_uploader, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $id=false, $prefix=false, $layer=false ) {
    $default_global_options = $this->default_global_options;
	$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer == "" ) {
      $new_layer = array(
          'static_layer'        => '0',
          'id'                  => 'pr_%%LayerId%%',
          'image_url'           => '',
          'image_width'         =>  '',
          'image_height'        => '',
          'image_scale'         => 'slide' . $id . '_layerpr_%%LayerId%%_image_scale',
          'alt'                 => '',
          'link'                => '',
          'target_attr_layer'   => 0,
          'left'                => '0',
          'top'                 => '0',
          'layer_callback_list' => $layer_callbacks,
          'link_to_slide'       => '',
          'imgtransparent'      => 0,
          'published'           => 1,
	   			'hide_on_mobile' => 0,
		  		'start' 				=> $default_global_options->default_layer_start,
          'layer_effect_in' 	=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 	=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 		=> $default_global_options->default_layer_infinite_in,
          'end' 				=> $default_global_options->default_layer_end,
          'layer_effect_out' 	=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 	=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 		=> $default_global_options->default_layer_infinite_out,
          'border_width'        => 2,
          'border_style'        => $border_styles,
          'border_color'        => 'FFFFFF',
          'border_radius'       => '2px',
          'shadow'              => '',
          'add_class'           => '',
      );
    }
	else {
      $new_layer = array(
          'static_layer'        => $layer->static_layer,
          'id'                  => $layer->id,
          'image_url'           => $layer->image_url,
          'image_width'         => $layer->image_width,
          'image_height'        => $layer->image_height,
          'image_scale'         => $layer->image_scale,
          'alt'                 => $layer->alt,
          'link'                => $layer->link,
          'target_attr_layer'   => $layer->target_attr_layer,
          'left'                => $layer->left,
          'top'                 => $layer->top,
          'layer_callback_list' => $layer->layer_callback_list,
          'link_to_slide'       => $layer->link_to_slide,
          'imgtransparent'      => $layer->imgtransparent,
          'published'           => $layer->published,
	   			'hide_on_mobile'			=> $layer->hide_on_mobile,
          'start'               => $layer->start,
          'layer_effect_in'     => $layer->layer_effect_in,
          'duration_eff_in'     => $layer->duration_eff_in,
          'infinite_in'         => $layer->infinite_in,
          'end'                 => $layer->end,
          'layer_effect_out'    => $layer->layer_effect_out,
          'duration_eff_out'    => $layer->duration_eff_out,
          'infinite_out'        => $layer->infinite_out,
          'border_width'        => $layer->border_width,
          'border_style'        => $layer->border_style,
          'border_color'        => $layer->border_color,
          'border_radius'       => $layer->border_radius,
          'shadow'              => $layer->shadow,
          'add_class'           => $layer->add_class,
      );

    }
    ob_start();
    ?>
    <tr style="display:none" class="wds_layer_tr wds_imageLayer wds_layer_content">
      <td colspan="3">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?>/>
                  <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                  <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Dimensions:', WDS()->prefix); ?></label>
                  <input type="hidden" name="<?php echo $prefix; ?>_image_url" id="<?php echo $prefix; ?>_image_url" value="<?php echo $new_layer['image_url']; ?>" />
                  <input id="<?php echo $prefix; ?>_image_width" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_width']; ?>" name="<?php echo $prefix; ?>_image_width" /> x
                  <input id="<?php echo $prefix; ?>_image_height" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_height']; ?>" name="<?php echo $prefix; ?>_image_height" /> px
                  <input id="<?php echo $prefix; ?>_image_scale" type="checkbox" onchange="wds_scale(this, '<?php echo $prefix; ?>')" name="<?php echo $prefix; ?>_image_scale" <?php echo (($new_layer['image_scale']) ? 'checked="checked"' : ''); ?> /><label for="<?php echo $prefix; ?>_image_scale"><?php _e('Scale', WDS()->prefix); ?></label>
                  <?php
                  if ( !$spider_uploader ) {
                    ?>
                    <input type="button" class="button button-secondary" id="button_image_url<?php echo $id; ?>" onclick="wds_add_layer('image', '<?php echo $id; ?>', '<?php echo $new_layer['id']; ?>', '', '', 1); return false;" value="<?php _e('Edit Image', WDS()->prefix); ?>" />
                    <?php
                  } else {
                    ?>
                    <a href="<?php echo add_query_arg(array('callback' => 'wds_add_image', 'image_for' => 'add_update_layer', 'slide_id' => $id, 'layer_id' => $new_layer['id'], 'TB_iframe' => '1'), $query_url); ?>" class="button button-secondary thickbox thickbox-preview" title="<?php _e('Edit Image', WDS()->prefix); ?>" onclick="return false;">
                      <?php _e('Edit Image', WDS()->prefix); ?>
                    </a>
                    <?php
                  }
                  ?>
                  <p class="description"><?php _e('Set width and height of the image.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_alt" title=""><?php _e('Alt:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_alt" type="text"  class="wds_link" value="<?php echo $new_layer['alt']; ?>" name="<?php echo $prefix; ?>_alt" />
                  <p class="description"><?php _e('Set the value of alt HTML attribute for this image layer.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_link" title=""><?php _e('Link:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_link" class="wds_link" type="text"  value="<?php echo $new_layer['link']; ?>" name="<?php echo $prefix; ?>_link" />
                  <input id="<?php echo $prefix; ?>_target_attr_layer" type="checkbox"  name="<?php echo $prefix; ?>_target_attr_layer" <?php echo (($new_layer['target_attr_layer']) ? 'checked="checked"' : ''); ?> value="1" /><label for="<?php echo $prefix; ?>_target_attr_layer"><?php _e('Open in a new window', WDS()->prefix); ?></label>
                  <p class="description"><?php _e('Use http:// and https:// for external links.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Position:', WDS()->prefix); ?></label>
                  X <input id="<?php echo $prefix; ?>_left" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                  Y <input id="<?php echo $prefix; ?>_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                  <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_imgtransparent" title=""><?php _e('Transparency:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_imgtransparent" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({opacity: (100 - jQuery(this).val()) / 100, filter: 'Alpha(opacity=' + 100 - jQuery(this).val() + ')'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['imgtransparent']; ?>" name="<?php echo $prefix; ?>_imgtransparent"> %
                  <p class="description"><?php _e('Value must be between 0 and 100.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_callback_list"><?php _e('Add click action:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width: 120px;" id="<?php echo $prefix; ?>_layer_callback_list" name="<?php echo $prefix; ?>_layer_callback_list" onchange="wds_show_slides_name('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_callbacks as $key => $layer_callback_list) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['layer_callback_list'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $layer_callback_list; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_link_to_slide" class="link_to_slide" style="<?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>"><?php _e('Slides Name:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width: 120px;  <?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>" id="<?php echo $prefix; ?>_link_to_slide" name="<?php echo $prefix; ?>_link_to_slide">
                    <?php
                    foreach ($slides_name as $key => $slide_name) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['link_to_slide'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $slide_name; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;"  onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_in as $key => $layer_effect_in) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" /> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="<?php _e('0 for play infinte times', WDS()->prefix); ?>" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320 wds_link" name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out"> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_border_width" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderWidth: jQuery(this).val()})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['border_width']; ?>" name="<?php echo $prefix; ?>_border_width"> px
                  <select class="select_icon"  id="<?php echo $prefix; ?>_border_style" onchange="jQuery('#<?php echo $prefix; ?>').css({borderStyle: jQuery(this).val()})" style="width: 80px !important;" name="<?php echo $prefix; ?>_border_style">
                    <?php
                    foreach ($border_styles as $key => $border_style) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_border_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['border_color']; ?>" name="<?php echo $prefix; ?>_border_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_radius" title=""><?php _e('Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['border_radius']; ?>" name="<?php echo $prefix; ?>_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_shadow"><?php _e('Shadow:', WDS()->prefix); ?></label>
                  <input placeholder="10px 10px 5px #888888" id="<?php echo $prefix; ?>_shadow" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({boxShadow: jQuery(this).val()})" value="<?php echo $new_layer['shadow']; ?>" name="<?php echo $prefix; ?>_shadow" />
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class"><?php _e('Add class:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix); ?></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="image">
    </tr>
    <?php
    return ob_get_clean();
  }

  /**
   * Text layer template
   */
  function wds_textLayerTemplates( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families, $id=false, $prefix=false, $layer=false ) {
    $default_global_options = $this->default_global_options;
		$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer === FALSE ) {
      $new_layer = array(
          'text' 				=> 'Sample Text',
          'static_layer' 		=> '0',
          'id' 					=> $id,
          'image_width' 		=>  '',
          'image_height' 		=> '',
          'image_scale' 		=> '1',
          'size' 				=> '18',
          'min_size' 			=> '11',
          'color'               => 'FFFFFF',
          'hover_color_text'    => 'FFFFFF',
          'align'               => '0',
          'left'                => '0',
          'top'                 => '0',
          //'text_position'       => '0',
          'google_fonts'        => $default_global_options->default_layer_google_fonts,
          'ffamily'             => $font_families,
          'fweight'             => $default_global_options->default_layer_fweight,
          'link'                => '',
          'target_attr_layer'   => 0,
          'layer_callback_list' => $layer_callbacks,
          'published'           => 1,
		  		'hide_on_mobile' => 0,
		  		'start' 				=> $default_global_options->default_layer_start,
          'layer_effect_in' 	=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 	=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 		=> $default_global_options->default_layer_infinite_in,
          'end' 				=> $default_global_options->default_layer_end,
          'layer_effect_out' 	=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 	=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 		=> $default_global_options->default_layer_infinite_out,
          'padding' 			=> '5px',
          'fbgcolor' 			=> '000000',
          'transparent' 		=> 50,
          'border_width' 		=> 2,
          'border_style' 		=> $border_styles,
          'border_radius' 		=> '2px',
          'border_color' 		=> 'BBBBBB',
          'shadow' 				=> '',
          'add_class' 			=> '',
          'text_alignment' 		=> 'left',
          'link_to_slide' 		=> '',
      );
    }
	else {
      $new_layer = array(
          'text' 				=> $layer->text,
          'static_layer' 		=> $layer->static_layer,
          'id' 					=> $layer->id,
          'image_width' 		=> $layer->image_width,
          'image_height' 		=> $layer->image_height,
          'image_scale' 		=> $layer->image_scale,
          'size' 				=> $layer->size,
          'min_size' 			=> $layer->min_size,
          'color' 				=> $layer->color,
          'hover_color_text' 	=> $layer->hover_color_text,
          'align' 				=> $layer->align_layer,
          'left' 				=> $layer->left,
          'top' 				=> $layer->top,
          //'text_position' 		=> $layer->text_position,
          'google_fonts' 		=> $layer->google_fonts,
          'ffamily' 			=> $layer->ffamily,
          'fweight' 			=> $layer->fweight,
          'link' 				=> $layer->link,
          'target_attr_layer' 	=> $layer->target_attr_layer,
          'layer_callback_list' => $layer->layer_callback_list,
          'published' 			=> $layer->published,
          'hide_on_mobile' => $layer->hide_on_mobile,
          'start' 				=> $layer->start,
          'layer_effect_in' 	=> $layer->layer_effect_in,
          'duration_eff_in' 	=> $layer->duration_eff_in,
          'infinite_in' 		=> $layer->infinite_in,
          'end' 				=> $layer->end,
          'layer_effect_out' 	=> $layer->layer_effect_out,
          'duration_eff_out' 	=> $layer->duration_eff_out,
          'infinite_out' 		=> $layer->infinite_out,
          'padding' 			=> $layer->padding,
          'fbgcolor' 			=> $layer->fbgcolor,
          'transparent' 		=> $layer->transparent,
          'border_width' 		=> $layer->border_width,
          'border_style' 		=> $layer->border_style,
          'border_radius' 		=> $layer->border_radius,
          'border_color' 		=> $layer->border_color,
          'shadow' 				=> $layer->shadow,
          'add_class' 			=> $layer->add_class,
          'text_alignment' 		=> $layer->text_alignment,
          'link_to_slide' 		=> $layer->link_to_slide,

      );
    }
    ob_start();
    ?>
    <tr style="display:none" class="wds_layer_content wds_textLayer">
      <td colspan="2">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
              <span class="wd-group">
                <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?> />
                <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix); ?></label>
                <p class="description"></p>
              </span>

              <span class="wd-group">
                <label class="wd-label"  for="<?php echo $prefix; ?>_text"><?php _e('Text:', WDS()->prefix); ?></label>
                <textarea id="<?php echo $prefix; ?>_text" class='wds_textarea' name="<?php echo $prefix; ?>_text" onkeyup="wds_new_line('<?php echo $prefix; ?>');"><?php echo $new_layer['text']; ?></textarea>
                <input type="button" class="wds_editor_btn button button-secondary" onclick="wds_show_wp_editor('<?php echo $prefix; ?>')" value="<?php _e('Editor', WDS()->prefix); ?>" />
                <p class="description"></p>
              </span>
                <?php
                /*
				Todo will use this code in other version
				  <span class="wd-group">
					<label class="wd-label" for="<?php echo $prefix; ?>_text_position"><?php _e('Text position:', WDS()->prefix); ?></label>
					<input id="<?php echo $prefix; ?>_text_position0" class="wds_text_positio"  type="radio" name="<?php echo $prefix;?>_text_position" value="0" onchange="wds_change_text_position('<?php echo $new_layer['id'] ?>','<?php echo $prefix; ?>_text_position0')" <?php echo ( isset($new_layer['text_position']) && $new_layer['text_position'] == 0) ? 'checked="checked"' :''?> />
					<label for="<?php echo $prefix; ?>_text_position0"><?php _e('Inside', WDS()->prefix);?></label>
					<input id="<?php echo $prefix; ?>_text_position1" class="wds_text_positio"  type="radio" name="<?php echo $prefix;?>_text_position" value="1" onchange="wds_change_text_position('<?php echo $new_layer['id'] ?>','<?php echo $prefix; ?>_text_position1')" <?php echo ( isset($new_layer['text_position']) && $new_layer['text_position'] == 1) ? 'checked="checked"' :''?> />
					<label for="<?php echo $prefix; ?>_text_position1"><?php _e('Top', WDS()->prefix);?></label>
					<input id="<?php echo $prefix; ?>_text_position2" class="wds_text_positio"  type="radio" name="<?php echo $prefix;?>_text_position" value="2" onchange="wds_change_text_position('<?php echo $new_layer['id'] ?>','<?php echo $prefix; ?>_text_position2')" <?php echo ( isset($new_layer['text_position']) && $new_layer['text_position'] == 2) ? 'checked="checked"' :''?> />
					<label for="<?php echo $prefix; ?>_text_position2"><?php _e('Bottom', WDS()->prefix);?></label>
				  </span>
                */
                ?>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix); _e('Leave blank to keep the initial width and height.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_image_width"><?php _e('Dimensions:', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_image_width" class="spider_int_input" type="text" onchange="wds_text_width(this, '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_width']; ?>" name="<?php echo $prefix; ?>_image_width" /> x
                <input id="<?php echo $prefix; ?>_image_height" class="spider_int_input" type="text" onchange="wds_text_height(this, '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_height']; ?>" name="<?php echo $prefix; ?>_image_height" /> %
                <input id="<?php echo $prefix; ?>_image_scale" type="checkbox" onchange="wds_break_word(this, '<?php echo $prefix; ?>')" name="<?php echo $prefix; ?>_image_scale" <?php echo (($new_layer['image_scale']) ? 'checked="checked"' : ''); ?> /><label for="<?php echo $prefix; ?>_image_scale"><?php _e('Break-word', WDS()->prefix); ?></label>
                <p class="description"><?php _e('Leave blank to keep the initial width and height. ', WDS()->prefix); ?><?php _e('Break-word may break lines from between any two letters, if their width is larger than dimensions.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label"><?php _e('Position:', WDS()->prefix); ?></label>
                X <input id="<?php echo $prefix; ?>_left" class="spider_int_input" type="text" <?php echo ($new_layer['align']) ? 'disabled="disabled"' : ''; ?> onchange="jQuery('#<?php echo $prefix; ?>').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                Y <input id="<?php echo $prefix; ?>_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                <input id="<?php echo $prefix; ?>_align_layer" type="checkbox"  name="<?php echo $prefix; ?>_align_layer" <?php echo checked(1, $new_layer['align'] ); ?> value="1" onchange="wds_position_left_disabled('<?php echo $prefix; ?>')" /><label for="<?php echo $prefix; ?>_align_layer"><?php _e('Fixed step (left, center, right)', WDS()->prefix); ?></label>
                <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position. ', WDS()->prefix); ?><?php _e('Fixed step places the layer on one of three positions (left, center, or right), based on its Y position.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_size"><?php _e('Size:', WDS()->prefix); ?></label>
                <span style="display: inline-block">
                    <input id="<?php echo $prefix; ?>_size" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({fontSize: jQuery(this).val() + 'px', lineHeight: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['size']; ?>" name="<?php echo $prefix; ?>_size" /> px
                </span>
                <p class="description"><?php _e('Sets the font size of the text.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_size"><?php _e('Minimum font size:', WDS()->prefix); ?></label>
                <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_min_size" class="spider_int_input" type="text" onchange="wds_min_size_validation('<?php echo $prefix; ?>')" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['min_size']; ?>" name="<?php echo $prefix; ?>_min_size" /> px
                </span>
                <p class="description"><?php _e('Text layer font size shrinks on small screens. Choose the minimum font size, which the text should have.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_color"><?php _e('Color:', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({color: '#' + jQuery(this).val()})" value="<?php echo $new_layer['color']; ?>" name="<?php echo $prefix; ?>_color" />
                <p class="description"></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_hover_color_text"><?php _e('Hover Color:', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_hover_color_text" class="color" type="text" value="<?php echo $new_layer['hover_color_text']; ?>" name="<?php echo $prefix; ?>_hover_color_text" />
                <p class="description"></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_ffamily"><?php _e('Font family:', WDS()->prefix); ?></label>
                <select class="select_icon select_icon_320" style="width: 200px;" id="<?php echo $prefix; ?>_ffamily" onchange="wds_change_fonts('<?php echo $prefix; ?>', 1)" name="<?php echo $prefix; ?>_ffamily">
                  <?php
                  $fonts = (isset($new_layer['google_fonts']) && $new_layer['google_fonts']) ? $google_fonts : $font_families;
                  foreach ($fonts as $key => $font_family) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['ffamily'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <input id="<?php echo $prefix; ?>_google_fonts1" type="radio" name="<?php echo $prefix;  ?>_google_fonts" value="1" <?php echo (($new_layer['google_fonts']) ? 'checked="checked"' : ''); ?> onchange="wds_change_fonts('<?php echo $prefix; ?>')" />
                <label for="<?php echo $prefix; ?>_google_fonts1"><?php _e('Google fonts', WDS()->prefix); ?></label>
                <input id="<?php echo $prefix; ?>_google_fonts0" type="radio" name="<?php echo $prefix;?>_google_fonts" value="0" <?php echo (($new_layer['google_fonts']) ? '' : 'checked="checked"'); ?> onchange="wds_change_fonts('<?php echo $prefix; ?>')" />
                <label for="<?php echo $prefix; ?>_google_fonts0"><?php _e('Default', WDS()->prefix); ?></label>
                <p class="description"></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_fweight"><?php _e('Font weight:', WDS()->prefix); ?></label>
                <select class="select_icon select_icon_320" style="width:70px" id="<?php echo $prefix; ?>_fweight" onchange="jQuery('#<?php echo $prefix; ?>').css({fontWeight: jQuery(this).val()})" name="<?php echo $prefix; ?>_fweight">
                  <?php
                  foreach ($font_weights as $key => $fweight) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['fweight'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $fweight; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p class="description"></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_link" title=""><?php _e('Link:', WDS()->prefix); ?></label>
                <input class="wds_link" id="<?php echo $prefix; ?>_link" type="text" style="width: 200px;" value="<?php echo $new_layer['link']; ?>" name="<?php echo $prefix; ?>_link" />
                <input id="<?php echo $prefix; ?>_target_attr_layer" type="checkbox"  name="<?php echo $prefix; ?>_target_attr_layer" <?php echo (($new_layer["target_attr_layer"]) ? 'checked="checked"' : ''); ?> value="1" /><label for="<?php echo $prefix; ?>_target_attr_layer"><?php _e('Open in a new window', WDS()->prefix); ?></label>
                <p class="description"><?php _e('Use http:// and https:// for external links.', WDS()->prefix); ?></p>
              </span>
              <span class="wd-group">
                <label class="wd-label" for="<?php echo $prefix; ?>_layer_callback_list"><?php _e('Add click action:', WDS()->prefix); ?></label>
                <select class="select_icon select_icon_320" style="width: 120px;" id="<?php echo $prefix; ?>_layer_callback_list" name="<?php echo $prefix; ?>_layer_callback_list" onchange="wds_show_slides_name('<?php echo $prefix; ?>', jQuery(this).val())">
                  <?php
                  foreach ($layer_callbacks as $key => $layer_callback_list) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['layer_callback_list'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $layer_callback_list; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p class="description"></p>
              </span>
              <span class="wd-group">
                <label class="wd-label link_to_slide" for="<?php echo $prefix; ?>_link_to_slide" style="<?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>"><?php _e('Slides Name:', WDS()->prefix); ?></label>
                <select class="select_icon select_icon_320" style="width: 120px;  <?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>" id="<?php echo $prefix; ?>_link_to_slide" name="<?php echo $prefix; ?>_link_to_slide">
                  <?php
                  foreach ($slides_name as $key => $slide_name) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['link_to_slide'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $slide_name; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p class="description"></p>
              </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label"  for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix); ?></label>
                    <span style="display: inline-block;">
                      <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                      <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                    </span>
                    <span style="display: inline-block;">
                      <select class="select_icon select_icon_320" name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                      <?php
                      foreach ($layer_effects_in as $key => $layer_effect_in) {
                        ?>
                        <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                        <?php
                      }
                      ?>
                      </select>
                      <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                    </span>
                    <span style="display: inline-block;">
                      <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" /> ms
                      <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                    </span>
                    <span style="display: inline-block;">
                      <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                      <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                    </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320" name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out">ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_padding" title=""><?php _e('Padding:', WDS()->prefix); ?></label>
                  <input placeholder="5px 10px 10px" id="<?php echo $prefix; ?>_padding" class="spider_char_input" type="text" onchange="document.getElementById('<?php echo $prefix; ?>').style.padding=jQuery(this).val();" value="<?php echo $new_layer['padding']; ?>" name="<?php echo $prefix; ?>_padding">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_fbgcolor"><?php _e('Background Color:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_fbgcolor" class="color" type="text" onchange="wde_change_text_bg_color('<?php echo $prefix; ?>')" value="<?php echo $new_layer['fbgcolor']; ?>" name="<?php echo $prefix; ?>_fbgcolor" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_transparent" title=""><?php _e('Transparency:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_transparent" class="spider_int_input" type="text" onchange="wde_change_text_bg_color('<?php echo $prefix; ?>')" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['transparent']; ?>" name="<?php echo $prefix; ?>_transparent"> %
                  <p class="description"><?php _e('Value must be between 0 and 100.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_border_width" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderWidth: jQuery(this).val()})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['border_width']; ?>" name="<?php echo $prefix; ?>_border_width"> px
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_border_style" onchange="jQuery('#<?php echo $prefix; ?>').css({borderStyle: jQuery(this).val()})" style="width: 80px !important;" name="<?php echo $prefix; ?>_border_style">
                    <?php
                    foreach ($border_styles as $key => $border_style) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_border_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['border_color']; ?>" name="<?php echo $prefix; ?>_border_color">
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_radius" title=""><?php _e('Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['border_radius']; ?>" name="<?php echo $prefix; ?>_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_shadow" title=""><?php _e('Shadow:', WDS()->prefix); ?></label>
                  <input placeholder="10px 10px 5px #888888" id="<?php echo $prefix; ?>_shadow" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({boxShadow: jQuery(this).val()})" value="<?php echo $new_layer['shadow']; ?>" name="<?php echo $prefix; ?>_shadow" />
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class" title=""><?php _e('Add class:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_text_alignment"><?php _e('Text alignment:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width:70px" id="<?php echo $prefix; ?>_text_alignment" onchange="jQuery('#<?php echo $prefix; ?>').css({textAlign: jQuery(this).val()})" name="<?php echo $prefix; ?>_text_alignment">
                    <?php
                    foreach ($text_alignments as $key => $text_alignment) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['text_alignment'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $text_alignment; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="text">
    </tr>
    <?php
    return ob_get_clean();
  }

  /**
   * Video embed layer template
   */
  function wds_videoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles, $id=false, $prefix=false, $layer=false ) {
    $default_global_options = $this->default_global_options;
	$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer == "" ) {
      $new_layer = array(
          'tt' => true,
          'videoType' 				=> 'video',
          'youtube_rel_layer_video' => '0',
          'text' 					=> 'Sample Text',
          'static_layer' 			=> '0',
          'id' 						=> 'pr_1',
          'attr_width' 				=>  '300',
          'attr_height' 			=> '300',
          'image_width' 			=> '300',
          'image_height' 			=> '300',
          'image_scale' 			=> 'slide' . $id . '_layerpr_1_image_scale',
          'color' 					=> 'FFFFFF',
          'left' 					=> '0',
          'top' 					=> '0',
          'link' 					=> '',
          'target_attr_layer' 		=> 0,
          'published' 				=> 1,
          'hide_on_mobile'=> 0,
          'start' 					=> $default_global_options->default_layer_start,
          'layer_effect_in' 		=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 		=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 			=> $default_global_options->default_layer_infinite_in,
          'end' 					=> $default_global_options->default_layer_end,
          'layer_effect_out' 		=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 		=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 			=> $default_global_options->default_layer_infinite_out,
          'border_width' 			=> 2,
          'border_style' 			=> $border_styles,
          'border_radius' 			=> '2px',
          'border_color' 			=> 'BBBBBB',
          'shadow' 					=> '',
          'add_class' 				=> '',
          'layer_video_loop' 		=> '',
          'image_url' 				=> '',
          'alt' 					=> '',
      );
    }
    else {
      $new_layer = array(
          'videoType' 				=> $layer->type,
          'youtube_rel_layer_video' => $layer->youtube_rel_layer_video,
          'text' 					=> $layer->text,
          'static_layer' 			=> $layer->static_layer,
          'id' 						=> $id,
          'attr_width' 				=> $layer->attr_width,
          'attr_height' 			=> $layer->attr_height,
          'image_width' 			=> $layer->image_width,
          'image_height' 			=> $layer->image_height,
          'image_scale' 			=> $layer->image_scale,
          'color' 					=> $layer->color,
          'left' 					=> $layer->left,
          'top' 					=> $layer->top,
          'link' 					=> $layer->link,
          'target_attr_layer' 		=> $layer->target_attr_layer,
          'published' 				=> $layer->published,
	   			'hide_on_mobile' => $layer->hide_on_mobile,
          'start' 					=> $layer->start,
          'layer_effect_in' 		=> $layer->layer_effect_in,
          'duration_eff_in' 		=> $layer->duration_eff_in,
          'infinite_in' 			=> $layer->infinite_in,
          'end' 					=> $layer->end,
          'layer_effect_out' 		=> $layer->layer_effect_out,
          'duration_eff_out' 		=> $layer->duration_eff_out,
          'infinite_out' 			=> $layer->infinite_out,
          'border_width' 			=> $layer->border_width,
          'border_style' 			=> $layer->border_style,
          'border_radius' 			=> $layer->border_radius,
          'border_color' 			=> $layer->border_color,
          'shadow' 					=> $layer->shadow,
          'add_class' 				=> $layer->add_class,
          'layer_video_loop' 		=> $layer->layer_video_loop,
          'image_url' 				=> $layer->image_url,
          'alt' 					=> $layer->alt,
      );
    }
    ob_start();
    ?>
    <tr style="display:none" class="wds_layer_tr wds_videoLayer wds_layer_content">
      <td colspan="3">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                  <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Dimensions:', WDS()->prefix); ?></label>
                  <input type="hidden" id="<?php echo $prefix; ?>_attr_width" name="<?php echo $prefix; ?>_attr_width" value="<?php echo $new_layer['attr_width']; ?>"/>
                  <input type="hidden" id="<?php echo $prefix; ?>_attr_height" name="<?php echo $prefix; ?>_attr_height" value="<?php echo $new_layer['attr_height']; ?>"/>
                  <input type="hidden" id="<?php echo $prefix; ?>_layer_post_id" name="<?php echo $prefix; ?>_layer_post_id" value="<?php echo $new_layer['image_url']; ?>" />
                  <input type="hidden" name="<?php echo $prefix; ?>_alt" id="<?php echo $prefix; ?>_alt" value="<?php echo $new_layer['alt']; ?>"/>
                  <input type="hidden" name="<?php echo $prefix; ?>_link" id="<?php echo $prefix; ?>_link" value="<?php echo $new_layer['link']; ?>"/>
                  <input type="hidden" name="<?php echo $prefix; ?>_image_url" id="<?php echo $prefix; ?>_image_url" value="<?php echo $new_layer['image_url']; ?>" />
                  <input id="<?php echo $prefix; ?>_image_width" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_width']; ?>" name="<?php echo $prefix; ?>_image_width" /> x
                  <input id="<?php echo $prefix; ?>_image_height" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_height']; ?>" name="<?php echo $prefix; ?>_image_height" /> px
                  <p class="description"><?php _e('Set width and height of the video.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Position:', WDS()->prefix); ?></label>
                  X <input id="<?php echo $prefix; ?>_left" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                  Y <input id="<?php echo $prefix; ?>_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                  <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_border_width" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderWidth: jQuery(this).val()})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['border_width']; ?>" name="<?php echo $prefix; ?>_border_width"> px
                  <select class="select_icon"  id="<?php echo $prefix; ?>_border_style" onchange="jQuery('#<?php echo $prefix; ?>').css({borderStyle: jQuery(this).val()})" style="width: 80px !important;" name="<?php echo $prefix; ?>_border_style">
                  <?php
                  foreach ($border_styles as $key => $border_style) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_border_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['border_color']; ?>" name="<?php echo $prefix; ?>_border_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group <?php echo $prefix; ?>_autoplay_td" <?php echo ($new_layer['target_attr_layer'] == "0" && $new_layer['videoType'] == 'upvideo') ? 'style="visibility:hidden"' : ''; ?>>
                  <label class="wd-label"><?php _e('Autoplay:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_image_scale1" type="radio" name="<?php echo $prefix; ?>_image_scale" value="on" <?php echo (($new_layer['image_scale'] == "on") ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_image_scale1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_image_scale0" type="radio" name="<?php echo $prefix; ?>_image_scale" value="off" <?php echo (($new_layer['image_scale'] == "on") ? '' : 'checked="checked"'); ?> />
                  <label  for="<?php echo $prefix; ?>_image_scale0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group" <?php echo ($new_layer['alt'] != 'EMBED_OEMBED_YOUTUBE_VIDEO') ? 'style="visibility:hidden"' : ''; ?>>
                  <label class="wd-label"><?php _e('Disable youtube related video:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_youtube_rel_layer_video1" type="radio" name="<?php echo $prefix; ?>_youtube_rel_layer_video" value="0" <?php echo ((!$new_layer['youtube_rel_layer_video']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_youtube_rel_layer_video1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_youtube_rel_layer_video0" type="radio" name="<?php echo $prefix; ?>_youtube_rel_layer_video" value="1" <?php echo ((!$new_layer['youtube_rel_layer_video']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_youtube_rel_layer_video0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_in as $key => $layer_effect_in) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" /> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;"  onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out"> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_radius" title=""><?php _e('Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['border_radius']; ?>" name="<?php echo $prefix; ?>_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_shadow" title=""><?php _e('Shadow:', WDS()->prefix); ?></label>
                  <input placeholder="10px 10px 5px #888888" id="<?php echo $prefix; ?>_shadow" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({boxShadow: jQuery(this).val()})" value="<?php echo $new_layer['shadow']; ?>" name="<?php echo $prefix; ?>_shadow" />
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class"><?php _e('Add class:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix); ?></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="<?php echo $new_layer['videoType']?>">
    </tr>
    <?php
    return ob_get_clean();
  }

  /**
   * Video upload layer template
   */
  function wds_upvideoLayerTemplate( $layer_effects_in, $layer_effects_out, $border_styles, $id=false, $prefix=false, $layer=false ) {
    $default_global_options = $this->default_global_options;
	$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer == "" ) {
      $new_layer = array(
          'videoType' 				=> 'upvideo',
          'youtube_rel_layer_video' => '1',
          'text' 					=> 'Sample Text',
          'static_layer' 			=> '0',
          'id' 						=> 'pr_1',
          'attr_width' 				=>  '300',
          'attr_height' 			=> '300',
          'image_width' 			=> '300',
          'image_height' 			=> '300',
          'image_scale' 			=> 'on',
          'color' 					=> 'FFFFFF',
          'left' 					=> '0',
          'top' 					=> '0',
          'link' 					=> '',
          'target_attr_layer' 		=> 0,
          'published' 				=> 1,
	   			'hide_on_mobile' => 0,
		  		'start' 					=> $default_global_options->default_layer_start,
          'layer_effect_in' 		=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 		=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 			=> $default_global_options->default_layer_infinite_in,
          'end' 					=> $default_global_options->default_layer_end,
          'layer_effect_out' 		=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 		=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 			=> $default_global_options->default_layer_infinite_out,
          'border_width' 			=> 2,
          'border_style' 			=> $border_styles,
          'border_radius' 			=> '2px',
          'border_color' 			=> 'FFFFFF',
          'shadow' 					=> '',
          'add_class' 				=> '',
          'layer_video_loop' 		=> '',
          'image_url' 				=> '',
          'alt' 					=> '',
      );
    }
    else {
      $new_layer = array(
          'videoType' 				=> $layer->type,
          'youtube_rel_layer_video' => $layer->youtube_rel_layer_video,
          'text' 					=> $layer->text,
          'static_layer' 			=> $layer->static_layer,
          'id' 						=> $id,
          'attr_width' 				=> $layer->attr_width,
          'attr_height' 			=> $layer->attr_height,
          'image_width' 			=> $layer->image_width,
          'image_height' 			=> $layer->image_height,
          'image_scale' 			=> $layer->image_scale,
          'color' 					=> $layer->color,
          'left' 					=> $layer->left,
          'top' 					=> $layer->top,
          'link' 					=> $layer->link,
          'target_attr_layer' 		=> $layer->target_attr_layer,
          'published' 				=> $layer->published,
          'hide_on_mobile' => $layer->hide_on_mobile,
          'start' 					=> $layer->start,
          'layer_effect_in' 		=> $layer->layer_effect_in,
          'duration_eff_in' 		=> $layer->duration_eff_in,
          'infinite_in' 			=> $layer->infinite_in,
          'end' 					=> $layer->end,
          'layer_effect_out' 		=> $layer->layer_effect_out,
          'duration_eff_out' 		=> $layer->duration_eff_out,
          'infinite_out' 			=> $layer->infinite_out,
          'border_color' 			=> $layer->border_color,
          'border_width' 			=> $layer->border_width,
          'border_style' 			=> $layer->border_style,
          'border_radius' 			=> $layer->border_radius,
          'shadow' 					=> $layer->shadow,
          'add_class' 				=> $layer->add_class,
          'layer_video_loop' 		=> $layer->layer_video_loop,
          'image_url' 				=> $layer->image_url,
          'alt' 					=> $layer->alt,

      );
    }
    ob_start();
    ?>
    <tr style="display:none" class="wds_layer_tr wds_upvideoLayer wds_layer_content">
      <td colspan="3">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                  <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Dimensions:', WDS()->prefix); ?></label>
                  <input type="hidden" id="<?php echo $prefix; ?>_attr_width" name="<?php echo $prefix; ?>_attr_width" value="<?php echo $new_layer['attr_width']; ?>"/>
                  <input type="hidden" id="<?php echo $prefix; ?>_attr_height" name="<?php echo $prefix; ?>_attr_height" value="<?php echo $new_layer['attr_height']; ?>"/>
                  <input type="hidden" id="<?php echo $prefix; ?>_layer_post_id" name="<?php echo $prefix; ?>_layer_post_id" value="<?php echo $new_layer['image_url']; ?>" />
                  <input type="hidden" name="<?php echo $prefix; ?>_alt" id="<?php echo $prefix; ?>_alt" value="<?php echo $new_layer['alt']; ?>"/>
                  <input type="hidden" name="<?php echo $prefix; ?>_link" id="<?php echo $prefix; ?>_link" value="<?php echo $new_layer['link']; ?>"/>
                  <input type="hidden" name="<?php echo $prefix; ?>_image_url" id="<?php echo $prefix; ?>_image_url" value="<?php echo $new_layer['image_url']; ?>" />
                  <input id="<?php echo $prefix; ?>_image_width" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_width']; ?>" name="<?php echo $prefix; ?>_image_width" /> x
                  <input id="<?php echo $prefix; ?>_image_height" class="spider_int_input" type="text" onkeyup="wds_scale('#<?php echo $prefix; ?>_image_scale', '<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_height']; ?>" name="<?php echo $prefix; ?>_image_height" /> px
                  <p class="description"><?php _e('Set width and height of the video.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Position:', WDS()->prefix); ?></label>
                  X <input id="<?php echo $prefix; ?>_left" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                  Y <input id="<?php echo $prefix; ?>_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                  <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_border_width" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderWidth: jQuery(this).val()})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['border_width']; ?>" name="<?php echo $prefix; ?>_border_width"> px
                  <select class="select_icon"  id="<?php echo $prefix; ?>_border_style" onchange="jQuery('#<?php echo $prefix; ?>').css({borderStyle: jQuery(this).val()})" style="width: 80px !important;" name="<?php echo $prefix; ?>_border_style">
                    <?php
                    foreach ($border_styles as $key => $border_style) {
                      ?>
                      <option value="<?php echo $key; ?>" <?php echo (($new_layer['border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_border_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['border_color']; ?>" name="<?php echo $prefix; ?>_border_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Video Loop:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_layer_video_loop1" type="radio" name="<?php echo $prefix; ?>_layer_video_loop" value="1" <?php echo (($new_layer['layer_video_loop']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_layer_video_loop1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_layer_video_loop0" type="radio" name="<?php echo $prefix; ?>_layer_video_loop" value="0" <?php echo (($new_layer['layer_video_loop']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_layer_video_loop0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Controls:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_target_attr_layer1" type="radio" onClick="wds_enable_disable_autoplay('visible', '<?php echo $prefix; ?>_autoplay_td', '<?php echo $prefix; ?>_target_attr_layer1')" name="<?php echo $prefix; ?>_controls" value="1" <?php echo (($new_layer['target_attr_layer'] == "1") ? 'checked="checked"' : ''); ?> />
                  <label  for="<?php echo $prefix; ?>_target_attr_layer1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_target_attr_layer0" type="radio" onClick="wds_enable_disable_autoplay('hidden', '<?php echo $prefix; ?>_autoplay_td', '<?php echo $prefix; ?>_target_attr_layer0')" name="<?php echo $prefix; ?>_controls" value="0" <?php echo (($new_layer['target_attr_layer'] == "1") ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_target_attr_layer0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group <?php echo $prefix; ?>_autoplay_td" <?php echo ($new_layer['target_attr_layer'] == "0" && $new_layer['videoType'] == 'upvideo') ? 'style="visibility:hidden"' : ''; ?>>
                  <label class="wd-label"><?php _e('Autoplay:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_image_scale1" type="radio" name="<?php echo $prefix; ?>_image_scale" value="on" <?php echo (($new_layer['image_scale'] == "on") ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_image_scale1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_image_scale0" type="radio" name="<?php echo $prefix; ?>_image_scale" value="off" <?php echo (($new_layer['image_scale'] == "on") ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_image_scale0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_in as $key => $layer_effect_in) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" /> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;"  onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out"> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_radius" title=""><?php _e('Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['border_radius']; ?>" name="<?php echo $prefix; ?>_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_shadow" title=""><?php _e('Shadow:', WDS()->prefix); ?></label>
                  <input placeholder="10px 10px 5px #888888" id="<?php echo $prefix; ?>_shadow" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({boxShadow: jQuery(this).val()})" value="<?php echo $new_layer['shadow']; ?>" name="<?php echo $prefix; ?>_shadow" />
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class" title=""><?php _e('Add class:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix); ?></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="<?php echo $new_layer['videoType']?>">
    </tr>
    <?php
    return ob_get_clean();
  }

  /**
   * Hotspot layer template
   */
  function wds_hotspotLayerTemplate( $font_weights, $layer_callbacks, $slides_name, $layer_effects_in, $layer_effects_out, $border_styles, $text_alignments, $google_fonts, $font_families, $hotp_text_positions, $id=false, $prefix=false, $layer=false ) {
	$default_global_options = $this->default_global_options;
	$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer === FALSE ) {
      $new_layer = array(
          'text' 					=> 'Sample Text',
          'static_layer' 			=> '0',
          'image_width' 			=>  '',
          'image_height' 			=> '',
          'image_scale' 			=> 'slide' . $id . '_layerpr_1_image_scale',
          'size' 					=> '18',
          'min_size' 				=> '11',
          'left' 					=> 20,
          'top' 					=> 20,
          'google_fonts'        => $default_global_options->default_layer_google_fonts,
          'ffamily' 				=> $font_families,
          'fweight' 				=> $default_global_options->default_layer_fweight,
          'link' 					=> '',
          'target_attr_layer' 		=> 1,
          'layer_callback_list' 	=> $layer_callbacks,
          'published' 				=> 1,
          'hide_on_mobile' => 0,
				  'start' 					=> $default_global_options->default_layer_start,
          'layer_effect_in' 		=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 		=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 			=> $default_global_options->default_layer_infinite_in,
          'end' 					=> $default_global_options->default_layer_end,
          'layer_effect_out' 		=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 		=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 			=> $default_global_options->default_layer_infinite_out,
          'padding' 				=> '5px',
          'fbgcolor' 				=> '000000',
          'color' 				    => 'FFFFFF',
          'transparent' 			=> 50,
          'border_width' 			=> 2,
          'border_style' 			=> $border_styles,
          'border_color' 			=> 'BBBBBB',
          'hotp_border_radius' 		=> '25px',
          'border_radius' 			=> '2px',
          'shadow' 					=> '',
          'add_class' 				=> '',
          'text_alignment' 			=> $text_alignments,
          'hotspot_text_display' 	=> 'hover',
          'hotspot_animation' 		=> '1',
          'hotp_width' 				=> 20,
          'hotp_text_position'		=> 'right',
          'hotp_border_width' 		=> 2,
          'hotp_border_style' 		=> $border_styles,
          'hotp_border_color' 		=> 'BBBBBB',
          'hotp_fbgcolor' 		    => 'FFFFFF',
          'link_to_slide' 		    => '',
      );
    }
	else {
      $new_layer = array(
          'text' 					=> $layer->text,
          'static_layer' 			=> $layer->static_layer,
          'image_width' 			=> $layer->image_width,
          'image_height' 			=> $layer->image_height,
          'image_scale' 			=> $layer->image_scale,
          'size' 					=> $layer->size,
          'min_size' 				=> $layer->min_size,
          'left' 					=> $layer->left,
          'top' 					=> $layer->top,
          'google_fonts' 			=> $layer->google_fonts,
          'ffamily' 				=> $layer->ffamily,
          'fweight' 				=> $layer->fweight,
          'link' 					=> $layer->link,
          'target_attr_layer' 		=> $layer->target_attr_layer,
          'layer_callback_list' 	=> $layer->layer_callback_list,
          'published' 				=> $layer->published,
          'hide_on_mobile' => $layer->hide_on_mobile,
          'start' 					=> $layer->start,
          'layer_effect_in' 		=> $layer->layer_effect_in,
          'duration_eff_in' 		=> $layer->duration_eff_in,
          'infinite_in' 			=> $layer->infinite_in,
          'end' 					=> $layer->end,
          'layer_effect_out' 		=> $layer->layer_effect_out,
          'duration_eff_out' 		=> $layer->duration_eff_out,
          'infinite_out' 			=> $layer->infinite_out,
          'padding' 				=> $layer->padding,
          'fbgcolor' 				=> $layer->fbgcolor,
          'color' 				    => $layer->color,
          'transparent' 			=> $layer->transparent,
          'border_width' 			=> $layer->border_width,
          'border_style' 			=> $layer->border_style,
          'border_color' 			=> $layer->border_color,
          'hotp_border_radius' 		=> $layer->hotp_border_radius,
          'border_radius' 			=> $layer->border_radius,
          'shadow' 					=> $layer->shadow,
          'add_class' 				=> $layer->add_class,
          'text_alignment' 			=> $layer->text_alignment,
          'hotspot_text_display' 	=> $layer->hotspot_text_display,
          'hotspot_animation' 		=> $layer->hotspot_animation,
          'hotp_width' 				=> $layer->hotp_width,
          'hotp_text_position'		=> $layer->hotp_text_position,
          'hotp_border_width' 		=> $layer->hotp_border_width,
          'hotp_border_style' 		=> $layer->hotp_border_style,
          'hotp_border_color' 		=> $layer->hotp_border_color,
          'hotp_fbgcolor' 		    => $layer->hotp_fbgcolor,
          'link_to_slide' 		    => $layer->link_to_slide,
      );
    }
    ob_start();
    ?>
    <tr class="wds_layer_tr wds_hotspotLayer wds_layer_content" style="display: none;">
      <td colspan="3">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_text"><?php _e('Text:', WDS()->prefix); ?></label>
                  <textarea id="<?php echo $prefix; ?>_text" class="wds_textarea" name="<?php echo $prefix; ?>_text" onkeyup="wds_new_line('<?php echo $prefix; ?>');"><?php echo $new_layer['text']; ?></textarea>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                  <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_image_width" title=""><?php _e('Dimensions:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_image_width" class="spider_int_input" type="text" onchange="wds_hotspot_text_width('<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_width']; ?>" name="<?php echo $prefix; ?>_image_width" /> x
                  <input id="<?php echo $prefix; ?>_image_height" class="spider_int_input" type="text" onchange="wds_hotspot_text_width('<?php echo $prefix; ?>')" value="<?php echo $new_layer['image_height']; ?>" name="<?php echo $prefix; ?>_image_height" /> px
                  <input id="<?php echo $prefix; ?>_image_scale" type="checkbox" onchange="wds_break_word(this, '<?php echo $prefix; ?>')" name="<?php echo $prefix; ?>_image_scale" <?php echo (($new_layer['image_scale']) ? 'checked="checked"' : ''); ?> /><label for="<?php echo $prefix; ?>_image_scale"><?php _e('Break-word', WDS()->prefix); ?></label>
                  <p class="description"><?php _e('Leave blank to keep the initial width and height. ', WDS()->prefix); ?><?php _e('Break-word may break lines from between any two letters, if their width is larger than dimensions.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Position:', WDS()->prefix); ?></label>
                  X <input id="<?php echo $prefix; ?>_div_left" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>_div').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                  Y <input id="<?php echo $prefix; ?>_div_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>_div').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                  <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_link" title=""><?php _e('Link:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_link" type="text" size="39" class="wds_link" value="<?php echo $new_layer['link']; ?>" name="<?php echo $prefix; ?>_link" />
                  <input id="<?php echo $prefix; ?>_target_attr_layer" type="checkbox"  name="<?php echo $prefix; ?>_target_attr_layer" <?php echo (($new_layer['target_attr_layer']) ? 'checked="checked"' : ''); ?> value="1" /><label for="<?php echo $prefix; ?>_target_attr_layer"><?php _e('Open in a new window', WDS()->prefix); ?></label>
                  <p class="description"><?php _e('Use http:// and https:// for external links.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_htextposition"><?php _e('Hotspot text position:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_htextposition"  name="<?php echo $prefix; ?>_htextposition"  onchange="jQuery('#<?php echo $prefix; ?>_div').attr('data-text-position', jQuery(this).val()); wds_hotspot_position('<?php echo $prefix; ?>')">
                  <?php
                  foreach ($hotp_text_positions as $key => $hotp_text_position) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['hotp_text_position'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $hotp_text_position; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Show Hotspot text:', WDS()->prefix); ?></label>
                  <input type="radio" name="<?php echo $prefix; ?>_hotspot_text_display" id="<?php echo $prefix; ?>_hotspot_text_display_1" value="hover" <?php if ($new_layer['hotspot_text_display'] == 'hover') echo 'checked="checked"'; ?> />
									<label for="<?php echo $prefix; ?>_hotspot_text_display_1"><?php _e('On hover', WDS()->prefix); ?></label>
                  <input type="radio" name="<?php echo $prefix; ?>_hotspot_text_display" id="<?php echo $prefix; ?>_hotspot_text_display_0" value="click" <?php if ($new_layer['hotspot_text_display'] == 'click' ) echo 'checked="checked"'; ?> />
									<label for="<?php echo $prefix; ?>_hotspot_text_display_0"><?php _e('On click', WDS()->prefix); ?></label>
                  <p class="description"><?php _e('Select between the option of always displaying the navigation buttons or only when hovered.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_link"><?php _e('Hotspot Width:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_hotp_width" class="spider_int_input" type="text" onchange="wds_hotspot_width('<?php echo $prefix; ?>')" value="<?php echo $new_layer['hotp_width']; ?>" name="<?php echo $prefix; ?>_hotp_width" /> px
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_hotp_fbgcolor"><?php _e('Hotspot Background Color:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_hotp_fbgcolor" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>_round').css({backgroundColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['hotp_fbgcolor']; ?>" name="<?php echo $prefix; ?>_hotp_fbgcolor" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_hotp_border_width"><?php _e('Hotspot Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_round_hotp_border_width" class="spider_int_input" type="text" onchange="wds_hotpborder_width('<?php echo $prefix; ?>')" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['hotp_border_width']; ?>" name="<?php echo $prefix; ?>_hotp_border_width"> px
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_round_hotp_border_style" onchange="wds_hotpborder_width('<?php echo $prefix; ?>')" style="width: 80px;"  name="<?php echo $prefix; ?>_hotp_border_style">
                  <?php
                  foreach ($border_styles as $key => $hotp_border_style) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['hotp_border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $hotp_border_style; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_hotp_border_color" class="color" type="text" onchange="wds_hotpborder_width('<?php echo $prefix; ?>')" value="<?php echo $new_layer['hotp_border_color']; ?>" name="<?php echo $prefix; ?>_hotp_border_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_hotp_border_radius" title=""><?php _e('Hotspot Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_hotp_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>_round').css({borderRadius: jQuery(this).val()});jQuery('#<?php echo $prefix; ?>_round_effect').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['hotp_border_radius']; ?>" name="<?php echo $prefix; ?>_hotp_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Hotspot Animation:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_hotspot_animation1" type="radio" name="<?php echo $prefix; ?>_hotspot_animation" value="1" <?php echo (($new_layer['hotspot_animation']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_hotspot_animation1"><?php _e('Yes', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_hotspot_animation0" type="radio" name="<?php echo $prefix; ?>_hotspot_animation" value="0" <?php echo (($new_layer['hotspot_animation']) ? '' : 'checked="checked"'); ?> />
                  <label for="<?php echo $prefix; ?>_hotspot_animation0"><?php _e('No', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_callback_list"><?php _e('Add click action:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width: 120px;" id="<?php echo $prefix; ?>_layer_callback_list" name="<?php echo $prefix; ?>_layer_callback_list" onchange="wds_show_slides_name('<?php echo $prefix; ?>', jQuery(this).val())">
                  <?php
                  foreach ($layer_callbacks as $key => $layer_callback_list) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['layer_callback_list'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $layer_callback_list; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_link_to_slide" class="link_to_slide" style="<?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>"><?php _e('Slides Name:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width: 120px;  <?php if ($new_layer['layer_callback_list'] != 'SlideLink') echo 'display:none;'; ?>" id="<?php echo $prefix; ?>_link_to_slide" name="<?php echo $prefix; ?>_link_to_slide">
                  <?php
                  foreach ($slides_name as $key => $slide_name) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['link_to_slide'] == $key ) ? 'selected="selected"' : ''); ?>><?php echo $slide_name; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_in as $key => $layer_effect_in) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" />ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix); ?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;"  onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out"> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_padding" title=""><?php _e('Padding:', WDS()->prefix); ?></label>
                  <input placeholder="5px 10px 10px" id="<?php echo $prefix; ?>_padding" class="spider_char_input" type="text" onchange="document.getElementById('<?php echo $prefix; ?>').style.padding=jQuery(this).val();" value="<?php echo $new_layer['padding']; ?>" name="<?php echo $prefix; ?>_padding">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_size"><?php _e('Size:', WDS()->prefix); ?> </label>
                  <span style="display: inline-block">
                  <input id="<?php echo $prefix; ?>_size" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({fontSize: jQuery(this).val() + 'px', lineHeight: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['size']; ?>" name="<?php echo $prefix; ?>_size" /> px
                  </span>
                  <p class="description"><?php _e('Sets the font size of the text.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_size"><?php _e('Minimum font size:', WDS()->prefix); ?> </label>
                  <span style="display: inline-block;">
                  <input id="<?php echo $prefix; ?>_min_size" class="spider_int_input" type="text" onchange="wds_min_size_validation('<?php echo $prefix; ?>')" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['min_size']; ?>" name="<?php echo $prefix; ?>_min_size" /> px
                  </span>
                  <p class="description"><?php _e('Text layer font size shrinks on small screens. Choose the minimum font size, which the text should have.', WDS()->prefix); ?></p>
                </span>

                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_color"><?php _e('Color:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({color: '#' + jQuery(this).val()})" value="<?php echo $new_layer['color']; ?>" name="<?php echo $prefix; ?>_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_ffamily"><?php _e('Font family:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320"  style="width: 180px;"  id="<?php echo $prefix; ?>_ffamily" onchange="wds_change_fonts('<?php echo $prefix; ?>', 1)" name="<?php echo $prefix; ?>_ffamily">
                  <?php
                  $fonts = (isset($new_layer['google_fonts']) && $new_layer['google_fonts']) ? $google_fonts : $font_families;
                  foreach ($fonts as $key => $font_family) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['ffamily'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_google_fonts1" type="radio" name="<?php echo $prefix; ?>_google_fonts" value="1" <?php echo (($new_layer['google_fonts']) ? 'checked="checked"' : ''); ?> onchange="wds_change_fonts('<?php echo $prefix; ?>')" />
                  <label for="<?php echo $prefix; ?>_google_fonts1">Google fonts</label>
                  <input id="<?php echo $prefix; ?>_google_fonts0" type="radio" name="<?php echo $prefix; ?>_google_fonts" value="0" <?php echo (($new_layer['google_fonts']) ? '' : 'checked="checked"'); ?> onchange="wds_change_fonts('<?php echo $prefix; ?>')" />
                  <label for="<?php echo $prefix; ?>_google_fonts0"><?php _e('Default', WDS()->prefix); ?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_fweight"><?php _e('Font weight:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_fweight" onchange="jQuery('#<?php echo $prefix; ?>').css({fontWeight: jQuery(this).val()})" name="<?php echo $prefix; ?>_fweight">
                  <?php
                  foreach ($font_weights as $key => $fweight) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['fweight'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $fweight; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_fbgcolor"><?php _e('Background Color:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_fbgcolor" class="color" type="text" onchange="wde_change_text_bg_color('<?php echo $prefix; ?>')" value="<?php echo $new_layer['fbgcolor']; ?>" name="<?php echo $prefix; ?>_fbgcolor" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_transparent" title=""><?php _e('Transparency:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_transparent" class="spider_int_input" type="text" onchange="wde_change_text_bg_color('<?php echo $prefix; ?>')" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['transparent']; ?>" name="<?php echo $prefix; ?>_transparent"> %
                  <p class="description"><?php _e('Value must be between 0 and 100.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_width"><?php _e('Border:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_border_width" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderWidth: jQuery(this).val()})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['border_width']; ?>" name="<?php echo $prefix; ?>_border_width"> px
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_border_style" onchange="jQuery('#<?php echo $prefix; ?>').css({borderStyle: jQuery(this).val()})" style="width: 80px;"  name="<?php echo $prefix; ?>_border_style">
                  <?php
                  foreach ($border_styles as $key => $border_style) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['border_style'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <input id="<?php echo $prefix; ?>_border_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderColor: '#' + jQuery(this).val()})" value="<?php echo $new_layer['border_color']; ?>" name="<?php echo $prefix; ?>_border_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_border_radius"><?php _e('Radius:', WDS()->prefix); ?></label>
                  <input placeholder="4px" id="<?php echo $prefix; ?>_border_radius" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({borderRadius: jQuery(this).val()})" value="<?php echo $new_layer['border_radius']; ?>" name="<?php echo $prefix; ?>_border_radius">
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_shadow" title=""><?php _e('Shadow:', WDS()->prefix); ?></label>
                  <input placeholder="10px 10px 5px #888888" id="<?php echo $prefix; ?>_shadow" class="spider_char_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({boxShadow: jQuery(this).val()})" value="<?php echo $new_layer['shadow']; ?>" name="<?php echo $prefix; ?>_shadow" />
                  <p class="description"><?php _e('Use CSS type values.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class"><?php _e('Add class:', WDS()->prefix); ?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix); ?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_text_alignment"><?php _e('Text alignment:', WDS()->prefix); ?></label>
                  <select class="select_icon select_icon_320" style="width:70px" id="<?php echo $prefix; ?>_text_alignment" onchange="jQuery('#<?php echo $prefix; ?>').css({textAlign: jQuery(this).val()})" name="<?php echo $prefix; ?>_text_alignment">
                  <?php
                  foreach ($text_alignments as $key => $text_alignment) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['text_alignment'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $text_alignment; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <p class="description"></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="hotspots">
    </tr>
    <?php
    return ob_get_clean();
  }

  /**
   * Social layer template
   */
  function wds_socialLayerTemplate( $social_buttons, $layer_effects_in, $layer_effects_out, $id=false, $prefix=false, $layer=false ) {
    $default_global_options = $this->default_global_options;
	$free_layer_effects = array('none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight');
    $id = ( $id == "" ) ? '%%slideID%%' : $id;
    $prefix = (isset($prefix) && $prefix != "") ? $prefix : 'slide'.$id.'_layerpr_%%LayerId%%';
    if( $layer == "" ) {
      $new_layer = array(
          'static_layer' 		=> '0',
          'left' 				=> '0',
          'top' 				=> '0',
          'social_button' 		=> $social_buttons,
          'size' 				=> 18,
          'transparent' 		=> 0,
          'published' 			=> 1,
          'hide_on_mobile' 	=> 0,
		  		'start' 				=> $default_global_options->default_layer_start,
          'layer_effect_in' 	=> $default_global_options->default_layer_effect_in,
          'duration_eff_in' 	=> $default_global_options->default_layer_duration_eff_in,
          'infinite_in' 		=> $default_global_options->default_layer_infinite_in,
          'end' 				=> $default_global_options->default_layer_end,
          'layer_effect_out' 	=> $default_global_options->default_layer_effect_out,
          'duration_eff_out' 	=> $default_global_options->default_layer_duration_eff_out,
          'infinite_out' 		=> $default_global_options->default_layer_infinite_out,
          'color' 				=> 'FFFFFF',
          'hover_color' 		=> 'FFFFFF',
          'add_class' 			=> ''
      );
    }
	else {
      $new_layer = array(
          'static_layer' 		=> $layer->static_layer,
          'left' 				=> $layer->left,
          'top' 				=> $layer->top,
          'social_button' 		=> $layer->social_button,
          'size' 				=> $layer->size,
          'transparent' 		=> $layer->transparent,
          'published' 			=> $layer->published,
          'hide_on_mobile' => $layer->hide_on_mobile,
          'start' 				=> $layer->start,
          'layer_effect_in' 	=> $layer->layer_effect_in,
          'duration_eff_in' 	=> $layer->duration_eff_in,
          'infinite_in' 		=> $layer->infinite_in,
          'end' 				=> $layer->end,
          'layer_effect_out' 	=> $layer->layer_effect_out,
          'duration_eff_out' 	=> $layer->duration_eff_out,
          'infinite_out' 		=> $layer->infinite_out,
          'color' 				=> $layer->color,
          'hover_color' 		=> $layer->hover_color,
          'add_class' 			=> $layer->add_class
      );
    }

    ob_start();
    ?>
    <tr class="wds_layer_tr wds_socialLayer wds_layer_content" style="display: none;">
      <td colspan="3">
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <span class="wd-group">
                  <label class="wd-label"><?php _e('Published:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_published1" type="radio" name="<?php echo $prefix; ?>_published" value="1" <?php echo (($new_layer['published']) ? 'checked="checked"' : ''); ?> />
                  <label for="<?php echo $prefix; ?>_published1"><?php _e('Yes', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_published0" type="radio" name="<?php echo $prefix; ?>_published" value="0" <?php echo (($new_layer['published']) ? '' : 'checked="checked"'); ?>/>
                  <label for="<?php echo $prefix; ?>_published0"><?php _e('No', WDS()->prefix);?></label>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_static_layer"><?php _e('Static layer:', WDS()->prefix);?> </label>
                  <input id="<?php echo $prefix; ?>_static_layer" type="checkbox"  name="<?php echo $prefix; ?>_static_layer" <?php echo checked(1, $new_layer['static_layer']); ?> value="1" />
                  <p class="description"><?php _e('The layer will be visible on all slides.', WDS()->prefix);?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" title=""><?php _e('Position:', WDS()->prefix);?></label>
                  X <input id="<?php echo $prefix; ?>_left" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({left: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['left']; ?>" name="<?php echo $prefix; ?>_left" />
                  Y <input id="<?php echo $prefix; ?>_top" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({top: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['top']; ?>" name="<?php echo $prefix; ?>_top" />
                  <p class="description"><?php _e('In addition, you can drag the layer and drop it to the desired position.', WDS()->prefix);?></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_social_button"><?php _e('Social button:', WDS()->prefix);?></label>
                  <select class="select_icon select_icon_320"  id="<?php echo $prefix; ?>_social_button"  onchange="jQuery('#<?php echo $prefix; ?>').attr('class', 'wds_draggable_<?php echo $id; ?> wds_draggable fa fa-' + jQuery(this).val())" name="<?php echo $prefix; ?>_social_button" style="width:150px;">
                  <?php
                  foreach ($social_buttons as $key => $social_button) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo (($new_layer['social_button'] == $key) ? 'selected="selected"' : ''); ?>><?php echo $social_button; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_size"><?php _e('Size:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_size" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({fontSize: jQuery(this).val() + 'px', lineHeight: jQuery(this).val() + 'px'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['size']; ?>" name="<?php echo $prefix; ?>_size" /> px
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_transparent" title=""><?php _e('Transparency:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_transparent" class="spider_int_input" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({opacity: (100 - jQuery(this).val()) / 100, filter: 'Alpha(opacity=' + 100 - jQuery(this).val() + ')'})" onkeypress="return spider_check_isnum(event)" value="<?php echo $new_layer['transparent']; ?>" name="<?php echo $prefix; ?>_transparent" /> %
                  <p class="description"><?php _e('Value must be between 0 and 100.', WDS()->prefix);?></p>
                </span>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
								<span class="wd-group">
									<label class="wd-label" for="<?php echo $prefix; ?>_hide_on_mobile"><?php _e('Hide on small screens', WDS()->prefix); ?></label>
										<input type="text" id="<?php echo $prefix; ?>_hide_on_mobile" name="<?php echo $prefix; ?>_hide_on_mobile" value="<?php echo $new_layer['hide_on_mobile']; ?> " class="spider_int_input" onkeypress="return spider_check_isnum(event)" /> px
										<p class="description"><?php _e('Hide layer when screen size is smaller than this value.', WDS()->prefix); ?></p>
								</span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_in"><?php _e('Effect In:', WDS()->prefix);?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_start" class="spider_int_input" type="text" value="<?php echo $new_layer['start']; ?>" name="<?php echo $prefix; ?>_start" /> ms
                    <p class="description"><?php _e('Start', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                          <select class="select_icon select_icon_320"  name="<?php echo $prefix; ?>_layer_effect_in" id="<?php echo $prefix; ?>_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_in as $key => $layer_effect_in) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_in'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_in; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_in" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_in').val());" value="<?php echo $new_layer['duration_eff_in']; ?>" name="<?php echo $prefix; ?>_duration_eff_in" /> ms
                    <p class="description"><?php _e('Duration', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_in" type="text" name="<?php echo $prefix; ?>_infinite_in" value="<?php echo $new_layer['infinite_in']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_in'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_in('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix);?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_layer_effect_out"><?php _e('Effect Out:', WDS()->prefix);?></label>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_end" class="spider_int_input" type="text" value="<?php echo $new_layer['end']; ?>" name="<?php echo $prefix; ?>_end"> ms
                    <p class="description"><?php _e('Start', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                    <select class="select_icon select_icon_320" name="<?php echo $prefix; ?>_layer_effect_out" id="<?php echo $prefix; ?>_layer_effect_out" style="width:150px;" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());">
                    <?php
                    foreach ($layer_effects_out as $key => $layer_effect_out) {
                      ?>
                      <option <?php echo (WDS()->is_free && !in_array($key, $free_layer_effects)) ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', WDS()->prefix) . '"' : ''; ?> value="<?php echo $key; ?>" <?php if ($new_layer['layer_effect_out'] == $key) echo 'selected="selected"'; ?>><?php echo $layer_effect_out; ?></option>
                      <?php
                    }
                    ?>
                    </select>
                    <p class="description"><?php _e('Effect', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 1); wds_trans_end('<?php echo $prefix; ?>', jQuery('#<?php echo $prefix; ?>_layer_effect_out').val());" value="<?php echo $new_layer['duration_eff_out']; ?>" name="<?php echo $prefix; ?>_duration_eff_out">ms
                    <p class="description"><?php _e('Duration', WDS()->prefix);?></p>
                  </span>
                  <span style="display: inline-block;">
                    <input id="<?php echo $prefix; ?>_infinite_out" type="text" name="<?php echo $prefix; ?>_infinite_out" value="<?php echo $new_layer['infinite_out']; ?>" class="spider_int_input" title="0 for play infinte times" <?php echo ($new_layer['layer_effect_out'] == 'none') ? 'disabled="disabled"' : ''; ?> onchange="wds_trans_effect_out('<?php echo $id; ?>', '<?php echo $prefix; ?>', 0); wds_trans_end('<?php echo $prefix; ?>', jQuery(this).val());" />
                    <p class="description"><?php _e('Iteration', WDS()->prefix);?></p>
                  </span>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_color"><?php _e('Color:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_color" class="color" type="text" onchange="jQuery('#<?php echo $prefix; ?>').css({color: '#' + jQuery(this).val()})" value="<?php echo $new_layer['color']; ?>" name="<?php echo $prefix; ?>_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_hover_color"><?php _e('Hover Color:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_hover_color" class="color" type="text" value="<?php echo $new_layer['hover_color']; ?>" name="<?php echo $prefix; ?>_hover_color" />
                  <p class="description"></p>
                </span>
                <span class="wd-group">
                  <label class="wd-label" for="<?php echo $prefix; ?>_add_class" title=""><?php _e('Add class:', WDS()->prefix);?></label>
                  <input id="<?php echo $prefix; ?>_add_class" class="spider_char_input" type="text" value="<?php echo $new_layer['add_class']; ?>" name="<?php echo $prefix; ?>_add_class" />
                  <p class="description"><?php _e('Use this option to add a unique class to this layer.', WDS()->prefix);?></p>
                </span>
              </div>
            </div>
          </div>
        </div>
      </td>
      <input type="hidden" name="<?php echo $prefix; ?>_type" id="<?php echo $prefix; ?>_type" value="social">
    </tr>
    <?php
    return ob_get_clean();
  }
}