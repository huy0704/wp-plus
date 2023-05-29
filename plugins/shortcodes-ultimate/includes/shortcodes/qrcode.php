<?php

su_add_shortcode(
	array(
		'id'       => 'qrcode',
		'callback' => 'su_shortcode_qrcode',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/qrcode.svg',
		'name'     => __( 'QR code', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'content',
		'atts'     => array(
			'data'       => array(
				'default' => '',
				'name'    => __( 'Data', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					// translators: %1$s and %2$s will be replaced with %CURRENT_URL% and %PERMALINK% tokens respectively
					__( 'The content to store in the QR code. You can use here any text or even URL.<br>Use %1$s to display the URL of the current page or %2$s to display the permalink of the current post.', 'shortcodes-ultimate' ),
					'<b%value>%CURRENT_URL%</b>',
					'<b%value>%PERMALINK%</b>'
				),
			),
			'title'      => array(
				'default' => '',
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'Enter here short description. This text will be used in alt attribute of QR code', 'shortcodes-ultimate' ),
			),
			'size'       => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1000,
				'step'    => 10,
				'default' => 200,
				'name'    => __( 'Size', 'shortcodes-ultimate' ),
				'desc'    => __( 'Image width and height (in pixels)', 'shortcodes-ultimate' ),
			),
			'margin'     => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 50,
				'step'    => 5,
				'default' => 0,
				'name'    => __( 'Margin', 'shortcodes-ultimate' ),
				'desc'    => __( 'Thickness of a margin (in pixels)', 'shortcodes-ultimate' ),
			),
			'align'      => array(
				'type'    => 'select',
				'values'  => array(
					'none'   => __( 'None', 'shortcodes-ultimate' ),
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'none',
				'name'    => __( 'Align', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose image alignment', 'shortcodes-ultimate' ),
			),
			'link'       => array(
				'default' => '',
				'name'    => __( 'Link', 'shortcodes-ultimate' ),
				'desc'    => __( 'You can make this QR code clickable. Enter here the URL', 'shortcodes-ultimate' ),
			),
			'target'     => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'blank',
				'name'    => __( 'Link target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select link target', 'shortcodes-ultimate' ),
			),
			'color'      => array(
				'type'    => 'color',
				'default' => '#000000',
				'name'    => __( 'Primary color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick a primary color', 'shortcodes-ultimate' ),
			),
			'background' => array(
				'type'    => 'color',
				'default' => '#ffffff',
				'name'    => __( 'Background color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick a background color', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Advanced QR code generator', 'shortcodes-ultimate' ),
		'icon'     => 'qrcode',
	)
);

function su_shortcode_qrcode( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'data'       => '',
			'title'      => '',
			'size'       => 200,
			'margin'     => 0,
			'align'      => 'none',
			'link'       => '',
			'target'     => 'blank',
			'color'      => '#000000',
			'background' => '#ffffff',
			'class'      => '',
		),
		$atts,
		'qrcode'
	);

	if ( ! $atts['data'] ) {
		return su_error_message( 'QR code', __( 'please specify the data', 'shortcodes-ultimate' ) );
	}

	$atts['data'] = str_replace(
		array( '%CURRENT_URL%', '%PERMALINK%' ),
		array( su_get_current_url(), get_permalink() ),
		$atts['data']
	);

	$atts['data'] = su_do_attribute( $atts['data'] );
	$atts['data'] = sanitize_text_field( $atts['data'] );

	if ( $atts['link'] ) {

		$atts['link'] = sprintf(
			' href="%s"',
			esc_attr( su_do_attribute( $atts['link'] ) )
		);

		$atts['class'] .= ' su-qrcode-clickable';

	}

	$atts['title'] = esc_attr( $atts['title'] );

	su_query_asset( 'css', 'su-shortcodes' );

	$url = add_query_arg(
		array(
			'data'    => rawurlencode( $atts['data'] ),
			'size'    => sprintf( '%1$sx%1$s', intval( $atts['size'] ) ),
			'format'  => 'png',
			'margin'  => intval( $atts['margin'] ),
			'color'   => ltrim( $atts['color'], '#' ),
			'bgcolor' => ltrim( $atts['background'], '#' ),
		),
		'https://api.qrserver.com/v1/create-qr-code/'
	);

	return sprintf(
		'<span class="su-qrcode su-qrcode-align-%1$s%2$s"><a%3$s target="_%4$s" title="%5$s"><img src="%6$s" alt="%5$s" /></a></span>',
		/* %1$s */ esc_attr( $atts['align'] ),
		/* %2$s */ su_get_css_class( $atts ),
		/* %3$s */ $atts['link'],
		/* %4$s */ esc_attr( $atts['target'] ),
		/* %5$s */ esc_attr( $atts['title'] ),
		/* %6$s */ esc_url( $url )
	);

}
