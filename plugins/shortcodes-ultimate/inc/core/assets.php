<?php

/**
 * Class for managing plugin assets
 */
class Su_Assets {

	/**
	 * Set of queried assets
	 *
	 * @var array
	 */
	static $assets = array( 'css' => array(), 'js' => array() );

	/**
	 * Constructor
	 */
	function __construct() {
		// Register
		add_action( 'wp_head',                     array( __CLASS__, 'register' ) );
		add_action( 'admin_head',                  array( __CLASS__, 'register' ) );
		add_action( 'su/generator/preview/before', array( __CLASS__, 'register' ) );
		add_action( 'su/examples/preview/before',  array( __CLASS__, 'register' ) );
		// Enqueue
		add_action( 'wp_footer',                   array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_footer',                array( __CLASS__, 'enqueue' ) );
		// Print
		add_action( 'su/generator/preview/after',  array( __CLASS__, 'prnt' ) );
		add_action( 'su/examples/preview/after',   array( __CLASS__, 'prnt' ) );
		// Custom CSS
		add_action( 'wp_footer',                   array( __CLASS__, 'custom_css' ), 99 );
		add_action( 'su/generator/preview/after',  array( __CLASS__, 'custom_css' ), 99 );
		add_action( 'su/examples/preview/after',   array( __CLASS__, 'custom_css' ), 99 );
		// RTL support
		add_action( 'su/assets/custom_css/after',        array( __CLASS__, 'rtl_shortcodes' ) );
	}

	/**
	 * Register assets
	 */
	public static function register() {
		// Chart.js
		wp_register_script( 'chartjs', plugins_url( 'vendor/chartjs/chart.js', SU_PLUGIN_FILE ), false, '0.2', true );
		// SimpleSlider
		wp_register_script( 'simpleslider', plugins_url( 'vendor/simpleslider/simpleslider.js', SU_PLUGIN_FILE ), array( 'jquery' ), '1.0.0', true );
		wp_register_style( 'simpleslider', plugins_url( 'vendor/simpleslider/simpleslider.css', SU_PLUGIN_FILE ), false, '1.0.0', 'all' );
		// Owl Carousel
		wp_register_script( 'owl-carousel', plugins_url( 'vendor/owl-carousel/owl-carousel.js', SU_PLUGIN_FILE ), array( 'jquery' ), '2.3.4', true );
		wp_register_style( 'owl-carousel', plugins_url( 'vendor/owl-carousel/owl-carousel.css', SU_PLUGIN_FILE ), false, '2.3.4', 'all' );
		// Animate.css
		wp_register_style( 'animate', plugins_url( 'vendor/animatecss/animate.css', SU_PLUGIN_FILE ), false, '3.1.1', 'all' );
		// InView
		wp_register_script( 'jquery-inview', plugins_url( 'vendor/jquery-inview/jquery-inview.js', SU_PLUGIN_FILE ), array( 'jquery' ), '1.1.2', true );
		// PopperJS
		wp_register_script(
			'popper',
			plugins_url( 'vendor/popper/popper.min.js', SU_PLUGIN_FILE ),
			array(),
			'2.9.2',
			true
		);
		// Magnific Popup
		wp_register_style( 'magnific-popup', plugins_url( 'vendor/magnific-popup/magnific-popup.css', SU_PLUGIN_FILE ), false, '1.1.0', 'all' );
		wp_register_script( 'magnific-popup', plugins_url( 'vendor/magnific-popup/magnific-popup.js', SU_PLUGIN_FILE ), array( 'jquery' ), '1.1.0', true );
		// Swiper
		wp_register_script( 'swiper', plugins_url( 'vendor/swiper/swiper.js', SU_PLUGIN_FILE ), array( 'jquery' ), '2.6.1', true );
		// Flickity
		wp_register_script(
			'flickity',
			plugins_url( 'vendor/flickity/flickity.js', SU_PLUGIN_FILE ),
			array(),
			'2.2.1',
			true
		);
		wp_register_style(
			'flickity',
			plugins_url( 'vendor/flickity/flickity.css', SU_PLUGIN_FILE ),
			array(),
			'2.2.1',
			'all'
		);
		// jPlayer
		wp_register_script( 'jplayer', plugins_url( 'vendor/jplayer/jplayer.js', SU_PLUGIN_FILE ), array( 'jquery' ), '2.4.0', true );
		// Generator
		wp_register_style( 'su-generator', plugins_url( 'admin/css/generator.css', SU_PLUGIN_FILE ), array( 'farbtastic', 'magnific-popup', 'simpleslider' ), SU_PLUGIN_VERSION, 'all' );
		wp_register_script( 'su-generator', plugins_url( 'includes/js/generator/index.js', SU_PLUGIN_FILE ), array( 'farbtastic', 'magnific-popup', 'simpleslider' ), SU_PLUGIN_VERSION, true );
		wp_localize_script( 'su-generator', 'SUGL10n', array(
				'upload_title'         => __( 'Choose file', 'shortcodes-ultimate' ),
				'upload_insert'        => __( 'Insert', 'shortcodes-ultimate' ),
				'isp_media_title'      => __( 'Select images', 'shortcodes-ultimate' ),
				'isp_media_insert'     => __( 'Add selected images', 'shortcodes-ultimate' ),
				'presets_prompt_msg'   => __( 'Please enter a name for new preset', 'shortcodes-ultimate' ),
				'presets_prompt_value' => __( 'New preset', 'shortcodes-ultimate' ),
				'last_used'            => __( 'Last used settings', 'shortcodes-ultimate' ),
			) );
		// Shortcodes stylesheets
		wp_register_style( 'su-shortcodes', plugins_url( 'includes/css/shortcodes.css', SU_PLUGIN_FILE ), false, SU_PLUGIN_VERSION, 'all' );
		// Plugin Icons (Fork Awesome)
		wp_register_style( 'su-icons', plugins_url( 'includes/css/icons.css', SU_PLUGIN_FILE ), false, '1.1.5', 'all' );
		// DEPRECATED - Shortcodes stylesheets
		// wp_register_style( 'su-content-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// wp_register_style( 'su-box-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// wp_register_style( 'su-media-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// wp_register_style( 'su-other-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// wp_register_style( 'su-galleries-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// wp_register_style( 'su-players-shortcodes', '', false, SU_PLUGIN_VERSION, 'all' );
		// RTL stylesheets
		wp_register_style( 'su-rtl-shortcodes', plugins_url( 'includes/css/rtl-shortcodes.css', SU_PLUGIN_FILE ), false, SU_PLUGIN_VERSION, 'all' );
		wp_register_style( 'su-rtl-admin', plugins_url( 'admin/css/rtl-admin.css', SU_PLUGIN_FILE ), false, SU_PLUGIN_VERSION, 'all' );
		// Shortcodes scripts
		wp_register_script(
			'su-shortcodes',
			plugins_url( 'includes/js/shortcodes/index.js', SU_PLUGIN_FILE ),
			array( 'jquery' ),
			SU_PLUGIN_VERSION,
			true
		);
		wp_localize_script(
			'su-shortcodes',
			'SUShortcodesL10n',
			array(
				'noPreview'     => __( 'This shortcode doesn\'t work in live preview. Please insert it into editor and preview on the site.', 'shortcodes-ultimate' ),
				'magnificPopup' => array(
					'close'   => __( 'Close (Esc)', 'shortcodes-ultimate' ),
					'loading' => __( 'Loading...', 'shortcodes-ultimate' ),
					'prev'    => __( 'Previous (Left arrow key)', 'shortcodes-ultimate' ),
					'next'    => __( 'Next (Right arrow key)', 'shortcodes-ultimate' ),
					// translators: %1$s of %2$s represents image counter in lightbox, will be replaced with "1 of 5"
					'counter' => sprintf( __( '%1$s of %2$s', 'shortcodes-ultimate' ), '%curr%', '%total%' ),
					'error'   => sprintf(
						// translators: %1$s and %2$s will be replace <a> and </a> tags
						__( 'Failed to load content. %1$sOpen link%2$s' ),
						'<a href="%url%" target="_blank"><u>',
						'</u></a>'
					),
				),
			)
		);
		// Hook to deregister assets or add custom
		do_action( 'su/assets/register' );
	}

	/**
	 * Enqueue assets
	 */
	public static function enqueue() {
		// Get assets query and plugin object
		$assets = self::assets();
		// Enqueue stylesheets
		foreach ( $assets['css'] as $style ) wp_enqueue_style( $style );
		// Enqueue scripts
		foreach ( $assets['js'] as $script ) wp_enqueue_script( $script );
		// Hook to dequeue assets or add custom
		do_action( 'su/assets/enqueue', $assets );
	}

	/**
	 * Print assets without enqueuing
	 */
	public static function prnt() {
		// Prepare assets set
		$assets = self::assets();
		// Enqueue stylesheets
		wp_print_styles( $assets['css'] );
		// Enqueue scripts
		wp_print_scripts( $assets['js'] );
		// Hook
		do_action( 'su/assets/print', $assets );
	}

	/**
	 * Print custom CSS
	 */
	public static function custom_css() {

		// Get custom CSS and apply filters to it
		$custom_css = (string) apply_filters( 'su/assets/custom_css', get_option( 'su_option_custom-css' ) );

		$template = '%1$s<!-- %2$s - %3$s -->%1$s<style type="text/css">%1$s%5$s%1$s</style>%1$s<!-- %2$s - %4$s -->%1$s';
		$template = apply_filters( 'su/assets/custom_css/template', $template );

		if ( ! empty( $custom_css ) ) {

			$custom_css = str_replace(
				array( '%theme_url%', '%home_url%', '%plugin_url%' ),
				array(
					trailingslashit( get_stylesheet_directory_uri() ),
					trailingslashit( get_option( 'home' ) ),
					trailingslashit( plugins_url( '', SU_PLUGIN_FILE ) ),
				),
				$custom_css
			);

			printf(
				$template,
				PHP_EOL,
				'Shortcodes Ultimate custom CSS',
				'start',
				'end',
				strip_tags( $custom_css )
			);

		}

		// Hook
		do_action( 'su/assets/custom_css/after' );

	}

	/**
	 * RTL support for shortcodes
	 */
	public static function rtl_shortcodes( $assets ) {
		// Check RTL
		if ( !is_rtl() ) return;
		// Add RTL stylesheets
		wp_print_styles( array( 'su-rtl-shortcodes' ) );
	}

	/**
	 * RTL support for admin
	 */
	public static function rtl_admin( $assets ) {
		// Check RTL
		if ( !is_rtl() ) return;
		// Add RTL stylesheets
		self::add( 'css', 'su-rtl-admin' );
	}

	/**
	 * Add asset to the query
	 */
	public static function add( $type, $handle ) {
		// Array with handles
		if ( is_array( $handle ) ) { foreach ( $handle as $h ) self::$assets[$type][$h] = $h; }
		// Single handle
		else self::$assets[$type][$handle] = $handle;
	}

	/**
	 * Get queried assets
	 */
	public static function assets() {
		// Get assets query
		$assets = self::$assets;
		// Apply filters to assets set
		$assets['css'] = array_unique( ( array ) apply_filters( 'su/assets/css', ( array ) array_unique( $assets['css'] ) ) );
		$assets['js'] = array_unique( ( array ) apply_filters( 'su/assets/js', ( array ) array_unique( $assets['js'] ) ) );
		// Return set
		return $assets;
	}

	/**
	 * Helper to get full URL of a skin file
	 */
	public static function skin_url( $file = '' ) {
		$skin = get_option( 'su_option_skin' );
		$uploads = wp_upload_dir(); $uploads = $uploads['baseurl'];
		// Prepare url to skin directory
		$url = ( !$skin || $skin === 'default' ) ? plugins_url( 'assets/css/', SU_PLUGIN_FILE ) : $uploads . '/shortcodes-ultimate-skins/' . $skin;
		return trailingslashit( apply_filters( 'su/assets/skin', $url ) ) . $file;
	}
}

new Su_Assets;

/**
 * Helper function to add asset to the query
 *
 * @param string  $type   Asset type (css|js)
 * @param mixed   $handle Asset handle or array with handles
 */
function su_query_asset( $type, $handle ) {
	Su_Assets::add( $type, $handle );
}

/**
 * Helper function to get current skin url
 *
 * @param string  $file Asset file name. Example value: box-shortcodes.css
 */
function su_skin_url( $file ) {
	return Su_Assets::skin_url( $file );
}
