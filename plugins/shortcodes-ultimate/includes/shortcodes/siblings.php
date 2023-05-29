<?php

su_add_shortcode(
	array(
		'id'       => 'siblings',
		'callback' => 'su_shortcode_siblings',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/siblings.svg',
		'name'     => __( 'Siblings', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'other',
		'atts'     => array(
			'depth' => array(
				'type'    => 'select',
				'values'  => array( 1, 2, 3 ),
				'default' => 1,
				'name'    => __( 'Depth', 'shortcodes-ultimate' ),
				'desc'    => __( 'Max depth level', 'shortcodes-ultimate' ),
			),
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'List of cureent page siblings', 'shortcodes-ultimate' ),
		'icon'     => 'bars',
	)
);

function su_shortcode_siblings( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'depth' => 1,
			'class' => '',
		),
		$atts,
		'siblings'
	);

	global $post;

	if ( ! ( $post instanceof WP_Post ) ) {
		return;
	}

	$return = wp_list_pages(
		array(
			'title_li' => '',
			'echo'     => 0,
			'child_of' => $post->post_parent,
			'depth'    => intval( $atts['depth'] ),
			'exclude'  => $post->ID,
		)
	);

	return $return
		? '<ul class="su-siblings' . su_get_css_class( $atts ) . '">' . $return . '</ul>'
		: '';
}
