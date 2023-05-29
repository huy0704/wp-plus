<?php defined( 'ABSPATH' ) || exit; ?>

<div class="su-admin-about-wrap wrap">

	<?php if ( ! su_fs()->can_use_premium_code() && ! su_has_active_addons() ) : ?>
		<?php su_partial( 'admin/partials/notices/pro.php', array( 'page' => 'about' ) ); ?>
	<?php endif; ?>

	<div class="su-admin-about">

		<div class="su-admin-about-page-header">
			<div>
				<img src="<?php echo esc_attr( plugins_url( '../../images/plugin-icon.svg', __FILE__ ) ); ?>" alt="" width="72" height="72">
			</div>
			<h1>
				<?php // translators: %s will be replaced with "Shortcodes Ultimate" ?>
				<?php echo esc_html( sprintf( __( 'Welcome to %s', 'shortcodes-ultimate' ), 'Shortcodes&nbsp;Ultimate' ) ); ?>
			</h1>
			<p><?php esc_html_e( 'The most powerful shortcode plugin for WordPress', 'shortcodes-ultimate' ); ?></p>
		</div>

		<div class="su-admin-about-getting-started">
			<div class="su-admin-about-getting-started-video su-admin-about-getting-started-video-loading" id="su-admin-about-getting-started-video">
				<button class="su-admin-about-getting-started-video-button su-admin-u-hidden" id="su-admin-about-getting-started-video-button">
					<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill="currentcolor" d="m16 0c8.831 0 16 7.169 16 16s-7.169 16-16 16-16-7.169-16-16 7.169-16 16-16zm-4 20.5c0 .501.273.962.712 1.203s.974.224 1.397-.045c2.049-1.304 5.128-3.263 7.072-4.5.396-.252.635-.689.635-1.158s-.239-.906-.635-1.158c-1.944-1.237-5.023-3.196-7.072-4.5-.423-.269-.958-.286-1.397-.045s-.712.702-.712 1.203z"/></svg>
					<span><?php esc_html_e( 'See the plugin in action', 'shortcodes-ultimate' ); ?></span>
				</button>
				<iframe class="su-admin-about-getting-started-video-iframe su-admin-u-hidden" id="su-admin-about-getting-started-video-iframe" src="https://player.vimeo.com/video/507942335?color=0073D7&title=0&byline=0&portrait=0&dnt=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>

		<div class="su-admin-about-popular">
			<h2><?php esc_html_e( 'Shortcodes', 'shortcodes-ultimate' ); ?></h2>
			<p><?php esc_html_e( 'The plugin includes over 60 amazing shortcodes', 'shortcodes-ultimate' ); ?></p>
			<ul class="su-admin-about-popular-grid">
				<?php foreach ( su_get_config( 'popular-shortcodes' ) as $shortcode ) : ?>
					<li class="su-admin-about-popular-item">
						<img class="su-admin-about-popular-item-icon" src="<?php echo esc_attr( su_get_plugin_url() . $shortcode['icon'] ); ?>" alt="<?php echo esc_attr( sprintf( '%s: %s', __( 'Shortcode icon', 'shortcodes-ultimate' ), $shortcode['title'] ) ); ?>" width="24" height="24">
						<span class="su-admin-about-popular-item-text">
							<span class="su-admin-about-popular-item-title"><?php echo esc_html( $shortcode['title'] ); ?></span>
							<span class="su-admin-about-popular-item-description"><?php echo esc_html( $shortcode['description'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="su-admin-about-popular-bottom">
				<a href="https://getshortcodes.com/docs-category/shortcodes/" class="button" target="_blank"><?php esc_html_e( 'View all shortcodes', 'shortcodes-ultimate' ); ?> <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="16" height="16" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M14 9 L3 9 3 29 23 29 23 18 M18 4 L28 4 28 14 M28 4 L14 18" /></svg></a>
			</div>
		</div>

		<?php if ( su_fs()->is_not_paying() ) : ?>

			<div class="su-admin-about-upgrade">
				<div class="su-admin-about-upgrade-features">
					<h2><?php esc_html_e( 'Upgrade to PRO', 'shortcodes-ultimate' ); ?></h2>
					<div class="su-admin-about-upgrade-features-list">
						<ul>
							<li><?php esc_html_e( 'Parallax Section shortcode', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Custom PHP shortcodes', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Content Slider shortcode', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Personal email support', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Testimonail shortcode', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( '24 styles for Heading', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Progress Bar shortcode', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( '14 styles for Spoiler', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( 'Exit Popup shortcode', 'shortcodes-ultimate' ); ?></li>
							<li><?php esc_html_e( '13 styles for Tabs', 'shortcodes-ultimate' ); ?></li>
						</ul>
					</div>
				</div>
				<div class="su-admin-about-upgrade-buy">
					<div class="su-admin-about-upgrade-buy-pricing">
						<div class="su-admin-about-upgrade-buy-pricing-price">
							<span class="su-admin-about-upgrade-buy-pricing-currency">$</span>
							<span class="su-admin-about-upgrade-buy-pricing-value">39</span>
						</div>
						<div class="su-admin-about-upgrade-buy-pricing-period"><?php esc_html_e( 'per year', 'shortcodes-ultimate' ); ?></div>
					</div>
					<a href="<?php echo esc_attr( esc_attr( su_get_utm_link( 'https://getshortcodes.com/pricing/', 'wp-dashboard', 'admin-menu', 'about-upgrade' ) ) ); ?>" class="su-admin-about-upgrade-buy-button su-admin-c-button"><?php esc_html_e( 'Upgrade to PRO', 'shortcodes-ultimate' ); ?> &rarr;</a>
				</div>
			</div>

		<?php endif; ?>

		<div class="su-admin-about-help">
			<h2><?php esc_html_e( 'Get help', 'shortcodes-ultimate' ); ?></h2>
			<div class="su-admin-about-help-menu">
				<ul>
					<li>
						<a href="https://getshortcodes.com/docs/" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="18" height="18" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M16 7 C16 7 9 1 2 6 L2 28 C9 23 16 28 16 28 16 28 23 23 30 28 L30 6 C23 1 16 7 16 7 Z M16 7 L16 28"/></svg>
							<?php esc_html_e( 'Plugin documentation', 'shortcodes-ultimate' ); ?>
						</a>
					</li>
					<li>
						<a href="https://wordpress.org/support/plugin/shortcodes-ultimate/" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="18" height="18" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z"/></svg>
							<?php esc_html_e( 'Community support forum', 'shortcodes-ultimate' ); ?>
						</a>
					</li>
					<li>
						<a href="https://getshortcodes.com/support/open-support-ticket/" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="18" height="18" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M2 26 L30 26 30 6 2 6 Z M2 6 L16 16 30 6"/></svg>
							<?php esc_html_e( 'Premium support', 'shortcodes-ultimate' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

	</div>

</div>
