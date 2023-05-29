<?php

/**
 * Helper function to check validity of a given HEX color.
 *
 * Valid formats are:
 * - #aabbcc
 * - aabbcc
 * - #abc
 * - abc
 *
 * @since  5.2.0
 * @param  string $color HEX color to check validity of.
 * @return bool          True if a given color mathes accepted pattern, False otherwise.
 */
function su_is_valid_hex( $color ) {
	return preg_match( '/^#([a-f0-9]{3}){1,2}\b$/i', $color ) === 1;
}

/**
 * Helper function that expands 3-sybol string into 6-sybol by repeating each
 * symbol twice.
 *
 * @since  5.6.0
 * @param  string $hex Short value.
 * @return string      Expanded value.
 */
function su_expand_short_color( $value ) {

	if ( ! is_string( $value ) || 3 !== strlen( $value ) ) {
		return $value;
	}

	return $value[0] . $value[0] . $value[1] . $value[1] . $value[2] . $value[2];

}

/**
 * Helper function that adjusts brightness of a given HEX color value.
 *
 * Examples of use:
 * `su_adjust_brightness( '#fc0', 50 )` - increase color brightness by 50%
 * `su_adjust_brightness( 'ffcc00', -50 )` - decrease color brightness by 50%
 *
 * @since  5.2.0
 * @param  string $color A valid HEX color
 * @param  int $percent  The percent to adjust brightness to.
 * @return string        Adjusted HEX color value.
 */
function su_adjust_brightness( $color, $percent ) {

	if (
		! su_is_valid_hex( $color ) ||
		! is_numeric( $percent )
	) {
		return $color;
	}

	$percent = max( -100, min( 100, $percent ) );
	$steps   = round( $percent * 2.55 );
	$color   = ltrim( $color, '#' );

	if ( 3 === strlen( $color ) ) {
		$color = su_expand_short_color( $color );
	}

	$color_parts = str_split( $color, 2 );
	$new_color   = '#';

	foreach ( $color_parts as $color_part ) {

		$color_part = hexdec( $color_part );
		$color_part = max( 0, min( 255, $color_part + $steps ) );

		$new_color .= str_pad( dechex( $color_part ), 2, '0', STR_PAD_LEFT );

	}

	return $new_color;

}

/**
 * Helper function that adjusts lightness of a given HEX color value.
 *
 * Examples of use:
 * `su_adjust_lightness( '#fc0', 50 )` - increase color lightness by 50%
 * `su_adjust_lightness( 'ffcc00', -50 )` - decrease color lightness by 50%
 *
 * @since  5.6.0
 * @param  string $color A valid HEX color
 * @param  int $percent  The percent to adjust lightness to.
 * @return string        Adjusted HEX color value.
 */
function su_adjust_lightness( $color, $percent ) {

	if (
		! su_is_valid_hex( $color ) ||
		! is_numeric( $percent )
	) {
		return $color;
	}

	$percent   = max( -100, min( 100, $percent ) );
	$color     = ltrim( $color, '#' );
	$new_color = '#';

	if ( 3 === strlen( $color ) ) {
		$color = su_expand_short_color( $color );
	}

	$color = array_map( 'hexdec', str_split( $color, 2 ) );

	foreach ( $color as $part ) {

		$limit  = $percent < 0 ? $part : 255 - $part;
		$amount = ceil( $limit * $percent / 100 );

		$new_color .= str_pad( dechex( $part + $amount ), 2, '0', STR_PAD_LEFT );

	}

	return $new_color;

}
