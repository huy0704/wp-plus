<?php

class Su_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'shortcodes-ultimate',
			'description' => __( 'Shortcodes Ultimate widget', 'shortcodes-ultimate' )
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'shortcodes-ultimate'
		);
		parent::__construct( 'shortcodes-ultimate', __( 'Shortcodes Ultimate', 'shortcodes-ultimate' ), $widget_ops, $control_ops );
	}

	public static function register() {
		if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
			register_widget( 'Su_Widget' );
		}
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$content = $instance['content'];
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		echo '<div class="textwidget">' . do_shortcode( $content ) . '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['content'] = $new_instance['content'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'   => __( 'Shortcodes Ultimate', 'shortcodes-ultimate' ),
			'content' => ''
		);
		$instance = wp_parse_args( ( array ) $instance, $defaults );
?>
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'shortcodes-ultimate' ); ?></label>
					<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
				</p>
				<p>
				<?php Su_Generator::button( array( 'target' => $this->get_field_id( 'content' ) ) ); ?><br/>
					<textarea name="<?php echo $this->get_field_name( 'content' ); ?>" id="<?php echo $this->get_field_id( 'content' ); ?>" rows="7" class="widefat" style="margin-top:10px"><?php echo $instance['content']; ?></textarea>
				</p>
				<?php
	}

}

add_action( 'widgets_init', array( 'Su_Widget', 'register' ) );
