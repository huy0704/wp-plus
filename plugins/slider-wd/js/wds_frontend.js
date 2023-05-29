var wds;
var wds_carousel = [];
var wds_currentlyMoving = [];
var wds_currentCenterNum = [];
var textLayerPosition = [];
var wds_zoomfade_first_img = 0;

jQuery(window).resize(function () {
  wds_resize();
});

jQuery(document).ready(function () {
  jQuery(".wds_slider_cont").each(function () {
    wds = jQuery(this).attr("data-wds");
    if ( wds_params[wds].carousel == 1 ) {
      wds_currentlyMoving[wds] = false;
      wds_currentCenterNum[wds] = wds_params[wds].start_slide_num_car;
      wds_params[wds].wds_currentCenterNum = wds_currentCenterNum[wds];
      jQuery(".wds_left-ico_" + wds).click(function () {
        wds_carousel[jQuery(this).closest('div[class^="wds_slider_cont"]').attr("data-wds")].prev();
      });
      jQuery(".wds_right-ico_" + wds).click(function () {
        wds_carousel[jQuery(this).closest('div[class^="wds_slider_cont"]').attr("data-wds")].next();
      });
    }
    /* Start first image with zoomFade */
    if ( wds_params[wds].slider_effect === "zoomFade" ) {
      start_slide_num = wds_params[wds].start_slide_num;
      wds_change_image(wds, start_slide_num, start_slide_num, wds_params[wds].wds_data);
    }
  });
  wds_slider_ready();
});

jQuery(window).on('load', function () {
  jQuery(".wds_slider_cont").each(function () {
    wds = jQuery(this).attr("data-wds");
    if (!wds_object.is_free && wds_params[wds].carousel == 1) {
      wds_carousel_params(wds);
      wds_display_hotspot();
      wds_hotspot_position();
    }
  })
});

function wds_resize() {
  jQuery(".wds_slider_cont").each(function () {
    wds = jQuery(this).attr("data-wds");
    wds_resize_slider(wds);
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderR' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderR );
        wds_callback_f();
      }
    });
  });
  if ( !wds_object.is_free ) {
    jQuery(".wds_slider_cont").each(function () {
      wds = jQuery(this).attr("data-wds");
      if (wds_params[wds].carousel == 1) {
        wds_carousel_params(wds);
        wds_carousel[wds].pause();
        if (!jQuery(".wds_ctrl_btn_" + wds).hasClass("fa-play")) {
          wds_carousel[wds].start();
        }
      }
    });
  }
}

/* ready slider. */
function wds_slider_ready() {
	jQuery(".wds_slider_cont").each(function () {
    var wds = jQuery(this).attr("data-wds");
    if (wds_params[wds].current_image_url != '') {
      jQuery('<img />').attr("src", wds_params[wds].current_image_url).on('load', function () {
        jQuery(this).remove();
        wds_ready_func(wds);
      });
      wds_data[wds_params[wds].wds_current_key]["loaded"] = true;
    }
    else {
      wds_ready_func(wds);
    }
    if (wds_params[wds].fixed_bg == 1) {
      jQuery(window).scroll(function () {
        wds_window_fixed_pos(wds);
      });
    }
    wds_params[wds].wds_play_pause_state = 0;
    if (wds_params[wds].carousel == 1) {
      wds_carousel_params(wds);
    }
    document.addEventListener("visibilitychange", function() {
      if(document.visibilityState != 'visible') {
        window.clearInterval(wds_params[wds].wds_playInterval);
        wds_event_stack_wds = [];
        if (typeof jQuery().stop !== 'undefined') {
          if (jQuery.isFunction(jQuery().stop)) {
            if (wds_params[wds].timer_bar_type == 'top' || wds_params[wds].timer_bar_type == 'bottom') {
              jQuery(".wds_line_timer_" + wds).stop();
              if (wds_params[wds].carousel) {
                wds_carousel[wds].pause();
              }
            }
            else if (wds_params[wds].timer_bar_type != 'none') {
              wds_params[wds].circle_timer_animate.stop();
              if (wds_params[wds].carousel) {
                wds_carousel[wds].pause();
              }
            }
          }
        }
      } else {
        wds_restart_slideshow_autoplay( wds );
      }
    });
  });
}

/* restart slideshow. */
function wds_restart_slideshow_autoplay( wds ) {
  if (!jQuery(".wds_ctrl_btn_" + wds).hasClass("fa-play")) {
    if (wds_params[wds].enable_slideshow_autoplay) {
      play_wds(wds);
      if (wds_params[wds].carousel == 1) {
        wds_carousel[wds].start();
      }
      if (wds_params[wds].timer_bar_type != 'none') {
        if (wds_params[wds].timer_bar_type != 'top') {
          if (wds_params[wds].timer_bar_type != 'bottom') {
            if (typeof wds_params[wds].circle_timer_animate !== 'undefined') {
              wds_params[wds].circle_timer_animate.stop();
            }
            wds_circle_timer(wds, wds_params[wds].curent_time_deggree);
          }
        }
      }
    }
  }
  if (wds_params[wds].carousel != 1) {
    var i_wds = 0;
    jQuery(".wds_slider_" + wds).children("span").each(function () {
      if (jQuery(this).css('opacity') == 1) {
        jQuery("#wds_current_image_key_" + wds).val(i_wds);
      }
      i_wds++;
    });
  }
}

function wds_carousel_params(wds) {
  var width, height;
  var slide_orig_width = wds_params[wds].image_width;
  var slide_orig_height = wds_params[wds].image_height;
  var slide_width = wds_get_overall_parent(jQuery("#wds_container1_" + wds));
  var par = 1, par1 = 1;
  var ratio = slide_width / slide_orig_width;
  if (jQuery(window).width() <= parseInt(wds_params[wds].full_width_for_mobile) || (wds_params[wds].full_width == '1')) {
    var full_width = '1';
  }
  else {
    var full_width = '0';
  }
  if (full_width == '1') {
    ratio = jQuery(window).width() / slide_orig_width;
    slide_orig_width = jQuery(window).width() - (2 * wds_params[wds].wds_glb_margin);
    slide_orig_height = wds_params[wds].image_height * slide_orig_width / wds_params[wds].image_width;
    slide_width = jQuery(window).width() - (2 * wds_params[wds].wds_glb_margin);
    wds_full_width(wds);
  }
  else if (parseInt(wds_params[wds].full_width_for_mobile)) {
    jQuery(".wds_slideshow_image_wrap_" + wds).removeAttr("style");
  }
  var slide_height = slide_orig_height;
  if (slide_orig_width > slide_width) {
    slide_height = Math.floor(slide_width * slide_orig_height / slide_orig_width);
  }
  width = slide_width;
  height = slide_height;
  var larg_width, img_height, parF = 1;
  if (width < wds_params[wds].carousel_width) {
    par = width / wds_params[wds].carousel_width;
  }
  par1 = wds_params[wds].image_height * par / height;
  if (width < wds_params[wds].carousel_width) {
    jQuery(".wds_slideshow_image_wrap_" + wds + ", #wds_container2_" + wds).height(height * par1 + ((wds_params[wds].filmstrip_direction == 'horizontal') ? wds_params[wds].filmstrip_height : 0));
    jQuery(".wds_slideshow_image_container_" + wds).height(height * par1);
    jQuery(".wds_btn_cont wds_contTableCell" + wds).height(height * par1);
    jQuery(".wds_slide_container_" + wds).height(height * par1);
  }
  if (full_width == '1') {
    var parF = parseFloat(wds_params[wds].carousel_image_parameters);
    parF = isNaN(parF) ? 1 : parF;
    parF *= wds_params[wds].image_width;
    jQuery(".wds_slideshow_image_wrap_" + wds + ", #wds_container2_" + wds).height(height * par1 + ((wds_params[wds].filmstrip_direction == 'horizontal') ? wds_params[wds].filmstrip_height : 0));
    jQuery(".wds_slideshow_image_container_" + wds).height(height * par1);
    jQuery(".wds_btn_cont wds_contTableCell" + wds).height(height * par1);
    jQuery(".wds_slide_container_" + wds).height(height * par1);
  }
  if (wds_params[wds].carousel_image_counts > wds_params[wds].slides_count) {
    wds_params[wds].carousel_image_counts = wds_params[wds].slides_count;
  }
  if (wds_params[wds].carousel_image_parameters > 1) {
    wds_params[wds].carousel_image_parameters = 1;
  }
  var interval = 0;
  if (wds_params[wds].enable_slideshow_autoplay) {
    interval = wds_params[wds].slideshow_interval;
  }
  var slideshow_filmstrip_container_width = wds_params[wds].filmstrip_direction == 'horizontal' ? 0 : jQuery(".wds_slideshow_filmstrip_container_" + wds).width();
  jQuery(".wds_slideshow_dots_container_" + wds).css({
    width: (wds_params[wds].image_width * par),
    left: (width - wds_params[wds].image_width * par - slideshow_filmstrip_container_width) / 2
  });
  var orig_width = wds_params[wds].image_width;
  var img_width = Math.min(larg_width, orig_width);
  wds_carousel[wds] = jQuery(".wds_slide_container_" + wds).featureCarouselslider({
    containerWidth: width,
    containerHeight: height,
    largeFeatureWidth: wds_params[wds].image_width * par,
    largeFeatureHeight: wds_params[wds].image_height * par,
    fit_containerWidth: wds_params[wds].carousel_fit_containerWidth,
    smallFeaturePar: wds_params[wds].carousel_image_parameters,
    featuresArray: [],
    timeoutVar: null,
    rotationsRemaining: 0,
    parametr: par,
    parf: parF,
    data: wds_params[wds].wds_data,
    autoPlay: wds_params[wds].interval * 1000,
    interval: wds_params[wds].slideshow_interval * 1000,
    imagecount: wds_params[wds].carousel_image_counts,
    wds_number: wds_params[wds].wds,
    startingFeature: wds_currentCenterNum[wds],
    carouselSpeed: wds_params[wds].wds_transition_duration,
    carousel_degree: wds_params[wds].carousel_degree,
    carousel_grayscale: wds_params[wds].carousel_grayscale,
    carousel_transparency: wds_params[wds].carousel_transparency,
    borderWidth: 0
  });
}

function wds_show_thumb(key, wds) {
  var data = wds_params[wds].wds_data[key];
  var full_width = wds_params[wds].wds_data[key]["full_width"];
  var bull_position = wds_params[wds].wds_data[key]["bull_position"];
  var image_url = data["image_url"];
  var dot_conteiner_width = jQuery('.wds_slideshow_dots_container_' + wds).width() / 2;
  var dot_conteiner_height = jQuery('.wds_slideshow_dots_container_' + wds).height();
  var wds_bulframe_width = jQuery('.wds_bulframe_' + wds).width() / 2;
  var dot_position = jQuery('#wds_dots_' + key + '_' + wds).position();
  var dot_width = jQuery('#wds_dots_' + key + '_' + wds).width() / 2;
  dot_position = dot_position.left;
  var childPos = jQuery('#wds_dots_' + key + '_' + wds).offset();
  var parentPos = jQuery('.wds_slideshow_dots_thumbnails_' + wds).parent().offset();
  var childOffset = childPos.left - parentPos.left;
  var right_offset = 0;
  var rt = (dot_conteiner_width * 2) - childOffset;
  if (wds_bulframe_width >= rt && rt > 0) {
    right_offset = wds_bulframe_width - rt;
    dot_width = 0;
  }
  if (full_width == '1') {
    if (wds_bulframe_width >= childOffset) {
      wds_bulframe_width = childOffset - parentPos.left;
      dot_width = 0;
    }
  }
  else {
    if (wds_bulframe_width >= childOffset) {
      wds_bulframe_width = childOffset;
      dot_width = 0;
    }
  }
  dot_position = childOffset - wds_bulframe_width + dot_width - right_offset;
  jQuery('.wds_bulframe_' + wds).css({
    'position': 'absolute',
    'z-index': '9999',
    'left': dot_position,
    'background-image': 'url("' + image_url + '")',
    'background-size': 'contain',
    'background-repeat': 'no-repeat',
    'background-position': 'center center'
  });
  jQuery('.wds_bulframe_' + wds).css(bull_position, dot_conteiner_height);
  jQuery('.wds_bulframe_' + wds).fadeIn();
}

function wds_hide_thumb(wds) {
  jQuery('.wds_bulframe_' + wds).css({'background-image':''});
  jQuery('.wds_bulframe_' + wds).fadeOut();
}

function wds_get_overall_parent(obj) {
  if (obj.parent().width()) {
    obj.width(obj.parent().width());
    return obj.parent().width();
  }
  else {
    return wds_get_overall_parent(obj.parent());
  }
}

function wds_set_text_dots_cont(wds) {
  var wds_bull_width = 0;
  jQuery(".wds_slideshow_dots_" + wds).each(function(){
    wds_bull_width += jQuery(this).outerWidth() + 2 * parseInt(jQuery(this).css("margin-left"));
  });
  jQuery(".wds_slideshow_dots_thumbnails_" + wds).css({width: wds_bull_width});
}

/* Generate background position for Zoom Fade effect.*/
function wds_genBgPos(current_key, wds, slideshow_interval) {
  var bgSizeArray = [0, 70];
  var bgSize = bgSizeArray[Math.floor(Math.random() * bgSizeArray.length)];
  var bgPosXArray = ['left', 'right'];
  var bgPosYArray = ['top', 'bottom'];
  var bgPosX = bgPosXArray[Math.floor(Math.random() * bgPosXArray.length)];
  var bgPosY = bgPosYArray[Math.floor(Math.random() * bgPosYArray.length)];
  var container = jQuery(current_key + " .wds_slideshow_image_" + wds);
  var container_width = container.width();
  var container_height = container.height();
  var ver_hor_type = '';
  var backgroundSize = (100 + bgSize) + "% " + "auto";
  if( container_width <= container_height ) {
    ver_hor_type = 'v';
    backgroundSize = "auto " + (100 + bgSize) + "%";
  }
  container.css({
    backgroundPosition: bgPosX + " " + bgPosY,
    backgroundSize: backgroundSize,
    webkitAnimation: ' wdszoom' + bgSize + ' ' + Math.floor(1.1 * slideshow_interval) + 's linear 0s alternate infinite',
    mozAnimation: ' wdszoom' + ver_hor_type + bgSize + ' ' + Math.floor(1.1 * slideshow_interval) + 's linear 0s alternate infinite',
    animation: ' wdszoom' + ver_hor_type + bgSize + ' ' + Math.floor(1.1 * slideshow_interval) + 's linear 0s alternate infinite'
  });
}

/* For browsers that does not support transitions.*/
function wds_fallback(wds, current_image_class, next_image_class, direction, wds, wds_transition_duration) {
  wds_fade(current_image_class, next_image_class, direction);
}

function wds_fade(wds, current_image_class, next_image_class, direction) {
  var container = {};
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
  if (wds_testBrowser_cssTransitions()) {
    jQuery(next_image_class).css('transition', 'opacity ' + wds_params[wds].wds_transition_duration + 'ms linear');
    jQuery(current_image_class).css({'opacity': 0, 'z-index': 1});
    jQuery(next_image_class).css({'opacity': 1, 'z-index': 2});
  }
  else {
    jQuery(current_image_class).animate({'opacity': 0, 'z-index': 1}, wds_params[wds].wds_transition_duration);
    jQuery(next_image_class).animate({
      'opacity': 1,
      'z-index': 2
    }, {
      duration: wds_params[wds].wds_transition_duration,
      complete: function () {
      }
    });
    /* For IE.*/
    jQuery(current_image_class).fadeTo(wds_params[wds].wds_transition_duration, 0);
    jQuery(next_image_class).fadeTo(wds_params[wds].wds_transition_duration, 1);
  }

  jQuery.each( wds_params[wds].callback_items, function( index, value ) {
    if ( index === 'onSliderCE' && value !== '' ) {
      var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCE );
      wds_callback_f();
    }
  });
}

/* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
function wds_fallback3d(wds, urrent_image_class, next_image_class, direction) {
  wds_sliceV(wds, current_image_class, next_image_class, direction);
}

function wds_sliceV(wds, current_image_class, next_image_class, direction) {
  if (direction == 'right') {
    var translateY = 'min-auto';
  }
  else if (direction == 'left') {
    var translateY = 'auto';
  }
  wds_grid(wds, 10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_grid(wds, cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction, random, roy, easing) {
  /* If browser does not support CSS transitions.*/
  if (!wds_testBrowser_cssTransitions()) {
    return wds_fallback(current_image_class, next_image_class, direction);
  }
  wds_params[wds].wds_trans_in_progress = true;
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
  /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
  var count = (wds_params[wds].wds_transition_duration) / (cols + rows);

  /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
  function wds_gridlet(wds, width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
    var delay = random ? Math.floor((cols + rows) * count * Math.random()) : (c + r) * count;
    /* Return a gridlet elem with styles for specific transition.*/
    var grid_div = jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: imgWidth, /*"100%"*/
      height: jQuery(".wds_slideshow_image_spun_" + wds).height() + "px",
      top: -top,
      left: -left,
      backgroundImage: src,
      backgroundSize: jQuery(".wds_slideshow_image_" + wds).css("background-size"),
      backgroundPosition: jQuery(".wds_slideshow_image_" + wds).css("background-position"),
      backgroundRepeat: 'no-repeat'
    });
    return jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: width, /*"100%"*/
      height: height,
      top: top,
      left: left,
      backgroundSize: imgWidth + 'px ' + imgHeight + 'px',
      backgroundPosition: img_left + 'px ' + img_top + 'px',
      backgroundRepeat: 'no-repeat',
      overflow: "hidden",
      transition: 'all ' + wds_params[wds].wds_transition_duration + 'ms ' + easing + ' ' + delay + 'ms',
      transform: 'none'
    }).append(grid_div);
  }

  /* Get the current slide's image.*/
  var cur_img = jQuery(current_image_class).find('span[data-img-id^="wds_slideshow_image"]');
  /* Create a grid to hold the gridlets.*/
  var grid = jQuery('<span style="display: block;" />').addClass('wds_grid_' + wds);
  /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
  jQuery(current_image_class).prepend(grid);
  /* vars to calculate positioning/size of gridlets*/
  var cont = jQuery(".wds_slide_bg_" + wds);
  var imgWidth = cur_img.width();
  var imgHeight = cur_img.height();
  var contWidth = cont.width(),
    contHeight = cont.height(),
    imgSrc = cur_img.css('background-image'), /*.replace('/thumb', ''),*/
    colWidth = Math.floor(contWidth / cols),
    rowHeight = Math.floor(contHeight / rows),
    colRemainder = contWidth - (cols * colWidth),
    colAdd = Math.ceil(colRemainder / cols),
    rowRemainder = contHeight - (rows * rowHeight),
    rowAdd = Math.ceil(rowRemainder / rows),
    leftDist = 0,
    img_leftDist = (jQuery(".wds_slide_bg_" + wds).width() - cur_img.width()) / 2;
  /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
  tx = tx === 'auto' ? contWidth : tx;
  tx = tx === 'min-auto' ? -contWidth : tx;
  ty = ty === 'auto' ? contHeight : ty;
  ty = ty === 'min-auto' ? -contHeight : ty;
  /* Loop through cols*/
  for (var i = 0; i < cols; i++) {
    var topDist = 0,
      img_topDst = (jQuery(".wds_slide_bg_" + wds).height() - cur_img.height()) / 2,
      newColWidth = colWidth;
    /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
    if (colRemainder > 0) {
      var add = colRemainder >= colAdd ? colAdd : colRemainder;
      newColWidth += add;
      colRemainder -= add;
    }
    /* Nested loop to create row gridlets for each col.*/
    for (var j = 0; j < rows; j++) {
      var newRowHeight = rowHeight,
        newRowRemainder = rowRemainder;
      /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
      if (newRowRemainder > 0) {
        add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
        newRowHeight += add;
        newRowRemainder -= add;
      }
      /* Create & append gridlet to grid.*/
      grid.append(wds_gridlet(wds, newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
      topDist += newRowHeight;
      img_topDst -= newRowHeight;
    }
    img_leftDist -= newColWidth;
    leftDist += newColWidth;
  }
  /* Show grid & hide the image it replaces.*/
  grid.show();
  cur_img.css('opacity', 0);
  /* Add identifying classes to corner gridlets (useful if applying border radius).*/
  grid.children().first().addClass('rs-top-left');
  grid.children().last().addClass('rs-bottom-right');
  grid.children().eq(rows - 1).addClass('rs-bottom-left');
  grid.children().eq(-rows).addClass('rs-top-right');
  /* Execution steps.*/
  setTimeout(function () {
    grid.children().css({
      opacity: op,
      transform: 'rotate(' + ro + 'deg) rotateY(' + roy + 'deg) translateX(' + tx + 'px) translateY(' + ty + 'px) scale(' + sc + ')'
    });
  }, 1);
  jQuery(next_image_class).css('opacity', 1);
  /* After transition.*/
  var cccount = 0;
  var obshicccount = cols * rows;
  grid.children().one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wds_after_trans_each));

  function wds_after_trans_each(wds) {
    if (++cccount == obshicccount) {
      wds_after_trans(wds);
    }
  }

  function wds_after_trans() {
    jQuery(current_image_class).css({'opacity': 0, 'z-index': 1});
    jQuery(next_image_class).css({'opacity': 1, 'z-index': 2});
    cur_img.css('opacity', 1);
    grid.remove();
    wds_params[wds].wds_trans_in_progress = false;
    if (typeof wds_params[wds].wds_event_stack !== 'undefined') {
      if (wds_params[wds].wds_event_stack.length > 0) {
        key = wds_params[wds].wds_event_stack[0].split("-");
        wds_params[wds].wds_event_stack.shift();
        wds_change_image(wds, key[0], key[1], wds_params[wds].wds_data, true, direction);
      }
    }
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderCE' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCE );
        wds_callback_f();
      }
    });
  }
}

function wds_change_image(wds, current_key, key, wds_data, from_effect, btn) {
  if (typeof btn == "undefined") {
    var btn = "";
  }
  if (!(wds_params[wds].carousel != 0 || wds_data[key]["is_video"] != 'image') && !wds_data[key]["loaded"]) {
    jQuery('<img />').attr("src", wds_data[key]["image_url"])
                     .on('load', function () {
                       jQuery(this).remove();
                       wds_change_image_when_loaded(wds, current_key, key, wds_data, from_effect, btn);
                     })
                     .on('error', function () {
                       jQuery(this).remove();
                       wds_change_image_when_loaded(wds, current_key, key, wds_data, from_effect, btn);
                     });
    wds_data[key]["loaded"] = true;
  }
  else {
    wds_change_image_when_loaded(wds, current_key, key, wds_data, from_effect, btn);
  }
}

function wds_play_wds(wds) {
  wds_params[wds].wds_play_pause_state = 0;
  /* Play.*/
  jQuery(".wds_slideshow_play_pause_" + wds).attr("title", wds_object.pause);
  jQuery(".wds_slideshow_play_pause_" + wds).attr("class", "wds_ctrl_btn_" + wds + " wds_slideshow_play_pause_" + wds + " fa fa-pause");
  /* Finish current animation and begin the other.*/
  if (wds_params[wds].enable_slideshow_autoplay) {
    if (wds_params[wds].timer_bar_type != 'top') {
      if (wds_params[wds].timer_bar_type != 'bottom') {
        if (typeof wds_params[wds].circle_timer_animate !== 'undefined') {
          wds_params[wds].circle_timer_animate.stop();
        }
          wds_circle_timer(wds_params[wds].curent_time_deggree);
      }
    }
  }
  play_wds(wds);
  if (wds_params[wds].enable_slideshow_music) {
    if (wds_params[wds].slideshow_music_url != '') {
      document.getElementById("wds_audio_" + wds).play();
    }
  }
}

function play_wds(wds) {
  if (wds_params[wds].timer_bar_type != 'none') {
    if (wds_params[wds].enable_slideshow_autoplay || jQuery('.wds_ctrl_btn_' + wds).hasClass('fa-pause')) {
      jQuery(".wds_line_timer_" + wds).animate({
        width: "100%"
      }, {
        duration: wds_params[wds].slideshow_interval * 1000,
        specialEasing: {width: "linear"}
      });
    }
  }
  window.clearInterval(wds_params[wds].wds_playInterval);
  /* Play.*/
  wds_params[wds].wds_playInterval = setInterval(function () {
    var curr_img_index = parseInt(jQuery('#wds_current_image_key_' + wds).val());
    if (wds_params[wds].slider_loop == 0) {
      if (wds_params[wds].twoway_slideshow) {
        if (wds_params[wds].wds_global_btn_wds == "left") {
          if (curr_img_index == 0) {
            return false;
          }
        }
        else {
          if (curr_img_index == wds_params[wds].slides_count - 1) {
            return false;
          }
        }
      }
      else {
        if (curr_img_index == wds_params[wds].slides_count - 1) {
          return false;
        }
      }
    }
    if ( typeof wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_' + wds).val())] != "undefined") {
      var curr_img_id = wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_' + wds).val())]["id"];
    }
    wds_params[wds].video_is_playing = false;
    jQuery("#wds_image_id_" + wds + "_" + curr_img_id).find("video").each(function () {
      if (!this.paused) {
          wds_params[wds].video_is_playing = true;
      }
    });
    jQuery("#wds_image_id_" + wds + "_" + curr_img_id).find("iframe[data-type='youtube']").each(function () {
      player = wds_params[wds].youtube_iframes_ids.indexOf(this.id);
      if (typeof wds_params[wds].youtube_iframes[player] != "undefined") {
        if (typeof wds_params[wds].youtube_iframes[player].getPlayerState == "function") {
          if (wds_params[wds].youtube_iframes[player].getPlayerState() == 1) {
              wds_params[wds].video_is_playing = true;
          }
        }
      }
    });
    iframe_message_sent_wds = 0;
    wds_params[wds].iframe_message_received = 0;
    jQuery("#wds_image_id_" + wds + "_" + curr_img_id).find("iframe[data-type='vimeo']").each(function () {
      jQuery(this)[0].contentWindow.postMessage('{ "method": "paused" }', "*");
      iframe_message_sent_wds = iframe_message_sent_wds + 1;
    });

    function wds_call_change() {
      if (!wds_params[wds].video_is_playing) {
        var iterator = 1;
        var img_index = (parseInt(jQuery('#wds_current_image_key_' + wds).val()) + iterator) % wds_params[wds].wds_data.length;
        if (wds_params[wds].enable_slideshow_shuffle) {
          iterator = Math.floor((wds_params[wds].wds_data.length - 1) * Math.random() + 1);
        }
        else if (wds_params[wds].twoway_slideshow) {
          if (wds_params[wds].wds_global_btn_wds == "left") {
            iterator = -1;
          }
        }
        img_index = (parseInt(jQuery('#wds_current_image_key_' + wds).val()) + iterator) >= 0 ? (parseInt(jQuery('#wds_current_image_key_' + wds).val()) + iterator) % wds_params[wds].wds_data.length : wds_params[wds].wds_data.length - 1;
        wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_' + wds).val()), img_index, wds_params[wds].wds_data);
        if (wds_params[wds].carousel == 1) {
            wds_carousel[wds].next();
        }
      }
    }

    function wds_check_message_received() {
      return iframe_message_sent_wds == wds_params[wds].iframe_message_received ? true : false;
    }

    function wds_call(wds_condition, wds_callback) {
      if (wds_condition()) {
        wds_callback();
      }
      else {
        setTimeout(function () {
          wds_call(wds_condition, wds_callback);
        }, 10);
      }
    }

    wds_call(wds_check_message_received, wds_call_change);
  }, parseInt(wds_params[wds].slideshow_interval * 1000) + wds_params[wds].wds_duration_for_change);
}

function wds_change_image_when_loaded(wds, current_key, key, wds_data, from_effect, btn) {
  if (wds_params[wds].carousel == 1) {
    if (wds_currentlyMoving[wds] == true) {
      return;
    }
  }
  /* Pause videos.*/
  jQuery("#wds_slideshow_image_container_" + wds).find("iframe").each(function () {
    if (typeof jQuery(this)[0].contentWindow != "undefined") {
      if (jQuery(this).data('type') == 'youtube') {
        player = wds_params[wds].youtube_iframes_ids.indexOf(this.id);
        if (typeof wds_params[wds].youtube_iframes[player] != "undefined" && wds_done) {
          wds_params[wds].youtube_iframes[player].stopVideo();
        }
      }
      else if (jQuery(this).data('type') == 'vimeo') {
        jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
      }
      else {
        jQuery(this)[0].contentWindow.postMessage('stop', '*');
      }
    }
  });
  jQuery("#wds_slideshow_image_container_" + wds).find("video").each(function () {
    jQuery(this).trigger('pause');
    jQuery('.wds_bigplay_' + wds).show();
  });
  /* Pause layer videos.*/
  jQuery(".wds_video_layer_frame_" + wds).each(function () {
    if (typeof jQuery(this)[0].contentWindow != "undefined") {
      if (jQuery(this).data('type') == 'youtube') {
        player = wds_params[wds].youtube_iframes_ids.indexOf(this.id);
        if (typeof wds_params[wds].youtube_iframes[player] != "undefined") {
          wds_params[wds].youtube_iframes[player].stopVideo();
        }
      }
      else if (jQuery(this).data('type') == 'vimeo') {
        jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
      }
      else {
        jQuery(this)[0].contentWindow.postMessage('stop', '*');
      }
    }
  });

  if (wds_data[key]) {

    if (jQuery('.wds_ctrl_btn_' + wds).hasClass('fa-pause') || wds_params[wds].autoplay) {
      play_wds(wds);
    }
    if (!from_effect) {
      /* Change image key.*/
      jQuery("#wds_current_image_key_" + wds).val(key);
      if (current_key == '-1') { /* Filmstrip.*/
        current_key = jQuery(".wds_slideshow_thumb_active_" + wds).children("img").attr("data-image-key");
      }
      else if (current_key == '-2') { /* Dots.*/
        currId = jQuery(".wds_slideshow_dots_active_" + wds).attr("id");
        current_key = currId.replace('wds_dots_', '').replace('_' + wds, '');
      }
    }
    if (wds_params[wds].wds_trans_in_progress) {
      wds_params[wds].wds_event_stack.push(current_key + '-' + key);
      return;
    }
    if (btn == "") {
      var direction = "right";
      var int_curr_key = parseInt(wds_params[wds].wds_current_key);
      var int_key = parseInt(key);
      var last_pos = wds_data.length - 1;
      if (int_curr_key > int_key) {
        direction = 'left';
      }
      else if (int_curr_key == int_key && wds_zoomfade_first_img !== 0) {
        return;
      }
      /* From last slide to first.*/
      if (int_key == 0) {
        if (int_curr_key == last_pos) {
          direction = 'right';
        }
      }
      /* From first slide to last if there are more than two slides in the slider.*/
      if (int_key == last_pos) {
        if (int_curr_key == 0) {
          if (last_pos > 1) {
            direction = 'left';
          }
        }
      }
    }
    else {
      direction = btn;
    }
    if (wds_params[wds].twoway_slideshow) {
      wds_params[wds].wds_global_btn_wds = direction;
    }
    /* Set active thumbnail position.*/
    if (wds_params[wds].width_or_height == 'width') {
      wds_params[wds].wds_current_filmstrip_pos = key * (jQuery(".wds_slideshow_filmstrip_thumbnail_" + wds).width() + 2 + 2 * 0);
    }
    else {
      wds_params[wds].wds_current_filmstrip_pos = key * (jQuery(".wds_slideshow_filmstrip_thumbnail_" + wds).height() + 2 + 2 * 0);
    }
    wds_params[wds].wds_current_key = key;
    /* Change image id.*/
    jQuery("div[data-img-id=wds_slideshow_image_" + wds + "]").attr('data-image-id', wds_data[key]["id"]);
    if(typeof wds_data[current_key] != "undefined"){
      var current_image_class = "#wds_image_id_" + wds + "_" + wds_data[current_key]["id"];
    }
    var next_image_class = "#wds_image_id_" + wds + "_" + wds_data[key]["id"];
    var next_image_type = wds_data[key]["is_video"];
    if (next_image_type == 'video' || next_image_type.indexOf('EMBED') >= 0) {
      jQuery('.wds_pp_btn_cont').hide();
    }
    else {
      jQuery('.wds_pp_btn_cont').show();
    }
    if (wds_data[key]["target_attr_slide"] == 1) {
      wds_embed_slide_autoplay(next_image_class, wds);
    }
    if (wds_params[wds].preload_images && !wds_params[wds].carousel) {
      if (wds_data[key]["is_video"] == 'image') {
        jQuery(next_image_class).find(".wds_slideshow_image_" + wds).css("background-image", 'url("' + wds_data[key]["image_url"] + '")');
      }
      else if (wds_data[key]["is_video"] == 'EMBED_OEMBED_INSTAGRAM_IMAGE') {
        jQuery(next_image_class).find(".wds_slideshow_image_" + wds).css("background-image", 'url("//instagram.com/p/' + wds_data[key]["image_url"] + '/media/?size=l")');
      }
    }
    wds_video_dimenstion(wds, key);
    if(typeof wds_data[current_key] != "undefined"){
      var current_slide_layers_count = wds_data[current_key]["slide_layers_count"];
    }
    var next_slide_layers_count = wds_data[key]["slide_layers_count"];

    /* Clear layers before image change.*/
    function set_layer_effect_out_before_change(wds, m) {
      wds_params[wds].wds_clear_layers_effects_out_before_change[current_key][m] = setTimeout(function () {
        if (wds_data[current_key]["layer_" + m + "_type"] != 'social') {
          if (jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).prev().attr('id') != 'wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"] + '_round_effect') {
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).css('-webkit-animation-duration', 0.6 + 's').css('animation-duration', 0.6 + 's');
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).removeClass().addClass(wds_data[current_key]["layer_" + m + "_layer_effect_out"] + ' wds_animated');
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).addClass(jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).data("class"));
          }
          else {
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"] + "_div").css('-webkit-animation-duration', 0.6 + 's').css('animation-duration', 0.6 + 's');
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"] + "_div").removeClass().addClass(wds_data[current_key]["layer_" + m + "_layer_effect_out"] + ' wds_animated');
          }
        }
        else {
          jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).css('-webkit-animation-duration', 0.6 + 's').css('animation-duration', 0.6 + 's');
          jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).removeClass().addClass(wds_data[current_key]["layer_" + m + "_layer_effect_out"] + ' fa fa-' + wds_data[current_key]["layer_" + m + "_social_button"] + ' wds_animated');
        }

      }, 10);
    }

    if (wds_params[wds].layer_out_next) {
      for (var m = 0; m < current_slide_layers_count; m++) {
        if (jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).prev().attr('id') != 'wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + i + "_id"] + '_round_effect') {
          if (jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"]).css('opacity') != 0) {
            set_layer_effect_out_before_change(wds, m);
          }
        }
        else {
          if (jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + m + "_id"] + "_div").css('opacity') != 0) {
            set_layer_effect_out_before_change(wds, m);
          }
        }
      }
    }

    /* Loop through current slide layers for clear effects.*/
    setTimeout(function () {
      for (var k = 0; k < current_slide_layers_count; k++) {
        clearTimeout(wds_params[wds].wds_clear_layers_effects_in[current_key][k]);
        clearTimeout(wds_params[wds].wds_clear_layers_effects_out[current_key][k]);
        if (wds_data[current_key]["layer_" + k + "_type"] != 'social') {
          if(wds_data[current_key]["layer_" + k + "_type"] == 'hotspots') {
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + k + "_id"]+'_div').removeClass().addClass('hotspot_container wds_layer_' + wds_data[current_key]["layer_" + k + "_id"]+'_div');
          } else {
            jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + k + "_id"]).removeClass().addClass('wds_layer_' + wds_data[current_key]["layer_" + k + "_id"]);
          }
        }
        else {
          jQuery('#wds_' + wds + '_slide' + wds_data[current_key]["id"] + '_layer' + wds_data[current_key]["layer_" + k + "_id"]).removeClass().addClass('fa fa-' + wds_data[current_key]["layer_" + k + "_social_button"] + ' wds_layer_' + wds_data[current_key]["layer_" + k + "_id"]);
        }
      }
    }, wds_params[wds].wds_duration_for_clear_effects);

    /* Loop through layers in.*/
    for (var j = 0; j < next_slide_layers_count; j++) {
      wds_set_layer_effect_in_wds(wds, j, key);
    }

    /* Loop through layers out if pause button not pressed.*/
    for (var i = 0; i < next_slide_layers_count; i++) {
      wds_set_layer_effect_out_wds(wds, i, key);
    }

    setTimeout(function () {
      if (typeof jQuery().finish !== 'undefined') {
        if (jQuery.isFunction(jQuery().finish)) {
          jQuery(".wds_line_timer_" + wds).finish();
        }
      }
      jQuery(".wds_line_timer_" + wds).css({width: 0});
      if (!wds_params[wds].carousel) {
        if (typeof window[wds_params[wds].wds_slideshow_effect] == 'function') {
          window[wds_params[wds].wds_slideshow_effect](wds, current_image_class, next_image_class, direction);
        }
        else {
          wds_none(wds, current_image_class, next_image_class, direction);
        }
      }
      if (wds_params[wds].timer_bar_type != 'none') {

        if (wds_params[wds].enable_slideshow_autoplay || jQuery('.wds_ctrl_btn_' + wds).hasClass('fa-pause')) {
          if (wds_params[wds].timer_bar_type == 'top' || wds_params[wds].timer_bar_type == 'bottom') {
            if (!jQuery(".wds_ctrl_btn_" + wds).hasClass("fa-play")) {
              jQuery(".wds_line_timer_" + wds).animate({
                width: "100%"
              }, {
                duration: wds_params[wds].slideshow_interval * 1000,
                specialEasing: {width: "linear"}
              });
            }
          }
          else if (wds_params[wds].timer_bar_type != 'none') {
            if (typeof wds_params[wds].circle_timer_animate !== 'undefined') {
              wds_params[wds].circle_timer_animate.stop();
            }
            jQuery('#top_right_' + wds).css({
              '-moz-transform': 'rotate(0deg)',
              '-webkit-transform': 'rotate(0deg)',
              '-o-transform': 'rotate(0deg)',
              '-ms-transform': 'rotate(0deg)',
              'transform': 'rotate(0deg)',
              '-webkit-transform-origin': 'left bottom',
              '-ms-transform-origin': 'left bottom',
              '-moz-transform-origin': 'left bottom',
              'transform-origin': 'left bottom'
            });
            jQuery('#bottom_right_' + wds).css({
              '-moz-transform': 'rotate(0deg)',
              '-webkit-transform': 'rotate(0deg)',
              '-o-transform': 'rotate(0deg)',
              '-ms-transform': 'rotate(0deg)',
              'transform': 'rotate(0deg)',
              '-webkit-transform-origin': 'left top',
              '-ms-transform-origin': 'left top',
              '-moz-transform-origin': 'left top',
              'transform-origin': 'left top'
            });
            jQuery('#bottom_left_' + wds).css({
              '-moz-transform': 'rotate(0deg)',
              '-webkit-transform': 'rotate(0deg)',
              '-o-transform': 'rotate(0deg)',
              '-ms-transform': 'rotate(0deg)',
              'transform': 'rotate(0deg)',
              '-webkit-transform-origin': 'right top',
              '-ms-transform-origin': 'right top',
              '-moz-transform-origin': 'right top',
              'transform-origin': 'right top'
            });
            jQuery('#top_left_' + wds).css({
              '-moz-transform': 'rotate(0deg)',
              '-webkit-transform': 'rotate(0deg)',
              '-o-transform': 'rotate(0deg)',
              '-ms-transform': 'rotate(0deg)',
              'transform': 'rotate(0deg)',
              '-webkit-transform-origin': 'right bottom',
              '-ms-transform-origin': 'right bottom',
              '-moz-transform-origin': 'right bottom',
              'transform-origin': 'right bottom'
            });
            if (!jQuery(".wds_ctrl_btn_" + wds).hasClass("fa-play")) {
              /* Begin circle timer on next.*/
              wds_circle_timer(0);
            }
            else {
              wds_params[wds].curent_time_deggree = 0;
            }
          }
        }
      }
      if (wds_params[wds].filmstrip_position != 'none' && wds_params[wds].slides_count > 1) {
        wds_move_filmstrip(wds);
      }

      if (wds_params[wds].bull_position != 'none' && wds_params[wds].slides_count > 1) {
        wds_move_dots(wds);
      }
      if (wds_params[wds].wds_data[key]["is_video"] != 'image') {
        jQuery("#wds_slideshow_play_pause_" + wds).css({display: 'none'});
      }
      else {
        jQuery("#wds_slideshow_play_pause_" + wds).css({display: ''});
      }
    }, wds_params[wds].wds_duration_for_change);
  }
  if (wds_params[wds].parallax_effect == 1) {
    wds_parallax(wds);
  }

  if (wds_params[wds].slider_effect == 'zoomFade') {
    wds_genBgPos(next_image_class, wds, wds_params[wds].slideshow_interval);
    wds_zoomfade_first_img = 1;
  }
  wds_window_fixed_size(wds, next_image_class);

  jQuery.each( wds_params[wds].callback_items, function( index, value ) {
    if ( index === 'onSliderCS' && value !== '' ) {
      var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCS );
      wds_callback_f();
    }
  });
}

function wds_blindR(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 8, 1, 0, 0, 0, 1, 1, current_image_class, next_image_class, direction, 1, 90, 'ease-in-out');
}

function wds_parallelSlideH(wds, current_image_class, next_image_class, direction) {
  var width = jQuery(current_image_class).width();
  var height = jQuery(current_image_class).height();
  if (direction == 'right') {
    wds_parallelSlide(wds, width, 0, -width, 0, current_image_class, next_image_class, direction, 'ease-in-out');
  }
  else if (direction == 'left') {
    wds_parallelSlide(wds, -width, 0, width, 0, current_image_class, next_image_class, direction, 'ease-in-out');
  }
}

function wds_parallelSlideV(wds, current_image_class, next_image_class, direction) {
  var width = jQuery(current_image_class).width();
  var height = jQuery(current_image_class).height();
  if (direction == 'right') {
    wds_parallelSlide(wds, 0, height, 0, -height, current_image_class, next_image_class, direction, 'ease-in-out');
  }
  else if (direction == 'left') {
    wds_parallelSlide(wds, 0, -height, 0, height, current_image_class, next_image_class, direction, 'ease-in-out');
  }
}

function wds_slic3DH(wds, current_image_class, next_image_class, direction) {
  var dimension = jQuery(current_image_class).width() / 2;
  if (direction == 'right') {
    wds_grid3d(wds, 1, 5, dimension, 0, -90, 0, dimension, 90, 0, current_image_class, next_image_class, direction, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
  else if (direction == 'left') {
    wds_grid3d(wds, 1, 5, dimension, 0, 90, 0, -dimension, -90, 0, current_image_class, next_image_class, direction, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
}

function wds_slic3DV(wds, current_image_class, next_image_class, direction) {
  var dimension = jQuery(current_image_class).height() / 2;
  if (direction == 'right') {
    wds_grid3d(wds, 10, 1, dimension, -90, 0, -dimension, 0, 0, 90, current_image_class, next_image_class, direction, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
  else if (direction == 'left') {
    wds_grid3d(wds, 10, 1, dimension, 90, 0, dimension, 0, 0, -90, current_image_class, next_image_class, direction, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
}

function wds_slicR3DH(wds, current_image_class, next_image_class, direction) {
  var dimension = jQuery(current_image_class).width() / 2;
  if (direction == 'right') {
    wds_grid3d(wds, 1, 5, dimension, 0, -90, 0, dimension, 90, 0, current_image_class, next_image_class, direction, 1, 'ease-in-out');
  }
  else if (direction == 'left') {
    wds_grid3d(wds, 1, 5, dimension, 0, 90, 0, -dimension, -90, 0, current_image_class, next_image_class, direction, 1, 'ease-in-out');
  }
}

function wds_slicR3DV(wds, current_image_class, next_image_class, direction) {
  var dimension = jQuery(current_image_class).height() / 2;
  if (direction == 'right') {
    wds_grid3d(wds, 10, 1, dimension, -90, 0, -dimension, 0, 0, 90, current_image_class, next_image_class, direction, 1, 'ease-in-out');
  }
  else if (direction == 'left') {
    wds_grid3d(wds, 10, 1, dimension, 90, 0, dimension, 0, 0, -90, current_image_class, next_image_class, direction, 1, 'ease-in-out');
  }
}

function wds_tilesR(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 8, 8, 0, 0, 0, 1, 1, current_image_class, next_image_class, direction, 1, 90, 'ease-in-out');
}

function wds_blockScaleR(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 8, 6, 0, 0, 0, 0.6, 0, current_image_class, next_image_class, direction, 1, 0, 'ease-in-out');
}

function wds_cubeH(wds, current_image_class, next_image_class, direction) {
  /* Set to half of image width.*/
  var dimension = jQuery(current_image_class).width() / 2;
  if (direction == 'right') {
    wds_cube(wds, dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
  else if (direction == 'left') {
    wds_cube(wds, dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
}

function wds_cubeV(wds, current_image_class, next_image_class, direction) {
  /* Set to half of image height.*/
  var dimension = jQuery(current_image_class).height() / 2;
  /* If next slide.*/
  if (direction == 'right') {
    wds_cube(wds, dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
  else if (direction == 'left') {
    wds_cube(wds, dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
  }
}

function wds_cube(wds, tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction, easing) {
  /* If browser does not support 3d transforms/CSS transitions.*/
  if (!wds_testBrowser_cssTransitions()) {
    return wds_fallback(wds, current_image_class, next_image_class, direction);
  }
  if (!wds_testBrowser_cssTransforms3d()) {
    return wds_fallback3d(wds, current_image_class, next_image_class, direction);
  }
  wds_params[wds].wds_trans_in_progress = true;
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
  jQuery(".wds_slide_container_" + wds).css('overflow', 'visible');
  jQuery(".wds_slideshow_image_spun2_" + wds).css('overflow', 'visible');
  jQuery(".wds_slideshow_image_wrap_" + wds).css('overflow', 'visible');
  var filmstrip_position = wds_params[wds].filmstrip_position;
  if (filmstrip_position == 'none') {
    jQuery(".wds_slideshow_image_" + wds).css('border-radius', jQuery(".wds_slideshow_image_wrap_" + wds).css('border-radius'));
  }
  else {
    jQuery(".wds_slideshow_image_" + wds).css('border-radius', wds_params[wds].glb_border_radius);
    jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-radius', wds_params[wds].glb_border_radius);
    if (filmstrip_position == 'top') {
      jQuery(".wds_slideshow_image_" + wds).css('border-top-left-radius', 0);
      jQuery(".wds_slideshow_image_" + wds).css('border-top-right-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-bottom-left-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-bottom-right-radius', 0);
    }
    else if (filmstrip_position == 'bottom') {
      jQuery(".wds_slideshow_image_" + wds).css('border-bottom-left-radius', 0);
      jQuery(".wds_slideshow_image_" + wds).css('border-bottom-right-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-top-left-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-top-right-radius', 0);
    }
    else if (filmstrip_position == 'right') {
      jQuery(".wds_slideshow_image_" + wds).css('border-bottom-right-radius', 0);
      jQuery(".wds_slideshow_image_" + wds).css('border-top-right-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-bottom-left-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-top-left-radius', 0);
    }
    else if (filmstrip_position == 'left') {
      jQuery(".wds_slideshow_image_" + wds).css('border-bottom-left-radius', 0);
      jQuery(".wds_slideshow_image_" + wds).css('border-top-left-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-bottom-right-radius', 0);
      jQuery(".wds_slideshow_filmstrip_container_" + wds).css('border-top-right-radius', 0);
    }
  }
  jQuery(".wds_slide_bg_" + wds).css('perspective', 1000);
  jQuery(current_image_class).css({
    transform: 'translateZ(' + tz + 'px)',
    backfaceVisibility: 'hidden'
  });
  jQuery(next_image_class).css({
    opacity: 1,
    filter: 'Alpha(opacity=100)',
    zIndex: 2,
    backfaceVisibility: 'hidden',
    transform: 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY(' + nry + 'deg) rotateX(' + nrx + 'deg)'
  });
  jQuery(".wds_slider_" + wds).css({
    transform: 'translateZ(-' + tz + 'px)',
    transformStyle: 'preserve-3d',
    position: 'absolute'
  });
  /* Execution steps.*/
  setTimeout(function () {
    jQuery(".wds_slider_" + wds).css({
      transition: 'all ' + wds_params[wds].wds_transition_duration + 'ms ' + easing,
      transform: 'translateZ(-' + tz + 'px) rotateX(' + wrx + 'deg) rotateY(' + wry + 'deg)'
    });
  }, 20);
  /* After transition.*/
  jQuery(".wds_slider_" + wds).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wds_after_trans));

  function wds_after_trans() {
    jQuery(current_image_class).removeAttr('style');
    jQuery(next_image_class).removeAttr('style');
    jQuery(".wds_slider_" + wds).removeAttr('style');
    jQuery(current_image_class).css({'opacity': 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
    jQuery(next_image_class).css({'opacity': 1, filter: 'Alpha(opacity=100)', 'z-index': 2});
    wds_params[wds].wds_trans_in_progress = false;
    if (typeof wds_params[wds].wds_event_stack !== 'undefined') {
      if (wds_params[wds].wds_event_stack.length > 0) {
        key = wds_params[wds].wds_event_stack[0].split("-");
        wds_params[wds].wds_event_stack.shift();
        wds_change_image(wds, key[0], key[1], wds_params[wds].wds_data, true, direction);
      }
    }
    jQuery(".wds_slide_container_" + wds).css('overflow', 'hidden');
    jQuery(".wds_slideshow_image_spun2_" + wds).css('overflow', 'hidden');
    jQuery(".wds_slideshow_image_wrap_" + wds).css('overflow', 'hidden');
    jQuery(".wds_slide_bg_" + wds).css('perspective', 'none');
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderCE' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCE );
        wds_callback_f();
      }
    });
  }
}

function wds_cubeR(wds, current_image_class, next_image_class, direction) {
  var random_direction = Math.floor(Math.random() * 2);
  var dimension = random_direction ? jQuery(current_image_class).height() / 2 : jQuery(current_image_class).width() / 2;
  if (direction == 'right') {
    if (random_direction) {
      wds_cube(wds, dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
    }
    else {
      wds_cube(wds, dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
    }
  }
  else if (direction == 'left') {
    if (random_direction) {
      wds_cube(wds, dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
    }
    else {
      wds_cube(wds, dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
    }
  }
}

function wds_sliceH(wds, current_image_class, next_image_class, direction) {
  if (direction == 'right') {
    var translateX = 'min-auto';
  }
  else if (direction == 'left') {
    var translateX = 'auto';
  }
  wds_grid(wds, 1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_slideV(wds, current_image_class, next_image_class, direction) {
  if (direction == 'right') {
    var translateY = 'auto';
  }
  else if (direction == 'left') {
    var translateY = 'min-auto';
  }
  wds_grid(wds, 1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction, 0, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
}

function wds_slideH(wds, current_image_class, next_image_class, direction) {
  if (direction == 'right') {
    var translateX = 'min-auto';
  }
  else if (direction == 'left') {
    var translateX = 'auto';
  }
  wds_grid(wds, 1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction, 0, 0, 'cubic-bezier(0.785, 0.135, 0.150, 0.860)');
}

function wds_scaleOut(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_scaleIn(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_blockScale(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 8, 6, 0, 0, 0, 0.6, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_kaleidoscope(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_fan(wds, current_image_class, next_image_class, direction) {
  if (direction == 'right') {
    var rotate = 45;
    var translateX = 100;
  }
  else if (direction == 'left') {
    var rotate = -45;
    var translateX = -100;
  }
  wds_grid(wds, 1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_blindV(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_blindH(wds, current_image_class, next_image_class, direction) {
  wds_grid(wds, 10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class, direction, 0, 0, 'ease-in-out');
}

function wds_random(wds, current_image_class, next_image_class, direction) {
  var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV', 'parallelSlideH', 'parallelSlideV'];
  /* Pick a random transition from the anims array.*/
  window["wds_" + anims[Math.floor(Math.random() * anims.length)]](wds, current_image_class, next_image_class, direction);
}

function wds_3Drandom(wds, current_image_class, next_image_class, direction) {
  var wds = wds_params[wds].wds;
  var anims = ['cubeH', 'cubeV', 'cubeR', 'slic3DH', 'slic3DV', 'slicR3DH', 'slicR3DV'];
  /* Pick a random transition from the anims array.*/
  window["wds_" + anims[Math.floor(Math.random() * anims.length)]](wds, current_image_class, next_image_class, direction);
}

function wds_grid3d(wds, cols, rows, tz, wrx, wry, nty, ntx, nry, nrx, current_image_class, next_image_class, direction, random, easing) {
  /* If browser does not support CSS transitions.*/
  if (!wds_testBrowser_cssTransitions()) {
    return wds_fallback(wds, current_image_class, next_image_class, direction);
  }
  wds_params[wds].wds_trans_in_progress = true;
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
  /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
  var count = (wds_params[wds].wds_transition_duration) / (cols + rows);

  /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
  function wds_gridlet(width, height, top, img_top, left, img_left, src, src2, imgWidth, imgHeight, c, r) {
    var delay = random ? Math.floor((cols + rows) * count * Math.random()) : (c + r) * count;
    /* Return a gridlet elem with styles for specific transition.*/
    var grid_div = jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: "100%",
      height: "100%",
      transform: 'translateZ(' + tz + 'px)',
      backfaceVisibility: 'hidden',
      overflow: 'hidden'
    }).append(jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: jQuery(".wds_slideshow_image_spun_" + wds).width() + "px",
      height: jQuery(".wds_slideshow_image_spun_" + wds).height() + "px",
      top: -top,
      left: -left,
      backgroundImage: src,
      backgroundSize: jQuery(".wds_slideshow_image_" + wds).css("background-size"),
      backgroundPosition: jQuery(".wds_slideshow_image_" + wds).css("background-position"),
      backgroundRepeat: 'no-repeat',
    }));
    var grid_div2 = jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: "100%",
      height: "100%",
      backfaceVisibility: 'hidden',
      transform: 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY(' + nry + 'deg) rotateX(' + nrx + 'deg)',
      overflow: 'hidden'
    }).append(jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: jQuery(".wds_slideshow_image_spun_" + wds).width() + "px",
      height: jQuery(".wds_slideshow_image_spun_" + wds).height() + "px",
      top: -top,
      left: -left,
      backgroundImage: src2,
      backgroundSize: jQuery(".wds_slideshow_image_" + wds).css("background-size"),
      backgroundPosition: jQuery(".wds_slideshow_image_" + wds).css("background-position"),
      backgroundRepeat: 'no-repeat',
    }));
    return jQuery('<span class="wds_gridlet_' + wds + '" />').css({
      display: "block",
      width: width,
      height: height,
      top: top,
      left: left,
      transition: 'all ' + wds_params[wds].wds_transition_duration + 'ms ' + easing + ' ' + delay + 'ms',
      transform: 'translateZ(-' + tz + 'px)',
      transformStyle: 'preserve-3d',
    }).append(grid_div).append(grid_div2);
  }

  /* Get the current slide's image.*/
  var cur_img = jQuery(current_image_class).find('span[data-img-id^="wds_slideshow_image"]');
  var next_img = jQuery(next_image_class).find('span[data-img-id^="wds_slideshow_image"]');
  /* Create a grid to hold the gridlets.*/
  var grid = jQuery('<span style="display: block;" />').addClass('wds_grid_' + wds).css('perspective', 1000);
  /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
  jQuery(current_image_class).prepend(grid);
  /* vars to calculate positioning/size of gridlets*/
  var cont = jQuery(".wds_slide_bg_" + wds);
  var imgWidth = cur_img.width();
  var imgHeight = cur_img.height();
  var contWidth = cont.width(),
    contHeight = cont.height(),
    imgSrc = cur_img.css('background-image'),
    imgSrcNext = next_img.css('background-image'),
    colWidth = Math.floor(contWidth / cols),
    rowHeight = Math.floor(contHeight / rows),
    colRemainder = contWidth - (cols * colWidth),
    colAdd = Math.ceil(colRemainder / cols),
    rowRemainder = contHeight - (rows * rowHeight),
    rowAdd = Math.ceil(rowRemainder / rows),
    leftDist = 0,
    img_leftDist = (jQuery(".wds_slide_bg_" + wds).width() - cur_img.width()) / 2;
  /* Loop through cols*/
  for (var i = 0; i < cols; i++) {
    var topDist = 0,
      img_topDst = (jQuery(".wds_slide_bg_" + wds).height() - cur_img.height()) / 2,
      newColWidth = colWidth;
    /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
    if (colRemainder > 0) {
      var add = colRemainder >= colAdd ? colAdd : colRemainder;
      newColWidth += add;
      colRemainder -= add;
    }
    /* Nested loop to create row gridlets for each col.*/
    for (var j = 0; j < rows; j++) {
      var newRowHeight = rowHeight,
        newRowRemainder = rowRemainder;
      /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
      if (newRowRemainder > 0) {
        add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
        newRowHeight += add;
        newRowRemainder -= add;
      }
      /* Create & append gridlet to grid.*/
      grid.append(wds_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgSrcNext, imgWidth, imgHeight, i, j));
      topDist += newRowHeight;
      img_topDst -= newRowHeight;
    }
    img_leftDist -= newColWidth;
    leftDist += newColWidth;
  }
  /* Show grid & hide the image it replaces.*/
  grid.show();
  cur_img.css('opacity', 0);
  /* Execution steps.*/
  setTimeout(function () {
    grid.children().css({
      transform: 'translateZ(-' + tz + 'px) rotateX(' + wrx + 'deg) rotateY(' + wry + 'deg)'
    });
  }, 1);
  /* After transition.*/
  var cccount = 0;
  var obshicccount = cols * rows;
  grid.children().one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wds_after_trans_each));

  function wds_after_trans_each(wds) {
    if (++cccount == obshicccount) {
      wds_after_trans(wds);
    }
  }

  function wds_after_trans() {
    jQuery(current_image_class).css({'opacity': 0, 'z-index': 1});
    jQuery(next_image_class).css({'opacity': 1, 'z-index': 2});
    cur_img.css('opacity', 1);
    grid.remove();
    wds_params[wds].wds_trans_in_progress = false;
    if (typeof wds_params[wds].wds_event_stack !== 'undefined') {
      if (wds_params[wds].wds_event_stack.length > 0) {
        key = wds_params[wds].wds_event_stack[0].split("-");
        wds_params[wds].wds_event_stack.shift();
        wds_change_image(wds, key[0], key[1], wds_params[wds].wds_data, true, direction);
      }
    }
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderCE' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCE );
        wds_callback_f();
      }
    });
  }
}

function wds_window_fixed_size(wds, id) {

  if (wds_params[wds].fixed_bg != 1 || wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_' + wds).val())]["is_video"] != 'image') {
    return;
  }
  var image = new Image();
  image.src = jQuery(id + " .wds_slideshow_image_" + wds).css('background-image').replace(/url\(|\)$|"/ig, '');
  var slide_bg_width = image.width;
  var slide_bg_height = image.height;
  if (typeof image.remove != 'undefined') {
    image.remove();
  }
  var window_height = jQuery(window).height();
  var window_width = jQuery(window).width();
  var width, height;
  var scale = Math.max(window_width / slide_bg_width, window_height / slide_bg_height);
  width = slide_bg_width * scale;
  height = slide_bg_height * scale;
  /* TOOD remove this
   if ('<?php echo $slider->bg_fit; ?>' == 'cover' || '<?php echo $slider->bg_fit; ?>' == 'contain') {
   var scale = Math.max(window_width / slide_bg_width, window_height / slide_bg_height);
   width = slide_bg_width * scale;
   height = slide_bg_height * scale;
   }
   else {
   width = window_width;
   height = window_height;
   }
   */
  jQuery(id + " .wds_slideshow_image_"+wds).css({"background-size": width + "px " + height + "px"});
  wds_window_fixed_pos(wds, id);
}

function wds_window_fixed_pos(wds, id) {
  var cont = (typeof id == "undefined") ? "" : id + " ";
  var offset = jQuery(cont + ".wds_slideshow_image_" + wds).offset().top;
  var scrtop = jQuery(document).scrollTop();
  var sliderheight = jQuery(cont + ".wds_slideshow_image_" + wds).height();
  var window_height = jQuery(window).height();
  var fixed_pos;
  if (wds_params[wds].smart_crop == '1') {
    if (wds_params[wds].crop_image_position == 'right bottom'
      || wds_params[wds].crop_image_position == 'center bottom'
      || wds_params[wds].crop_image_position == 'left bottom') {
      pos_retion_height = "100%";
    }
    else if (wds_params[wds].crop_image_position == 'left center'
      || wds_params[wds].crop_image_position == 'center center'
      || wds_params[wds].crop_image_position == 'right center') {
      pos_retion_height = "50%";
    }
    else if (wds_params[wds].crop_image_position == 'left top'
      || wds_params[wds].crop_image_position == 'center top'
      || wds_params[wds].crop_image_position == 'right top') {
      pos_retion_height = "0%";
    }
  }
  fixed_pos = offset - scrtop - window_height / 2 + sliderheight / 2;
  jQuery(cont + ".wds_slideshow_image_" + wds).css({"background-position": "50% " + "calc(50% - " + fixed_pos + "px)"});
  if (scrtop < offset + sliderheight) {
    if (wds_params[wds].smart_crop == '1') {
      jQuery(cont + ".wds_slideshow_image_" + wds).css({"background-position": "" + pos_retion_height + " " + "calc(50% - " + fixed_pos + "px)"});
    }
  }
}

/* Effects out part.*/
function wds_set_layer_effect_out_wds(wds, i, key) {
  var cout;
  wds_params[wds].wds_clear_layers_effects_out[key][i] = setTimeout(function () {
    cout = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"]);
    if (wds_params[wds].wds_data[key]["layer_" + i + "_layer_effect_out"] != 'none') {
      if (wds_params[wds].wds_data[key]["layer_" + i + "_type"] != 'social') {
        if (jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"]).prev().attr('id') != 'wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"] + '_round_effect') {
          cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's');
          cout.removeClass().addClass(wds_params[wds].wds_data[key]["layer_" + i + "_layer_effect_out"] + ' wds_animated');
        }
        else {
          cout = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"] + '_div');
          cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's');
          cout.removeClass().addClass(wds_params[wds].wds_data[key]["layer_" + i + "_layer_effect_out"] + ' wds_animated');
        }

        setTimeout(function () {
          if(wds_params[wds].wds_data[key]["layer_" + i + "_type"] == 'upvideo') {
            var curr_iframe = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"]+" video");
            jQuery("#wds_slideshow_image_container_" + wds).find("video").each(function () {
              if(jQuery(this).attr('id') == curr_iframe.attr('id')) {
                jQuery(this).trigger('pause');
                jQuery('.wds_bigplay_' + wds).show();
              }
            });
          }

          if(wds_params[wds].wds_data[key]["layer_" + i + "_type"] == 'video') {
            var curr_iframe = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + i + "_id"]+" .wds_video_layer_frame_" + wds);
            /* Pause layer videos. */
            jQuery(".wds_video_layer_frame_" + wds).each(function () {
              if(jQuery(this).attr('id') == curr_iframe.attr('id')) {
                if (typeof jQuery(this)[0].contentWindow != "undefined") {
                  if (jQuery(this).data('type') == 'youtube') {
                    player = wds_params[wds].youtube_iframes_ids.indexOf(this.id);

                    if (typeof wds_params[wds].youtube_iframes[player] != "undefined" && wds_done) {
                      wds_params[wds].youtube_iframes[player].stopVideo();
                    }
                  }
                  else if (jQuery(this).data('type') == 'vimeo') {
                    jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
                  }
                  else {
                    jQuery(this)[0].contentWindow.postMessage('stop', '*');
                  }
                }
              }
            });
          }
        },wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"]);
      }
      else {
        cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + i + "_duration_eff_out"] / 1000 + 's');
        cout.removeClass().addClass(wds_params[wds].wds_data[key]["layer_" + i + "_layer_effect_out"] + ' fa fa-' + wds_params[wds].wds_data[key]["layer_" + i + "_social_button"] + ' wds_animated');
      }
      var iteration_count = wds_params[wds].wds_data[key]["layer_" + i + "_infinite_out"] == 0 ? 'infinite' : wds_params[wds].wds_data[key]["layer_" + i + "_infinite_out"];
      cout.css(
        '-webkit-animation-iteration-count', iteration_count
      ).css(
        'animation-iteration-count', iteration_count
      );
    }
  }, wds_params[wds].wds_data[key]["layer_" + i + "_end"]);
}

/* Effects in part.*/
function wds_set_layer_effect_in_wds(wds, j, key) {
  var cout;
  wds_params[wds].wds_clear_layers_effects_in[key][j] = setTimeout(function () {
    cout = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + j + "_id"]);
    if (wds_params[wds].wds_data[key]["layer_" + j + "_type"] != 'social') {
      if (cout.prev().attr('id') != 'wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + j + "_id"] + '_round_effect') {
        cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's');
        cout.attr("class", "");
        cout.addClass(wds_params[wds].wds_data[key]["layer_" + j + "_layer_effect_in"] + ' wds_animated');
        cout.addClass(jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + j + "_id"]).data("class"));
      }
      else {
        cout = jQuery('#wds_' + wds + '_slide' + wds_params[wds].wds_data[key]["id"] + '_layer' + wds_params[wds].wds_data[key]["layer_" + j + "_id"] + '_div');
        cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's');
        cout.removeClass().addClass('hotspot_container ' + wds_params[wds].wds_data[key]["layer_" + j + "_layer_effect_in"] + ' wds_animated');
      }
    }
    else {
      cout.css('-webkit-animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's').css('animation-duration', wds_params[wds].wds_data[key]["layer_" + j + "_duration_eff_in"] / 1000 + 's');
      cout.removeClass().addClass(wds_params[wds].wds_data[key]["layer_" + j + "_layer_effect_in"] + ' fa fa-' + wds_params[wds].wds_data[key]["layer_" + j + "_social_button"] + ' wds_animated');
    }
    /* Play video on layer in.*/

    if (wds_params[wds].wds_data[key]["layer_" + j + "_type"] == "video") {
      if (wds_params[wds].wds_data[key]["layer_" + j + "_video_autoplay"] == "on") {
        cout.find("iframe").each(function () {
          if (jQuery(this).data('type') == 'youtube') {
            player = wds_params[wds].youtube_iframes_ids.indexOf(this.id);
            if (typeof wds_params[wds].youtube_iframes[player] != "undefined") {
              wds_playVideo(wds_params[wds].youtube_iframes[player]);
            }
          }
          else if (jQuery(this).data('type') == 'vimeo') {
            jQuery(this)[0].contentWindow.postMessage('{ "method": "play" }', "*");
          }
          else {
            jQuery(this)[0].contentWindow.postMessage('play', '*');
          }
        });
      }
    }
    wds_upvideo_layer_dimenstion(wds, key, j);
    var iteration_count = wds_params[wds].wds_data[key]["layer_" + j + "_infinite_in"] == 0 ? 'infinite' : wds_params[wds].wds_data[key]["layer_" + j + "_infinite_in"];
    cout.css(
      '-webkit-animation-iteration-count', iteration_count
    ).css(
      'animation-iteration-count', iteration_count
    );
  }, wds_params[wds].wds_data[key]["layer_" + j + "_start"]);
}

function wds_none(wds, current_image_class, next_image_class, direction) {
  jQuery(current_image_class).css({'opacity': 0, 'z-index': 1});
  jQuery(next_image_class).css({'opacity': 1, 'z-index': 2});
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
}

function wds_parallelSlide(wds, ni_left, ni_top, tx, ty, current_image_class, next_image_class, direction, easing) {
  /* If browser does not support 3d transforms/CSS transitions.*/
  if (!wds_testBrowser_cssTransitions()) {
    return wds_fallback(wds, current_image_class, next_image_class, direction);
  }
  if (!wds_testBrowser_cssTransforms3d(wds)) {
    return wds_fallback3d(wds, current_image_class, next_image_class, direction);
  }
  wds_params[wds].wds_trans_in_progress = true;
  /* Set active thumbnail.*/
  wds_set_filmstrip_class(wds);
  wds_set_dots_class(wds);
  jQuery(current_image_class).css({
    position: 'absolute',
    top: '0px',
    left: '0px',
    position: 'absolute',
  });
  jQuery(next_image_class).css({
    position: 'absolute',
    top: ni_top + 'px',
    left: ni_left + 'px',
    'opacity': 1,
    filter: 'Alpha(opacity=100)',
    position: 'absolute',
  });
  jQuery(".wds_slider_" + wds_params[wds].wds).css({
    position: 'relative',
    'backface-visibility': 'hidden'
  });
  jQuery(".wds_slide_bg_" + wds_params[wds].wds).css({
    overflow: 'hidden',
  });
  /* Execution steps.*/
  setTimeout(function () {
    jQuery('.wds_slider_' + wds_params[wds].wds).css({
      transition: 'all ' + wds_params[wds].wds_transition_duration + 'ms ' + easing,
      transform: 'translateX(' + tx + 'px) translateY(' + ty + 'px)',
    });
  }, 1);
  /* After transition.*/
  jQuery('.wds_slider_' + wds_params[wds].wds).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wds_after_trans));

  function wds_after_trans() {
    jQuery(current_image_class).removeAttr('style');
    jQuery(next_image_class).removeAttr('style');
    jQuery(".wds_slider_" + wds_params[wds].wds).removeAttr('style');
    jQuery(".wds_slide_bg_" + wds_params[wds].wds).removeAttr('style');
    jQuery(current_image_class).css({'opacity': 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
    jQuery(next_image_class).css({'opacity': 1, filter: 'Alpha(opacity=100)', 'z-index': 2});
    wds_params[wds].wds_trans_in_progress = false;
    if (typeof wds_params[wds].wds_event_stack !== 'undefined') {
      if (wds_params[wds].wds_event_stack.length > 0) {
        key = wds_params[wds].wds_event_stack[0].split("-");
        wds_params[wds].wds_event_stack.shift();
        wds_change_image(wds, key[0], key[1], wds_params[wds].wds_data, true, direction);
      }
    }
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderCE' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderCE );
        wds_callback_f();
      }
    });
  }
}

function wds_callbackItems(wds, callbackList, slide_id) {
  var key = jQuery(".wds_slideshow_image_" + wds + "[data-image-id='" + slide_id + "']").attr('data-image-key');
  switch (callbackList) {
    case 'SlidePlay':
      wds_play_pause(wds, 'play');
      break;
    case 'SlidePause':
      wds_play_pause(wds, 'pause');
      break;
    case 'SlidePlayPause':
      wds_play_pause(wds);
      break;
    case 'SlideNext':
      wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_' + wds).val()), (parseInt(jQuery('#wds_current_image_key_' + wds).val()) + wds_iterator_wds(wds)) % wds_params[wds].wds_data.length, wds_params[wds].wds_data, false, "right");
      if (wds_params[wds].carousel == 1) {
          wds_carousel[wds].next();
      }
      return false;
      break;
    case 'SlidePrevious':
      wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_' + wds).val()), (parseInt(jQuery('#wds_current_image_key_' + wds).val()) - wds_iterator_wds(wds)) >= 0 ? (parseInt(jQuery('#wds_current_image_key_' + wds_params[wds].wds).val()) - wds_iterator_wds(wds)) % wds_params[wds].wds_data.length : wds_params[wds].wds_data.length - 1, wds_params[wds].wds_data, false, "left");
      if (wds_params[wds].carousel == 1) {
          wds_carousel[wds].prev();
      }
      return false;
      break;
    case 'SlideLink':
      wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_' + wds).val()), parseInt(key), wds_params[wds].wds_data);
      if (wds_params[wds].carousel == 1) {
          wds_carousel[wds].shift(jQuery('.wds_slider_car_image' + wds + '[data-image-id=' + slide_id + ']'));
      }
      return false;
      break;
    case 'PlayMusic':
      document.getElementById("wds_audio_" + wds).play();
      break;
  }
}

function wds_iterator_wds(wds) {
  var iterator = 1;
  if (wds_params[wds].enable_slideshow_shuffle) {
    iterator = Math.floor((wds_params[wds].wds_data.length - 1) * Math.random() + 1);
  }
  else if (wds_params[wds].twoway_slideshow) {
    if (wds_params[wds].wds_global_btn_wds == "left") {
      iterator = -1;
    }
    if (wds_params[wds].slider_loop == 0) {
      if (parseInt(jQuery('#wds_current_image_key_' + wds).val()) == 0) {
        iterator = 1;
      }
    }
  }
  return iterator;
}

/* Set filmstrip initial position.*/
function wds_set_filmstrip_pos(wds, filmStripWidth) {
  if ( wds_params[wds].width_or_height == 'width' ) {
    var selectedImagePos = -(wds_params[wds].wds_current_key * jQuery(".wds_slideshow_filmstrip_thumbnails_"+wds).width() / wds_params[wds].slides_count) - jQuery(".wds_slideshow_filmstrip_thumbnail_"+wds).width() / 2;
    var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".wds_slideshow_filmstrip_thumbnails_"+wds).width(), selectedImagePos + filmStripWidth / 2));
  } else if( wds_params[wds].width_or_height == 'height' ) {
    var selectedImagePos = -(wds_params[wds].wds_current_key * jQuery(".wds_slideshow_filmstrip_thumbnails_"+wds).height() / wds_params[wds].slides_count) - jQuery(".wds_slideshow_filmstrip_thumbnail_"+wds).height() / 2;
    var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".wds_slideshow_filmstrip_thumbnails_"+wds).height(), selectedImagePos + filmStripWidth / 2));
  }
  if(wds_params[wds].left_or_top == 'top') {
    jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
      top: imagesContainerLeft
    }, {
      duration: 500,
      complete: function () {
        wds_filmstrip_arrows(wds);
      }
    });
  } else {
    jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
      left: imagesContainerLeft
    }, {
      duration: 500,
      complete: function () {
        wds_filmstrip_arrows(wds);
      }
    });
  }
}

function wds_move_filmstrip(wds) {
  if ( wds_params[wds].outerWidth_or_outerHeight == 'outerWidth' ) {
    var wds_filmstrip_width = jQuery(".wds_slideshow_filmstrip_container_" + wds).outerWidth(true);
    var wds_filmstrip_thumbnails_width = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).outerWidth(true);
  }
  else {
    var wds_filmstrip_width = jQuery(".wds_slideshow_filmstrip_container_" + wds).outerHeight(true);
    var wds_filmstrip_thumbnails_width = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).outerHeight(true);
  }
  if ( wds_params[wds].left_or_top == 'left' ) {
    var image_left = jQuery(".wds_slideshow_thumb_active_" + wds).position().left;
    var long_filmstrip_cont_left = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left;
    var long_filmstrip_cont_right = Math.abs(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left) + wds_filmstrip_width;
    if ( wds_params[wds].outerWidth_or_outerHeight == 'outerWidth' ) {
      var image_right = jQuery(".wds_slideshow_thumb_active_" + wds).position().left + jQuery(".wds_slideshow_thumb_active_" + wds).outerWidth(true);
    }
    else {
      var image_right = jQuery(".wds_slideshow_thumb_active_" + wds).position().left + jQuery(".wds_slideshow_thumb_active_" + wds).outerHeight(true);
    }
  }
  if ( wds_params[wds].left_or_top == 'top' ) {
    var image_left = jQuery(".wds_slideshow_thumb_active_" + wds).position().top;
    var long_filmstrip_cont_left = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top;
    var long_filmstrip_cont_right = Math.abs(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top) + wds_filmstrip_width;
    if ( wds_params[wds].outerWidth_or_outerHeight == 'outerWidth' ) {
      var image_right = jQuery(".wds_slideshow_thumb_active_" + wds).position().top + jQuery(".wds_slideshow_thumb_active_" + wds).outerWidth(true);
    }
    else {
      var image_right = jQuery(".wds_slideshow_thumb_active_" + wds).position().top + jQuery(".wds_slideshow_thumb_active_" + wds).outerHeight(true);
    }
  }
  if ( wds_filmstrip_width > wds_filmstrip_thumbnails_width ) {
    return;
  }
  var left_or_top = wds_params[wds].left_or_top;
  if ( image_left < Math.abs(long_filmstrip_cont_left) ) {
    if(left_or_top == 'top') {
      jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
        top: -image_left
      }, {
        duration: 500,
        complete: function () {
          wds_filmstrip_arrows(wds);
        }
      });
    } else {
      jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
        left: -image_left
      }, {
        duration: 500,
        complete: function () {
          wds_filmstrip_arrows(wds);
        }
      });
    }
  }
  else if ( image_right > long_filmstrip_cont_right ) {
    if(left_or_top == 'top') {
      jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
        top: -(image_right - wds_filmstrip_width)
      }, {
        duration: 500,
        complete: function () {
          wds_filmstrip_arrows(wds);
        }
      });
    }
    else {
      jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({
        left: -(image_right - wds_filmstrip_width)
      }, {
        duration: 500,
        complete: function () {
          wds_filmstrip_arrows(wds);
        }
      });
    }
  }
}

function wds_move_dots(wds) {
  if(typeof jQuery(".wds_slideshow_dots_active_" + wds).position() != "undefined"){
    var image_left = jQuery(".wds_slideshow_dots_active_" + wds).position().left;
    var image_right = jQuery(".wds_slideshow_dots_active_" + wds).position().left + jQuery(".wds_slideshow_dots_active_" + wds).outerWidth(true);
 }
  var wds_dots_width = jQuery(".wds_slideshow_dots_container_" + wds).outerWidth(true);
  var wds_dots_thumbnails_width = jQuery(".wds_slideshow_dots_thumbnails_" + wds).outerWidth(true);
  if(typeof jQuery(".wds_slideshow_dots_thumbnails_" + wds).position() != "undefined") {
    var long_filmstrip_cont_left = jQuery(".wds_slideshow_dots_thumbnails_" + wds).position().left;
    var long_filmstrip_cont_right = Math.abs(jQuery(".wds_slideshow_dots_thumbnails_" + wds).position().left) + wds_dots_width;
  }
  if (!wds_params[wds].carousel) {
    if (wds_dots_width > wds_dots_thumbnails_width) {
      return;
    }
  }
  if (image_left < Math.abs(long_filmstrip_cont_left)) {
    jQuery(".wds_slideshow_dots_thumbnails_" + wds).animate({
      left: -image_left
    }, {
      duration: 500
    });
  }
  else if (image_right > long_filmstrip_cont_right) {
    jQuery(".wds_slideshow_dots_thumbnails_" + wds).animate({
      left: -(image_right - wds_dots_width)
    }, {
      duration: 500
    });
  }
}

/* Show/hide filmstrip arrows.*/
function wds_filmstrip_arrows(wds) {
  if (wds_params[wds].width_or_height == 'width') {
    var cond1 = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).width();
    var cond2 = jQuery(".wds_slideshow_filmstrip_" + wds).width();
  }
  else if (wds_params[wds].width_or_height == 'height') {
    var cond1 = jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).height();
    var cond2 = jQuery(".wds_slideshow_filmstrip_" + wds).height();
  }
  if (cond1 < cond2) {
    jQuery(".wds_slideshow_filmstrip_left_" + wds).hide();
    jQuery(".wds_slideshow_filmstrip_right_" + wds).hide();
  }
  else {
    jQuery(".wds_slideshow_filmstrip_left_" + wds).show();
    jQuery(".wds_slideshow_filmstrip_right_" + wds).show();
  }
}

function wds_testBrowser_cssTransitions() {
  return wds_testDom('Transition');
}

function wds_testBrowser_cssTransforms3d() {
  return wds_testDom('Perspective');
}

function wds_testDom(prop) {
  /* Browser vendor DOM prefixes.*/
  var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
  var i = domPrefixes.length;
  while (i--) {
    if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
      return true;
    }
  }
  return false;
}

function wds_set_dots_class(wds) {
  jQuery(".wds_slideshow_dots_" + wds).removeClass("wds_slideshow_dots_active_" + wds).addClass("wds_slideshow_dots_deactive_" + wds);
  jQuery("#wds_dots_" + wds_params[wds].wds_current_key + "_" + wds).removeClass("wds_slideshow_dots_deactive_" + wds).addClass("wds_slideshow_dots_active_" + wds);
  if (wds_params[wds].bull_butt_img_or_not == 'style') {
    jQuery(".wds_slideshow_dots_" + wds).removeClass(wds_params[wds].bull_style_active).addClass(wds_params[wds].bull_style_deactive);
    jQuery("#wds_dots_" + wds_params[wds].wds_current_key + "_" + wds).removeClass(wds_params[wds].bull_style_deactive).addClass(wds_params[wds].bull_style_active);
  }
}

function wds_set_filmstrip_class(wds) {
  jQuery('.wds_slideshow_filmstrip_thumbnail_' + wds).removeClass('wds_slideshow_thumb_active_' + wds).addClass('wds_slideshow_thumb_deactive_' + wds);
  jQuery('#wds_filmstrip_thumbnail_' + wds_params[wds].wds_current_key + '_' + wds).removeClass('wds_slideshow_thumb_deactive_' + wds).addClass('wds_slideshow_thumb_active_' + wds);
}

var wds_done = false;
var wds_ready = false;

function wds_resize_instagram_post( wds ) {
  if (jQuery('.inner_instagram_iframe_wds_video_frame_'+wds).length) {
    var post_width = jQuery('.wds_slideshow_video_'+wds).width();
    var post_height = jQuery('.wds_slideshow_video_'+wds).height();
    var parent_height = post_height;
    jQuery('.inner_instagram_iframe_wds_video_frame_'+wds).each(function() {
      var parent_container = jQuery(this).parent();
      if (post_height < post_width + 88) {
        post_width = post_height - 88;
      }
      else {
        post_height = post_width + 88;
      }
      parent_container.height(post_height);
      parent_container.width(post_width);
      parent_container.css({top: 0.5 * (parent_height - post_height)});
    });
  }
}

function wds_resize_slider( wds ) {
  if( jQuery(window).width() < parseInt(wds_params[wds].full_width_for_mobile) ||
  (wds_params[wds].full_width == '1') ){
    var full_width = '1';
  } else {
    var full_width = wds_params[wds].full_width;
  }
  wds_params[wds].wds_glb_margin = parseInt(wds_params[wds].glb_margin);
  if (wds_params[wds].bull_butt_img_or_not == 'text') {
    wds_set_text_dots_cont( wds );
  }
  var slide_orig_width = wds_params[wds].image_width + (wds_params[wds].filmstrip_direction == 'horizontal' ? 0 : wds_params[wds].filmstrip_width);
  var slide_orig_height = wds_params[wds].image_height + (wds_params[wds].filmstrip_direction == 'horizontal' ? wds_params[wds].filmstrip_height : 0);
  var slide_width = wds_get_overall_parent(jQuery("#wds_container1_"+wds));
  var ratio;
  if (wds_params[wds].carousel != 1) {
    if (slide_width > slide_orig_width) {
      slide_width = slide_orig_width;
    }
    ratio = slide_width / (slide_orig_width + 2 * wds_params[wds].wds_glb_margin);
  } 
  if (full_width == '1') {
    ratio = jQuery(window).width() / slide_orig_width;
    slide_orig_width = jQuery(window).width();
    if (wds_params[wds].auto_height) {
      slide_orig_height = jQuery(window).height();
    }
    else { 
      /* slide_orig_height = wds_params[wds].image_height + wds_params[wds].filmstrip_height * slide_orig_width / wds_params[wds].image_width; */
      slide_orig_height = (wds_params[wds].filmstrip_height + wds_params[wds].image_height) * ratio;
    }
    slide_width = jQuery(window).width();
    wds_full_width( wds );
  }
  else if ( full_width == "2" ) {
    if ( wds_params[wds].carousel != 1 ) {
      slide_orig_width = wds_get_overall_parent(jQuery("#wds_container1_" + wds));
      ratio = slide_orig_width / wds_params[wds].image_width;
      /* slide_orig_height = wds_params[wds].image_height + wds_params[wds].filmstrip_height * slide_orig_width / wds_params[wds].image_width; */
      slide_orig_height = (wds_params[wds].filmstrip_height + wds_params[wds].image_height) * ratio - (2 * wds_params[wds].wds_glb_margin);
    }
  }
  else if ( parseInt(wds_params[wds].full_width_for_mobile) ) {
    jQuery(".wds_slideshow_image_wrap_"+wds).removeAttr("style");
  }
  if ( wds_params[wds].carousel == 1) {
    ratio = 1;
    if ( slide_width < wds_params[wds].carousel_width ) {
      ratio = slide_width / wds_params[wds].carousel_width;
    }
  }
  wds_params[wds].wds_glb_margin = parseInt(wds_params[wds].glb_margin);
  wds_params[wds].wds_glb_margin *= ratio;
  if (full_width == '0') {
    slide_orig_height -= wds_params[wds].wds_glb_margin;
  }
  jQuery("#wds_container2_"+wds).css("margin", wds_params[wds].wds_glb_margin + "px " + (full_width=='1' ? 0 : '') + "");
  var slide_height = slide_orig_height;

  if (slide_orig_width > slide_width && full_width != '2') {
    slide_height = Math.floor(slide_width * slide_orig_height / slide_orig_width);
  }
  jQuery(".wds_slideshow_image_wrap_"+wds+", #wds_container2_"+wds).height(slide_height);
  jQuery(".wds_slideshow_image_"+wds+" img").each(function () {
    var wds_theImage = new Image();
    wds_theImage.src = jQuery(this).attr("src");
    var wds_origWidth = wds_theImage.width;
    var wds_origHeight = wds_theImage.height;
    if (typeof wds_theImage.remove != 'undefined') {
      wds_theImage.remove();
    }
    var wds_imageWidth = jQuery(this).attr("data-wds-image-width");
    var wds_imageHeight = jQuery(this).attr("data-wds-image-height");
    var wds_imageTop = jQuery(this).attr("data-wds-image-top");
    var wds_width = wds_imageWidth;
    if (wds_imageWidth > wds_origWidth) {
      wds_width = wds_origWidth;
    }
    var wds_height = wds_imageHeight;
    if (wds_imageHeight > wds_origHeight) {
      wds_height = wds_origHeight;
    }
    var top_px = parseFloat(wds_imageTop) * wds_params[wds].image_height / 100;
    jQuery(this).css({
      maxWidth: (parseFloat(wds_imageWidth) * ratio) + "px",
      maxHeight: (parseFloat(wds_imageHeight) * ratio) + "px",
      top: ((top_px * (slide_height - ratio * wds_imageHeight) / (wds_params[wds].image_height - wds_imageHeight)) * 100 / slide_height) + "%",
    });
    if (jQuery(this).attr("data-wds-scale") != "on") {
      jQuery(this).css({
        width: (parseFloat(wds_imageWidth) * ratio) + "px",
        height: (parseFloat(wds_imageHeight) * ratio) + "px",
      });
    }
    else if (wds_imageWidth >= wds_origWidth || wds_imageHeight >= wds_origHeight) {
      if (wds_origWidth / wds_imageWidth > wds_origHeight / wds_imageHeight) {
        jQuery(this).css({
          width: (parseFloat(wds_imageWidth) * ratio) + "px"
        });
      }
      else {
        jQuery(this).css({
          height: (parseFloat(wds_imageHeight) * ratio) + "px"
        });
      }
    }
  });

  jQuery(".wds_slideshow_image_"+wds+" [data-type='hotspot']").each(function () {
    jQuery(this).children().each(function () {
      var width = jQuery(this).attr("data-width");
      if (jQuery(this).attr("data-type") == "hotspot_text") {
        var height = jQuery(this).attr("data-height");
        if (width != 0) {
          jQuery(this).width(ratio * width);
        }
        if (height != 0) {
          jQuery(this).height(ratio * height);
        }
        var min_font_size;
        var font_size;
        min_font_size = jQuery(this).attr("data-fmin-size");
        font_size = ratio * jQuery(this).attr("data-fsize");
        if (min_font_size > font_size) {
          font_size = min_font_size;
        }
        jQuery(this).css({fontSize: font_size + "px"});
      }
      else {
        if (width != 0) {
          jQuery(this).width(ratio * width);
          jQuery(this).height(ratio * width);
          jQuery(this).parent().width(ratio * width);
          jQuery(this).parent().height(ratio * width);
        }
        jQuery(this).css({
          borderWidth: ratio * jQuery(this).attr("data-border-width")
        });
      }
    });
  });

    jQuery(".wds_slideshow_image_"+wds+" span, .wds_slideshow_image_"+wds+" i").each(function () {
    var font_size;
    var ratio_new;
    var font_size_new;
    var min_font_size;
    font_size = parseFloat(jQuery(this).attr("data-wds-fsize")) * ratio;
    font_size_new = font_size;
    ratio_new = ratio;
    if (jQuery(this).attr('data-type') == 'wds_text_parent') {
      min_font_size = jQuery(this).attr("data-wds-fmin-size");
      if (min_font_size > font_size) {
        font_size_new = min_font_size;
        ratio_new = ratio * font_size_new / font_size;
      }
    }
    jQuery(this).css({
      fontSize: (font_size_new) + "px",
      lineHeight: "1.25em",
      paddingLeft: (parseFloat(jQuery(this).attr("data-wds-fpaddingl")) * ratio_new) + "px",
      paddingRight: (parseFloat(jQuery(this).attr("data-wds-fpaddingr")) * ratio_new) + "px",
      paddingTop: (parseFloat(jQuery(this).attr("data-wds-fpaddingt")) * ratio_new) + "px",
      paddingBottom: (parseFloat(jQuery(this).attr("data-wds-fpaddingb")) * ratio_new) + "px",
    });
  });

  if ( !wds_object.is_free ) {
    wds_display_hotspot();
    wds_hotspot_position("", ratio);
  }
  if (wds_params[wds].parallax_effect == 1) {
    wds_parallax( wds );
  }
  jQuery(".wds_slideshow_image_"+wds+" [data-type='wds_text_parent']").each(function () {
    var id = jQuery(this).attr("id");
    if (wds_params[wds].wds_data[jQuery("#" + id).data("row-key")]["layer_"+ jQuery("#" + id).data("layer-key") +"_align_layer"] == 1) {
      var left;
      var slider_width = jQuery(".wds_slider_"+ wds).outerWidth();
      var start_left_percent = jQuery(this).attr("data-left-percent");

      /* Fix layer position only one time */
      if( typeof textLayerPosition[wds] == 'undefined') {
        if (start_left_percent == 0) {
          textLayerPosition[wds] = 'left';
        } /* layer width < slider_with/2 */
        else if ( Math.ceil((jQuery("#" +  id).offset().left) - (jQuery(".wds_slideshow_image_"+wds).offset().left)+jQuery(this).outerWidth()) >= slider_width ) {
          textLayerPosition[wds] = 'right';
        }
        else {
          textLayerPosition[wds] = 'center';
        }
      }
      if( textLayerPosition[wds] == 'left' ) {
          left = 0;
      } else if( textLayerPosition[wds] == 'center') {
          left = slider_width / 2 - jQuery(this).outerWidth() / 2;
      } else {
          left = slider_width - jQuery(this).outerWidth();
      }

      var left_percent = (slider_width != 0) ? 100 * left / slider_width : 0;
      jQuery("#" +  id).css({left:left_percent + "%"});
    }
  });
  wds_resize_instagram_post( wds );
  wds_window_fixed_size( wds, "#wds_image_id_"+wds+"_" + wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_'+wds).val())]["id"]);
}

function wds_full_width( wds ) {
  var left = jQuery("#wds_container1_"+wds).offset().left;
  jQuery(".wds_slideshow_image_wrap_"+wds).css({
    left: (-left) + "px",
    width: (jQuery(window).width()) + "px",
    maxWidth: "none"
  });
}

function wds_ready_func( wds ) {
  jQuery.each( wds_params[wds].callback_items, function( index, value ) {
    if ( index === 'onSliderI' && value !== '' ) {
      var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderI );
      wds_callback_f();
    }
  });
  if (wds_params[wds].enable_slideshow_autoplay && wds_params[wds].stop_animation) {
    jQuery("#wds_container1_"+wds).mouseover(function(e) {
      wds_stop_animation( wds );
    });
    jQuery("#wds_container1_"+wds).mouseout(function(e) {
      if (!e) {
        var e = window.event;
      }
      var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
      if (typeof reltg != "undefined") {
        if (reltg != null) {
          if (typeof reltg.tagName != "undefined") {
            while (reltg.tagName != 'BODY') {
              if (reltg.id == this.id){
                return;
              }
              reltg = reltg.parentNode;
            }
          }
        }
      }
      wds_play_animation(wds);
    });
  }

  if (wds_params[wds].bull_butt_img_or_not == 'text') {
    wds_set_text_dots_cont(wds);
  }
  jQuery(".wds_slideshow_image_"+wds+" span, .wds_slideshow_image_"+wds+" i").each(function () {
    jQuery(this).attr("data-wds-fpaddingl", jQuery(this).css("paddingLeft"));
    jQuery(this).attr("data-wds-fpaddingr", jQuery(this).css("paddingRight"));
    jQuery(this).attr("data-wds-fpaddingt", jQuery(this).css("paddingTop"));
    jQuery(this).attr("data-wds-fpaddingb", jQuery(this).css("paddingBottom"));
  });

  if (wds_params[wds].navigation) {
    jQuery("#wds_container2_"+wds).hover(function () {
      jQuery(".wds_right-ico_"+wds).animate({left: 0}, 0, "swing");
      jQuery(".wds_left-ico_"+wds).animate({left: 0}, 0, "swing");
      jQuery("#wds_slideshow_play_pause_"+wds).animate({opacity: 1, filter: "Alpha(opacity=100)"}, 0, "swing");
    }, function () {
      jQuery(".wds_right-ico_"+wds).css({left: 4000});
      jQuery(".wds_left-ico_"+wds).css({left: -4000});
      jQuery("#wds_slideshow_play_pause_"+wds).css({opacity: 0, filter: "Alpha(opacity=0)"});
    });
  }

  if (!wds_params[wds].bull_hover) {
    jQuery("#wds_container2_"+wds).hover(function () {
      jQuery(".wds_slideshow_dots_container_"+wds).animate({opacity: 1, filter: "Alpha(opacity=100)"}, 0, "swing");
    }, function () {
      jQuery(".wds_slideshow_dots_container_"+wds).css({opacity: 0, filter: "Alpha(opacity=0)"});
    });
  }
  wds_resize_slider( wds );

  if ( wds_params[wds].carousel != 1 ) {
    jQuery("#wds_container2_"+wds).css({visibility: 'visible'});
    jQuery(".wds_loading").hide();
  }


  function wds_filmstrip_move_left() {
    if (typeof jQuery().stop !== 'undefined') {
      if (jQuery.isFunction(jQuery().stop)) {
        jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).stop(true, false);
      }
    }

    if (wds_params[wds].left_or_top == 'top') {
      if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top < 0) {
        jQuery(".wds_slideshow_filmstrip_right_" + wds).css({opacity: 1, filter: "Alpha(opacity=100)"});
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top > -wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width) {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({top: 0}, 100, 'linear');
        }
        else {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({top: (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top + wds_params[wds].filmstrip_thumb_margin_hor + wds_params[wds].filmstrip_width)}, 100, 'linear');
        }
      }
      /* Disable left arrow.*/
      window.setTimeout(function () {
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top == 0) {
          jQuery(".wds_slideshow_filmstrip_left_" + wds).css({opacity: 0.3, filter: "Alpha(opacity=30)"});
        }
      }, 500);
    }
    else if (wds_params[wds].left_or_top == 'left') {
      if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left < 0) {
        jQuery(".wds_slideshow_filmstrip_right_" + wds).css({opacity: 1, filter: "Alpha(opacity=100)"});
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left > -wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width) {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({left: 0}, 100, 'linear');
        }
        else {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({left: (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left + wds_params[wds].filmstrip_thumb_margin_hor + wds_params[wds].filmstrip_width)}, 100, 'linear');
        }
      }
      window.setTimeout(function () {
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left == 0) {
          jQuery(".wds_slideshow_filmstrip_left_" + wds).css({opacity: 0.3, filter: "Alpha(opacity=30)"});
        }
      }, 500);
    }
  }

  function wds_filmstrip_move_right() {
    if (typeof jQuery().stop !== 'undefined') {
      if (jQuery.isFunction(jQuery().stop)) {
        jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).stop(true, false);
      }
    }
    if (wds_params[wds].left_or_top == 'top') {
      if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top >= -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).height() - jQuery(".wds_slideshow_filmstrip_container_" + wds).height())) {
        jQuery(".wds_slideshow_filmstrip_left_" + wds).css({opacity: 1, filter: "Alpha(opacity=100)"});
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top < -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).height() - jQuery(".wds_slideshow_filmstrip_container_" + wds).height() - wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width)) {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({top: -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).height() - jQuery(".wds_slideshow_filmstrip_container_" + wds).height())}, 100, 'linear');
        }
        else {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({top: (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top - wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width)}, 100, 'linear');
        }
      }
      /* Disable right arrow.*/
      window.setTimeout(function () {
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().top == -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).height() - jQuery(".wds_slideshow_filmstrip_container_" + wds).height())) {
          jQuery(".wds_slideshow_filmstrip_right_" + wds).css({opacity: 0.3, filter: "Alpha(opacity=30)"});
        }
      }, 500);
    }
    else if (wds_params[wds].left_or_top == 'left') {
      if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left >= -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).width() - jQuery(".wds_slideshow_filmstrip_container_" + wds).width())) {
        jQuery(".wds_slideshow_filmstrip_left_" + wds).css({opacity: 1, filter: "Alpha(opacity=100)"});
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left < -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).width() - jQuery(".wds_slideshow_filmstrip_container_" + wds).width() - wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width)) {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({left: -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).width() - jQuery(".wds_slideshow_filmstrip_container_" + wds).width())}, 100, 'linear');
        }
        else {
          jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).animate({left: (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left - wds_params[wds].filmstrip_thumb_margin_hor - wds_params[wds].filmstrip_width)}, 100, 'linear');
        }
      }
      /* Disable right arrow.*/
      window.setTimeout(function () {
        if (jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).position().left == -(jQuery(".wds_slideshow_filmstrip_thumbnails_" + wds).width() - jQuery(".wds_slideshow_filmstrip_container_" + wds).width())) {
          jQuery(".wds_slideshow_filmstrip_right_" + wds).css({opacity: 0.3, filter: "Alpha(opacity=30)"});
        }
      }, 500);
    }
  }

  if (wds_params[wds].slider_effect == 'zoomFade') {
    wds_genBgPos("#wds_image_id_"+wds+"_" + wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_'+wds).val())]["id"]);
  }
  if (wds_params[wds].image_right_click) {
    /* Disable right click.*/
    jQuery('div[id^="wds_container"]').bind("contextmenu", function () {
      return false;
    });
  }
  if (wds_params[wds].slider_effect == 'fade') {
    var curr_img_id = wds_params[wds].wds_data[parseInt(jQuery('#wds_current_image_key_'+wds).val())]["id"];
    jQuery("#wds_image_id_"+wds+"_" + curr_img_id).css('transition', 'opacity ' + wds_params[wds].wds_transition_duration + 'ms linear');
  }
  var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  if (isMobile) {
    if (wds_params[wds].touch_swipe_nav) {
      wds_swipe(wds);
    }
  }
  else {
    if (wds_params[wds].mouse_swipe_nav) {
      wds_swipe(wds);
    }
  }

  function wds_swipe(wds) {
    if (typeof jQuery().swiperight !== 'undefined') {
      if (jQuery.isFunction(jQuery().swiperight)) {
        jQuery('.wds_slideshow_filmstrip_thumbnails_'+wds).swiperight(function () {
          wds_filmstrip_move_left();
          return false;
        });
        jQuery('#wds_container1_'+wds).swiperight(function () {
          wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds(wds)) >= 0 ? (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds( wds )) % wds_params[wds].wds_data.length : wds_params[wds].wds_data.length - 1, wds_params[wds].wds_data, false, "left");
          if ( wds_params[wds].carousel == 1 ) {
              wds_carousel[wds].prev();
          }
          jQuery.each( wds_params[wds].callback_items, function( index, value ) {
            if ( index === 'onSwipeS' && value !== '' ) {
              var wds_callback_f = new Function( wds_params[wds].callback_items.onSwipeS );
              wds_callback_f();
            }
          });
          return false;
        });
      }
    }
    if (typeof jQuery().swipeleft !== 'undefined') {
      if (jQuery.isFunction(jQuery().swipeleft)) {
        jQuery('.wds_slideshow_filmstrip_thumbnails_'+wds).swipeleft(function () {
          wds_filmstrip_move_right();
          return false;
        });
        jQuery('#wds_container1_'+wds).swipeleft(function () {
          wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) + wds_iterator_wds(wds)) % wds_params[wds].wds_data.length, wds_params[wds].wds_data, false, "right");
          if ( wds_params[wds].carousel == 1 ) {
              wds_carousel[wds].next();
          }
          jQuery.each( wds_params[wds].callback_items, function( index, value ) {
            if ( index === 'onSwipeS' && value !== '' ) {
              var wds_callback_f = new Function( wds_params[wds].callback_items.onSwipeS );
              wds_callback_f();
            }
          });
          return false;
        });
      }
    }
  }

  var wds_click = isMobile ? 'touchend' : 'click';
  var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel"; /* FF doesn't recognize mousewheel as of FF3.x */
  jQuery('.wds_slideshow_filmstrip_'+wds).bind(mousewheelevt, function(e) {
    var evt = window.event || e; /* Equalize event object.*/
    evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
    var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
    if (delta > 0) {
      /* Scroll up.*/
      jQuery(".wds_slideshow_filmstrip_left_"+wds).trigger("click");
    }
    else {
      /* Scroll down.*/
      jQuery(".wds_slideshow_filmstrip_right_"+wds).trigger("click");
    }
    return false;
  });
  jQuery(".wds_slideshow_filmstrip_right_"+wds).on(wds_click, function () {
    wds_filmstrip_move_right();
  });
  jQuery(".wds_slideshow_filmstrip_left_"+wds).on(wds_click, function () {
    wds_filmstrip_move_left();
  });

  /* Set filmstrip initial position.*/
  (wds_params[wds].width_or_height == 'width') ? wds_set_filmstrip_pos(wds, jQuery(".wds_slideshow_filmstrip_container_"+wds).width()) : wds_set_filmstrip_pos(wds, jQuery(".wds_slideshow_filmstrip_container_"+wds).height());

  function wds_message_listener(e) {
    try {
      var data = JSON.parse(e.data);
      if (data.method == "paused") {
        wds_params[wds].iframe_message_received = wds_params[wds].iframe_message_received + 1;
        if (data.value == false) {
          wds_params[wds].video_is_playing = true;
        }
      }
    } catch (e) {
      return false;
    }
  }

  if (window.addEventListener){
    window.addEventListener('message', wds_message_listener, false);
  }
  else {
    window.attachEvent('onmessage', wds_message_listener, false);
  }
  /* Mouswheel navigation.*/
  if ( wds_params[wds].mouse_wheel_nav) {
    jQuery('.wds_slide_container_'+wds).bind(mousewheelevt, function(e) {
      var evt = window.event || e; /* Equalize event object.*/
      evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
      var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
      if (delta > 0) {
        /* Scroll up.*/
        wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds(wds)) >= 0 ? (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds(wds)) % wds_params[wds].wds_data.length : wds_params[wds].wds_data.length - 1, wds_params[wds].wds_data, false, "left");
      }
      else {
        /* Scroll down.*/
        wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) + wds_iterator_wds(wds)) % wds_params[wds].wds_data.length, wds_params[wds].wds_data, false, "right");
      }
      return false;
    });
  }

  /* Keyboard navigation.*/
  if (wds_params[wds].keyboard_nav) {
    jQuery(document).on('keydown', function (e) {
      if (e.keyCode === 39 || e.keyCode === 38) { /* Right arrow.*/
        wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) + wds_iterator_wds(wds)) % wds_params[wds].wds_data.length, wds_params[wds].wds_data, false, "right");
      }
      else if (e.keyCode === 37 || e.keyCode === 40) { /* Left arrow.*/
        wds_change_image(wds, parseInt(jQuery('#wds_current_image_key_'+wds).val()), (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds(wds)) >= 0 ? (parseInt(jQuery('#wds_current_image_key_'+wds).val()) - wds_iterator_wds(wds)) % wds_params[wds].wds_data.length : wds_params[wds].wds_data.length - 1, wds_params[wds].wds_data, false, "left");
      }
      else if (e.keyCode === 32) { /* Space.*/
        wds_play_pause(wds);
      }
    });
  }
  /* Play/pause.*/
  jQuery("#wds_slideshow_play_pause_"+wds).on(wds_click, function () {
    wds_play_pause(wds);
  });
  if (wds_params[wds].enable_slideshow_autoplay) {
    play_wds(wds);

    jQuery(".wds_slideshow_play_pause_"+wds).attr("title", wds_object.pause);
    jQuery(".wds_slideshow_play_pause_"+wds).attr("class", "wds_ctrl_btn_"+wds+" wds_slideshow_play_pause_"+wds+" fa fa-pause");
    if (wds_params[wds].enable_slideshow_music) {
      if (wds_params[wds].slideshow_music_url != '') {
        document.getElementById("wds_audio_"+wds).play();
      }
    }
    if (wds_params[wds].timer_bar_type != 'none') {
      if (wds_params[wds].timer_bar_type != 'top') {
        if (wds_params[wds].timer_bar_type != 'bottom') {
          wds_circle_timer(wds, 0);
        }
      }
    }
  }

  if (wds_params[wds].preload_images) { 
    function wds_preload(wds, preload_key) {
      if (wds_params[wds].wds_data[preload_key]["is_video"] == 'image'  && !wds_params[wds].wds_data[preload_key]["loaded"]) {
        jQuery('<img />')
            .on('load', function() {
              jQuery(this).remove();
              if (preload_key < wds_params[wds].wds_data.length - 1) wds_preload(wds, preload_key + 1);
            })
            .on('error', function() {
              jQuery(this).remove();
              if (preload_key < wds_params[wds].wds_data.length - 1) wds_preload(wds, preload_key + 1);
            })
            .attr("src", wds_params[wds].wds_data[preload_key]["image_url"]);
        wds_params[wds].wds_data[preload_key]["loaded"] = true;
      }
      else {
        if (preload_key < wds_params[wds].wds_data.length - 1) wds_preload(wds, preload_key + 1);
      }
    }
    wds_preload(wds, 0);
  }
  var first_slide_layers_count_wds = wds_params[wds].wds_data[wds_params[wds].start_slide_num]["slide_layers_count"];
  if (first_slide_layers_count_wds) {
    /* Loop through layers in.*/
    for (var j = 0; j < first_slide_layers_count_wds; j++) {
      wds_set_layer_effect_in_wds(wds, j, wds_params[wds].start_slide_num);
    }
    /* Loop through layers out.*/
    for (var i = 0; i < first_slide_layers_count_wds; i++) {
      wds_set_layer_effect_out_wds(wds, i, wds_params[wds].start_slide_num);
    }
  }

  if ( !wds_object.is_free ) {
    wds_video_dimenstion(wds, jQuery("#wds_current_image_key_"+wds).val());
  }
  if (wds_params[wds].fixed_bg == 1) {
    wds_window_fixed_pos(wds);
  }
  jQuery(".wds_slideshow_filmstrip_container_"+wds).hover(function() {
    jQuery(".wds_slideshow_filmstrip_left_"+wds+" i, .wds_slideshow_filmstrip_right_"+wds+" i").animate({opacity: 1, filter: "Alpha(opacity=100)"}, 700, "swing");
  }, function () {
    jQuery(".wds_slideshow_filmstrip_left_"+wds+" i, .wds_slideshow_filmstrip_right_"+wds+" i").animate({opacity: 0, filter: "Alpha(opacity=0)"}, 700, "swing");
  });
  jQuery("#wds_container1_"+wds).hover(function() {
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderHover' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderHover );
        wds_callback_f();
      }
    });
  }, function () {
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderBlur' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderBlur );
        jQuery('#top_right_' + wds).css({
          '-moz-transform': 'rotate(0deg)',
          '-webkit-transform': 'rotate(0deg)',
          '-o-transform': 'rotate(0deg)',
          '-ms-transform': 'rotate(0deg)',
          'transform': 'rotate(0deg)',
          '-webkit-transform-origin': 'left bottom',
          '-ms-transform-origin': 'left bottom',
          '-moz-transform-origin': 'left bottom',
          'transform-origin': 'left bottom'
        });
        jQuery('#bottom_right_' + wds).css({
          '-moz-transform': 'rotate(0deg)',
          '-webkit-transform': 'rotate(0deg)',
          '-o-transform': 'rotate(0deg)',
          '-ms-transform': 'rotate(0deg)',
          'transform': 'rotate(0deg)',
          '-webkit-transform-origin': 'left top',
          '-ms-transform-origin': 'left top',
          '-moz-transform-origin': 'left top',
          'transform-origin': 'left top'
        });
        jQuery('#bottom_left_' + wds).css({
          '-moz-transform': 'rotate(0deg)',
          '-webkit-transform': 'rotate(0deg)',
          '-o-transform': 'rotate(0deg)',
          '-ms-transform': 'rotate(0deg)',
          'transform': 'rotate(0deg)',
          '-webkit-transform-origin': 'right top',
          '-ms-transform-origin': 'right top',
          '-moz-transform-origin': 'right top',
          'transform-origin': 'right top'
        });
        jQuery('#top_left_' + wds).css({
          '-moz-transform': 'rotate(0deg)',
          '-webkit-transform': 'rotate(0deg)',
          '-o-transform': 'rotate(0deg)',
          '-ms-transform': 'rotate(0deg)',
          'transform': 'rotate(0deg)',
          '-webkit-transform-origin': 'right bottom',
          '-ms-transform-origin': 'right bottom',
          '-moz-transform-origin': 'right bottom',
          'transform-origin': 'right bottom'
        });
        wds_callback_f();
      }
    });
  });
  jQuery("#wds_slideshow_play_pause_"+wds).on("click", ".fa-play", function() {
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderPlay' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderPlay );
        wds_callback_f();
      }
    });
  });
  jQuery("#wds_slideshow_play_pause_"+wds).on("click", ".fa-pause", function() {
    jQuery.each( wds_params[wds].callback_items, function( index, value ) {
      if ( index === 'onSliderPause' && value !== '' ) {
        var wds_callback_f = new Function( wds_params[wds].callback_items.onSliderPause );
        wds_callback_f();
      }
    });

  });
}

function wds_pause(wds) {
  /* Pause.*/
  /* Pause layers out effect.*/
  wds_params[wds].wds_play_pause_state = 1;
  var current_key = jQuery('#wds_current_image_key_'+wds).val();
  var current_slide_layers_count = wds_params[wds].wds_data[current_key]["slide_layers_count"];
  setTimeout(function() {
    for (var k = 0; k < current_slide_layers_count; k++) {
      clearTimeout(wds_params[wds].wds_clear_layers_effects_out[current_key][k]);
    }
  }, wds_params[wds].wds_duration_for_clear_effects);
  window.clearInterval(wds_params[wds].wds_playInterval);
  jQuery(".wds_slideshow_play_pause_"+wds).attr("title", wds_object.play);
  jQuery(".wds_slideshow_play_pause_"+wds).attr("class", "wds_ctrl_btn_"+wds+" wds_slideshow_play_pause_"+wds+" fa fa-play");
  if (wds_params[wds].enable_slideshow_music) {
    document.getElementById("wds_audio_"+wds).pause();
  }
  if (typeof jQuery().stop !== 'undefined') {
    if (jQuery.isFunction(jQuery().stop)) {
      if (wds_params[wds].timer_bar_type == 'top' || wds_params[wds].timer_bar_type == 'bottom') {
        jQuery(".wds_line_timer_" + wds).stop();
      }
      else if (wds_params[wds].timer_bar_type != 'none') {
        /* Pause circle timer.*/
        if (typeof wds_params[wds].circle_timer_animate.stop !== 'undefined') {
          wds_params[wds].circle_timer_animate.stop();
          if ( wds_params[wds].carousel == 1 ) {
              wds_carousel[wds].pause();
          }
          jQuery('#top_right_' + wds).css({
            '-moz-transform': 'rotate(0deg)',
            '-webkit-transform': 'rotate(0deg)',
            '-o-transform': 'rotate(0deg)',
            '-ms-transform': 'rotate(0deg)',
            'transform': 'rotate(0deg)',
            '-webkit-transform-origin': 'left bottom',
            '-ms-transform-origin': 'left bottom',
            '-moz-transform-origin': 'left bottom',
            'transform-origin': 'left bottom'
          });
          jQuery('#bottom_right_' + wds).css({
            '-moz-transform': 'rotate(0deg)',
            '-webkit-transform': 'rotate(0deg)',
            '-o-transform': 'rotate(0deg)',
            '-ms-transform': 'rotate(0deg)',
            'transform': 'rotate(0deg)',
            '-webkit-transform-origin': 'left top',
            '-ms-transform-origin': 'left top',
            '-moz-transform-origin': 'left top',
            'transform-origin': 'left top'
          });
          jQuery('#bottom_left_' + wds).css({
            '-moz-transform': 'rotate(0deg)',
            '-webkit-transform': 'rotate(0deg)',
            '-o-transform': 'rotate(0deg)',
            '-ms-transform': 'rotate(0deg)',
            'transform': 'rotate(0deg)',
            '-webkit-transform-origin': 'right top',
            '-ms-transform-origin': 'right top',
            '-moz-transform-origin': 'right top',
            'transform-origin': 'right top'
          });
          jQuery('#top_left_' + wds).css({
            '-moz-transform': 'rotate(0deg)',
            '-webkit-transform': 'rotate(0deg)',
            '-o-transform': 'rotate(0deg)',
            '-ms-transform': 'rotate(0deg)',
            'transform': 'rotate(0deg)',
            '-webkit-transform-origin': 'right bottom',
            '-ms-transform-origin': 'right bottom',
            '-moz-transform-origin': 'right bottom',
            'transform-origin': 'right bottom'
          });
        }
      }
    }
  }
  if ( wds_params[wds].carousel == 1 ) {
    wds_carousel[wds].pause();
  }
}

function wds_play_pause(wds, play_pause) {
  if (typeof play_pause == "undefined") {
    var play_pause = "";
  }
  if (play_pause == "") {
    if (jQuery(".wds_ctrl_btn_"+wds).hasClass("fa-play") || wds_params[wds].wds_play_pause_state) {
      wds_play_wds( wds );
    }
  else {
      wds_pause( wds );
    }
  }
  else if (play_pause == "play") {
    wds_play_wds( wds );
  }
  else if (play_pause == "pause") {
    wds_pause( wds );
  }
}

function wds_stop_animation( wds ) {
  window.clearInterval(wds_params[wds].wds_playInterval);
  /* Pause layers out effect.*/
  var current_key = jQuery('#wds_current_image_key_'+wds).val();
  var current_slide_layers_count = wds_params[wds].wds_data[current_key]["slide_layers_count"];

  setTimeout(function() {
    for (var k = 0; k < current_slide_layers_count; k++) {
      clearTimeout(wds_params[wds].wds_clear_layers_effects_out[current_key][k]);
    }
  }, wds_params[wds].wds_duration_for_clear_effects);
  if (wds_params[wds].enable_slideshow_music) {
    document.getElementById("wds_audio_"+wds).pause();
  }
  if (typeof jQuery().stop !== 'undefined') {
    if (jQuery.isFunction(jQuery().stop)) {
      if (wds_params[wds].timer_bar_type == 'top' || wds_params[wds].timer_bar_type == 'bottom') {
        jQuery(".wds_line_timer_"+wds).stop();
        if ( wds_params[wds].carousel == 1 ) {
            wds_carousel[wds].pause();
        }
      }
      else if (wds_params[wds].timer_bar_type != 'none') {
        wds_params[wds].circle_timer_animate.stop();
        if ( wds_params[wds].carousel == 1 ) {
            wds_carousel[wds].pause();
        }
      }
    }
  }
}

function wds_play_animation( wds ) {
  if (jQuery(".wds_ctrl_btn_"+wds).hasClass("fa-play")) {
    return;
  }
  play_wds( wds );
  if ( wds_params[wds].carousel == 1 ) {
      wds_carousel[wds].start();
  }
  if (wds_params[wds].timer_bar_type != 'none') {
    if (wds_params[wds].timer_bar_type != 'bottom') {
      if (wds_params[wds].timer_bar_type != 'top') {
        if (typeof wds_params[wds].circle_timer_animate !== 'undefined') {
          wds_params[wds].circle_timer_animate.stop();
          if ( wds_params[wds].carousel == 1 ) {
              wds_carousel[wds].pause();
          }
        }
          wds_circle_timer(wds_params[wds].curent_time_deggree);
      }
    }
  }
  if (wds_params[wds].enable_slideshow_music) {
    if (wds_params[wds].slideshow_music_url != '') {
      document.getElementById("wds_audio_"+wds).play();
    }
  }
  var next_slide_layers_count = wds_params[wds].wds_data[wds_params[wds].wds_current_key]["slide_layers_count"];
  for (var i = 0; i < next_slide_layers_count; i++) {
    wds_set_layer_effect_out_wds(wds, i, wds_params[wds].wds_current_key);
  }
}

function wds_get_overall_parent(obj) {
  if (obj.parent().width()) {
    obj.width(obj.parent().width());
    return obj.parent().width();
  }
  else {
    return wds_get_overall_parent(obj.parent());
  }
}

function wds_circle_timer(wds, angle) {
  wds_params[wds].circle_timer_animate = jQuery({deg: angle}).animate({deg: 360}, {
    duration: wds_params[wds].slideshow_interval * 1000,
    step: function (now) {
      wds_params[wds].curent_time_deggreewds = now;
      if (now >= 0) {
        if (now < 271) {
          jQuery('#top_right_' + wds).css({
            '-moz-transform': 'rotate(' + now + 'deg)',
            '-webkit-transform': 'rotate(' + now + 'deg)',
            '-o-transform': 'rotate(' + now + 'deg)',
            '-ms-transform': 'rotate(' + now + 'deg)',
            'transform': 'rotate(' + now + 'deg)',
            '-webkit-transform-origin': 'left bottom',
            '-ms-transform-origin': 'left bottom',
            '-moz-transform-origin': 'left bottom',
            'transform-origin': 'left bottom'
          });
        }
      }
      if (now >= 90) {
        if (now < 271) {
          wds_params[wds].bottom_right_deggree_wds = now - 90;
          jQuery('#bottom_right_' + wds).css({
            '-moz-transform': 'rotate(' + wds_params[wds].bottom_right_deggree_wds + 'deg)',
            '-webkit-transform': 'rotate(' + wds_params[wds].bottom_right_deggree_wds + 'deg)',
            '-o-transform': 'rotate(' + wds_params[wds].bottom_right_deggree_wds + 'deg)',
            '-ms-transform': 'rotate(' + wds_params[wds].bottom_right_deggree_wds + 'deg)',
            'transform': 'rotate(' + wds_params[wds].bottom_right_deggree_wds + 'deg)',
            '-webkit-transform-origin': 'left top',
            '-ms-transform-origin': 'left top',
            '-moz-transform-origin': 'left top',
            'transform-origin': 'left top'
          });
        }
      }
      if (now >= 180) {
        if (now < 361) {
          wds_params[wds].bottom_left_deggree_wds = now - 180;
          jQuery('#bottom_left_' + wds).css({
            '-moz-transform': 'rotate(' + wds_params[wds].bottom_left_deggree_wds + 'deg)',
            '-webkit-transform': 'rotate(' + wds_params[wds].bottom_left_deggree_wds + 'deg)',
            '-o-transform': 'rotate(' + wds_params[wds].bottom_left_deggree_wds + 'deg)',
            '-ms-transform': 'rotate(' + wds_params[wds].bottom_left_deggree_wds + 'deg)',
            'transform': 'rotate(' + wds_params[wds].bottom_left_deggree_wds + 'deg)',
            '-webkit-transform-origin': 'right top',
            '-ms-transform-origin': 'right top',
            '-moz-transform-origin': 'right top',
            'transform-origin': 'right top'
          });
        }
      }
      if (now >= 270) {
        if (now < 361) {
          wds_params[wds].top_left_deggree_wds = now - 270;
          jQuery('#top_left_' + wds).css({
            '-moz-transform': 'rotate(' + wds_params[wds].top_left_deggree_wds + 'deg)',
            '-webkit-transform': 'rotate(' + wds_params[wds].top_left_deggree_wds + 'deg)',
            '-o-transform': 'rotate(' + wds_params[wds].top_left_deggree_wds + 'deg)',
            '-ms-transform': 'rotate(' + wds_params[wds].top_left_deggree_wds + 'deg)',
            'transform': 'rotate(' + wds_params[wds].top_left_deggree_wds + 'deg)',
            '-webkit-transform-origin': 'right bottom',
            '-ms-transform-origin': 'right bottom',
            '-moz-transform-origin': 'right bottom',
            'transform-origin': 'right bottom'
          });
        }
      }
    }
  });
}

function wds_slide_redirect_link(event, url, target) {
	if ( event.target.className != 'wds_play_btn_cont' && event.target.className != 'wds_bigplay_layer' && event.target.className != '') {
		window.open(url,target);
	}
}

function wds_playVideo( wds_player ) {}
function wds_parallax(wds, slide_id) {}
function wds_embed_slide_autoplay(slide_id, wds) {}
function wds_video_dimenstion(wds, current_key) {}
function wds_upvideo_layer_dimenstion(wds, key, j) {}
function wds_video_play_pause(wds, id) {}
function wds_video_play_pause_layer(event, wds, slide_id, layer_id) {}