<?php

su_add_shortcode(
	array(
		'id'       => 'pullquote',
		'callback' => 'su_shortcode_pullquote',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/pullquote.svg',
		'name'     => __( 'Pullquote', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'box',
		'atts'     => array(
			'align' => array(
				'type'    => 'select',
				'values'  => array(
					'left'  => __( 'Left', 'shortcodes-ultimate' ),
					'right' => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'left',
				'name'    => __( 'Align', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pullquote alignment (float)', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Pullquote', 'shortcodes-ultimate' ),
		'desc'     => __( 'Pullquote', 'shortcodes-ultimate' ),
		'icon'     => 'quote-left',
	)
);

function su_shortcode_pullquote( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'align' => 'left',
			'class' => '',
		),
		$atts,
		'pullquote'
	);

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-pullquote su-pullquote-align-' . esc_attr( $atts['align'] ) . su_get_css_class( $atts ) . '">' . do_shortcode( $content ) . '</div>';

}
