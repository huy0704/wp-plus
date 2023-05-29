<?php

/**
 * The class responsible for plugin upgrade procedures.
 *
 * @since        5.0.0
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/includes
 */
final class Shortcodes_Ultimate_Upgrade {

	/**
	 * The current version of the plugin.
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $current_version   The current version of the plugin.
	 */
	private $current_version;

	/**
	 * Name of the option which stores plugin version.
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $saved_version_option   Name of the option which stores plugin version.
	 */
	private $saved_version_option;

	/**
	 * Define the functionality of the updater.
	 *
	 * @since   5.0.0
	 * @param string  $plugin_version The current version of the plugin.
	 */
	public function __construct( $plugin_version ) {

		$this->current_version      = $plugin_version;
		$this->saved_version_option = 'su_option_version';

	}

	/**
	 * Run upgrades if version changed.
	 *
	 * @since  5.0.0
	 */
	public function maybe_upgrade() {

		if ( ! $this->is_version_changed() ) {
			return;
		}

		$this->setup_defaults();

		$this->maybe_upgrade_to( '5.0.0' );
		$this->maybe_upgrade_to( '5.0.7' );
		$this->maybe_upgrade_to( '5.6.0' );
		$this->maybe_upgrade_to( '5.9.1' );

		$this->update_saved_version();

	}

	/**
	 * Helper function to register a new upgrade routine.
	 *
	 * @since 5.4.0
	 * @param string $version New version number.
	 */
	private function maybe_upgrade_to( $version ) {

		if ( ! $this->is_saved_version_lower_than( $version ) ) {
			return;
		}

		$this->upgrade_to( $version );

	}

	/**
	 * Helper function to test a new upgrade routine.
	 *
	 * @since 5.6.0
	 * @param string $version New version number.
	 */
	private function upgrade_to( $version ) {

		$upgrade_file = __DIR__ . '/upgrade/' . $version . '.php';

		if ( ! file_exists( $upgrade_file ) ) {
			return;
		}

		include $upgrade_file;

	}

	/**
	 * Conditional check if plugin was updated.
	 *
	 * @since  5.0.0
	 * @access private
	 * @return boolean True if plugin was updated, False otherwise.
	 */
	private function is_version_changed() {
		return $this->is_saved_version_lower_than( $this->current_version );
	}

	/**
	 * Conditional check if previous version of the plugin lower than passed one.
	 *
	 * @since  5.0.0
	 * @access private
	 * @return boolean True if previous version of the plugin lower than passed one, False otherwise.
	 */
	private function is_saved_version_lower_than( $version ) {

		return version_compare(
			get_option( $this->saved_version_option, 0 ),
			$version,
			'<'
		);

	}

	/**
	 * Save current version number.
	 *
	 * @since  5.0.0
	 * @access private
	 */
	private function update_saved_version() {
		update_option( $this->saved_version_option, $this->current_version, false );
	}

	/**
	 * Setup missing default settings
	 */
	private function setup_defaults() {

		$defaults = su_get_config( 'default-settings' );

		foreach ( $defaults as $option => $value ) {

			if ( get_option( $option, 0 ) === 0 ) {
				add_option( $option, $value );
			}

		}

	}

}
