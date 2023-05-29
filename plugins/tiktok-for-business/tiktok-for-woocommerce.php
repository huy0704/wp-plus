<?php
/**
 * Copyright (c) Bytedance, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * Plugin Name: TikTok
 * Plugin URI: https://wordpress.org/plugins/tiktok-for-business
 * Description: With the TikTok x WooCommerce integration, it's easier than ever to unlock innovative social commerce features for your business to drive traffic and sales to a highly engaged community. With guided & simple setup prompts, you can sync your WooCommerce product catalog and promote it with custom ads without leaving your dashboard. Also, in just 1 click you can install the most-advanced TikTok pixel to unlock advanced visibility into detailed campaign performance tracking. Reach over 1 billion users, globally, and drive more e-commerce sales when you sell via one of the world’s most downloaded applications!
 * Author: TikTok
 * Version: 1.0.16
 *
 * Requires at least: 5.7.0
 * Tested up to: 6.1
 *
 * Woo:
 * WC requires at least: 2.6.0
 * WC tested up to: 7.1
 *
 * @package TikTok
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/mapi/Tt4b_Mapi_Class.php';
require_once __DIR__ . '/logging/Logger.php';
require_once __DIR__ . '/catalog/Tt4b_Catalog_Class.php';
require_once __DIR__ . '/pixel/Tt4b_Pixel_Class.php';
require_once __DIR__ . '/admin/tts/common.php';

use Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists;
use tiktok\admin\Tasks\Onboarding;

/**
 * The plugin loader class
 */
final class Tiktokforbusiness {

	/**
	 * The version of TikTok for WooCommerce
	 *
	 * @var string[]
	 */
	private static $current_tiktok_for_woocommerce_version = [
		'version' => '1.0.16',
	];

	/**
	 * Whether WooCommerce has been loaded.
	 *
	 * @var bool
	 */
	private static $woocommerce_loaded = false;

	/**
	 * Initializes hooks.
	 *
	 * This should be hooked to the 'woocommerce_loaded' action.
	 *
	 * @return void
	 */
	public function initialize_hooks() {
		self::$woocommerce_loaded = did_action( 'woocommerce_loaded' ) > 0;
		if ( ! self::$woocommerce_loaded ) {
			return;
		}

		require_once __DIR__ . '/admin/tts/order_list.php';
		require_once __DIR__ . '/admin/tts/order_detail.php';
		require_once __DIR__ . '/admin/Tasks/Onboarding.php';
		require_once __DIR__ . '/admin/tt4b_menu.php';
		require_once __DIR__ . '/pixel/tt4b_pixel.php';

		$this->init();

		register_deactivation_hook( __FILE__, [ self::class, 'tt_plugin_deactivate' ] );
		register_activation_hook( __FILE__, [ self::class, 'tt_plugin_activate' ] );
	}

	/**
	 * Initialize most of the plugin logic.
	 *
	 * @return void
	 */
	private function init() {
		if ( get_option( 'tt4b_version' ) !== json_encode( self::$current_tiktok_for_woocommerce_version ) ) {
			update_option( 'tt4b_version', json_encode( self::$current_tiktok_for_woocommerce_version ) );
		}

		$logger  = new Logger( wc_get_logger() );
		$mapi    = new Tt4b_Mapi_Class( $logger );
		$catalog = new Tt4b_Catalog_Class( $mapi, $logger );
		$mapi->init();
		$catalog->init();

		// Hook the onboarding task. The hook admin_init is not triggered when the WC fetches the tasks using the endpoint: wp-json/wc-admin/onboarding/tasks and hence hooking into init.
		add_action( 'init', [ $this, 'add_onboarding_task' ], 20 );
	}

	/**
	 * Adds the onboarding task to the Tasklists.
	 *
	 * @since x.x.x
	 */
	public function add_onboarding_task() {
		if ( ! class_exists( TaskLists::class ) ) {
			// WC 5.9 backward compatibility.
			return;
		}

		TaskLists::add_task(
			'extended',
			new Onboarding(
				TaskLists::get_list( 'extended' )
			)
		);
	}

	/**
	 * Show an admin notice if WooCommerce hasn't been loaded.
	 *
	 * @return void
	 */
	public function maybe_show_admin_notice() {
		if ( self::$woocommerce_loaded ) {
			return;
		}

		?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'TikTok for WooCommerce requires WooCommerce version 7.0 or higher to be enabled.', 'tiktok-for-business' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Deactivates plugin.
	 *
	 * @return void
	 */
	public static function tt_plugin_deactivate() {
		// call disconnect API
		$access_token         = get_option( 'tt4b_access_token' );
		$external_business_id = get_option( 'tt4b_external_business_id' );
		$app_id               = get_option( 'tt4b_app_id' );
		$params               = [
			'external_business_id' => $external_business_id,
			'business_platform'    => 'WOO_COMMERCE',
			'is_setup_page'        => 0,
			'app_id'               => $app_id,
		];

		// delete scheduled TikTok related actions
		as_unschedule_all_actions( 'tt4b_trust_signal_collection' );
		as_unschedule_all_actions( 'tt4b_trust_signal_helper' );
		as_unschedule_all_actions( 'tt4b_catalog_sync' );
		as_unschedule_all_actions( 'tt4b_catalog_sync_helper' );

		$mapi = new Tt4b_Mapi_Class( new Logger( wc_get_logger() ) );
		$mapi->mapi_post( 'tbp/business_profile/disconnect/', $access_token, $params );

		// delete tiktok credentials
		delete_option( 'tt4b_app_id' );
		delete_option( 'tt4b_secret' );
		delete_option( 'tt4b_access_token' );
		delete_option( 'tt4b_external_data_key' );

		// call tts disconnect
		$external_data = get_option( 'tt4b_external_data' );
		$mapi->tts_shop_disconnect( $external_data );

		delete_option( 'tt4b_external_data' );
		delete_option( 'tt4b_catalog_page_total' );
		delete_option( 'tt4b_eligibility_page_total' );
		delete_option( 'tt4b_version' );
		delete_option( 'tt4b_mapi_total_gmv' );
		delete_option( 'tt4b_mapi_total_orders' );
		delete_option( 'tt4b_mapi_tenure' );
		delete_option( 'tt4b_should_send_s2s_events' );
	}

	/**
	 * Generates app credentials.
	 *
	 * @return void
	 */
	public static function tt_plugin_activate() {
		$mapi                 = new Tt4b_Mapi_Class( new Logger( wc_get_logger() ) );
		$external_business_id = get_option( 'tt4b_external_business_id' );
		if ( false === $external_business_id ) {
			$external_business_id = uniqid( 'tt4b_woocommerce_' );
			update_option( 'tt4b_external_business_id', $external_business_id );
		}
		add_option( 'tt4b_catalog_page_total', 0 );
		add_option( 'tt4b_eligibility_page_total', 0 );
		add_option( 'tt4b_version', json_encode( self::$current_tiktok_for_woocommerce_version ) );
		add_option( 'tt4b_mapi_total_gmv', 0 );
		add_option( 'tt4b_mapi_total_orders', 0 );
		add_option( 'tt4b_mapi_tenure', 0 );
		$cleaned_redirect = preg_replace( '/[^A-Za-z0-9\-]/', '', admin_url() );
		$smb_id           = $external_business_id . $cleaned_redirect;
		$app_rsp          = $mapi->create_open_source_app( $smb_id, 'PROD', admin_url() );
		if ( false !== $app_rsp ) {
			$open_source_app_rsp = json_decode( $app_rsp, true );
			$app_id              = $open_source_app_rsp['data']['app_id'];
			$secret              = $open_source_app_rsp['data']['app_secret'];
			$external_data_key   = $open_source_app_rsp['data']['external_data_key'];
			update_option( 'tt4b_app_id', $app_id );
			update_option( 'tt4b_secret', $secret );
			update_option( 'tt4b_external_data_key', $external_data_key );
		}
	}
}

/**
 * Get the instance of the Tiktokforbusiness class.
 *
 * @return Tiktokforbusiness
 */
function tiktok_for_business_get_instance() {
	static $instance = null;
	if ( null === $instance ) {
		$instance = new Tiktokforbusiness();
	}

	return $instance;
}

add_action(
	'woocommerce_loaded',
	function() {
		tiktok_for_business_get_instance()->initialize_hooks();
	}
);

add_action(
	'admin_notices',
	function() {
		tiktok_for_business_get_instance()->maybe_show_admin_notice();
	}
);
