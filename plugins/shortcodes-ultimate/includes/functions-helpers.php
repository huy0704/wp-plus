<?php

/**
 * Helper Functions.
 *
 * @since        5.0.5
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/includes
 */

/**
 * Retrieves instance of the main plugin class.
 *
 * @since  5.0.4
 */
function shortcodes_ultimate() {
	return Shortcodes_Ultimate::get_instance();
}

/**
 * Retrieve the URL of the plugin directory (with trailing slash).
 *
 * @since  5.0.5
 * @return string The URL of the plugin directory (with trailing slash).
 */
function su_get_plugin_url() {
	return plugin_dir_url( dirname( __FILE__ ) );
}

/**
 * Retrieve the filesystem path of the plugin directory (with trailing slash).
 *
 * @since  5.0.5
 * @return string The filesystem path of the plugin directory (with trailing slash).
 */
function su_get_plugin_path() {
	return plugin_dir_path( dirname( __FILE__ ) );
}

/**
 * Retrieve the current version of the plugin.
 *
 * @since  5.2.0
 * @return string The current verion of the plugin.
 */
function su_get_plugin_version() {
	return get_option( 'su_option_version', '0' );
}

/**
 * Get plugin config.
 *
 * @since  5.0.5
 * @param string  $key
 * @return mixed      Config data if found, False otherwise.
 */
function su_get_config( $key = null, $default = false ) {

	static $config = array();

	if (
		empty( $key ) ||
		preg_match( '/^(?!-)[a-z0-9-_]+(?<!-)(\/(?!-)[a-z0-9-_]+(?<!-))*$/', $key ) !== 1
	) {
		return $default;
	}

	if ( isset( $config[ $key ] ) ) {
		return $config[ $key ];
	}

	$config_file = su_get_plugin_path() . 'includes/config/' . $key . '.php';

	if ( ! file_exists( $config_file ) ) {
		return $default;
	}

	$config[ $key ] = include $config_file;

	return $config[ $key ];

}

/**
 * Create an error message.
 *
 * @since  5.0.5
 * @param string  $title   Error title.
 * @param string  $message Error message.
 * @return string          Error message markup.
 */
function su_error_message( $title = '', $message = '', $echo = false ) {

	if ( ! su_current_user_can_insert() ) {
		return;
	}

	if ( $title ) {
		$title = "<strong>${title}:</strong> ";
	}

	$output = sprintf(
		'<p class="su-error" style="padding:5px 10px;color:#8f3a35;border-left:3px solid #8f3a35;background:#fff7f6;line-height:1.35">%1$s%2$s</p>',
		$title,
		$message
	);

	if ( $echo ) {
		// phpcs:disable
		echo $output;
		// phpcs:enable
	}

	return $output;

}

/**
 * Conditional check if current user can use the plugin.
 *
 * @since 5.4.0
 * @return bool True if user is allowed to use the plugin, False otherwise.
 */
function su_current_user_can_insert() {

	$required_capability = (string) get_option(
		'su_option_generator_access',
		'manage_options'
	);

	return current_user_can( $required_capability );

}

/**
 * Validate filter callback name.
 *
 * @since  5.0.5
 * @param string  $filter Filter callback name.
 * @return boolean         True if filter name contains word 'filter', False otherwise.
 */
function su_is_filter_safe( $filter ) {
	return is_string( $filter ) && false !== strpos( $filter, 'filter' );
}

/**
 * Helper function to safely apply user defined filter to a given value
 * @param  string $filter Filter function name
 * @param  string $value  Filterable value
 * @return string         A filtered value if the given filter is safe
 */
function su_safely_apply_user_filter( $filter = null, $value = null ) {

	if (
		is_string( $filter ) &&
		is_string( $value ) &&
		su_is_filter_safe( $filter ) &&
		function_exists( $filter )
	) {
		$value = call_user_func( $filter, $value );
	}

	return $value;

}

/**
 * Range converter.
 *
 * Converts string ranges like '1, 3-5' into arrays like [1, 3, 4, 5].
 *
 * @since  5.0.5
 * @param string  $string Range string.
 * @return array          Parsed range.
 */
function su_parse_range( $string = '' ) {

	$parsed = array();

	foreach ( explode( ',', $string ) as $range ) {

		if ( strpos( $range, '-' ) === false ) {
			$parsed[] = intval( $range );
			continue;
		}

		$range = explode( '-', $range );

		if ( ! is_numeric( $range[0] ) ) {
			$range[0] = 0;
		}

		if ( ! is_numeric( $range[1] ) ) {
			$range[1] = 0;
		}

		foreach ( range( $range[0], $range[1] ) as $value ) {
			$parsed[] = $value;
		}

	}

	sort( $parsed );
	$parsed = array_unique( $parsed );

	return $parsed;

}

/**
 * Extract CSS class name(s) from shortcode $atts and prepend with a space.
 *
 * @since  5.0.5
 * @param array   $atts Shortcode atts.
 * @return string       Extra CSS class(es) prepended by a space.
 */
if ( ! function_exists( 'su_get_css_class' ) ) {

	function su_get_css_class( $atts ) {
		return $atts['class'] ? ' ' . esc_attr( trim( $atts['class'] ) ) : '';
	}

}

/**
 * Helper function to force enqueuing of the shortcode generator assets and
 * templates.
 *
 * Usage example:
 * `add_action( 'admin_init', 'su_enqueue_generator' );`
 *
 * @since 5.1.0
 */
function su_enqueue_generator() {
	Su_Generator::enqueue_generator();
}

/**
 * Helper function to check that the given path is related to the current theme
 * or to the plugin directory.
 *
 * @since  5.4.0
 * @param  string $path Relative path to check.
 * @return bool         True if the given path relates to theme/plugin directory, False otherwise.
 */
function su_is_valid_template_name( $path ) {

	$path = su_set_file_extension( $path, 'php' );

	$allowed = apply_filters(
		'su/allowed_template_paths',
		array(
			get_stylesheet_directory(),
			get_template_directory(),
			plugin_dir_path( dirname( __FILE__ ) ),
		)
	);

	foreach ( $allowed as $dir ) {

		$dir  = untrailingslashit( $dir );
		$real = realpath( path_join( $dir, $path ) );

		$dir  = str_replace( '\\', '/', $dir );
		$real = str_replace( '\\', '/', $real );

		if ( strpos( $real, $dir ) === 0 ) {
			return true;
		}

	}

	return false;

}

/**
 * Helper function to add/remove file extension to/from a given path.
 *
 * @since  5.4.0
 * @param  string      $path      Path to add/remove file extension to/from.
 * @param  string|bool $extension Extension to add/remove.
 * @return string                 Modified file path.
 */
function su_set_file_extension( $path, $extension ) {

	$path_info = pathinfo( $path );

	if ( ! $extension ) {
		return path_join( $path_info['dirname'], $path_info['filename'] );
	}

	if ( empty( $path_info['extension'] ) || $path_info['extension'] !== $extension ) {
		$path .= ".{$extension}";
	}

	return $path;

}

/**
 * Helper function to add utm-args to an URL.
 *
 * @since 5.6.1
 */
function su_get_utm_link( $url, $utm_campaign, $utm_medium, $utm_source ) {

	return add_query_arg(
		array(
			'utm_campaign' => $utm_campaign,
			'utm_medium'   => $utm_medium,
			'utm_source'   => $utm_source,
		),
		$url
	);

}

/**
 * Helper function to check if a passed value is a positive number.
 *
 * Returns true for positive numbers, allows integers and strings.
 *
 * @param  mixed  $value Value to test
 * @return bool          True if passed value is a positive number (integer or string), False otherwise
 */
function su_is_positive_number( $value ) {

	if ( ! is_string( $value ) && ! is_int( $value ) ) {
		return false;
	}

	if ( ! ctype_digit( (string) $value ) ) {
		return false;
	}

	return (int) $value > 0;

}

/**
 * Helper function to join multiple path pieces into one.
 *
 * @return string Merged path pieces
 */
function su_join_paths() {

	$is_absolute = func_get_arg( 0 ) !== ltrim( func_get_arg( 0 ), '\\/' );

	$pieces = array_map(
		function( $piece ) {
			return trim( $piece, '\\/' );
		},
		func_get_args()
	);

	$path = implode( DIRECTORY_SEPARATOR, $pieces );

	if ( $is_absolute ) {
		$path = DIRECTORY_SEPARATOR . $path;
	}

	return $path;

}

/**
 * Helper function that adds CSS units to the supplied numeric value
 * @param  mixed  $value The original value (String or Integer)
 * @param  string $units CSS units to add
 * @return string        Value with CSS units
 */
function su_maybe_add_css_units( $value = '', $units = '' ) {

	if ( is_numeric( $value ) ) {
		$value .= $units;
	}

	return $value;

}

/**
 * Helper to get the current page URL
 * @return string Current page URL
 */
function su_get_current_url() {

	$protocol = is_ssl() ? 'https' : 'http';

	return esc_url( "{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" );

}

function su_is_unsafe_features_enabled() {
	return 'on' === get_option( 'su_option_unsafe_features' );
}

/**
 * Helper function to get contents of a template file and pass data to it
 *
 * Examples of use
 *
 * su_get_partial( 'includes/partials/partial.php' );
 * su_get_partial( 'includes/partials/partial.php', [ 'foo' => 'bar' ] );
 */
function su_get_partial( $file, $data = array() ) {

	$plugin_dir = plugin_dir_path( SU_PLUGIN_FILE );
	$file       = realpath( $plugin_dir . $file );

	if ( strpos( $file, $plugin_dir ) !== 0 ) {
		return '';
	}

	if ( ! file_exists( $file ) ) {
		return '';
	}

	ob_start();
	include $file;
	return ob_get_clean();

}

/**
 * Helper function to display contents of a template file and pass data to it
 *
 * Examples of use
 *
 * su_partial( 'includes/partials/partial.php' );
 * su_partial( 'includes/partials/partial.php', [ 'foo' => 'bar' ] );
 */
function su_partial( $file, $data = array() ) {
	// phpcs:disable
	echo su_get_partial( $file, $data );
	// phpcs:enable
}

function su_has_active_addons() {

	foreach ( array( 'skins', 'extra', 'maker' ) as $addon ) {

		if ( function_exists( "run_shortcodes_ultimate_{$addon}" ) ) {
			return true;
		}
	}

	return false;

}

function su_has_all_active_addons() {

	foreach ( array( 'skins', 'extra', 'maker' ) as $addon ) {

		if ( ! function_exists( "run_shortcodes_ultimate_{$addon}" ) ) {
			return false;
		}
	}

	return true;

}

function su_load_textdomain() {

	$domain    = 'shortcodes-ultimate';
	$languages = plugin_dir_path( SU_PLUGIN_FILE ) . 'languages/';
	$mofile    = $languages . $domain . '-' . determine_locale() . '.mo';

	load_textdomain( $domain, $mofile );

}

function su_current_user_can_read_post( $post_id ) {
	if ( post_password_required( $post_id ) ) {
		return false;
	}

	if ( 'publish' !== get_post_status( $post_id ) && ! current_user_can( 'read_post', $post_id ) ) {
		return false;
	}

	return true;
}
