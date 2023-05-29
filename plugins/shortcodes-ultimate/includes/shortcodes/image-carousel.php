<?php

su_add_shortcode(
	array(
		'id'       => 'image_carousel',
		'callback' => 'su_shortcode_image_carousel',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/image_carousel.svg',
		'name'     => __( 'Image carousel', 'shortcodes-ultimate' ),
		'desc'     => __( 'Customizable image gallery (slider and carousel)', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'gallery',
		'icon'     => 'picture-o',
		'atts'     => array(
			'source'         => array(
				'type'          => 'image_source',
				'default'       => 'none',
				'media_sources' => array(
					'media'         => __( 'Media library', 'shortcodes-ultimate' ),
					'media: recent' => __( 'Recent media', 'shortcodes-ultimate' ),
					'posts: recent' => __( 'Recent posts', 'shortcodes-ultimate' ),
					'taxonomy'      => __( 'Taxonomy', 'shortcodes-ultimate' ),
				),
				'name'          => __( 'Images source', 'shortcodes-ultimate' ),
				'desc'          => __( 'This option defines which images will be shown in the gallery. Images can be selected manually from media library or fetched automatically from post featured images, or even filtered by a taxonomy.', 'shortcodes-ultimate' ),
			),
			'limit'          => array(
				'type'    => 'slider',
				'min'     => -1,
				'max'     => 100,
				'step'    => 1,
				'default' => 20,
				'name'    => __( 'Limit', 'shortcodes-ultimate' ),
				'desc'    => __( 'Maximum number of posts to search featured images in (for recent media, recent posts, and taxonomy)', 'shortcodes-ultimate' ),
			),
			'slides_style'   => array(
				'type'    => 'select',
				'values'  => array(
					'default' => __( 'Default', 'shortcodes-ultimate' ),
					'minimal' => __( 'Minimal', 'shortcodes-ultimate' ),
					'photo'   => __( 'Photo', 'shortcodes-ultimate' ),
				),
				'default' => 'default',
				'name'    => __( 'Slides style', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option control carousel slides appearance.', 'shortcodes-ultimate' ),
			),
			'controls_style' => array(
				'type'    => 'select',
				'values'  => array(
					'dark'  => __( 'Dark', 'shortcodes-ultimate' ),
					'light' => __( 'Light', 'shortcodes-ultimate' ),
				),
				'default' => 'dark',
				'name'    => __( 'Controls style', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option control carousel controls appearance.', 'shortcodes-ultimate' ),
			),
			'crop'           => array(
				'type'    => 'select',
				'values'  => array( 'none' => __( 'Do not crop images', 'shortcodes-ultimate' ) ) + su_get_config( 'crop-ratios' ),
				'default' => '4:3',
				'name'    => __( 'Crop images', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option allows to enable/disable image cropping and crop aspect ratio.', 'shortcodes-ultimate' ),
			),
			'columns'        => array(
				'type'    => 'slider',
				'min'     => 1,
				'max'     => 8,
				'step'    => 1,
				'default' => 1,
				'name'    => __( 'Columns', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option control the number of columns used in the carousel.', 'shortcodes-ultimate' ),
			),
			'adaptive'       => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Adaptive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Set this option to Yes to ignore the columns parameter and display a single column on mobile devices.', 'shortcodes-ultimate' ),
			),
			'spacing'        => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Spacing', 'shortcodes-ultimate' ),
				'desc'    => __( 'Adds spacing between carousel columns.', 'shortcodes-ultimate' ),
			),
			'align'          => array(
				'type'    => 'select',
				'values'  => array(
					'none'   => __( 'None', 'shortcodes-ultimate' ),
					'left'   => __( 'Left', 'shortcodes-ultimate' ),
					'right'  => __( 'Right', 'shortcodes-ultimate' ),
					'center' => __( 'Center', 'shortcodes-ultimate' ),
					'full'   => __( 'Full', 'shortcodes-ultimate' ),
				),
				'default' => 'none',
				'name'    => __( 'Alignment', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option controls how the gallery will be aligned within a page. Left, Right, and Center options require Max Width to be set. Full option requires page template with no sidebar.', 'shortcodes-ultimate' ),
			),
			'max_width'      => array(
				'default' => 'none',
				'name'    => __( 'Max width', 'shortcodes-ultimate' ),
				'desc'    => sprintf(
					'%1$s<br>%2$s: %4$s.<br>%3$s: %5$s.',
					__( 'Sets maximum width of the carousel container. CSS uints are allowed.', 'shortcodes-ultimate' ),
					__( 'Example values', 'shortcodes-ultimate' ),
					__( 'Default value', 'shortcodes-ultimate' ),
					'<b%value>500</b>, <b%value>500px</b>, <b%value>50%</b>, <b%value>40rem</b>',
					'<b%value>none</b>'
				),
			),
			'captions'       => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Captions', 'shortcodes-ultimate' ),
				'desc'    => __( 'Set this option to Yes to display image captions.', 'shortcodes-ultimate' ),
			),
			'arrows'         => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Arrows (left / right)', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option enables left/right arrow navigation.', 'shortcodes-ultimate' ),
			),
			'dots'           => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Dots (pagination)', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option enables dots/pages navigation.', 'shortcodes-ultimate' ),
			),
			'link'           => array(
				'type'    => 'select',
				'values'  => array(
					'none'       => __( 'None', 'shortcodes-ultimate' ),
					'image'      => __( 'Full-size image', 'shortcodes-ultimate' ),
					'lightbox'   => __( 'Lightbox', 'shortcodes-ultimate' ),
					'custom'     => __( 'Custom link (added in media editor)', 'shortcodes-ultimate' ),
					'attachment' => __( 'Attachment page', 'shortcodes-ultimate' ),
					'post'       => __( 'Post permalink', 'shortcodes-ultimate' ),
				),
				'default' => 'none',
				'name'    => __( 'Link to', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option adds links to carousel slides.', 'shortcodes-ultimate' ),
			),
			'target'         => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'blank',
				'name'    => __( 'Links target', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option controls how slide links will be opened.', 'shortcodes-ultimate' ),
			),
			'autoplay'       => array(
				'type'    => 'slider',
				'min'     => 0,
				'max'     => 15,
				'step'    => 1,
				'default' => 5,
				'name'    => __( 'Autoplay', 'shortcodes-ultimate' ),
				'desc'    => __( 'Sets the time interval between automatic slide transitions, in seconds. Set to 0 to disable autoplay.', 'shortcodes-ultimate' ),
			),
			'speed'          => array(
				'type'    => 'select',
				'values'  => array(
					'immediate' => __( 'Immediate', 'shortcodes-ultimate' ),
					'fast'      => __( 'Fast', 'shortcodes-ultimate' ),
					'medium'    => __( 'Medium', 'shortcodes-ultimate' ),
					'slow'      => __( 'Slow', 'shortcodes-ultimate' ),
				),
				'default' => 'medium',
				'name'    => __( 'Transition speed', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option control slides transition speed.', 'shortcodes-ultimate' ),
			),
			'image_size'     => array(
				'type'    => 'select',
				'values'  => su_get_image_sizes(),
				'default' => 'large',
				'name'    => __( 'Images size (quality)', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option controls the size of carousel slide images. This option only affects image quality, not the actual slide size.', 'shortcodes-ultimate' ),
			),
			'outline'        => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Outline on focus', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option enables outline when carousel gets focus. The outline improves keyboard navigation.', 'shortcodes-ultimate' ),
			),
			'random'         => array(
				'type'    => 'bool',
				'default' => 'no',
				'name'    => __( 'Random order', 'shortcodes-ultimate' ),
				'desc'    => __( 'This option enables random order for selected images', 'shortcodes-ultimate' ),
			),
			'class'          => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
	)
);

function su_shortcode_image_carousel( $atts = null, $content = null ) {

	$atts = su_parse_shortcode_atts(
		'image_carousel',
		$atts,
		array( 'prefer_caption' => 'no' )
	);

	$atts['columns']        = intval( $atts['columns'] );
	$atts['autoplay']       = floatval( str_replace( ',', '.', $atts['autoplay'] ) );
	$atts['crop']           = sanitize_key( str_replace( ':', '-', $atts['crop'] ) );
	$atts['slides_style']   = sanitize_key( $atts['slides_style'] );
	$atts['controls_style'] = sanitize_key( $atts['controls_style'] );
	$atts['image_size']     = sanitize_key( $atts['image_size'] );
	$atts['align']          = sanitize_key( $atts['align'] );
	$atts['speed']          = sanitize_key( $atts['speed'] );
	$atts['limit']          = intval( $atts['limit'] );

	$items            = array();
	$styles           = array();
	$slides           = su_get_gallery_slides( $atts );
	$link_target_attr = 'blank' === $atts['target']
		? ' target="_blank" rel="noopener noreferrer"'
		: '';
	$transitions      = array(
		'immediate' => array( 1, 1 ),
		'fast'      => array( 0.15, 1 ),
		'medium'    => array( 0.025, 0.28 ),
		'slow'      => array( 0.007, 0.25 ),
	);

	if ( ! $slides ) {

		return su_error_message(
			'Image Carousel',
			__( 'images not found', 'shortcodes-ultimate' )
		);

	}

	foreach ( $slides as $slide ) {

		$content = wp_get_attachment_image(
			$slide['attachment_id'],
			$atts['image_size'],
			false,
			array( 'class' => '' )
		);

		if ( 'yes' === $atts['captions'] ) {

			$content = sprintf(
				'%s<span>%s</span>',
				$content,
				$slide['caption']
			);

		}

		if ( 'none' !== $atts['link'] ) {

			$content = sprintf(
				'<a href="%s"%s data-caption="%s">%s</a>',
				esc_attr( esc_url_raw( $slide['link'] ) ),
				$link_target_attr,
				esc_attr( $slide['caption'] ),
				$content
			);

		}

		$items[] = sprintf(
			'<div class="su-image-carousel-item">' .
			'<div class="su-image-carousel-item-content">%s</div>' .
			'</div>',
			$content
		);

	}

	if ( $atts['columns'] > 1 ) {
		$atts['class'] .= ' su-image-carousel-columns-' . $atts['columns'];
	}

	if ( 'yes' === $atts['spacing'] ) {
		$atts['class'] .= ' su-image-carousel-has-spacing';
	}

	if ( 'none' !== $atts['crop'] ) {
		$atts['class'] .= ' su-image-carousel-crop su-image-carousel-crop-' . $atts['crop'];
	}

	if ( 'lightbox' === $atts['link'] ) {
		$atts['class'] .= ' su-image-carousel-has-lightbox';
	}

	if ( 'yes' === $atts['outline'] ) {
		$atts['class'] .= ' su-image-carousel-has-outline';
	}

	if ( 'yes' === $atts['adaptive'] ) {
		$atts['class'] .= ' su-image-carousel-adaptive';
	}

	if ( is_numeric( $atts['max_width'] ) ) {
		$atts['max_width'] = $atts['max_width'] . 'px';
	}

	if ( 'none' !== $atts['max_width'] ) {
		$styles[] = 'max-width:' . $atts['max_width'];
	}

	$atts['class'] .= ' su-image-carousel-slides-style-' . $atts['slides_style'];

	$atts['class'] .= ' su-image-carousel-controls-style-' . $atts['controls_style'];

	$atts['class'] .= ' su-image-carousel-align-' . $atts['align'];

	$flickity = array(
		'groupCells'      => true,
		'cellSelector'    => '.su-image-carousel-item',
		'adaptiveHeight'  => 'none' === $atts['crop'],
		'cellAlign'       => 'left',
		'prevNextButtons' => 'yes' === $atts['arrows'],
		'pageDots'        => 'yes' === $atts['dots'],
		'autoPlay'        => $atts['autoplay'] > 0 ? $atts['autoplay'] * 1000 : false,
		'imagesLoaded'    => true,
		// Disable 'contain' if slides have variable height
		// @see: https://github.com/metafizzy/flickity/issues/554
		'contain'         => 'none' !== $atts['crop'],
	);

	if ( isset( $transitions[ $atts['speed'] ] ) ) {
		$flickity['selectedAttraction'] = $transitions[ $atts['speed'] ][0];
		$flickity['friction']           = $transitions[ $atts['speed'] ][1];
	}

	$uniqid = uniqid( 'su_image_carousel_' );

	$flickity = apply_filters(
		'su/shortcode/image_carousel/flickity',
		$flickity,
		$atts
	);

	if ( 'lightbox' === $atts['link'] ) {
		su_query_asset( 'js', 'magnific-popup' );
		su_query_asset( 'css', 'magnific-popup' );
	}

	su_query_asset( 'js', 'flickity' );
	su_query_asset( 'js', 'su-shortcodes' );
	su_query_asset( 'css', 'flickity' );
	su_query_asset( 'css', 'su-shortcodes' );

	$script = sprintf(
		'<script id="%1$s_script">if(window.SUImageCarousel){setTimeout(function() {window.SUImageCarousel.initGallery(document.getElementById("%1$s"))}, 0);}var %1$s_script=document.getElementById("%1$s_script");if(%1$s_script){%s_script.parentNode.removeChild(%1$s_script);}</script>',
		esc_js( $uniqid )
	);

	return sprintf(
		'<div class="su-image-carousel %1$s" style="%2$s" data-flickity-options=\'%3$s\' id="%4$s">%5$s</div>%6$s',
		esc_attr( su_get_css_class( $atts ) ),
		esc_attr( implode( ';', $styles ) ),
		wp_json_encode( $flickity ),
		esc_attr( $uniqid ),
		implode( $items ),
		$script
	);

}
