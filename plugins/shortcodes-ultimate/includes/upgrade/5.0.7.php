<?php

/**
 * 1. Rename `su_generator_access` option to `su_option_generator_access`.
 */
$su_generator_access_value = get_option( 'su_generator_access' );

if ( $su_generator_access_value ) {

	delete_option( 'su_generator_access' );

	add_option( 'su_option_generator_access', $su_generator_access_value, '', false );

}
