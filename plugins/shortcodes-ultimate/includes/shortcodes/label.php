<?php

su_add_shortcode(
	array(
		'id'       => 'label',
		'callback' => 'su_shortcode_label',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/label.svg',
		'name'     => __( 'Label', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'content',
		'atts'     => array(
			'type'  => array(
				'type'    => 'select',
				'values'  => array(
					'default'   => __( 'Default', 'shortcodes-ultimate' ),
					'success'   => __( 'Success', 'shortcodes-ultimate' ),
					'warning'   => __( 'Warning', 'shortcodes-ultimate' ),
					'important' => __( 'Important', 'shortcodes-ultimate' ),
					'black'     => __( 'Black', 'shortcodes-ultimate' ),
					'info'      => __( 'Info', 'shortcodes-ultimate' ),
				),
				'default' => 'default',
				'name'    => __( 'Type', 'shortcodes-ultimate' ),
				'desc'    => __( 'Style of the label', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Label', 'shortcodes-ultimate' ),
		'desc'     => __( 'Styled label', 'shortcodes-ultimate' ),
		'icon'     => 'tag',
	)
);

function su_shortcode_label( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'type'  => 'default',
			'style' => null, // 3.x
			'class' => '',
		),
		$atts,
		'label'
	);

	if ( $atts['style'] !== null ) {
		$atts['type'] = $atts['style'];
	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<span class="su-label su-label-type-' . esc_attr( $atts['type'] ) . su_get_css_class( $atts ) . '">' . do_shortcode( $content ) . '</span>';

}
