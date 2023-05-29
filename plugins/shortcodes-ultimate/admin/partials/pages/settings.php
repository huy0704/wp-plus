<?php defined( 'ABSPATH' ) || exit; ?>

<div class="su-admin-settings wrap">

	<?php if ( ! su_fs()->can_use_premium_code() && ! su_has_active_addons() ) : ?>
		<?php su_partial( 'admin/partials/notices/pro.php', array( 'page' => 'settings' ) ); ?>
	<?php endif; ?>

	<h1 class="su-admin-settings-page-title">
		<img src="<?php echo esc_attr( su_get_plugin_url() . 'admin/images/plugin-icon.svg' ); ?>" alt="" width="36" height="36">
		<?php esc_html_e( 'Shortcodes Ultimate Settings', 'shortcodes-ultimate' ); ?>
	</h1>

	<?php settings_errors(); ?>

	<form action="options.php" method="post" class="su-admin-settings-form">

			<?php if ( $this->is_advanced_settings() ) : ?>

				<div class="notice notice-warning">
					<p>
						<strong><?php esc_html_e( 'Warning!', 'shortcodes-ultimate' ); ?></strong><br>
						<?php esc_html_e( 'You are editing advanced settings. Do NOT modify anything in here unless you know what you are doing.', 'shortcodes-ultimate' ); ?>
					</p>
					<p>
						<a href="<?php echo esc_attr( $this->get_component_url() ); ?>">&larr; <?php esc_html_e( 'Go back to main settings', 'shortcodes-ultimate' ); ?></a>
					</p>
				</div>

				<?php settings_fields( $this->plugin_prefix . 'advanced-settings' ); ?>
				<?php do_settings_sections( $this->plugin_prefix . 'advanced-settings' ); ?>

			<?php else : ?>

				<?php settings_fields( rtrim( $this->plugin_prefix, '-_' ) ); ?>
				<?php do_settings_sections( $this->plugin_prefix . 'settings' ); ?>

			<?php endif; ?>

			<?php submit_button(); ?>

	</form>

	<ul class="su-admin-settings-bottom-menu">
		<?php if ( ! $this->is_advanced_settings() ) : ?>
			<li><a href="<?php echo esc_attr( add_query_arg( 'advanced', '', $this->get_component_url() ) ); ?>"><?php esc_html_e( 'Advanced settings', 'shortcodes-ultimate' ); ?></a></li>
		<?php else : ?>
			<li><a href="<?php echo esc_attr( $this->get_component_url() ); ?>"><?php esc_html_e( 'Main settings', 'shortcodes-ultimate' ); ?></a></li>
		<?php endif; ?>
		<li><a href="<?php echo esc_attr( add_query_arg( 'page', 'shortcodes-ultimate', admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'About', 'shortcodes-ultimate' ); ?></a></li>
	</ul>

</div>
