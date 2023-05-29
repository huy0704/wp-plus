<?php

su_add_shortcode(
	array(
		'id'       => 'youtube_advanced',
		'callback' => 'su_shortcode_youtube_advanced',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/youtube_advanced.svg',
		'name'     => __( 'YouTube advanced', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'media',
		'atts'     => array(
			'url'            => array(
				'values'  => array(),
				'default' => '',
				'name'    => __( 'Url', 'shortcodes-ultimate' ),
				'desc'    => __( 'Url of YouTube page with video. Ex: http://youtube.com/watch?v=XXXXXX', 'shortcodes-ultimate' ),
			),
			'playlist'       => array(
				'default' => '',
				'name'    => __( 'Playlist', 'shortcodes-ultimate' ),
				'desc'    => __( 'Value is a comma-separated list of video IDs to play. If you specify a value, the first video that plays will be the VIDEO_ID specified in the URL path, and the videos specified in the playlist parameter will play thereafter', 'shortcodes-ultimate' ),
			),
			'width'          => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Player width', 'shortcodes-ultimate' ),
			),
			'height'         => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 400,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Player height', 'shortcodes-ultimate' ),
			),
			'responsive'     => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Ignore width and height parameters and make player responsive', 'shortcodes-ultimate' ),
			),
			'controls'       => array(
				'type'    => 'select',
				'values'  => array(
					'no'  => __( '0 - Hide controls', 'shortcodes-ultimate' ),
					'yes' => __( '1 - Show controls', 'shortcodes-ultimate' ),
					'alt' => __( '2 - Show controls when playback is started', 'shortcodes-ultimate' ),
				),
				'default' => 'yes',
				'name'    => __( 'Controls', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter indicates whether the video player controls will display', 'shortcodes-ultimate' ),
			),
			'autohide'       => array(
				'type'    => 'select',
				'values'  => array(
					'no'  => __( '0 - Do not hide controls', 'shortcodes-ultimate' ),
					'yes' => __( '1 - Hide all controls on mouse out', 'shortcodes-ultimate' ),
					'alt' => __( '2 - Hide progress bar on mouse out', 'shortcodes-ultimate' ),
				),
				'default' => 'alt',
				'name'    => __( 'Autohide', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter indicates whether the video controls will automatically hide after a video begins playing', 'shortcodes-ultimate' ),
			),
			'autoplay'       => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Autoplay', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter specifies whether the video will automatically start to play when the player loads. Please note, in modern browsers autoplay option only works with the mute option enabled', 'shortcodes-ultimate' ),
			),
			'mute'           => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Mute', 'shortcodes-ultimate' ),
				'desc'    => __( 'Mute the player', 'shortcodes-ultimate' ),
			),
			'loop'           => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Loop', 'shortcodes-ultimate' ),
				'desc'    => __( 'Setting of YES will cause the player to play the initial video again and again', 'shortcodes-ultimate' ),
			),
			'rel'            => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Display related videos from the same channel', 'shortcodes-ultimate' ),
				'desc'    => __( 'If this parameter is set to YES, related videos will come from the same channel as the video that was just played.', 'shortcodes-ultimate' ),
			),
			'fs'             => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Show full-screen button', 'shortcodes-ultimate' ),
				'desc'    => __( 'Setting this parameter to NO prevents the fullscreen button from displaying', 'shortcodes-ultimate' ),
			),
			'modestbranding' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => 'modestbranding',
				'desc'    => __( 'This parameter lets you use a YouTube player that does not show a YouTube logo. Set the parameter value to YES to prevent the YouTube logo from displaying in the control bar. Note that a small YouTube text label will still display in the upper-right corner of a paused video when the user\'s mouse pointer hovers over the player', 'shortcodes-ultimate' ),
			),
			'theme'          => array(
				'type'    => 'select',
				'values'  => array(
					'dark'  => __( 'Dark theme', 'shortcodes-ultimate' ),
					'light' => __( 'Light theme', 'shortcodes-ultimate' ),
				),
				'default' => 'dark',
				'name'    => __( 'Theme', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter indicates whether the embedded player will display player controls (like a play button or volume control) within a dark or light control bar', 'shortcodes-ultimate' ),
			),
			'wmode'          => array(
				'default' => '',
				'name'    => __( 'WMode', 'shortcodes-ultimate' ),
				// Translators: %1$s, %2$s - example values for shortcode attribute
				'desc'    => sprintf( __( 'Here you can specify wmode value for the embed URL.<br>Example values: %1$s, %2$s', 'shortcodes-ultimate' ), '<b%value>transparent</b>', '<b%value>opaque</b>' ),
			),
			'playsinline'    => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Plays inline', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter controls whether videos play inline or fullscreen in an HTML5 player on iOS', 'shortcodes-ultimate' ),
			),
			'title'          => array(
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A brief description of the embedded content (used by screenreaders)', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'          => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'YouTube video player with advanced settings', 'shortcodes-ultimate' ),
		'example'  => 'media',
		'icon'     => 'youtube-play',
	)
);

function su_shortcode_youtube_advanced( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'            => false,
			'width'          => 600,
			'height'         => 400,
			'responsive'     => 'yes',
			'autohide'       => 'alt',
			'autoplay'       => 'no',
			'mute'           => 'no',
			'controls'       => 'yes',
			'fs'             => 'yes',
			'loop'           => 'no',
			'modestbranding' => 'no',
			'playlist'       => '',
			'rel'            => 'yes',
			'showinfo'       => 'yes',
			'theme'          => 'dark',
			'wmode'          => '',
			'playsinline'    => 'no',
			'title'          => '',
			'class'          => '',
		),
		$atts,
		'youtube_advanced'
	);

	if ( ! $atts['url'] ) {
		return su_error_message( 'YouTube Advanced', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$atts['url'] = su_do_attribute( $atts['url'] );

	$video_id = preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $atts['url'], $match )
		? $match[1]
		: false;

	if ( ! $video_id ) {
		return su_error_message( 'YouTube Advanced', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	if ( 'alt' === $atts['controls'] ) {
		$atts['controls'] = 'yes';
	}

	$url_params = array();
	$yt_options = array(
		'autohide',
		'autoplay',
		'mute',
		'controls',
		'fs',
		'loop',
		'modestbranding',
		'playlist',
		'rel',
		'showinfo',
		'theme',
		'wmode',
		'playsinline',
	);

	foreach ( $yt_options as $yt_option ) {
		$url_params[ $yt_option ] = str_replace( array( 'no', 'yes', 'alt' ), array( '0', '1', '2' ), $atts[ $yt_option ] );
	}

	if ( '1' === $url_params['loop'] && '' === $url_params['playlist'] ) {
		$url_params['playlist'] = $video_id;
	}

	if ( empty( $url_params['playlist'] ) ) {
		unset( $url_params['playlist'] );
	}

	$url_params = http_build_query( $url_params );

	$domain = strpos( $atts['url'], 'youtube-nocookie.com' ) !== false
		? 'www.youtube-nocookie.com'
		: 'www.youtube.com';

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-youtube su-u-responsive-media-' . esc_attr( $atts['responsive'] ) . su_get_css_class( $atts ) . '"><iframe width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" src="https://' . $domain . '/embed/' . $video_id . '?' . esc_attr( $url_params ) . '" frameborder="0" allowfullscreen allow="autoplay; encrypted-media; picture-in-picture" title="' . esc_attr( $atts['title'] ) . '"></iframe></div>';

}
