<?php

su_add_shortcode(
	array(
		'id'       => 'table',
		'callback' => 'su_shortcode_table',
		'type'     => 'wrap',
		'name'     => __( 'Table', 'shortcodes-ultimate' ),
		'desc'     => __( 'Styled table', 'shortcodes-ultimate' ),
		'group'    => 'content',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/table.svg',
		'icon'     => 'table',
		'atts'     => array(
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
		'content'  => __( "<table>\n<tr>\n\t<td>Table</td>\n\t<td>Table</td>\n</tr>\n<tr>\n\t<td>Table</td>\n\t<td>Table</td>\n</tr>\n</table>", 'shortcodes-ultimate' ),
	)
);

function su_shortcode_table( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'        => '', // deprecated since 5.3.0
			'responsive' => 'no',
			'alternate'  => 'yes',
			'fixed'      => 'no',
			'class'      => '',
		),
		$atts,
		'table'
	);

	if ( $atts['url'] && ! su_is_unsafe_features_enabled() ) {

		return su_error_message(
			'Table',
			sprintf(
				'%s.<br><a href="https://getshortcodes.com/docs/unsafe-features/" target="_blank">%s</a>',
				// translators: do not translate the <b>url</b> part, the <b>Unsafe features</b> must be translated
				__( 'The <b>url</b> attribute cannot be used while <b>Unsafe features</b> option is turned off', 'shortcodes-ultimate' ),
				__( 'Learn more', 'shortcodes-ultimate' )
			)
		);

	}

	foreach ( array( 'responsive', 'alternate', 'fixed' ) as $feature ) {

		if ( 'yes' === $atts[ $feature ] ) {
			$atts['class'] .= ' su-table-' . $feature;
		}

	}

	su_query_asset( 'css', 'su-shortcodes' );

	$table = do_shortcode( $content );

	if ( $atts['url'] ) {
		$table = su_shortcode_csv_table(
			array(
				'url'        => $atts['url'],
				'responsive' => $atts['responsive'],
				'alternate'  => $atts['alternate'],
				'fixed'      => $atts['fixed'],
				'class'      => $atts['class'],
				'delimiter'  => ';',
			)
		);
	}

	return '<div class="su-table' . su_get_css_class( $atts ) . '">' . wp_kses_post( $table ) . '</div>';

}
