<?php

su_add_shortcode(
	array(
		'deprecated' => true,
		'id'         => 'document',
		'callback'   => 'su_shortcode_document',
		'image'      => su_get_plugin_url() . 'admin/images/shortcodes/document.svg',
		'name'       => __( 'Document', 'shortcodes-ultimate' ),
		'type'       => 'single',
		'group'      => 'media',
		'atts'       => array(
			'url'        => array(
				'type'    => 'upload',
				'default' => '',
				'name'    => __( 'Url', 'shortcodes-ultimate' ),
				'desc'    => __( 'Url to uploaded document. Supported formats: doc, xls, pdf etc.', 'shortcodes-ultimate' ),
			),
			'width'      => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Viewer width', 'shortcodes-ultimate' ),
			),
			'height'     => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Viewer height', 'shortcodes-ultimate' ),
			),
			'responsive' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Ignore width and height parameters and make viewer responsive', 'shortcodes-ultimate' ),
			),
			'title'      => array(
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A brief description of the embedded content (used by screenreaders)', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'       => __( 'Document viewer by Google', 'shortcodes-ultimate' ),
		'icon'       => 'file-text',
	)
);

function su_shortcode_document( $atts = null, $content = null ) {
	$atts = shortcode_atts(
		array(
			'url'        => '',
			'file'       => null, // 3.x
			'width'      => 600,
			'height'     => 400,
			'responsive' => 'yes',
			'title'      => '',
			'class'      => '',
		),
		$atts,
		'document'
	);

	if ( null !== $atts['file'] ) {
		$atts['url'] = $atts['file'];
	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-document su-u-responsive-media-' . esc_attr( $atts['responsive'] ) . '"><iframe src="//docs.google.com/viewer?embedded=true&url=' . esc_attr( $atts['url'] ) . '" width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" class="su-document' . su_get_css_class( $atts ) . '" title="' . esc_attr( $atts['title'] ) . '"></iframe></div>';
}
