<?php
/**
 * Settings page functions.
 *
 * @package Facebook_Messenger_Customer_Chat
 *
 * Copyright (C) 2017-present, Facebook, Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

add_action( 'admin_enqueue_scripts', 'fbmcc_add_styles' );
add_action( 'admin_enqueue_scripts', 'fbmcc_localize_ajax' );
add_action( 'admin_menu', 'fbmcc_admin_menu' );
add_action( 'wp_ajax_fbmcc_update_options', 'fbmcc_update_options' );

/**
 * Get the capability require to change plugin settings.
 *
 * @return string Required capability.
 */
function fbmcc_get_options_capability() {
	/**
	 * Filters the capability required to manage plugin settings.
	 *
	 * @since 2.3
	 *
	 * @param bool $capability The capability required to manage plugin settings..
	 */
	$capability = apply_filters( 'fbmcc_options_capability', 'manage_options' );

	return $capability;
}

/**
 * Add admin menu page.
 *
 * @return void
 */
function fbmcc_admin_menu() {
	$capability = fbmcc_get_options_capability();

	add_options_page(
		esc_html__( 'Plugin settings', 'facebook-messenger-customer-chat' ),
		esc_html__( 'Facebook Chat', 'facebook-messenger-customer-chat' ),
		$capability,
		'facebook-messenger-customer-chat',
		'fbmcc_integration_settings'
	);
}

/**
 * Sanitize page types field.
 *
 * @param array $data Data to sanitize.
 *
 * @return array Page types.
 */
function fbmcc_sanitize_page_types( $data ) {
	$defaults = array(
		'all'            => '0',
		'category_index' => '0',
		'front_page'     => '0',
		'pages'          => array(),
		'pages_all'      => '0',
		'posts'          => '0',
		'product_pages'  => '0',
		'tag_index'      => '0',
	);

	if ( ! is_array( $data ) ) {
		return $defaults;
	}

	$page_types = array();

	foreach ( $data as $key => $value ) {
		if ( ! array_key_exists( $key, $defaults ) ) {
			continue;
		}

		if ( 'pages' === $key ) {
			$page_types[ $key ] = array_map( 'sanitize_text_field', (array) $value );
		} else {
			$page_types[ $key ] = '1' === $value ? '1' : '0';
		}
	}

	$page_types = wp_parse_args( $page_types, $defaults );

	return $page_types;
}

/**
 * Update plugin settings.
 *
 * @return void
 */
function fbmcc_update_options() {
	$capability = fbmcc_get_options_capability();

	if ( current_user_can( $capability ) ) {
		check_ajax_referer( 'update_fbmcc_code' );

		if ( isset( $_POST['pageTypes'] ) ) {
			update_option( 'fbmcc_page_types', fbmcc_sanitize_page_types( $_POST['pageTypes'] ) );
		}

		if ( isset( $_POST['pageID'] ) ) {
			update_option( 'fbmcc_pageID', absint( $_POST['pageID'] ) );
		}

		if ( isset( $_POST['locale'] ) ) {
			update_option( 'fbmcc_locale', sanitize_text_field( $_POST['locale'] ) );
		}

		update_option( 'fbmcc_install_ts', time() );
	}

	wp_die();
}

/**
 * Enqueue admin styles.
 *
 * @return void
 */
function fbmcc_add_styles() {
	wp_enqueue_style(
		'fbmcc-admin',
		plugins_url( '/settings.css', __FILE__ ),
		array(),
		FBMCC_VERSION,
		'all'
	);
}

/**
 * Enqueue admin JavaScript.
 *
 * @param string $hook_suffix The current admin page.
 *
 * @return void
 */
function fbmcc_localize_ajax( $hook_suffix ) {
	if ( 'settings_page_facebook-messenger-customer-chat' !== $hook_suffix && 'plugins.php' !== $hook_suffix ) {
		return;
	}

	$capability = fbmcc_get_options_capability();

	if ( ! current_user_can( $capability ) ) {
		return;
	}

	wp_register_script(
		'fbmcc-admin',
		plugins_url( '/script.js', __FILE__ ),
		array(),
		FBMCC_VERSION,
		false
	);

	wp_localize_script(
		'fbmcc-admin',
		'ajax_object',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'update_fbmcc_code' ),
		)
	);

	wp_enqueue_script( 'fbmcc-admin' );
}

/**
 * Display settings page.
 *
 * @return void
 */
function fbmcc_integration_settings() {
	$pages_arr  = get_pages();
	$page_id    = get_option( 'fbmcc_pageID' );
	$page_types = get_option( 'fbmcc_page_types' );
	?>

	<div class="wrap">
		<h2>
			<?php esc_html_e( 'Facebook Chat Plugin Settings', 'facebook-messenger-customer-chat' ); ?>
		</h2>

		<div class="fbmcc-card card">
			<div class="intro">
				<div>
					<h2>
						<?php esc_html_e( 'Getting Started?', 'facebook-messenger-customer-chat' ); ?>
					</h2>

					<p class="fbmcc-instructions">
						<?php
						esc_html_e(
							'Let people start a conversation on
							your website and continue in Messenger. It\'s easy to set up. Chats
							started on your website can be continued in the customers\'
							Messenger app, so you never lose connections with your customers.
							Even those without a Facebook Messenger account can chat with you
							in guest mode, so you can reach more customers than ever.',
							'facebook-messenger-customer-chat'
						);
						?>
					</p>
				</div>

				<div class="fbmcc-buttonContainer">
					<button class="fbmcc-setupButton" type="button" onclick="fbmcc_setupCustomerChat()">
						<?php
						if ( ! $page_id ) {
							esc_html_e( 'Setup Chat Plugin', 'facebook-messenger-customer-chat' );
						} else {
							esc_html_e( 'Edit Chat Plugin Configuration', 'facebook-messenger-customer-chat' );
						}
						?>
					</button>

					<?php
					if ( $page_id ) {
						$inbox_url = add_query_arg(
							'asset_id',
							$page_id,
							'https://business.facebook.com/latest/inbox/automated_responses'
						);

						printf(
							'<p><a href="%1$s" class="fbmcc-availabilityLink" target="_blank">%2$s</a></p>',
							esc_url( $inbox_url ),
							esc_html__( 'Edit Availability and Automated Responses', 'facebook-messenger-customer-chat' )
						);
					}
					?>
				</div>
			</div>
		</div>

		<div id="fbmcc-page-params" class="fbmcc-card card <?php echo ! get_option( 'fbmcc_pageID' ) ? 'hidden' : ''; ?>">
			<div>
				<h2>
					<?php esc_html_e( 'Setup status', 'facebook-messenger-customer-chat' ); ?>
				</h2>

				<p class="fbmcc-instructions">
					<?php
					esc_html_e(
						'The plugin code has already been added.
						into your website. You can always go back through the setup process
						to customize the plugin.',
						'facebook-messenger-customer-chat'
					);
					?>
				</p>

				<h2>
					<?php esc_html_e( 'Advanced Configuration', 'facebook-messenger-customer-chat' ); ?>
				</h2>

				<ul>
					<li>
						<table>
							<tr>
								<td>
									<?php esc_html_e( 'Deploy Chat plugin on:', 'facebook-messenger-customer-chat' ); ?>
								</td>
								<td>
									<select id="fbmcc-deploymentSelector" class="fbmcc-displaySetting">
										<?php
										printf(
											'<option value="1" %1$s>%2$s</option>',
											selected( ! $page_types || '1' === $page_types['all'], true, false ),
											esc_html__( 'All WordPress pages', 'facebook-messenger-customer-chat' )
										);

										printf(
											'<option value="0" %1$s>%2$s</option>',
											selected( $page_types && '0' === $page_types['all'], true, false ),
											esc_html__( 'Custom WordPress pages', 'facebook-messenger-customer-chat' )
										);
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="fbmcc-deploymentMenu <?php echo ( ! $page_types || '1' === $page_types['all'] ) ? 'hidden' : ''; ?>">
										<ul>
											<li>
												<input
													type="checkbox"
													id="cbShowFrontPage"
													class="fbmcc-displaySetting"
													<?php checked( isset( $page_types['front_page'] ) && '1' === $page_types['front_page'] ); ?>
												/>
												<label for="cbShowFrontPage">
													<?php esc_html_e( 'Homepage', 'facebook-messenger-customer-chat' ); ?>
												</label>
											</li>

											<li>
												<input
													aria-label="<?php esc_attr_e( 'Posts', 'facebook-messenger-customer-chat' ); ?>"
													type="checkbox"
													id="cbShowPosts"
													class="fbmcc-displaySetting fbmcc-menuParentItem"
													<?php checked( isset( $page_types['posts'] ) && '1' === $page_types['posts'] ); ?>
												/>
												<a
													href="javascript:;"
													id="fbmcc-postsSubmenuLink"
													class="fbmcc-menuParentLink"
													title="<?php esc_attr_e( 'Click for granular options', 'facebook-messenger-customer-chat' ); ?>"
												>
													<?php esc_html_e( 'Posts', 'facebook-messenger-customer-chat' ); ?>
													<img src="<?php echo esc_url( plugins_url( '/images/chevron-right.png', __FILE__ ) ); ?>" class="fbmcc-chevron" alt="&gt;" />
												</a>

												<ul class="fbmcc-submenu hidden">
													<li>
														<input
															type="checkbox"
															id="cbShowSinglePostView"
															class="fbmcc-displaySetting fbmcc-submenuOption"
															<?php checked( isset( $page_types['posts'] ) && '1' === $page_types['posts'] ); ?>
														/>
														<label for="cbShowSinglePostView">
															<?php esc_html_e( 'Single post view', 'facebook-messenger-customer-chat' ); ?>
														</label>
													</li>

													<li>
														<input
															type="checkbox"
															id="cbShowCategoryIndex"
															class="fbmcc-displaySetting fbmcc-submenuOption"
															<?php checked( isset( $page_types['category_index'] ) && '1' === $page_types['category_index'] ); ?>
														/>
														<label for="cbShowCategoryIndex">
															<?php esc_html_e( 'Category view', '' ); ?>
														</label>
													</li>

													<li>
														<input
															type="checkbox"
															id="cbShowTagsIndex"
															class="fbmcc-displaySetting fbmcc-submenuOption"
															<?php checked( isset( $page_types['tag_index'] ) && '1' === $page_types['tag_index'] ); ?>
														/>
														<label for="cbShowTagsIndex">
															<?php esc_html_e( 'Tags view', 'facebook-messenger-customer-chat' ); ?>
														</label>
													</li>
												</ul>
											</li>

											<li>
												<input
													aria-label="<?php esc_attr_e( 'Pages', 'facebook-messenger-customer-chat' ); ?>"
													type="checkbox"
													id="cbShowPages"
													class="fbmcc-displaySetting fbmcc-menuParentItem"
													<?php
													checked(
														( isset( $page_types['all'] ) && '1' === $page_types['all'] ) ||
														( isset( $page_types['pages_all'] ) && '1' === $page_types['pages_all'] )
													);
													?>
												/>
												<?php if ( ! empty( $pages_arr ) ) : ?>
													<a
														href="javascript:;"
														id="fbmcc-pagesSubmenuLink"
														class="fbmcc-menuParentLink"
														title="<?php esc_attr_e( 'Click for granular options', 'facebook-messenger-customer-chat' ); ?>"
													>
												<?php endif; ?>

												<?php esc_html_e( 'Pages', 'facebook-messenger-customer-chat' ); ?>

												<?php if ( ! empty( $pages_arr ) ) : ?>
														<img src="<?php echo esc_url( plugins_url( '/images/chevron-right.png', __FILE__ ) ); ?>" class="fbmcc-chevron" alt="&gt;" />
													</a>
												<?php endif; ?>

												<ul class="fbmcc-submenu hidden">
													<?php foreach ( $pages_arr as $page ) : ?>
														<li>
															<?php
															$checked = ! $page_types ||
																( isset( $page_types['all'] ) && '1' === $page_types['all'] ) ||
																( isset( $page_types['pages_all'] ) && '1' === $page_types['pages_all'] ) ||
																( isset( $page_types['pages'] ) && in_array( (string) $page->ID, $page_types['pages'], true ) );

															printf(
																'<input
																	type="checkbox"
																	id="pageid_%1$s"
																	class="fbmcc-displaySetting fbmcc-submenuOption fbmcc-activePageOption"
																	%2$s
																/>
																<label for="pageid_%1$s">
																	%3$s
																</label>',
																esc_attr( $page->ID ),
																checked( $checked, true, false ),
																esc_html( $page->post_title )
															);
															?>
														</li>
													<?php endforeach; ?>
												</ul>
											</li>

											<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
												<li>
													<input
														type="checkbox"
														id="cbShowProductPages"
														class="fbmcc-displaySetting"
														<?php checked( isset( $page_types['product_pages'] ) && '1' === $page_types['product_pages'] ); ?>
													/>
													<label for="cbShowProductPages">
														<?php echo esc_html__( 'WooCommerce Product pages', 'facebook-messenger-customer-chat' ); ?>
													</label>
												</li>
											<?php endif; ?>
										</ul>
									</div>

									<div id="fbmcc-saveStatus">
										<div class="hidden" id="fbmcc-saveStatus-error">
											<?php esc_html_e( 'Error saving settings.', 'facebook-messenger-customer-chat' ); ?>
										</div>

										<div class="hidden" id="fbmcc-saveStatus-saved">
											<?php esc_html_e( 'Settings saved.', 'facebook-messenger-customer-chat' ); ?>
										</div>

										<div class="hidden" id="fbmcc-saveStatus-saving">
											<?php esc_html_e( 'Saving settings...', 'facebook-messenger-customer-chat' ); ?>
										</div>
									</div>
								</td>
							</tr>
						</table>
					</li>
				</ul>
			</div>
		</div>

		<div class="fbmcc-card card">
			<div class="intro">
				<p class="fbmcc-instructions">
					<a href="<?php echo esc_url( __( 'https://developers.facebook.com/terms/', 'facebook-messenger-customer-chat' ) ); ?>">
						<?php esc_html_e( 'Use of this plugin is subject to Facebook\'s Platform Terms', 'facebook-messenger-customer-chat' ); ?>
					</a>
				</p>

				<p>
					<?php esc_html_e( 'Having a problem setting up or using the Chat Plugin?', 'facebook-messenger-customer-chat' ); ?>
				</p>

				<ul>
					<li>
						<a href="https://www.facebook.com/business/help/789975831794468">
							<?php esc_html_e( 'Please consult our Troubleshooting Guide.', 'facebook-messenger-customer-chat' ); ?>
						</a>
					</li>

					<li>
						<a href="https://wordpress.org/support/plugin/facebook-messenger-customer-chat/">
							<?php esc_html_e( 'If the troubleshooting steps in the guide do not solve your problem, please post in the plugin support forum.', 'facebook-messenger-customer-chat' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php
}
