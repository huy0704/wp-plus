<?php

su_add_shortcode(
	array(
		'id'         => 'media',
		'callback'   => 'su_shortcode_media',
		'deprecated' => true,
		'hidden'     => true,
	)
);

function su_shortcode_media( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'    => '',
			'width'  => 600,
			'height' => 400,
			'class'  => '',
		),
		$atts,
		'media'
	);

	if ( strpos( $atts['url'], 'youtu' ) !== false ) {
		return su_shortcode_youtube( $atts, $content );
	} elseif ( strpos( $atts['url'], 'vimeo' ) !== false ) {
		return su_shortcode_vimeo( $atts, $content );
	} else {
		return '<img src="' . esc_attr( $atts['url'] ) . '" width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" style="max-width:100%" />';
	}

}
