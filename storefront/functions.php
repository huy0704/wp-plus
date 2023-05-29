<?php

/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme('storefront');
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if (class_exists('Jetpack')) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if (storefront_is_woocommerce_activated()) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if (is_admin()) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if (version_compare(get_bloginfo('version'), '4.7.3', '>=') && (is_admin() || is_customize_preview())) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}
/*Thay đổi logo trang đăng nhập*/
function login_page_logo()
{
	echo '<style>.login h1 a {
	background-repeat: no-repeat;
	background-image: url(http://localhost/wordpress-plus/wp-content/uploads/2023/04/logo2-1.png);
	background-position: center center;
	background-size: contain !important;
	width: 290px;
	height: 180px;
	}
	</style>';
}
add_action('login_head', 'login_page_logo');

/*Thay đổi link url logo trang đăng nhập*/
function login_page_URL($url)
{
	$url = home_url('/');
	return $url;
}
add_filter('login_headerurl', 'login_page_URL');

/**  */
function create_post_type()
{
	register_post_type(
		'custom_post',
		array(
			'labels' => array(
				'name' => __('Slider'),
				'singular_name' => __('Bài viết tùy chỉnh')
			),
			'public' => true,
			'has_archive' => true,
		)
	);
}
add_action('init', 'create_post_type');

function create_custom_taxonomy() {
    register_taxonomy(
        'topics',

		
        'custom_post',
        array(
            'label' => __( 'Category Slider' ),
            'rewrite' => array( 'slug' => 'topics' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_custom_taxonomy' );

/* Add bootstrap support to the Wordpress theme*/

function theme_add_bootstrap() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );
	// wp_enqueue_style( 'style-css', get_template_directory_uri() . '/style.css' );
	// wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '3.0.0', true );
	}
	
	add_action( 'wp_enqueue_scripts', 'theme_add_bootstrap' );
/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
