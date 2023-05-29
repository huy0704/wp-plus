<?php

su_add_shortcode(
	array(
		'id'               => 'lightbox_content',
		'callback'         => 'su_shortcode_lightbox_content',
		'image'            => su_get_plugin_url() . 'admin/images/shortcodes/lightbox_content.svg',
		'name'             => __( 'Lightbox content', 'shortcodes-ultimate' ),
		'type'             => 'wrap',
		'group'            => 'gallery',
		'required_sibling' => 'lightbox',
		'article'          => 'https://getshortcodes.com/docs/lightbox/',
		'atts'             => array(
			'id'         => array(
				'default' => '',
				'name'    => __( 'ID', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					'%1$s %2$s: %3$s',
					__( 'The ID of the element. Use the value from the Content source field of the lightbox shortcode.', 'shortcodes-ultimate' ),
					__( 'Example', 'shortcodes-ultimate' ),
					'<b%value>my-custom-popup</b>'
				),
			),
			'width'      => array(
				'default' => 'auto',
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					'%1$s<br>%2$s: %3$s',
					__( 'The width of the element. CSS units are allowed.', 'shortcodes-ultimate' ),
					__( 'Examples', 'shortcodes-ultimate' ),
					'<b%value>auto</b>, <b%value>300px</b>, <b%value>40em</b>, <b%value>90%</b>, <b%value>90vw</b>'
				),
			),
			'min_width'  => array(
				'default' => 'none',
				'name'    => __( 'Min. Width', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					'%1$s<br>%2$s: %3$s',
					__( 'The minimum width of the element. CSS units are allowed.', 'shortcodes-ultimate' ),
					__( 'Examples', 'shortcodes-ultimate' ),
					'<b%value>none</b>, <b%value>300px</b>, <b%value>40em</b>, <b%value>90%</b>, <b%value>90vw</b>'
				),
			),
			'max_width'  => array(
				'default' => '600px',
				'name'    => __( 'Max. Width', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					'%1$s<br>%2$s: %3$s',
					__( 'The maximum width of the element. CSS units are allowed.', 'shortcodes-ultimate' ),
					__( 'Examples', 'shortcodes-ultimate' ),
					'<b%value>none</b>, <b%value>300px</b>, <b%value>40em</b>, <b%value>90%</b>, <b%value>90vw</b>'
				),
			),
			'margin'     => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 600,
				'step'    => 5,
				'default' => 40,
				'name'    => __( 'Margin', 'shortcodes-ultimate' ),
				'desc'    => __( 'The outer spacing of the element (in pixels)', 'shortcodes-ultimate' ),
			),
			'padding'    => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 600,
				'step'    => 5,
				'default' => 40,
				'name'    => __( 'Padding', 'shortcodes-ultimate' ),
				'desc'    => __( 'The inner spacing of the element (in pixels)', 'shortcodes-ultimate' ),
			),
			'text_align' => array(
				'type'    => 'select',
				'values'  => array(
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'center',
				'name'    => __( 'Text alignment', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select the text alignment', 'shortcodes-ultimate' ),
			),
			'background' => array(
				'type'    => 'color',
				'default' => '#FFFFFF',
				'name'    => __( 'Background color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick a background color', 'shortcodes-ultimate' ),
			),
			'color'      => array(
				'type'    => 'color',
				'default' => '#333333',
				'name'    => __( 'Text color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick a text color', 'shortcodes-ultimate' ),
			),
			'color'      => array(
				'type'    => 'color',
				'default' => '#333333',
				'name'    => __( 'Text color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick a text color', 'shortcodes-ultimate' ),
			),
			'shadow'     => array(
				'type'    => 'shadow',
				'default' => '0px 0px 15px #333333',
				'name'    => __( 'Shadow', 'shortcodes-ultimate' ),
				'desc'    => __( 'Adjust the shadow for content box', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'          => __( 'Inline content', 'shortcodes-ultimate' ),
		'desc'             => __( 'Inline content for lightbox', 'shortcodes-ultimate' ),
		'icon'             => 'external-link',
	)
);

function su_shortcode_lightbox_content( $atts = null, $content = null ) {

	$atts = su_parse_shortcode_atts( 'lightbox_content', $atts );

	if ( ! $atts['id'] ) {

		return su_error_message(
			'Lightbox content',
			__( 'invalid ID. Use the value from the Content source field of the lightbox shortcode.', 'shortcodes-ultimate' )
		);

	}

	if ( is_numeric( $atts['margin'] ) ) {
		$atts['margin'] = "{$atts['margin']}px";
	}

	if ( is_numeric( $atts['padding'] ) ) {
		$atts['padding'] = "{$atts['padding']}px";
	}

	$style = array(
		'display:none',
		'width:' . sanitize_text_field( $atts['width'] ),
		'min-width:' . sanitize_text_field( $atts['min_width'] ),
		'max-width:' . sanitize_text_field( $atts['max_width'] ),
		'margin-top:' . sanitize_text_field( $atts['margin'] ),
		'margin-bottom:' . sanitize_text_field( $atts['margin'] ),
		'padding:' . sanitize_text_field( $atts['padding'] ),
		'background:' . sanitize_text_field( $atts['background'] ),
		'color:' . sanitize_text_field( $atts['color'] ),
		'box-shadow:' . sanitize_text_field( $atts['shadow'] ),
		'text-align:' . sanitize_key( $atts['text_align'] ),
	);

	$output = sprintf(
		'<div class="su-lightbox-content su-u-trim %1$s" id="%2$s"%3$s>%4$s</div>',
		su_get_css_class( $atts ),
		sanitize_html_class( $atts['id'] ),
		su_html_style( $style ),
		do_shortcode( $content )
	);

	if ( did_action( 'su/generator/preview/before' ) ) {

		$output = sprintf(
			'<div class="su-lightbox-content-preview">%s</div>',
			$output
		);

	}

	su_query_asset( 'css', 'su-shortcodes' );

	return $output;

}
