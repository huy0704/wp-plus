<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/default_settings',
	array(
		'su_option_custom-formatting'    => 'on',
		'su_option_skip'                 => 'on',
		'su_option_prefix'               => 'su_',
		'su_option_custom-css'           => '',
		'su_option_supported_blocks'     => array(
			'core/paragraph',
			'core/shortcode',
			'core/freeform',
		),
		'su_option_generator_access'     => 'manage_options',
		'su_option_enable_shortcodes_in' => array( 'term_description' ),
		'su_option_hide_deprecated'      => 'on',
		'su_option_unsafe_features'      => 'on',
	)
);
