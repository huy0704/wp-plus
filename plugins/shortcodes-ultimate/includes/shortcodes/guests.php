<?php

su_add_shortcode(
	array(
		'id'       => 'guests',
		'callback' => 'su_shortcode_guests',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/guests.svg',
		'name'     => __( 'Guests', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'other',
		'atts'     => array(
			'class' => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'This content will be available only for non-logged visitors', 'shortcodes-ultimate' ),
		'desc'     => __( 'Content for guests only', 'shortcodes-ultimate' ),
		'icon'     => 'user',
	)
);

function su_shortcode_guests( $atts = null, $content = null ) {
	$atts   = shortcode_atts( array( 'class' => '' ), $atts, 'guests' );
	$return = '';
	if ( ! is_user_logged_in() && ! is_null( $content ) ) {
		su_query_asset( 'css', 'su-shortcodes' );
		$return = '<div class="su-guests' . su_get_css_class( $atts ) . '">' . do_shortcode( $content ) . '</div>';
	}
	return $return;
}
