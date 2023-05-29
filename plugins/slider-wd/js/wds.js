jQuery(document).ready(function () {
  jQuery(".wds_form .colspanchange").attr("colspan", jQuery(".wds_form table>thead>tr>th").length);
  jQuery(".wds_requried").each(function () {
    jQuery(this).on("keypress", function () {
      jQuery(this).removeAttr("style");
    });
  });
  setDataSlideElement();
  setDataFormElement();

  hide_dimmension_ratio();

  image_for_play_pause_butt();
  image_for_next_prev_butt();
  image_for_bull_butt();

  wds_whr('height');

  /* Ask a question menu link target _blank */
  jQuery('#wds_ask_question').parent().attr('target','_blank');
});

jQuery(window).on('load', function () {
  /* For "Add posts" iframe. */
  jQuery(".wds_category_name").change(function () {
    jQuery("#page_number").val(1);
    jQuery("#search_or_not").val("search");
    jQuery("#posts_form").submit();
  });
});

/**
 * Set attribute and class all form elements
 */
function setDataFormElement() {
  jQuery(".wds-check-change_form :input:not([type=hidden]):not([readonly=readonly])").each(function(){ /* Set attr and class to each input of class */
    jQuery(this).attr("data-initial-value",jQuery(this).val());
    jQuery(this).addClass('wds-check-change');
  });
}

/**
 * Set and get data of slider
 */
function setDataSlideElement() {
  jQuery(".tab_image").each(function() {
    jQuery(this).attr("data-initial-image",jQuery(this).attr("style")); /* Set attribute all slides */
  });
  slideSubtabCount = jQuery(".wds_tabs  .wds_subtab_wrap").length;     /* Check slides count */
  layerCount = jQuery(".wds_slides_box  .wds_box table>tbody").length; /* Check layers count */
}

/**
 * Check if form is changed but not saved.
 */
jQuery(window).on('beforeunload', function() {
  slide_changed = !SlideManageChanges(true);
  if (slide_changed) {
    return 'Changes you made may not be saved.';
  }
});

/**
 * Check changes in form.
 *
 * @param check_for_changes
 * @returns {boolean}
 */
function SlideManageChanges( wds_check_for_changes ) {
  if ( wds_check_for_changes == undefined ) {
    wds_check_for_changes = false;
    jQuery(window).off('beforeunload');
  }
  slide_changed = false;

  if ( wds_check_for_changes ) {
    jQuery('.wds-check-change').each(function () {
      if ( jQuery(this).val() != jQuery(this).attr('data-initial-value') && jQuery(this).attr('name') != 'ratio' ) {
        slide_changed = true;
      }
    });
    if ( slideSubtabCount != jQuery(".wds_tabs  .wds_subtab_wrap").length ){ /* check count of slides */
      slide_changed = true;
    }
    else if ( layerCount != jQuery(".wds_slides_box  .wds_box table>tbody").length ) { /* check count of layers */
      slide_changed = true;
    }
    else {
      jQuery(".tab_image").each(function () {  /* Check slide image change */
        if ( jQuery(this).attr("data-initial-image") != jQuery(this).attr("style") ) {
          slide_changed = true;
        }
      })
    }
  }
  return !wds_check_for_changes || !slide_changed;
}

function wds_spider_ajax_save(form_id, event) {
  if (!wds_check_required()) {
    return false;
  }
  if (!validate_audio()) {
    return false;
  }
  var task = jQuery("#task").val();

  /* Loading.*/
  jQuery(".spider_load").show();
  set_ffamily_value();
  var post_data = {};
  post_data["task"] = "apply";
  if ( task == "reset" || task == "set_watermark" || task == "reset_watermark" ) {
    post_data["task"] = task;
  }

  /* Global.*/
  post_data["current_id"] = jQuery("#current_id").val();
  post_data["save_as_copy"] = jQuery("#save_as_copy").val();
  post_data["nonce_wd"] = jQuery("#nonce_wd").val();
  post_data["nav_tab"] = jQuery("#nav_tab").val();
  post_data["tab"] = jQuery("#tab").val();
  post_data["sub_tab"] = jQuery("#sub_tab").val();
  
  var slider_data = {};  
  slider_data["slide_ids_string"] = jQuery("#slide_ids_string").val();
  slider_data["del_slide_ids_string"] = jQuery("#del_slide_ids_string").val();
  slider_data["name"] = jQuery("#name").val();
  slider_data["width"] = jQuery("#width").val();
  slider_data["height"] = jQuery("#height").val();
  slider_data["full_width"] = jQuery("input[name=full_width]:checked").val();
  slider_data["auto_height"] = jQuery("input[name=auto_height]:checked").val();
  slider_data["align"] = jQuery("#align").val();
  slider_data["effect"] = jQuery("#effect").val();
  slider_data["time_intervval"] = jQuery("#time_intervval").val();
  slider_data["autoplay"] = jQuery("input[name=autoplay]:checked").val();
  slider_data["stop_animation"] = jQuery("input[name=stop_animation]:checked").val();
  slider_data["shuffle"] = jQuery("input[name=shuffle]:checked").val();
  slider_data["music"] = jQuery("input[name=music]:checked").val();
  slider_data["music_url"] = jQuery("#music_url").val();
  slider_data["preload_images"] = jQuery("input[name=preload_images]:checked").val();
  slider_data["background_color"] = jQuery("#background_color").val();
  slider_data["background_transparent"] = jQuery("#background_transparent").val();
  slider_data["glb_border_width"] = jQuery("#glb_border_width").val();
  slider_data["glb_border_style"] = jQuery("#glb_border_style").val();
  slider_data["glb_border_color"] = jQuery("#glb_border_color").val();
  slider_data["glb_border_radius"] = jQuery("#glb_border_radius").val();
  slider_data["glb_margin"] = jQuery("#glb_margin").val();
  slider_data["glb_box_shadow"] = jQuery("#glb_box_shadow").val();
  slider_data["image_right_click"] = jQuery("input[name=image_right_click]:checked").val();
  slider_data["layer_out_next"] = jQuery("input[name=layer_out_next]:checked").val();
  slider_data["published"] = jQuery("input[name=published]:checked").val();
  slider_data["start_slide_num"] = jQuery("#start_slide_num").val();
  slider_data["effect_duration"] = jQuery("#effect_duration").val();
  slider_data["parallax_effect"] = jQuery("input[name=parallax_effect]:checked").val();
  slider_data["carousel"] = jQuery("input[name=carousel]:checked").val();
  slider_data["carousel_image_counts"] = jQuery("#carousel_image_counts").val();
  slider_data["carousel_image_parameters"] = jQuery("#carousel_image_parameters").val();
  slider_data["carousel_fit_containerWidth"] = jQuery("input[name=carousel_fit_containerWidth]:checked").val();
  slider_data["carousel_width"] = jQuery("#carousel_width").val();
  slider_data["carousel_degree"] = jQuery("#carousel_degree").val();
  slider_data["carousel_grayscale"] = jQuery("#carousel_grayscale").val();
  slider_data["carousel_transparency"] = jQuery("#carousel_transparency").val();
  slider_data["slider_loop"] = jQuery("input[name=slider_loop]:checked").val();
  slider_data["hide_on_mobile"] = jQuery("#hide_on_mobile").val();
  slider_data["twoway_slideshow"] = jQuery("input[name=twoway_slideshow]:checked").val();
  slider_data["full_width_for_mobile"] = jQuery("#full_width_for_mobile").val();
  slider_data["order_dir"] = jQuery("input[name=order_dir]:checked").val();

  /* Navigation.*/
  slider_data["prev_next_butt"] = jQuery("input[name=prev_next_butt]:checked").val();
  slider_data["play_paus_butt"] = jQuery("input[name=play_paus_butt]:checked").val();
  slider_data["navigation"] = jQuery("input[name=navigation]:checked").val();
  slider_data["rl_butt_img_or_not"] = jQuery("input[name=rl_butt_img_or_not]:checked").val();
  slider_data["rl_butt_style"] = jQuery("#rl_butt_style").val();
  slider_data["right_butt_url"] = jQuery("#right_butt_url").val();
  slider_data["left_butt_url"] = jQuery("#left_butt_url").val();
  slider_data["right_butt_hov_url"] = jQuery("#right_butt_hov_url").val();
  slider_data["left_butt_hov_url"] = jQuery("#left_butt_hov_url").val();
  slider_data["rl_butt_size"] = jQuery("#rl_butt_size").val();
  slider_data["pp_butt_size"] = jQuery("#pp_butt_size").val();
  slider_data["butts_color"] = jQuery("#butts_color").val();
  slider_data["hover_color"] = jQuery("#hover_color").val();
  slider_data["nav_border_width"] = jQuery("#nav_border_width").val();
  slider_data["nav_border_style"] = jQuery("#nav_border_style").val();
  slider_data["nav_border_color"] = jQuery("#nav_border_color").val();
  slider_data["nav_border_radius"] = jQuery("#nav_border_radius").val();
  slider_data["nav_bg_color"] = jQuery("#nav_bg_color").val();
  slider_data["butts_transparent"] = jQuery("#butts_transparent").val();
  slider_data["play_paus_butt_img_or_not"] = jQuery("input[name=play_paus_butt_img_or_not]:checked").val();
  slider_data["play_butt_url"] = jQuery("#play_butt_url").val();
  slider_data["play_butt_hov_url"] = jQuery("#play_butt_hov_url").val();
  slider_data["paus_butt_url"] = jQuery("#paus_butt_url").val();
  slider_data["paus_butt_hov_url"] = jQuery("#paus_butt_hov_url").val();

  /* Bullets.*/
  slider_data["enable_bullets"] = jQuery("input[name=enable_bullets]:checked").val();
  slider_data["bull_position"] = jQuery("#bull_position").val();
  slider_data["bull_style"] = jQuery("#bull_style").val();
  slider_data["bullets_img_main_url"] = jQuery("#bullets_img_main_url").val();
  slider_data["bullets_img_hov_url"] = jQuery("#bullets_img_hov_url").val();
  slider_data["bull_butt_img_or_not"] = jQuery("input[name=bull_butt_img_or_not]:checked").val();
  slider_data["bull_size"] = jQuery("#bull_size").val();
  slider_data["bull_color"] = jQuery("#bull_color").val();
  slider_data["bull_act_color"] = jQuery("#bull_act_color").val();
  slider_data["bull_margin"] = jQuery("#bull_margin").val();

  /* Filmstrip.*/
  slider_data["enable_filmstrip"] = jQuery("input[name=enable_filmstrip]:checked").val();
  slider_data["film_small_screen"] = jQuery("#film_small_screen").val();
  slider_data["film_pos"] = jQuery("#film_pos").val();
  slider_data["film_thumb_width"] = jQuery("#film_thumb_width").val();
  slider_data["film_thumb_height"] = jQuery("#film_thumb_height").val();
  slider_data["film_bg_color"] = jQuery("#film_bg_color").val();
  slider_data["film_tmb_margin"] = jQuery("#film_tmb_margin").val();
  slider_data["film_act_border_width"] = jQuery("#film_act_border_width").val();
  slider_data["film_act_border_style"] = jQuery("#film_act_border_style").val();
  slider_data["film_act_border_color"] = jQuery("#film_act_border_color").val();
  slider_data["film_dac_transparent"] = jQuery("#film_dac_transparent").val();

  /* Timer bar.*/
  slider_data["enable_time_bar"] = jQuery("input[name=enable_time_bar]:checked").val();
  slider_data["timer_bar_type"] = jQuery("#timer_bar_type").val();
  slider_data["timer_bar_size"] = jQuery("#timer_bar_size").val();
  slider_data["timer_bar_color"] = jQuery("#timer_bar_color").val();
  slider_data["timer_bar_transparent"] = jQuery("#timer_bar_transparent").val();

  /* Watermark.*/
  slider_data["built_in_watermark_type"] = jQuery("input[name=built_in_watermark_type]:checked").val();
  slider_data["built_in_watermark_text"] = jQuery("#built_in_watermark_text").val();
  slider_data["built_in_watermark_font_size"] = jQuery("#built_in_watermark_font_size").val();
  slider_data["built_in_watermark_font"] = jQuery("#built_in_watermark_font").val();
  slider_data["built_in_watermark_color"] = jQuery("#built_in_watermark_color").val();
  slider_data["built_in_watermark_opacity"] = jQuery("#built_in_watermark_opacity").val();
  slider_data["built_in_watermark_position"] = jQuery("input[name=built_in_watermark_position]:checked").val();
  slider_data["built_in_watermark_url"] = jQuery("#built_in_watermark_url").val();
  slider_data["built_in_watermark_size"] = jQuery("#built_in_watermark_size").val();

  slider_data["mouse_swipe_nav"] = jQuery("input[name=mouse_swipe_nav]:checked").val();
  slider_data["bull_hover"] = jQuery("input[name=bull_hover]:checked").val();
  slider_data["touch_swipe_nav"] = jQuery("input[name=touch_swipe_nav]:checked").val();
  slider_data["mouse_wheel_nav"] = jQuery("input[name=mouse_wheel_nav]:checked").val();
  slider_data["keyboard_nav"] = jQuery("input[name=keyboard_nav]:checked").val();
  slider_data["possib_add_ffamily"] = jQuery("#possib_add_ffamily").val();
  slider_data["show_thumbnail"] = jQuery("input[name=show_thumbnail]:checked").val();
  slider_data["thumb_size"] = jQuery("input[name=wds_thumb_size]").val();
  slider_data["fixed_bg"] = jQuery("input[name=fixed_bg]:checked").val();
  slider_data["smart_crop"] = jQuery("input[name=smart_crop]:checked").val();
  slider_data["crop_image_position"] = jQuery("input[name=crop_image_position]:checked").val();
  slider_data["possib_add_google_fonts"] = jQuery("input[name=possib_add_google_fonts]:checked").val();
  slider_data["possib_add_ffamily_google"] = jQuery("#possib_add_ffamily_google").val();
  /* Css.*/
  slider_data["css"] = jQuery("#css").val();
  /* Javascript */
  var js_textarea_val = {};
  jQuery(".callbeck-textarea").each(function(index,element){
	  js_textarea_val[jQuery(element).attr("name")] = jQuery(element).val();
  });
  slider_data["javascript"] = JSON.stringify(js_textarea_val);
  slider_data["bull_back_act_color"] = jQuery("#bull_back_act_color").val();
  slider_data["bull_back_color"] = jQuery("#bull_back_color").val();
  slider_data["bull_radius"] = jQuery("#bull_radius").val();

  if (task != "reset") {
    post_data["slider_data"] = JSON.stringify(slider_data);
    post_data["slides"] = new Array();
    var wds_slide_ids = jQuery("#slide_ids_string").val();
    var slide_ids_array = wds_slide_ids.split(",");
    for (var i in slide_ids_array) {
      if (slide_ids_array.hasOwnProperty(i) && slide_ids_array[i] && slide_ids_array[i] != ",") {
        var slide_id = slide_ids_array[i];
        var slide_data = {};
        slide_data["id"] = slide_id;
        slide_data["title" + slide_id] = jQuery("#title" + slide_id).val();
        slide_data["order" + slide_id] = jQuery("#order" + slide_id).val();
        slide_data["published" + slide_id] = jQuery("input[name=published" + slide_id + "]:checked").val();
        slide_data["link" + slide_id] = jQuery("#link" + slide_id).val();
        slide_data["target_attr_slide" + slide_id] = jQuery("input[name=target_attr_slide" + slide_id + " ]:checked").val();
        slide_data["type" + slide_id] = jQuery("#type" + slide_id).val();
        slide_data["image_url" + slide_id] = jQuery("#image_url" + slide_id).val();
        slide_data["thumb_url" + slide_id] = jQuery("#thumb_url" + slide_id).val();
        slide_data["wds_video_type" + slide_id] = jQuery("#wds_video_type" + slide_id).val();
        var layer_ids_string = jQuery("#slide" + slide_id + "_layer_ids_string").val();
        slide_data["slide" + slide_id + "_layer_ids_string"] = layer_ids_string;
        slide_data["slide" + slide_id + "_del_layer_ids_string"] = jQuery("#slide" + slide_id + "_del_layer_ids_string").val();
        slide_data["att_width" + slide_id] = jQuery("#att_width" + slide_id).val();
        slide_data["att_height" + slide_id] = jQuery("#att_height" + slide_id).val();
        slide_data["video_duration" + slide_id] = jQuery("#video_duration" + slide_id).val();
        slide_data["video_loop" + slide_id] = jQuery("input[name=video_loop" + slide_id + " ]:checked").val();
        slide_data["mute" + slide_id] = jQuery("input[name=mute" + slide_id + " ]:checked").val();
        slide_data["fillmode" + slide_id] = jQuery("input[name=fillmode" + slide_id +" ]").val();
        if (slide_data["type" + slide_id] == 'video') {
          slide_data["link" + slide_id] = jQuery("input[name=controls" + slide_id + " ]:checked").val();
          slide_data["thumb_url" + slide_id] = jQuery("#post_id" + slide_id).val();
          slide_data["target_attr_slide" + slide_id] = jQuery("input[name=wds_slide_autoplay" + slide_id + " ]:checked").val();
        }
        if (slide_data["type" + slide_id] == 'EMBED_OEMBED_YOUTUBE_VIDEO' || slide_data["type" + slide_id] == 'EMBED_OEMBED_VIMEO_VIDEO') {
          slide_data["youtube_rel_video" + slide_id] = jQuery("input[name=youtube_rel_video" + slide_id + "]:checked").val();
          slide_data["target_attr_slide" + slide_id] = jQuery("input[name=wds_slide_autoplay" + slide_id + " ]:checked").val();
        }
        if (layer_ids_string) {
          var layer_ids_array = layer_ids_string.split(",");
          for (var i in layer_ids_array) {
            if (layer_ids_array.hasOwnProperty(i) && layer_ids_array[i] && layer_ids_array[i] != ",") {
              var json_data = {};
              var layer_id = layer_ids_array[i];
              var prefix = "slide" + slide_id + "_layer" + layer_id;
              var type = jQuery("#" + prefix + "_type").val();
              json_data["type"] = type;
              json_data["title"] = jQuery("#" + prefix + "_title").val();
              json_data["depth"] = jQuery("#" + prefix + "_depth").val();
              json_data["static_layer"] = jQuery("input[name=" + prefix + "_static_layer]:checked").val();
              json_data["infinite_in"] = jQuery("input[name=" + prefix + "_infinite_in]").val();
              json_data["infinite_out"] = jQuery("input[name=" + prefix + "_infinite_out]").val();
              json_data["hide_on_mobile"] = jQuery("#" + prefix + "_hide_on_mobile").val();
              switch (type) {
                case "text":
                {
                  json_data["text"] = jQuery("#" + prefix + "_text").val().replace(/[\\"]/g, '\\$&').replace(/\u0000/g, '\\0');
                  json_data["image_width"] = jQuery("#" + prefix + "_image_width").val();
                  json_data["image_height"] = jQuery("#" + prefix + "_image_height").val();
                  json_data["image_scale"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_image_scale]:checked").val();
                  json_data["size"] = jQuery("#" + prefix + "_size").val();
                  json_data["color"] = jQuery("#" + prefix + "_color").val();
                  json_data["ffamily"] = jQuery("#" + prefix + "_ffamily").val();
                  json_data["google_fonts"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_google_fonts]:checked").val();
                  json_data["fweight"] = jQuery("#" + prefix + "_fweight").val();
                  json_data["link"] = jQuery("#" + prefix + "_link").val();
                  json_data["target_attr_layer"] = jQuery("input[name=" + prefix + "_target_attr_layer]:checked").val();
                  json_data["padding"] = jQuery("#" + prefix + "_padding").val();
                  json_data["fbgcolor"] = jQuery("#" + prefix + "_fbgcolor").val();
                  json_data["transparent"] = jQuery("#" + prefix + "_transparent").val();
                  json_data["border_width"] = jQuery("#" + prefix + "_border_width").val();
                  json_data["border_style"] = jQuery("#" + prefix + "_border_style").val();
                  json_data["border_color"] = jQuery("#" + prefix + "_border_color").val();
                  json_data["border_radius"] = jQuery("#" + prefix + "_border_radius").val();
                  json_data["shadow"] = jQuery("#" + prefix + "_shadow").val();
                  json_data["add_class"] = jQuery("#" + prefix + "_add_class").val();
                  json_data["hover_color_text"] = jQuery("#" + prefix + "_hover_color_text").val();
                  json_data["text_alignment"] = jQuery("#" + prefix + "_text_alignment").val();
                  json_data["layer_callback_list"] = jQuery("#" + prefix + "_layer_callback_list").val();
                  json_data["link_to_slide"] = jQuery("#" + prefix + "_link_to_slide").val();
                  json_data["align_layer"] = jQuery("input[name=" + prefix + "_align_layer]:checked").val();
                  json_data["min_size"] = jQuery("#" + prefix + "_min_size").val();
                  break;
                }
                case "image":
                {
                  json_data["image_url"] = jQuery("#" + prefix + "_image_url").val();
                  json_data["image_width"] = jQuery("#" + prefix + "_image_width").val();
                  json_data["image_height"] = jQuery("#" + prefix + "_image_height").val();
                  json_data["image_scale"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_image_scale]:checked").val();
                  json_data["alt"] = jQuery("#" + prefix + "_alt").val();
                  json_data["link"] = jQuery("#" + prefix + "_link").val();
                  json_data["target_attr_layer"] = jQuery("input[name=" + prefix + "_target_attr_layer]:checked").val();
                  json_data["imgtransparent"] = jQuery("#" + prefix + "_imgtransparent").val();
                  json_data["border_width"] = jQuery("#" + prefix + "_border_width").val();
                  json_data["border_style"] = jQuery("#" + prefix + "_border_style").val();
                  json_data["border_color"] = jQuery("#" + prefix + "_border_color").val();
                  json_data["border_radius"] = jQuery("#" + prefix + "_border_radius").val();
                  json_data["shadow"] = jQuery("#" + prefix + "_shadow").val();
                  json_data["add_class"] = jQuery("#" + prefix + "_add_class").val();
                  json_data["layer_callback_list"] = jQuery("#" + prefix + "_layer_callback_list").val();
                  json_data["link_to_slide"] = jQuery("#" + prefix + "_link_to_slide").val();
                  break;
                }
                case "video":
                case "upvideo":
                {
                  json_data["image_url"] = jQuery("#" + prefix + "_image_url").val();
                  if (type == 'upvideo') {
                    json_data["image_url"] = jQuery("#" + prefix + "_layer_post_id").val();
                  }
                  json_data["image_width"] = jQuery("#" + prefix + "_image_width").val();
                  json_data["image_height"] = jQuery("#" + prefix + "_image_height").val();
                  json_data["attr_width"] = jQuery("#" + prefix + "_attr_width").val();
                  json_data["attr_height"] = jQuery("#" + prefix + "_attr_height").val();
                  json_data["image_scale"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_image_scale]:checked").val();
                  json_data["target_attr_layer"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_controls]:checked").val();
                  json_data["link"] = jQuery("#" + prefix + "_link").val();
                  json_data["alt"] = jQuery("#" + prefix + "_alt").val();
                  json_data["border_width"] = jQuery("#" + prefix + "_border_width").val();
                  json_data["border_style"] = jQuery("#" + prefix + "_border_style").val();
                  json_data["border_color"] = jQuery("#" + prefix + "_border_color").val();
                  json_data["border_radius"] = jQuery("#" + prefix + "_border_radius").val();
                  json_data["shadow"] = jQuery("#" + prefix + "_shadow").val();
                  json_data["add_class"] = jQuery("#" + prefix + "_add_class").val();
                  json_data["layer_video_loop"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_layer_video_loop]:checked").val();
                  json_data["youtube_rel_layer_video"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_youtube_rel_layer_video]:checked").val();
                  break;
                }
                case "social":
                {
                  json_data["social_button"] = jQuery("#" + prefix + "_social_button").val();
                  json_data["size"] = jQuery("#" + prefix + "_size").val();
                  json_data["transparent"] = jQuery("#" + prefix + "_transparent").val();
                  json_data["color"] = jQuery("#" + prefix + "_color").val();
                  json_data["hover_color"] = jQuery("#" + prefix + "_hover_color").val();
                  json_data["add_class"] = jQuery("#" + prefix + "_add_class").val();
                  break;
                }
                case "hotspots":
                {
                  json_data["text"] = jQuery("#" + prefix + "_text").val();
                  json_data["image_width"] = jQuery("#" + prefix + "_image_width").val();
                  json_data["image_height"] = jQuery("#" + prefix + "_image_height").val();
                  json_data["image_scale"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_image_scale]:checked").val();
                  json_data["size"] = jQuery("#" + prefix + "_size").val();
                  json_data["color"] = jQuery("#" + prefix + "_color").val();
                  json_data["ffamily"] = jQuery("#" + prefix + "_ffamily").val();
                  json_data["google_fonts"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_google_fonts]:checked").val();
                  json_data["fweight"] = jQuery("#" + prefix + "_fweight").val();
                  json_data["link"] = jQuery("#" + prefix + "_link").val();
                  json_data["target_attr_layer"] = jQuery("input[name=" + prefix + "_target_attr_layer]:checked").val();
                  json_data["padding"] = jQuery("#" + prefix + "_padding").val();
                  json_data["fbgcolor"] = jQuery("#" + prefix + "_fbgcolor").val();
                  json_data["transparent"] = jQuery("#" + prefix + "_transparent").val();
                  json_data["border_width"] = jQuery("#" + prefix + "_border_width").val();
                  json_data["border_style"] = jQuery("#" + prefix + "_border_style").val();
                  json_data["border_color"] = jQuery("#" + prefix + "_border_color").val();
                  json_data["border_radius"] = jQuery("#" + prefix + "_border_radius").val();
                  json_data["shadow"] = jQuery("#" + prefix + "_shadow").val();
                  json_data["left"] = jQuery("#" + prefix + "_div_left").val();
                  json_data["top"] = jQuery("#" + prefix + "_div_top").val();
                  json_data["hotp_width"] = jQuery("#" + prefix + "_hotp_width").val();
                  json_data["hotp_fbgcolor"] = jQuery("#" + prefix + "_hotp_fbgcolor").val();
                  json_data["hotp_border_width"] = jQuery("#" + prefix + "_round_hotp_border_width").val();
                  json_data["hotp_border_style"] = jQuery("#" + prefix + "_round_hotp_border_style").val();
                  json_data["hotp_border_color"] = jQuery("#" + prefix + "_hotp_border_color").val();
                  json_data["hotp_border_radius"] = jQuery("#" + prefix + "_hotp_border_radius").val();
                  json_data["hotp_text_position"] = jQuery("#" + prefix + "_htextposition").val();
                  json_data["add_class"] = jQuery("#" + prefix + "_add_class").val();
                  json_data["hotspot_animation"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_hotspot_animation]:checked").val();
                  json_data["layer_callback_list"] = jQuery("#" + prefix + "_layer_callback_list").val();
                  json_data["hotspot_text_display"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_hotspot_text_display]:checked").val();
                  json_data["text_alignment"] = jQuery("#" + prefix + "_text_alignment").val();
                  json_data["link_to_slide"] = jQuery("#" + prefix + "_link_to_slide").val();
                  json_data["min_size"] = jQuery("#" + prefix + "_min_size").val();
                  break;
                }
                default:
                  break;
              }
              if (typeof(jQuery("#" + prefix + "_left").val()) != "undefined") {
                json_data["left"] = jQuery("#" + prefix + "_left").val();
              }
              if (typeof(jQuery("#" + prefix + "_top").val()) != "undefined") {
                json_data["top"] = jQuery("#" + prefix + "_top").val();
              }
              json_data["published"] = jQuery("input[name=slide" + slide_id + "_layer" + layer_id + "_published]:checked").val();
              json_data["start"] = jQuery("#" + prefix + "_start").val();
              json_data["layer_effect_in"] = jQuery("#" + prefix + "_layer_effect_in").val();
              json_data["duration_eff_in"] = jQuery("#" + prefix + "_duration_eff_in").val();
              json_data["end"] = jQuery("#" + prefix + "_end").val();
              json_data["layer_effect_out"] = jQuery("#" + prefix + "_layer_effect_out").val();
              json_data["duration_eff_out"] = jQuery("#" + prefix + "_duration_eff_out").val();
              slide_data[prefix + "_json"] = JSON.stringify(json_data);
              json_data = null;
            }
          }
        }
        post_data["slides"].splice(post_data["slides"].length, 0, JSON.stringify(slide_data));
      }
    }
  }
  jQuery.post(
    jQuery('#' + form_id).attr("action"),
    post_data,
    function (data) {
      var content = jQuery(data).find(".wds_nav_global_box").parent();
      var str = content.html();
      jQuery(".wds_nav_global_box").parent().html(str);

      if ( task != "reset" ) {
        var str = jQuery(data).find(".wds_task_cont").html();
        jQuery(".wds_task_cont").html(str);
      }

      var str = jQuery(data).find(".wds_buttons").html();
      jQuery(".wds_buttons").html(str);

      var content = jQuery(data).find(".wds_slides_box");
      var str = content.html();
      jQuery(".wds_slides_box").html(str);

      var post_btn_href = jQuery(data).find("#wds_posts_btn").attr("href");
      jQuery("#wds_posts_btn").attr("href", post_btn_href);
    }
  ).success(function (data, textStatus, errorThrown) {
    wds_success(form_id);
    setDataSlideElement();
    setDataFormElement();
    hide_dimmension_ratio();

    image_for_play_pause_butt();
    image_for_next_prev_butt();
    image_for_bull_butt();

	  showHowToTabBlock();
  });
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

function wds_add_post(ids_string, single) {
  var ids_array = ids_string.split(",");
  /* Delete active slide if it has no image.*/
  if (!single) {
    window.parent.jQuery(".wds_box input[id^='image_url']").each(function () {
      var slide_id = window.parent.jQuery(this).attr("id").replace("image_url", "");
      if (!window.parent.jQuery("#image_url" + slide_id).val() && !window.parent.jQuery("#slide" + slide_id + "_layer_ids_string").val()) {
        window.parent.wds_remove_slide(slide_id, 0);
      }
    });
  }
  else {
    var slideID = jQuery("#slide_id").val();
  }
  for (var i in ids_array) {
    if (ids_array.hasOwnProperty(i) && ids_array[i]) {
      var id = ids_array[i];
      if (jQuery("#check_" + id).prop('checked')) {
        if (typeof window.parent.wp.media.frames.file_frame.options.id == "undefined") {
          var slideID = window.parent.wds_add_slide();
        }
        else {
          var slideID = window.parent.wp.media.frames.file_frame.options.id;
        }
        window.parent.jQuery("#title" + slideID).val(jQuery("#wds_title_" + id).val());
        window.parent.jQuery("#type" + slideID).val("image");
        window.parent.jQuery("#image_url" + slideID).val(jQuery("#wds_image_url_" + id).val());
        window.parent.jQuery("#thumb_url" + slideID).val(jQuery("#wds_thumb_url_" + id).val());
        window.parent.jQuery("#wds_preview_image" + slideID).css({backgroundImage: "url('" + jQuery("#wds_image_url_" + id).val() + "')"});
        window.parent.jQuery("#wds_tab_image" + slideID).css({backgroundImage: "url('" + jQuery("#wds_image_url_" + id).val() + "')"});
        window.parent.jQuery("#wds_tab_image" + slideID).css('background-position', 'center');
        var layerID = window.parent.wds_add_layer('text', slideID);
        var prefix = 'slide' + slideID + '_layer' + layerID;
        window.parent.jQuery("#" + prefix + "_text").html(jQuery("#wds_content_" + id).val());
        window.parent.jQuery("#" + prefix + "_link").val(jQuery("#wds_link_" + id).val());
        window.parent.jQuery("#" + prefix).html(jQuery("#wds_content_" + id).val());
        window.parent.wds_new_line(prefix);
        window.parent.jQuery("#trlink" + slideID).show();
        window.parent.jQuery("#controls" + slideID).hide();
        window.parent.jQuery("#autoplay" + slideID).hide();
        window.parent.jQuery("#video_loop" + slideID).hide();
        window.parent.jQuery("#mute" + slideID).hide();
      }
    }
  }
  window.parent.tb_remove();
}

function wds_success(form_id) {
  jQuery("#" + form_id).parent().find(".spider_message").remove();
  var task = jQuery("#task").val();
  var message;

  switch (task) {
    case "set_watermark": {
      /* Reload images to prevent load from cache. */
      jQuery(".wds_preview").find("div[class^='wds_preview_image']").each(function() {
        var image = jQuery(this).css("background-image");
        jQuery(this).css({backgroundImage: image.replace('")', (image.indexOf("?") === -1 ? '?' : '') + Math.floor((Math.random() * 100) + 1) + '")')});
      });
      if (jQuery("input[name=built_in_watermark_type]:checked").val() == 'none') {
        message = "<div class='wd_error'><strong><p>"+ wds_object.translate.you_must_set_watermark_type +"</p></strong></div>";
      }
      else {
        message = "<div class='wd_updated'><strong><p>"+ wds_object.translate.watermark_succesfully_set +"</p></strong></div>";
      }
      break;
    }
    case "reset_watermark": {
      /* Reload images to prevent load from cache. */
      jQuery(".wds_preview").find("div[class^='wds_preview_image']").each(function() {
        var image = jQuery(this).css("background-image");
        jQuery(this).css({backgroundImage: image.replace('")', (image.indexOf("?") === -1 ? '?' : '') + Math.floor((Math.random() * 100) + 1) + '")')});
      });
      message = "<div class='wd_updated'><strong><p>"+ wds_object.translate.watermark_succesfully_reset +"</p></strong></div>";
      break;
    }
    case "reset": {
      message = "<div class='wd_error'><strong><p>"+ wds_object.translate.Changes_must_be_saved +"</p></strong></div>";
      window.scrollTo(0,0);
      break;
    }
    default: {
      message = "<div class='wd_updated'><strong><p>"+ wds_object.translate.items_succesfully_saved +"</p></strong></div>";
      break;
    }
  }

  /* Loading.*/
  jQuery(".spider_load").hide();
  if (message) {
    jQuery(".spider_message_cont").html(message);
    jQuery(".spider_message_cont").show();
  }
  wds_onload();
  jscolor.bind();
}


function wds_onload() {
  var type_key;
  var color_key;
  var bull_type_key;
  var bull_color_key;
  jQuery(".wds_tabs").show();
  var nav_tab = jQuery("#nav_tab").val();
  wds_change_nav(jQuery(".wds_nav_tabs li[tab_type='" + nav_tab + "']"), 'wds_nav_' + nav_tab + '_box');
  var tab = jQuery("#tab").val();
  wds_change_tab(jQuery("." + tab  + "_tab_button_wrap"), 'wds_' + tab + '_box');
  wds_built_in_watermark("watermark_type_" + jQuery("input[name=built_in_watermark_type]:checked").val());
  preview_built_in_watermark();
  wds_slide_weights();
  if (jQuery("#music1").is(":checked")) {
    wds_enable_disable('', 'tr_music_url', 'music1');
  }
  else {
    wds_enable_disable('none', 'tr_music_url', 'music0');
  }
  if (jQuery("#show_thumbnail1").is(":checked")) {
    wds_enable_disable('', 'tr_thumb_size', 'show_thumbnail1');
  }
  else {
    wds_enable_disable('none', 'tr_thumb_size', 'show_thumbnail0');
  }
  jQuery('#tr_smart_crop').show();
  if (jQuery("#smart_crop1").is(":checked")) {
    wds_enable_disable('', 'tr_crop_pos', 'smart_crop1');
  }
  else {
    wds_enable_disable('none', 'tr_crop_pos', 'smart_crop0');
  }

  jQuery('.wds_rl_butt_groups').each(function(i) {
    var type_key = jQuery(this).attr('value');
    if( typeof type_key !== 'undefined' && typeof wds_rl_butt_type[type_key] !== 'undefined' && typeof wds_rl_butt_type[type_key]['type_name'] !== 'undefined' ) {
      var src_top_left	= rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/1.png';
      var src_top_right	= rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/2.png';
      var src_bottom_left	= rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/3.png';
      var src_bottom_right	= rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/4.png';

      jQuery(this).find('.src_top_left').attr('src', src_top_left);
      jQuery(this).find('.src_top_right').attr('src', src_top_right);
      jQuery(this).find('.src_bottom_left').attr('src', src_bottom_left);
      jQuery(this).find('.src_bottom_right').attr('src', src_bottom_right);
    }
  });

  jQuery('.wds_rl_butt_col_groups').each(function(i) {
    var color_key = jQuery(this).attr('value');
    if ( typeof color_key !== 'undefined' && typeof wds_rl_butt_type[type_cur_fold] !== 'undefined' && typeof wds_rl_butt_type[type_cur_fold]['type_name'] !== 'undefined' && typeof wds_rl_butt_type[type_cur_fold][color_key] !== 'undefined' ) {
      src_col_top_left = rl_butt_dir + wds_rl_butt_type[type_cur_fold]["type_name"] + '/' + wds_rl_butt_type[type_cur_fold][color_key] + '/1.png';
      src_col_top_right = rl_butt_dir + wds_rl_butt_type[type_cur_fold]["type_name"] + '/' + wds_rl_butt_type[type_cur_fold][color_key] + '/2.png';
      src_col_bottom_left = rl_butt_dir + wds_rl_butt_type[type_cur_fold]["type_name"] + '/' + wds_rl_butt_type[type_cur_fold][color_key] + '/3.png';
      src_col_bottom_right = rl_butt_dir + wds_rl_butt_type[type_cur_fold]["type_name"] + '/' + wds_rl_butt_type[type_cur_fold][color_key] + '/4.png';
      jQuery(this).find('.src_col_top_left').attr('src', src_col_top_left);
      jQuery(this).find('.src_col_top_right').attr('src', src_col_top_right);
      jQuery(this).find('.src_col_bottom_left').attr('src', src_col_bottom_left);
      jQuery(this).find('.src_col_bottom_right').attr('src', src_col_bottom_right);
    }
  });

  jQuery('.wds_pp_butt_groups').each(function(i) {
    var pp_type_key = jQuery(this).attr('value');
    if( typeof pp_type_key !== 'undefined' && typeof wds_pp_butt_type[pp_type_key] !== 'undefined' && typeof wds_pp_butt_type[pp_type_key]['type_name'] !== 'undefined' ) {
      var pp_src_top_left = pp_butt_dir + wds_pp_butt_type[pp_type_key]["type_name"] + '/1/1.png';
      var pp_src_top_right = pp_butt_dir + wds_pp_butt_type[pp_type_key]["type_name"] + '/1/2.png';
      var pp_src_bottom_left = pp_butt_dir + wds_pp_butt_type[pp_type_key]["type_name"] + '/1/3.png';
      var pp_src_bottom_right = pp_butt_dir + wds_pp_butt_type[pp_type_key]["type_name"] + '/1/4.png';

      jQuery(this).find('.pp_src_top_left').attr('src', pp_src_top_left);
      jQuery(this).find('.pp_src_top_right').attr('src', pp_src_top_right);
      jQuery(this).find('.pp_src_bottom_left').attr('src', pp_src_bottom_left);
      jQuery(this).find('.pp_src_bottom_right').attr('src', pp_src_bottom_right);
    }
  });

  jQuery('.wds_pp_butt_col_groups').each(function(i) {
    var pp_color_key = jQuery(this).attr('value');
    if ( typeof pp_color_key !== 'undefined' && typeof wds_pp_butt_type[pp_type_cur_fold] !== 'undefined' && typeof wds_pp_butt_type[pp_type_cur_fold]['type_name'] !== 'undefined' && typeof wds_pp_butt_type[pp_type_cur_fold][pp_color_key] !== 'undefined' ) {
      var pp_src_col_top_left = pp_butt_dir + wds_pp_butt_type[pp_type_cur_fold]['type_name'] + '/' + wds_pp_butt_type[pp_type_cur_fold][pp_color_key] + '/1.png';
      var pp_src_col_top_right = pp_butt_dir + wds_pp_butt_type[pp_type_cur_fold]['type_name'] + '/' + wds_pp_butt_type[pp_type_cur_fold][pp_color_key] + '/2.png';
      var pp_src_col_bottom_left = pp_butt_dir + wds_pp_butt_type[pp_type_cur_fold]['type_name'] + '/' + wds_pp_butt_type[pp_type_cur_fold][pp_color_key] + '/3.png';
      var pp_src_col_bottom_right = pp_butt_dir + wds_pp_butt_type[pp_type_cur_fold]['type_name'] + '/' + wds_pp_butt_type[pp_type_cur_fold][pp_color_key] + '/4.png';
      jQuery(this).find('.pp_src_col_top_left').attr('src', pp_src_col_top_left);
      jQuery(this).find('.pp_src_col_top_right').attr('src', pp_src_col_top_right);
      jQuery(this).find('.pp_src_col_bottom_left').attr('src', pp_src_col_bottom_left);
      jQuery(this).find('.pp_src_col_bottom_right').attr('src', pp_src_col_bottom_right);
    }
  });

  jQuery('.wds_bull_butt_groups').each(function(i) {
    bull_type_key = jQuery(this).attr('value');
    if( typeof bull_type_key !== 'undefined' && typeof wds_blt_img_type[bull_type_key] !== 'undefined' && typeof wds_blt_img_type[bull_type_key]['type_name'] !== 'undefined' ) {
      bull_src_left = blt_img_dir + wds_blt_img_type[bull_type_key]["type_name"] + '/1/1.png';
      bull_src_right = blt_img_dir + wds_blt_img_type[bull_type_key]["type_name"] + '/1/2.png';
      jQuery(this).find('.bull_src_left').attr('src', bull_src_left);
      jQuery(this).find('.bull_src_right').attr('src', bull_src_right);
    }
  });

  jQuery('.wds_bull_butt_col_groups').each(function(i) {
    bull_color_key = jQuery(this).attr('value');
    if ( typeof bull_color_key !== 'undefined' && typeof wds_blt_img_type[bull_type_cur_fold] !== 'undefined' && typeof wds_blt_img_type[bull_type_cur_fold][bull_color_key] !== 'undefined' ) {
      bull_col_src_left = blt_img_dir + wds_blt_img_type[bull_type_cur_fold]["type_name"] + '/' + wds_blt_img_type[bull_type_cur_fold][bull_color_key] + '/1.png';
      bull_col_src_right = blt_img_dir + wds_blt_img_type[bull_type_cur_fold]["type_name"] + '/' + wds_blt_img_type[bull_type_cur_fold][bull_color_key] + '/2.png';
      jQuery(this).find('.bull_col_src_left').attr('src', bull_col_src_left);
      jQuery(this).find('.bull_col_src_right').attr('src', bull_col_src_right);
    }
  });

  if ( !wds_object.is_free ) {
    wds_display_hotspot();
    wds_hotspot_position();
  }

  /* Add events to slide tabs. */
  jQuery(".tab_image").on("click", function () {
    var slide_id = jQuery(this).data("id");
    wds_change_sub_tab(this, "wds_slide" + slide_id);
  });
  jQuery(".tab_image input").on("click", function (e) {
    e.stopPropagation();
  });
  jQuery(".tab_image .wds_tab_title").on("click", function () {
    var slide_id = jQuery(this).data("id");
    wds_change_sub_tab(jQuery("#wds_tab_image" + slide_id), "wds_slide" + slide_id);
    wds_change_sub_tab_title(this, "wds_slide" + slide_id);
  });

  /* Open/close section container on its header click. */
  jQuery(".hndle, .handlediv").each(function () {
    jQuery(this).on("click", function () {
      wds_toggle_postbox(this);
    });
  });

  /* Set preview container overflow width. */
  jQuery(".wds-preview-overflow").width(jQuery(".wd-slides-title").width());
}

function spider_select_value(obj) {
  obj.focus();
  obj.select();
}

function spider_run_checkbox() {
  jQuery("tbody").children().children(".check-column").find(":checkbox").click(function (l) {
    if ("undefined" == l.shiftKey) {
      return true
    }
    if (l.shiftKey) {
      if (!i) {
        return true
      }
      d = jQuery(i).closest("form").find(":checkbox");
      f = d.index(i);
      j = d.index(this);
      h = jQuery(this).prop("checked");
      if (0 < f && 0 < j && f != j) {
        d.slice(f, j).prop("checked", function () {
          if (jQuery(this).closest("tr").is(":visible")) {
            return h
          }
          return false
        })
      }
    }
    i = this;
    var k = jQuery(this).closest("tbody").find(":checkbox").filter(":visible").not(":checked");
    jQuery(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
      return(0 == k.length)
    });
    return true
  });
  jQuery("thead, tfoot").find(".check-column :checkbox").click(function (m) {
    var n = jQuery(this).prop("checked"), l = "undefined" == typeof toggleWithKeyboard ? false : toggleWithKeyboard, k = m.shiftKey || l;
    jQuery(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (jQuery(this).is(":hidden")) {
        return false
      }
      if (k) {
        return jQuery(this).prop("checked")
      } else {
        if (n) {
          return true
        }
      }
      return false
    });
    jQuery(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (k) {
        return false
      } else {
        if (n) {
          return true
        }
      }
      return false
    })
  });
}

/* Set value by id. */
function spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

/* Submit form by id. */
function spider_form_submit(event, form_id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

/* Check required fields. */
function wds_check_required() {
  var flag = true;
  jQuery(".wds_requried").each(function () {
    if (jQuery(this).val() == '') {
      alert(jQuery(this).data('name') + ' is required.');
      wds_change_tab(jQuery(".wds_tab_label[tab_type='slides']"), 'wds_slides_box');
      jQuery(this).css({borderColor: '#FF0000'});
      jQuery(this).focus();
      jQuery('html, body').animate({
        scrollTop:jQuery(this).offset().top - 200
      }, 500);
      flag = false;
      return;
    }
  });
  return flag;
}

/* Check audio file. */
function ValidAudioExtantion(url) {
  var ext = url.split('.').pop();
  switch (ext.toLowerCase()) {
    case 'aac':
    case 'm4a':
    case 'f4a':
    case 'mp3':
    case 'ogg':
    case 'oga':
      return true;
  }
  return false;
}

function ValidAudioURL(url) {
  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
  return regexp.test(url);
}

function validate_audio() {
  var url = jQuery("#music_url").val();
  if (jQuery("#music1").is(":checked")) {
    if (url == "") { /* check is url empty */
      jQuery("#music0").prop('checked', true);
      wds_enable_disable('none', 'tr_music_url', 'music0');
      return true;
    }
    else if (!ValidAudioExtantion(url)) /* check extention */
    {
      alert(wds_object.translate.insert_valid_audio_file);
      return false;
    }
    else if (!ValidAudioURL(url)) {  /* check url */
      alert(wds_object.translate.insert_valid_audio_file);
      return false;
    }
  }
  return true;
}

/* Show/hide order column and drag and drop column. */
function spider_show_hide_weights() {
  if (jQuery("#show_hide_weights").val() == 'Show order column') {
    jQuery(".connectedSortable").css("cursor", "default");
    jQuery("#tbody_arr").find(".handle").hide(0);
    jQuery("#th_order").show(0);
    jQuery("#tbody_arr").find(".spider_order").show(0);
    jQuery("#show_hide_weights").val("Hide order column");
    if (jQuery("#tbody_arr").sortable()) {
      jQuery("#tbody_arr").sortable("disable");
    }
  }
  else {
    jQuery(".connectedSortable").css("cursor", "move");
    var page_number;
    if (jQuery("#page_number") && jQuery("#page_number").val() != '' && jQuery("#page_number").val() != 1) {
      page_number = (jQuery("#page_number").val() - 1) * 20 + 1;
    }
    else {
      page_number = 1;
    }
    jQuery("#tbody_arr").sortable({
      handle:".connectedSortable",
      connectWith:".connectedSortable",
      update:function (event, tr) {
        jQuery("#draganddrop").attr("style", "");
        jQuery("#draganddrop").html("<strong><p>"+ wds_object.translate.changes_made_in_this_table_should_be_saved +"</p></strong>");
        var i = page_number;
        jQuery('.spider_order').each(function (e) {
          if (jQuery(this).find('input').val()) {
            jQuery(this).find('input').val(i++);
          }
        });
      }
    }); /* .disableSelection(); */
    jQuery("#tbody_arr").sortable("enable");
    jQuery("#tbody_arr").find(".handle").show(0);
    jQuery("#tbody_arr").find(".handle").attr('class', 'handle connectedSortable');
    jQuery("#th_order").hide(0);
    jQuery("#tbody_arr").find(".spider_order").hide(0);
    jQuery("#show_hide_weights").val("Show order column");
  }
}

/* Check all items. */
function spider_check_all_items() {
  spider_check_all_items_checkbox();
  jQuery('#check_all').trigger('click');
}

function spider_check_all_items_checkbox() {
  if (jQuery('#check_all_items').prop('checked')) {
    jQuery('#check_all_items').prop('checked', false);
    jQuery('#draganddrop').hide();
  }
  else {
    var saved_items = (parseInt(jQuery(".displaying-num").html()) ? parseInt(jQuery(".displaying-num").html()) : 0);
    var added_items = (jQuery('input[id^="check_pr_"]').length ? parseInt(jQuery('input[id^="check_pr_"]').length) : 0);
    var items_count = added_items + saved_items;
    jQuery('#check_all_items').prop('checked', true);
    if (items_count) {
      jQuery('#draganddrop').html("<strong><p>"+ wds_object.translate.selected +" " + items_count + " "+ wds_object.translate.item + (items_count > 1 ? wds_object.translate.s : "") + ".</p></strong>");
      jQuery('#draganddrop').show();
    }
  }
}

function spider_check_all(current) {
  if (!jQuery(current).prop('checked')) {
    jQuery('#check_all_items').prop('checked', false);
    jQuery('#draganddrop').hide();
  }
}

/* Set uploader to button class. */
function spider_uploader(button_id, input_id, delete_id, img_id) {
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  jQuery(function () {
    var formfield = null;
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
      if (formfield) {
        var fileurl = jQuery('img', html).attr('src');
        if (!fileurl) {
          var exploded_html;
          var exploded_html_askofen;
          exploded_html = html.split('"');
          for (i = 0; i < exploded_html.length; i++) {
            exploded_html_askofen = exploded_html[i].split("'");
          }
          for (i = 0; i < exploded_html.length; i++) {
            for (j = 0; j < exploded_html_askofen.length; j++) {
              if (exploded_html_askofen[j].search("href")) {
                fileurl = exploded_html_askofen[i + 1];
                break;
              }
            }
          }
          if (img_id != '') {
            alert(wds_object.translate.you_must_select_an_image_file);
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(input_id).style.display = "inline-block";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
        }
        else {
          if (img_id == '') {
            alert('You must select an audio file.');
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
          if ((img_id != '') && window.parent.document.getElementById(img_id)) {
            window.parent.document.getElementById(img_id).src = fileurl;
            window.parent.document.getElementById(img_id).style.display = "inline-block";
          }
        }
        formfield.val(fileurl);
        tb_remove();
      }
      else {
        window.original_send_to_editor(html);
      }
      formfield = null;
    };
    formfield = jQuery(this).parent().parent().find(".url_input");
    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    jQuery('#TB_overlay,#TB_closeWindowButton').bind("click", function () {
      formfield = null;
    });
    return false;
  });
}

/* Remove uploaded file. */
function spider_remove_url(input_id, img_id) {
  var id = input_id.substr(9);
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = '';
  }
  if ((img_id != '') && document.getElementById(img_id)) {
    document.getElementById(img_id).style.backgroundImage = "url('')";
  }
}

function spider_reorder_items(tbody_id) {
  jQuery("#" + tbody_id).sortable({
    handle: ".connectedSortable",
    connectWith: ".connectedSortable",
    update: function (event, tr) {
      spider_sortt(tbody_id);
    }
  });
}

function spider_sortt(tbody_id) {
  var str = "";
  var counter = 0;
  jQuery("#" + tbody_id).children().each(function () {
    str += ((jQuery(this).attr("id")).substr(3) + ",");
    counter++;
  });
  jQuery("#albums_galleries").val(str);
  if (!counter) {
    document.getElementById("table_albums_galleries").style.display = "none";
  }
}

function spider_remove_row(tbody_id, event, obj) {
  var span = obj;
  var tr = jQuery(span).closest("tr");
  jQuery(tr).remove();
  spider_sortt(tbody_id);
}

function spider_jslider(idtaginp) {
  jQuery(function () {
    var inpvalue = jQuery("#" + idtaginp).val();
    if (inpvalue == "") {
      inpvalue = 50;
    }
    jQuery("#slider-" + idtaginp).slider({
      range:"min",
      value:inpvalue,
      min:1,
      max:100,
      slide:function (event, ui) {
        jQuery("#" + idtaginp).val("" + ui.value);
      }
    });
    jQuery("#" + idtaginp).val("" + jQuery("#slider-" + idtaginp).slider("value"));
  });
}

function preview_built_in_watermark() {
  setTimeout(function() {
    watermark_type = window.parent.document.getElementById('built_in_watermark_type_text').checked;
    if (watermark_type) {
      watermark_text = document.getElementById('built_in_watermark_text').value;
      watermark_font_size = document.getElementById('built_in_watermark_font_size').value * 400 / 500;
      watermark_font = 'wds_' + document.getElementById('built_in_watermark_font').value.replace('.TTF', '').replace('.ttf', '');
      watermark_color = document.getElementById('built_in_watermark_color').value;
      watermark_opacity = 100 - document.getElementById('built_in_watermark_opacity').value;
      watermark_position = jQuery("input[name=built_in_watermark_position]:checked").val().split('-');
      document.getElementById("preview_built_in_watermark").style.verticalAlign = watermark_position[0];
      document.getElementById("preview_built_in_watermark").style.textAlign = watermark_position[1];
      stringHTML = '<span style="cursor:default;margin:4px;font-size:' + watermark_font_size + 'px;font-family:' + watermark_font + ';color:#' + watermark_color + ';opacity:' + (watermark_opacity / 100) + ';filter: Alpha(opacity=' + watermark_opacity + ');" class="non_selectable">' + watermark_text + '</span>';
      document.getElementById("preview_built_in_watermark").innerHTML = stringHTML;
    }
    watermark_type = window.parent.document.getElementById('built_in_watermark_type_image').checked;
    if (watermark_type) {
      watermark_url = document.getElementById('built_in_watermark_url').value;
      watermark_size = document.getElementById('built_in_watermark_size').value;
      watermark_position = jQuery("input[name=built_in_watermark_position]:checked").val().split('-');
      document.getElementById("preview_built_in_watermark").style.verticalAlign = watermark_position[0];
      document.getElementById("preview_built_in_watermark").style.textAlign = watermark_position[1];
      stringHTML = '<img class="non_selectable" src="' + watermark_url + '" style="margin:0 4px 0 4px;max-width:95%;width:' + watermark_size + '%;" />';
      document.getElementById("preview_built_in_watermark").innerHTML = stringHTML;
    }
  }, 50);
}

function wds_built_in_watermark(watermark_type) {
  jQuery("#built_in_" + watermark_type).prop('checked', 'checked');
  jQuery("#tr_built_in_watermark_url").css('display', 'none');
  jQuery("#tr_built_in_watermark_size").css('display', 'none');
  jQuery("#tr_built_in_watermark_opacity").css('display', 'none');
  jQuery("#tr_built_in_watermark_text").css('display', 'none');
  jQuery("#tr_built_in_watermark_font_size").css('display', 'none');
  jQuery("#tr_built_in_watermark_font").css('display', 'none');
  jQuery("#tr_built_in_watermark_color").css('display', 'none');
  jQuery("#tr_built_in_watermark_position").css('display', 'none');
  jQuery("#tr_built_in_watermark_preview").css('display', 'none');
  jQuery("#preview_built_in_watermark").css('display', 'none');
  switch (watermark_type) {
    case 'watermark_type_text':
    {
      jQuery("#tr_built_in_watermark_opacity").css('display', '');
      jQuery("#tr_built_in_watermark_text").css('display', '');
      jQuery("#tr_built_in_watermark_font_size").css('display', '');
      jQuery("#tr_built_in_watermark_font").css('display', '');
      jQuery("#tr_built_in_watermark_color").css('display', '');
      jQuery("#tr_built_in_watermark_position").css('display', '');
      jQuery("#tr_built_in_watermark_preview").css('display', '');
      jQuery("#preview_built_in_watermark").css('display', 'table-cell');
      break;
    }
    case 'watermark_type_image':
    {
      jQuery("#tr_built_in_watermark_url").css('display', '');
      jQuery("#tr_built_in_watermark_size").css('display', '');
      jQuery("#tr_built_in_watermark_position").css('display', '');
      jQuery("#tr_built_in_watermark_preview").css('display', '');
      jQuery("#preview_built_in_watermark").css('display', 'table-cell');
      break;
    }
  }
}

function wds_inputs() {
  jQuery(".spider_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
      return false;
    }
    return true;
  });
}

function wds_enable_disable(display, id, current) {
  jQuery("#" + current).prop('checked', 'checked');
  jQuery("#" + id).css('display', display);
}
function wds_enable_disable_autoplay(display, id, current) {
  jQuery("#" + current).prop('checked', 'checked');
  jQuery("." + id).css('visibility', display); 
}

function change_rl_butt_style(type_key) {
  jQuery("#wds_left_style").removeClass().addClass("fa " + type_key + "-left");
  jQuery("#wds_right_style").removeClass().addClass("fa " + type_key + "-right");
}

function change_bull_style(type_key) {
  jQuery("#wds_act_bull_style").removeClass().addClass("fa " + type_key.replace("-o", ""));
  jQuery("#wds_deact_bull_style").removeClass().addClass("fa " + type_key);
}

function wds_change_fillmode_type(that, id) {
  var title = jQuery(that).find('#wds_fillmode_option_title-'+ id ).attr('data-title');
  var img = jQuery(that).find('#wds_fillmode_option_img-'+ id +' img').attr('src');
  jQuery(".wds_fillmode_option .spider_option_cont").removeClass('selected');
  jQuery('#wds_fillmode_option-'+ id +' .spider_option_main_title').html(title);
  jQuery('#wds_fillmode_preview-'+ id +' img').attr('src', img);
  jQuery('#wds_fillmode_preview-'+ id +' input').val(title);
  jQuery(that).addClass('selected');
  jQuery('#wds_fillmode_preview-'+ id).show();
  if ( title == 'fill' ) {
    var bg_pos = {0 : 'center', 1 : 'center'};
    if ( jQuery("input[name='smart_crop']:checked").val() == 1 ) {
      bg_pos = jQuery("input[name='crop_image_position']:checked").val().split(" ");
    }
	  jQuery('.wds_box.wds_sub_active div[id^=\'wds_preview_image\']').css({ 'background-size': 'cover', 'background-position': bg_pos[0] + ' ' + bg_pos[1], 'background-repeat': 'no-repeat' });
  }
  if ( title == 'fit' ) {
	jQuery('.wds_box.wds_sub_active div[id^=\'wds_preview_image\']').css({ 'background-size': 'contain', 'background-position': 'center center', 'background-repeat': 'no-repeat' });
  }
  if ( title == 'stretch' ) {
	jQuery('.wds_box.wds_sub_active div[id^=\'wds_preview_image\']').css({ 'background-size': '100% 100%', 'background-position': '100% 100%', 'background-repeat': 'no-repeat' });
  }
  if ( title == 'center' ) {
	jQuery('.wds_box.wds_sub_active div[id^=\'wds_preview_image\']').css({ 'background-size': 'unset', 'background-position': 'center center', 'background-repeat': 'no-repeat' });
  }
  if ( title == 'tile' ) {
	jQuery('.wds_box.wds_sub_active div[id^=\'wds_preview_image\']').css({ 'background-size': 'unset', 'background-position': 'unset', 'background-repeat': 'repeat' });
  }
  jQuery('.wds_fillmode_option .spider_options_cont').hide();
}

function change_rl_butt_type(that) {
  var type_key = jQuery(that).attr('value');
  src	= rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/1.png';
  var options = '';
  var divs = '';
  for (var i = 0; i < wds_rl_butt_type[type_key].length - 1; i++) {
    var num = i + 1;
    divs += '<div class="spider_option_cont" value="' + i + '"  onclick="change_rl_butt_color(this, ' + type_key + ')" > ' +
			  '<div  class="spider_option_cont_title" >'+ wds_object.translate.color +'-'+ num +
			  '</div>' +
			  '<div class="spider_option_cont_img" >' + 
			    '<img  src="' + rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/'+wds_rl_butt_type[type_key][i]+'/1.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/'+wds_rl_butt_type[type_key][i]+'/2.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/'+wds_rl_butt_type[type_key][i]+'/3.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/'+wds_rl_butt_type[type_key][i]+'/4.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			  '</div>' +
		    '</div>';
  }
  jQuery(".spider_options_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  jQuery('.spider_options_color_cont').html(divs);
  jQuery('#rl_butt_img_l').attr("src", rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#rl_butt_img_r').attr("src", rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/2.png');
  jQuery('#rl_butt_hov_img_l').attr("src", rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/3.png');
  jQuery('#rl_butt_hov_img_r').attr("src", rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/4.png');

  jQuery('#left_butt_url').val(rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#right_butt_url').val(rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/2.png');
  jQuery('#left_butt_hov_url').val(rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/3.png');
  jQuery('#right_butt_hov_url').val(rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/1/4.png');
}

function change_play_paus_butt_type(that) {
  var type_key = jQuery(that).attr('value');
  var src	= pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/1.png';
  var options = '';
  var divs = '';
  for (var i = 0; i < wds_pp_butt_type[type_key].length; i++) {
    var num = i + 1;
    divs += '<div class="spider_option_cont" value="' + i + '" onclick="change_play_paus_butt_color(this, ' + type_key + ')" > ' +
			  '<div  class="spider_option_cont_title" >' + wds_object.translate.color +'-'+ num +
			  '</div>' +
			  '<div class="spider_option_cont_img" >' + 
			    '<img  src="' + pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/'+wds_pp_butt_type[type_key][i]+'/1.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/'+wds_pp_butt_type[type_key][i]+'/2.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/'+wds_pp_butt_type[type_key][i]+'/3.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			    '<img  src="' + pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/'+wds_pp_butt_type[type_key][i]+'/4.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			  '</div>' +
		    '</div>';
  }
  jQuery(".spider_pp_options_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  jQuery('.spider_pp_options_color_cont').html(divs);
  jQuery('#pp_butt_img_play').attr("src", pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#pp_butt_img_paus').attr("src", pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/3.png');
  jQuery('#pp_butt_hov_img_play').attr("src", pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/2.png');
  jQuery('#pp_butt_hov_img_paus').attr("src", pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/4.png');

  jQuery('#play_butt_url').val(pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#paus_butt_url').val(pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/3.png');
  jQuery('#play_butt_hov_url').val(pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/2.png');
  jQuery('#paus_butt_hov_url').val(pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/1/4.png');
}

function change_rl_butt_color(that, type_key) {
  var color_key = jQuery(that).attr('value');
  jQuery(".spider_options_color_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  var src = rl_butt_dir + wds_rl_butt_type[type_key]["type_name"] + '/' + wds_rl_butt_type[type_key][color_key];
  jQuery('#rl_butt_img_l').attr("src", src + '/1.png');
  jQuery('#rl_butt_img_r').attr("src", src + '/2.png');
  jQuery('#rl_butt_hov_img_l').attr("src", src + '/3.png');
  jQuery('#rl_butt_hov_img_r').attr("src", src + '/4.png');

  jQuery('#left_butt_url').val(src + '/1.png');
  jQuery('#right_butt_url').val(src + '/2.png');
  jQuery('#left_butt_hov_url').val(src + '/3.png');
  jQuery('#right_butt_hov_url').val(src + '/4.png');
}

function change_play_paus_butt_color(that, type_key) {
  var color_key = jQuery(that).attr('value');
  jQuery(".spider_pp_options_color_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  var src = pp_butt_dir + wds_pp_butt_type[type_key]["type_name"] + '/' + wds_pp_butt_type[type_key][color_key];
  jQuery('#pp_butt_img_play').attr("src", src + '/1.png');
  jQuery('#pp_butt_img_paus').attr("src", src + '/3.png');
  jQuery('#pp_butt_hov_img_play').attr("src", src + '/2.png');
  jQuery('#pp_butt_hov_img_paus').attr("src", src + '/4.png');

  jQuery('#play_butt_url').val(src + '/1.png');
  jQuery('#paus_butt_url').val(src + '/3.png');
  jQuery('#play_butt_hov_url').val(src + '/2.png');
  jQuery('#paus_butt_hov_url').val(src + '/4.png');
}

function change_src() {
  var src_l = jQuery('#rl_butt_img_l').attr("src");
  var src_r = jQuery('#rl_butt_img_r').attr("src");

  var src_h_l = jQuery('#rl_butt_hov_img_l').attr("src");
  var src_h_r = jQuery('#rl_butt_hov_img_r').attr("src");

  jQuery('#rl_butt_img_l').attr("src", src_h_l);
  jQuery('#rl_butt_img_r').attr("src", src_h_r);
  jQuery('#rl_butt_hov_img_l').attr("src", src_l);
  jQuery('#rl_butt_hov_img_r').attr("src", src_r);

  jQuery('#left_butt_url').val(src_h_l);
  jQuery('#right_butt_url').val(src_h_r);
  jQuery('#left_butt_hov_url').val(src_l);
  jQuery('#right_butt_hov_url').val(src_r);
}

function wds_choose_option(that) {
  jQuery('.spider_options_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_choose_option_color(that) {
  jQuery('.spider_options_color_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_choose_pp_option(that) {
  jQuery('.spider_pp_options_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_choose_pp_option_color(that) {
  jQuery('.spider_pp_options_color_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_choose_bull_option(that) {
  jQuery('.spider_bull_options_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_choose_bull_option_color(that) {
  jQuery('.spider_bull_options_color_cont').toggle(1, function() {});
  jQuery(that).find("i").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
}

function wds_change_custom_src() {
  var src_l = jQuery('#left_butt_img').attr("src");
  var src_r = jQuery('#right_butt_img').attr("src");

  var src_h_l = jQuery('#left_butt_hov_img').attr("src");
  var src_h_r = jQuery('#right_butt_hov_img').attr("src");

  jQuery('#left_butt_img').attr("src", src_h_l);
  jQuery('#right_butt_img').attr("src", src_h_r);
  jQuery('#left_butt_hov_img').attr("src", src_l);
  jQuery('#right_butt_hov_img').attr("src", src_r);

  jQuery('#left_butt_url').val(src_h_l);
  jQuery('#right_butt_url').val(src_h_r);
  jQuery('#left_butt_hov_url').val(src_l);
  jQuery('#right_butt_hov_url').val(src_r);
}

function wds_change_play_paus_custom_src() {
  var src_l = jQuery('#play_butt_img').attr("src");
  var src_r = jQuery('#paus_butt_img').attr("src");

  var src_h_l = jQuery('#play_butt_hov_img').attr("src");
  var src_h_r = jQuery('#paus_butt_hov_img').attr("src");

  jQuery('#play_butt_img').attr("src", src_h_l);
  jQuery('#paus_butt_img').attr("src", src_h_r);
  jQuery('#play_butt_hov_img').attr("src", src_l);
  jQuery('#paus_butt_hov_img').attr("src", src_r);

  jQuery('#play_butt_url').val(src_h_l);
  jQuery('#paus_butt_url').val(src_h_r);
  jQuery('#play_butt_hov_url').val(src_l);
  jQuery('#paus_butt_hov_url').val(src_r);
}


function change_play_paus_src() {
  var src_l = jQuery('#pp_butt_img_play').attr("src");
  var src_r = jQuery('#pp_butt_img_paus').attr("src");

  var src_h_l = jQuery('#pp_butt_hov_img_play').attr("src");
  var src_h_r = jQuery('#pp_butt_hov_img_paus').attr("src");

  jQuery('#pp_butt_img_play').attr("src", src_h_l);
  jQuery('#pp_butt_img_paus').attr("src", src_h_r);
  jQuery('#pp_butt_hov_img_play').attr("src", src_l);
  jQuery('#pp_butt_hov_img_paus').attr("src", src_r);

  jQuery('#play_butt_url').val(src_h_l);
  jQuery('#paus_butt_url').val(src_h_r);
  jQuery('#play_butt_hov_url').val(src_l);
  jQuery('#paus_butt_hov_url').val(src_r);
}

function wds_change_bullets_custom_src() {
  var src_m = jQuery('#bull_img_main').attr("src");
  var src_h = jQuery('#bull_img_hov').attr("src"); 

  jQuery('#bull_img_main').attr("src", src_h);
  jQuery('#bull_img_hov').attr("src", src_m);

  jQuery('#bullets_img_main_url').val(src_h);
  jQuery('#bullets_img_hov_url').val(src_m);
}

function change_bullets_images_type(that) {
  var type_key = jQuery(that).attr('value');
  var src	= blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/1/1.png';
  var options = '';
  var divs = '';
  for (var i = 0; i < wds_blt_img_type[type_key].length-1; i++) {
    var num = i + 1;
    divs += '<div class="spider_option_cont" value="'+i+'"  onclick="change_bullets_images_color(this, ' + type_key + ')" > ' +
			  '<div  class="spider_option_cont_title" style="width: 64%" >' + wds_object.translate.color +'-'+ num +
			  '</div>' +
			  '<div class="spider_option_cont_img" style="width: 22%;padding: 6px 5px 0px 5px;" >' + 
				'<img  src="' + blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/'+wds_blt_img_type[type_key][i]+'/1.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
				'<img  src="' + blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/'+wds_blt_img_type[type_key][i]+'/2.png" style="display:inline-block; width: 14px; height: 14px;" />' + 
			  '</div>' +
			'</div>';
	
  }
  jQuery(".spider_bull_options_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  var select = '<select class="select_icon"  name="bullets_images_color" id="bullets_images_color" onchange="change_bullets_images_color(this, '+type_key+')">' + options + '</select>';
  jQuery('.spider_bull_options_color_cont').html(divs);
  jQuery('#bullets_images_color_cont').html(select);
  jQuery('#bullets_img_main').attr("src", blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#bullets_img_hov').attr("src", blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/1/2.png');

  jQuery('#bullets_img_main_url').val(blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/1/1.png');
  jQuery('#bullets_img_hov_url').val(blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/1/2.png');
}

function change_bullets_images_color(that, type_key) {
  var color_key = jQuery(that).attr('value');
  jQuery(".spider_bull_options_color_cont .spider_option_cont").css({backgroundColor: ""});
  jQuery(that).css({backgroundColor: "#3399FF"});
  var src = blt_img_dir + wds_blt_img_type[type_key]["type_name"] + '/' + wds_blt_img_type[type_key][color_key];
  jQuery('#bullets_img_main').attr("src", src + '/1.png');
  jQuery('#bullets_img_hov').attr("src", src + '/2.png');

  jQuery('#bullets_img_main_url').val(src + '/1.png');
  jQuery('#bullets_img_hov_url').val(src + '/2.png');
}

function change_bullets_src() {
  var src_l = jQuery('#bullets_img_main').attr("src");
  var src_r = jQuery('#bullets_img_hov').attr("src");

  jQuery('#bullets_img_main').attr("src", src_r);
  jQuery('#bullets_img_hov').attr("src", src_l);

  jQuery('#bullets_img_main_url').val(src_r);
  jQuery('#bullets_img_hov_url').val(src_l);
}

function image_for_next_prev_butt(display) {
  if ( jQuery('.wds_settings_box [name=rl_butt_img_or_not]:checked').val() == 'our' ) {
    jQuery("#right_left_butt_style, #right_butt_upl, #tr_butts_color, #tr_hover_color").hide();
    jQuery("#right_left_butt_select").show();
  }
  if ( jQuery('.wds_settings_box [name=rl_butt_img_or_not]:checked').val() == 'custom' ) {
    jQuery("#right_left_butt_select, #right_left_butt_style, #tr_butts_color, #tr_hover_color").hide();
    jQuery("#right_butt_upl").show();
  }
  if ( jQuery('.wds_settings_box [name=rl_butt_img_or_not]:checked').val() == 'style' ) {
    jQuery("#right_butt_upl, #right_left_butt_select").hide();
    jQuery("#right_left_butt_style, #tr_butts_color, #tr_hover_color").show();
  }

  switch (display) {
    case 'our' : {
      jQuery("#rl_butt_img_or_not_our").prop('checked', true);
      jQuery("#right_left_butt_style, #right_butt_upl, #tr_butts_color, #tr_hover_color").hide();
      jQuery("#right_left_butt_select").show();
      break;
    }
    case 'custom' : {
      jQuery("#rl_butt_img_or_not_custom").prop('checked', true);
      jQuery("#right_left_butt_select, #right_left_butt_style, #tr_butts_color, #tr_hover_color").hide();
      jQuery("#right_butt_upl").show();
      break;
    }
    case 'style' : {
      jQuery("#rl_butt_img_or_not_0").prop('checked', true);
      jQuery("#right_butt_upl, #right_left_butt_select").hide();
      jQuery("#right_left_butt_style, #tr_butts_color, #tr_hover_color").show();
      break;
    }
    default: {
      break;
    }
  }
}

function image_for_bull_butt(display) {
  if ( jQuery('.wds_settings_box [name=bull_butt_img_or_not]:checked').val() == 'our' ) {
    jQuery("#bullets_style, #bullets_images_cust, #bullets_act_color, #bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
    jQuery("#bullets_images_select").show();
  }
  if ( jQuery('.wds_settings_box [name=bull_butt_img_or_not]:checked').val() == 'custom' ) {
    jQuery("#bullets_images_select, #bullets_style, #bullets_act_color, #bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
    jQuery("#bullets_images_cust").show();
  }
  if ( jQuery('.wds_settings_box [name=bull_butt_img_or_not]:checked').val() == 'style' ) {
    jQuery("#bullets_images_select, #bullets_images_cust, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
    jQuery("#bullets_style, #bullets_act_color, #bullets_color").show();
  }
  if ( jQuery('.wds_settings_box [name=bull_butt_img_or_not]:checked').val() == 'text' ) {
    jQuery("#bullets_images_select, #bullets_images_select, #bullets_images_cust, #bullets_style, #bullets_act_color").hide();
    jQuery("#bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").show();
  }

  switch (display) {
    case 'our' : {
      jQuery("#bull_butt_img_or_not_our").prop('checked', true);
      jQuery("#bullets_style, #bullets_images_cust, #bullets_act_color, #bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
      jQuery("#bullets_images_select").show();
      break;
    }

    case 'custom' : {
      jQuery("#bull_butt_img_or_not_cust").prop('checked', true);
      jQuery("#bullets_images_select, #bullets_style, #bullets_act_color, #bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
      jQuery("#bullets_images_cust").show();
      break;
    }
	
    case 'style' : {
      jQuery("#bull_butt_img_or_not_stl").prop('checked', true);
      jQuery("#bullets_images_select, #bullets_images_cust, #bullets_back_act_color, #bullets_back_color, #bullets_radius").hide();
      jQuery("#bullets_style, #bullets_act_color, #bullets_color").show();
      break;
    }
   case 'text' : {
      jQuery("#bull_butt_img_or_not_txt").prop('checked', true);
      jQuery("#bullets_images_select, #bullets_images_select, #bullets_images_cust, #bullets_style, #bullets_act_color").hide();
      jQuery("#bullets_color, #bullets_back_act_color, #bullets_back_color, #bullets_radius").show();
      break;
    }
    default: {
      break;
    }
  }
}

function showhide_for_carousel_fildes(display) {
  if (display == 1) {
   jQuery("#carousel1").prop('checked', true);
   jQuery("#carousel_fildes").css('display', '');
  }
  else {
   jQuery("#carousel0" ).prop('checked', true);
   jQuery("#carousel_fildes").css('display', 'none');
  }
}

function image_for_play_pause_butt(display) {
  if ( jQuery('.wds_settings_box [name=play_paus_butt_img_or_not]:checked').val() == 'our' ) {
    jQuery("#play_pause_butt_style, #play_pause_butt_cust, #tr_butts_color, #tr_hover_color").hide();
    jQuery("#play_pause_butt_select").show();
  }
  if ( jQuery('.wds_settings_box [name=play_paus_butt_img_or_not]:checked').val() == 'custom' ) {
    jQuery("#play_pause_butt_select, #play_pause_butt_style, #tr_butts_color, #tr_hover_color").hide();
    jQuery("#play_pause_butt_cust").show();
  }
  if ( jQuery('.wds_settings_box [name=play_paus_butt_img_or_not]:checked').val() == 'style' ) {
    jQuery("#play_pause_butt_cust, #play_pause_butt_select").hide();
    jQuery("#tr_butts_color, #tr_hover_color, #play_pause_butt_style").show();
  }

  switch (display) {
    case 'our' : {
      jQuery("#play_pause_butt_img_or_not_our").prop('checked', true);
      jQuery("#play_pause_butt_style, #play_pause_butt_cust, #tr_butts_color, #tr_hover_color").hide();
      jQuery("#play_pause_butt_select").show();
      break;
    }
    case 'custom' : {
      jQuery("#play_pause_butt_img_or_not_cust").prop('checked', true);
      jQuery("#play_pause_butt_select, #play_pause_butt_style, #tr_butts_color, #tr_hover_color").hide();
      jQuery("#play_pause_butt_cust").show();
      break;
    }
    case 'style' : {
      jQuery("#play_pause_butt_img_or_not_style").prop('checked', true);
      jQuery("#play_pause_butt_cust, #play_pause_butt_select").hide();
      jQuery("#tr_butts_color, #tr_hover_color, #play_pause_butt_style").show();
      break;
    }
    default: {
      break;
    }
  }
}

function spider_check_isnum(e) {
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
    return false;
  }
  return true;
}

function spider_set_image_url(id) {
  if (!jQuery("#image_url_input").val()) {
    return false;
  }
  jQuery("#image_url" + id).val(jQuery("#image_url_input").val());
  jQuery("#thumb_url" + id).val(jQuery("#image_url_input").val());
  jQuery("#wds_preview_image" + id).css("background-image", "url('" + jQuery("#image_url_input").val() + "')");
  jQuery("#wds_tab_image" + id).css("background-image", "url('" + jQuery("#image_url_input").val() + "')");
  jQuery("#delete_image_url" + id).css("display", "inline-block");
  jQuery("#wds_preview_image" + id).css("display", "inline-block");
  jQuery("#image_url_input").val("");
  jQuery("#type" + id).val("image");
  jQuery("#trlink" + id).show();
  return true;
}

function wds_media_uploader_add_slide(e, id, multiple) {
  if (typeof multiple == "undefined") {
    var multiple = true;
  }
  var custom_uploader;
  e.preventDefault();
  /* If the uploader object has already been created, reopen the dialog. */
  if (custom_uploader) {
    custom_uploader.open();
    /*TODO remove return; */
  }

  type_ = jQuery("#type" + id).val();
  if (type_ != "image" && type_ != "video") {
    type_ = '';
  }
  custom_uploader = wp.media.frames.file_frame = wp.media({
    library : { type : type_ },
    frame: 'post',
    multiple: multiple,
    id: id
  });

  /* Insert files to slider. */
  custom_uploader.on('insert select', function() {
    var attachment = [];
    if ( custom_uploader.state().id == "embed" ) {
      if ( custom_uploader.state().changed.type != "image" && custom_uploader.state()._previousAttributes.type != "image") {
        alert(wds.file_not_supported);
        return;
      }
      /* Insert image from URL. */
      attachment.push({'url': custom_uploader.state().props.attributes.url, 'mime': 'image/jpeg'});
    }
    else {
      attachment = custom_uploader.state().get('selection').toJSON();
    }

    var supported_image_mime = ['image/jpeg', 'image/png', 'image/gif'];
    var supported_video_mime = ['video/mp4', 'audio/ogg', 'video/webm'];
    var supported_audio_mime = ['audio/mpeg3', 'audio/ogg', 'audio/aac', 'audio/m4a', 'audio/f4a', 'audio/mp4'];
    for (var i in attachment) {
      /* In some case (found from support) attachment[i].mime was typed 2 times - with correct value and with 'undefined'. */
      if ( typeof attachment[i].mime != 'undefined') {
        if ( jQuery.inArray(attachment[i].mime, supported_image_mime) == -1 && jQuery.inArray(attachment[i].mime, supported_video_mime) == -1 ) {
          alert(wds.file_not_supported);
          wds_media_uploader_add_slide(e);
          return;
        }
        if ( wds_object.is_free ) {
          if ( jQuery.inArray(attachment[i].mime, supported_video_mime) != -1 ) {
            alert(wds_object.translate.video_disabled_in_free_version);
            wds_media_uploader_add_slide(e);
            return;
          }
        }
      }
    }
    /* Delete active slide if it has no image. */
    jQuery(".wds_box input[id^='image_url']").each(function () {
      var slide_id = jQuery(this).attr("id").replace("image_url", "");
      if ( !jQuery("#image_url" + slide_id).val() && !jQuery("#slide" + slide_id + "_layer_ids_string").val() ) {
        wds_remove_slide(slide_id, 0);
      }
    });
    for ( var i in attachment ) {
      /* In some case (found from support) attachment[i].mime was typed 2 times - with correct value and with 'undefined'. */
      if ( typeof attachment[i].mime != 'undefined') {
        var slides_count = jQuery(".wbs_subtab div[id^='wbs_subtab']").length;
        if ( typeof id == "undefined" ) {
          var new_slide_id = wds_add_slide();
        }
        else {
          var new_slide_id = id;
        }
        if ( jQuery.inArray(attachment[i].mime, supported_image_mime) != -1 ) { /* image */
          var thumb_url = (attachment[i]['sizes'] && attachment[i]['sizes']['thumbnail']) ? attachment[i]['sizes']['thumbnail']['url'] : attachment[i]['url'];
          jQuery("#wds_preview_image" + new_slide_id).css({
            backgroundImage: 'url("' + attachment[i]['url'] + '")',
            display: "inline-block"
          });
          jQuery("#wds_tab_image" + new_slide_id).css({
            backgroundImage: 'url("' + attachment[i]['url'] + '")',
            backgroundPosition: 'center'
          });
          jQuery("#type" + new_slide_id).val("image");
          jQuery("#trlink" + new_slide_id).show();
          jQuery("#controls" + new_slide_id).hide();
          jQuery("#autoplay" + new_slide_id).hide();
          jQuery("#video_loop" + new_slide_id).hide();
          jQuery("#mute" + new_slide_id).hide();
        }
        else if (jQuery.inArray(attachment[i].mime, supported_video_mime) != -1) { /* video */
          var thumb_url = WD_S_URL + '/images/no-video.png';
          if ( typeof attachment[i].image != "undefined" ) {
            if ( attachment[i].image.src.indexOf('media/video.png') == '-1' ) {
              thumb_url = attachment[i].image.src;
            }
          }
          jQuery("#att_width" + new_slide_id).val(attachment[i].width);
          jQuery("#att_height" + new_slide_id).val(attachment[i].height);
          jQuery("#post_id" + new_slide_id).val(attachment[i].id);
          jQuery("#video_duration" + new_slide_id).val(attachment.fileLength);
          jQuery("#wds_preview_image" + new_slide_id).css({
            backgroundImage: 'url("' + thumb_url + '")',
            backgroundSize: "cover",
            width: "inherit",
            height: "inherit",
            display: "inline-block"
          });
          jQuery("#wds_tab_image" + new_slide_id).css({
            backgroundImage: 'url("' + thumb_url + '")',
            backgroundReapeat: 'no-repeat',
            backgroundPosition: 'center',
          });
          jQuery("#type" + new_slide_id).val("video");
          jQuery("#trlink" + new_slide_id).hide();
          jQuery("#controls" + new_slide_id).show();
          jQuery("#autoplay" + new_slide_id).show();
          jQuery("#video_loop" + new_slide_id).show();
          jQuery("#mute" + new_slide_id).show();
          jQuery('#wds_fillmode_option-' + new_slide_id + ' .spider_options_cont').addClass('type_video');
        }
        jQuery("#image_url" + new_slide_id).val(attachment[i]['url']);
        jQuery("#thumb_url" + new_slide_id).val(thumb_url);
        jQuery("#delete_image_url" + new_slide_id).css("display", "inline-block");
        jQuery("#youtube_rel_video" + new_slide_id).hide();
      }
    }
  });
  /* Open the uploader dialog. */
  custom_uploader.open();

  /* Remove the Media Library tab. */
  jQuery(".media-menu a:contains('" + wds_object.translate.media_library + "')").remove();
  jQuery("#media-attachment-filters option[value='" + type_ + "']").attr('selected','selected');
}

function wds_media_uploader(id, e, multiple) {
  if (typeof multiple == "undefined") {
    var multiple = false;
  }
  var custom_uploader;
  e.preventDefault();
  /* If the uploader object has already been created, reopen the dialog. */
  if (custom_uploader) {
    custom_uploader.open();
    /*TODO remove return; */
  }
  /* Extend the wp.media object. */
  if (id == 'music') {
    var library_type = 'audio';
  }
  else if (id.indexOf('video__') > -1) {
    var library_type = 'video';
    id = id.split("__");
    var slide_id = id[1];
    id =  'video';
  }
  else if (id.indexOf('add_update_thumbnail__') > -1) {
    library_type = 'image';
    id = id.split("__");
    var slide_id = id[1];
    id =  'add_update_thumbnail';
  }
  else {
    var slide_id = id;
    library_type = 'image';
  }

  custom_uploader = wp.media.frames.file_frame = wp.media({
    title: wds_object.translate.choose +' '+ library_type,
    library : { type : library_type},
    button: { text: wds_object.translate.insert },
    multiple: multiple
  });
  /* When a file is selected, grab the URL and set it as the text field's value */
  custom_uploader.on('select', function() {
    if (multiple == false) {
      attachment = custom_uploader.state().get('selection').first().toJSON();
    }
    else {
      attachment = custom_uploader.state().get('selection').toJSON();
    }
    var image_url = attachment.url;
    var thumb_url = (attachment.sizes && attachment.sizes.thumbnail)  ? attachment.sizes.thumbnail.url : image_url;
    jQuery("#wds_preview_image" + slide_id ).find("video").remove();
    switch (id) {
      case 'settings': {
        document.getElementById("background_image_url").value = image_url;
        document.getElementById("background_image").src = image_url;
        document.getElementById("button_bg_img").style.display = "none";
        document.getElementById("delete_bg_img").style.display = "inline-block";
        document.getElementById("background_image").style.display = "";
        document.getElementById("background_image_url").style.display = "";
        break;
      }
      case 'watermark': {
        document.getElementById("built_in_watermark_url").value = image_url;
        preview_built_in_watermark();
        break;
      }
      case 'music': {
        var music_url = image_url;
        document.getElementById("music_url").value = music_url;
        break;
      }
      case 'video': {
        if (attachment.mime != 'video/mp4' && attachment.mime != 'audio/ogg' && attachment.mime != 'video/webm') {
          alert('This file type is not supported.');
          wds_media_uploader('video__' + slide_id, e, multiple);
          return;
        }
        if (typeof attachment.image != "undefined") {
          if (attachment.image.src.indexOf('media/video.png') != '-1') {
            thumb_url = WD_S_URL + '/images/no-video.png';
          }
          else {
            thumb_url = attachment.image.src;
          }
        }
        else {
          thumb_url = WD_S_URL + '/images/no-video.png';
        }
        jQuery("#att_width" + slide_id).val(attachment.width);
        jQuery("#att_height" + slide_id).val(attachment.height);
        jQuery("#post_id" + slide_id).val(attachment.id);
        jQuery("#thumb_url" + slide_id).val(thumb_url);
        jQuery("#video_duration" + slide_id).val(attachment.fileLength);
        jQuery("#wds_preview_image" + slide_id).css("background", "url('" + thumb_url + "') no-repeat center center" );
        jQuery("#wds_tab_image" + slide_id).css("background", "url('" + thumb_url + "') no-repeat center center" );
        jQuery("#wds_tab_image" + slide_id).css('background-position', 'center');
        jQuery("#wds_preview_image" + slide_id).css("background-size", "cover" );
        jQuery("#wds_tab_image" + slide_id).css("background-size", "cover" );
        jQuery("#wds_preview_image" + slide_id).css("width", "inherit" );
        jQuery("#wds_preview_image" + slide_id).css("height", "inherit" );
        jQuery("#image_url" + slide_id).val(image_url);
        jQuery("#delete_image_url" + slide_id).css("display", "inline-block");
        jQuery("#wds_preview_image" + slide_id).css("display", "inline-block");
        jQuery("#type" + slide_id).val("video");
        jQuery("#trlink" + slide_id).hide();
        jQuery("#autoplay" + slide_id).removeAttr("style");
        jQuery("#controls" + slide_id).removeAttr("style");
        jQuery("#video_loop" + slide_id).removeAttr("style");
        jQuery("#mute" + slide_id).removeAttr("style");
        jQuery(".edit_thumb").text("Edit Thumbnail");
        break;
      }
      case 'nav_left_but': {
        /* Add image for left button.*/
        jQuery("#left_butt_img").attr("src", image_url);
        jQuery("#left_butt_url").val(image_url);
        break;
      }
      case 'nav_right_but': {
        /* Add image for right buttons.*/
        jQuery("#right_butt_img").attr("src", image_url);
        jQuery("#right_butt_url").val(image_url);
        break;
      }
      case 'nav_left_hov_but': {
        /* Add hover image for right buttons.*/
        jQuery("#left_butt_hov_img").attr("src", image_url);
        jQuery("#left_butt_hov_url").val(image_url);
        break;
      }
      case 'nav_right_hov_but': {
        /* Add hover image for left button.*/
        jQuery("#right_butt_hov_img").attr("src", image_url);
        jQuery("#right_butt_hov_url").val(image_url);
        break;
      }
      case 'bullets_main_but': {
        /* Add image for main button.*/
        jQuery("#bull_img_main").attr("src", image_url);
        jQuery("#bullets_img_main_url").val(image_url);
        break;
      }
      case 'bullets_hov_but': {
        /* Add image for hover button.*/
        jQuery("#bull_img_hov").attr("src", image_url);
        jQuery("#bullets_img_hov_url").val(image_url);
        break;
      }
      case 'play_but': {
        /* Add image for play button.*/
        jQuery("#play_butt_img").attr("src", image_url);
        jQuery("#play_butt_url").val(image_url);
        break;
      }
      case 'play_hov_but': {
        /* Add image for pause button.*/
        jQuery("#play_butt_hov_img").attr("src", image_url);
        jQuery("#play_butt_hov_url").val(image_url);
        break;
      }
      case 'paus_but': {
        /* Add hover image for play button.*/
        jQuery("#paus_butt_img").attr("src", image_url);
        jQuery("#paus_butt_url").val(image_url);
        break;
      }
      case 'paus_hov_but': {
        /* Add hover image for pause button.*/
        jQuery("#paus_butt_hov_img").attr("src", image_url);
        jQuery("#paus_butt_hov_url").val(image_url);
        break;
      }
      case 'add_update_thumbnail' : {
        if ( jQuery("#type" + slide_id).val() == "video" ) {
          /* For video slides.*/
          jQuery("#wds_preview_image" + slide_id).css("background-image", 'url("' + image_url + '")');
        }
        jQuery("#thumb_url" + slide_id).val(image_url);
        jQuery("#wds_tab_image" + slide_id).css("background-image", 'url("' + thumb_url + '")');
        jQuery("#wds_tab_image" + slide_id).css("background-position", "center");
        jQuery("#post_id" + slide_id).val(image_url);
        break;
      }
      case 'button_image_url': {
        /* Delete active slide if it has no image.*/
        jQuery(".wds_box input[id^='image_url']").each(function () {
          var slide_id = jQuery(this).attr("id").replace("image_url", "");
          if (!jQuery("#image_url" + slide_id).val() && !jQuery("#slide" + slide_id + "_layer_ids_string").val()) {
            wds_remove_slide(slide_id, 0);
          }
        });
        /* Add one or more slides.*/
        for (var i in attachment) {
          wds_add_slide();
          var slides_count = jQuery(".wbs_subtab div[id^='wbs_subtab']").length;
          var new_slide_id = "pr_" + slides_count;
          jQuery("#image_url" + new_slide_id).val(attachment[i]['url']);
          var thumb_url = (attachment[i]['sizes'] && attachment[i]['sizes']['thumbnail'])  ? attachment[i]['sizes']['thumbnail']['url'] : attachment[i]['url'];
          jQuery("#thumb_url" + new_slide_id).val(thumb_url);
          jQuery("#wds_preview_image" + new_slide_id).css("background-image", 'url("' + attachment[i]['url'] + '")');
          jQuery("#wds_tab_image" + new_slide_id).css("background-image", 'url("' + attachment[i]['url'] + '")');
          jQuery("#wds_tab_image" + new_slide_id).css("background-position", 'center');
          jQuery("#delete_image_url" + new_slide_id).css("display", "inline-block");
          jQuery("#wds_preview_image" + new_slide_id).css("display", "inline-block");
          jQuery("#type" + new_slide_id).val("image");
          jQuery("#trlink" + new_slide_id).show();
          jQuery("#controls" + new_slide_id).hide();
          jQuery("#autoplay" + new_slide_id).hide();
          jQuery("#video_loop" + new_slide_id).hide();
          jQuery("#mute" + new_slide_id).hide();
          jQuery("#youtube_rel_video" + new_slide_id).hide();
          jQuery(".edit_thumb").text("Edit Thumbnail");
        }
        break;
      }
      default: {
        jQuery("#image_url" + id).val(image_url);
        jQuery("#thumb_url" + id).val(thumb_url);
        jQuery("#wds_preview_image" + id).css("background-image", "url('" + image_url + "')");
        jQuery("#wds_tab_image" + id).css("background-image", "url('" + image_url + "')");
        jQuery("#wds_tab_image" + id).css("background-position", "center");
        jQuery("#delete_image_url" + id).css("display", "inline-block");
        jQuery("#wds_preview_image" + id).css("display", "inline-block");
        jQuery("#type" + id).val("image");
        jQuery("#trlink" + id).show();
        jQuery("#autoplay" + slide_id).hide();
        jQuery("#controls" + slide_id).hide();
        jQuery("#video_loop" + slide_id).hide();
        jQuery("#mute" + slide_id).hide();
        jQuery("#youtube_rel_video" + slide_id).hide();
        jQuery(".edit_thumb").text("Edit Thumbnail");
      }
    }
  });
  /* Open the uploader dialog. */
  custom_uploader.open();
}

function wds_add_image(files, image_for, slide_id, layer_id) {
  if (typeof files == "undefined" || files.length == 0) {
    return;
  }
  switch (image_for) {
    case 'add_slides': {
      /* Delete active slide if it has no image.*/
      jQuery(".wds_box input[id^='image_url']").each(function () {
        var slide_id = jQuery(this).attr("id").replace("image_url", "");
        if (!jQuery("#image_url" + slide_id).val() && !jQuery("#slide" + slide_id + "_layer_ids_string").val()) {
          wds_remove_slide(slide_id, 0);
        }
      });
      /* Add one or more slides.*/
      for (var i in files) {
        if (typeof window.parent.wp.media.frames.file_frame.options.id == "undefined") {
          var new_slide_id = window.parent.wds_add_slide();
        }
        else {
          var new_slide_id = window.parent.wp.media.frames.file_frame.options.id;
        }
        jQuery("#image_url" + new_slide_id).val(files[i]['url']);
        jQuery("#thumb_url" + new_slide_id).val(files[i]['thumb_url']);
        jQuery("#wds_preview_image" + new_slide_id).css("background-image", 'url("' + files[i]['url'] + '")');
        jQuery("#wds_tab_image" + new_slide_id).css("background-image", 'url("' + files[i]['url'] + '")');
        jQuery("#wds_tab_image" + new_slide_id).css("background-position", 'center');
        jQuery(".wds_video_container" + new_slide_id).html("");
        jQuery("#delete_image_url" + new_slide_id).css("display", "inline-block");
        jQuery("#wds_preview_image" + new_slide_id).css("display", "inline-block");
        jQuery("#type" + new_slide_id).val("image");
        jQuery("#trlink" + new_slide_id).show();
        jQuery("#controls" + new_slide_id).hide();
        jQuery("#autoplay" + new_slide_id).hide();
        jQuery("#video_loop" + new_slide_id).hide();
        jQuery("#mute" + new_slide_id).hide();
        jQuery("#youtube_rel_video" + new_slide_id).hide();
        jQuery(".edit_thumb").text(wds_object.translate.edit_thumbnail);
      }
      break;
    }
    case 'add_layer': {
      /* Add image layer to current slide.*/
      wds_add_layer('image', slide_id, '', '', files);
      break;
    }
    case 'add_update_layer': {
      /* Update current layer image.*/
      if (typeof layer_id == "undefined") {
        var layer_id = "";
      }
      jQuery("#slide" + slide_id + "_layer" + layer_id).attr('src', files[0]['url']);
      jQuery("#slide" + slide_id + "_layer" + layer_id+"_image_url").val(files[0]['url']);  
      break;
    }
    case 'add_update_slide': {
      /* Add or update current slide.*/
      jQuery("#image_url" + slide_id).val(files[0]['url']);
      jQuery("#thumb_url" + slide_id).val(files[0]['thumb_url']);
      jQuery("#wds_preview_image" + slide_id).css("background-image", 'url("' + files[0]['url'] + '")');
      jQuery("#wds_tab_image" + slide_id).css("background-image", 'url("' + files[0]['url'] + '")');
      jQuery("#wds_tab_image" + slide_id).css("background-position", 'center');
      jQuery(".wds_video_container" + slide_id).html("");
      jQuery("#delete_image_url" + slide_id).css("display", "inline-block");
      jQuery("#wds_preview_image" + slide_id).css("display", "inline-block");
      jQuery("#type" + slide_id).val("image");
      jQuery("#trlink" + slide_id).show();
      jQuery("#controls" + slide_id).hide();
      jQuery("#autoplay" + slide_id).hide();
      jQuery("#video_loop" + slide_id).hide();
      jQuery("#mute" + slide_id).hide();
      jQuery("#youtube_rel_video" + slide_id).hide();
      jQuery(".edit_thumb").text(wds_object.translate.edit_thumbnail);
      break;
    }
    case 'add_update_thumbnail': {
      jQuery("#thumb_url" + slide_id).val(files[0]['thumb_url']);
      jQuery("#wds_tab_image" + slide_id).css("background-image", 'url("' + (files[0]['thumb_url']) + '")');
      jQuery("#wds_tab_image" + slide_id).css("background-position", 'center');
      jQuery("#post_id" + slide_id).val(files[0]['thumb_url']);
      break;
    }
    case 'watermark': {
      /* Add image for watermark.*/
      document.getElementById("built_in_watermark_url").value = files[0]['url']; 
      preview_built_in_watermark();							
      break;
    }
    case 'nav_right_but': {
      /* Add image for right buttons.*/
      document.getElementById("right_butt_url").value = files[0]['url']; 
      document.getElementById("right_butt_img").src = files[0]['url'];
      break;
    }
    case 'nav_left_but': {
      /* Add image for left button.*/
      document.getElementById("left_butt_url").value = files[0]['url']; 
      document.getElementById("left_butt_img").src = files[0]['url'];
      break;
    }
    case 'nav_right_hov_but': {
      /* Add hover image for right buttons.*/
      document.getElementById("right_butt_hov_url").value = files[0]['url']; 
      document.getElementById("right_butt_hov_img").src = files[0]['url'];
      break;
    }
    case 'nav_left_hov_but': {
      /* Add hover image for left button.*/
      document.getElementById("left_butt_hov_url").value = files[0]['url']; 
      document.getElementById("left_butt_hov_img").src = files[0]['url'];
      break;
    }
    case 'bullets_main_but': {
      /* Add image for main button.*/
      document.getElementById("bullets_img_main_url").value = files[0]['url'];
      document.getElementById("bull_img_main").src = files[0]['url'];
      break;
    }
    case 'bullets_hov_but': {
      /* Add image for hover button.*/
      document.getElementById("bullets_img_hov_url").value = files[0]['url'];
      document.getElementById("bull_img_hov").src = files[0]['url'];
      break;
    }
    case 'play_but': {
      /* Add hover image for right buttons.*/
      document.getElementById("play_butt_url").value = files[0]['url']; 
      document.getElementById("play_butt_img").src = files[0]['url'];
      break;
    }
    case 'play_hov_but': {
      /* Add hover image for left button.*/
      document.getElementById("play_butt_hov_url").value = files[0]['url']; 
      document.getElementById("play_butt_hov_img").src = files[0]['url'];
      break;
    }
    case 'paus_but': {
      /* Add image for main button.*/
      document.getElementById("paus_butt_url").value = files[0]['url']; 
      document.getElementById("paus_butt_img").src = files[0]['url'];
      break;
    }
    case 'paus_hov_but': {
      /* Add image for hover button.*/
      document.getElementById("paus_butt_hov_url").value = files[0]['url']; 
      document.getElementById("paus_butt_hov_img").src = files[0]['url'];
      break;
    }
    default: {
      break;
    }
  }
}

function wds_change_sub_tab_title(that, box) {
  var slideID = box.substring("9");
  jQuery('#type' + slideID).val().indexOf('EMBED') > -1 ? jQuery(".edit_thumb").text(wds_object.translate.edit_filmstrip_thumbnail) : jQuery(".edit_thumb").text(wds_object.translate.edit_thumbnail);
  jQuery("#sub_tab").val(jQuery(that).attr("tab_type"));
  jQuery(".tab_buttons").removeClass("wds_sub_active");
  jQuery(".wds_tab_title_wrap").removeClass("wds_sub_active");
  jQuery(that).parent().addClass("wds_sub_active");
  jQuery(".wds_box").removeClass("wds_sub_active");
  jQuery("." + box).addClass("wds_sub_active");
  jQuery(".wds_sub_active .wds_tab_title").focus();
  jQuery(".wds_sub_active .wds_tab_title").select();
  if ( !wds_object.is_free ) {
    wds_hotspot_position();
  }

  /* Open/close section container on its header click.*/
  jQuery(".hndle, .handlediv").each(function () {
    jQuery(this).on("click", function () {
      wds_toggle_postbox(this);
    });
  });
}

function wds_change_sub_tab(that, box) {
  var slideID = box.substring("9");
  var edit_thum_text;
  if ( jQuery('#type' + slideID).val()
    && jQuery('#type' + slideID).val().indexOf('EMBED') > -1 ) {
    edit_thum_text = wds_object.translate.edit_filmstrip_thumbnail;
  }
  else {
    edit_thum_text = wds_object.translate.edit_thumbnail;
  }
  jQuery(".edit_thumb").text(edit_thum_text);

  jQuery("#sub_tab").val(jQuery(that).attr("tab_type"));
  jQuery(".tab_buttons").removeClass("wds_sub_active");
  jQuery(".tab_link").removeClass("wds_sub_active");
  jQuery(".wds_tab_title_wrap").removeClass("wds_sub_active");
  jQuery(".wds_box").removeClass("wds_sub_active");
  jQuery(that).parent().addClass("wds_sub_active");
  jQuery("." + box).addClass("wds_sub_active");
  jQuery(".tab_image").css('border-color','#B4AFAF');
  jQuery(that).css('border-color','#00A0D4');
  jQuery('.tab_image').find('input').blur();
  jQuery('.wds_fillmode_option .spider_options_cont').hide();
  if ( !wds_object.is_free ) {
    wds_hotspot_position();
  }
}

function wds_change_tab(that, box) {
  jQuery("#tab").val(jQuery(that).find(".wds_tab_label").attr("tab_type"));
  jQuery(".tab_button_wrap a").removeClass("wds_active");
  jQuery(that).children().addClass("wds_active");
  jQuery(".tab_button_wrap").children().css('border-color','#ddd');
  if(jQuery(that).children().hasClass('wds_active')) {
    jQuery(that).children().css('border-color','#00A0D4');
  }
  jQuery(".wds_box").removeClass("wds_active");
  jQuery("." + box).addClass("wds_active");
  if (box == "wds_settings_box") {
    /* Show "Reset all settings" button.*/
    jQuery(".reset-all-settings").removeClass("wd-hidden");
  }
  else {
    /* Hide "Reset all settings" button.*/
    jQuery(".reset-all-settings").addClass("wd-hidden");
  }
	jQuery(".tab_button_wrap").css('border-color','#ddd');
	if(jQuery(".wds_settings_box:visible").length>0){
		jQuery(".settings_tab_button_wrap a").css('border-color','#00A0D4');
	}
	else if(jQuery(".wds_slides_box:visible").length>0){
		jQuery(".slides_tab_button_wrap a").css('border-color','#00A0D4');
	}

  /* Set preview container overflow width.*/
  jQuery(".wds-preview-overflow").width(jQuery(".wd-slides-title").width());
}

function wds_change_nav(that, box) {
  jQuery("#nav_tab").val(jQuery(that).attr("tab_type"));
  jQuery(".wds_nav_tabs li").removeClass("wds_active");
  jQuery(that).addClass("wds_active");
  jQuery(".wds_nav_box").removeClass("wds_active");
  jQuery("." + box).addClass("wds_active");
}

function wds_showhide_layer(tbodyID, always_show) {
  jQuery(".wds_layer_head_tr").attr("style", "background-color : #e1e1e1" );
  jQuery("#" + tbodyID).css("background-color", "#FFFFFF");
  jQuery("#" + tbodyID + " .wds_layer_head_tr").css("background-color", "#cccccc");

  jQuery("#" + tbodyID).children().each(function() {
    if (!jQuery(this).hasClass("wds_layer_head_tr")) {
      if (jQuery(this).is(':hidden') || always_show) {
        jQuery('.wds_layer_content').hide();
        jQuery(this).show();
      }
      else {
        jQuery("#" + tbodyID).css("background-color", "#e1e1e1");
        jQuery("#" + tbodyID + " .wds_layer_head_tr").css("background-color", "#e1e1e1");
        jQuery(this).hide();
      }
    }
  });
}

function wds_delete_layer(id, layerID) {
  if (confirm(wds_object.translate.do_you_want_to_delete_layer)) {
    var prefix = "slide" + id + "_layer" + layerID;
	if (jQuery("#" + prefix).parent().attr("id") == prefix + "_div") {
       jQuery("#" + prefix).parent().remove();
       }
	else {
	   jQuery("#" + prefix).remove();
        }
    jQuery("#" + prefix + "_tbody").remove();
    var dellayerIds;
    var layerIDs = jQuery("#slide" + id + "_layer_ids_string").val();
    layerIDs = layerIDs.replace(layerID + ",", "");
    jQuery("#slide" + id + "_layer_ids_string").val(layerIDs);
    if (layerID.indexOf("pr_") == -1) {
      dellayerIds = jQuery("#slide" + id + "_del_layer_ids_string").val() + layerID + ",";
      jQuery("#slide" + id + "_del_layer_ids_string").val(dellayerIds);
    }
  }
}

function wds_duplicate_layer(type, id, layerID, new_id) {
  var prefix = "slide" + id + "_layer" + layerID;
  var new_layerID = "pr_" + wds_layerID;
  var new_prefix = "slide" + id + "_layer" + new_layerID;
  if (typeof new_id != 'undefined') {
    /* From slide duplication.*/
    new_prefix = "slide" + new_id + "_layer" + new_layerID;
    id = new_id;
    jQuery("#" + new_prefix + "_left").val(jQuery("#" + prefix + "_left").val());
    jQuery("#" + new_prefix + "_top").val(jQuery("#" + prefix + "_top").val());
    jQuery("#" + new_prefix + "_div_left").val(jQuery("#" + prefix + "_div_left").val());
    jQuery("#" + new_prefix + "_div_top").val(jQuery("#" + prefix + "_div_top").val());
  }
  else {
    /* From layer duplication.*/
    jQuery("#" + new_prefix + "_left").val(0);
    jQuery("#" + new_prefix + "_top").val(0);
    jQuery("#" + new_prefix + "_div_left").val(20);
    jQuery("#" + new_prefix + "_div_top").val(20);
  }
  jQuery("#" + new_prefix + "_text").val(jQuery("#" + prefix + "_text").val());
  jQuery("#" + new_prefix + "_link").val(jQuery("#" + prefix + "_link").val());
  jQuery("#" + new_prefix + "_hide_on_mobile").val(jQuery("#" + prefix + "_hide_on_mobile").val());
  jQuery("#" + new_prefix + "_start").val(jQuery("#" + prefix + "_start").val());
  jQuery("#" + new_prefix + "_end").val(jQuery("#" + prefix + "_end").val());
  jQuery("#" + new_prefix + "_delay").val(jQuery("#" + prefix + "_delay").val());
  jQuery("#" + new_prefix + "_duration_eff_in").val(jQuery("#" + prefix + "_duration_eff_in").val());
  jQuery("#" + new_prefix + "_duration_eff_out").val(jQuery("#" + prefix + "_duration_eff_out").val());
  jQuery("#" + new_prefix + "_color").val(jQuery("#" + prefix + "_color").val());
  jQuery("#" + new_prefix + "_size").val(jQuery("#" + prefix + "_size").val());
  jQuery("#" + new_prefix + "_padding").val(jQuery("#" + prefix + "_padding").val());
  jQuery("#" + new_prefix + "_fbgcolor").val(jQuery("#" + prefix + "_fbgcolor").val());
  jQuery("#" + new_prefix + "_transparent").val(jQuery("#" + prefix + "_transparent").val());
  jQuery("#" + new_prefix + "_border_width").val(jQuery("#" + prefix + "_border_width").val());
  jQuery("#" + new_prefix + "_border_color").val(jQuery("#" + prefix + "_border_color").val());
  jQuery("#" + new_prefix + "_border_radius").val(jQuery("#" + prefix + "_border_radius").val());
  jQuery("#" + new_prefix + "_shadow").val(jQuery("#" + prefix + "_shadow").val());
  jQuery("#" + new_prefix + "_image_url").val(jQuery("#" + prefix + "_image_url").val());
  jQuery("#" + new_prefix + "_image_width").val(jQuery("#" + prefix + "_image_width").val());
  jQuery("#" + new_prefix + "_image_height").val(jQuery("#" + prefix + "_image_height").val());
  jQuery("#" + new_prefix + "_alt").val(jQuery("#" + prefix + "_alt").val());
  jQuery("#" + new_prefix + "_imgtransparent").val(jQuery("#" + prefix + "_imgtransparent").val());
  jQuery("#" + new_prefix + "_hover_color").val(jQuery("#" + prefix + "_hover_color").val());
  jQuery("#" + new_prefix + "_type").val(jQuery("#" + prefix + "_type").val());
  jQuery("#" + new_prefix + "_hotp_width").val(jQuery("#" + prefix + "_hotp_width").val());
  jQuery("#" + new_prefix + "_hotp_fbgcolor").val(jQuery("#" + prefix + "_hotp_fbgcolor").val());
  jQuery("#" + new_prefix + "_round_hotp_border_width").val(jQuery("#" + prefix + "_round_hotp_border_width").val());
  jQuery("#" + new_prefix + "_hotp_border_color").val(jQuery("#" + prefix + "_hotp_border_color").val());
  jQuery("#" + new_prefix + "_hotp_border_radius").val(jQuery("#" + prefix + "_hotp_border_radius").val());
  jQuery("#" + new_prefix + "_add_class").val(jQuery("#" + prefix + "_add_class").val());
  jQuery("#" + new_prefix + "_hover_color_text").val(jQuery("#" + prefix + "_hover_color_text").val());
  jQuery("#" + new_prefix + "_infinite_in").val(jQuery("#" + prefix + "_infinite_in").val());
  jQuery("#" + new_prefix + "_infinite_out").val(jQuery("#" + prefix + "_infinite_out").val());
  jQuery("#" + new_prefix + "_min_size").val(jQuery("#" + prefix + "_min_size").val());
  if (jQuery("#" + prefix + "_published1").is(":checked")) {
    jQuery("#" + new_prefix + "_published1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_published0").is(":checked")) {
    jQuery("#" + new_prefix + "_published0").attr("checked", "checked");
  }
  if (type == "video") {
    if (jQuery("#" + prefix + "_image_scale1").is(":checked")) {
      jQuery("#" + new_prefix + "_image_scale1").attr("checked", "checked");
    }
    else if (jQuery("#" + prefix + "_image_scale0").is(":checked")) {
      jQuery("#" + new_prefix + "_image_scale0").attr("checked", "checked");
    }
  }
  else {
    if (jQuery("#" + prefix + "_image_scale").is(":checked")) {
      jQuery("#" + new_prefix + "_image_scale").attr("checked", "checked");
    }
  }
  if (jQuery("#" + prefix + "_target_attr_layer").is(":checked")) {
      jQuery("#" + new_prefix + "_target_attr_layer").attr("checked", "checked");
  }
  else {
	  jQuery("#" + new_prefix + "_target_attr_layer").removeAttr("checked");
  }
  jQuery("#" + new_prefix + "_transition option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_transition").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  if (jQuery("#" + prefix + "_google_fonts1").is(":checked")) {
    jQuery("#" + new_prefix + "_google_fonts1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_google_fonts0").is(":checked")) {
    jQuery("#" + new_prefix + "_google_fonts0").attr("checked", "checked");
  }
  wds_change_fonts(new_prefix);
  jQuery("#" + new_prefix + "_ffamily option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_ffamily").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_fweight option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_fweight").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_htextposition option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_htextposition").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_round_hotp_border_style option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_round_hotp_border_style").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_border_style option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_border_style").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_social_button option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_social_button").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_layer_effect_in option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_layer_effect_in").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_layer_effect_out option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_layer_effect_out").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_text_alignment option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_text_alignment").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  if (jQuery("#" + prefix + "_layer_video_loop1").is(":checked")) {
    jQuery("#" + new_prefix + "_layer_video_loop1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_layer_video_loop0").is(":checked")) {
    jQuery("#" + new_prefix + "_layer_video_loop0").attr("checked", "checked");
  }
  if (jQuery("#" + prefix + "_youtube_rel_layer_video1").is(":checked")) {
    jQuery("#" + new_prefix + "_youtube_rel_layer_video1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_youtube_rel_layer_video0").is(":checked")) {
    jQuery("#" + new_prefix + "_youtube_rel_layer_video0").attr("checked", "checked");
  }
  if (jQuery("#" + prefix + "_hotspot_animation1").is(":checked")) {
    jQuery("#" + new_prefix + "_hotspot_animation1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_hotspot_animation0").is(":checked")) {
    jQuery("#" + new_prefix + "_hotspot_animation0").attr("checked", "checked");
  }
  jQuery("#" + new_prefix + "_layer_callback_list option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_layer_callback_list").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_link_to_slide option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_link_to_slide").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  if (jQuery("#" + prefix + "_hotspot_text_display1").is(":checked")) {
    jQuery("#" + new_prefix + "_hotspot_text_display1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_hotspot_text_display0").is(":checked")) {
    jQuery("#" + new_prefix + "_hotspot_text_display0").attr("checked", "checked");
  }
  if (jQuery("#" + prefix + "_align_layer").is(":checked")) {
    jQuery("#" + new_prefix + "_align_layer").attr("checked", "checked");
  }
  else {
	  jQuery("#" + new_prefix + "_align_layer").removeAttr("checked");
  }
  if (jQuery("#" + prefix + "_static_layer").is(":checked")) {
    jQuery("#" + new_prefix + "_static_layer").attr("checked", "checked");
  }
  else {
	  jQuery("#" + new_prefix + "_static_layer").removeAttr("checked");
  }
  if (type == "text") {
    wds_new_line(new_prefix);
    jQuery("#" + new_prefix).attr({
      id: new_prefix,
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + ";" +
             "left: " + jQuery("#" + new_prefix + "_left").val() + "px;" +
             "top: " + jQuery("#" + new_prefix + "_top").val() + "px;" +
             "display: inline-block;" +
             "color: #" + jQuery("#" + prefix + "_color").val() + "; " +
             "font-size: " + jQuery("#" + prefix + "_size").val() + "px; " +
             "line-height: 1.25em; " +
             "font-family: " + jQuery("#" + prefix + "_ffamily").val() + "; " +
             "font-weight: " + jQuery("#" + prefix + "_fweight").val() + "; " +
             "padding: " + jQuery("#" + prefix + "_padding").val() + "; " +
             "background-color: " + wds_hex_rgba(jQuery("#" + prefix+ "_fbgcolor").val(), (100 - jQuery("#" + prefix+ "_transparent").val())) + "; " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + ";" +
             "text-align: " + jQuery("#" + prefix + "_text_alignment").val() + ";" + 
             "position: absolute;"
    });
    jQuery("#" + new_prefix).hover(function() { jQuery(this).css("color", jQuery("#" + prefix + "_hover_color_text").val()); }, function() { jQuery(this).css("color", jQuery("#" + prefix + "_color").val()); });
    wds_text_width("#" + new_prefix + "_image_width", new_prefix);
    wds_text_height("#" + new_prefix + "_image_height", new_prefix);
    wds_break_word("#" + new_prefix + "_image_scale", new_prefix);
  }
  else if (type == "image") {
    jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
      id: new_prefix,
      src: jQuery("#" + prefix).attr("src"),
      "class": "wds_draggable_" + id + " wds_draggable",
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + "; " +
             "left: " + jQuery("#" + new_prefix + "_left").val() + "px;" +
             "top: " + jQuery("#" + new_prefix + "_top").val() + "px;" +
             "opacity: " + (100 - jQuery("#" + prefix + "_imgtransparent").val()) / 100 + "; filter: Alpha(opacity=" + (100 - jQuery("#" + prefix+ "_imgtransparent").val()) + "); " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + "; " +
             "box-shadow: " + jQuery("#" + prefix + "_shadow").val() + "; " +
             "position: absolute;"
    }));
    wds_scale("#" + new_prefix + "_image_scale", new_prefix);
  }
  else if (type == "video") {
    jQuery("#" + new_prefix).attr({
      id: new_prefix,
      src: jQuery("#" + prefix).attr("src"),
      "class": "wds_draggable_" + id + " wds_draggable",
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + "; " +
             "left: " + jQuery("#" + new_prefix + "_left").val() + "px;" +
             "top: " + jQuery("#" + new_prefix + "_top").val() + "px;" +
             "opacity: " + (100 - jQuery("#" + prefix + "_imgtransparent").val()) / 100 + "; filter: Alpha(opacity=" + (100 - jQuery("#" + prefix+ "_imgtransparent").val()) + "); " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + "; " +
             "box-shadow: " + jQuery("#" + prefix + "_shadow").val() + "; " +
             "position: absolute;"
    });
    wds_scale("#" + new_prefix + "_image_scale", new_prefix);
  }
  else if (type == "social") {
    jQuery("#" + new_prefix).attr({
      id: new_prefix,
      "class": "wds_draggable_" + id + " wds_draggable ui-draggable fa fa-" + jQuery("#" + prefix+ "_social_button").val(),
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + "; " +
             "left: " + jQuery("#" + new_prefix + "_left").val() + "px;" +
             "top: " + jQuery("#" + new_prefix + "_top").val() + "px;" +
             "color: #" + jQuery("#" + prefix + "_color").val() + "; " +
             "font-size: " + jQuery("#" + prefix + "_size").val() + "px; " +
             "line-height: 1.25em; " +
             "padding: " + jQuery("#" + prefix + "_padding").val() + "; " +
             "opacity: " + (100 - jQuery("#" + prefix + "_imgtransparent").val()) / 100 + "; filter: Alpha(opacity=" + (100 - jQuery("#" + prefix+ "_imgtransparent").val()) + "); " +
             "position: absolute;"
    });
    jQuery("#" + new_prefix).hover(function() { jQuery(this).css("color", jQuery("#" + prefix + "_hover_color").val()); }, function() { jQuery(this).css("color", jQuery("#" + prefix + "_color").val()); });
  }
  else if (type == "hotspots") {
    jQuery("#" + new_prefix + '_div').attr({
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + ";" +
             "left: " + jQuery("#" + new_prefix + "_div_left").val() + "px;" +
             "top: " + jQuery("#" + new_prefix + "_div_top").val() + "px;" +
             "display: inline-block;" + 
             "width: " + jQuery("#" + prefix + "_hotp_width").val() + "px;" +
             "height: " + jQuery("#" + prefix + "_hotp_width").val() + "px;" +
             "position: absolute;"	
    });
    jQuery("#" + new_prefix + '_round').attr({
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + ";" +
             "width: " + jQuery("#" + prefix + "_hotp_width").val() + "px;" +
             "height: " + jQuery("#" + prefix + "_hotp_width").val() + "px;" +
             "background-color: #" + jQuery("#" + prefix + "_hotp_fbgcolor").val()+ ";" +
             "border-radius: " + jQuery("#" + prefix + "_hotp_border_radius").val() + ";" +
			       "border: " + jQuery("#" + prefix + "_round_hotp_border_width").val() + "px " + jQuery("#" + prefix+ "_round_hotp_border_style").val() + " #" + jQuery("#" + prefix+ "_hotp_border_color").val() + ";" +
             "position: absolute;" +
             "display: block;" +
             "left: 0;" +
             "top: 0;" +
             "opacity: 1 !important"
    });
    jQuery("#" + new_prefix + '_round_effect').attr({
      style: "width: " + jQuery("#" + new_prefix + "_hotp_width").val() + "px;" +
             "height: " + jQuery("#" + new_prefix + "_hotp_width").val() + "px;" +
             "background-color: rgba(0, 0, 0, 0.360784);" +
             "border-radius: " + jQuery("#" + prefix + "_hotp_border_radius").val() + ";" +
             "border: " + jQuery("#" + prefix + "_round_hotp_border_width").val() + "px " + jQuery("#" + prefix+ "_round_hotp_border_style").val() + " transparent;" +
             "position: absolute;" +
             "display: block;" +
             "left: 0;" +
             "top: 0;" +
             "padding: 0;" +
             "animation: point-anim 1.5s infinite;" +
             "-moz-animation: point-anim 1.5s infinite;" +
             "-webkit-animation: point-anim 1.5s infinite;" +
             "-o-animation: point-anim 1.5s infinite;"
    });
    jQuery("#" + new_prefix).attr({
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + ";" +
             "display: none; " +
             "color: #" + jQuery("#" + prefix + "_color").val() + "; " +
             (jQuery("#" + prefix + "_image_width").val() != 0 ? "width: " + jQuery("#" + prefix + "_image_width").val() + "px; " : "white-space: nowrap;") +
             (jQuery("#" + prefix + "_image_height").val() != 0 ? "height: " + jQuery("#" + prefix + "_image_height").val() + "px; " : "") +
             "font-size: " + jQuery("#" + prefix + "_size").val() + "px; " +
             "line-height: 1.25em; " +
             "font-family: " + jQuery("#" + prefix).css("font-family") + "; " +
             "font-weight: " + jQuery("#" + prefix + "_fweight").val() + "; " +
             "padding: " + jQuery("#" + prefix + "_padding").val() + "; " +
             "background-color: " + wds_hex_rgba(jQuery("#" + prefix+ "_fbgcolor").val(), (100 - jQuery("#" + prefix+ "_transparent").val())) + "; " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + ";" +
             "box-shadow: " + jQuery("#" + prefix + "_shadow").val() + ";" +
             "text-align: " + jQuery("#" + prefix + "_text_alignment").val() + ";" + 
             "position: absolute;"
    });
    jQuery("#" + new_prefix + "_before").attr({
      "class": "hotspot_text_before"
    });
    wds_break_word("#" + new_prefix + "_image_scale", new_prefix);
    if ( !wds_object.is_free ) {
      wds_hotspot_position(new_prefix);
    }
  }
  jscolor.bind();
  wds_drag_layer(id);
}

function wds_duplicate_slide(slide_id) {
  var new_slide_id = wds_add_slide();
  var type;
  var prefix;
  var layer_id;
  var tab_image = jQuery('#wds_tab_image' + slide_id).css('background-image');
  jQuery("input[name=published" + new_slide_id + "]:checked").val(jQuery("input[name=published" + slide_id + "]:checked").val());
  jQuery("#link" + new_slide_id).val(jQuery("#link" + slide_id).val());
  jQuery("input[name=target_attr_slide" + new_slide_id +" ]:checked").val(jQuery("input[name=target_attr_slide" + slide_id +" ]:checked").val());
  jQuery("#type" + new_slide_id).val(jQuery("#type" + slide_id).val());
  jQuery("#image_url" + new_slide_id).val(jQuery("#image_url" + slide_id).val());
  jQuery("#thumb_url" + new_slide_id).val(jQuery("#thumb_url" + slide_id).val());
  jQuery("#att_width" + new_slide_id).val(jQuery("#att_width" + slide_id).val());		  
  jQuery("#att_height" + new_slide_id).val(jQuery("#att_height" + slide_id).val());		  
  jQuery("#video_duration" + new_slide_id).val(jQuery("#video_duration" + slide_id).val());	
  if (jQuery("#type" + new_slide_id).val() == 'video') { 
    jQuery("#post_id" + new_slide_id).val(jQuery("#thumb_url" + slide_id).val());
    jQuery("#link" + new_slide_id).val(jQuery("input[name=controls" + slide_id + " ]:checked").val());
    jQuery("input[name=wds_slide_autoplay" + new_slide_id +" ]:checked").val(jQuery("input[name=wds_slide_autoplay" + slide_id +" ]:checked").val());
  }
  if (jQuery("#type" + new_slide_id).val() == 'EMBED_OEMBED_YOUTUBE_VIDEO') { 
    jQuery("input[name=youtube_rel_video" + new_slide_id +" ]:checked").val(jQuery("input[name=youtube_rel_video" + slide_id +" ]:checked").val());
  }
  if (jQuery("#type" + new_slide_id).val() == 'image') {
    jQuery("#wds_preview_image" + new_slide_id).css("background-image", 'url("' + jQuery("#image_url" + slide_id).val() + '")');
    jQuery("#wds_tab_image" + new_slide_id).css("background-image", tab_image );
    jQuery("#trlink" + new_slide_id).show();
    jQuery("#controls" + new_slide_id).hide();
    jQuery("#autoplay" + new_slide_id).hide();
    jQuery(".edit_thumb").text(wds_object.translate.edit_thumbnail);
  }
  else {
    jQuery("#wds_preview_image" + new_slide_id).css("background-image", 'url("' + jQuery("#thumb_url" + slide_id).val() + '")');
    jQuery("#wds_tab_image" + new_slide_id).css("background-image", tab_image );
    jQuery("#trlink" + new_slide_id).hide();
    jQuery("#controls" + new_slide_id).show();
    jQuery("#autoplay" + new_slide_id).show();
    jQuery(".edit_thumb").text(wds_object.translate.edit_thumbnail);
  }
  var layer_ids_string = jQuery("#slide" + slide_id + "_layer_ids_string").val();
  if (layer_ids_string) {
    var layer_ids_array = layer_ids_string.split(",");
    for (var i in layer_ids_array) {
      if (layer_ids_array.hasOwnProperty(i) && layer_ids_array[i] && layer_ids_array[i] != ",") {
      layer_id = layer_ids_array[i];
      prefix = "slide" + slide_id + "_layer" + layer_id;
      type = jQuery("#" + prefix + "_type").val();		
      wds_add_layer(type, new_slide_id, '', 1);
      wds_duplicate_layer(type, slide_id, layer_id, new_slide_id);
      }
    }
  }
}

var wds_layerID = 0;
function wds_add_layer(type, id, layerID, duplicate, files, edit) {
  jQuery(".wds_layer_content").hide();
  jQuery(".wds_layer_head_tr").attr("style", "background-color : #e1e1e1" );
  var laydef_options = wds_object.LDO;
  var layers_count = jQuery(".wds_slide" + id + " .layer_table_count").length;
  wds_layerID = layers_count + 1;
  if (typeof layerID == "undefined" || layerID == "") {
    var layerID = "pr_" + wds_layerID;
    jQuery("#slide" + id + "_layer_ids_string").val(jQuery("#slide" + id + "_layer_ids_string").val() + layerID + ',');
  }
  if (typeof duplicate == "undefined") {
    var duplicate = 0;
  }
  if (typeof edit == "undefined") {
    var edit = 0;
  }

  var layer_effects_in_option = "";
  var layer_effects_out_option = "";
  var free_layer_effects = ['none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight'];
  var layer_effects_in = {
    'none' : wds_object.translate.none,
    'bounce' : wds_object.translate.bounce,
    'tada' : wds_object.translate.tada, 
    'flash' : wds_object.translate.flash,
    'pulse' : wds_object.translate.pulse,
    'shake' : wds_object.translate.shake,
    'swing' : wds_object.translate.swing,
    'wobble' : wds_object.translate.wobble,
    'hinge' : wds_object.translate.hinge,
    'rubberBand' : wds_object.translate.rubberBand,
    'lightSpeedIn' : wds_object.translate.lightSpeedIn,
    'rollIn' : wds_object.translate.rollIn,	
    
    'bounceIn' : wds_object.translate.bounceIn,
    'bounceInDown' : wds_object.translate.bounceInDown,
    'bounceInLeft' : wds_object.translate.bounceInLeft,
    'bounceInRight' : wds_object.translate.bounceInRight,
    'bounceInUp' : wds_object.translate.bounceInUp,
   
    'fadeIn' : wds_object.translate.fadeIn,
    'fadeInDown' : wds_object.translate.fadeInDown,
    'fadeInDownBig' : wds_object.translate.fadeInDownBig,
    'fadeInLeft' : wds_object.translate.fadeInLeft,
    'fadeInLeftBig' : wds_object.translate.fadeInLeftBig,
    'fadeInRight' : wds_object.translate.fadeInRight,
    'fadeInRightBig' : wds_object.translate.fadeInRightBig,
    'fadeInUp' : wds_object.translate.fadeInUp,
    'fadeInUpBig' : wds_object.translate.fadeInUpBig,

    'flip' : wds_object.translate.flip,
    'flipInX' : wds_object.translate.flipInX,
    'flipInY' : wds_object.translate.flipInY,

    'rotateIn' : wds_object.translate.rotateIn,
    'rotateInDownLeft' : wds_object.translate.rotateInDownLeft,
    'rotateInDownRight' : wds_object.translate.rotateInDownRight,
    'rotateInUpLeft' : wds_object.translate.rotateInUpLeft,
    'rotateInUpRight' : wds_object.translate.rotateInUpRight,
	
    'zoomIn' : wds_object.translate.zoomIn,
    'zoomInDown' : wds_object.translate.zoomInDown,
    'zoomInLeft' : wds_object.translate.zoomInLeft,
    'zoomInRight' : wds_object.translate.zoomInRight,
    'zoomInUp' : wds_object.translate.zoomInUp,
  };

  var layer_effects_out = {
    'none' : wds_object.translate.none,
    'bounce' : wds_object.translate.bounce,
    'flash' : wds_object.translate.flash,
    'pulse' : wds_object.translate.pulse,
    'tada' : wds_object.translate.tada,
    'shake' : wds_object.translate.shake,
    'swing' : wds_object.translate.swing,
    'wobble' : wds_object.translate.wobble,
    'hinge' : wds_object.translate.hinge,
    'rubberBand' : wds_object.translate.rubberBand,
  	'lightSpeedOut' : 'LightSpeedOut',
    'rollOut' : 'RollOut',

    'bounceOut' : wds_object.translate.bounceOut,
    'bounceOutDown' : wds_object.translate.bounceOutDown,
    'bounceOutLeft' : wds_object.translate.bounceOutLeft,
    'bounceOutRight' : wds_object.translate.bounceOutRight,
    'bounceOutUp' : wds_object.translate.bounceOutUp,
    
    'fadeOut' : wds_object.translate.fadeOut,
    'fadeOutDown' : wds_object.translate.fadeOutDown,
    'fadeOutDownBig' : wds_object.translate.fadeOutDownBig,
    'fadeOutLeft' : wds_object.translate.fadeOutLeft,
    'fadeOutLeftBig' : wds_object.translate.fadeOutLeftBig,
    'fadeOutRight' : wds_object.translate.fadeOutRight,
    'fadeOutRightBig' : wds_object.translate.fadeOutRightBig,
    'fadeOutUp' : wds_object.translate.fadeOutUp,
    'fadeOutUpBig' : wds_object.translate.fadeOutUpBig,

    'flip' : wds_object.translate.flip,
    'flipOutX' : wds_object.translate.flipOutX,
    'flipOutY' : wds_object.translate.flipOutY,

    'rotateOut' : wds_object.translate.rotateOut,
    'rotateOutDownLeft' : wds_object.translate.rotateOutDownLeft,
    'rotateOutDownRight' : wds_object.translate.rotateOutDownRight,
    'rotateOutUpLeft' : wds_object.translate.rotateOutUpLeft,
    'rotateOutUpRight' : wds_object.translate.rotateOutUpRight,

    'zoomOut' : wds_object.translate.zoomOut,
    'zoomOutDown' : wds_object.translate.zoomOutDown,
    'zoomOutLeft' : wds_object.translate.zoomOutLeft,
    'zoomOutRight' : wds_object.translate.zoomOutRight,
    'zoomOutUp' : wds_object.translate.zoomOutUp
  };

  for (var i in layer_effects_in) {
    layer_effects_in_option += '<option ' +
      ((wds_object.is_free && jQuery.inArray(i, free_layer_effects) == -1) ? 'disabled="disabled" title="'+ wds_object.translate.disabled_in_free_version +'"' : '') +
      ' value="' + i + '" ' + (i == laydef_options.default_layer_effect_in ? 'selected' : '') + '>' + layer_effects_in[i] + '</option>';
  }
  for (var i in layer_effects_out) {
    layer_effects_out_option += '<option value="' + i + '" ' + (i == laydef_options.default_layer_effect_out ? 'selected' : '') + '>' + layer_effects_out[i] + '</option>';
    layer_effects_out_option += '<option ' +
      ((wds_object.is_free && jQuery.inArray(i, free_layer_effects) == -1) ? 'disabled="disabled" title="'+ wds_object.translate.disabled_in_free_version +'"' : '') +
      ' value="' + i + '" ' + (i == laydef_options.default_layer_effect_out ? 'selected' : '') + '>' + layer_effects_out[i] + '</option>';
  }
  
  var font_weights_option = "";
  var font_weights = {
		'lighter' : 'Lighter',
		'normal' : 'Normal',
		'bold' : 'Bold'
		};
  for (var i in font_weights) {
    font_weights_option += '<option value="' + i + '" ' + (i == laydef_options.default_layer_fweight ? 'selected' : '') + '>' + font_weights[i] + '</option>';
  }
  var border_styles_option = "";
  var border_styles = {
	  'none' : 'None',
	  'solid' : 'Solid', 
	  'dotted' : 'Dotted',
	  'dashed' : 'Dashed',
	  'double' : 'Double', 
	  'groove' : 'Groove', 
	  'ridge' : 'Ridge', 
	  'inset' : 'Inset',
	  'outset' : 'Outset'
	};
  for (var i in border_styles) {
    border_styles_option += '<option value="' + i + '">' + border_styles[i] + '</option>';
  }
  var social_button_option = "";
  var social_button = {
		"facebook" : "Facebook", 
		"twitter" : "Twitter", 
		"pinterest" : "Pinterest", 
		"tumblr" : "Tumblr"
	};
  for (var i in social_button) {
    social_button_option += '<option value="' + i + '">' + social_button[i] + '</option>';
  }

  var uploader_href_for_add_slide = uploader_href.replace('slideID', id);
  var uploader_href_for_add_layer = uploader_href_for_add_slide.replace('add_update_slide', 'add_update_layer').replace('layerID', layerID);
  var upload_href_for_change_thumb =  uploader_href_for_add_slide.replace('add_update_slide', 'add_update_thumbnail');
  var prefix = "slide" + id + "_layer" + layerID;
  var tbodyID = prefix + "_tbody";

  var hotptext_pos = "";
  var hotp_text_positions = {
		'top' : 'Top', 
		'bottom' : 'Bottom', 
		'left' : 'Left', 
		'right' : 'Right'
	};
  for (var i in hotp_text_positions) {
    hotptext_pos += '<option value="' + i + '" ' + (i == 'right' ? 'selected' : '') + '>' + hotp_text_positions[i] + '</option>';
  }

  var layer_callback_list_option = "";
  var layer_callbacks = {
	'' : 'Select action', 
	'SlidePlay' : 'Play', 
	'SlidePause' : 'Pause', 
	'SlidePlayPause' : 'Play/Pause', 
	'SlideNext' : 'Next slide', 
	'SlidePrevious' : 'Previous slide',
	'SlideLink' : 'Link to slide', 
	'PlayMusic' : 'Play music'
	};
  for (var i in layer_callbacks) {
    layer_callback_list_option += '<option value="' + i + '">' + layer_callbacks[i] + '</option>';
  }
  var text_alignments_option = "";
  var text_alignments = {
		'center' : 'Center',
		'left' : 'Left', 
		'right' : 'Right'
	};
  for (var i in text_alignments) {
    text_alignments_option += '<option value="' + i + '">' + text_alignments[i] + '</option>';
  }
  var link_to_slide_option = "";
  var link_to_slide = [];
  jQuery(".wds_tab_title").each(function(){
    link_to_slide.push();
    link_to_slide_option += '<option value="' + jQuery(this).attr('name').replace('title', '') + '">' + jQuery(this).val() + '</option>';
  });
  jQuery(".wds_slide" + id + ">table").append(jQuery("<tbody />").attr("id", tbodyID));
  jQuery('#' + tbodyID).attr('style',"background-color:#fff");
  jQuery('#' + tbodyID).addClass("layer_table_count");
  var tbody = '<tr class="wds_layer_head_tr">' +
                '<td colspan="4" class="wds_layer_head">' +
                  '<div class="wds_layer_left"><div class="layer_handle handle connectedSortable" title="'+ wds_object.translate.drag_to_re_order +'"></div>' +
                  '<span class="wds_layer_label" onclick="wds_showhide_layer(\'' + tbodyID + '\', 0)"><input id="' + prefix + '_title" name="' + prefix + '_title" type="text" class="wds_layer_title" style="width: 120px; padding:5px; color:#00A2D0; " value="'+ wds_object.translate.layer +' ' + wds_layerID + '" title="Layer title" /></span></div>' +
                  '<div class="wds_layer_right"><span class="wds_layer_remove" title="'+ wds_object.translate.delete_layer +'" onclick="wds_delete_layer(\'' + id + '\', \'' + layerID + '\')"></span>' +
                  '<span class="wds_layer_dublicate" title="'+ wds_object.translate.duplicate_layer +'" onclick="wds_add_layer(\'' + type + '\', \'' + id + '\', \'\', 1); wds_duplicate_layer(\'' + type + '\', \'' + id + '\', \'' + layerID + '\');"></span>' +
                  '<input type="text" name="' + prefix + '_depth" id="' + prefix + '_depth" prefix="' + prefix + '" value="' + wds_layerID + '" class="wds_layer_depth spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="change_zindex(this,\''+prefix+'\')" title="z-index" /></div><div class="wds_clear"></div></td>' +
              '</tr>';

  switch(type) {
    case 'text': {
      jQuery("#wds_preview_image" + id).append(jQuery("<span />").attr({
        id: prefix,
        "class": "wds_draggable_" + id + " wds_draggable",
        "data-type": "wds_text_parent",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
        "word-break: normal;" +
        "display: inline-block; " +
        "position: absolute;" +
        "left: 0; top: 0; " +
        "color: #FFFFFF; " +
        "font-size: 18px; " +
        "line-height: 1.25em; " +
        "font-family: Arial; " +
        "font-weight: normal; " +
        "padding: 5px; " +
        "background-color: " + wds_hex_rgba('000000', 50) + "; " +
        "border-radius: 2px;"
      }).html("Sample text"));
      jQuery("#" + tbodyID).append(tbody);
      str = jQuery(".wds_textLayer").html();
      tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
      tbody_html = tbody_html.replace(/%%slideID%%/g, id);
      jQuery('#' + prefix + '_tbody').append('<tr style="display:none" class="wds_layer_content">'+tbody_html+'</tr>');
      jQuery('#' + prefix + '_tbody .wds_layer_content').show();
      wds_change_fonts(prefix);
      break;
    }
    case 'image': {
      if (edit == 0) {
        str = jQuery(".wds_imageLayer").html();
        var tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
        tbody_html = tbody + '<tr class="wds_layer_content">' + tbody_html.replace(/%%slideID%%/g, id) + '</tr>';
      }
      if (!duplicate) {
        if (spider_uploader_) { /* Add image layer by spider uploader.*/
          wds_add_image_layer_by_spider_uploader(prefix, files, tbodyID, id, layerID, tbody_html);
        }
        else { /* Add image layer by media uploader.*/
          image_escape = wds_add_image_layer(prefix, tbodyID, id, layerID, tbody_html, edit);
        }
      }
      else {
        jQuery('#' + prefix + '_tbody').append(tbody_html);
        jQuery('#' + prefix + '_tbody wds_imageLayer').show();
      }
      break;
    }
    case 'video': {
      jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
        id: prefix,
        "class": "wds_draggable_" + id + " wds_draggable",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "max-height: 100%; max-width: 100%;" +
        "z-index: " + layerID.replace("pr_", "") + "; " +
        "left: 0; top: 0; " +
        "border: 2px none #FFFFFF; " +
        "border-radius: 2px; " +
        "position: absolute;"
      }));

      str = jQuery(".wds_videoLayer").html();
      tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
      tbody_html = tbody+'<tr class="wds_layer_content">'+tbody_html.replace(/%%slideID%%/g, id)+'</tr>';
      jQuery('#' + prefix + '_tbody').append(tbody_html);
      jQuery('#' + prefix + '_tbody .wds_videoLayer').show();
      if (!duplicate) {
        if (!wds_object.is_free) {
          if (wds_add_embeded_video(files, 'layer', prefix)) {
            jQuery('.opacity_add_video').hide();
          }
        }
      }
      break;
    }
    case 'upvideo': {
      if (edit == 0) {
        str = jQuery(".wds_upvideoLayer").html();
        var tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
        tbody_html = tbody + '<tr class="wds_layer_content">' + tbody_html.replace(/%%slideID%%/g, id) + '</tr>';
      }
      if (!duplicate) {
        image_escape = wds_add_video_layer(prefix, tbodyID, id, layerID, tbody_html, edit);
      }
      else {
        jQuery('#' + prefix + '_tbody').append(tbody_html);
        jQuery('#' + prefix + '_tbody .wds_upvideoLayer').show();
      }
      break;
    }
    case 'social': {
      jQuery("#wds_preview_image" + id).append(jQuery("<i />").attr({
        id: prefix,
        "class": "wds_draggable_" + id + " wds_draggable fa fa-facebook",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
        "left: 0; top: 0; " +
        "color: #FFFFFF; " +
        "font-size: 18px; " +
        "line-height: 18px; " +
        "padding: 5px; " +
        "opacity: 1; filter: Alpha(opacity=100); " +
        "position: absolute;"
      }));
      jQuery("#" + tbodyID).append(tbody);
      str = jQuery(".wds_socialLayer").html();
      tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
      tbody_html = tbody_html.replace(/%%slideID%%/g, id);
      jQuery('#' + prefix + '_tbody').append('<tr style="display:none" class="wds_socialLayer wds_layer_content">'+tbody_html+'</tr>');
      jQuery('#' + prefix + '_tbody .wds_socialLayer').show();
      break;
    }
    case 'hotspots': {
      jQuery("#wds_preview_image" + id).append(jQuery("<span />").attr({
        id: prefix + '_div',
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        "class": "hotspot_container wds_draggable_" + id + " wds_draggable",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
        "display: inline-block; " +
        "width: 20px;" +
        "height: 20px; " +
        "position: absolute;" +
        "left: 20px;" +
        "top: 20px;"
      }));
      jQuery("#" + prefix + "_div").append(jQuery("<span />").attr({
        id: prefix + "_round",
        "data-displaytype": "hover",
        "class": "wds_layer_" + id + " wds_layer",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
        "width: 20px;" +
        "height: 20px; " +
        "top: 0; " +
        "left: 0; " +
        "display: block; " +
        "opacity: 1 !important; " +
        "position: absolute;" +
        "background-color: #FFFFFF; " +
        "border-radius: 25px;"
      }));
      jQuery("#" + prefix + "_round").after(jQuery("<span />").attr({
        id: prefix + "_round_effect",
        "class": "wds_layer_" + id + " wds_layer",
        style: "z-index: " + (layerID.replace("pr_", "") - 1) + "; " +
        "width: 20px;" +
        "height: 20px; " +
        "top: 0; " +
        "left: 0; " +
        "position: absolute;" +
        "border-radius: 25px;" +
        "animation: point-anim 1.5s infinite;" +
        "-moz-animation: point-anim 1.5s infinite;" +
        "-webkit-animation: point-anim 1.5s infinite;" +
        "-o-animation: point-anim 1.5s infinite;" +
        "background-color: " + wds_hex_rgba('000000', 50) + ";"
      }));
      jQuery("#" + prefix + "_round_effect").after(jQuery("<span />").attr({
        id: prefix,
        "class": "wds_hotspot_text",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
        "word-break: normal;" +
        "white-space: nowrap;" +
        "display: none; " +
        "position: absolute;" +
        "left: 40px;" +
        "top: 0; " +
        "color: #FFFFFF;" +
        "font-size: 18px;" +
        "line-height: 1.25em;" +
        "font-family: Arial;" +
        "font-weight: normal;" +
        "padding: 5px;" +
        "background-color: " + wds_hex_rgba('000000', 50) + ";" +
        "border-radius: 2px;"
      }).html("Sample text"));
      jQuery("#" + prefix).append(jQuery("<span />").attr({
        id: prefix + "_before",
        "class": "hotspot_text_before",
        style: "left: -7px;" +
        "top: 9px;" +
        "border-top: 7px solid transparent; " +
        "border-bottom: 7px solid transparent; " +
        "border-left: 7px solid " + wds_hex_rgba('000000', 50) + "; " +
        "display: inline-block;"
      }));

      jQuery("#" + tbodyID).append(tbody);
      str = jQuery(".wds_hotspotLayer").html();
      tbody_html = str.replace(/%%LayerId%%/g, wds_layerID);
      tbody_html = tbody_html.replace(/%%slideID%%/g, id);
      jQuery('#' + prefix + '_tbody').append('<tr style="display:none" class="wds_hotspotLayer wds_layer_content">'+tbody_html+'</tr>');
      jQuery('#' + prefix + '_tbody .wds_hotspotLayer').show();
      if ( !wds_object.is_free ) {
        wds_display_hotspot();
        wds_hotspot_position();
      }
      wds_change_fonts(prefix);
      break;
    }
    default: {
      break;
    }
  }
  if (!duplicate) {
    wds_drag_layer(id);
    jscolor.bind();
  }
  wds_layer_weights(id);
  wds_onkeypress();

  setDataFormElement();
  return layerID;
}

function wds_scale(that, prefix) {
  var wds_theImage = new Image();
  wds_theImage.src = jQuery("#" + prefix).attr("src");
  var wds_origWidth = wds_theImage.width;
  var wds_origHeight = wds_theImage.height;
  var width = jQuery("#" + prefix + "_image_width").val();
  var height = jQuery("#" + prefix + "_image_height").val();
  jQuery("#" + prefix).css({maxWidth: width + "px", maxHeight: height + "px", width: "", height: ""});
  if (!jQuery(that).is(':checked') || !jQuery(that).val()) {
    jQuery("#" + prefix).css({width: width + "px", height: height + "px"});
  }
  else if (wds_origWidth <= width || wds_origHeight <= height) {
    if (wds_origWidth / width > wds_origHeight / height) {
      jQuery("#" + prefix).css({width: width + "px"});
    }
    else {
      jQuery("#" + prefix).css({height: height + "px"});
    }
  }
}

function wds_drag_layer(id) {
  jQuery(".wds_draggable_" + id).draggable({ containment: "#wds_preview_wrapper_" + id, scroll: false });
  jQuery(".wds_draggable_" + id).bind('dragstart', function(event) {
    jQuery(this).addClass('wds_active_layer');
  }).bind('drag', function(event) {
    var prefix = jQuery(this).attr("id");
    var check = jQuery('#' + prefix + '_align_layer').is(":checked");
    if (!check) {
      jQuery("#" + prefix + "_left").val(parseInt(jQuery(this).offset().left - jQuery(".wds_preview_image" + id).offset().left));
    }
    jQuery("#" + prefix + "_top").val(parseInt(jQuery(this).offset().top - jQuery(".wds_preview_image" + id).offset().top));

    /* Do not set layer width/height on drag.*/
    if (jQuery("#" + prefix + "_image_width").val() == 0) {
      jQuery("#" + prefix).css({'width': ''});
    }
    if (jQuery("#" + prefix + "_image_height").val() == 0) {
      jQuery("#" + prefix).css({'height': ''});
    }

    if ( !wds_object.is_free ) {
      wds_hotspot_position(prefix.slice(0, -4));
    }
  });
  jQuery(".wds_draggable_" + id).bind('dragstop', function(event) {
    jQuery(this).removeClass('wds_active_layer');
    var prefix = jQuery(this).attr("id");
    var check = jQuery('#' + prefix + '_align_layer').is(":checked");
    var left = parseInt(jQuery(this).offset().left - jQuery(".wds_preview_image" + id).offset().left);
    var layer_center = left + jQuery("#" + prefix).width() / 2;
    var pos_center = -jQuery("#" + prefix).width() / 2 + jQuery(".wds_preview_image" + id).width() / 2;
    var pos_rigth = (jQuery(".wds_preview_image" + id).width() - jQuery("#" + prefix).width()) - 2 * parseInt(jQuery("#" + prefix + "_padding").val());
    if (check) {
      /*center*/
      if ((layer_center > jQuery(".wds_preview_image" + id).width() / 4 && layer_center < jQuery(".wds_preview_image" + id).width() / 2) || (layer_center >jQuery(".wds_preview_image" + id).width() / 2 && layer_center <= 3 * jQuery(".wds_preview_image" + id).width() / 4)) {
        jQuery("#" + prefix).css({left:pos_center + 'px'});
        jQuery("#" + prefix + "_left").val(parseInt(pos_center));
      }
      /*right*/
      else if (layer_center > (3 * jQuery(".wds_preview_image" + id).width() / 4) && layer_center < jQuery(".wds_preview_image" + id).width()) {
        jQuery("#" + prefix).css({left:pos_rigth + 'px'});
        jQuery("#" + prefix + "_left").val(parseInt(pos_rigth));
      }
      /*left*/
      else if (layer_center > 0 && layer_center <= jQuery(".wds_preview_image" + id).width() / 4){
       jQuery("#" + prefix).css({left:'0px'});
       jQuery("#" + prefix + "_left").val(0);
      }
    }
  });
}

function wds_layer_weights(id) {
  jQuery(".ui-sortable" + id + "").sortable({
    handle: ".connectedSortable",
    connectWith: ".connectedSortable",
    update: function (event) {
      var i = 1;
      jQuery(".wds_slide" + id + " .wds_layer_depth").each(function (e) {
        if (jQuery(this).val()) {
          jQuery(this).val(i++);
          prefix = jQuery(this).attr("prefix");
          jQuery("#" + prefix).css({zIndex: jQuery(this).val()});
        }
      });
    }
  });
  /* TODO. remove
  .disableSelection();
  jQuery(".ui-sortable").sortable("enable"); 
  */
}

function wds_slide_weights() {
  jQuery(".aui-sortable").sortable({
    connectWith: ".connectedSortable",
    items: ".connectedSortable",
    update: function (event) {
      var i = 1;
      jQuery(".wbs_subtab input[id^='order']").each(function (e) {
        if (jQuery(this).val()) {
          jQuery(this).val(i++);
        }
      });
    }
  });
  jQuery(".aui-sortable").disableSelection();
}

function wds_add_video_layer(prefix, tbodyID, id, layerID, tbody_html, edit) {
  var custom_uploader;
  /*event.preventDefault();*/
  /* If the uploader object has already been created, reopen the dialog.*/
  if (custom_uploader) {
    custom_uploader.open();
    return;
  }
 
  if (typeof edit == "undefined") {
    var edit = 0;
  }
  /* Extend the wp.media object. */
  custom_uploader = wp.media.frames.file_frame = wp.media({
    title: wds_object.translate.choose_video,
    library : { type : 'video'},
    button: { text: wds_object.translate.insert },
    multiple: false
  });
  /* When a file is selected, grab the URL and set it as the text field's value.*/
  custom_uploader.on('select', function() {
    jQuery("#" + tbodyID).append(tbody_html);
    attachment = custom_uploader.state().get('selection').first().toJSON();
    if (typeof attachment.image != "undefined") {
      if (attachment.image.src.indexOf('media/video.png') != '-1') {
        thumb_url = WD_S_URL + '/images/no-video.png';
      }
      else {
        thumb_url = attachment.image.src;
      }
    }
    else {
      thumb_url = WD_S_URL + '/images/no-video.png';
    }
    if (edit == 0) {
      jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
        id: prefix,
        "class": "wds_draggable_" + id + " wds_draggable",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
             "left: 0; top: 0; " +
             "border: 2px none #FFFFFF; " +
             "border-radius: 2px; " +
             "opacity: 1; filter: Alpha(opacity=100); " +
             "position: absolute;"		  
		  }));

      jQuery("#" + prefix + "_layer_post_id").val(attachment.id);
      jQuery("#" + prefix + "_attr_width").val(attachment.width);
      jQuery("#" + prefix + "_attr_height").val(attachment.height);
      jQuery("#" + prefix + "_link").val(attachment.url);
      var ratio = attachment.width / attachment.height ;
			jQuery("#" + prefix + "_image_width").val(300);
      jQuery("#" + prefix + "_image_height").val(parseInt(300 / ratio));
    }
    jQuery("#" + prefix + "_image_url").val(thumb_url);
    jQuery("#" + prefix).attr("src", thumb_url);
    wds_scale("#" + prefix + "_image_scale", prefix);
    wds_drag_layer(id);
    jscolor.bind();
        
  });

  /* Open the uploader dialog.*/
  custom_uploader.open();
}

function wds_add_image_layer(prefix, tbodyID, id, layerID, tbody_html, edit) {
  var custom_uploader;
  /*event.preventDefault();*/
  /* If the uploader object has already been created, reopen the dialog.*/
  if (custom_uploader) {
    custom_uploader.open();
    return;
  }
  if (typeof edit == "undefined") {
    var edit = 0;
  }
  /* Extend the wp.media object.*/
  custom_uploader = wp.media.frames.file_frame = wp.media({
    title:  wds_object.translate.choose_image,
    library : { type : 'image'},
    button: { text: wds_object.translate.insert },
    multiple: false
  });
  /* When a file is selected, grab the URL and set it as the text field's value*/
  custom_uploader.on('select', function() {
    jQuery("#" + tbodyID).append(tbody_html);
    attachment = custom_uploader.state().get('selection').first().toJSON();
    if (edit == 0) {
      jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
        id: prefix,
        "class": "wds_draggable_" + id + " wds_draggable",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        src: attachment.url,
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
               "left: 0; top: 0; " +
               "border: 2px none #FFFFFF; " +
               "border-radius: 2px; " +
               "opacity: 1; filter: Alpha(opacity=100); " +
               "position: absolute;"
      }));

      var att_width = attachment.width ? attachment.width : jQuery("#" + prefix).width();
      var att_height = attachment.height ? attachment.height : jQuery("#" + prefix).height();
      var width = Math.min(att_width, jQuery("#wds_preview_image" + id).width());
      var height = Math.min(att_height, jQuery("#wds_preview_image" + id).height());

      jQuery("#" + prefix + "_image_url").val(attachment.url);
      jQuery("#" + prefix + "_image_width").val(width);
      jQuery("#" + prefix + "_image_height").val(height);
      jQuery("#" + prefix + "_image_scale").attr("checked", "checked");
      wds_scale("#" + prefix + "_image_scale", prefix);
    }
    else {
      jQuery("#" + prefix).attr("src", attachment.url);
      jQuery("#" + prefix + "_image_url").val(attachment.url);
    }
    wds_drag_layer(id);
    jscolor.bind();
  });

  /* Open the uploader dialog.*/
  custom_uploader.open();
}

function wds_add_image_layer_by_spider_uploader(prefix, files, tbodyID, id, layerID, tbody_html) {
  var file_resolution = [];
  jQuery("#" + tbodyID).append(tbody_html);	
    jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
      id: prefix,
      class: "wds_draggable_" + id + " wds_draggable",
      onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
      src: files[0]['url'],
      style: "z-index: " + layerID.replace("pr_", "") + "; " +
             "left: 0; top: 0; " +
             "border: 2px none #FFFFFF; " +
             "border-radius: 2px; " +
             "opacity: 1; filter: Alpha(opacity=100); " +
             "position: absolute;"
    }));
	
    file_resolution = files[0]['resolution'].split('x');	
    var file_width = parseInt(file_resolution[0]) ? parseInt(file_resolution[0]) : jQuery("#" + prefix).width();
    var file_height = parseInt(file_resolution[1]) ? parseInt(file_resolution[1]) : jQuery("#" + prefix).height();
    var width = Math.min(file_width, jQuery("#wds_preview_image" + id).width());
    var height = Math.min(file_height, jQuery("#wds_preview_image" + id).height());

    jQuery("#" + prefix + "_image_url").val(files[0]['url']);
    jQuery("#" + prefix + "_image_width").val(width);
    jQuery("#" + prefix + "_image_height").val(height);
    jQuery("#" + prefix + "_image_scale").attr("checked", "checked");
    wds_scale("#" + prefix + "_image_scale", prefix);
    wds_drag_layer(id);
    jscolor.bind();  
}

function wds_hex_rgba(color, transparent) {
  color = "#" + color;
  var redHex = color.substring(1, 3);
  var greenHex = color.substring(3, 5);
  var blueHex = color.substring(5, 7);

  var redDec = parseInt(redHex, 16);
  var greenDec = parseInt(greenHex, 16);
  var blueDec = parseInt(blueHex, 16);

  var colorRgba = 'rgba(' + redDec + ', ' + greenDec + ', ' + blueDec + ', ' + transparent / 100 + ')';
  return colorRgba;
}

function wds_add_slide() {
  var slides_count = jQuery(".wbs_subtab div[id^='wbs_subtab']").length;
  var tmp_arr = [];
  var order_arr = [];
  var tmp_i = 0;
  jQuery(".wbs_subtab").find(".tab_link").each(function() {
    var tmp_id = jQuery(this).attr("id");
    if (tmp_id.indexOf("pr_") !== -1) {
      tmp_arr[tmp_i++] = tmp_id.replace("wbs_subtabpr_", "");
    }
    order_arr.push(jQuery('#order' + tmp_id.replace("wbs_subtab", "")).val()) ;
  });
  if (typeof tmp_arr !== 'undefined' && tmp_arr.length > 0) {
    var slideID = "pr_" + (Math.max.apply(Math, tmp_arr) + 1);
    ++slides_count;
  }
  else {
    var slideID = "pr_" + ++slides_count;
  }
  var order_id = 1;
  if (typeof order_arr !== 'undefined' && order_arr.length > 0) {
    order_id = Math.max.apply(Math, order_arr) + 1;
  }
  var new_slide_name = wds_object.translate.slide +' ' + order_id;
  var uploader_href_for_add_slide = uploader_href.replace('slideID', slideID);
  var uploader_href_for_add_layer = uploader_href_for_add_slide.replace('add_update_slide', 'add_layer'); 
  var upload_href_for_change_thumb = uploader_href_for_add_slide.replace('add_update_slide', 'add_update_thumbnail');
  if (spider_uploader_) {
    slide_upload_by = ' <a href="' + uploader_href_for_add_slide + '" class="action_buttons edit_slide thickbox thickbox-preview" title="'+ wds_object.translate.add_edit_image +'" onclick="return false;">'+ wds_object.translate.add_edit_image +'</a>';
    update_thumb_by = ' <a href="' + upload_href_for_change_thumb + '" class="button button-secondary thickbox thickbox-preview" title="'+ wds_object.translate.edit_thumbnail +'" onclick="return false;">'+ wds_object.translate.edit_thumbnail +'</a>';
    /* TODO remove
	edit_slide_by = ' <a href="' + uploader_href_for_add_slide + '" class="wds_change_thumbnail thickbox thickbox-preview" title="'+ wds_object.translate.add_edit_image +'" onclick="return false;"></a>';
	*/
    img_layer_upload_by = ' <a onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : '') + '; return false;" ' + (wds_object.is_free ? '' : 'href="' + uploader_href_for_add_layer + '"') + ' class="action_buttons add_image_layer button-small' + (wds_object.is_free ? " wds_free_button" : " thickbox thickbox-preview") + '" title="'+ wds_object.translate.add_image_layer +'">'+ wds_object.translate.add_image_layer +'</a>';
  }
  else {
    slide_upload_by = ' <input id="button_image_url' + slideID + '" class="action_buttons edit_slide" type="button" value="'+ wds_object.translate.add_edit_image +'" onclick="wds_media_uploader(\'' + slideID + '\', event); return false;" />';
    update_thumb_by = ' <input type="button" class="button button-secondary" id="button_image_url' + slideID + '" value="'+ wds_object.translate.edit_thumbnail +'" onclick="wds_media_uploader(\'add_update_thumbnail__' + slideID + '\', event); return false;" />';
    img_layer_upload_by = ' <input class="action_buttons add_image_layer button-small' + (wds_object.is_free ? " wds_free_button" : "") + '" type="button" value="'+ wds_object.translate.add_image_layer +'" onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : 'wds_add_layer(\'image\', \'' + slideID + '\', \'\')') + '; return false;" />';
  }
  edit_slide_by = ' <span class="wds_change_thumbnail" type="button" title="'+ wds_object.translate.edit_slide +'" value="'+ wds_object.translate.edit_slide +'" onclick="wds_media_uploader_add_slide(event, \'' + slideID + '\', false); return false;" ></span>';
	var fillmode_select ='';
	jQuery.each(wds_slider_fillmode_option, function(index, value) { 
		fillmode_select += 
			'<div class="spider_option_cont ' + (index == 'fill' ? 'selected' : '') + '" onclick="wds_change_fillmode_type(this, \'' + slideID + '\')">'+
			  '<div id="wds_fillmode_option_title-' + slideID + '"class="spider_option_cont_title" data-title="' + index + '">' + value + '</div>'+
			  '<div id="wds_fillmode_option_img-'+ slideID +'" class="spider_option_cont_img">'+
				'<img src="' + WD_S_URL + '/images/fillmode/' + index + '.png" />'+														
			 '</div>'+
			'</div>';
	});
	var fillmode_html = '<div class="wd-group">'+
						'<label class="wd-label" for="fillmode' + slideID + '">' + wds_object.translate.fillmode + '</label>'+
						'<div id="wds_fillmode_option-' + slideID + '" class="wds_fillmode_option">'+
							'<div style="width: 210px; position: relative;">'+
								'<div class="spider_choose_option" onclick="wds_choose_option(this)">'+
									'<div  class="spider_option_main_title">' + wds_object.translate.fill + '</div>'+
									'<div class="spider_sel_option_ic"><i class="fa fa-angle-down fa-lg"></i></div>'+
								'</div>'+
								'<div class="spider_options_cont">'+ fillmode_select +
								'</div>'+
							'</div>'+
						'</div>'+
						'<div id="wds_fillmode_preview-' + slideID + '" class="wds_fillmode_preview">'+
							'<img src="' + WD_S_URL + '/images/fillmode/fill.png">'+
							'<input type="hidden" name="fillmode' + slideID + '" value="fill">'+
						'</div>'+	
						'<div class="clear"></div>'+
						'<p class="description">' + wds_object.translate.fillmode_desc + '</p>'+
					'</div>';
  jQuery("#slide_ids_string").val(jQuery("#slide_ids_string").val() + slideID + ',');
  jQuery(".wds_slides_box *").removeClass("wds_sub_active");
  var bg_pos = {0 : 'center', 1 : 'center'};
  if ( jQuery("input[name='smart_crop']:checked").val() == 1 ) {
    bg_pos = jQuery("input[name='crop_image_position']:checked").val().split(" ");
  }
  jQuery(
    '<div id="wds_subtab_wrap' + slideID + '" class="wds_subtab_wrap connectedSortable"><div id="wbs_subtab' + slideID + '" class="tab_link wds_sub_active" style="display:block !important; width:149px; height:140px; padding:0; margin-right: 25px;">' +
      '<div  class="tab_image" id="wds_tab_image' + slideID + '">' + 
        '<div class="tab_buttons">' + 
          '<div class="handle_wrap"><div class="handle" title="Drag to re-order"></div></div>' +
          '<div class="wds_tab_title_wrap"><input type="text" id="title' + slideID + '" name="title' + slideID + '" value="'+ new_slide_name + '" class="wds_tab_title" tab_type="slide' + slideID + '" onchange="wds_set_slide_title(\'' + slideID + '\');"/></div><input  type="hidden" name="order' + slideID + '" id="order' + slideID + '" value="' + order_id + '" /></div>' +
          '<div class="wds_overlay"><div id="hover_buttons">' +
          edit_slide_by +
          ' <span class="wds_slide_dublicate" onclick="wds_duplicate_slide(\'' + slideID + '\');" title="'+ wds_object.translate.duplicate_slide +'"></span>' +
          ' <span class="wds_tab_remove" title="'+ wds_object.translate.delete_slide +'" onclick="wds_remove_slide(\'' + slideID + '\')"></span></div></div>' +
         ' </div></div></div>').insertBefore(".new_tab_image");
  jQuery(".wbs_subtab").after(
    '<div class="wds_box wds_sub_active wds_slide' + slideID + '">' +
      '<table class="ui-sortable' + slideID + '">' +
        '<tbody>' +
          '<input type="hidden" name="type' + slideID + '" id="type' + slideID + '" value="image" />' +
          '<input type="hidden" id="wds_video_type' + slideID + '" name="wds_video_type' + slideID + '" value="" />' +
		  '<tr><td>' +
          '<div class="postbox">' +
            '<button class="button-link handlediv" type="button" aria-expanded="true">' +
              '<span class="screen-reader-text">Toggle panel:</span>' +
              '<span class="toggle-indicator" aria-hidden="true"></span>' +
            '</button>' +
            '<h2 class="hndle">' +
              '<span>Slide options</span>' +
            '</h2>' +
            '<div class="inside">' +
              '<div class="wd-table">' +
                '<div class="wd-table-col wd-table-col-50 wd-table-col-left">' +
                  '<div class="wd-box-section">' +
                    '<div class="wd-box-content">'
                      +
                      fillmode_html
                      +
                      '<div class="wd-group">'+
                        '<label class="wd-label">'+ wds_object.translate.published +'</label>'+
                        '<input type="radio" id="published' + slideID + '1" name="published' + slideID + '" checked="checked" value="1" data-initial-value="1" class="wds-check-change" /><label class="selected_color" for="published' + slideID + '1">'+ wds_object.translate.yes +'</label>'+
                        '<input type="radio" id="published' + slideID + '0" name="published' + slideID + '" value="0" data-initial-value="0" class="wds-check-change"/><label for="published' + slideID + '0">'+ wds_object.translate.no +'</label>'+
                        '<p class="description"></p>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<div class="wd-table-col wd-table-col-50 wd-table-col-right">'+
                  '<div class="wd-box-section">'+
                    '<div class="wd-box-content">'+
                      '<div class="wd-group" id="controls' + slideID + '" style="display: none;">' +
						'<label class="wd-label">' + wds_object.translate.controls + '</label>' +
						'<input type="radio" onclick="wds_enable_disable(\'\', \'autoplay' + slideID + '\',\'controls' + slideID + '_1\')" id="controls' + slideID + '_1" name="controls' + slideID + '" checked="checked" value="1" /><label for="controls' + slideID + '_1">'+ wds_object.translate.yes +'</label>' +
						'<input type="radio" onclick="wds_enable_disable(\'none\', \'autoplay' + slideID + '\',\'controls' + slideID + '_0\')" id="controls' + slideID + '_0" name="controls' + slideID + '" value="0" /><label for="controls' + slideID + '_0">'+ wds_object.translate.no +'</label>' +
						'<p class="description"></p>' +
                      '</div>'+
                      '<div class="wd-group" id="autoplay' + slideID + '" style="display: none;">'+
						'<label class="wd-label">'+wds_object.translate.autoplay+'</label>'+
						'<input type="radio" id="autoplay' + slideID + '_1" name="wds_slide_autoplay' + slideID + '" checked="checked" value="1" /><label for="autoplay' + slideID + '_1">'+ wds_object.translate.yes +'</label>'+
						'<input type="radio" id="autoplay' + slideID + '_0" name="wds_slide_autoplay' + slideID + '" value="0" /><label for="autoplay' + slideID + '_0">'+ wds_object.translate.no +'</label>'+
						'<p class="description"></p>'+
                      '</div>'+
                      '<div class="wd-group" id="youtube_rel_video' + slideID + '" style="display: none;">'+
						'<label class="wd-label">'+wds_object.translate.youtube_related_video+'</label>'+
						'<input type="radio" id="youtube_rel_video' + slideID + '_1" name="youtube_rel_video' + slideID + '" checked="checked"  value="1" /><label for="youtube_rel_video' + slideID + '_1">'+ wds_object.translate.yes +'</label>'+
						'<input type="radio" id="youtube_rel_video' + slideID + '_0" name="youtube_rel_video' + slideID + '" value="0" /><label for="youtube_rel_video' + slideID + '_0">'+ wds_object.translate.no +'</label>'+
						'<p class="description"></p>'+
                      '</div>'+
                      '<div class="wd-group" id="video_loop' + slideID + '" style="display: none;">'+
                        '<label class="wd-label">'+ wds_object.translate.video_loop +'</label>'+
                        '<input type="radio" id="video_loop' + slideID + '_1" name="video_loop' + slideID + '" value="1" /><label for="video_loop' + slideID + '_1">'+ wds_object.translate.yes +'</label>'+
                        '<input type="radio" id="video_loop' + slideID + '_0" name="video_loop' + slideID + '" checked="checked" value="0" /><label for="video_loop' + slideID + '_0">'+ wds_object.translate.no +'</label>'+
                        '<p class="description"></p>'+
                      '</div>'+
    '<div class="wd-group" id="mute' + slideID + '" style="display: none;">'+
    '<label class="wd-label">'+ wds_object.translate.mute +'</label>'+
    '<input type="radio" id="mute' + slideID + '_1" name="mute' + slideID + '" value="1" /><label for="video_loop' + slideID + '_1">'+ wds_object.translate.yes +'</label>'+
    '<input type="radio" id="mute' + slideID + '_0" name="mute' + slideID + '" checked="checked" value="0" /><label for="video_loop' + slideID + '_0">'+ wds_object.translate.no +'</label>'+
    '<p class="description"></p>'+
    '</div>'+
                      '<div class="wd-group" id="trlink' + slideID + '">'+
						'<label class="wd-label" for="link' + slideID + '">'+ wds_object.translate.link_the_slide_to +'</label>'+
						'<input class="wds_external_link" id="link' + slideID + '" type="text" value="" name="link' + slideID + '" />'+
						'<input id="target_attr_slide' + slideID + '" type="checkbox" name="target_attr_slide' + slideID + '" checked="checked" value="1" /><label for="target_attr_slide' + slideID + '">'+ wds_object.translate.open_in_a_new_window +'</label>'+
						'<p class="description">'+ wds_object.translate.you_can_set_a_redirection_link_so_that_the_user_will_get_to_the_mentioned_location_upon_hitting_the_slide_use_http_and_https_for_external_links +'</p>'+
                     '</div>'+
					 '<div class="wd-group">'
						+ 
						update_thumb_by 
						+
						'<p class="description">Note, that thumbnail will be used in the filmstrip only.</p>' +
                      '</div>' +
                    '</div>'+
                  '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
              '</div>'+
            '</td>'+
          '</tr>'+
          '<tr class="bgcolor"><td colspan="4"><h2 class="titles wds_slide-title-'+ slideID +'">' + new_slide_name + '</h2>' +
            '<div class="wds-preview-overflow">' +
            '<div id="wds_preview_wrapper_' + slideID + '" class="wds_preview_wrapper" style="width: ' + jQuery("#width").val() + 'px; height: ' + jQuery("#height").val() + 'px;">' +
            '<div class="wds_preview">' +
            '<div id="wds_preview_image' + slideID + '" class="wds_preview_image' + slideID + '" ' +
                 'style="background-color: ' + wds_hex_rgba(jQuery("#background_color").val(), (100 - jQuery("#background_transparent").val())) + '; ' +
                        'background-image: url(\'\'); ' +
                        'background-position: ' + bg_pos[0] + ' ' + bg_pos[1] + '; ' +
                        'background-repeat: no-repeat; ' +
						'background-size: cover;' +
                        'border-width: ' + jQuery('#glb_border_width').val() + 'px; ' +
                        'width: inherit; height: inherit;"></div></div></div></div>' +
                        ' <input id="image_url' + slideID + '" type="hidden" value="" name="image_url' + slideID + '" />' +
                        ' <input id="thumb_url' + slideID + '" type="hidden" value="" name="thumb_url' + slideID + '" />' +
                        ' <input id="post_id' + slideID + '" type="hidden" value="" name="post_id' + slideID + '" />' +
                        ' <input id="video_duration' + slideID + '" type="hidden" value="" name="video_duration' + slideID + '" />' +
                        ' <input id="att_width' + slideID + '" type="hidden" value="" name="att_width' + slideID + '" />' +
                        ' <input id="att_height' + slideID + '" type="hidden" value="" name="att_height' + slideID + '" />' +
            '</td>'+
          '</tr>'+
          '<tr class="bgcolor"><td colspan="4">' +
            '<div class="layer_add_buttons_wrap"><input class="action_buttons add_text_layer button-small' + (!fv ? "" : " wds_free_button") + '" type="button" value="'+ wds_object.translate.add_text_layer +'" onclick="' + (!fv ? 'wds_add_layer(\'text\', \'' + slideID + '\')' : 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')') + '; return false;"></div><div class="layer_add_buttons_wrap">' +
            img_layer_upload_by +
            '</div><div class="layer_add_buttons_wrap"><input class="action_buttons button-small add_video_layer' + (wds_object.is_free ? " wds_free_button" : "") + '" type="button" onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : 'wds_add_layer(\'upvideo\', \'' + slideID + '\')') + '; return false;" value="'+ wds_object.translate.add_video_layer +'" />' +
            '</div><div class="layer_add_buttons_wrap"><input class="action_buttons add_embed_layer button-small' + (wds_object.is_free ? " wds_free_button" : "") + '" type="button" onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : 'wds_add_video(\'' +  slideID + '\', \'video_layer\')') + '; return false;" value="'+ wds_object.translate.embed_media_layer +'" />' +
            '</div><div class="layer_add_buttons_wrap"><input class="action_buttons add_social_layer button-small' + (wds_object.is_free ? " wds_free_button" : "") + '" type="button" onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : 'wds_add_layer(\'social\', \'' + slideID + '\')') + '; return false;" value="' + wds_object.translate.add_social_buttons_layer +'" />' +
	          '</div><div class="layer_add_buttons_wrap"><input class="action_buttons add_hotspot_layer button-small' + (wds_object.is_free ? " wds_free_button" : "") + '" type="button" onclick="' + (wds_object.is_free ? 'alert(\''+ wds_object.translate.disabled_in_free_version + '\')' : 'wds_add_layer(\'hotspots\', \'' + slideID + '\')') + '; return false;" value="' + wds_object.translate.add_hotspot_layer + '" /></td>' +
          '</tr></tbody></table>' +
          '<input id="slide' + slideID + '_layer_ids_string" name="slide' + slideID + '_layer_ids_string" type="hidden" value="" />' +
          '<input id="slide' + slideID + '_del_layer_ids_string" name="slide' + slideID + '_del_layer_ids_string" type="hidden" value="" />' +
          '<script>' +
            'jQuery(window).load(function() {' +
              'wds_drag_layer(\'' + slideID + '\');' +
            '});' +
            'spider_remove_url(\'image_url' + slideID + '\', \'wds_preview_image' + slideID + '\');' +
          '</script>' +
          '</div>');
  jQuery('#published' + slideID + '1').prop('checked', true);
  jQuery('#controls' + slideID + '_1').prop('checked', true);
  jQuery('#autoplay' + slideID + '_1').prop('checked', true);
  jQuery('#youtube_rel_video' + slideID + '_1').prop('checked', true);
  jQuery('#target_attr_slide' + slideID).prop('checked', true);
  jQuery('#wbs_subtab' + slideID).addClass("wds_sub_active");
  wds_slide_weights();
  wds_onkeypress();

  /* Open/close section container on its header click.*/
  jQuery(".wds_slide" + slideID + " .hndle, .wds_slide" + slideID + " .handlediv").each(function () {
    jQuery(this).on("click", function () {
      wds_toggle_postbox(this);
    });
  });

  jQuery(function(){
    jQuery(document).on("click","#wds_tab_image" + slideID ,function(){
        wds_change_sub_tab(this, 'wds_slide' + slideID);
    });
    jQuery(document).on("click","#wds_tab_image" + slideID + " input",function(e){
        e.stopPropagation();
    });
    jQuery(document).on("click","#title" + slideID,function(){
        wds_change_sub_tab(jQuery("#wds_tab_image" + slideID), 'wds_slide' + slideID);
        wds_change_sub_tab_title(this, 'wds_slide' + slideID);
    });
  });
  jQuery(".wds-preview-overflow").width(jQuery(".wd-slides-title").width());

  return slideID;
}

function wds_remove_slide(slideID, conf) {
  if (typeof conf == "undefined") {
    var conf = 1;
  }
  if (conf) {
    if (!confirm(wds_object.translate.do_you_want_to_delete_slide)) {
      return;
    }
  }
  jQuery("#sub_tab").val("");
  jQuery(".wds_slides_box *").removeClass("wds_sub_active");
  jQuery(".wds_slide" + slideID).remove();
  jQuery("#wbs_subtab" + slideID).remove();
  jQuery("#wds_subtab_wrap" + slideID).remove();
  var delslideIds;
  var slideIDs = jQuery("#slide_ids_string").val();
  slideIDs = slideIDs.replace(slideID + ",", "");
  jQuery("#slide_ids_string").val(slideIDs);
  if (slideID.indexOf('pr_') == -1) {
    delslideIds = jQuery("#del_slide_ids_string").val() + slideID + ",";
    jQuery("#del_slide_ids_string").val(delslideIds);
  }
  jQuery(".wbs_subtab div[id^='wbs_subtab']").each(function () {
    var id = jQuery(this).attr("id");
    firstSlideID = id.replace("wbs_subtab", "");
    jQuery("#wbs_subtab" + firstSlideID).addClass("wds_sub_active");
    jQuery(".wds_slide" + firstSlideID).addClass("wds_sub_active");
  });
}

function wds_trans_end(id, effect) {
  var transitionEvent = wds_whichTransitionEvent();
  if (jQuery("#"+id).parent().attr('id') == id+"_div") {
    var e = document.getElementById(id+'_div');
    transitionEvent && e.addEventListener(transitionEvent, function() {
      jQuery("#" + id + "_div").removeClass("wds_animated").removeClass(effect);
    });
  }
  else {
     var e = document.getElementById(id);
     transitionEvent && e.addEventListener(transitionEvent, function() {
      jQuery("#" + id).removeClass("wds_animated").removeClass(effect);
    });
  }
}

function wds_whichTransitionEvent() {
  var t;
  var el = document.createElement('fakeelement');
  var transitions = {
    'animation':'animationend',
    'OAnimation':'oAnimationEnd',
    'MozAnimation':'animationend',
    'WebkitAnimation':'webkitAnimationEnd'
  }
  for (t in transitions) {
    if (el.style[t] !== undefined) {
      return transitions[t];
    }
  }
}

function wds_new_line(prefix) {
  jQuery("#" + prefix).html(jQuery("#" + prefix + "_text").val().replace(/(\r\n|\n|\r)/gm, "<br />"));
}

function wds_trans_effect_in(slider_id, prefix, social) {
  if (typeof prefix == "undefined") {
   if (jQuery("#default_layer_effect_in").val() != 'none') {
     jQuery("#default_layer_infinite_in").removeAttr("disabled");
   }
    else {
     jQuery("#default_layer_infinite_in").attr('disabled', 'disabled')
   }
  }
  if (jQuery("#" + prefix + "_layer_effect_in").val() != 'none') {
    jQuery("#" + prefix + "_infinite_in").removeAttr("disabled");
  }
  else {
    jQuery("#" + prefix + "_infinite_in").attr('disabled', 'disabled')
  }
  var cont = jQuery("#" + prefix);
  var social_class = social ? ' fa fa-' + jQuery("#" + prefix + "_social_button").val() : "";
  if (jQuery("#" + prefix).prev().attr('id') == prefix + '_round_effect') {
    cont = jQuery("#" + prefix).parent();
  }
  cont.css(
    '-webkit-animation-duration', jQuery("#" + prefix + "_duration_eff_in").val() / 1000 + "s").css(
    'animation-duration' , jQuery("#" + prefix + "_duration_eff_in").val() / 1000 + "s");
  cont.removeClass().addClass(
    jQuery("#" + prefix + "_layer_effect_in").val() + " wds_animated wds_draggable_" + slider_id + social_class + " wds_draggable ui-draggable");
  var iteration_count = jQuery("#" + prefix + "_infinite_in").val() == 0 ? 'infinite' : jQuery("#" + prefix + "_infinite_in").val();
  cont.css(
    '-webkit-animation-iteration-count', iteration_count).css(
    'animation-iteration-count', iteration_count
  );
}

function wds_trans_effect_out(slider_id, prefix, social) {
  if (typeof prefix == "undefined") {
     if (jQuery("#default_layer_effect_out").val() != 'none') {
       jQuery("#default_layer_infinite_out").removeAttr("disabled");
     }
     else {
       jQuery("#default_layer_infinite_out").attr('disabled', 'disabled')
     }
   }
  if (jQuery("#" + prefix + "_layer_effect_out").val() != 'none') {
    jQuery("#" + prefix + "_infinite_out").removeAttr("disabled");
  }
  else {
    jQuery("#" + prefix + "_infinite_out").attr('disabled', 'disabled');
  }
  var cont = jQuery("#" + prefix);
  var social_class = social ? ' fa fa-' + jQuery("#" + prefix + "_social_button").val() : "";
  if (jQuery("#" + prefix).prev().attr('id') == prefix + '_round_effect') {
    cont = jQuery("#" + prefix).parent();
  }
  cont.css(
    '-webkit-animation-duration', jQuery("#" + prefix + "_duration_eff_out").val() / 1000 + "s").css(
    'animation-duration' , jQuery("#" + prefix + "_duration_eff_out").val() / 1000 + "s");
  cont.removeClass().addClass(
    jQuery("#" + prefix + "_layer_effect_out").val() + " wds_animated wds_draggable_" + slider_id + social_class + " wds_draggable ui-draggable");
  var iteration_count = jQuery("#" + prefix + "_infinite_out").val() == 0 ? 'infinite' : jQuery("#" + prefix + "_infinite_out").val();
  cont.css(
    '-webkit-animation-iteration-count', iteration_count).css(
    'animation-iteration-count', iteration_count
  );
}

function wds_break_word(that, prefix) {
  if (jQuery(that).is(':checked')) {
    jQuery("#" + prefix).css({wordBreak: "break-all"});
    if (jQuery("#" + prefix + "_image_width").val() > 0) {
      jQuery("#" + prefix).css('white-space', 'inherit');
    }
  }
  else {
    jQuery("#" + prefix).css({wordBreak: "normal"});
    jQuery("#" + prefix).css('white-space', 'nowrap');
  }
}

function wds_hotspot_width(prefix) {
  var width = parseInt(jQuery("#" + prefix + "_hotp_width").val());
  jQuery("#" + prefix + "_div").css({width: width + "px", height: width + "px"});
  jQuery("#" + prefix + "_round").css({width: width + "px", height: width + "px"});
  jQuery("#" + prefix + "_round_effect").css({width: width + "px", height: width + "px"});
  if ( !wds_object.is_free ) {
    wds_hotspot_position(prefix);
  }
}

function wds_hotspot_text_width(prefix) {
  var width = jQuery("#" + prefix + "_image_width").val();
  var height = jQuery("#" + prefix + "_image_height").val();
  jQuery("#" + prefix).width(width);
  jQuery("#" + prefix).height(height);
  if (jQuery("#" + prefix + "_image_scale").is(':checked')) {
    jQuery("#" + prefix).css('white-space', 'inherit');
  }
  if (!wds_object.is_free) {
    wds_hotspot_position(prefix);
  }
}

function wds_text_width(that, prefix) {
  var width = parseInt(jQuery(that).val());
  if (width) {
    if (width >= 100) {
      width = 100;
      jQuery("#" + prefix).css({left : 0});
      jQuery("#" + prefix + "_left").val(0);
    }
    else {
      var layer_left_position = parseInt(jQuery("#" + prefix).css("left"));	
      var layer_parent_div_width = parseInt(jQuery("#" + prefix).parent().width());
      var left_position_in_percent = (layer_left_position / layer_parent_div_width) * 100;
      if ((parseInt(left_position_in_percent) + width) > 100) {
        var left_in_pix = parseInt((100 - width) * (layer_parent_div_width / 100));
        jQuery("#" + prefix).css({left : left_in_pix + "px"});
        jQuery("#" + prefix + "_left").val(left_in_pix);
      }
    }
    jQuery("#" + prefix).css({width: width + "%"});
    jQuery(that).val(width);
  }
  else {
    jQuery("#" + prefix).css({width: ""});
    jQuery(that).val("0");
  }
}

function wds_text_height(that, prefix) {
  var height = parseInt(jQuery(that).val());
  if (height) {
    if (height >= 100) {
      jQuery("#" + prefix).css({top : 0});
      jQuery("#" + prefix + "_top").val(0);
    }
    else {
      var layer_top_position = parseInt(jQuery("#" + prefix).css("top"));	
      var layer_parent_div_height = parseInt(jQuery("#" + prefix).parent().height());
      var top_position_in_percent = (layer_top_position / layer_parent_div_height) * 100;
      if ((parseInt(top_position_in_percent) + height) > 100) {
        var top_in_pix = parseInt((100 - height) * (layer_parent_div_height / 100 ));
        jQuery("#" + prefix).css({top : top_in_pix});
        jQuery("#" + prefix + "_top").val(top_in_pix);
      }
    }
    jQuery("#" + prefix).css({height: height + "%"});
    jQuery(that).val(height);
  }
  else {
    jQuery("#" + prefix).css({height: ""});
    jQuery(that).val("0");
  }
}

function wds_whr(forfield) {
  var width = jQuery("#width").val();
  var height = jQuery("#height").val();
  var ratio = jQuery("#ratio").val();
  if (forfield == 'width') {
    if (width && height) {
      jQuery("#ratio").val(Math.round((width / height) * 100) / 100);
    }
    else if (width && ratio) {
      jQuery("#height").val(Math.round((width / ratio) * 100) / 100);
    }
  }
  else if (forfield == 'height') {
    if (width && height) {
      jQuery("#ratio").val(Math.round((width / height) * 100) / 100);
    }
  }
  else {
    if (width && ratio) {
      jQuery("#height").val(Math.round((width / ratio) * 100) / 100);
    }
  }
  jQuery('.wds_preview_wrapper').width(width);
  jQuery('.wds_preview_wrapper').height(height);
}

function wds_onkeypress() {
  jQuery("input[type='text']").on("keypress", function (event) {
    if ((jQuery(this).attr("id") != "search_value") && (jQuery(this).attr("id") != "current_page")) {
      var chCode1 = event.which || event.paramlist_keyCode;
      if (chCode1 == 13) {
        if (event.preventDefault) {
          event.preventDefault();
        }
        else {
          event.returnValue = false;
        }
      }
    }
    return true;
  });
}

jQuery(document).ready(function () {
  wds_onkeypress();
});

function wds_get_checked() {
  var ids_string = "";
  if (jQuery('#check_all_items').is(':checked')) {
    ids_string = 'all';
  }
  else {
    jQuery("#wds_sliders_form input[type='checkbox']").each(function () {
      if (jQuery(this).is(':checked')) {
        var id = jQuery(this).attr("id");
        if (id != 'check_all' && id != 'check_all_items' && id != 'imagesexport') {
          id = id.replace("check_", "");
          ids_string += id + ", ";
        }
      }
    });
  }
  if (jQuery('#imagesexport').is(':checked')) {
    var imagesexport_checked = true;
  }
  else {
    var imagesexport_checked = false;
  }
  var href = jQuery(".wds_export").attr("href");
  if (href.indexOf("&imagesexport") !== -1) {
    href = href.substr(0, href.indexOf("&imagesexport")); 
  }
    jQuery(".wds_export").attr("href", href + "&imagesexport=" + imagesexport_checked + "&slider_ids=" + ids_string);
    jQuery('.wds_opacity_export').hide();
    jQuery('.wds_exports').hide();
}

function wds_getfileextension(filename) {
  if (filename.length == 0) {
    alert('Choose file.');
    return false;
  }
  var dot = filename.lastIndexOf(".");
  var extension = filename.substr(dot + 1, filename.length);
  var exten = 'zip';
  /* TODO remove
  exten=exten.replace(/\./g,'');
  exten=exten.replace(/ /g,'');
  */
  if (extension.toLowerCase() == exten.toLowerCase()) {
    return true;
  }
  else {
    alert(wds_object.translate.sorry_you_are_not_allowed_to_upload_this_type_of_file);
  }
  return false;
}

function wds_import() {
  jQuery('.wds_opacity_import').show();
  jQuery('.wds_imports').show();
  return false;
}
function wds_merge() {
  var flag = true;
  jQuery('#bulk-action-selector-top').prop('selectedIndex',0);
  jQuery('#select_slider_merge').prop('selectedIndex',0);
  jQuery('input[id^="check_"]').each(function() {
    var id = jQuery(this).attr("id").replace("check_", "");
    if (jQuery(this).is(':checked')) {
      flag = false;
      jQuery('#select_slider_merge option[value="' + id + '"]').show();
    }
    else {
      jQuery('#select_slider_merge option[value="' + id + '"]').hide();
    }
  });
  if (flag) {
    alert(wds_object.translate.you_must_select_at_least_one_item);
    return false;
  }
  jQuery('.wds_opacity_merge').show();
  jQuery('.wds_merge').show();
  return false;
}

function wds_export() {
  if ( wds_object.is_free ) {
    alert(wds_object.translate.disabled_in_free_version);
    return false;
  }
  var flag = false;
  if (jQuery('#check_all_items').is(':checked') || jQuery('#check_all').is(':checked') || jQuery('input[id^=check_]').is(':checked')) {
    flag = true;
  }
  if (!flag) {
    alert(wds_object.translate.you_must_select_at_least_one_item);
  } 
  else { 
    jQuery('.wds_opacity_export').show();
    jQuery('.wds_exports').show();
  }
  return false;
}

function wds_hotpborder_width(prefix) {
  var border_width = jQuery("#" + prefix + "_round_hotp_border_width").val();
  var border_style = jQuery("#" + prefix + "_round_hotp_border_style").val();
  var border_color = jQuery("#" + prefix + "_hotp_border_color").val();
  jQuery("#" + prefix + "_round").css({
    borderWidth: border_width,
    borderStyle: border_style,
    borderColor: "#" + border_color
  });
  jQuery("#" + prefix + "_round_effect").css({
    borderWidth: border_width,
    borderStyle: border_style,
    borderColor: "transparent"
  });
  if ( !wds_object.is_free ) {
    wds_hotspot_position(prefix);
  }
}

function change_zindex(that, prefix) {
  if (jQuery("#" + prefix).prev().attr("id") == prefix + "_round_effect") {
    jQuery("#"+prefix).parent().css({zIndex: jQuery(that).val()});
  }
  else {
    jQuery("#"+prefix).css({zIndex: jQuery(that).val()});
  }
}

function wde_change_text_bg_color(prefix) {
  var bgColor = wds_hex_rgba(jQuery("#" + prefix + "_fbgcolor").val(), 100 - jQuery("#" + prefix + "_transparent").val());
  jQuery("#" + prefix).css({backgroundColor: bgColor});
  if ( !wds_object.is_free ) {
    wds_hotspot_position(prefix);
  }
}

function wds_show_wp_editor(id) {
  jQuery(".wds_editor").show();
  jQuery(".opacity_wp_editor").show();
  jQuery("#current_prefix").val(id);
  var content = jQuery("#" + id + "_text").val();
  if ((typeof tinyMCE != "undefined")) {
    tinyMCE.get("template_text").setContent(content);
  }
  jQuery("#template_text").val(content)
  return false;
}

function wds_insert_html() {
  jQuery(".wds_editor").hide();
  jQuery(".opacity_wp_editor").hide();
  var content = "";
  var editor = tinyMCE.get("template_text");
  if (editor) {
    /* Active tab is Visual.*/
    content = editor.getContent();
  }
  else {
    /* Active tab is HTML.*/
    content = jQuery("#template_text").val();
  }
  var prefix = jQuery("#current_prefix").val();
  jQuery("#current_prefix").val("");
  jQuery("#" + prefix).html(content);
  jQuery("#" + prefix + "_text").val(content);
}

function wds_change_fonts(prefix, change) {
  var fonts;
  if (typeof prefix == "undefined" || prefix == "") {
    var prefix = "default_layer";
  }
  if (jQuery("#" + prefix + "_google_fonts1").is(":checked")) {
    fonts = wds_object.GGF;
    jQuery("#possib_add_ffamily_input").closest("div").addClass("wds_hide");
  }
  else {
    fonts = wds_object.FGF;
    jQuery("#possib_add_ffamily_input").closest("div").removeClass("wds_hide");
  }
  if (typeof change == "undefined") {
    var fonts_option = "";
    for (var i in fonts) {
     var selected = (wds_object.LDO.default_layer_ffamily == i) ? "selected='selected'" : "";
      fonts_option += '<option value="' + i + '" ' + selected + '>' + fonts[i] + '</option>';
    }
    jQuery("#" + prefix + "_ffamily").html(fonts_option);
  }
  var font = jQuery("#" + prefix + "_ffamily").val();
  jQuery("#" + prefix).css({fontFamily: fonts[font]});
}

function set_ffamily_value() {
  var font = jQuery("#possib_add_ffamily_input").val();
  if (font != '' ) {
    if (jQuery("#possib_add_google_fonts").is(":checked")) {
      var ffamily_google = jQuery('#possib_add_ffamily_google').val();
      if (ffamily_google != '') {
        ffamily_google += "*WD*" + font;
      }
      else {
        ffamily_google = font;
      }
      jQuery('#possib_add_ffamily_google').val(ffamily_google);
    }
    else {
      var ffamily = jQuery('#possib_add_ffamily').val();
      if (ffamily != '') {
        ffamily += "*WD*" + font;
      }
      else {
        ffamily = font;
      }
      jQuery('#possib_add_ffamily').val(ffamily);
    }
  }
}

function wds_check_number() {
  var number = jQuery('#wds_thumb_size').val();
  if (number != '' && (number < 0 || number > 1)) {
      alert('The thumbnail size must be between 0 to 1.');
      jQuery('#wds_thumb_size').val("");
  }
}

function add_new_callback(par_tr, select) {
	var select_val = select.val(),
      selected = select.find("option[value=" + select_val + "]"),
      textarea_html = "";
  par_tr.next().append("<div class='callbeck-item'><span class='wd-label'>" + selected.text() + "</span><textarea class='callbeck-textarea' name='" + select_val + "'>" + textarea_html + "</textarea><button type='button' id='remove_callback' class='action_buttons remove_callback' onclick=\"remove_callback_item(this);\">Remove</button></div>");
	selected.hide().removeAttr("selected");

  select.find("option").each(function() {
    if (jQuery(this).css("display") == "block") {
      jQuery(this).attr("selected", "selected");
      return false;
    }
  });
}

function remove_callback_item(that) {
	jQuery(that).parent().remove();
	jQuery("#callback_list").find("option[value=" + jQuery(that).prev().attr("name") + "]").show();
}

function wd_bulk_action(that) {
  var action = jQuery(that).val();
  if (action == 'export') {
    wds_export();
  }
  else if (action == 'merge') {
   wds_merge();
   return false;
  }
  else if (action != '') {
    if (action == 'delete_all') {
      if (!confirm(wds_object.translate.do_you_want_to_delete_selected_items)) {
        return false;
      }
    }
    spider_set_input_value('task', action);
    jQuery('#wds_sliders_form').submit();
  }
  else {
    return false;
  }
  return true;
}
function wds_loading_preview() {
	if ( !jQuery(".wds_fieldset_img").hasClass('opacity') ) {
		jQuery(".wds_fieldset_img").addClass('opacity');
	} else {
		jQuery(".wds_fieldset_img").removeClass('opacity');
	}
}

function wds_loading_gif(image_name, plagin_url) {
 jQuery(".wds_fieldset_img_preview").hide();
 jQuery("#load_gif_img").attr('src', plagin_url + '/images/loading/' + image_name + '.gif');
 jQuery(".wds_fieldset_img").css('opacity', '1');
}

function wds_show_slides_name(prefix, selected) {
  var id = prefix + '_link_to_slide';
  if (selected == 'SlideLink') {
    jQuery('#' + id).show();
    jQuery('.link_to_slide').show();
  }
  else {
    jQuery('#' + id).hide();
    jQuery('.link_to_slide').hide();
  }
}

function wds_position_left_disabled(that) {
  if (jQuery("#" + that + "_align_layer").is(":checked")) {
    jQuery("#" + that + "_left").attr('disabled', 'disabled');
  }
  else {
    jQuery("#" + that + "_left").removeAttr("disabled");
  }
}

function wds_reset(event) {
  if (confirm(wds_object.translate.are_you_sure_you_want_to_reset_the_settings)) {
    if (!wds_check_required()) {
      return false;
    }
    spider_set_input_value('task', 'reset');
    wds_spider_ajax_save('sliders_form', event);
    return true;
  }
  return false;
}

function wds_set_one() {
  jQuery('.wds_opacity_set').show();
  jQuery('.wds_set').show();
  return false;
}

function wds_invent_default_layer_check() {
  jQuery(".choose_layer_tr").remove();
  jQuery(".wds_default_label").each(function() {
    var choose_layer_html = jQuery('.wds_template_class').clone().removeClass('wds_template_class').addClass("choose_layer_tr").insertBefore(".wds_template_class");
    choose_layer_html.find('label').attr('for', jQuery(this).attr('for') + '_check').html(jQuery(this).text());   
    choose_layer_html.find('input').attr('name', jQuery(this).attr('for') + '_check').attr('id', jQuery(this).attr('for') + '_check');
  });
}

function wds_checked_options(event) {
  if (jQuery("#choose_slider").val() != '0') {
    if (jQuery(".wds_check").is(':checked') == true) {
      spider_form_submit(event, 'sliders_form');
    }
    else {
	  alert(wds_object.translate.check_at_least);
    }
  }
  else {
    alert(wds_object.translate.no_slider);
  }
}

function wds_min_size_validation(that) {
  var cont = jQuery("#" + that + "_min_size");
  var min_size = parseInt(cont.val());
  var size = parseInt(jQuery("#" + that + "_size").val());
  if (min_size > size) {
    cont.val(0);
    cont.css({'borderColor': 'rgb(255, 0, 0)'});
  }
  else {
    cont.removeAttr('style');
  }
}

function wds_toggle_postbox(that) {
  jQuery(that).parent(".postbox").toggleClass("closed");
}

jQuery(window).resize(function () {
  /* Set preview container overflow width.*/
  jQuery(".wds-preview-overflow").width(jQuery(".wd-slides-title").width());
});
/* Set slide title on change.*/
function wds_set_slide_title( id ) {
	var val = jQuery('.wds_tab_title_wrap #title'+ id).val();
	jQuery( '.wds_slide'+ id +' .wds_slide-title-'+ id ).html(val);
}

/* Hide dimensions and ratio based on full width in settings. */
function hide_dimmension_ratio() {
  jQuery(".full_width_desc").hide();
  jQuery("#" + jQuery('.wds_settings_box [name=full_width]:checked').attr("id") + "_desc").show();
  if (jQuery('.wds_settings_box [name=full_width]:checked').val() == '0' || jQuery('.wds_settings_box [name=full_width]:checked').val() == '2') {
    jQuery("#auto_height").hide();
    jQuery(".wds_nav_global_box #dimensions").show();
  }
  else {
    jQuery("#auto_height").show();
    if (jQuery('.wds_settings_box [name=auto_height]:checked').val() == 0) {
      jQuery(".wds_nav_global_box #dimensions").show();
      jQuery("#ratio_container").show();
    }
    else {
      jQuery(".wds_nav_global_box #dimensions").hide();
      jQuery("#ratio_container").hide();
    }
  }
}

/* Show How To block on tab.*/
function showHowToTabBlock(){
    jQuery(".tab_conteiner .howto_tab_button_wrap.hide").removeClass('hide');
    var id = jQuery("#current_id").val();
    var shortcode = '[wds id="'+ id +'"]';
    var phpcode   = '<?php wd_slider('+ id +'); ?>';
    jQuery(".wds_howto_content .wds_howto_shortcode").val(shortcode);
    jQuery(".wds_howto_content .wds_howto_phpcode").val(phpcode);
}

/**
 * Search on input enter.
 *
 * @param e
 * @param that
 * @returns {boolean}
 */
function input_search(e, that) {
  var key_code = (e.keyCode ? e.keyCode : e.which);
  if (key_code == 13) { /*Enter keycode*/
    search(that);
    return false;
  }
}

/**
 * Search.
 *
 * @param that
 */
/**
 * Search.
 *
 * @param that
 */
function search(that) {
  var form = jQuery(that).parents("form");
  form.attr("action", window.location + "&paged=1&s=" + jQuery("input[name='s']").val());
  form.submit();
}
