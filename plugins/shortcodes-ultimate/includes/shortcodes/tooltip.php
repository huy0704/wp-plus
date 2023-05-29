<?php

su_add_shortcode(
	array(
		'id'       => 'tooltip',
		'callback' => 'su_shortcode_tooltip',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/tooltip.svg',
		'name'     => __( 'Tooltip', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'other',
		'atts'     => array(
			'title'      => array(
				'default' => '',
				'name'    => __( 'Tooltip title', 'shortcodes-ultimate' ),
				'desc'    => __( 'Title of the tooltip. Use empty value to hide the title', 'shortcodes-ultimate' ),
			),
			'text'       => array(
				'default' => __( 'Tooltip content', 'shortcodes-ultimate' ),
				'name'    => __( 'Tooltip content', 'shortcodes-ultimate' ),
				'desc'    => __( 'Content of the tooltip', 'shortcodes-ultimate' ),
			),
			'position'   => array(
				'type'    => 'select',
				'values'  => array(
					'top'    => __( 'Top', 'shortcodes-ultimate' ),
					'bottom' => __( 'Bottom', 'shortcodes-ultimate' ),
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'top',
				'name'    => __( 'Position', 'shortcodes-ultimate' ),
				'desc'    => __( 'Tooltip position', 'shortcodes-ultimate' ),
			),
			'background' => array(
				'type'    => 'color',
				'default' => '#222222',
				'name'    => __( 'Background color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Tooltip background color', 'shortcodes-ultimate' ),
			),
			'color'      => array(
				'type'    => 'color',
				'default' => '#FFFFFF',
				'name'    => __( 'Text color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Tooltip text color', 'shortcodes-ultimate' ),
			),
			'font_size'  => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 24,
				'step'    => 1,
				'default' => 16,
				'name'    => __( 'Font size', 'shortcodes-ultimate' ),
				'desc'    => __( 'The font size of the tooltip content', 'shortcodes-ultimate' ),
			),
			'text_align' => array(
				'type'    => 'select',
				'values'  => array(
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
				),
				'default' => 'left',
				'name'    => __( 'Text align', 'shortcodes-ultimate' ),
				'desc'    => __( 'The alignment of the tooltip content', 'shortcodes-ultimate' ),
			),
			'max_width'  => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1000,
				'step'    => 10,
				'default' => 300,
				'name'    => __( 'Max width', 'shortcodes-ultimate' ),
				'desc'    => __( 'The maximum width of the tooltip, in pixels', 'shortcodes-ultimate' ),
			),
			'radius'     => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 20,
				'step'    => 1,
				'default' => 5,
				'name'    => __( 'Tooltip border radius', 'shortcodes-ultimate' ),
				'desc'    => __( 'The radius of the tooltip corners, in pixels. Use 0 to make corners square', 'shortcodes-ultimate' ),
			),
			'shadow'     => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Tooltip shadow', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option enables tooltip shadow', 'shortcodes-ultimate' ),
			),
			'behavior'   => array(
				'type'    => 'select',
				'values'  => array(
					'hover'  => __( 'Show and hide on mouse over', 'shortcodes-ultimate' ),
					'click'  => __( 'Show and hide on mouse click', 'shortcodes-ultimate' ),
					'always' => __( 'Always visible', 'shortcodes-ultimate' ),
				),
				'default' => 'hover',
				'name'    => __( 'Behavior', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option determines how the tooltip will be opened', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Hover me to open tooltip', 'shortcodes-ultimate' ),
		'desc'     => __( 'Tooltip window with custom content', 'shortcodes-ultimate' ),
		'icon'     => 'comment-o',
	)
);

function su_shortcode_tooltip( $atts = null, $content = null ) {

	$atts = su_parse_shortcode_atts(
		'tooltip',
		$atts,
		array(
			'style'         => 'none',
			'size'          => 'none',
			'rounded'       => 'none',
			'outline'       => 'yes',
			'tabindex'      => 'yes',
			'reference_tag' => 'span',
			'line_height'   => '1.25',
			'hide_delay'    => '0',
			'content'       => '',
			'z_index'       => '100',
		)
	);

	if ( 'none' !== $atts['style'] ) {

		$bc_styles = array(
			'light'     => array( '#FFFFFF', '#454545' ),
			'dark'      => array( '#505050', '#F3F3F3' ),
			'yellow'    => array( '#FFFFA3', '#555555' ),
			'green'     => array( '#CAED9E', '#3F6219' ),
			'red'       => array( '#F78B83', '#912323' ),
			'blue'      => array( '#E5F6FE', '#5E99BD' ),
			'youtube'   => array( '#252626', '#FFFFFF' ),
			'tipsy'     => array( '#111111', '#FFFFFF' ),
			'bootstrap' => array( '#FFFFFF', '#333333' ),
			'jtools'    => array( '#252626', '#F5F5F5' ),
			'tipped'    => array( '#FFFFFF', '#454545' ),
			'cluetip'   => array( '#D9D9C2', '#111111' ),
		);

		if ( array_key_exists( $atts['style'], $bc_styles ) ) {

			$atts['background'] = $bc_styles[ $atts['style'] ][0];
			$atts['color']      = $bc_styles[ $atts['style'] ][1];

		}

	}

	if ( 'none' !== $atts['size'] ) {

		$bc_sizes = array(
			'default' => '14',
			'1'       => '13',
			'2'       => '14',
			'3'       => '16',
			'4'       => '18',
			'5'       => '19',
			'6'       => '21',
		);

		if ( array_key_exists( $atts['size'], $bc_sizes ) ) {
			$atts['font_size'] = $bc_sizes[ $atts['size'] ];
		}

	}

	if ( ! empty( $atts['content'] ) ) {
		$atts['text'] = $atts['content'];
	}

	if ( 'no' === $atts['rounded'] ) {
		$atts['radius'] = 0;
	}

	$atts['position'] = sanitize_key( $atts['position'] );
	$atts['position'] = str_replace(
		array( 'north', 'east', 'south', 'west' ),
		array( 'top', 'right', 'bottom', 'left' ),
		$atts['position']
	);

	$atts['tabindex'] = 'yes' === $atts['tabindex'] ? ' tabindex="0"' : '';

	$js_settings = array(
		'position'  => sanitize_key( $atts['position'] ),
		'behavior'  => sanitize_key( $atts['behavior'] ),
		'hideDelay' => intval( $atts['hide_delay'] ),
	);

	su_query_asset( 'css', 'su-shortcodes' );
	su_query_asset( 'js', 'popper' );
	su_query_asset( 'js', 'su-shortcodes' );

	$template = '<{{REFERENCE_TAG}} id="{{ID}}_button" class="su-tooltip-button su-tooltip-button-outline-{{OUTLINE}}{{CSS_CLASS}}" aria-describedby="{{ID}}" data-settings=\'{{JSON}}\'{{TABINDEX}}>{{BUTTON}}</{{REFERENCE_TAG}}><span style="display:none;z-index:{{Z_INDEX}}" id="{{ID}}" class="su-tooltip{{CSS_CLASS}}" role="tooltip"><span class="su-tooltip-inner su-tooltip-shadow-{{SHADOW}}" style="z-index:{{Z_INDEX}};background:{{BACKGROUND}};color:{{COLOR}};font-size:{{FONT_SIZE}};border-radius:{{RADIUS}};text-align:{{ALIGN}};max-width:{{MAX_WIDTH}};line-height:{{LINE_HEIGHT}}"><span class="su-tooltip-title">{{TITLE}}</span><span class="su-tooltip-content su-u-trim">{{TEXT}}</span></span><span id="{{ID}}_arrow" class="su-tooltip-arrow" style="z-index:{{Z_INDEX}};background:{{BACKGROUND}}" data-popper-arrow></span></span>';

	$template_data = array(
		'{{ID}}'            => uniqid( 'su_tooltip_' ),
		'{{CSS_CLASS}}'     => su_get_css_class( $atts ),
		'{{JSON}}'          => wp_json_encode( $js_settings ),
		'{{BUTTON}}'        => do_shortcode( $content ),
		'{{TITLE}}'         => su_do_attribute( $atts['title'] ),
		'{{TEXT}}'          => su_do_attribute( $atts['text'] ),
		'{{SHADOW}}'        => sanitize_key( $atts['shadow'] ),
		'{{RADIUS}}'        => esc_attr( su_maybe_add_css_units( $atts['radius'], 'px' ) ),
		'{{BACKGROUND}}'    => esc_attr( $atts['background'] ),
		'{{COLOR}}'         => esc_attr( $atts['color'] ),
		'{{FONT_SIZE}}'     => esc_attr( su_maybe_add_css_units( $atts['font_size'], 'px' ) ),
		'{{MAX_WIDTH}}'     => esc_attr( su_maybe_add_css_units( $atts['max_width'], 'px' ) ),
		'{{ALIGN}}'         => sanitize_key( $atts['text_align'] ),
		'{{OUTLINE}}'       => sanitize_key( $atts['outline'] ),
		'{{REFERENCE_TAG}}' => sanitize_key( $atts['reference_tag'] ),
		'{{TABINDEX}}'      => $atts['tabindex'],
		'{{LINE_HEIGHT}}'   => esc_attr( $atts['line_height'] ),
		'{{Z_INDEX}}'       => intval( $atts['z_index'] ),
	);

	return str_replace(
		array_keys( $template_data ),
		array_values( $template_data ),
		$template
	);

}
