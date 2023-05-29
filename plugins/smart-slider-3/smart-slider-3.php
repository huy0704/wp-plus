<?php
/*
Plugin Name: Smart Slider 3
Plugin URI: https://smartslider3.com/
Description: The perfect all-in-one responsive slider solution for WordPress.
Version: 3.5.1.14
Requires PHP: 7.0
Requires at least: 5.0
Author: Nextend
Author URI: https://smartslider3.com
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('SMARTSLIDER3_LIBRARY_PATH')) {
    define('SMARTSLIDER3_LIBRARY_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Nextend');
}

if (!version_compare(PHP_VERSION, '7.0', '>=')) {

    require_once SMARTSLIDER3_LIBRARY_PATH . '/WordPress/Fail.php';
    add_action('admin_notices', 'smartslider3_fail_php_version');

} else if (!version_compare(get_bloginfo('version'), '5.0', '>=')) {

    require_once SMARTSLIDER3_LIBRARY_PATH . '/WordPress/Fail.php';
    add_action('admin_notices', 'smartslider3_fail_wp_version');

} else if (!defined('NONCE_SALT')) {

    require_once SMARTSLIDER3_LIBRARY_PATH . '/WordPress/Fail.php';
    add_action('admin_notices', 'smartslider3_fail_nonce_salt');

} else if (!function_exists('smart_slider_3_plugins_loaded')) {
    define('NEXTEND_SMARTSLIDER_3_FREE_BASENAME', plugin_basename(__FILE__));
    define('NEXTEND_SMARTSLIDER_3_FREE_SLUG', 'smart-slider-3');

    require_once dirname(__FILE__) . '/plugin.php';
}