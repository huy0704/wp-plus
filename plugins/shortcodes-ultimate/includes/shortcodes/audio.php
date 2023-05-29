<?php

su_add_shortcode(
	array(
		'id'       => 'audio',
		'callback' => 'su_shortcode_audio',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/audio.svg',
		'name'     => __( 'Audio', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'media',
		'atts'     => array(
			'url'      => array(
				'type'    => 'upload',
				'default' => '',
				'name'    => __( 'File', 'shortcodes-ultimate' ),
				'desc'    => __( 'Audio file url. Supported formats: mp3, ogg', 'shortcodes-ultimate' ),
			),
			'width'    => array(
				'values'  => array(),
				'default' => '100%',
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Player width. You can specify width in percents and player will be responsive. Example values: <b%value>200px</b>, <b%value>100&#37;</b>', 'shortcodes-ultimate' ),
			),
			'autoplay' => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Autoplay', 'shortcodes-ultimate' ),
				'desc'    => __( 'Play file automatically when page is loaded', 'shortcodes-ultimate' ),
			),
			'loop'     => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Loop', 'shortcodes-ultimate' ),
				'desc'    => __( 'Repeat when playback is ended', 'shortcodes-ultimate' ),
			),
			'class'    => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Custom audio player', 'shortcodes-ultimate' ),
		'example'  => 'media',
		'icon'     => 'play-circle',
	)
);

function su_shortcode_audio( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'      => false,
			'width'    => 'auto',
			'title'    => '',
			'autoplay' => 'no',
			'loop'     => 'no',
			'class'    => '',
		),
		$atts,
		'audio'
	);

	if ( ! $atts['url'] ) {
		return su_error_message( 'Audio', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$atts['url'] = su_do_attribute( $atts['url'] );

	$id = uniqid( 'su_audio_player_' );

	$width = ( $atts['width'] !== 'auto' )
		? 'max-width:' . $atts['width']
		: '';

	if ( ! $atts['url'] ) {
		return su_error_message( 'Audio', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	su_query_asset( 'css', 'su-shortcodes' );
	su_query_asset( 'js', 'jquery' );
	su_query_asset( 'js', 'jplayer' );
	su_query_asset( 'js', 'su-shortcodes' );

	return '<div class="su-audio' . su_get_css_class( $atts ) . '" data-id="' . $id . '" data-audio="' . esc_attr( $atts['url'] ) . '" data-swf="' . plugins_url( 'vendor/jplayer/jplayer.swf', SU_PLUGIN_FILE ) . '" data-autoplay="' . esc_attr( $atts['autoplay'] ) . '" data-loop="' . esc_attr( $atts['loop'] ) . '" style="' . esc_attr( $width ) . '"><div id="' . $id . '" class="jp-jplayer"></div><div id="' . $id . '_container" class="jp-audio"><div class="jp-type-single"><div class="jp-gui jp-interface"><div class="jp-controls"><span class="jp-play"></span><span class="jp-pause"></span><span class="jp-stop"></span><span class="jp-mute"></span><span class="jp-unmute"></span><span class="jp-volume-max"></span></div><div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div><div class="jp-current-time"></div><div class="jp-duration"></div></div><div class="jp-title">' . esc_attr( $atts['title'] ) . '</div></div></div></div>';

}
