<?php

su_add_shortcode(
	array(
		'id'       => 'permalink',
		'callback' => 'su_shortcode_permalink',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/permalink.svg',
		'name'     => __( 'Permalink', 'shortcodes-ultimate' ),
		'type'     => 'mixed',
		'group'    => 'content other',
		'atts'     => array(
			'id'     => array(
				'default' => 1,
				'name'    => __( 'ID', 'shortcodes-ultimate' ),
				'desc'    => __( 'Post or page ID', 'shortcodes-ultimate' ),
			),
			'target' => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'self',
				'name'    => __( 'Target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Link target', 'shortcodes-ultimate' ),
			),
			'title'  => array(
				'default' => '',
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A value for the title attribute of the link', 'shortcodes-ultimate' ),
			),
			'rel'    => array(
				'default' => '',
				'name'    => __( 'Rel', 'shortcodes-ultimate' ),
				'desc'    => __( 'A value for the rel attribute of the link', 'shortcodes-ultimate' ),
			),
			'class'  => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => '',
		'desc'     => __( 'Permalink to specified post/page', 'shortcodes-ultimate' ),
		'icon'     => 'link',
	)
);

function su_shortcode_permalink( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'id'     => 1,
			'p'      => null, // 3.x
			'target' => 'self',
			'title'  => '',
			'rel'    => '',
			'class'  => '',
		),
		$atts,
		'permalink'
	);

	if ( $atts['p'] !== null ) {
		$atts['id'] = $atts['p'];
	}

	$atts['id'] = su_do_attribute( $atts['id'] );

	if ( ! $content ) {
		$content = get_the_title( $atts['id'] );
	}

	return sprintf(
		'<a href="%s" title="%s" target="_%s" rel="%s" class="%s">%s</a>',
		get_permalink( $atts['id'] ),
		esc_attr( $atts['title'] ),
		esc_attr( $atts['target'] ),
		esc_attr( $atts['rel'] ),
		su_get_css_class( $atts ),
		do_shortcode( $content )
	);

}
