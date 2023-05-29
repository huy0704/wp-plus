<?php

su_add_shortcode(
	array(
		'deprecated' => true,
		'id'         => 'screenr',
		'callback'   => 'su_shortcode_screenr',
		'group'      => 'media',
		'atts'       => array(),
		'name'       => __( 'Screenr', 'shortcodes-ultimate' ),
		'type'       => 'single',
	)
);

function su_shortcode_screenr( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'        => false,
			'width'      => 600,
			'height'     => 400,
			'responsive' => 'yes',
			'class'      => '',
		),
		$atts,
		'screenr'
	);

	if ( ! $atts['url'] ) {
		return su_error_message( 'Screenr', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$atts['url'] = su_do_attribute( $atts['url'] );
	$id          = ( preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*screenr\.com(?:[\/\w]*\/videos?)?\/([a-zA-Z0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $atts['url'], $match ) ) ? $match[1] : false;

	if ( ! $id ) {
		return su_error_message( 'Screenr', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-screenr su-u-responsive-media-' . esc_attr( $atts['responsive'] ) . su_get_css_class( $atts ) . '"><iframe width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" src="http://screenr.com/embed/' . $id . '" frameborder="0" allowfullscreen="true"></iframe></div>';

}
