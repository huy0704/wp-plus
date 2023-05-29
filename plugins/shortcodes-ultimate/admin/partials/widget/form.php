<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		<?php esc_html_e( 'Title:', 'shortcodes-ultimate' ); ?>
	</label>
	<input
		type="text"
		id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		value="<?php echo esc_attr( $instance['title'] ); ?>"
		class="widefat"
	/>
</p>
<p>
	<?php Su_Generator::button_html_editor( array( 'target' => $this->get_field_id( 'content' ) ) ); ?><br/>
	<textarea
		name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"
		id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"
		rows="7"
		class="widefat"
		style="margin-top:10px"
	><?php echo esc_textarea( $instance['content'] ); ?></textarea>
</p>
