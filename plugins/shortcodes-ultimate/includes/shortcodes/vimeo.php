<?php

su_add_shortcode(
	array(
		'id'       => 'vimeo',
		'callback' => 'su_shortcode_vimeo',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/vimeo.svg',
		'name'     => __( 'Vimeo', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'media',
		'atts'     => array(
			'url'        => array(
				'default' => '',
				'name'    => __( 'Url', 'shortcodes-ultimate' ),
				'desc'    => __( 'Url of Vimeo page with video', 'shortcodes-ultimate' ),
			),
			'width'      => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Player width', 'shortcodes-ultimate' ),
			),
			'height'     => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 400,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Player height', 'shortcodes-ultimate' ),
			),
			'responsive' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Ignore width and height parameters and make player responsive', 'shortcodes-ultimate' ),
			),
			'autoplay'   => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Autoplay', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter specifies whether the video will automatically start to play when the player loads. Please note, in modern browsers autoplay option only works with the mute option enabled', 'shortcodes-ultimate' ),
			),
			'mute'       => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Mute', 'shortcodes-ultimate' ),
				'desc'    => __( 'Mute the player', 'shortcodes-ultimate' ),
			),
			'dnt'        => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Do not track', 'shortcodes-ultimate' ),
				'desc'    => __( 'Setting this parameter to YES will block the player from tracking any playback session data. Will have the same effect as enabling a Do Not Track header in your browser', 'shortcodes-ultimate' ),
			),
			'title'      => array(
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A brief description of the embedded content (used by screenreaders)', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'texttrack'  => array(
				'name'    => __( 'Subtitles', 'shortcodes-ultimate' ),
				'desc'    => __( 'Use language code as the value to enable subtitles. Example values: en, es', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Vimeo video', 'shortcodes-ultimate' ),
		'example'  => 'media',
		'icon'     => 'youtube-play',
	)
);

function su_shortcode_vimeo( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'        => '',
			'width'      => 600,
			'height'     => 400,
			'autoplay'   => 'no',
			'dnt'        => 'no',
			'mute'       => 'no',
			'responsive' => 'yes',
			'title'      => '',
			'texttrack'  => '',
			'class'      => '',
		),
		$atts,
		'vimeo'
	);

	if ( ! $atts['url'] ) {
		return su_error_message( 'Vimeo', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$atts['url'] = su_do_attribute( $atts['url'] );

	$video_id = preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $atts['url'], $match )
		? $match[1]
		: false;

	if ( ! $video_id ) {
		return su_error_message( 'Vimeo', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$url_params = array(
		'title'     => 0,
		'byline'    => 0,
		'portrait'  => 0,
		'color'     => 'ffffff',
		'autoplay'  => 'yes' === $atts['autoplay'] ? 1 : 0,
		'dnt'       => 'yes' === $atts['dnt'] ? 1 : 0,
		'muted'     => 'yes' === $atts['mute'] ? 1 : 0,
		'texttrack' => esc_attr( $atts['texttrack'] ),
	);

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-vimeo su-u-responsive-media-' . esc_attr( $atts['responsive'] ) . su_get_css_class( $atts ) . '"><iframe width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" src="//player.vimeo.com/video/' . $video_id . '?' . esc_attr( http_build_query( $url_params ) ) . '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen title="' . esc_attr( $atts['title'] ) . '"></iframe></div>';

}
