<?php

su_add_shortcode(
	array(
		'id'       => 'members',
		'callback' => 'su_shortcode_members',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/members.svg',
		'name'     => __( 'Members', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'other',
		'atts'     => array(
			'message'    => array(
				'default' => __( 'This content is for registered users only. Please %login%.', 'shortcodes-ultimate' ),
				'name'    => __( 'Message', 'shortcodes-ultimate' ),
				'desc'    => __( 'Message for not logged users', 'shortcodes-ultimate' ),
			),
			'color'      => array(
				'type'    => 'color',
				'default' => '#ffcc00',
				'name'    => __( 'Box color', 'shortcodes-ultimate' ),
				'desc'    => __( 'This color will applied only to box for not logged users', 'shortcodes-ultimate' ),
			),
			'login_text' => array(
				'default' => __( 'login', 'shortcodes-ultimate' ),
				'name'    => __( 'Login link text', 'shortcodes-ultimate' ),
				'desc'    => __( 'Text for the login link', 'shortcodes-ultimate' ),
			),
			'login_url'  => array(
				'default' => '',
				'name'    => __( 'Login link url', 'shortcodes-ultimate' ),
				'desc'    => __( 'Login link url', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Content for logged members', 'shortcodes-ultimate' ),
		'desc'     => __( 'Content for logged in members only', 'shortcodes-ultimate' ),
		'icon'     => 'lock',
	)
);

function su_shortcode_members( $atts = null, $content = null ) {

	$atts = su_parse_shortcode_atts(
		'members',
		$atts,
		array(
			'style' => null,
			'login' => null,
		)
	);

	if ( empty( $atts['login_url'] ) ) {
		$atts['login_url'] = wp_login_url();
	}

	// 3.x
	if ( null !== $atts['style'] ) {
		$atts['color'] = str_replace( array( '0', '1', '2' ), array( '#fff', '#FFFF29', '#1F9AFF' ), $atts['style'] );
	}

	if ( is_feed() ) {
		return;
	}

	if ( is_user_logged_in() ) {
		return do_shortcode( $content );
	}

	// 3.x
	if ( null !== $atts['login'] && '0' === $atts['login'] ) {
		return;
	}

	$login = '<a href="' . esc_attr( $atts['login_url'] ) . '">' . $atts['login_text'] . '</a>';

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-members' . su_get_css_class( $atts ) . '" style="background-color:' . su_adjust_brightness( $atts['color'], 50 ) . ';border-color:' . su_adjust_brightness( $atts['color'], -20 ) . ';color:' . su_adjust_brightness( $atts['color'], -60 ) . '">' . str_replace( '%login%', $login, su_do_attribute( $atts['message'] ) ) . '</div>';

}
