<?php

su_add_shortcode(
	array(
		'deprecated' => true,
		'id'         => 'frame',
		'callback'   => 'su_shortcode_frame',
		'name'       => __( 'Frame', 'shortcodes-ultimate' ),
		'type'       => 'wrap',
		'group'      => 'content',
		'atts'       => array(
			'align' => array(
				'type'    => 'select',
				'values'  => array(
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'left',
				'name'    => __( 'Align', 'shortcodes-ultimate' ),
				'desc'    => __( 'Frame alignment', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'default' => '',
				'name'    => __( 'Class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
			),
		),
		'content'    => '<img src="http://lorempixel.com/g/400/200/" />',
		'desc'       => __( 'Styled image frame', 'shortcodes-ultimate' ),
		'icon'       => 'picture-o',
	)
);

function su_shortcode_frame( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'style' => 'default',
			'align' => 'left',
			'class' => '',
		),
		$atts,
		'frame'
	);

	su_query_asset( 'css', 'su-shortcodes' );
	su_query_asset( 'js', 'su-shortcodes' );

	return '<span class="su-frame su-frame-align-' . esc_attr( $atts['align'] ) . ' su-frame-style-' . esc_attr( $atts['style'] ) . su_get_css_class( $atts ) . '"><span class="su-frame-inner">' . do_shortcode( $content ) . '</span></span>';

}
