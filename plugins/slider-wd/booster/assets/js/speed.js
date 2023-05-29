jQuery(function () {
  jQuery(".twb-analyze-input-button").on("click", function () {
    if ( !jQuery(this).hasClass("twb-disable-analyze") ) {
      twb_get_google_score(this, '', '');
    }
  });

  jQuery(document).on('change', '.twb-analyze-input', function () {
    analize_input_change();
  });

  /* If there is no score of home page run google score and get homepage score */
  if ( twb.home_speed_status === '0' ) {
    twb_get_google_score('', twb.home_url, '');
  }
  else {
    /* Draw score circle if it is Speed Optimization page */
    if( jQuery(".twb-analyze-desktop-score .speed_circle").length > 0 ) {
      if (typeof twb.home_speed_status.desktop_score != "undefined") {
        draw_score_circle(twb.home_speed_status.desktop_score, twb.home_speed_status.mobile_score);
      }
    }
  }

  get_total_size_of_images();
  if ( twb.compressed_pages_status === "0" ) {
    set_compressed_pages();
  }

  /* Bind an action to enable/disable CTAs.*/
  jQuery("#twb-show-cta").on("click", function () {
    if ( jQuery(this).attr("disabled") ) {
      return;
    } else {
      jQuery(this).attr("disabled", true);
    }
    var show_cta = 0;
    if ( jQuery(this).prop("checked") ){
      show_cta = 1;
    }
    jQuery.ajax( {
      url: ajaxurl,
      type: "POST",
      data: {
        action: "twb",
        task: "set_show_cta",
        show_cta: show_cta,
        speed_ajax_nonce: twb.speed_ajax_nonce
      },
      complete: function () {
        jQuery("#twb-show-cta").removeAttr("disabled");
      },
    });
  })
});

/* Count total size of images. */
function set_compressed_pages() {
  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    data: {
      action: "twb",
      task: "set_compressed_pages",
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function ( result ) {
    },
  });
}

/* Count total size of images. */
function get_total_size_of_images() {
  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    data: {
      action: "twb",
      task: "get_total_size_of_images",
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function ( result ) {
      if( isValidJSONString(result) ) {
        var data = JSON.parse(result);
        jQuery(".twb-total_size_value").text(data.size);
      }
    },
  });
}

function analize_input_change() {
  var twb_analyze_input = jQuery(".twb-analyze-input");
  twb_analyze_input.removeClass("twb-analyze-input-error");
  jQuery(".twb-analyze-input-button").removeClass("twb-disable-analyze");
  jQuery(".twb-analyze-input-container .twb-error-msg").remove();
  var domain = twb.home_url.replace(/^https?:\/\/|www./g, '');
  var url = twb_analyze_input.val();
  var page_public = twb_analyze_input.data('page-public');

  var error = false;
  var error_msg = '';
  if( url == '' ) {
    error = true;
    error_msg = twb.enter_page_url;
  }
  else if ( !isUrlValid(url) ) {
    error = true;
    error_msg = twb.wrong_url;
  }
  else if ( !url.includes(domain) ) {
    error = true;
    error_msg = twb.wrong_domain_url;
  }
  else if ( page_public === 0 ) {
    error = true;
    error_msg = twb.page_is_not_public;
  }


  if ( error === true ) {
    jQuery(".twb-analyze-input-button").addClass("twb-disable-analyze");
    jQuery(".twb-analyze-input").addClass("twb-analyze-input-error");
    jQuery(".twb-analyze-input").after('<p class="twb-error-msg">' + error_msg + '</p>');
  }

}

/**
 * Install/activate the plugin.
 *
 * @param that object
*/
function twb_install_plugin( that ) {
  if ( jQuery(that).hasClass("twb-disable-link") ) {
    return false;
  }
  jQuery(that).addClass('twb-disable-link');
  jQuery(that).html('<div class="speed-loader-blue"></div>');
  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    dataType: "text",
    data: {
      action: "twb",
      task: "install_booster",
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function ( data ) {
      jQuery(".twb-speed-header").html(data).find(".wrap").remove();
      jQuery(".twb-speed-footer").remove();
    }
  });
}

/**
 * Run ajax action and Sign Up to dashboard
 *
 * @param that object
 */
function twb_sign_up_dashboard( that ) {
  if ( jQuery(that).hasClass("twb-disable-link") ) {
    return false;
  }

  var email_input = jQuery(that).parent().parent().find(".twb-sign-up-input");
  var parent_slug = jQuery(that).data("parent_slug");
  var slug = jQuery(that).data("slug");
  var is_plugin = jQuery(that).data("is_plugin");

  jQuery(".twb-error-msg").remove();
  email_input.removeClass("twb-input-error");
  jQuery(that).addClass('twb-disable-link');
  jQuery(that).html('<div class="speed-loader-blue"></div>');

  var email = email_input.val();
  if (email === '') {
    email_input.after('<p class="twb-error-msg">' + twb.empty_email + '</p>');
    email_input.addClass("twb-input-error");
    jQuery(that).text(twb.sign_up);
    jQuery(that).removeClass('twb-disable-link');
    return;
  }
  if (!isEmail(email)) {
    email_input.after('<p class="twb-error-msg">' + twb.wrong_email + '</p>');
    email_input.addClass("twb-input-error");
    jQuery(that).text(twb.sign_up);
    jQuery(that).removeClass('twb-disable-link');
    return;
  }
  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    dataType: "text",
    data: {
      action: "twb",
      task: "sign_up_dashboard",
      twb_email: email,
      parent_slug: parent_slug,
      slug: slug,
      is_plugin: is_plugin,
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function (result) {
      if ( !isValidJSONString(result) ) {
        jQuery(that).text(twb.sign_up);
        jQuery(that).removeClass('twb-disable-link');
        email_input.after('<p class="twb-error-msg">' + twb.something_wrong + '</p>');
        return;
      }
      var data = JSON.parse(result);
      if ( data['status'] === 'success' ) {
        window.location.href = data['booster_connect_url'];
      }
      else {
        jQuery(that).text(twb.sign_up);
        jQuery(that).removeClass('twb-disable-link');
        email_input.after('<p class="twb-error-msg">' + twb.something_wrong + '</p>');
        return;
      }
    },
    error: function (xhr) {
      jQuery(that).text(twb.sign_up);
      jQuery(that).removeClass('twb-disable-link');
      email_input.after('<p class="twb-error-msg">' + twb.something_wrong + '</p>');
    }
  });
}

function twb_connect_to_dashboard( that ) {
  if ( jQuery(that).hasClass("twb-disable-link") ) {
    return false;
  }

  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    data: {
      action: "twb",
      task: "connect_to_dashboard",
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function ( result ) {
      if ( !isValidJSONString(result) ) {
        jQuery(that).text(twb.connect);
        jQuery(that).removeClass('twb-disable-link');
        return;
      }
      var data = JSON.parse(result);
      if ( data['status'] === 'success' ) {
        window.location.href = data['booster_connect_url'];
      } else {
        jQuery(that).text(twb.connect);
        jQuery(that).removeClass('twb-disable-link');
        return;
      }
    },
    error: function ( xhr ) {
      jQuery(that).text(twb.connect);
      jQuery(that).removeClass('twb-disable-link');
    }
  });
}

/**
 * Drawing score circle in different colors
 *
 * @param desktop_score int score value of desktop
 * @param mobile_score int score value of desktop
*/
function draw_score_circle( desktop_score, mobile_score ) {
  var d = desktop_score;
  var m = mobile_score;
  var color_desktop = d <= 49 ? "rgb(253, 60, 49)" : (d >= 90 ? "rgb(12, 206, 107)" : "rgb(255, 164, 0)");
  var color_mobile = m <= 49 ? "rgb(253, 60, 49)" : (m >= 90 ? "rgb(12, 206, 107)" : "rgb(255, 164, 0)");
  var bg_color_desktop = d <= 49 ? "rgb(253, 60, 49, 0.1)" : (d >= 90 ? "rgb(12, 206, 107, 0.1)" : "rgb(255, 164, 0, 0.1)");
  var bg_color_mobile = m <= 49 ? "rgb(253, 60, 49, 0.1)" : (m >= 90 ? "rgb(12, 206, 107, 0.1)" : "rgb(255, 164, 0, 0.1)");

  jQuery('.speed_circle').each(function () {
    var _this = this;
    var val = desktop_score / 100;
    var num = d;
    var color = color_desktop;
    var bg_color = bg_color_desktop;
    if ( jQuery(this).data("id") === "mobile" ) {
      val = mobile_score / 100;
      num = m;
      color = color_mobile;
      bg_color = bg_color_mobile;
    }
    jQuery(_this).circleProgress({
      value: val,
      size: 78,
      startAngle: -Math.PI / 4 * 2,
      lineCap: 'round',
      emptyFill: "rgba(255, 255, 255, 0)",
      fill: {
        color: color
      }
    }).on('circle-animation-progress', function ( event, progress ) {
      jQuery(this).find('.circle_animated').html(Math.round(parseFloat(num) * progress)).css({"color": color});
      jQuery(this).find('canvas').html(Math.round(parseFloat(num) * progress)).css({ "background": bg_color });
    });
  });
}

/**
 * Run ajax action and count google score.
 *
 * @param that object
 * @param url string
 * @param last_api_key_index int/empty last index of array where keeped google api keys
*/
function twb_get_google_score( that, url, last_api_key_index ) {
  jQuery(".twb-error-msg").remove();
  if (url === '') {
    if (jQuery(that).hasClass("twb-disable-link")) {
      return false;
    }
    jQuery(that).addClass('twb-disable-analyze');
    jQuery(that).html('<div class="speed-loader-grey"></div>');
    url = jQuery(".twb-analyze-input").val();
  }

  if (!isUrlValid(url)) {
    jQuery(".twb-analyze-input").after('<p class="twb-error-msg">' + twb.wrong_url + '</p>');
    jQuery(".twb-analyze-input-button").removeClass('twb-disable-analyze');
    jQuery(".twb-analyze-input-button").text(twb.analyze_button_text);
    return;
  }
  if ( jQuery(".speed_circle_loader").length === 0 ) {
    jQuery(".speed_circle").after("<div class='speed_circle_loader'></div>");
  }
  jQuery(".speed_circle").addClass("twb-hidden");
  jQuery(".twb-load-time-mobile span").text("-");
  jQuery(".twb-load-time-desktop span").text("-");
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "twb",
      task: "get_google_page_speed",
      last_api_key_index: last_api_key_index,
      twb_url: url,
      speed_ajax_nonce: twb.speed_ajax_nonce
    },
    success: function (result) {
      if( !isValidJSONString(result) ) {
        google_speed_error_result('');
        return;
      } else {
          var data = JSON.parse(result);
          if ( data['error'] === 1 ) {
            if ( typeof data['last_api_key_index'] !== 'undefined' ) {
              twb_get_google_score(that, twb.home_url, data['last_api_key_index'] );
              return;
            }
            var msg = '';
            if( typeof data['msg'] !== 'undefined') {
              msg = data['msg'];
            }
            google_speed_error_result(msg);
            return;
          }
      }

      jQuery(".speed_circle_loader").remove();
      jQuery(".speed_circle").removeClass("twb-hidden");
      draw_score_circle(data['desktop_score'], data['mobile_score']);
      jQuery(".twb-last-analyzed-page").text(url);
      jQuery(".twb-last-analyzed-date").text(data['last_analyzed_time']);
      jQuery(".twb-load-time-mobile span").text(data['mobile_loading_time']);
      jQuery(".twb-load-time-desktop span").text(data['desktop_loading_time']);
    },
    error: function (xhr) {
      google_speed_error_result('');
    },
    complete: function () {
      jQuery('.twb-analyze-input-button.twb-disable-analyze').removeClass('twb-disable-analyze');
      jQuery('.twb-analyze-input-button').text(twb.analyze_button_text);
    }
  });
}

/* Case when counting of score returns error. */
function google_speed_error_result( msg ) {
  if ( msg !== '' ) {
    twb.something_wrong = msg;
  }
  jQuery(".twb-analyze-input").after('<p class="twb-error-msg">' + twb.something_wrong + '</p>');
  jQuery('.twb-analyze-input-button.twb-disable-analyze').removeClass('twb-disable-analyze');
  jQuery('.twb-analyze-input-button').text(twb.analyze_button_text);
  jQuery(".speed_circle_loader").remove();
  jQuery(".speed_circle").removeClass("twb-hidden");
}

/**
 * Check if value is email
 *
 * @param email string
 *
 * @return bool
*/
function isEmail( email ) {
  var EmailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return EmailRegex.test(email);
}

/**
 * Check if value is URL
 *
 * @param url string
 *
 * @return bool
*/
function isUrlValid(url) {
  if (typeof url == 'undefined' || url == '') {
    return false;
  }
  if ( url.indexOf("http") !== 0 && url.indexOf("www.") !== 0) {
    return false;
  }
  regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
  if (regexp.test(url)) {
    return true;
  } else {
    return false;
  }
}

/**
 * Check if data is valid json
 *
 * @param str string
 *
 * @return bool
 */
function isValidJSONString(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

//var twb_leaving_popup = false;

//jQuery(".twb-speed-body").parents(".wrap").mouseleave(function () {
//  if (twb_leaving_popup == false && jQuery(".twb-sign-up-input").is(":visible")) {
//    jQuery(".twb-popup-overlay").removeClass("twb-hidden");
//  }
//});