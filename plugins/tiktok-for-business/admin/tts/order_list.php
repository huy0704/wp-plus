<?php

namespace tiktok\admin\tts\order_list;

use function tiktok\admin\tts\common\array_insert_after;
use function tiktok\admin\tts\common\get_edit_link;
use function tiktok\admin\tts\common\get_tts_seller_center_origin;

/**
 * The order list class
 */
class OrderList {

	/**
	 * The constructor
	 */
	public static function init() {
		self::modify_table();
		self::add_tiktok_filter();
	}

	/**
	 * Add Channel adn Update columns
	 * hook located in wordpress/wp-admin/includes/screen.php get_column_headers function
	 */
	private static function modify_table() {
		add_filter(
			'manage_edit-shop_order_columns',
			function ( $columns ) {
				$new_columns = array_insert_after(
					'order_number',
					$columns,
					'order_channel',
					esc_html__( 'Channel' )
				);
				$new_columns = array_insert_after(
					'order_status',
					$new_columns,
					'order_update',
					esc_html__( 'Update' )
				);

				return $new_columns;
			}
		);

		/**
		 * Default show all columns in order list page
		 * hook located in wordpress/wp-admin/includes/screen.php get_hidden_columns function
		 * set priority greater than woocommerce filter
		 */
		add_filter(
			'default_hidden_columns',
			function ( $hidden, $screen ) {
				return 'edit-shop_order' === $screen->id ? [] : $hidden;
			},
			10000,
			2
		);

		/**
		 * Tiktok order remove action button
		 * hook located in woocommerce/src/Internal/Admin/Orders/ListTable.php column_wc_actions method
		 */
		add_filter(
			'woocommerce_admin_order_actions',
			function ( $actions, $order ) {
				return $order->get_meta( 'tiktok_order' ) ? [] : $actions;
			},
			10,
			2
		);

		/**
		 * Set the contents for each row of the custom columns
		 * hook located in wordpress/wp-admin/includes/class-wp-posts-list-table.php column_default method
		 */
		add_action(
			'manage_shop_order_posts_custom_column',
			function ( $column ) {
				// wc_get_order() defaults to the global $post object, so we don't need to pass a parameter.
				$order           = wc_get_order();
				$is_tiktok_order = ! ! $order->get_meta( 'tiktok_order' );
				$tiktok_order_id = $order->get_meta( 'tiktok_order_id' );

				if ( 'order_channel' === $column ) {
					echo esc_html__( $is_tiktok_order ? 'TikTok' : '-' );
					return;
				}

				if ( 'order_update' === $column ) {
					// split text by timestamp
					$texts = preg_split(
						'/{(\d+)}/',
						$is_tiktok_order && $order->get_meta( 'tiktok_order_update' ) ? $order->get_meta( 'tiktok_order_update' ) : '',
						-1,
						PREG_SPLIT_DELIM_CAPTURE
					);

					foreach ( $texts as $key => $value ) {
						if ( preg_match( '/^\d+$/', $value ) ) {
							// need to replace utc timestamp with the time in the corresponding time zone
							echo '<span style="color:red">' . esc_html__( get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $value ) ) ) . '</span>';
						} else {
							echo esc_html__( $value );
						}
					}

					return;
				}

				if ( 'wc_actions' === $column && $is_tiktok_order ) {
					printf(
						'<a class="button" style="%s" target="_blank" href="%s">%s</a>',
						'padding:0.3em !important;width:7em;height:auto !important;text-indent:0;text-align:center;line-height:normal;white-space:normal',
						esc_url( get_tts_seller_center_origin() . '/order/detail?order_no=' . $tiktok_order_id ),
						esc_html__( 'Manage on seller center' )
					);
					return;
				}
			}
		);
	}

	/**
	 * Add TikTok views
	 * hook is located in wordpress/wp-admin/includes/class-wp-list-table.php views method
	 */
	private static function add_tiktok_filter() {
		add_filter(
			'views_edit-shop_order',
			function ( $views ) {
				$screen    = get_current_screen();
				$curr_page = 1;
				$orders    = [];
				$num_pages = wc_get_orders(
					[
						'page'     => $curr_page,
						'paginate' => true,
						'limit'    => 100,
						'meta_key' => 'tiktok_order',
						'type'     => 'shop_order',
					]
				)->max_num_pages;

				while ( $curr_page <= $num_pages ) {
					$new_orders = wc_get_orders(
						[
							'page'     => $curr_page,
							'limit'    => 100,
							'meta_key' => 'tiktok_order',
							'type'     => 'shop_order',
						]
					);
					$orders     = array_merge( $orders, $new_orders );
					$curr_page++;
				}

				return array_merge(
					$views,
					[
						'tiktok' => get_edit_link(
							[
								'post_type' => $screen->post_type,
								'channel'   => 'tiktok',
							],
							'TikTok <span class="count">(' . number_format_i18n( count( $orders ) ) . ')</span>',
							isset( $_REQUEST['channel'] ) && 'tiktok' === $_REQUEST['channel'] ? 'current' : ''
						),
					]
				);
			}
		);

		/**
		 * Modify query args of WP_Query instance
		 * need to wait current screen been set
		 * hook located in wordpress/wp-includes/class-wp-query.php get_posts method
		 */
		add_action(
			'current_screen',
			function ( $screen ) {
				add_action(
					'pre_get_posts',
					function ( $query ) use ( $screen ) {
						if (
							'edit-shop_order' === $screen->id &&
							'shop_order' === $screen->post_type &&
							isset( $_GET['channel'] ) &&
							'tiktok' === $_GET['channel']
						) {
							$query->query_vars['meta_key'] = 'tiktok_order';
						}
					}
				);
			}
		);
	}
}

OrderList::init();
