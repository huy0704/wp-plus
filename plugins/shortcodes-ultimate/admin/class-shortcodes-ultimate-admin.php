<?php

/**
 * The abstract class for creating admin components.
 *
 * @since        5.0.0
 *
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/admin
 */
abstract class Shortcodes_Ultimate_Admin {

	/**
	 * The path of the main plugin file.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      string    $plugin_file    The path of the main plugin file.
	 */
	protected $plugin_file;

	/**
	 * The current version of the plugin.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      string    $plugin_version    The current version of the plugin.
	 */
	protected $plugin_version;

	/**
	 * The prefix of the plugin.
	 *
	 * @since    5.0.8
	 * @access   protected
	 * @var      string      $plugin_prefix   The prefix of the plugin.
	 */
	protected $plugin_prefix;

	/**
	 * The URL of the plugin folder.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      string    $plugin_url    The URL of the plugin folder.
	 */
	protected $plugin_url;

	/**
	 * The path to the plugin folder.
	 *
	 * @since    5.1.0
	 * @access   private
	 * @var      string      $plugin_path   The path to the plugin folder.
	 */
	private $plugin_path;

	/**
	 * User capability required to access admin pages.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      string    $capability    User capability required to access admin pages.
	 */
	protected $capability;

	/**
	 * The plugin's pages collection.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      array     $plugin_menu_pages   The plugin's pages collection.
	 */
	static protected $plugin_menu_pages;

	/**
	 * The hook_suffix of the component menu page.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      array     $component_hook_suffix   The hook_suffix of the component menu page.
	 */
	protected $component_hook_suffix;

	/**
	 * The URL of the component menu page.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      array     $component_url   The URL of the component menu page.
	 */
	protected $component_url;

	/**
	 * Component tabs collection.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @var      array     $component_tabs   Component tabs collection.
	 */
	protected $component_tabs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  5.0.0
	 * @access protected
	 * @param string  $plugin_file    The path of the main plugin file.
	 * @param string  $plugin_version The current version of the plugin.
	 * @param string  $plugin_prefix  The prefix of the plugin.
	 */
	public function __construct( $plugin_file, $plugin_version, $plugin_prefix ) {

		$this->plugin_file           = $plugin_file;
		$this->plugin_version        = $plugin_version;
		$this->plugin_prefix         = $plugin_prefix;
		$this->plugin_url            = plugin_dir_url( $plugin_file );
		$this->plugin_path           = plugin_dir_path( $plugin_file );
		$this->capability            = 'manage_options';
		$this->component_url         = null;
		$this->component_hook_suffix = null;
		$this->component_tabs        = array();
		self::$plugin_menu_pages     = (array) self::$plugin_menu_pages;

	}

	/**
	 * Register menu pages.
	 *
	 * @since   5.0.0
	 */
	public function add_menu_pages() {}

	/**
	 * Register settings.
	 *
	 * @since   5.0.8
	 */
	public function add_settings() {}

	/**
	 * Enqueue JavaScript(s) and Stylesheet(s) for the component.
	 *
	 * @since   5.0.0
	 */
	public function enqueue_scripts() {}

	/**
	 * Add a top-level menu page.
	 *
	 * @since  5.0.0
	 * @uses  add_menu_page
	 * @access protected
	 * @param string  $page_title The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string  $menu_title The text to be used for the menu.
	 * @param string  $capability The capability required for this menu to be displayed to the user.
	 * @param string  $menu_slug  The slug name to refer to this menu by (should be unique for this menu).
	 * @param callable $function   The function to be called to output the content for this page.
	 * @param string  $icon_url   The URL to the icon to be used for this menu.
	 * @param int     $position   The position in the menu order this one should appear.
	 */
	final protected function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {

		$hook_suffix = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		self::$plugin_menu_pages[]   = $hook_suffix;
		$this->component_hook_suffix = $hook_suffix;

		$this->component_url = menu_page_url( $menu_slug, false );

	}

	/**
	 * Add a submenu page.
	 *
	 * @since  5.0.0
	 * @uses add_submenu_page
	 * @access protected
	 * @param string  $parent_slug The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param string  $page_title  The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string  $menu_title  The text to be used for the menu.
	 * @param string  $capability  The capability required for this menu to be displayed to the user.
	 * @param string  $menu_slug   The slug name to refer to this menu by (should be unique for this menu).
	 * @param callable $function    The function to be called to output the content for this page.
	 */
	final protected function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {

		$hook_suffix = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

		self::$plugin_menu_pages[]   = $hook_suffix;
		$this->component_hook_suffix = $hook_suffix;

		$this->component_url = menu_page_url( $menu_slug, false );

	}

	/**
	 * Common callback for all menu pages.
	 *
	 * This method retrieves current page slug from $_GET and loads appropriate
	 * template.
	 *
	 * @since    5.0.0
	 * @return   string   Settings pages markup.
	 */
	public function the_menu_page() {

		// Sanitize current page slug
		$page = sanitize_title( $_GET['page'], false );

		// Replace plugin slug with template prefix
		$page = str_replace( $this->plugin_prefix, '', $page );

		$this->the_template( 'admin/partials/pages/' . $page );

	}

	/**
	 * Display settings section.
	 *
	 * @param mixed   $args Settings section data.
	 * @since  5.0.8
	 */
	public function the_settings_section( $args ) {

		$section = str_replace( $this->plugin_prefix, '', $args['id'] );

		$this->the_template( 'admin/partials/settings/sections/' . $section, $args );

	}

	/**
	 * Display settings field.
	 *
	 * @param mixed   $args The field data.
	 * @since  5.0.8
	 */
	public function the_settings_field( $args ) {
		$this->the_template( 'admin/partials/settings/fields/' . $args['type'], $args );
	}

	/**
	 * Helper function to check component's menu page hook_suffix
	 *
	 * @since  5.0.0
	 * @return boolean Returns true if current screen is the component's menu page
	 */
	protected function is_component_page() {

		$screen = get_current_screen();

		return $screen->id === $this->get_component_hook_suffix();

	}

	/**
	 * Retrieve the title of the current admin screen.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @return   string   The title of the current admin screen.
	 */
	protected function get_page_title() {

		return sprintf(
			'%s &rsaquo; %s',
			__( 'Shortcodes Ultimate', 'shortcodes-ultimate' ),
			get_admin_page_title()
		);

	}

	/**
	 * Display the title of the current admin screen.
	 *
	 * @since    5.0.0
	 * @access   protected
	 */
	public function the_page_title() {
		echo $this->get_page_title();
	}

	/**
	 * Retrieve tabs collection.
	 *
	 * @since  5.0.0
	 * @return array Component tabs collection.
	 */
	protected function get_tabs() {
		return $this->component_tabs;
	}

	/**
	 * Retrieve the current tab ID
	 *
	 * @since  5.0.0
	 * @return string Current tab ID
	 */
	protected function get_current_tab() {

		if ( empty( $_GET['tab'] ) ) {
			return $this->get_first_tab();
		}

		if ( ! array_key_exists( $_GET['tab'], $this->get_tabs() ) ) {
			return $this->get_first_tab();
		}

		return sanitize_key( $_GET['tab'] );

	}

	/**
	 * Retrieve the ID of the first tab.
	 *
	 * @since  5.0.0
	 * @return string The ID of the first tab.
	 */
	protected function get_first_tab() {

		foreach ( $this->get_tabs() as $tab_id => $tab_title ) {
			return $tab_id;
		}

	}

	/**
	 * Retrieve the tab URL by ID
	 *
	 * @param string  $tab Tab ID
	 * @return string      The tab's URL
	 */
	protected function get_tab_url( $tab = null ) {

		$url = add_query_arg( 'tab', $tab, $this->get_component_url() );

		return esc_url_raw( $url );

	}

	/**
	 * Retrieve user capability required to access admin pages.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @return   string   User capability required to access admin pages.
	 */
	protected function get_capability() {
		return apply_filters( 'su/admin/capability', $this->capability );
	}

	/**
	 * Retrieve the plugin menu pages.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @return   array   The plugin menu pages.
	 */
	protected function get_plugin_menu_pages() {
		return self::$plugin_menu_pages;
	}

	/**
	 * Retrieve the hook_suffix of the component menu page.
	 *
	 * @since    5.0.0
	 * @return   array   The hook_suffix of the component menu page.
	 */
	public function get_component_hook_suffix() {
		return $this->component_hook_suffix;
	}

	/**
	 * Retrieve the URL of the component menu page.
	 *
	 * @since    5.0.0
	 * @access   protected
	 * @return   array   The URL of the component menu page.
	 */
	public function get_component_url() {
		return $this->component_url;
	}

	/**
	 * Callback function to sanitize checkbox value.
	 *
	 * @since  5.0.0
	 * @param mixed   $value String 'on' or null.
	 * @return string        Sanitized checkbox value ('on' or empty string '').
	 */
	public function sanitize_checkbox( $value ) {
		return ! empty( $value ) && 'on' === $value ? 'on' : '';
	}

	/**
	 * Callback function to sanitize checkbox-group value.
	 *
	 * @since  5.1.0
	 * @param mixed   $value Array with selected checkboxes or null.
	 * @return string        Array with selected checkbox IDs, each sanitized.
	 */
	public function sanitize_checkbox_group( $value ) {
		return array_map( 'sanitize_text_field', array_keys( (array) $value ) );
	}

	/**
	 * Callback function to sanitize prefix value.
	 *
	 * @since  5.0.1
	 * @param string  $prefix Prefix value.
	 * @return string          Sanitized string.
	 * @see  https://developer.wordpress.org/reference/functions/add_shortcode/ Source of the RegExp.
	 */
	public function sanitize_prefix( $prefix ) {
		return preg_replace( '@[<>&/\[\]\x00-\x20="\']@', '', $prefix );
	}

	/**
	 * Utility function to get specified template by it's name.
	 *
	 * @since 5.0.0
	 * @param string  $name Template name without extension.
	 * @param mixed   $data Data to be available from within template.
	 * @return string       Template content. Returns empty string if template name is invalid or template file wasn't found.
	 */
	public function get_template( $name = '', $data = array() ) {

		// Validate template name
		if ( preg_match( "/^(?!-)[a-z0-9-_]+(?<!-)(\/(?!-)[a-z0-9-_]+(?<!-))*$/", $name ) !== 1 ) {
			return '';
		}

		// The full path to template file
		$file = $this->plugin_path . $name . '.php';

		// Look for a specified file
		if ( file_exists( $file ) ) {

			ob_start();
			include $file;
			$template = ob_get_contents();
			ob_end_clean();

		}

		return isset( $template ) ? $template : '';

	}


	/**
	 * Utility function to display specified template by it's name.
	 *
	 * @since 5.0.0
	 * @param string  $name Template name (without extension).
	 * @param mixed   $data Template data to be passed to the template.
	 */
	public function the_template( $name, $data = null ) {
		echo $this->get_template( $name, $data );
	}

}
