<?php

su_add_shortcode(
	array(
		'id'             => 'row',
		'callback'       => 'su_shortcode_row',
		'image'          => su_get_plugin_url() . 'admin/images/shortcodes/row.svg',
		'name'           => __( 'Columns', 'shortcodes-ultimate' ),
		'type'           => 'wrap',
		'group'          => 'box',
		'required_child' => 'column',
		'article'        => 'https://getshortcodes.com/docs/columns/',
		'atts'           => array(
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'        => array(
			'id'     => 'column',
			'number' => 2,
		),
		'desc'           => __( 'Row for flexible columns', 'shortcodes-ultimate' ),
		'icon'           => 'columns',
	)
);

function su_shortcode_row( $atts = null, $content = null ) {

	$atts = shortcode_atts( array( 'class' => '' ), $atts );

	return '<div class="su-row' . su_get_css_class( $atts ) . '">' . su_do_nested_shortcodes( $content, 'row' ) . '</div>';

}
