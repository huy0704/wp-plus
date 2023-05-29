<?php
/**
 * Copyright (c) Bytedance, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package TikTok
 */
require_once 'Tt4b_Pixel_Class.php';
add_action( 'woocommerce_add_to_cart', [ 'Tt4b_Pixel_Class', 'inject_add_to_cart_event' ], 40, 4 );
add_action( 'woocommerce_after_single_product', [ 'Tt4b_Pixel_Class', 'inject_view_content_event' ] );
add_action( 'woocommerce_payment_complete', [ 'Tt4b_Pixel_Class', 'inject_purchase_event' ] );
add_action( 'woocommerce_thankyou', [ 'Tt4b_Pixel_Class', 'inject_purchase_event' ] );
add_action( 'woocommerce_before_checkout_form', [ 'Tt4b_Pixel_Class', 'inject_start_checkout' ] );
add_action( 'init', [ 'Tt4b_Pixel_Class', 'set_ttclid' ] );
