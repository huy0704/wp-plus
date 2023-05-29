<?php

/**
 * 1. Replace 'su_vote' option with 'su_option_dismissed_notices'.
 */
if ( get_option( 'su_vote' ) ) {

	$dismissed_notices = get_option( 'su_option_dismissed_notices' );

	if ( ! is_array( $dismissed_notices ) ) {

		$dismissed_notices = array(
			'rate' => true,
		);

	}

	update_option( 'su_option_dismissed_notices', $dismissed_notices );

	delete_option( 'su_vote' );

}

/**
 * 2. Delete 'su_installed' option.
 */
delete_option( 'su_installed' );

/**
 * 3. Remove extra slashes from 'su_option_custom-css' option.
 */
$custom_css = get_option( 'su_option_custom-css' );

if ( ! empty( $custom_css ) ) {

	$custom_css = stripslashes( $custom_css );

	update_option( 'su_option_custom-css', $custom_css, true );

}
