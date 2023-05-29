<?php
/**
 * Copyright (c) Bytedance, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package TikTok
 */
require_once 'Tt4b_Menu_Class.php';
add_action( 'admin_menu', [ 'tt4b_menu_class', 'tt4b_admin_menu' ] );
add_action( 'admin_head', [ 'tt4b_menu_class', 'tt4b_store_access_token' ] );
add_action( 'admin_head', 'check_woocommerce_install' );
add_action( 'save_post', 'tt4b_product_sync', 10, 3 );
add_action( 'delete_post', 'tt4b_product_delete', 10, 2 );
add_action( 'trashed_post', 'tt4b_product_trashed' );
add_action( 'untrashed_post', 'tt4b_product_untrashed' );

/**
 * Updates on product change
 *
 * @param string $post_id The product_id.
 * @param string $post    The post.
 * @param string $update  The update.
 *
 * @return void
 */
function tt4b_product_sync( $post_id, $post, $update = null ) {
	if ( 'product' !== $post->post_type ) {
		return;
	}
	$product = wc_get_product( $post_id );
	if ( is_null( $product ) ) {
		return;
	}
	$logger = new Logger( wc_get_logger() );

	$access_token = get_option( 'tt4b_access_token' );
	$catalog_id   = get_option( 'tt4b_catalog_id' );
	$bc_id        = get_option( 'tt4b_bc_id' );
	$shop_name    = get_bloginfo( 'name' );
	if ( false === $access_token ) {
		$logger->log( __METHOD__, 'missing access token for tt4b_product_sync' );
		return;
	}
	if ( '' === $catalog_id ) {
		$logger->log( __METHOD__, 'missing catalog_id for tt4b_product_sync' );
		return;
	}
	if ( '' === $bc_id ) {
		$logger->log( __METHOD__, 'missing bc_id for tt4b_product_sync' );
		return;
	}
	// shop_name just used for brand, can default it.
	if ( '' === $shop_name ) {
		$shop_name = 'WOO_COMMERCE';
	}

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
	if ( '' === $sku_id ) {
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
			'sku_id: %s is missing the following fields for product sync: %s',
			$sku_id,
			join( ',', $missing_fields )
		);
		$logger->log( __METHOD__, $debug_message );
		return;
	}

	$dpa_product = [
		'sku_id'        => $sku_id,
		'item_group_id' => $sku_id,
		'title'         => $title,
		'availability'  => $availability,
		'description'   => $description,
		'image_link'    => $image_url,
		'brand'         => $shop_name,
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

	// post to catalog manager.
	$mapi                    = new Tt4b_Mapi_Class( $logger );
	$dpa_products            = [ $dpa_product ];
	$dpa_product_information = [
		'bc_id'        => $bc_id,
		'catalog_id'   => $catalog_id,
		'dpa_products' => $dpa_products,
	];
	$mapi->mapi_post( 'catalog/product/upload/', $access_token, $dpa_product_information );
}


/**
 * Untrash a product
 *
 * @param string $post_id The product_id.
 *
 * @return void
 */
function tt4b_product_untrashed( $post_id ) {
	$post = get_post( $post_id );
	tt4b_product_sync( $post_id, $post );
}


/**
 * Trash a product
 *
 * @param string $post_id The product_id.
 *
 * @return void
 */
function tt4b_product_trashed( $post_id ) {
	$post = get_post( $post_id );
	tt4b_product_delete( $post_id, $post );
}


/**
 * Delete a product
 *
 * @param string $post_id The product_id.
 * @param string $post    The post.
 *
 * @return void
 */
function tt4b_product_delete( $post_id, $post ) {
	if ( 'product' !== $post->post_type ) {
		return;
	}
	$product = wc_get_product( $post_id );
	if ( is_null( $product ) ) {
		return;
	}
	$logger = new Logger( wc_get_logger() );

	$access_token = get_option( 'tt4b_access_token' );
	$catalog_id   = get_option( 'tt4b_catalog_id' );
	$bc_id        = get_option( 'tt4b_bc_id' );
	if ( false === $access_token ) {
		$logger->log( __METHOD__, 'missing access token for tt4b_product_sync' );
		return;
	}
	if ( '' === $catalog_id ) {
		$logger->log( __METHOD__, 'missing catalog_id for tt4b_product_sync' );
		return;
	}
	if ( '' === $bc_id ) {
		$logger->log( __METHOD__, 'missing bc_id for tt4b_product_sync' );
		return;
	}

	$sku_id = (string) $product->get_sku();
	if ( '' === $sku_id ) {
		$sku_id = (string) $product->get_id();
	}

	// post to catalog manager.
	$mapi                    = new Tt4b_Mapi_Class( $logger );
	$dpa_product_information = [
		'bc_id'      => $bc_id,
		'catalog_id' => $catalog_id,
		'sku_ids'    => [ $sku_id ],
	];
	$mapi->mapi_post( 'catalog/product/delete/', $access_token, $dpa_product_information );
}

function check_woocommerce_install() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_action( 'admin_notices', 'update_tt4b_version_admin_notice' );
		function update_tt4b_version_admin_notice() {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'TikTok for WooCommerce requires WooCommerce version 7.0 or higher to be enabled.' ); ?></p>
			</div>
			<?php
		}
		return;
	}
}
