<?php defined( 'ABSPATH' ) or exit; ?>

<input type="checkbox" name="<?php echo esc_attr( $data['id'] ); ?>" id="<?php echo esc_attr( $data['id'] ); ?>" <?php checked( get_option( $data['id'] ), 'on' ); ?>> <label for="<?php echo esc_attr( $data['id'] ); ?>"><?php _e( 'Enabled', 'shortcodes-ultimate' ); ?></label>

<p class="description"><?php echo $data['description']; ?></p>
