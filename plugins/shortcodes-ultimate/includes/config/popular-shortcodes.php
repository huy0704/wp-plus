<?php defined( 'ABSPATH' ) || exit;

return apply_filters(
	'su/config/popular_shortcodes',
	array(
		array(
			'id'          => 'posts',
			'title'       => __( 'Posts', 'shortcodes-ultimate' ),
			'description' => __( 'Create your own posts query and display it where you want', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/posts.svg',
		),
		array(
			'id'          => 'accordion',
			'title'       => __( 'Accordion &amp; Spoiler', 'shortcodes-ultimate' ),
			'description' => __( 'Create a single toggle or an accordion with multiple items', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/accordion.svg',
		),
		array(
			'id'          => 'button',
			'title'       => __( 'Button', 'shortcodes-ultimate' ),
			'description' => __( 'Beautiful button with multiple styles and ton of options', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/button.svg',
		),
		array(
			'id'          => 'lightbox',
			'title'       => __( 'Lightbox', 'shortcodes-ultimate' ),
			'description' => __( 'A lightbox, that can display images and custom HTML', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/lightbox.svg',
		),
		array(
			'id'          => 'columns',
			'title'       => __( 'Columns', 'shortcodes-ultimate' ),
			'description' => __( 'Two shortcodes for creating flexible multi-column layouts', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/row.svg',
		),
		array(
			'id'          => 'image_carousel',
			'title'       => __( 'Image carousel', 'shortcodes-ultimate' ),
			'description' => __( 'A powerful shortcode for creating both sliders and carousels', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/image_carousel.svg',
		),
		array(
			'id'          => 'box',
			'title'       => __( 'Box', 'shortcodes-ultimate' ),
			'description' => __( 'Simple yet stylish colorful block with caption and content', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/box.svg',
		),
		array(
			'id'          => 'tabs',
			'title'       => __( 'Tabs', 'shortcodes-ultimate' ),
			'description' => __( 'Two shortcodes for breaking your content into tabs', 'shortcodes-ultimate' ),
			'icon'        => 'admin/images/shortcodes/tabs.svg',
		),
	)
);
