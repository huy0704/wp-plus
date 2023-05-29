<?php
class WDSViewGoptions_wds {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display($sliders) {
    $default_layer_fweights = array(
      'lighter' => __('Lighter', WDS()->prefix),
      'normal' => __('Normal', WDS()->prefix),
      'bold' => __('Bold', WDS()->prefix),
    );
    $permissions =  array(
      'edit_posts' => 'Contributor',
      'publish_posts' => 'Author',
      'moderate_comments' => 'Editor',
      'manage_options' => 'Administrator',
    );
    $default_layer_effects_in = array(
      'none'          => __('None', WDS()->prefix),
      'bounce'        => __('Bounce', WDS()->prefix),
      'flash'         => __('Flash', WDS()->prefix),
      'pulse'         => __('Pulse', WDS()->prefix),
      'rubberBand'    => __('RubberBand', WDS()->prefix),
      'shake'         => __('Shake', WDS()->prefix),
      'swing'         => __('Swing', WDS()->prefix),
      'tada'          => __('Tada', WDS()->prefix),
      'wobble'        => __('Wobble', WDS()->prefix),
      'hinge'         => __('Hinge', WDS()->prefix),
      'lightSpeedIn'  => __('LightSpeedIn', WDS()->prefix),
      'rollIn'        => __('RollIn', WDS()->prefix),
      'bounceIn'      => __('BounceIn', WDS()->prefix),
      'bounceInDown'  => __('BounceInDown', WDS()->prefix),
      'bounceInLeft'  => __('BounceInLeft', WDS()->prefix),
      'bounceInRight' => __('BounceInRight', WDS()->prefix),
      'bounceInUp'    => __('BounceInUp', WDS()->prefix),
      'fadeIn'         => __('FadeIn', WDS()->prefix),
      'fadeInDown'     => __('FadeInDown', WDS()->prefix),
      'fadeInDownBig'  => __('FadeInDownBig', WDS()->prefix),
      'fadeInLeft'     => __('FadeInLeft', WDS()->prefix),
      'fadeInLeftBig'  => __('FadeInLeftBig', WDS()->prefix),
      'fadeInRight'    => __('FadeInRight', WDS()->prefix),
      'fadeInRightBig' => __('FadeInRightBig', WDS()->prefix),
      'fadeInUp'       => __('FadeInUp', WDS()->prefix),
      'fadeInUpBig'    => __('FadeInUpBig', WDS()->prefix),
      'flip'    => __('Flip', WDS()->prefix),
      'flipInX' => __('FlipInX', WDS()->prefix),
      'flipInY' => __('FlipInY', WDS()->prefix),
      'rotateIn'          => __('RotateIn', WDS()->prefix),
      'rotateInDownLeft'  => __('RotateInDownLeft', WDS()->prefix),
      'rotateInDownRight' => __('RotateInDownRight', WDS()->prefix),
      'rotateInUpLeft'    => __('RotateInUpLeft', WDS()->prefix),
      'rotateInUpRight'   => __('RotateInUpRight', WDS()->prefix),
      'zoomIn'      => __('ZoomIn', WDS()->prefix),
      'zoomInDown'  => __('ZoomInDown', WDS()->prefix),
      'zoomInLeft'  => __('ZoomInLeft', WDS()->prefix),
      'zoomInRight' => __('ZoomInRight', WDS()->prefix),
      'zoomInUp'    => __('ZoomInUp', WDS()->prefix),
    );
    $default_layer_effects_out = array(
      'none'       => __('None', WDS()->prefix),
      'bounce'     => __('Bounce', WDS()->prefix),
      'flash'      => __('Flash', WDS()->prefix),
      'pulse'      => __('Pulse', WDS()->prefix),
      'rubberBand' => __('RubberBand', WDS()->prefix),
      'shake'      => __('Shake', WDS()->prefix),
      'swing'      => __('Swing', WDS()->prefix),
      'tada'       => __('Tada', WDS()->prefix),
      'wobble'     => __('Wobble', WDS()->prefix),
      'hinge'      => __('Hinge', WDS()->prefix),
      'lightSpeedOut' => __('LightSpeedOut', WDS()->prefix),
      'rollOut'       => __('RollOut', WDS()->prefix),
      'bounceOut'      => __('BounceOut', WDS()->prefix),
      'bounceOutDown'  => __('BounceOutDown', WDS()->prefix),
      'bounceOutLeft'  => __('BounceOutLeft', WDS()->prefix),
      'bounceOutRight' => __('BounceOutRight', WDS()->prefix),
      'bounceOutUp'    => __('BounceOutUp', WDS()->prefix),
      'fadeOut'         => __('FadeOut', WDS()->prefix),
      'fadeOutDown'     => __('FadeOutDown', WDS()->prefix),
      'fadeOutDownBig'  => __('FadeOutDownBig', WDS()->prefix),
      'fadeOutLeft'     => __('FadeOutLeft', WDS()->prefix),
      'fadeOutLeftBig'  => __('FadeOutLeftBig', WDS()->prefix),
      'fadeOutRight'    => __('FadeOutRight', WDS()->prefix),
      'fadeOutRightBig' => __('FadeOutRightBig', WDS()->prefix),
      'fadeOutUp'       => __('FadeOutUp', WDS()->prefix),
      'fadeOutUpBig'    => __('FadeOutUpBig', WDS()->prefix),
      'flip'     => __('Flip', WDS()->prefix),
      'flipOutX' => __('FlipOutX', WDS()->prefix),
      'flipOutY' => __('FlipOutY', WDS()->prefix),
      'rotateOut'          => __('RubberBand', WDS()->prefix),
      'rotateOutDownLeft'  => __('RotateOutDownLeft', WDS()->prefix),
      'rotateOutDownRight' => __('RotateOutDownRight', WDS()->prefix),
      'rotateOutUpLeft'    => __('RotateOutUpLeft', WDS()->prefix),
      'rotateOutUpRight'   => __('RotateOutUpRight', WDS()->prefix),
      'zoomOut'      => __('ZoomOut', WDS()->prefix),
      'zoomOutDown'  => __('ZoomOutDown', WDS()->prefix),
      'zoomOutLeft'  => __('ZoomOutLeft', WDS()->prefix),
      'zoomOutRight' => __('ZoomOutRight', WDS()->prefix),
      'zoomOutUp'    => __('ZoomOutUp', WDS()->prefix),
    );
    $font_families = WDW_S_Library::get_font_families();
    $google_fonts = WDW_S_Library::get_google_fonts();
    $loading_gifs = array(
      0 => __('Loading default', WDS()->prefix),
      1 => __('Loading1', WDS()->prefix),
      2 => __('Loading2', WDS()->prefix),
      3 => __('Loading3', WDS()->prefix),
      4 => __('Loading4', WDS()->prefix),
      5 => __('Loading5', WDS()->prefix),
    );

    $wds_global_options = get_option("wds_global_options", 0);
    $global_options = json_decode($wds_global_options);
    if ( !$wds_global_options ) {
      $global_options = (object) WDW_S_Library::global_options_defults();
      $global_options->loading_gif = get_option("wds_loading_gif", 0);
      $global_options->register_scripts = get_option("wds_register_scripts", 0);
    }
    $global_options->permission = isset($global_options->permission) && $global_options->permission ? $global_options->permission : 'manage_options';;
    $uninstall_href = add_query_arg( array( 'page' => 'uninstall_wds'), admin_url('admin.php') );
    ?>
  <div class="clear"></div>
	<div class="wrap">
    <form id="sliders_form" class="wds_options_form" method="post" action="admin.php?page=goptions_wds" enctype="multipart/form-data">
      <?php wp_nonce_field('nonce_wd', 'nonce_wd'); ?>
		<div class="wds-options-page-banner">
			<div class="wds-options-logo"></div>
			<div class="wds-options-logo-title"><?php _e('Options', WDS()->prefix); ?></div>
			<div class="wds-page-actions">
			   <button class="button button-primary" onclick="spider_set_input_value('task', 'save');"><?php _e('Save', WDS()->prefix); ?></button>
			</div>	
		</div>
		<div class="wd-table">
			<div class="wd-table-col wd-table-col-50 wd-table-col-left">
				<div class="wd-box-section">
					<div class="wd-box-title">
						<strong><?php _e('Global Options', WDS()->prefix); ?></strong>
					</div>
					<div class="wd-box-content">
						<div class="wd-group">
							<label class="wd-label"><?php _e('Enable WD Media Uploader', WDS()->prefix); ?></label>
							<input type="radio" id="spider_uploader1" name="spider_uploader" <?php echo (($global_options->spider_uploader == 1)? "checked='checked'" : ""); ?> value="1" /><label <?php echo ($global_options->spider_uploader ? 'class="selected_color"' : ''); ?> for="spider_uploader1"><?php _e('Yes', WDS()->prefix); ?></label>
							<input type="radio" id="spider_uploader0" name="spider_uploader" <?php echo (($global_options->spider_uploader == 0)? "checked='checked'" : ""); ?> value="0" /><label <?php echo ($global_options->spider_uploader ? '' : 'class="selected_color"'); ?> for="spider_uploader0"><?php _e('No', WDS()->prefix); ?></label>
							<p class="description"><?php _e('Enabling this option lets you use custom media uploader to add images, instead of WordPress Media Library.', WDS()->prefix); ?></p>
						</div>
						<div class="wd-group">
							<label for="loading_gif" class="wd-label"><?php _e('Loading icon', WDS()->prefix); ?></label>
							<select class="select_icon select_icon_320 select_gif" name="loading_gif" id="loading_gif" onchange="wds_loading_gif(jQuery(this).val(), '<?php echo WDS()->plugin_url ?>')">
							<?php foreach ($loading_gifs as $key => $loading_gif) { ?>
								<option value="<?php echo $key; ?>" <?php if ($global_options->loading_gif == $key) echo 'selected="selected"'; ?>><?php echo $loading_gif; ?></option>
							<?php } ?>
							</select>
							<span class="button wds_fieldset_img_preview" onclick="wds_loading_preview()"><?php _e('Preview', WDS()->prefix); ?></span>
							<div class="wds_fieldset_img">
								<img id="load_gif_img" src="<?php echo WDS()->plugin_url . '/images/loading/' . $global_options->loading_gif . '.gif'; ?>" />
							</div>
						</div>
						<div class="wd-group">
							<label class="wd-label"><?php _e('Uninstall Slider by 10Web', WDS()->prefix); ?></label>
							<a class="button" href="<?php echo $uninstall_href ?>"><?php _e('Uninstall', WDS()->prefix); ?></a>
						</div>
					</div>
				</div>
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Role Options', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <div class="wd-group">
              <label for="permission" class="wd-label"><?php _e('Roles', WDS()->prefix); ?></label>
              <select id="permission" name="permission">
                <?php
                foreach ($permissions as $key => $permission) {
                  ?>
                  <option value="<?php echo $key; ?>" <?php if (isset($global_options->permission) && $global_options->permission == $key) echo 'selected="selected"'; ?>><?php echo $permission; ?></option>
                  <?php
                }
                ?>
              </select>
              <p class="description"><?php _e('Choose a WordPress user role which can have visible slider on the menu bar.',WDS()->prefix); ?></p>
            </div>
          </div>
        </div>
			</div>
			<div class="wd-table-col wd-table-col-50 wd-table-col-right">
				<div class="wd-box-section">
					<div class="wd-box-title">
						<strong><?php _e('Default options for layers', WDS()->prefix); ?></strong>
					</div>
					<div class="wd-box-content<?php echo (WDS()->is_free ? ' wd-free' : ''); ?>">
            <?php
            if ( WDS()->is_free ) {
              echo WDW_S_Library::message_id(0, __('This functionality is disabled in free version.', WDS()->prefix), 'error');
            }
            ?>
						<div class="wd-group">
							<label class="wd-label" for="default_layer_ffamily"><?php _e('Font', WDS()->prefix); ?></label>
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_google_fonts1" type="radio" name="default_layer_google_fonts" value="1" <?php echo (($global_options->default_layer_google_fonts) ? 'checked="checked"' : ''); ?> onchange="wds_change_fonts()" />
							<label for="default_layer_google_fonts1"><?php _e('Google fonts', WDS()->prefix); ?></label>
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_google_fonts0" type="radio" name="default_layer_google_fonts" value="0" <?php echo (($global_options->default_layer_google_fonts) ? '' : 'checked="checked"'); ?> onchange="wds_change_fonts()" />
							<label for="default_layer_google_fonts0"><?php _e('Default', WDS()->prefix); ?></label>
						</div>
						<div class="wd-group">
							<label class="wd-label wds_default_label" for="default_layer_ffamily"><?php _e('Font family', WDS()->prefix); ?></label>
							<select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" id="default_layer_ffamily" onchange="wds_change_fonts('', 1)" name="default_layer_ffamily">
							<?php
								$fonts = (isset($global_options->default_layer_google_fonts) && $global_options->default_layer_google_fonts) ? $google_fonts : $font_families;
								foreach ($fonts as $key => $font_family) {
								?>
								<option value="<?php echo $key; ?>" <?php echo (($global_options->default_layer_ffamily == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="wd-group<?php echo (($global_options->default_layer_google_fonts) ? ' wds_hide' : ''); ?>">
							<label class="wd-label" for="possib_add_ffamily_input"><?php _e('Add font-family', WDS()->prefix); ?></label>
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="text" id="possib_add_ffamily_input" value="" class="spider_box_input"/>
							<input type="hidden" id="possib_add_ffamily" name="possib_add_ffamily" value="<?php echo $global_options->possib_add_ffamily; ?>"/>
							<input type="hidden" id="possib_add_ffamily_google" name="possib_add_ffamily_google" value="<?php echo $global_options->possib_add_ffamily_google; ?>"/>
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="add_font_family" class="button button-primary" type="button" onclick="set_ffamily_value();spider_set_input_value('task', 'save_font_family');spider_form_submit(event, 'sliders_form')" value="<?php _e('Add font-family', WDS()->prefix); ?>"/>
							<p class="description"><?php _e('The added font family will appear in the drop-down list of fonts.', WDS()->prefix); ?></p>
						</div>
						<div class="wd-group">
							<label class="wd-label wds_default_label" for="default_layer_fweight"><?php _e('Font weight', WDS()->prefix); ?></label>
							<select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" id="default_layer_fweight"  name="default_layer_fweight">
							<?php foreach ($default_layer_fweights as $key => $default_layer_fweight) { ?>
								<option value="<?php echo $key; ?>" <?php echo (($global_options->default_layer_fweight == $key) ? 'selected="selected"' : ''); ?>><?php echo $default_layer_fweight; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="wd-group">
							<label class="wd-label wds_default_label" for="default_layer_effect_in"><?php _e('Effect In', WDS()->prefix); ?></label>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_start" class="spider_int_input" type="text" value="<?php echo $global_options->default_layer_start; ?>" name="default_layer_start"/> ms
								<p class="description"><?php _e('Start', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" name="default_layer_effect_in" id="default_layer_effect_in">
								<?php foreach ( $default_layer_effects_in as $key => $default_layer_effect_in ) { ?>
									<option value="<?php echo $key; ?>" <?php echo ( $global_options->default_layer_effect_in == $key ) ? 'selected="selected"' : '' ?>><?php echo $default_layer_effect_in; ?></option>
								<?php } ?>
								</select>
								<p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_duration_eff_in" class="spider_int_input" type="text" value="<?php echo $global_options->default_layer_duration_eff_in; ?>" name="default_layer_duration_eff_in"/>ms
								<p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_infinite_in" type="text" name="default_layer_infinite_in" value="<?php echo $global_options->default_layer_infinite_in; ?>" class="spider_int_input" title="<?php _e('0 for play infinte times', WDS()->prefix);?>" />
								<p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
							</span>
						</div>
						<div class="wd-group">
							<label class="wd-label wds_default_label" for="default_layer_effect_out"><?php _e('Effect Out', WDS()->prefix); ?></label>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_end" class="spider_int_input" type="text" value="<?php echo $global_options->default_layer_end; ?>" name="default_layer_end">ms
								<p class="description"><?php _e('Start', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<select <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> class="select_icon select_icon_320" name="default_layer_effect_out" id="default_layer_effect_out" style="width:150px;">
								<?php foreach ($default_layer_effects_out as $key => $default_layer_effect_out) { ?>
									<option value="<?php echo $key; ?>" <?php echo ( $global_options->default_layer_effect_out == $key ) ? 'selected="selected"' : '' ?>><?php echo $default_layer_effect_out; ?></option>
									<?php
								  }
								  ?>
								</select>
								<p class="description"><?php _e('Effect', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" value="<?php echo $global_options->default_layer_duration_eff_out; ?>" name="default_layer_duration_eff_out">ms
								<p class="description"><?php _e('Duration', WDS()->prefix); ?></p>
							</span>
							<span style="display: inline-block;">
								<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_infinite_out" type="text" name="default_layer_infinite_out" value="<?php echo $global_options->default_layer_infinite_out; ?>" class="spider_int_input" title="<?php _e('0 for play infinte times', WDS()->prefix); ?>" />
								<p class="description"><?php _e('Iteration', WDS()->prefix); ?></p>
							</span>
						</div>
						<div class="wd-group">
							<label class="wd-label wds_default_label" for="default_layer_add_class"><?php _e('Add class', WDS()->prefix); ?></label>
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> id="default_layer_add_class" class="spider_char_input" type="text" value="<?php echo $global_options->default_layer_add_class; ?>" name="default_layer_add_class" />
						</div>
						<div class="wd-group">
							<input <?php echo (WDS()->is_free ? 'disabled="disabled" title="' . __('This functionality is disabled in free version.', WDS()->prefix) . '"' : ''); ?> type="button" class="button button-primary" onclick="wds_set_one(); wds_invent_default_layer_check();" value="<?php _e('Apply to existing layers', WDS()->prefix); ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wds_opacity_set" onclick="jQuery('.wds_opacity_set').hide();jQuery('.wds_set').hide();"></div>
		<div class="wds_set">
			<table>
			  <tbody class="choose_slider_tbody">
				<tr>
				  <td colspan="2">
					<select class="select_icon select_icon_320" id="choose_slider" name="choose_slider">
					<?php foreach ($sliders as $key => $slider) { ?>
						<option value="<?php echo $slider->id; ?>"><?php echo $slider->name; ?></option>
					<?php } ?>
					</select>
					<p class="description"><?php _e('Select slider to apply.', WDS()->prefix); ?></p>
				  </td>
				</tr>
				<tr class="wds_template_class">
				  <td><label class="spider_label"></label></td>
				  <td align="right"><input class="wds_check" type="checkbox" value="1" /></td>
				</tr>
				<tr>
				  <td colspan="2" align="right">
					<br>
					<input type="button" class="button button-primary" onclick="spider_set_input_value('task', 'change_layer_options'); wds_checked_options(event);" value="<?php _e('Apply', WDS()->prefix); ?>" />
					<input type="button" class="button" onclick="jQuery('.wds_set').hide(); jQuery('.wds_opacity_set').hide(); return false;" value="<?php _e('Cancel', WDS()->prefix); ?>" />
				  </td>
				</tr>
				<tbody>
			</table>
		</div>		
		<input id="task" name="task" type="hidden" value="" />
		</form>
	</div>
    <?php
  }
}
