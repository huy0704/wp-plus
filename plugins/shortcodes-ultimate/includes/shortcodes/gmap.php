<?php

su_add_shortcode(
	array(
		'id'       => 'gmap',
		'callback' => 'su_shortcode_gmap',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/gmap.svg',
		'name'     => __( 'Google map', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'media',
		'atts'     => array(
			'width'      => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Map width', 'shortcodes-ultimate' ),
			),
			'height'     => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 400,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Map height', 'shortcodes-ultimate' ),
			),
			'responsive' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Ignore width and height parameters and make map responsive', 'shortcodes-ultimate' ),
			),
			'address'    => array(
				'values'  => array(),
				'default' => '',
				'name'    => __( 'Marker', 'shortcodes-ultimate' ),
				'desc'    => __( 'Address for the marker. You can type it in any language', 'shortcodes-ultimate' ),
			),
			'zoom'       => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 21,
				'step'    => 1,
				'default' => 0,
				'name'    => __( 'Zoom', 'shortcodes-ultimate' ),
				'desc'    => __( 'Zoom sets the initial zoom level of the map. Accepted values range from 1 (the whole world) to 21 (individual buildings). Use 0 (zero) to set zoom level depending on displayed object (automatic)', 'shortcodes-ultimate' ),
			),
			'title'      => array(
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A brief description of the embedded content (used by screenreaders)', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Maps by Google', 'shortcodes-ultimate' ),
		'icon'     => 'globe',
	)
);

function su_shortcode_gmap( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'width'      => 600,
			'height'     => 400,
			'responsive' => 'yes',
			'address'    => 'Moscow',
			'zoom'       => 0,
			'title'      => '',
			'class'      => '',
		),
		$atts,
		'gmap'
	);

	$atts['zoom'] = is_numeric( $atts['zoom'] ) && $atts['zoom'] > 0
		? '&amp;z=' . $atts['zoom']
		: '';

	su_query_asset( 'css', 'su-shortcodes' );

	return sprintf(
		'<div class="su-gmap su-u-responsive-media-%s%s"><iframe width="%s" height="%s" src="//maps.google.com/maps?q=%s&amp;output=embed%s" title="%s"></iframe></div>',
		esc_attr( $atts['responsive'] ),
		su_get_css_class( $atts ),
		intval( $atts['width'] ),
		intval( $atts['height'] ),
		rawurlencode( su_do_attribute( $atts['address'] ) ),
		$atts['zoom'],
		esc_attr( $atts['title'] )
	);

}
