<?php

su_add_shortcode(
	array(
		'id'       => 'subpages',
		'callback' => 'su_shortcode_subpages',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/subpages.svg',
		'name'     => __( 'Sub pages', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'other',
		'atts'     => array(
			'depth' => array(
				'type'    => 'select',
				'values'  => array( 1, 2, 3, 4, 5 ),
				'default' => 1,
				'name'    => __( 'Depth', 'shortcodes-ultimate' ),
				'desc'    => __( 'Max depth level of children pages', 'shortcodes-ultimate' ),
			),
			'p'     => array(
				'values'  => array(),
				'default' => '',
				'name'    => __( 'Parent ID', 'shortcodes-ultimate' ),
				'desc'    => __( 'ID of the parent page. Leave blank to use current page', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'List of sub pages', 'shortcodes-ultimate' ),
		'icon'     => 'bars',
	)
);

function su_shortcode_subpages( $atts = null, $content = null ) {
	$atts = shortcode_atts(
		array(
			'depth' => 1,
			'p'     => false,
			'class' => '',
		),
		$atts,
		'subpages'
	);
	global $post;
	$child_of = ( $atts['p'] ) ? intval( $atts['p'] ) : get_the_ID();
	$return   = wp_list_pages(
		array(
			'title_li' => '',
			'echo'     => 0,
			'child_of' => $child_of,
			'depth'    => intval( $atts['depth'] ),
		)
	);
	return ( $return ) ? '<ul class="su-subpages' . su_get_css_class( $atts ) . '">' . $return . '</ul>' : false;
}
