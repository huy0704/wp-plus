<?php

su_add_shortcode(
	array(
		'id'       => 'heading',
		'callback' => 'su_shortcode_heading',
		'name'     => __( 'Heading', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'content',
		'atts'     => array(
			'style'  => array(
				'type'    => 'select',
				'values'  => su_get_available_styles_for( 'heading' ),
				'default' => 'default',
				'name'    => __( 'Style', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose style for this heading', 'shortcodes-ultimate' ),
			),
			'size'   => array(
				'type'    => 'slider',
				'min'     => 7,
				'max'     => 48,
				'step'    => 1,
				'default' => 13,
				'name'    => __( 'Size', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select heading size (pixels)', 'shortcodes-ultimate' ),
			),
			'align'  => array(
				'type'    => 'select',
				'values'  => array(
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'center',
				'name'    => __( 'Align', 'shortcodes-ultimate' ),
				'desc'    => __( 'Heading text alignment', 'shortcodes-ultimate' ),
			),
			'margin' => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 200,
				'step'    => 10,
				'default' => 20,
				'name'    => __( 'Margin', 'shortcodes-ultimate' ),
				'desc'    => __( 'Bottom margin (pixels)', 'shortcodes-ultimate' ),
			),
			'id'     => array(
				'name'    => __( 'HTML Anchor (ID)', 'shortcodes-ultimate' ),
				'desc'    => __( 'Anchors lets you link directly to a heading on a page.', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'  => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Heading text', 'shortcodes-ultimate' ),
		'desc'     => __( 'Styled heading', 'shortcodes-ultimate' ),
		'icon'     => 'h-square',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/heading.svg',
	)
);

function su_shortcode_heading( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'style'  => 'default',
			'size'   => 13,
			'align'  => 'center',
			'margin' => '20',
			'id'     => '',
			'class'  => '',
		),
		$atts,
		'heading'
	);

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-heading su-heading-style-' . esc_attr( $atts['style'] ) . ' su-heading-align-' . esc_attr( $atts['align'] ) . su_get_css_class( $atts ) . '" id="' . esc_attr( $atts['id'] ) . '" style="font-size:' . intval( $atts['size'] ) . 'px;margin-bottom:' . esc_attr( $atts['margin'] ) . 'px"><div class="su-heading-inner">' . do_shortcode( $content ) . '</div></div>';

}
