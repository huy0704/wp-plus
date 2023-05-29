<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since        5.0.0
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/includes
 */
class Shortcodes_Ultimate_Activator {

	private static $required_php;
	private static $required_wp;

	/**
	 * Plugin activation.
	 *
	 * @since    5.0.0
	 */
	public static function activate() {

		self::$required_php = '5.4';
		self::$required_wp  = '5.0';

		self::check_php_version();
		self::check_wp_version();
		self::setup_defaults();

	}

	/**
	 * Check PHP version.
	 *
	 * @access  private
	 * @since   5.0.0
	 */
	private static function check_php_version() {

		$current = phpversion();

		if ( version_compare( $current, self::$required_php, '>=' ) ) {
			return;
		}

		$message = sprintf(
			// Translators: %1$s - required version number, %2$s - current version number
			__( 'Shortcodes Ultimate is not activated, because it requires PHP version %1$s (or higher). You have version %2$s.', 'shortcodes-ultimate' ),
			self::$required_php,
			$current
		);

		die( esc_html( $message ) );

	}

	/**
	 * Check WordPress version.
	 *
	 * @access  private
	 * @since   5.0.0
	 */
	private static function check_wp_version() {

		$current = get_bloginfo( 'version' );

		if ( version_compare( $current, self::$required_wp, '>=' ) ) {
			return;
		}

		$message = sprintf(
			// Translators: %1$s - required version number, %2$s - current version number
			__( 'Shortcodes Ultimate is not activated, because it requires WordPress version %1$s (or higher). You have version %2$s.', 'shortcodes-ultimate' ),
			self::$required_wp,
			$current
		);

		die( esc_html( $message ) );

	}

	/**
	 * Setup plugin's default settings.
	 *
	 * @access  private
	 * @since   5.0.0
	 */
	private static function setup_defaults() {

		if ( ! function_exists( 'su_get_config' ) ) {
			require_once 'functions-helpers.php';
		}

		$defaults = su_get_config( 'default-settings' );

		foreach ( $defaults as $option => $value ) {

			if ( get_option( $option, 0 ) === 0 ) {
				add_option( $option, $value );
			}

		}

	}

}
