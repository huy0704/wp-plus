<?php

su_add_shortcode(
	array(
		'id'       => 'animate',
		'callback' => 'su_shortcode_animate',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/animate.svg',
		'name'     => __( 'Animation', 'shortcodes-ultimate' ),
		'type'     => 'wrap',
		'group'    => 'other',
		'atts'     => array(
			'type'     => array(
				'type'    => 'select',
				'values'  => array_combine( su_get_config( 'animations' ), su_get_config( 'animations' ) ),
				'default' => 'bounceIn',
				'name'    => __( 'Animation', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select animation type', 'shortcodes-ultimate' ),
			),
			'duration' => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 20,
				'step'    => 0.5,
				'default' => 1,
				'name'    => __( 'Duration', 'shortcodes-ultimate' ),
				'desc'    => __( 'Animation duration (seconds)', 'shortcodes-ultimate' ),
			),
			'delay'    => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 20,
				'step'    => 0.5,
				'default' => 0,
				'name'    => __( 'Delay', 'shortcodes-ultimate' ),
				'desc'    => __( 'Animation delay (seconds)', 'shortcodes-ultimate' ),
			),
			'inline'   => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Inline', 'shortcodes-ultimate' ),
				'desc'    => __( 'This parameter determines what HTML tag will be used for animation wrapper. Turn this option to YES and animated element will be wrapped in SPAN instead of DIV. Useful for inline animations, like buttons', 'shortcodes-ultimate' ),
			),
			'class'    => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'content'  => __( 'Animated content', 'shortcodes-ultimate' ),
		'desc'     => __( 'Wrapper for animation. Any nested element will be animated', 'shortcodes-ultimate' ),
		'example'  => 'animations',
		'icon'     => 'bolt',
	)
);

function su_shortcode_animate( $atts = null, $content = null ) {
	$atts   = shortcode_atts(
		array(
			'type'     => 'bounceIn',
			'duration' => 1,
			'delay'    => 0,
			'inline'   => 'no',
			'class'    => '',
		),
		$atts,
		'animate'
	);
	$tag    = ( $atts['inline'] === 'yes' ) ? 'span' : 'div';
	$time   = '-webkit-animation-duration:' . $atts['duration'] . 's;-webkit-animation-delay:' . $atts['delay'] . 's;animation-duration:' . $atts['duration'] . 's;animation-delay:' . $atts['delay'] . 's;';
	$return = '<' . $tag . ' class="su-animate' . su_get_css_class( $atts ) . '" style="opacity:0;' . esc_attr( $time ) . '" data-animation="' . esc_attr( $atts['type'] ) . '" data-duration="' . esc_attr( $atts['duration'] ) . '" data-delay="' . esc_attr( $atts['delay'] ) . '">' . do_shortcode( $content ) . '</' . $tag . '>';
	su_query_asset( 'css', 'animate' );
	su_query_asset( 'js', 'jquery' );
	su_query_asset( 'js', 'jquery-inview' );
	su_query_asset( 'js', 'su-shortcodes' );
	return $return;
}
