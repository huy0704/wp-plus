<?php defined( 'ABSPATH' ) or exit; ?>

<input type="text" name="<?php echo esc_attr( $data['id'] ); ?>" id="<?php echo esc_attr( $data['id'] ); ?>" value="<?php echo esc_attr( get_option( $data['id'] ) ); ?>" class="regular-text">

<p class="description"><?php echo $data['description']; ?></p>
