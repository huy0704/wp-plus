<?php

su_add_shortcode(
	array(
		'id'       => 'dummy_text',
		'callback' => 'su_shortcode_dummy_text',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/dummy_text.svg',
		'name'     => __( 'Dummy text', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'content',
		'atts'     => array(
			'what'    => array(
				'type'    => 'select',
				'values'  => array(
					'paras' => __( 'Paragraphs', 'shortcodes-ultimate' ),
					'words' => __( 'Words', 'shortcodes-ultimate' ),
					'bytes' => __( 'Bytes', 'shortcodes-ultimate' ),
				),
				'default' => 'paras',
				'name'    => __( 'What', 'shortcodes-ultimate' ),
				'desc'    => __( 'What to generate', 'shortcodes-ultimate' ),
			),
			'amount'  => array(
				'type'    => 'slider',
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 1,
				'name'    => __( 'Amount', 'shortcodes-ultimate' ),
				'desc'    => __( 'How many items (paragraphs or words) to generate. Minimum words amount is 5', 'shortcodes-ultimate' ),
			),
			'cache'   => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Cache', 'shortcodes-ultimate' ),
				'desc'    => __( 'Generated text will be cached. Be careful with this option. If you disable it and insert many dummy_text shortcodes the page load time will be highly increased', 'shortcodes-ultimate' ),
			),
			'wrapper' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Wrapper', 'shortcodes-ultimate' ),
				'desc'    => __( 'Wrap the dummy text with a div container', 'shortcodes-ultimate' ),
			),
			'class'   => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Text placeholder', 'shortcodes-ultimate' ),
		'icon'     => 'text-height',
	)
);

function su_shortcode_dummy_text( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'amount'  => 1,
			'what'    => 'paras',
			'cache'   => 'yes',
			'wrapper' => 'yes',
			'class'   => '',
		),
		$atts,
		'dummy_text'
	);

	$atts['what']   = sanitize_key( $atts['what'] );
	$atts['amount'] = intval( $atts['amount'] );

	$html = su_shortcode_dummy_text_fetch(
		$atts['what'],
		$atts['amount'],
		$atts['cache']
	);

	if ( 'yes' === $atts['wrapper'] || '' !== $atts['class'] ) {

		$html = sprintf(
			'<div class="su-dummy-text%s">%s</div>',
			su_get_css_class( $atts ),
			$html
		);

	}

	return $html;

}

function su_shortcode_dummy_text_fetch( $what, $amount, $cache ) {

	$key = sprintf( 'su/cache/dummy_text/%s/%s', $what, $amount );

	if ( $cache ) {
		$transient = get_transient( $key );
	}

	if ( $transient ) {
		return $transient;
	}

	$url = esc_url_raw(
		sprintf(
			'http://www.lipsum.com/feed/xml?what=%s&amount=%s&start=0',
			$what,
			$amount
		)
	);

	$xml  = simplexml_load_file( $url );
	$html = wpautop( str_replace( "\n", "\n\n", $xml->lipsum ) );

	set_transient( $key, $html, 30 * DAY_IN_SECONDS );

	return $html;

}
