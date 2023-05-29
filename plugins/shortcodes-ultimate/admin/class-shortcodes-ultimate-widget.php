<?php

class Shortcodes_Ultimate_Widget extends WP_Widget {

	public static $widget_prefix;

	public function __construct( $plugin_prefix = null ) {

		if ( ! empty( $plugin_prefix ) ) {
			self::$widget_prefix = rtrim( $plugin_prefix, '-_' );
		}

		$widget_ops = array(
			'classname'   => self::$widget_prefix,
			'description' => __( 'Shortcodes Ultimate widget', 'shortcodes-ultimate' ),
		);

		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => self::$widget_prefix,
		);

		parent::__construct(
			self::$widget_prefix,
			__( 'Shortcodes Ultimate', 'shortcodes-ultimate' ),
			$widget_ops,
			$control_ops
		);

	}

	public function register() {
		register_widget( 'Shortcodes_Ultimate_Widget' );
	}

	public function widget( $args, $instance ) {

		if ( empty( $instance['title'] ) && empty( $instance['content'] ) ) {
			return;
		}

		$instance['title'] = apply_filters( 'widget_title', $instance['title'] );

		if ( ! empty( $instance['title'] ) ) {
			$instance['title'] = "{$args['before_title']}{$instance['title']}{$args['after_title']}";
		}

		if ( ! empty( $instance['content'] ) ) {

			$instance['content'] = sprintf(
				'<div class="textwidget">%s</div>',
				do_shortcode( $instance['content'] )
			);

		}

		// phpcs:disable
		echo $args['before_widget'] . $instance['title'] . $instance['content'] . $args['after_widget'];
		// phpcs:enable

	}

	public function update( $new_instance, $old_instance ) {

		$instance            = $old_instance;
		$instance['title']   = wp_strip_all_tags( $new_instance['title'] );
		$instance['content'] = $new_instance['content'];

		return $instance;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'   => __( 'Shortcodes Ultimate', 'shortcodes-ultimate' ),
			'content' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		include plugin_dir_path( __FILE__ ) . 'partials/widget/form.php';

	}

}
