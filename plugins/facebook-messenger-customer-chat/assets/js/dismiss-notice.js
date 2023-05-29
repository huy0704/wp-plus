/**
 * Fork of the dismissible notices library script. This version is identical, but
 * it does not use event.preventDefault(), so that links with the
 * `.dismiss-this` class can be followed while still dismissing the notice.
 */
(function($) {
	//shorthand for ready event.
	$(
		function () {
			$( 'div[data-dismissible] button.notice-dismiss, div[data-dismissible] .dismiss-this' ).on("click",
				function () {
					var $this = $( this );

					var attr_value, option_name, dismissible_length, data;

					attr_value = $this.closest("div[data-dismissible]").attr( 'data-dismissible' ).split( '-' );

					// remove the dismissible length from the attribute value and rejoin the array.
					dismissible_length = attr_value.pop();

					option_name = attr_value.join( '-' );

					data = {
						'action': 'dismiss_admin_notice',
						'option_name': option_name,
						'dismissible_length': dismissible_length,
						'nonce': dismissible_notice.nonce
					};

					// We can also pass the url value separately from ajaxurl for front end AJAX implementations
					$.post( ajaxurl, data );
					$this.closest("div[data-dismissible]").hide('slow');
				}
			);
		}
	)

}(jQuery));
