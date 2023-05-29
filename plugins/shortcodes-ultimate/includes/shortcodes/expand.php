<?php

su_add_shortcode(
	array(
		'id'       => 'expand',
		'callback' => 'su_shortcode_expand',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/expand.svg',
		'name'     => __( 'Expand', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'box',
		'atts'     => array(
			'more_text'  => array(
				'default' => __( 'Show more', 'shortcodes-ultimate' ),
				'name'    => __( 'More text', 'shortcodes-ultimate' ),
				'desc'    => __( 'Enter the text for more link', 'shortcodes-ultimate' ),
			),
			'less_text'  => array(
				'default' => __( 'Show less', 'shortcodes-ultimate' ),
				'name'    => __( 'Less text', 'shortcodes-ultimate' ),
				'desc'    => __( 'Enter the text for less link', 'shortcodes-ultimate' ),
			),
			'height'     => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 1000,
				'step'    => 10,
				'default' => 100,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Height for collapsed state (in pixels)', 'shortcodes-ultimate' ),
			),
			'hide_less'  => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Hide less link', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option allows you to hide less link, when the text block has been expanded', 'shortcodes-ultimate' ),
			),
			'text_color' => array(
				'type'    => 'color',
				'values'  => array(),
				'default' => '#333333',
				'name'    => __( 'Text color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick the text color', 'shortcodes-ultimate' ),
			),
			'link_color' => array(
				'type'    => 'color',
				'values'  => array(),
				'default' => '#0088FF',
				'name'    => __( 'Link color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Pick the link color', 'shortcodes-ultimate' ),
			),
			'link_style' => array(
				'type'    => 'select',
				'values'  => array(
					'default'    => __( 'Default', 'shortcodes-ultimate' ),
					'underlined' => __( 'Underlined', 'shortcodes-ultimate' ),
					'dotted'     => __( 'Dotted', 'shortcodes-ultimate' ),
					'dashed'     => __( 'Dashed', 'shortcodes-ultimate' ),
					'button'     => __( 'Button', 'shortcodes-ultimate' ),
				),
				'default' => 'default',
				'name'    => __( 'Link style', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select the style for more/less link', 'shortcodes-ultimate' ),
			),
			'link_align' => array(
				'type'    => 'select',
				'values'  => array(
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'left',
				'name'    => __( 'Link align', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select link alignment', 'shortcodes-ultimate' ),
			),
			'more_icon'  => array(
				'type'    => 'icon',
				'default' => '',
				'name'    => __( 'More icon', 'shortcodes-ultimate' ),
				'desc'    => __( 'Add an icon to the more link', 'shortcodes-ultimate' ),
			),
			'less_icon'  => array(
				'type'    => 'icon',
				'default' => '',
				'name'    => __( 'Less icon', 'shortcodes-ultimate' ),
				'desc'    => __( 'Add an icon to the less link', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'This text block can be expanded', 'shortcodes-ultimate' ),
		'desc'     => __( 'Expandable text block', 'shortcodes-ultimate' ),
		'icon'     => 'sort-amount-asc',
	)
);

function su_shortcode_expand( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'more_text'  => __( 'Show more', 'shortcodes-ultimate' ),
			'less_text'  => __( 'Show less', 'shortcodes-ultimate' ),
			'height'     => '100',
			'hide_less'  => 'no',
			'text_color' => '#333333',
			'link_color' => '#0088FF',
			'link_style' => 'default',
			'link_align' => 'left',
			'more_icon'  => '',
			'less_icon'  => '',
			'class'      => '',
		),
		$atts,
		'expand'
	);

	// Prepare more icon
	$more_icon = ( $atts['more_icon'] ) ? su_html_icon( $atts['more_icon'] ) : '';
	$less_icon = ( $atts['less_icon'] ) ? su_html_icon( $atts['less_icon'] ) : '';

	if ( $more_icon || $less_icon ) {
		su_query_asset( 'css', 'su-icons' );
	}

	// Prepare less link
	$less = $atts['hide_less'] !== 'yes'
		? '<div class="su-expand-link su-expand-link-less" style="text-align:' . esc_attr( $atts['link_align'] ) . '"><a href="javascript:;" style="color:' . esc_attr( $atts['link_color'] ) . ';border-color:' . esc_attr( $atts['link_color'] ) . '">' . $less_icon . '<span style="border-color:' . esc_attr( $atts['link_color'] ) . '">' . $atts['less_text'] . '</span></a></div>'
		: '';

	su_query_asset( 'css', 'su-shortcodes' );
	su_query_asset( 'js', 'su-shortcodes' );

	return '<div class="su-expand su-expand-collapsed su-expand-link-style-' . esc_attr( $atts['link_style'] ) . su_get_css_class( $atts ) . '" data-height="' . esc_attr( $atts['height'] ) . '"><div class="su-expand-content su-u-trim" style="color:' . esc_attr( $atts['text_color'] ) . ';max-height:' . intval( $atts['height'] ) . 'px;overflow:hidden">' . do_shortcode( $content ) . '</div><div class="su-expand-link su-expand-link-more" style="text-align:' . esc_attr( $atts['link_align'] ) . '"><a href="javascript:;" style="color:' . esc_attr( $atts['link_color'] ) . ';border-color:' . esc_attr( $atts['link_color'] ) . '">' . $more_icon . '<span style="border-color:' . esc_attr( $atts['link_color'] ) . '">' . $atts['more_text'] . '</span></a></div>' . $less . '</div>';

}
