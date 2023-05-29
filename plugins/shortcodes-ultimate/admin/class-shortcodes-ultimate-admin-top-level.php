<?php

/**
 * The Shortcodes menu component.
 *
 * @since        5.0.0
 *
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/admin
 */
final class Shortcodes_Ultimate_Admin_Top_Level extends Shortcodes_Ultimate_Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  5.0.0
	 * @param string  $plugin_file    The path of the main plugin file
	 * @param string  $plugin_version The current version of the plugin
	 */
	public function __construct( $plugin_file, $plugin_version, $plugin_prefix ) {
		parent::__construct( $plugin_file, $plugin_version, $plugin_prefix );
	}

	/**
	 * Add menu page
	 *
	 * @since   5.0.0
	 */
	public function add_menu_pages() {

		// SVG icon (base64-encoded)
		$icon = apply_filters( 'su/admin/icon', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGwtcnVsZT0iZXZlbm9kZCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEuNDEiIHZpZXdCb3g9IjAgMCAyMCAyMCIgY2xpcC1ydWxlPSJldmVub2RkIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBmaWxsPSIjZjBmNWZhIiBmaWxsLXJ1bGU9Im5vbnplcm8iIGQ9Ik04LjQ4IDIuNzV2Mi41SDUuMjV2OS41aDMuMjN2Mi41SDIuNzVWMi43NWg1Ljczem05LjI3IDE0LjVoLTUuNzN2LTIuNWgzLjIzdi05LjVoLTMuMjN2LTIuNWg1LjczdjE0LjV6Ii8+PC9zdmc+' );

		/**
		 * Top-level menu: Shortcodes
		 * admin.php?page=shortcodes-ultimate
		 */
		$this->add_menu_page(
			__( 'Shortcodes Ultimate', 'shortcodes-ultimate' ),
			__( 'Shortcodes', 'shortcodes-ultimate' ),
			$this->get_capability(),
			rtrim( $this->plugin_prefix, '-_' ),
			'__return_false',
			$icon,
			'80.11'
		);

	}

	/**
	 * Display menu page.
	 *
	 * @since    5.0.8
	 * @return   string   Menu page markup.
	 */
	public function the_menu_page() {
		$this->the_template( 'admin/partials/pages/shortcodes' );
	}

}
