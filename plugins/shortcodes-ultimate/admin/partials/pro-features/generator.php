<div id="su-generator-breadcrumbs">
	<a href="javascript:;" class="su-generator-home" title="<?php esc_html_e( 'Click to return to the shortcodes list', 'shortcodes-ultimate' ); ?>"><?php esc_html_e( 'All shortcodes', 'shortcodes-ultimate' ); ?></a>
	&rarr;
	<span><?php echo esc_html( $data['shortcode']['name'] ); ?></span>
	<small class="alignright"><?php echo esc_html( $data['shortcode']['desc'] ); ?></small>
	<div class="su-generator-clear"></div>
</div>

<div class="su-generator-pro-features-banner">
	<img src="<?php echo esc_attr( $data['image_url'] . 'icon-banner.png' ); ?>" class="su-generator-pro-features-banner-icon">
	<h3 class="su-generator-pro-features-banner-title"><?php esc_html_e( 'Shortcodes Ultimate PRO', 'shortcodes-ultimate' ); ?></h3>
	<p class="su-generator-pro-features-banner-description">
		<?php // translators: %s - shortcode name ?>
		<?php printf( esc_html( __( 'This shortcode is available in the Pro version. Upgrade now to get 15 additional shortcodes, including %s and others', 'shortcodes-ultimate' ) ), '<strong style="text-transform:capitalize">' . esc_html( $data['shortcode']['name'] ) . '</strong>' ); ?>
	</p>
	<p class="su-generator-pro-features-banner-action">
		<a href="<?php echo esc_attr( esc_attr( su_get_utm_link( 'https://getshortcodes.com/pricing/', 'wp-dashboard', 'generator', 'shortcode' ) ) ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Details & Pricing', 'shortcodes-ultimate' ); ?> &rarr;</a>
	</p>
	<div class="su-generator-pro-features-banner-screenshot">
		<img src="<?php echo esc_attr( $data['image_url'] . 'screenshots/' . $data['shortcode']['id'] . '.png' ); ?>">
	</div>
</div>
