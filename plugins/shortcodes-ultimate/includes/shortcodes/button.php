<?php

su_add_shortcode(
	array(
		'id'       => 'button',
		'callback' => 'su_shortcode_button',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/button.svg',
		'name'     => __( 'Button', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'content',
		'atts'     => array(
			'url'         => array(
				'values'  => array(),
				'default' => get_option( 'home' ),
				'name'    => __( 'Link', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button link', 'shortcodes-ultimate' ),
			),
			'target'      => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'self',
				'name'    => __( 'Target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button link target', 'shortcodes-ultimate' ),
			),
			'style'       => array(
				'type'    => 'select',
				'values'  => array(
					'default' => __( 'Default', 'shortcodes-ultimate' ),
					'flat'    => __( 'Flat', 'shortcodes-ultimate' ),
					'ghost'   => __( 'Ghost', 'shortcodes-ultimate' ),
					'soft'    => __( 'Soft', 'shortcodes-ultimate' ),
					'glass'   => __( 'Glass', 'shortcodes-ultimate' ),
					'bubbles' => __( 'Bubbles', 'shortcodes-ultimate' ),
					'noise'   => __( 'Noise', 'shortcodes-ultimate' ),
					'stroked' => __( 'Stroked', 'shortcodes-ultimate' ),
					'3d'      => __( '3D', 'shortcodes-ultimate' ),
				),
				'default' => 'default',
				'name'    => __( 'Style', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button background style preset', 'shortcodes-ultimate' ),
			),
			'background'  => array(
				'type'    => 'color',
				'values'  => array(),
				'default' => '#2D89EF',
				'name'    => __( 'Background', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button background color', 'shortcodes-ultimate' ),
			),
			'color'       => array(
				'type'    => 'color',
				'values'  => array(),
				'default' => '#FFFFFF',
				'name'    => __( 'Text color', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button text color', 'shortcodes-ultimate' ),
			),
			'size'        => array(
				'type'    => 'slider',
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
				'default' => 3,
				'name'    => __( 'Size', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button size', 'shortcodes-ultimate' ),
			),
			'wide'        => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Fluid', 'shortcodes-ultimate' ),
				'desc'    => __( 'Fluid buttons has 100% width', 'shortcodes-ultimate' ),
			),
			'center'      => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Centered', 'shortcodes-ultimate' ),
				'desc'    => __( 'Is button centered on the page', 'shortcodes-ultimate' ),
			),
			'radius'      => array(
				'type'    => 'select',
				'values'  => array(
					'auto'  => __( 'Auto', 'shortcodes-ultimate' ),
					'round' => __( 'Round', 'shortcodes-ultimate' ),
					'0'     => __( 'Square', 'shortcodes-ultimate' ),
					'5'     => '5px',
					'10'    => '10px',
					'20'    => '20px',
				),
				'default' => 'auto',
				'name'    => __( 'Radius', 'shortcodes-ultimate' ),
				'desc'    => __( 'Radius of button corners. Auto-radius calculation based on button size', 'shortcodes-ultimate' ),
			),
			'icon'        => array(
				'type'    => 'icon',
				'default' => '',
				'name'    => __( 'Icon', 'shortcodes-ultimate' ),
				'desc'    => __( 'You can upload custom icon for this button or pick a built-in icon', 'shortcodes-ultimate' ),
			),
			'icon_color'  => array(
				'type'    => 'color',
				'default' => '#FFFFFF',
				'name'    => __( 'Icon color', 'shortcodes-ultimate' ),
				'desc'    => __( 'This color will be applied to the selected icon. Does not works with uploaded icons', 'shortcodes-ultimate' ),
			),
			'text_shadow' => array(
				'type'    => 'shadow',
				'default' => 'none',
				'name'    => __( 'Text shadow', 'shortcodes-ultimate' ),
				'desc'    => __( 'Button text shadow', 'shortcodes-ultimate' ),
			),
			'desc'        => array(
				'default' => '',
				'name'    => __( 'Description', 'shortcodes-ultimate' ),
				'desc'    => __( 'Small description under button text. This option is incompatible with icon.', 'shortcodes-ultimate' ),
			),
			'download'    => array(
				'default' => '',
				'name'    => __( 'Download', 'shortcodes-ultimate' ),
				'desc'    => __( 'The download attribute specifies that the button URL will be downloaded when a user clicks on the button. The value of the attribute will be the name of the downloaded file.', 'shortcodes-ultimate' ),
			),
			'onclick'     => array(
				'default' => '',
				'name'    => __( 'onClick', 'shortcodes-ultimate' ),
				'desc'    => __( 'Advanced JavaScript code for onClick action', 'shortcodes-ultimate' ),
			),
			'rel'         => array(
				'default' => '',
				'name'    => __( 'Rel attribute', 'shortcodes-ultimate' ),
				'desc'    => __( 'Here you can add value for the rel attribute.<br>Example values: <b%value>nofollow</b>, <b%value>lightbox</b>', 'shortcodes-ultimate' ),
			),
			'title'       => array(
				'default' => '',
				'name'    => __( 'Title attribute', 'shortcodes-ultimate' ),
				'desc'    => __( 'Here you can add value for the title attribute', 'shortcodes-ultimate' ),
			),
			'id'          => array(
				'default' => '',
				'name'    => __( 'Button ID', 'shortcodes-ultimate' ),
				'desc'    => __( 'Custom value for the ID attribute', 'shortcodes-ultimate' ),
			),
			'class'       => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Button text', 'shortcodes-ultimate' ),
		'desc'     => __( 'Styled button', 'shortcodes-ultimate' ),
		'example'  => 'buttons',
		'icon'     => 'heart',
	)
);

function su_shortcode_button( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'         => get_option( 'home' ),
			'link'        => null, // 3.x
			'target'      => 'self',
			'style'       => 'default',
			'background'  => '#2D89EF',
			'color'       => '#FFFFFF',
			'dark'        => null, // 3.x
			'size'        => 3,
			'wide'        => 'no',
			'center'      => 'no',
			'radius'      => 'auto',
			'icon'        => false,
			'icon_color'  => '#FFFFFF',
			'ts_color'    => null, // Dep. 4.3.2
			'ts_pos'      => null, // Dep. 4.3.2
			'text_shadow' => 'none',
			'desc'        => '',
			'download'    => '',
			'onclick'     => '',
			'rel'         => '',
			'title'       => '',
			'id'          => '',
			'class'       => '',
		),
		$atts,
		'button'
	);

	if ( $atts['link'] !== null ) {
		$atts['url'] = $atts['link'];
	}

	if ( $atts['dark'] !== null ) {
		$atts['background'] = $atts['color'];
		$atts['color']      = ( $atts['dark'] ) ? '#000' : '#fff';
	}

	// 3.x
	if ( is_numeric( $atts['style'] ) ) {
		$atts['style'] = str_replace( array( '1', '2', '3', '4', '5' ), array( 'default', 'glass', 'bubbles', 'noise', 'stroked' ), $atts['style'] );
	}

	// Prepare vars
	$a_css     = array();
	$span_css  = array();
	$img_css   = array();
	$small_css = array();
	$radius    = '0px';
	$before    = $after = '';

	// Text shadow values
	$shadows = array(
		'none'         => '0 0',
		'top'          => '0 -1px',
		'right'        => '1px 0',
		'bottom'       => '0 1px',
		'left'         => '-1px 0',
		'top-right'    => '1px -1px',
		'top-left'     => '-1px -1px',
		'bottom-right' => '1px 1px',
		'bottom-left'  => '-1px 1px',
	);

	// Sanitize the size value
	$atts['size'] = intval( $atts['size'] );

	// Common styles for button
	$styles = array(
		'size'     => round( ( $atts['size'] + 7 ) * 1.3 ),
		'ts_color' => ( $atts['ts_color'] === 'light' ) ? su_adjust_lightness( $atts['background'], 50 ) : su_adjust_lightness( $atts['background'], -40 ),
		'ts_pos'   => ( $atts['ts_pos'] !== null ) ? $shadows[ $atts['ts_pos'] ] : $shadows['none'],
	);

	// Calculate border-radius
	if ( $atts['radius'] == 'auto' ) {
		$radius = round( $atts['size'] + 2 ) . 'px';
	} elseif ( $atts['radius'] == 'round' ) {
		$radius = round( ( ( $atts['size'] * 2 ) + 2 ) * 2 + $styles['size'] ) . 'px';
	} elseif ( is_numeric( $atts['radius'] ) ) {
		$radius = intval( $atts['radius'] ) . 'px';
	}

	// CSS rules for <a> tag
	$a_rules = array(
		'color'                 => $atts['color'],
		'background-color'      => $atts['background'],
		'border-color'          => su_adjust_lightness( $atts['background'], -20 ),
		'border-radius'         => $radius,
		'-moz-border-radius'    => $radius,
		'-webkit-border-radius' => $radius,
	);

	// CSS rules for <span> tag
	$span_rules = array(
		'color'                 => $atts['color'],
		'padding'               => ( $atts['icon'] ) ? round( ( $atts['size'] ) / 2 + 4 ) . 'px ' . round( $atts['size'] * 2 + 10 ) . 'px' : '0px ' . round( $atts['size'] * 2 + 10 ) . 'px',
		'font-size'             => $styles['size'] . 'px',
		'line-height'           => ( $atts['icon'] ) ? round( $styles['size'] * 1.5 ) . 'px' : round( $styles['size'] * 2 ) . 'px',
		'border-color'          => su_adjust_lightness( $atts['background'], 30 ),
		'border-radius'         => $radius,
		'-moz-border-radius'    => $radius,
		'-webkit-border-radius' => $radius,
		'text-shadow'           => $styles['ts_pos'] . ' 1px ' . $styles['ts_color'],
		'-moz-text-shadow'      => $styles['ts_pos'] . ' 1px ' . $styles['ts_color'],
		'-webkit-text-shadow'   => $styles['ts_pos'] . ' 1px ' . $styles['ts_color'],
	);

	// Apply new text-shadow value
	if ( $atts['ts_color'] === null && $atts['ts_pos'] === null ) {
		$span_rules['text-shadow']         = $atts['text_shadow'];
		$span_rules['-moz-text-shadow']    = $atts['text_shadow'];
		$span_rules['-webkit-text-shadow'] = $atts['text_shadow'];
	}

	// CSS rules for <img> tag
	$img_rules = array(
		'width'  => round( $styles['size'] * 1.5 ) . 'px',
		'height' => round( $styles['size'] * 1.5 ) . 'px',
	);

	// CSS rules for <small> tag
	$small_rules = array(
		'padding-bottom' => round( ( $atts['size'] ) / 2 + 4 ) . 'px',
		'color'          => $atts['color'],
	);

	// Create style attr value for <a> tag
	foreach ( $a_rules as $a_rule => $a_value ) {
		$a_css[] = $a_rule . ':' . $a_value;
	}

	// Create style attr value for <span> tag
	foreach ( $span_rules as $span_rule => $span_value ) {
		$span_css[] = $span_rule . ':' . $span_value;
	}

	// Create style attr value for <img> tag
	foreach ( $img_rules as $img_rule => $img_value ) {
		$img_css[] = $img_rule . ':' . $img_value;
	}

	// Create style attr value for <img> tag
	foreach ( $small_rules as $small_rule => $small_value ) {
		$small_css[] = $small_rule . ':' . $small_value;
	}

	// Prepare button classes
	$classes = array( 'su-button', 'su-button-style-' . $atts['style'] );

	// Additional classes
	if ( $atts['class'] ) {
		$classes[] = $atts['class'];
	}

	// Wide class
	if ( $atts['wide'] === 'yes' ) {
		$classes[] = 'su-button-wide';
	}

	// Prepare icon
	if ( $atts['icon'] ) {

		if ( strpos( $atts['icon'], 'icon:' ) !== false ) {

			$icon = '<i class="sui sui-' . esc_attr( trim( str_replace( 'icon:', '', $atts['icon'] ) ) ) . '" style="font-size:' . $styles['size'] . 'px;color:' . esc_attr( $atts['icon_color'] ) . '"></i>';

			su_query_asset( 'css', 'su-icons' );

		} else {
			$icon = '<img src="' . esc_attr( $atts['icon'] ) . '" alt="' . esc_attr( $content ) . '" style="' . implode( ';', $img_css ) . '" />';
		}

	} else {
		$icon = '';
	}

	// Prepare <small> with description
	$desc = $atts['desc']
		? '<small style="' . esc_attr( implode( ';', $small_css ) ) . '">' . su_do_attribute( $atts['desc'] ) . '</small>'
		: '';

	// Wrap with div if button centered
	if ( $atts['center'] === 'yes' ) {
		$before .= '<div class="su-button-center">';
		$after  .= '</div>';
	}

	// Replace icon marker in content,
	// add float-icon class to rearrange margins
	if ( strpos( $content, '%icon%' ) !== false ) {
		$content   = str_replace( '%icon%', $icon, $content );
		$classes[] = 'su-button-float-icon';
	}

	// Button text has no icon marker, append icon to begin of the text
	else {
		$content = $icon . ' ' . $content;
	}

	// Prepare onclick action
	if ( $atts['onclick'] && ! su_is_unsafe_features_enabled() ) {

		return su_error_message(
			'Button',
			sprintf(
				'%s.<br><a href="https://getshortcodes.com/docs/unsafe-features/" target="_blank">%s</a>',
				// translators: do not translate the <b>onclick</b> part, the <b>Unsafe features</b> must be translated
				__( 'The <b>onclick</b> attribute cannot be used while <b>Unsafe features</b> option is turned off', 'shortcodes-ultimate' ),
				__( 'Learn more', 'shortcodes-ultimate' )
			)
		);

	}

	if ( $atts['onclick'] ) {
		$atts['onclick'] = ' onClick="' . $atts['onclick'] . '"';
	}

	// Set rel attribute to `noopener noreferrer` if it's empty and target=blank
	if ( 'blank' === $atts['target'] && '' === $atts['rel'] ) {
		$atts['rel'] = 'noopener noreferrer';
	}

	// Prepare download attribute
	$atts['download'] = $atts['download']
		? ' download="' . esc_attr( $atts['download'] ) . '"'
		: '';

	// Prepare rel attribute
	$atts['rel'] = $atts['rel']
		? ' rel="' . esc_attr( $atts['rel'] ) . '"'
		: '';

	// Prepare title attribute
	$atts['title'] = $atts['title']
		? ' title="' . esc_attr( su_do_attribute( $atts['title'] ) ) . '"'
		: '';

	// Add ID attribute
	$atts['id'] = ! empty( $atts['id'] )
		? sprintf( ' id="%s"', esc_attr( $atts['id'] ) )
		: '';

	su_query_asset( 'css', 'su-shortcodes' );

	return $before . '<a href="' . esc_attr( su_do_attribute( $atts['url'] ) ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( implode( ';', $a_css ) ) . '" target="_' . esc_attr( $atts['target'] ) . '"' . $atts['onclick'] . $atts['rel'] . $atts['title'] . $atts['id'] . $atts['download'] . '><span style="' . esc_attr( implode( ';', $span_css ) ) . '">' . do_shortcode( stripcslashes( $content ) ) . $desc . '</span></a>' . $after;

}
