<?php
/**
 * Copyright (c) Bytedance, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package TikTok
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Tt4b_Pixel_Class {
	// TTCLID Cookie name
	const TTCLID_COOKIE = 'tiktok_ttclid';


	/**
	 * Fires the add to cart event
	 *
	 * @param string $cart_item_key The cart item id
	 * @param string $product_id    The product id
	 * @param string $quantity      The quanity of products
	 * @param string $variation_id  The variant id
	 *
	 * @return void
	 */
	public static function inject_add_to_cart_event( $cart_item_key, $product_id, $quantity, $variation_id ) {
		$logger = new Logger( wc_get_logger() );
		$logger->log( __METHOD__, 'hit injectAddToCartEvent' );
		$mapi    = new Tt4b_Mapi_Class( $logger );
		$product = wc_get_product( $product_id );

		$fields = self::pixel_event_tracking_field_track( __METHOD__ );
		if ( 0 === count( $fields ) ) {
			return;
		}

		$pixel_obj              = new Tt4b_Pixel_Class();
		$should_send_event_data = $pixel_obj->confirm_to_send_s2s_events( $fields['access_token'], $fields['advertiser_id'], $fields['pixel_code'] );
		if ( ! $should_send_event_data ) {
			$logger->log( __METHOD__, 'will not send event data for this pixel' );
			return;
		}

		$event        = 'AddToCart';
		$current_user = wp_get_current_user();

		$email = $current_user->user_email;

		$hashed_email = $pixel_obj->get_advanced_matching_hashed_email( $email );
		$timestamp    = gmdate( 'c', time() );
		$ipaddress    = WC_Geolocation::get_ip_address();
		$content_id   = (string) $product->get_sku();
		if ( '' === $content_id ) {
			$content_id = (string) $product->get_id();
		}
		$price      = $product->get_price();
		$user_agent = '';
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
		$url = '';
		if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
		$properties = [
			'contents' => [
				[
					'price'        => (int) $price,
					'quantity'     => (int) $quantity,
					'content_type' => 'product',
					'content_id'   => strval( $content_id ),
				],
			],
		];

		$context = [
			'page'       => [
				'url' => $url,
			],
			'ip'         => $ipaddress,
			'user_agent' => $user_agent,
			'user'       => [
				'email' => $hashed_email,
			],
		];

		$context = self::get_ttclid( $context ); // add ttclid if available

		$params = [
			'partner_name' => 'WooCommerce',
			'pixel_code'   => $fields['pixel_code'],
			'event'        => $event,
			'timestamp'    => $timestamp,
			'properties'   => $properties,
			'context'      => $context,
		];
		$mapi->mapi_post( 'pixel/track/', $fields['access_token'], $params );
	}

	/**
	 * Fires the view content event
	 *
	 * @return void
	 */
	public static function inject_view_content_event() {
		$logger = new Logger( wc_get_logger() );
		$logger->log( __METHOD__, 'hit injectViewContentEvent' );
		$mapi = new Tt4b_Mapi_Class( new Logger( wc_get_logger() ) );
		global $post;
		if ( ! isset( $post->ID ) ) {
			return;
		}
		$fields = self::pixel_event_tracking_field_track( __METHOD__ );
		if ( 0 === count( $fields ) ) {
			return;
		}

		$pixel_obj              = new Tt4b_Pixel_Class();
		$should_send_event_data = $pixel_obj->confirm_to_send_s2s_events( $fields['access_token'], $fields['advertiser_id'], $fields['pixel_code'] );
		if ( ! $should_send_event_data ) {
			$logger->log( __METHOD__, 'will not send event data for this pixel' );
			return;
		}

		$event        = 'ViewContent';
		$current_user = wp_get_current_user();
		$email        = $current_user->user_email;
		$hashed_email = $pixel_obj->get_advanced_matching_hashed_email( $email );
		$timestamp    = gmdate( 'c', time() );
		$ipaddress    = WC_Geolocation::get_ip_address();
		$product      = wc_get_product( $post->ID );
		$content_id   = (string) $product->get_sku();
		if ( '' === $content_id ) {
			$content_id = (string) $product->get_id();
		}
		$price      = $product->get_price();
		$user_agent = '';
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
		$url = '';
		if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
		$properties = [
			'contents' => [
				[
					'price'        => (int) $price,
					'content_id'   => strval( $content_id ),
					'content_type' => 'product',
				],
			],
		];

		$context = [
			'page'       => [
				'url' => $url,
			],
			'ip'         => $ipaddress,
			'user_agent' => $user_agent,
			'user'       => [
				'email' => $hashed_email,
			],
		];

		$context = self::get_ttclid( $context ); // add ttclid if available

		$params = [
			'partner_name' => 'WooCommerce',
			'pixel_code'   => $fields['pixel_code'],
			'event'        => $event,
			'timestamp'    => $timestamp,
			'properties'   => $properties,
			'context'      => $context,
		];
		$mapi->mapi_post( 'pixel/track/', $fields['access_token'], $params );
	}

	/**
	 * Fires the purchase event
	 *
	 * @param string $order_id the order id
	 *
	 * @return void
	 */
	public static function inject_purchase_event( $order_id ) {
		$logger = new Logger( wc_get_logger() );
		$logger->log( __METHOD__, 'hit injectPurchaseEvent' );
		$mapi   = new Tt4b_Mapi_Class( $logger );
		$fields = self::pixel_event_tracking_field_track( __METHOD__ );
		if ( 0 === count( $fields ) ) {
			return;
		}

		$pixel_obj              = new Tt4b_Pixel_Class();
		$should_send_event_data = $pixel_obj->confirm_to_send_s2s_events( $fields['access_token'], $fields['advertiser_id'], $fields['pixel_code'] );
		if ( ! $should_send_event_data ) {
			$logger->log( __METHOD__, 'will not send event data for this pixel' );
			return;
		}

		$event = 'Purchase';
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}
		$value    = 0;
		$contents = [];
		foreach ( $order->get_items() as $item ) {
			$product    = $item->get_product();
			$price      = (int) $product->get_price();
			$quantity   = $item->get_quantity();
			$content_id = (string) $product->get_sku();
			if ( '' === $content_id ) {
				$content_id = (string) $product->get_id();
			}
			$content = [
				'price'        => $price,
				'content_id'   => $content_id,
				'content_type' => 'product',
				'quantity'     => (int) $quantity,
			];
			$value  += $quantity * $price;
			array_push( $contents, $content );
		}
		$current_user = wp_get_current_user();
		$email        = $current_user->user_email;

		$hashed_email = $pixel_obj->get_advanced_matching_hashed_email( $email );
		$timestamp    = gmdate( 'c', time() );
		$ipaddress    = WC_Geolocation::get_ip_address();
		$user_agent   = '';
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
		$url = '';
		if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}

		$properties = [
			'contents' => $contents,
			'value'    => $value,
		];

		$context = [
			'page'       => [
				'url' => $url,
			],
			'ip'         => $ipaddress,
			'user_agent' => $user_agent,
			'user'       => [
				'email' => $hashed_email,
			],
		];

		$context = self::get_ttclid( $context ); // add ttclid if available

		$params = [
			'partner_name' => 'WooCommerce',
			'pixel_code'   => $fields['pixel_code'],
			'event'        => $event,
			'timestamp'    => $timestamp,
			'properties'   => $properties,
			'context'      => $context,
		];
		$mapi->mapi_post( 'pixel/track/', $fields['access_token'], $params );
	}

	/**
	 * Fires the start checkout event
	 *
	 * @return void
	 */
	public static function inject_start_checkout() {
		$logger = new Logger( wc_get_logger() );
		$logger->log( __METHOD__, 'hit injectStartCheckout' );
		$mapi = new Tt4b_Mapi_Class( $logger );
		// if registration required, and can't register in checkout and user not logged in, don't fire event
		if ( ! WC()->checkout()->is_registration_enabled()
			&& WC()->checkout()->is_registration_required()
			&& ! is_user_logged_in()
		) {
			return;
		}
		$fields = self::pixel_event_tracking_field_track( __METHOD__ );
		if ( 0 === count( $fields ) ) {
			return;
		}

		$pixel_obj              = new Tt4b_Pixel_Class();
		$should_send_event_data = $pixel_obj->confirm_to_send_s2s_events( $fields['access_token'], $fields['advertiser_id'], $fields['pixel_code'] );
		if ( ! $should_send_event_data ) {
			$logger->log( __METHOD__, 'will not send event data for this pixel' );
			return;
		}

		$event        = 'InitiateCheckout';
		$current_user = wp_get_current_user();
		$email        = $current_user->user_email;

		$hashed_email = $pixel_obj->get_advanced_matching_hashed_email( $email );
		$timestamp    = gmdate( 'c', time() );
		$ipaddress    = WC_Geolocation::get_ip_address();
		$user_agent   = '';
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
		$url = '';
		if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}

		$contents = [];
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product    = $cart_item['data'];
			$quantity   = $cart_item['quantity'];
			$subtotal   = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
			$content_id = (string) $product->get_sku();
			if ( '' === $content_id ) {
				$content_id = (string) $product->get_id();
			}
			$content = [
				'price'        => (int) $subtotal,
				'content_id'   => $content_id,
				'content_type' => 'product',
				'quantity'     => (int) $quantity,
			];
			array_push( $contents, $content );
		}

		$properties = [
			'contents' => $contents,
		];

		$context = [
			'page'       => [
				'url' => $url,
			],
			'ip'         => $ipaddress,
			'user_agent' => $user_agent,
			'user'       => [
				'email' => $hashed_email,
			],
		];

		$context = self::get_ttclid( $context ); // add ttclid if available

		$params = [
			'partner_name' => 'WooCommerce',
			'pixel_code'   => $fields['pixel_code'],
			'event'        => $event,
			'timestamp'    => $timestamp,
			'properties'   => $properties,
			'context'      => $context,
		];

		$mapi->mapi_post( 'pixel/track/', $fields['access_token'], $params );
	}

	/**
	 *  Gets all pixels associated to an ad account.
	 *
	 * @param string $access_token  The MAPI issued access token.
	 * @param string $advertiser_id The users advertiser id.
	 * @param string $pixel_code    The users pixel code.
	 */
	public function get_pixels( $access_token, $advertiser_id, $pixel_code ) {
		// returns a raw API response from TikTok pixel/list/ endpoint
		$params = [
			'advertiser_id' => $advertiser_id,
			'code'          => $pixel_code,
		];
		$url    = 'https://business-api.tiktok.com/open_api/v1.3/pixel/list/?' . http_build_query( $params );
		$args   = [
			'method'  => 'GET',
			'headers' => [
				'Access-Token' => $access_token,
				'Content-Type' => 'application/json',
			],
		];
		$logger = new Logger( wc_get_logger() );
		$logger->log_request( $url, $args );
		$result = wp_remote_get( $url, $args );
		$logger->log_response( __METHOD__, $result );
		return wp_remote_retrieve_body( $result );
	}

	/**
	 *  Gets whether advanced matching is enabled for the user.
	 *
	 * @param string $access_token  The MAPi issued access token
	 * @param string $advertiser_id The users advertiser id
	 * @param string $pixel_code    The users pixel code
	 * @param string $email         The users email
	 *
	 * @return false|string
	 */
	public function get_advanced_matching_hashed_email( $email ) {
		// returns the SHA256 encrypted email if advanced_matching is enabled. If advanced_matching is not
		// enabled, then return an empty string
		$advanced_matching = get_option( 'tt4b_advanced_matching' );
		$hashed_email      = '';
		if ( $advanced_matching ) {
			$hashed_email = hash( 'SHA256', strtolower( $email ) );
		}
		return $hashed_email;
	}

	/**
	 *  Preprocess to ensure we have the required fields to call the event track API
	 *
	 * @param string $method The hook that is executed.
	 *
	 * @return array
	 */
	public static function pixel_event_tracking_field_track( $method ) {
		$logger = new Logger( wc_get_logger() );
		try {
			$access_token  = self::get_and_validate_option( 'access_token' );
			$pixel_code    = self::get_and_validate_option( 'pixel_code' );
			$advertiser_id = self::get_and_validate_option( 'advertiser_id' );
		} catch ( Exception $e ) {
			$logger->log( $method, $e->getMessage() );
			return [];
		}
		return [
			'access_token'  => $access_token,
			'advertiser_id' => $advertiser_id,
			'pixel_code'    => $pixel_code,
		];
	}

	/**
	 *  Validates to ensure tt4b options are stored, and return the option if it is.
	 *
	 * @param string $option_name The tt4b data option
	 * @param bool   $default     The default option boolean
	 *
	 * @return string
	 * @throws Exception          Throws exception when the given option is missing.
	 */
	protected static function get_and_validate_option( $option_name, $default = false ) {
		$option = get_option( "tt4b_{$option_name}", $default );
		if ( false === $option ) {
			throw new Exception( sprintf( 'Missing option "%s"', $option_name ) );
		}

		return $option;
	}

	/**
	 *  Checks to see whether to track events s2s
	 *
	 * @param string $access_token The access token
	 * @param string $advertiser_id The advertiser_id
	 * @param string $pixel_code The pixel_code
	 *
	 * @return bool
	 */
	public function confirm_to_send_s2s_events( $access_token, $advertiser_id, $pixel_code ) {
		$should_send_events = get_option( 'tt4b_should_send_s2s_events' );
		if ( false === $should_send_events ) {
			$pixel_obj          = new Tt4b_Pixel_Class();
			$pixel_rsp          = $pixel_obj->get_pixels(
				$access_token,
				$advertiser_id,
				$pixel_code
			);
			$pixel              = json_decode( $pixel_rsp, true );
			// case 1: always send events for woo_commerce pixels
			update_option( 'tt4b_should_send_s2s_events', 'YES' );
			if ( '' !== $pixel ) {
				$connected_pixel = $pixel['data']['pixels'][0];
				$partner         = $connected_pixel['partner_name'];
				if ( 'WOO_COMMERCE' !== $partner ) {
					update_option( 'tt4b_should_send_s2s_events', 'NO' );
					// case 2: if the pixel is not a partner pixel, send events if no recent activity
					if ( 'ACTIVE' !== $connected_pixel['activity_status'] ) {
						update_option( 'tt4b_should_send_s2s_events', 'YES' );
					}
				}
			}
		}

		$should_send_event_data = get_option( 'tt4b_should_send_s2s_events' );
		if ( 'NO' === $should_send_event_data ) {
			return false;
		}
		return true;
	}


	/**
	 *  Grab ttclid from URL and set cookie for 30 days
	 */
	public static function set_ttclid() {
		if ( isset( $_GET['ttclid'] ) ) {
			setcookie( self::TTCLID_COOKIE, sanitize_text_field( $_GET['ttclid'] ), time() + 30 * 86400, '/' );
		}
	}


	/**
	 *  Add ttclid if it is available
	 *
	 * @param string $context       The pixel context
	 *
	 * @return context|object
	 */
	protected static function get_ttclid( $context ) {
		if ( isset( $_COOKIE[ self::TTCLID_COOKIE ] ) ) {
			// TTCLID cookie is set, append it to the $context
			$context['ad'] = [
				'callback' => sanitize_text_field( $_COOKIE[ self::TTCLID_COOKIE ] ),
			];
		}

		return $context;
	}
}
