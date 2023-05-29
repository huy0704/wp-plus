/*
* Copyright (C) 2017-present, Facebook, Inc.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; version 2 of the License.

* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

function fbmcc_setupCustomerChat() {
  const FACEBOOK_URL = "https://www.facebook.com";
  var baseURL = "https://www.facebook.com/customer_chat/dialog/?domain=";
  var urlParam = encodeURI(
    window.location.protocol
    + '//'
    + window.location.hostname
    + (window.location.port ? ':' + window.location.port : '')
  );
  var customerWindow = window.open(
    baseURL + urlParam,
    "_blank",
    "width=1200,height=800"
  );

  jQuery(window).on("message", function(e) {
    if (e.originalEvent.origin === FACEBOOK_URL) {
      $data_json = JSON.parse(e.originalEvent.data);
      var data = {
        'action' : 'fbmcc_update_options',
        'pageID' : fbmcc_sanitizeNumbersOnly($data_json["pageID"]),
        'locale' : fbmcc_sanitizeLocale($data_json["locale"]),
        '_wpnonce' : ajax_object.nonce,
      };
      jQuery.ajax({
        type: 'POST',
        url: ajax_object.ajaxurl,
        data: data,
        success: function(results) {
          jQuery('#fbmcc-page-params').css('display', 'inline-block');
        }
      });
    }
  });
}

function fbmcc_sanitizeNumbersOnly( number ) {
  if( /^\d+$/.test(number) ) {
    return number;
  } else {
    return '';
  }
}

function fbmcc_sanitizeLocale( locale ) {
  if( /^[A-Za-z_]{4,5}$/.test(locale) ) {
    return locale;
  } else {
    return '';
  }
}

(function($)
{
	var ChatPlugin =
	{
		init: function()
	  {
      this.advancedConfigDeploymentSelectorHandler();
      this.advancedConfigMenuHandler();
      this.advancedConfigWriteHandler();
      this.deactivationFormSubmit();
			this.deactivationModalOpenHandler();
      this.deactivationModalCloseHandler();
      this.deactivationModalFreetextOptionOpenHandler();

    },

		advancedConfigDeploymentSelectorHandler: function() {
			$('#fbmcc-deploymentSelector').change( function () {
        if ($(this).val() == 1) {
          $('div.fbmcc-deploymentMenu').addClass("hidden");
        } else {
          $('div.fbmcc-deploymentMenu').removeClass("hidden");
        }
			})
    },
		advancedConfigMenuHandler: function() {
			$('li .fbmcc-menuParentLink').click(
				function () {
          if ($(this).parent().find('ul.fbmcc-submenu').css("display") === "none") {
            $(this).parent().find('img.fbmcc-chevron').attr("src", $(this).parent().find('img.fbmcc-chevron').attr("src").replace("chevron-right", "chevron-down"));
            $(this).parent().find('ul.fbmcc-submenu').slideDown('slow', 'swing');
          } else {
            $(this).parent().find('img.fbmcc-chevron').attr("src", $(this).parent().find('img.fbmcc-chevron').attr("src").replace("chevron-down", "chevron-right"));
            $(this).parent().find('ul.fbmcc-submenu').slideUp('slow', 'swing');
          }
				}
			)
    },
		advancedConfigWriteHandler: function() {
			$('div#fbmcc-page-params ul li .fbmcc-displaySetting').on('change',
				function () {
          var pages = [];
          if ($(this).hasClass("fbmcc-menuParentItem")) {
            that = $(this);
            $(this).parent().find('ul.fbmcc-submenu').find('input:checkbox').each(function() {
              if (that.is(":checked")) {
                $(this).prop("checked", true);
              } else {
                $(this).prop("checked", false);
              }
            });
          } else if ($(this).hasClass("fbmcc-submenuOption")) {
            var has_selected_item = false;
            var has_unselected_item = false;

            $(this).parent().parent().find('input:checkbox.fbmcc-submenuOption').each(function() {
              if ($(this).is(":checked")) {
                has_selected_item = true;
                if ($(this).hasClass("fbmcc-activePageOption")) {
                  pages.push($(this).attr('id').replace('pageid_', ''));
                }
              } else {
                has_unselected_item = true;
              }
            });
            if (has_selected_item && has_unselected_item) {
              $(this).parent().parent().parent().find('input:checkbox.fbmcc-menuParentItem').prop({
                checked: false,
                indeterminate: true
              });
            }
          }

          var data = {
            'action' : 'fbmcc_update_options',
            'pageTypes' : {
              all: $('#fbmcc-deploymentSelector').val() == 1 ? 1 : 0,
              category_index : $("#cbShowCategoryIndex").is(":checked") ? 1 : 0,
              front_page : $("#cbShowFrontPage").is(":checked") ? 1 : 0,
              pages : $("#cbShowPages").is(":checked") ? [] : pages,
              pages_all : $("#cbShowPages").is(":checked") ? 1 : 0,
              posts : $("#cbShowSinglePostView").is(":checked") ? 1 : 0,
              product_pages : $("#cbShowProductPages").is(":checked") ? 1 : 0,
              tag_index : $("#cbShowTagsIndex").is(":checked") ? 1 : 0,
            },
            '_wpnonce' : ajax_object.nonce,
          };
          if (!$('div.fbmcc-deploymentMenu').hasClass("hidden")) {
            $('#fbmcc-saveStatus-error').addClass('hidden');
            $('#fbmcc-saveStatus-saved').addClass('hidden');
            $('#fbmcc-saveStatus-saving').removeClass('hidden');
            $('#fbmcc-saveStatus-saving').delay(2000).fadeOut();
          }
          jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajaxurl,
            data: data,
            error: function(results) {
              if (!$('div.fbmcc-deploymentMenu').hasClass("hidden")) {
                $('#fbmcc-saveStatus-error').removeClass('hidden');
                $('#fbmcc-saveStatus-saved').addClass('hidden');
                $('#fbmcc-saveStatus-saving').addClass('hidden');
                $('#fbmcc-saveStatus-error').delay(2000).fadeOut();
              }
            },
            success: function(results) {
              if (!$('div.fbmcc-deploymentMenu').hasClass("hidden")) {
                $('#fbmcc-saveStatus-error').addClass('hidden');
                $('#fbmcc-saveStatus-saved').removeClass('hidden');
                $('#fbmcc-saveStatus-saving').addClass('hidden');
                $('#fbmcc-saveStatus-saved').delay(2000).fadeOut();
              }
            }
          });
				}
			)
    },
    deactivationFormSubmit: function() {
			$( '#fbmcc-deactivationFormSubmit' ).click(
        function () {
          $('#fbmcc-deactivationFormSubmit').attr('disabled','disabled');
          $.ajax(
            {
              method: 'POST',
              url: 'https://www.facebook.com/plugins/chat/wordpress_deactivation/',
              data: $.param(
                {
                  page_id: $('#fbmcc-deactivationForm-pageId').val(),
                  reason: $('#fbmcc-deactivationReason').val(),
                  selected_option: $('input[name=fbmcc-deactivationReason]:checked', '#fbmcc-deactivationForm').val()
                }
              ),
              complete: function () {
                $('#fbmcc-deactivationFormContainer').addClass('hidden');
                $('#fbmcc-deactivationModal-thankYou').removeClass('hidden');
              },
              error: function () {
                $('#fbmcc-deactivationFormContainer').addClass('hidden');
                $('#fbmcc-deactivationModal-thankYou').removeClass('hidden');
              }
            }
          );
				}
			)
    },
		deactivationModalOpenHandler: function() {
			$('table.plugins tr[data-slug=facebook-messenger-customer-chat] span.deactivate a').click(
				function (e) {
          e.preventDefault();
					$( '#fbmcc-deactivationModalOverlay' ).toggleClass( 'fbmcc-deactivationModalOverlay-display' );
				}
			)
    },
    deactivationModalCloseHandler: function() {
			$('#fbmcc-deactivationModalOverlay').click(
				function (e) {
					if (
            $('#fbmcc-deactivationModalOverlay').hasClass( 'fbmcc-deactivationModalOverlay-display' ) &&
					(
					! $( e.target ).closest( '#fbmcc-deactivationModalContainer' ).length ||
					$( e.target ).closest( '.fbmcc-deactivationModal-closeButton' ).length
					)
					) {
						$( '#fbmcc-deactivationModalOverlay' ).toggleClass( 'fbmcc-deactivationModalOverlay-display' );
            return window.location.replace(
              $( 'table.plugins tr[data-slug=facebook-messenger-customer-chat] span.deactivate a' ).attr( 'href' )
            );
					}
				}
			);
    },
		deactivationModalFreetextOptionOpenHandler: function() {
			$("#fbmcc-deactivationModal ul li input").click(
				function () {
          $('div.fbmcc-deactivationReason-commentContainer').addClass( 'fbmcc-display' );
				}
			)
    },
	};

	$( document ).ready(
		function() {
      ChatPlugin.init();
      $('ul.fbmcc-submenu').each(function() {
        var has_selected_item = false;
        var has_unselected_item = false;

        $(this).find('input:checkbox').each(function() {
          if ($(this).is(":checked")) {
            has_selected_item = true;
          } else {
            has_unselected_item = true;
          }
        });
        if (has_selected_item && has_unselected_item) {
          $(this).parent().find('input:checkbox.fbmcc-menuParentItem').prop({
            checked: false,
            indeterminate: true
          });
        }
      })
    });
})( jQuery );
