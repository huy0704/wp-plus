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

class Tt4b_Catalog_Class {


	/**
	 * The TikTok Mapi Class used to make various requests to TikTok
	 *
	 * @var Tt4b_Mapi_Class
	 */
	protected $mapi;

	/**
	 * The woocommerce logger
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param Tt4b_Mapi_Class $mapi   The Tt4b_Mapi_Class
	 * @param Logger          $logger
	 *
	 * @return void
	 */
	public function __construct( Tt4b_Mapi_Class $mapi, Logger $logger ) {
		$this->mapi   = $mapi;
		$this->logger = $logger;
	}

	/**
	 * Initializes actions related to Tt4b_Catalog_Class such as catalog sync functionality used by action_scheduler
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'tt4b_catalog_sync_helper', [ $this, 'catalog_sync_helper' ], 2, 5 );
		add_action( 'tt4b_catalog_sync', [ $this, 'catalog_sync' ], 1, 4 );
	}

	/**
	 * Returns the amount of catalog items are in approved/processing/rejected.
	 *
	 * @param string $access_token The MAPI issued access token
	 * @param string $bc_id        The users business center ID
	 * @param string $catalog_id   The users catalog ID
	 *
	 * @return array(processing, approved, rejected)
	 */
	public static function get_catalog_processing_status(
		$access_token,
		$bc_id,
		$catalog_id
	) {
		// returns a counter of how many items are approved, processing, or rejected
		// from the TikTok catalog/product/get/ endpoint
		$logger = new Logger( wc_get_logger() );
		$mapi   = new Tt4b_Mapi_Class( $logger );

		$url    = 'catalog/overview/';
		$params = [
			'bc_id'      => $bc_id,
			'catalog_id' => $catalog_id,
		];
		$base   = [
			'processing' => 0,
			'approved'   => 0,
			'rejected'   => 0,
		];

		$result = $mapi->mapi_get( $url, $access_token, $params );
		$obj    = json_decode( $result, true );

		if ( ! isset( $obj['data'] ) ) {
			$logger->log( __METHOD__, 'get_catalog_processing_status data not set' );
			return $base;
		}

		if ( 'OK' !== $obj['message'] ) {
			$logger->log( __METHOD__, 'get_catalog_processing_status not OK response' );
			return $base;
		}

		$processing = $obj['data']['processing'];
		$approved   = $obj['data']['approved'];
		$rejected   = $obj['data']['rejected'];

		return [
			'processing' => $processing,
			'approved'   => $approved,
			'rejected'   => $rejected,
		];
	}

	/**
	 * Begins catalog sync, if there is not one currently enqueued. Schedules recurring catalog sync on an hourly basis.
	 *
	 * @param string $catalog_id   The users catalog ID
	 * @param string $bc_id        The users business center ID
	 * @param string $store_name   The users store name
	 * @param string $access_token The MAPI issued access token
	 *
	 * @return void
	 */
	public function initiate_catalog_sync( $catalog_id, $bc_id, $store_name, $access_token ) {
		if ( false === as_has_scheduled_action( 'tt4b_catalog_sync_helper' ) && false === as_has_scheduled_action(
			'tt4b_catalog_sync',
			[
				'catalog_id'   => $catalog_id,
				'bc_id'        => $bc_id,
				'store_name'   => $store_name,
				'access_token' => $access_token,
			],
			'tt4b_management_catalog_sync'
		)
		) {
			as_enqueue_async_action(
				'tt4b_catalog_sync',
				[
					'catalog_id'   => $catalog_id,
					'bc_id'        => $bc_id,
					'store_name'   => $store_name,
					'access_token' => $access_token,
				],
				'tt4b_management_catalog_sync'
			);
		}
		if ( false === as_has_scheduled_action(
			'tt4b_catalog_sync',
			[
				'catalog_id'   => $catalog_id,
				'bc_id'        => $bc_id,
				'store_name'   => $store_name,
				'access_token' => $access_token,
			],
			'tt4b_scheduled_catalog_sync'
		)
		) {
			as_schedule_cron_action(
				'today',
				'0 0-23 * * *',
				'tt4b_catalog_sync',
				[
					'catalog_id'   => $catalog_id,
					'bc_id'        => $bc_id,
					'store_name'   => $store_name,
					'access_token' => $access_token,
				],
				'tt4b_scheduled_catalog_sync'
			);
		}
	}

	/**
	 * Sync merchant catalog from woocommerce store to TikTok catalog manager via creation of catalog_sync_helper functions for batches of products
	 *
	 * @param string $catalog_id   The users catalog ID
	 * @param string $bc_id        The users business center ID
	 * @param string $store_name   The users store name
	 * @param string $access_token The MAPI issued access token
	 *
	 * @return void
	 */
	public function catalog_sync( $catalog_id, $bc_id, $store_name, $access_token ) {
		if ( '' === $catalog_id ) {
			$this->logger->log( __METHOD__, 'missing catalog_id for full catalog sync' );
			return;
		}
		if ( '' === $bc_id ) {
			$this->logger->log( __METHOD__, 'missing bc_id for full catalog sync' );
			return;
		}
		if ( '' === $access_token || false === $access_token ) {
			$this->logger->log( __METHOD__, 'missing access token for full catalog sync' );
			return;
		}
		// store_name just used for brand, can default it.
		if ( '' === $store_name ) {
			$store_name = 'WOO_COMMERCE';
		}
		$args   = [
			'paginate' => true,
			'limit'    => 100,
		];
		$result = wc_get_products( $args );
		$pages  = $result->max_num_pages;
		update_option( 'tt4b_catalog_page_total', $pages );
		if ( false === as_has_scheduled_action(
			'tt4b_catalog_sync_helper',
			[
				'catalog_id'   => $catalog_id,
				'bc_id'        => $bc_id,
				'store_name'   => $store_name,
				'access_token' => $access_token,
				'page'         => 1,
			]
		)
		) {
			as_enqueue_async_action(
				'tt4b_catalog_sync_helper',
				[
					'catalog_id'   => $catalog_id,
					'bc_id'        => $bc_id,
					'store_name'   => $store_name,
					'access_token' => $access_token,
					'page'         => 1,
				]
			);
		}
	}

	/**
	 * Helper function used to post batches of products from woocommerce store to tiktok catalog manager
	 *
	 * @param string  $catalog_id   The users catalog ID
	 * @param string  $bc_id        The users business center ID
	 * @param string  $store_name   The users store name
	 * @param string  $access_token The MAPI issued access token
	 * @param integer $page         The page of products from the user catalog
	 *
	 * @return void
	 */
	public function catalog_sync_helper( $catalog_id, $bc_id, $store_name, $access_token, $page ) {
		$args         = [
			'limit' => 100,
			'page'  => $page,
		];
		$dpa_products = [];
		$products     = wc_get_products( $args );
		if ( 0 === count( $products ) ) {
			$this->logger->log( __METHOD__, 'no products retrieved from wc_get_products' );
		}
		$failed_products_count = 0;
		foreach ( $products as $product ) {
			if ( is_null( $product ) ) {
				$failed_products_count++;
				continue;
			}
			$this->logger->log( __METHOD__, "product retrieved: $product" );
			$title       = $product->get_name();
			$description = $product->get_short_description();
			if ( '' === $description ) {
				$description = $title;
			}
			$condition = 'NEW';

			$availability = 'IN_STOCK';
			$stock_status = $product->is_in_stock();
			if ( false === $stock_status ) {
				$availability = 'OUT_OF_STOCK';
			}
			$sku_id = (string) $product->get_sku();
			if ( '' === $sku_id ) {
				$sku_id = (string) $product->get_id();
			}
			$link       = get_permalink( $product->get_id() );
			$image_id   = $product->get_image_id();
			$image_url  = wp_get_attachment_image_url( $image_id, 'full' );
			$price      = $product->get_price();
			$sale_price = $product->get_sale_price();
			if ( '0' === $sale_price || '' === $sale_price ) {
				$sale_price = $price;
			}
			// Get product gallery images - max 10
			$gallery_image_ids  = array_slice( $product->get_gallery_image_ids(), 0, 10, true );
			$gallery_image_urls = [];
			foreach ( $gallery_image_ids as $gallery_image_id ) {
				$gallery_image_urls[] = wp_get_attachment_image_url( $gallery_image_id, 'full' );
			}

			// if any of the values are empty, the whole request will fail, so skip the product.
			$missing_fields = [];
			if ( '' === $sku_id || false === $sku_id ) {
				$missing_fields[] = 'sku_id';
			}
			if ( '' === $title || false === $title ) {
				$missing_fields[] = 'title';
			}
			if ( '' === $image_url || false === $image_url ) {
				$missing_fields[] = 'image_url';
			}
			if ( '' === $price || false === $price || '0' === $price ) {
				$missing_fields[] = 'price';
			}
			if ( count( $missing_fields ) > 0 ) {
				$debug_message = sprintf(
					'sku_id: %s title: %s is missing the following fields for product sync: %s',
					$sku_id,
					$title,
					join( ',', $missing_fields )
				);
				$this->logger->log( __METHOD__, $debug_message );
				continue;
			}

			$dpa_product = [
				'sku_id'        => $sku_id,
				'item_group_id' => $sku_id,
				'title'         => $title,
				'availability'  => $availability,
				'description'   => $description,
				'image_link'    => $image_url,
				'brand'         => $store_name,
				'profession'    => [
					'condition' => $condition,
				],
				'price'         => [
					'price'      => $price,
					'sale_price' => $sale_price,
				],
				'landing_url'   => [
					'link' => $link,
				],
			];

			// add additional product images if available
			if ( count( $gallery_image_urls ) > 0 ) {
				$dpa_product['additional_image_link'] = $gallery_image_urls;
			}

			$dpa_products[] = $dpa_product;
		}
		$count = count( $dpa_products );
		$this->logger->log( __METHOD__, "product_page: $page product_count: $count" );
		$dpa_product_information = [
			'bc_id'        => $bc_id,
			'catalog_id'   => $catalog_id,
			'dpa_products' => $dpa_products,
		];
		$this->mapi->mapi_post( 'catalog/product/upload/', $access_token, $dpa_product_information );
		$page_total = get_option( 'tt4b_catalog_page_total' );
		$page++;
		if ( ( $page <= $page_total ) && ( false === as_has_scheduled_action(
			'tt4b_catalog_sync_helper',
			[
				'catalog_id'   => $catalog_id,
				'bc_id'        => $bc_id,
				'store_name'   => $store_name,
				'access_token' => $access_token,
				'page'         => $page,
			]
		) )
		) {
			as_enqueue_async_action(
				'tt4b_catalog_sync_helper',
				[
					'catalog_id'   => $catalog_id,
					'bc_id'        => $bc_id,
					'store_name'   => $store_name,
					'access_token' => $access_token,
					'page'         => $page,
				]
			);
		}
	}
}
