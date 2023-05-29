<?php

/**
 * 1. Replace `category_description` with `term_description`
 *    in the `su_option_enable_shortcodes_in` option
 */

$old_value = get_option( 'su_option_enable_shortcodes_in' );
$new_value = array();

if ( is_array( $old_value ) ) {

	foreach ( $old_value as $item ) {

		if ( 'category_description' === $item ) {
			$item = 'term_description';
		}

		$new_value[] = $item;

	}

}

update_option( 'su_option_enable_shortcodes_in', $new_value );
