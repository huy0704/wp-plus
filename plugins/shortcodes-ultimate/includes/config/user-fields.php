<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/user_fields',
	[
		'first_name'      => __( 'First name', 'shortcodes-ultimate' ),
		'last_name'       => __( 'Last name', 'shortcodes-ultimate' ),
		'nickname'        => __( 'Nickname', 'shortcodes-ultimate' ),
		'description'     => __( 'Description', 'shortcodes-ultimate' ),
		'locale'          => __( 'Locale', 'shortcodes-ultimate' ),
		'display_name'    => __( 'Display name', 'shortcodes-ultimate' ),
		'ID'              => __( 'ID', 'shortcodes-ultimate' ),
		'user_nicename'   => __( 'Nice name', 'shortcodes-ultimate' ),
		'user_url'        => __( 'URL', 'shortcodes-ultimate' ),
		'user_registered' => __( 'Registered', 'shortcodes-ultimate' ),
		'user_status'     => __( 'Status', 'shortcodes-ultimate' ),
	]
);
