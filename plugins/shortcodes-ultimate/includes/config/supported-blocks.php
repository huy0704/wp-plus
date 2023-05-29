<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/supported_blocks',
	array(
		'core/paragraph' => __( 'Paragraph', 'shortcodes-ultimate' ),
		'core/shortcode' => __( 'Shortcode', 'shortcodes-ultimate' ),
		'core/freeform'  => __( 'Classic', 'shortcodes-ultimate' ),
	)
);
