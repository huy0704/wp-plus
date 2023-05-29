<?php

su_add_shortcode(
	array(
		'id'       => 'private',
		'callback' => 'su_shortcode_private',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/private.svg',
		'name'     => __( 'Private', 'shortcodes-ultimate' ),
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
		'content'  => __( 'Private note text', 'shortcodes-ultimate' ),
		'desc'     => __( 'Private note for post authors. Any content wrapped with this shortcode will only be visible to post authors (users with publish_posts capability).', 'shortcodes-ultimate' ),
		'icon'     => 'lock',
	)
);

function su_shortcode_private( $atts = null, $content = null ) {

	$atts = shortcode_atts( array( 'class' => '' ), $atts, 'private' );

	su_query_asset( 'css', 'su-shortcodes' );

	return ( current_user_can( 'publish_posts' ) )
		? '<div class="su-private' . su_get_css_class( $atts ) . '"><div class="su-private-shell">' . do_shortcode( $content ) . '</div></div>'
		: '';

}
