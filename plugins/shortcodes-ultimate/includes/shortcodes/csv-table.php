<?php

su_add_shortcode(
	array(
		'id'       => 'csv_table',
		'callback' => 'su_shortcode_csv_table',
		'type'     => 'single',
		'name'     => __( 'CSV Table', 'shortcodes-ultimate' ),
		'desc'     => __( 'Styled table from CSV file', 'shortcodes-ultimate' ),
		'group'    => 'content',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/table.svg',
		'icon'     => 'table',
		'atts'     => array(
			'url'        => array(
				'type'    => 'upload',
				'default' => '',
				'name'    => __( 'CSV file URL', 'shortcodes-ultimate' ),
				'desc'    => __( 'The URL of a CSV file that will be displayed', 'shortcodes-ultimate' ),
			),
			'delimiter'  => array(
				'type'    => 'text',
				'default' => ',',
				'name'    => __( 'Delimiter', 'shortcodes-ultimate' ),
				'desc'    => __( 'Set the field delimiter (one character only)', 'shortcodes-ultimate' ),
			),
			'header'     => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Display header', 'shortcodes-ultimate' ),
				'desc'    => __( 'Display first row as table header', 'shortcodes-ultimate' ),
			),
			'responsive' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Add horizontal scrollbar if table width larger than page width', 'shortcodes-ultimate' ),
			),
			'alternate'  => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Alternate row color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Enable to use alternative background color for even rows', 'shortcodes-ultimate' ),
			),
			'fixed'      => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Fixed layout', 'shortcodes-ultimate' ),
				'desc'    => __( 'Fixed width table cells', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
	)
);

function su_shortcode_csv_table( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'        => '',
			'delimiter'  => ',',
			'header'     => 'no',
			'responsive' => 'no',
			'alternate'  => 'yes',
			'fixed'      => 'no',
			'class'      => '',
		),
		$atts,
		'table'
	);

	if ( ! su_is_unsafe_features_enabled() ) {

		return su_error_message(
			'CSV Table',
			sprintf(
				'%s.<br><a href="https://getshortcodes.com/docs/unsafe-features/" target="_blank">%s</a>',
				__( 'This shortcode cannot be used while <b>Unsafe features</b> option is turned off', 'shortcodes-ultimate' ),
				__( 'Learn more', 'shortcodes-ultimate' )
			)
		);

	}

	if ( filter_var( $atts['url'], FILTER_VALIDATE_URL ) === false ) {
		return su_error_message( 'CSV Table', __( 'invalid URL', 'shortcodes-ultimate' ) );
	}

	$response = wp_safe_remote_get( $atts['url'] );

	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return su_error_message( 'CSV Table', __( 'invalid URL', 'shortcodes-ultimate' ) );
	}

	if ( ! is_string( $atts['delimiter'] ) || 1 !== strlen( $atts['delimiter'] ) ) {
		return su_error_message( 'CSV Table', __( 'invalid delimiter', 'shortcodes-ultimate' ) );
	}

	$csv  = wp_remote_retrieve_body( $response );
	$html = su_csv_to_html(
		$csv,
		$atts['delimiter'],
		'yes' === $atts['header']
	);

	foreach ( array( 'responsive', 'alternate', 'fixed' ) as $feature ) {

		if ( 'yes' === $atts[ $feature ] ) {
			$atts['class'] .= ' su-table-' . $feature;
		}

	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-table su-csv-table' . su_get_css_class( $atts ) . '">' . wp_kses_post( $html ) . '</div>';

}
