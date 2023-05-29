<?php

su_add_shortcode(
	array(
		'id'       => 'dummy_image',
		'callback' => 'su_shortcode_dummy_image',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/dummy_image.svg',
		'name'     => __( 'Dummy image', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'content',
		'atts'     => array(
			'width'  => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1600,
				'step'    => 10,
				'default' => 500,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Image width', 'shortcodes-ultimate' ),
			),
			'height' => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1600,
				'step'    => 10,
				'default' => 300,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Image height', 'shortcodes-ultimate' ),
			),
			'theme'  => array(
				'type'    => 'select',
				'values'  => array(
					'any'       => __( 'Any', 'shortcodes-ultimate' ),
					'abstract'  => __( 'Abstract', 'shortcodes-ultimate' ),
					'animals'   => __( 'Animals', 'shortcodes-ultimate' ),
					'business'  => __( 'Business', 'shortcodes-ultimate' ),
					'cats'      => __( 'Cats', 'shortcodes-ultimate' ),
					'city'      => __( 'City', 'shortcodes-ultimate' ),
					'food'      => __( 'Food', 'shortcodes-ultimate' ),
					'nightlife' => __( 'Night life', 'shortcodes-ultimate' ),
					'fashion'   => __( 'Fashion', 'shortcodes-ultimate' ),
					'people'    => __( 'People', 'shortcodes-ultimate' ),
					'nature'    => __( 'Nature', 'shortcodes-ultimate' ),
					'sports'    => __( 'Sports', 'shortcodes-ultimate' ),
					'technics'  => __( 'Technics', 'shortcodes-ultimate' ),
					'transport' => __( 'Transport', 'shortcodes-ultimate' ),
				),
				'default' => 'any',
				'name'    => __( 'Theme', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select the theme for this image', 'shortcodes-ultimate' ),
			),
			'class'  => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Image placeholder with random image', 'shortcodes-ultimate' ),
		'icon'     => 'picture-o',
	)
);

function su_shortcode_dummy_image( $atts = null, $content = null ) {
	$atts = shortcode_atts(
		array(
			'width'  => 500,
			'height' => 300,
			'theme'  => 'any',
			'class'  => '',
		),
		$atts,
		'dummy_image'
	);
	$url  = 'http://lorempixel.com/' . $atts['width'] . '/' . $atts['height'] . '/';
	if ( $atts['theme'] !== 'any' ) {
		$url .= $atts['theme'] . '/' . rand( 0, 10 ) . '/';
	}
	return '<img src="' . esc_attr( $url ) . '" alt="' . __( 'Dummy image', 'shortcodes-ultimate' ) . '" width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" class="su-dummy-image' . su_get_css_class( $atts ) . '" />';
}
