<?php

su_add_shortcode(
	array(
		'id'              => 'column',
		'callback'        => 'su_shortcode_column',
		'image'           => su_get_plugin_url() . 'admin/images/shortcodes/column.svg',
		'name'            => __( 'Column', 'shortcodes-ultimate' ),
		'type'            => 'wrap',
		'group'           => 'box',
		'required_parent' => 'row',
		'atts'            => array(
			'size'   => array(
				'type'    => 'select',
				'values'  => array(
					'1/1' => __( 'Full width', 'shortcodes-ultimate' ),
					'1/2' => __( 'One half', 'shortcodes-ultimate' ),
					'1/3' => __( 'One third', 'shortcodes-ultimate' ),
					'2/3' => __( 'Two third', 'shortcodes-ultimate' ),
					'1/4' => __( 'One fourth', 'shortcodes-ultimate' ),
					'3/4' => __( 'Three fourth', 'shortcodes-ultimate' ),
					'1/5' => __( 'One fifth', 'shortcodes-ultimate' ),
					'2/5' => __( 'Two fifth', 'shortcodes-ultimate' ),
					'3/5' => __( 'Three fifth', 'shortcodes-ultimate' ),
					'4/5' => __( 'Four fifth', 'shortcodes-ultimate' ),
					'1/6' => __( 'One sixth', 'shortcodes-ultimate' ),
					'5/6' => __( 'Five sixth', 'shortcodes-ultimate' ),
				),
				'default' => '1/2',
				'name'    => __( 'Size', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select column width. This width will be calculated depend page width', 'shortcodes-ultimate' ),
			),
			'center' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Centered', 'shortcodes-ultimate' ),
				'desc'    => __( 'Is this column centered on the page', 'shortcodes-ultimate' ),
			),
			'class'  => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'         => __( 'Column content', 'shortcodes-ultimate' ),
		'desc'            => __( 'Flexible and responsive columns', 'shortcodes-ultimate' ),
		'note'            => __( 'Did you know that you need to wrap columns with [row] shortcode?', 'shortcodes-ultimate' ),
		'example'         => 'columns',
		'icon'            => 'columns',
	)
);

function su_shortcode_column( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'size'   => '1/2',
			'center' => 'no',
			'last'   => null,
			'class'  => '',
		),
		$atts,
		'column'
	);

	if ( $atts['last'] !== null && $atts['last'] == '1' ) {
		$atts['class'] .= ' su-column-last';
	}

	if ( $atts['center'] === 'yes' ) {
		$atts['class'] .= ' su-column-centered';
	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-column su-column-size-' . esc_attr( str_replace( '/', '-', $atts['size'] ) ) . su_get_css_class( $atts ) . '"><div class="su-column-inner su-u-clearfix su-u-trim">' . su_do_nested_shortcodes( $content, 'column' ) . '</div></div>';

}
