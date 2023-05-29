<?php

su_add_shortcode(
	array(
		'id'       => 'template',
		'callback' => 'su_shortcode_template',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/template.svg',
		'name'     => __( 'Template', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'other',
		'atts'     => array(
			'name' => array(
				'default' => '',
				'name'    => __( 'Template name', 'shortcodes-ultimate' ),
				// translators: %1$s, %2$s, %3$s – example values for the shortcode attribute
				'desc'    => sprintf( __( 'Use template file name (with optional .php extension). If you need to use templates from theme sub-folder, use relative path. Example values: %1$s, %2$s, %3$s', 'shortcodes-ultimate' ), '<b%value>page</b>', '<b%value>page.php</b>', '<b%value>includes/page.php</b>' ),
			),
		),
		'desc'     => __( 'Theme template', 'shortcodes-ultimate' ),
		'icon'     => 'puzzle-piece',
	)
);

function su_shortcode_template( $atts = null, $content = null ) {

	$atts = shortcode_atts( array( 'name' => '' ), $atts, 'template' );

	if ( ! $atts['name'] ) {
		return su_error_message( 'Template', __( 'please specify template name', 'shortcodes-ultimate' ) );
	}

	if ( ! su_is_valid_template_name( $atts['name'] ) ) {
		return su_error_message( 'Template', __( 'invalid template name', 'shortcodes-ultimate' ) );
	}

	ob_start();
	get_template_part( su_set_file_extension( $atts['name'], false ) );
	$output = ob_get_clean();

	return $output;

}
