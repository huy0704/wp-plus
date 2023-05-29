<?php

su_add_shortcode(
	array(
		'id'             => 'tabs',
		'callback'       => 'su_shortcode_tabs',
		'name'           => __( 'Tabs', 'shortcodes-ultimate' ),
		'type'           => 'wrap',
		'group'          => 'box',
		'required_child' => 'tab',
		'desc'           => __( 'Tabs container', 'shortcodes-ultimate' ),
		'icon'           => 'list-alt',
		'image'          => su_get_plugin_url() . 'admin/images/shortcodes/tabs.svg',
		'atts'           => array(
			'style'         => array(
				'type'    => 'select',
				'values'  => su_get_available_styles_for( 'tabs' ),
				'default' => 'default',
				'name'    => __( 'Style', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose style for this tabs', 'shortcodes-ultimate' ),
			),
			'active'        => array(
				'type'    => 'number',
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 1,
				'name'    => __( 'Active tab', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select which tab is open by default', 'shortcodes-ultimate' ),
			),
			'vertical'      => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Vertical', 'shortcodes-ultimate' ),
				'desc'    => __( 'Align tabs vertically', 'shortcodes-ultimate' ),
			),
			'mobile'        => array(
				'type'    => 'select',
				'values'  => array(
					'stack'   => __( 'Stack – tab handles will stack vertically', 'shortcodes-ultimate' ),
					'desktop' => __( 'Desktop – tabs will be displayed as on the desktop', 'shortcodes-ultimate' ),
					'scroll'  => __( 'Scroll – tab bar will be scrollable horizontally', 'shortcodes-ultimate' ),
				),
				'default' => 'stack',
				'name'    => __( 'Appearance on mobile devices', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option controls how shortcode will look and function on mobile devices.', 'shortcodes-ultimate' ),
			),
			'anchor_in_url' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Anchor in URL', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option specifies whether an anchor will be added to page URL after clicking a tab', 'shortcodes-ultimate' ),
			),
			'class'         => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'        => array(
			'id'     => 'tab',
			'number' => 3,
		),
	)
);

su_add_shortcode(
	array(
		'id'              => 'tab',
		'callback'        => 'su_shortcode_tab',
		'name'            => __( 'Tab', 'shortcodes-ultimate' ),
		'type'            => 'wrap',
		'group'           => 'box',
		'required_parent' => 'tabs',
		'content'         => __( 'Tab content', 'shortcodes-ultimate' ),
		'desc'            => __( 'Single tab', 'shortcodes-ultimate' ),
		'note'            => __( 'Did you know that you need to wrap single tabs with [tabs] shortcode?', 'shortcodes-ultimate' ),
		'icon'            => 'list-alt',
		'image'           => su_get_plugin_url() . 'admin/images/shortcodes/tab.svg',
		'atts'            => array(
			'title'    => array(
				'default' => __( 'Tab name', 'shortcodes-ultimate' ),
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'Tab title', 'shortcodes-ultimate' ),
			),
			'disabled' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Disabled', 'shortcodes-ultimate' ),
				'desc'    => __( 'Is this tab disabled', 'shortcodes-ultimate' ),
			),
			'anchor'   => array(
				'default' => '',
				'name'    => __( 'Anchor', 'shortcodes-ultimate' ),
				'desc'    => __( 'You can use unique anchor for this tab to access it with hash in page url. For example: use <b%value>Hello</b> and then navigate to url like http://example.com/page-url#Hello. This tab will be activated and scrolled in', 'shortcodes-ultimate' ),
			),
			'url'      => array(
				'default' => '',
				'name'    => __( 'URL', 'shortcodes-ultimate' ),
				'desc'    => __( 'Link tab to any webpage. Use full URL to turn the tab title into link', 'shortcodes-ultimate' ),
			),
			'target'   => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'blank',
				'name'    => __( 'Link target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose how to open the custom tab link', 'shortcodes-ultimate' ),
			),
			'class'    => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
	)
);

$shortcodes_ultimate_global_tabs       = array();
$shortcodes_ultimate_global_tabs_count = 0;

function su_shortcode_tabs( $atts = null, $content = null ) {

	global $shortcodes_ultimate_global_tabs, $shortcodes_ultimate_global_tabs_count;

	$atts = shortcode_atts(
		array(
			'active'        => 1,
			'vertical'      => 'no',
			'style'         => 'default', // 3.x
			'mobile'        => 'stack',
			'scroll_offset' => 0,
			'anchor_in_url' => 'no',
			'class'         => '',
		),
		$atts,
		'tabs'
	);

	if ( '3' === $atts['style'] ) {
		$atts['vertical'] = 'yes';
	}

	if ( 'yes' === $atts['vertical'] ) {
		$atts['class'] .= ' su-tabs-vertical';
	}

	do_shortcode( $content );

	$tabs  = array();
	$panes = array();

	if ( ! is_array( $shortcodes_ultimate_global_tabs ) ) {
		return;
	}

	if ( $shortcodes_ultimate_global_tabs_count < $atts['active'] ) {
		$atts['active'] = $shortcodes_ultimate_global_tabs_count;
	}

	foreach ( $shortcodes_ultimate_global_tabs as $tab ) {

		$tabs[] = '<span class="' . su_get_css_class( $tab ) . $tab['disabled'] . '"' . $tab['anchor'] . $tab['url'] . $tab['target'] . ' tabindex="0" role="button">' . su_do_attribute( $tab['title'] ) . '</span>';

		$panes[] = '<div class="su-tabs-pane su-u-clearfix su-u-trim' . su_get_css_class( $tab ) . '" data-title="' . esc_attr( $tab['title'] ) . '">' . $tab['content'] . '</div>';

	}

	$atts['mobile'] = sanitize_key( $atts['mobile'] );

	$output = '<div class="su-tabs su-tabs-style-' . esc_attr( $atts['style'] ) . ' su-tabs-mobile-' . esc_attr( $atts['mobile'] ) . su_get_css_class( $atts ) . '" data-active="' . esc_attr( $atts['active'] ) . '" data-scroll-offset="' . intval( $atts['scroll_offset'] ) . '" data-anchor-in-url="' . sanitize_key( $atts['anchor_in_url'] ) . '"><div class="su-tabs-nav">' . implode( '', $tabs ) . '</div><div class="su-tabs-panes">' . implode( "\n", $panes ) . '</div></div>';

	// Reset tabs
	$shortcodes_ultimate_global_tabs       = array();
	$shortcodes_ultimate_global_tabs_count = 0;

	su_query_asset( 'css', 'su-shortcodes' );
	su_query_asset( 'js', 'jquery' );
	su_query_asset( 'js', 'su-shortcodes' );

	return $output;

}

function su_shortcode_tab( $atts = null, $content = null ) {

	global $shortcodes_ultimate_global_tabs, $shortcodes_ultimate_global_tabs_count;

	$atts = shortcode_atts(
		array(
			'title'    => __( 'Tab title', 'shortcodes-ultimate' ),
			'disabled' => 'no',
			'anchor'   => '',
			'url'      => '',
			'target'   => 'blank',
			'class'    => '',
		),
		$atts,
		'tab'
	);

	$x = $shortcodes_ultimate_global_tabs_count;

	$shortcodes_ultimate_global_tabs[ $x ] = array(
		'title'    => $atts['title'],
		'content'  => do_shortcode( $content ),
		'disabled' => 'yes' === $atts['disabled'] ? ' su-tabs-disabled' : '',
		'anchor'   => $atts['anchor'] ? ' data-anchor="' . str_replace( array( ' ', '#' ), '', esc_attr( $atts['anchor'] ) ) . '"' : '',
		'url'      => ' data-url="' . esc_attr( $atts['url'] ) . '"',
		'target'   => ' data-target="' . esc_attr( $atts['target'] ) . '"',
		'class'    => $atts['class'],
	);

	$shortcodes_ultimate_global_tabs_count++;

}
