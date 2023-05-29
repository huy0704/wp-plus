<?php

su_add_shortcode(
	array(
		'deprecated' => true,
		'id'         => 'slider',
		'callback'   => 'su_shortcode_slider',
		'image'      => su_get_plugin_url() . 'admin/images/shortcodes/slider.svg',
		// translators: Dep. – Deprecated
		'name'       => __( 'Slider (Dep.)', 'shortcodes-ultimate' ),
		'type'       => 'single',
		'group'      => 'gallery',
		'note'       => sprintf(
			'<p>%s</p><p><button class="button button-primary" onclick="document.querySelector(\'[data-shortcode=image_carousel]\').click(); return false;">%s &rarr;</button></p>',
			__( 'There is a much better shortcode for your images. Have you already tried the Image Carousel? It can create both sliders and carousels.', 'shortcodes-ultimate' ),
			__( 'Switch to Image Carousel', 'shortcodes-ultimate' )
		),
		'atts'       => array(
			'source'     => array(
				'type'    => 'image_source',
				'default' => 'none',
				'name'    => __( 'Source', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose images source. You can use images from Media library or retrieve it from posts (thumbnails) posted under specified blog category. You can also pick any custom taxonomy', 'shortcodes-ultimate' ),
			),
			'limit'      => array(
				'type'    => 'slider',
				'min'     => -1,
				'max'     => 100,
				'step'    => 1,
				'default' => 20,
				'name'    => __( 'Limit', 'shortcodes-ultimate' ),
				'desc'    => __( 'Maximum number of image source posts (for recent posts, category and custom taxonomy)', 'shortcodes-ultimate' ),
			),
			'link'       => array(
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
			'target'     => array(
				'type'    => 'select',
				'values'  => array(
					'self'  => __( 'Open in same tab', 'shortcodes-ultimate' ),
					'blank' => __( 'Open in new tab', 'shortcodes-ultimate' ),
				),
				'default' => 'self',
				'name'    => __( 'Links target', 'shortcodes-ultimate' ),
				'desc'    => __( 'Open links in', 'shortcodes-ultimate' ),
			),
			'width'      => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 600,
				'name'    => __( 'Width', 'shortcodes-ultimate' ),
				'desc'    => __( 'Slider width (in pixels)', 'shortcodes-ultimate' ),
			),
			'height'     => array(
				'type'    => 'slider',
				'min'     => 200,
				'max'     => 1600,
				'step'    => 20,
				'default' => 300,
				'name'    => __( 'Height', 'shortcodes-ultimate' ),
				'desc'    => __( 'Slider height (in pixels)', 'shortcodes-ultimate' ),
			),
			'responsive' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Responsive', 'shortcodes-ultimate' ),
				'desc'    => __( 'Ignore width and height parameters and make slider responsive', 'shortcodes-ultimate' ),
			),
			'title'      => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Show titles', 'shortcodes-ultimate' ),
				'desc'    => __( 'Display slide titles', 'shortcodes-ultimate' ),
			),
			'centered'   => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Center', 'shortcodes-ultimate' ),
				'desc'    => __( 'Is slider centered on the page', 'shortcodes-ultimate' ),
			),
			'arrows'     => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Arrows', 'shortcodes-ultimate' ),
				'desc'    => __( 'Show left and right arrows', 'shortcodes-ultimate' ),
			),
			'pages'      => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Pagination', 'shortcodes-ultimate' ),
				'desc'    => __( 'Show pagination', 'shortcodes-ultimate' ),
			),
			'mousewheel' => array(
				'type'    => 'bool',
				'default' => 'yes',
				'name'    => __( 'Mouse wheel control', 'shortcodes-ultimate' ),
				'desc'    => __( 'Allow to change slides with mouse wheel', 'shortcodes-ultimate' ),
			),
			'autoplay'   => array(
				'type'    => 'number',
				'min'     => 0,
				'max'     => 100000,
				'step'    => 100,
				'default' => 5000,
				'name'    => __( 'Autoplay', 'shortcodes-ultimate' ),
				'desc'    => __( 'Choose interval between slide animations. Set to 0 to disable autoplay', 'shortcodes-ultimate' ),
			),
			'speed'      => array(
				'type'    => 'number',
				'min'     => 0,
				'max'     => 20000,
				'step'    => 100,
				'default' => 600,
				'name'    => __( 'Speed', 'shortcodes-ultimate' ),
				'desc'    => __( 'Specify animation speed', 'shortcodes-ultimate' ),
			),
			'class'      => array(
				'type'    => 'extra_css_class',
				'name'    => __( 'Extra CSS class', 'shortcodes-ultimate' ),
				'desc'    => __( 'Additional CSS class name(s) separated by space(s)', 'shortcodes-ultimate' ),
				'default' => '',
			),
		),
		'desc'       => __( 'Customizable image slider', 'shortcodes-ultimate' ),
		'icon'       => 'picture-o',
	)
);

function su_shortcode_slider( $atts = null, $content = null ) {
	$return = '';
	$atts   = shortcode_atts(
		array(
			'source'     => 'none',
			'limit'      => 20,
			'gallery'    => null, // Dep. 4.3.2
			'link'       => 'none',
			'target'     => 'self',
			'width'      => 600,
			'height'     => 300,
			'responsive' => 'yes',
			'title'      => 'yes',
			'centered'   => 'yes',
			'arrows'     => 'yes',
			'pages'      => 'yes',
			'mousewheel' => 'yes',
			'autoplay'   => 3000,
			'speed'      => 600,
			'class'      => '',
		),
		$atts,
		'slider'
	);

	$slides = su_get_slides( $atts );
	$slides = apply_filters( 'su/shortcode/slider/slides', $slides, $atts );

	$atts['width']  = intval( $atts['width'] );
	$atts['height'] = intval( $atts['height'] );

	// Loop slides
	if ( count( $slides ) ) {
		// Prepare unique ID
		$id = uniqid( 'su_slider_' );
		// Links target
		$target = ( $atts['target'] === 'yes' || $atts['target'] === 'blank' ) ? ' target="_blank"' : '';
		// Centered class
		$centered = ( $atts['centered'] === 'yes' ) ? ' su-slider-centered' : '';
		// Wheel control
		$mousewheel = ( $atts['mousewheel'] === 'yes' ) ? 'true' : 'false';
		// Prepare width and height
		$size = ( $atts['responsive'] === 'yes' ) ? 'width:100%' : 'width:' . $atts['width'] . 'px;height:' . $atts['height'] . 'px';
		// Add lightbox class
		if ( $atts['link'] === 'lightbox' ) {
			$atts['class'] .= ' su-lightbox-gallery';
		}
		// Open slider
		$return .= '<div id="' . $id . '" class="su-slider' . $centered . ' su-slider-pages-' . esc_attr( $atts['pages'] ) . ' su-slider-responsive-' . esc_attr( $atts['responsive'] ) . su_get_css_class( $atts ) . '" style="' . $size . '" data-autoplay="' . esc_attr( $atts['autoplay'] ) . '" data-speed="' . esc_attr( $atts['speed'] ) . '" data-mousewheel="' . $mousewheel . '"><div class="su-slider-slides">';
		// Create slides
		foreach ( $slides as $slide ) {
			// Crop the image
			$image = su_image_resize( $slide['image'], $atts['width'], $atts['height'] );

			if ( is_wp_error( $image ) ) {
				continue;
			}

			// Prepare slide title
			$title = ( $atts['title'] === 'yes' && $slide['title'] ) ? '<span class="su-slider-slide-title">' . stripslashes( $slide['title'] ) . '</span>' : '';
			// Open slide
			$return .= '<div class="su-slider-slide">';
			// Slide content with link
			if ( $slide['link'] ) {
				$return .= '<a href="' . esc_attr( $slide['link'] ) . '" ' . $target . ' title="' . esc_attr( $slide['title'] ) . '"><img src="' . $image['url'] . '" alt="' . esc_attr( $slide['title'] ) . '" />' . $title . '</a>';
			}
			// Slide content without link
			else {
				$return .= '<a><img src="' . $image['url'] . '" alt="' . esc_attr( $slide['title'] ) . '" />' . $title . '</a>';
			}
			// Close slide
			$return .= '</div>';
		}
		// Close slides
		$return .= '</div>';
		// Open nav section
		$return .= '<div class="su-slider-nav">';
		// Append direction nav
		if ( $atts['arrows'] === 'yes' ) {
			$return .= '<div class="su-slider-direction"><span class="su-slider-prev"></span><span class="su-slider-next"></span></div>';
		}
		// Append pagination nav
		$return .= '<div class="su-slider-pagination"></div>';
		// Close nav section
		$return .= '</div>';
		// Close slider
		$return .= '</div>';
		// Add lightbox assets
		if ( $atts['link'] === 'lightbox' ) {
			su_query_asset( 'css', 'magnific-popup' );
			su_query_asset( 'js', 'magnific-popup' );
		}
		su_query_asset( 'css', 'su-shortcodes' );
		su_query_asset( 'js', 'jquery' );
		su_query_asset( 'js', 'swiper' );
		su_query_asset( 'js', 'su-shortcodes' );
	}
	// Slides not found
	else {
		$return = su_error_message( 'Slider', __( 'images not found', 'shortcodes-ultimate' ) );
	}
	return $return;
}
