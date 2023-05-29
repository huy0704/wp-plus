<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/crop_ratios',
	array(
		'21:9'  => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'21:9'
		),
		'16:9'  => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'16:9'
		),
		'16:10' => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'16:10'
		),
		'5:4'   => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'5:4'
		),
		'4:3'   => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'4:3'
		),
		'3:2'   => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'3:2'
		),
		'2:1'   => sprintf(
			'[%s] %s',
			__( 'Landscape', 'shortcodes-ultimate' ),
			'2:1'
		),
		'1:1'   => sprintf(
			'[%s] %s',
			__( 'Square', 'shortcodes-ultimate' ),
			'1:1'
		),
		'1:2'   => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'1:2'
		),
		'2:3'   => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'2:3'
		),
		'3:4'   => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'3:4'
		),
		'4:5'   => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'4:5'
		),
		'10:16' => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'10:16'
		),
		'9:16'  => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'9:16'
		),
		'9:21'  => sprintf(
			'[%s] %s',
			__( 'Portrait', 'shortcodes-ultimate' ),
			'9:21'
		),
	)
);
