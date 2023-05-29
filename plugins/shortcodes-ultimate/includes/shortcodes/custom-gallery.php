<?php

su_add_shortcode(
	array(
		'id'       => 'custom_gallery',
		'callback' => 'su_shortcode_custom_gallery',
		'image'    => su_get_plugin_url() . 'admin/images/shortcodes/custom_gallery.svg',
		'name'     => __( 'Gallery', 'shortcodes-ultimate' ),
		'type'     => 'single',
		'group'    => 'gallery',
		'atts'     => array(
			'source' => array(
				'type'    => 'image_source',
				'default' => 'none',
				'name'    => __( 'Source', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose images source. You can use images from Media library or retrieve it from posts (thumbnails) posted under specified blog category. You can also pick any custom taxonomy', 'shortcodes-ultimate' ),
			),
			'limit'  => array(
				'type'    => 'slider',
				'min'     => -1,
				'max'     => 100,
				'step'    => 1,
				'default' => 20,
				'name'    => __( 'Limit', 'shortcodes-ultimate' ),
				'desc'    => __( 'Maximum number of image source posts (for recent posts, category and custom taxonomy)', 'shortcodes-ultimate' ),
			),
			'link'   => array(
				'type'    => 'select',
				'values'  => array(
					'none'       => __( 'None', 'shortcodes-ultimate' ),
					'image'      => __( 'Full-size image', 'shortcodes-ultimate' ),
					'lightbox'   => __( 'Lightbox', 'shortcodes-ultimate' ),
					'custom'     => __( 'Slide link (added in media editor)', 'shortcodes-ultimate' ),
					'attachment' => __( 'Attachment page', 'shortcodes-ultimate' ),
					'post'       => __( 'Post permalink', 'shortcodes-ultimate' ),
				),
				'default' => 'none',
				'name'    => __( 'Links', 'shortcodes-ultimate' ),
				'desc'    => __( 'Select which links will be used for images in this gallery', 'shortcodes-ultimate' ),
			),
			'target' => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'self',
				'name'    => __( 'Links target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Open links in', 'shortcodes-ultimate' ),
			),
			'width'  => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1600,
				'step'    => 10,
				'default' => 90,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Single item width (in pixels)', 'shortcodes-ultimate' ),
			),
			'height' => array(
				'type'    => 'slider',
				'min'     => 10,
				'max'     => 1600,
				'step'    => 10,
				'default' => 90,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Single item height (in pixels)', 'shortcodes-ultimate' ),
			),
			'title'  => array(
				'type'    => 'select',
				'values'  => array(
					'never'  => __( 'Never', 'shortcodes-ultimate' ),
					'hover'  => __( 'On mouse over', 'shortcodes-ultimate' ),
					'always' => __( 'Always', 'shortcodes-ultimate' ),
				),
				'default' => 'hover',
				'name'    => __( 'Show titles', 'shortcodes-ultimate' ),
				'desc'    => __( 'Title display mode', 'shortcodes-ultimate' ),
			),
			'class'  => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'     => __( 'Customizable image gallery', 'shortcodes-ultimate' ),
		'icon'     => 'picture-o',
	)
);

function su_shortcode_custom_gallery( $atts = null, $content = null ) {
	$return = '';
	$atts   = shortcode_atts(
		array(
			'source'  => 'none',
			'limit'   => 20,
			'gallery' => null, // Dep. 4.4.0
			'link'    => 'none',
			'width'   => 90,
			'height'  => 90,
			'title'   => 'hover',
			'target'  => 'self',
			'class'   => '',
		),
		$atts,
		'custom_gallery'
	);

	$slides = su_get_slides( $atts );
	$slides = apply_filters( 'su/shortcode/custom_gallery/slides', $slides, $atts );

	$atts['width']  = intval( $atts['width'] );
	$atts['height'] = intval( $atts['height'] );

	// Loop slides
	if ( count( $slides ) ) {
		// Prepare links target
		$atts['target'] = ( 'yes' === $atts['target'] || 'blank' === $atts['target'] ) ? ' target="_blank"' : '';
		// Add lightbox class
		if ( 'lightbox' === $atts['link'] ) {
			$atts['class'] .= ' su-lightbox-gallery';
		}
		// Open gallery
		$return = '<div class="su-custom-gallery su-custom-gallery-title-' . esc_attr( $atts['title'] ) . su_get_css_class( $atts ) . '">';
		// Create slides
		foreach ( $slides as $slide ) {
			// Crop image
			$image = su_image_resize( $slide['image'], $atts['width'], $atts['height'] );

			if ( is_wp_error( $image ) ) {
				continue;
			}

			// Prepare slide title
			$title = ( $slide['title'] ) ? '<span class="su-custom-gallery-title">' . stripslashes( $slide['title'] ) . '</span>' : '';
			// Open slide
			$return .= '<div class="su-custom-gallery-slide">';
			// Slide content with link
			if ( $slide['link'] ) {
				$return .= '<a href="' . esc_attr( $slide['link'] ) . '"' . $atts['target'] . ' title="' . esc_attr( $slide['title'] ) . '"><img src="' . $image['url'] . '" alt="' . esc_attr( $slide['title'] ) . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" />' . $title . '</a>';
			}
			// Slide content without link
			else {
				$return .= '<a><img src="' . $image['url'] . '" alt="' . esc_attr( $slide['title'] ) . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" />' . $title . '</a>';
			}
			// Close slide
			$return .= '</div>';
		}
		// Clear floats
		$return .= '<div class="su-clear"></div>';
		// Close gallery
		$return .= '</div>';
		// Add lightbox assets
		if ( 'lightbox' === $atts['link'] ) {
			su_query_asset( 'css', 'magnific-popup' );
			su_query_asset( 'js', 'jquery' );
			su_query_asset( 'js', 'magnific-popup' );
			su_query_asset( 'js', 'su-shortcodes' );
		}
		su_query_asset( 'css', 'su-shortcodes' );
	}
	// Slides not found
	else {
		$return = su_error_message( 'Custom Gallery', __( 'images not found', 'shortcodes-ultimate' ) );
	}
	return $return;
}
