<?php defined( 'ABSPATH' ) || exit; ?>

<fieldset>

	<?php foreach ( $data['options'] as $cb_id => $cb_label ) : ?>

		<input
			type="checkbox"
			name="<?php echo esc_attr( sprintf( '%s[%s]', $data['id'], $cb_id ) ); ?>"
			id="<?php echo esc_attr( sprintf( '%s_%s', $data['id'], $cb_id ) ); ?>"
			<?php checked( in_array( $cb_id, get_option( $data['id'], array() ), true ) ); ?>
		>
		<label for="<?php echo esc_attr( sprintf( '%s_%s', $data['id'], $cb_id ) ); ?>" >
			<?php echo esc_html( $cb_label ); ?>
		</label>
		<br>

	<?php endforeach; ?>

</fieldset>

<p class="description"><?php echo $data['description']; ?></p>
