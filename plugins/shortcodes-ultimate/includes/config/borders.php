<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/data/borders',
	array(
		'none'   => __( 'None', 'shortcodes-ultimate' ),
		'solid'  => __( 'Solid', 'shortcodes-ultimate' ),
		'dotted' => __( 'Dotted', 'shortcodes-ultimate' ),
		'dashed' => __( 'Dashed', 'shortcodes-ultimate' ),
		'double' => __( 'Double', 'shortcodes-ultimate' ),
		'groove' => __( 'Groove', 'shortcodes-ultimate' ),
		'ridge'  => __( 'Ridge', 'shortcodes-ultimate' ),
	)
);
