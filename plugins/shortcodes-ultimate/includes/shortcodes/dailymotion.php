<?php

su_add_shortcode(
	array(
		'id'       => 'dailymotion',
		'callback' => 'su_shortcode_dailymotion',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/dailymotion.svg',
		'name'     => __( 'Dailymotion', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'media',
		'atts'     => array(
			'url'        => array(
				'default' => '',
				'name'    => __( 'Url', 'shortcodes-ultimate' ),
				'desc'    => __( 'Url of Dailymotion page with video', 'shortcodes-ultimate' ),
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
				'desc'    => __( 'Start the playback of the video automatically after the player load. May not work on some mobile OS versions', 'shortcodes-ultimate' ),
			),
			'background' => array(
				'type'    => 'color',
				'default' => '#FFC300',
				'name'    => __( 'Background color', 'shortcodes-ultimate' ),
				'desc'    => __( 'HTML color of the background of controls elements', 'shortcodes-ultimate' ),
			),
			'foreground' => array(
				'type'    => 'color',
				'default' => '#F7FFFD',
				'name'    => __( 'Foreground color', 'shortcodes-ultimate' ),
				'desc'    => __( 'HTML color of the foreground of controls elements', 'shortcodes-ultimate' ),
			),
			'highlight'  => array(
				'type'    => 'color',
				'default' => '#171D1B',
				'name'    => __( 'Highlight color', 'shortcodes-ultimate' ),
				'desc'    => __( 'HTML color of the controls elements\' highlights', 'shortcodes-ultimate' ),
			),
			'logo'       => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Show logo', 'shortcodes-ultimate' ),
				'desc'    => __( 'Allows to hide or show the Dailymotion logo', 'shortcodes-ultimate' ),
			),
			'quality'    => array(
				'type'    => 'select',
				'values'  => array(
					'240'  => '240',
					'380'  => '380',
					'480'  => '480',
					'720'  => '720',
					'1080' => '1080',
				),
				'default' => '380',
				'name'    => __( 'Quality', 'shortcodes-ultimate' ),
				'desc'    => __( 'Determines the quality that must be played by default if available', 'shortcodes-ultimate' ),
			),
			'related'    => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Show related videos', 'shortcodes-ultimate' ),
				'desc'    => __( 'Show related videos at the end of the video', 'shortcodes-ultimate' ),
			),
			'info'       => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Show video info', 'shortcodes-ultimate' ),
				'desc'    => __( 'Show videos info (title/author) on the start screen', 'shortcodes-ultimate' ),
			),
			'title'      => array(
				'name'    => __( 'Title', 'shortcodes-ultimate' ),
				'desc'    => __( 'A brief description of the embedded content (used by screenreaders)', 'shortcodes-ultimate' ),
				'default' => '',
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Dailymotion video', 'shortcodes-ultimate' ),
		'icon'     => 'youtube-play',
	)
);

function su_shortcode_dailymotion( $atts = null, $content = null ) {

	$atts = shortcode_atts(
		array(
			'url'        => false,
			'width'      => 600,
			'height'     => 400,
			'responsive' => 'yes',
			'autoplay'   => 'no',
			'background' => '#FFC300',
			'foreground' => '#F7FFFD',
			'highlight'  => '#171D1B',
			'logo'       => 'yes',
			'quality'    => '380',
			'related'    => 'yes',
			'info'       => 'yes',
			'title'      => '',
			'class'      => '',
		),
		$atts,
		'dailymotion'
	);

	if ( ! $atts['url'] ) {
		return su_error_message( 'Dailymotion', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$atts['url'] = su_do_attribute( $atts['url'] );
	$id          = strtok( basename( $atts['url'] ), '_' );

	if ( ! $id ) {
		return su_error_message( 'Screenr', __( 'please specify correct url', 'shortcodes-ultimate' ) );
	}

	$params     = array();
	$dm_options = array( 'autoplay', 'background', 'foreground', 'highlight', 'logo', 'quality', 'info' );

	foreach ( $dm_options as $dm_option ) {
		$params[] = $dm_option . '=' . str_replace( array( 'yes', 'no', '#' ), array( '1', '0', '' ), $atts[ $dm_option ] );
	}

	if ( 'no' === $atts['related'] ) {
		$params[] = 'queue-enable=false';
	}

	su_query_asset( 'css', 'su-shortcodes' );

	return '<div class="su-dailymotion su-u-responsive-media-' . esc_attr( $atts['responsive'] ) . su_get_css_class( $atts ) . '"><iframe width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" src="//www.dailymotion.com/embed/video/' . $id . '?' . esc_attr( implode( '&', $params ) ) . '" frameborder="0" allowfullscreen="true" title="' . esc_attr( $atts['title'] ) . '"></iframe></div>';

}
