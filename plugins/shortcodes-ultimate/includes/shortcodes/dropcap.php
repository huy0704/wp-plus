<?php

su_add_shortcode(
	array(
		'id'       => 'dropcap',
		'callback' => 'su_shortcode_dropcap',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/dropcap.svg',
		'name'     => __( 'Dropcap', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'content',
		'atts'     => array(
			'style' => array(
				'type'    => 'select',
				'values'  => array(
					'default' => __( 'Default', 'shortcodes-ultimate' ),
					'flat'    => __( 'Flat', 'shortcodes-ultimate' ),
					'light'   => __( 'Light', 'shortcodes-ultimate' ),
					'simple'  => __( 'Simple', 'shortcodes-ultimate' ),
				),
				'default' => 'default',
				'name'    => __( 'Style', 'shortcodes-ultimate' ),
				'desc'    => __( 'Dropcap style preset', 'shortcodes-ultimate' ),
			),
			'size'  => array(
				'type'    => 'slider',
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'default' => 3,
				'name'    => __( 'Size', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose dropcap size', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'D', 'shortcodes-ultimate' ),
		'desc'     => __( 'Dropcap', 'shortcodes-ultimate' ),
		'icon'     => 'bold',
	)
);

function su_shortcode_dropcap( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'style' => 'default',
			'size'  => 3,
			'class' => '',
		),
		$atts,
		'dropcap'
	);

	$atts['style'] = str_replace( array( '1', '2', '3' ), array( 'default', 'light', 'default' ), $atts['style'] ); // 3.x

	$em = intval( $atts['size'] ) * 0.5 . 'em';

	su_query_asset( 'css', 'su-shortcodes' );

	return '<span class="su-dropcap su-dropcap-style-' . esc_attr( $atts['style'] ) . su_get_css_class( $atts ) . '" style="font-size:' . $em . '">' . do_shortcode( $content ) . '</span>';

}
