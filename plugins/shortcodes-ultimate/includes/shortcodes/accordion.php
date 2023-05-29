<?php

su_add_shortcode(
	array(
		'id'             => 'accordion',
		'callback'       => 'su_shortcode_accordion',
		'image'          => su_get_plugin_url() . 'admin/images/shortcodes/accordion.svg',
		'name'           => __( 'Accordion', 'shortcodes-ultimate' ),
		'type'           => 'wrap',
		'group'          => 'box',
		'required_child' => 'spoiler',
		'atts'           => array(
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'        => array(
			'id'     => 'spoiler',
			'number' => 3,
		),
		'desc'           => __( 'Accordion with spoilers', 'shortcodes-ultimate' ),
		'note'           => __( 'Did you know that you can wrap multiple spoilers with [accordion] shortcode to create accordion effect?', 'shortcodes-ultimate' ),
		'example'        => 'spoilers',
		'icon'           => 'list',
	)
);

function su_shortcode_accordion( $atts = null, $content = null ) {

	$atts = shortcode_atts( array( 'class' => '' ), $atts, 'accordion' );

	return '<div class="su-accordion su-u-trim' . su_get_css_class( $atts ) . '">' . do_shortcode( $content ) . '</div>';

}
