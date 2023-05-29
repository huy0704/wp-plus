<?php

su_add_shortcode(
	array(
		'id'       => 'post',
		'type'     => 'single',
		'group'    => 'data',
		'callback' => 'su_shortcode_post',
		'icon'     => 'info-circle',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/post.svg',
		'name'     => __( 'Post data', 'shortcodes-ultimate' ),
		'desc'     => __( 'The utility shortcode to display various post data, like post title, status or excerpt', 'shortcodes-ultimate' ),
		'atts'     => array(
			'field'     => array(
				'type'    => 'select',
				'values'  => su_get_config( 'post-fields' ),
				'default' => 'post_title',
				'name'    => __( 'Field', 'shortcodes-ultimate' ),
				'desc'    => __( 'Post data field name', 'shortcodes-ultimate' ),
			),
			'default'   => array(
				'default' => '',
				'name'    => __( 'Default', 'shortcodes-ultimate' ),
				'desc'    => __( 'This text will be shown if data is not found', 'shortcodes-ultimate' ),
			),
			'before'    => array(
				'default' => '',
				'name'    => __( 'Before', 'shortcodes-ultimate' ),
				'desc'    => __( 'This content will be shown before the value', 'shortcodes-ultimate' ),
			),
			'after'     => array(
				'default' => '',
				'name'    => __( 'After', 'shortcodes-ultimate' ),
				'desc'    => __( 'This content will be shown after the value', 'shortcodes-ultimate' ),
			),
			'post_id'   => array(
				'default' => '',
				'name'    => __( 'Post ID', 'shortcodes-ultimate' ),
				'desc'    => __( 'You can specify custom post ID. Post slug is also allowed. Leave this field empty to use ID of the current post. Current post ID may not work in Live Preview mode', 'shortcodes-ultimate' ),
			),
			'post_type' => array(
				'type'    => 'post_type',
				'default' => 'post',
				'name'    => __( 'Post type', 'shortcodes-ultimate' ),
				'desc'    => __( 'Post type of the post you want to display the data from', 'shortcodes-ultimate' ),
			),
			'filter'    => array(
				'default' => '',
				'name'    => __( 'Filter', 'shortcodes-ultimate' ),
				'desc'    => __( 'You can apply custom filter to the retrieved value. Enter here function name. Your function must accept one argument and return modified value. Name of your function must include word <b>filter</b>. Example function: ', 'shortcodes-ultimate' ) . "<br /><pre><code style='display:block;padding:5px'>function my_custom_filter( \$value ) {\n\treturn 'Value is: ' . \$value;\n}</code></pre>",
			),
		),
	)
);

function su_shortcode_post( $atts = null, $content = null ) {
	$atts = su_parse_shortcode_atts(
		'post',
		$atts,
		array( 'filter_content' => 'no' )
	);

	if ( ! in_array( $atts['field'], array_keys( su_get_config( 'post-fields' ) ), true ) ) {
		return su_error_message(
			'Post',
			sprintf(
				'%s. <a href="https://getshortcodes.com/docs/post-data/#post-field-is-not-allowed">%s</a>',
				__( 'field is not allowed', 'shortcodes-ultimate' ),
				__( 'Learn more', 'shortcodes-ultimate' )
			)
		);
	}

	if ( ! $atts['post_id'] ) {
		$atts['post_id'] = get_the_ID();
	}

	if ( ! $atts['post_id'] ) {
		return su_error_message(
			'Post',
			__( 'invalid post ID', 'shortcodes-ultimate' )
		);
	}

	if ( 'the_content' === $atts['field'] ) {
		$atts['field']          = 'post_content';
		$atts['filter_content'] = 'yes';
	}

	$data = '';
	$post = su_is_positive_number( $atts['post_id'] )
		? get_post( $atts['post_id'] )
		: get_page_by_path( $atts['post_id'], OBJECT, $atts['post_type'] );

	if ( is_a( $post, 'WP_Post' ) && ! su_current_user_can_read_post( $post->ID ) ) {
		return su_error_message(
			'Post',
			__( 'unable to display data, check the post status and password', 'shortcodes-ultimate' )
		);
	}

	if ( isset( $post->{$atts['field']} ) ) {
		$data = $post->{$atts['field']};
	}

	if ( 'yes' === $atts['filter_content'] ) {
		$data = apply_filters( 'the_content', $data );
	}

	$data = su_safely_apply_user_filter( $atts['filter'], $data );

	if ( empty( $data ) ) {
		$data = $atts['default'];
	}

	return $data ? $atts['before'] . $data . $atts['after'] : '';
}
