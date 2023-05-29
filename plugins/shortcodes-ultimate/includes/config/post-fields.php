<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/post_fields',
	[
		'ID'                => __( 'Post ID', 'shortcodes-ultimate' ),
		'post_author'       => __( 'Post author', 'shortcodes-ultimate' ),
		'post_date'         => __( 'Post date', 'shortcodes-ultimate' ),
		'post_date_gmt'     => __( 'Post date', 'shortcodes-ultimate' ) . ' GMT',
		'post_content'      => __( 'Post content (Raw)', 'shortcodes-ultimate' ),
		'the_content'       => __( 'Post content (Filtered)', 'shortcodes-ultimate' ),
		'post_title'        => __( 'Post title', 'shortcodes-ultimate' ),
		'post_excerpt'      => __( 'Post excerpt', 'shortcodes-ultimate' ),
		'post_status'       => __( 'Post status', 'shortcodes-ultimate' ),
		'comment_status'    => __( 'Comment status', 'shortcodes-ultimate' ),
		'ping_status'       => __( 'Ping status', 'shortcodes-ultimate' ),
		'post_name'         => __( 'Post name', 'shortcodes-ultimate' ),
		'post_modified'     => __( 'Post modified', 'shortcodes-ultimate' ),
		'post_modified_gmt' => __( 'Post modified', 'shortcodes-ultimate' ) . ' GMT',
		'post_parent'       => __( 'Post parent', 'shortcodes-ultimate' ),
		'guid'              => __( 'GUID', 'shortcodes-ultimate' ),
		'menu_order'        => __( 'Menu order', 'shortcodes-ultimate' ),
		'post_type'         => __( 'Post type', 'shortcodes-ultimate' ),
		'post_mime_type'    => __( 'Post mime type', 'shortcodes-ultimate' ),
		'comment_count'     => __( 'Comment count', 'shortcodes-ultimate' ),
	]
);
