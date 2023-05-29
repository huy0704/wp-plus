<?php

/**
 * 1. Add autoload to plugin options
 */

$options = array_keys( su_get_config( 'default-settings' ) );

foreach ( $options as $option ) {

	if ( get_option( $option, 0 ) === 0 ) {
		continue;
	}

	$value = get_option( $option );

	delete_option( $option );
	add_option( $option, $value );

}
