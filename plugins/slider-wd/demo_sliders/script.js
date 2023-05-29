jQuery(document).ready(function () {
	jQuery(document).keyup(function(e) {
      if ( e.keyCode == 27 && !jQuery('#wd_download_popup').hasClass('hidden') ) {
        wd_download_popup();
      }
    });
});

function wd_download_popup() {
    jQuery('#wd_download_popup').toggleClass('hidden');
}