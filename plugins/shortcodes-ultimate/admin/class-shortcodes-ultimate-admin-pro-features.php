<?php

class Shortcodes_Ultimate_Admin_Pro_Features
{
    public function __construct()
    {
    }
    
    public function register_shortcodes()
    {
        if ( did_action( 'su/extra/ready' ) ) {
            return;
        }
        foreach ( $this->get_shortcodes() as $shortcode ) {
            su_add_shortcode( wp_parse_args( $shortcode, array(
                'group'              => 'extra',
                'image'              => $this->get_image_url( 'icon-available-shortcodes.png' ),
                'icon'               => $this->get_image_url( 'icon-generator.png' ),
                'desc'               => '',
                'callback'           => '__return_empty_string',
                'atts'               => array(),
                'generator_callback' => array( $this, 'generator_callback' ),
            ) ) );
        }
    }
    
    public function register_group( $groups )
    {
        if ( did_action( 'su/extra/ready' ) ) {
            return $groups;
        }
        $groups['extra'] = _x( 'Pro Shortcodes', 'Custom shortcodes group name', 'shortcodes-ultimate' );
        return $groups;
    }
    
    public function generator_callback( $shortcode )
    {
        su_partial( 'admin/partials/pro-features/generator.php', array(
            'shortcode' => $shortcode,
            'image_url' => $this->get_image_url(),
        ) );
    }
    
    public function get_image_url( $path = '' )
    {
        return plugin_dir_url( __FILE__ ) . 'images/pro-features/' . $path;
    }
    
    private function get_shortcodes()
    {
        return array(
            array(
            'id'   => 'splash',
            'name' => __( 'Splash screen', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'exit_popup',
            'name' => __( 'Exit popup', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'panel',
            'name' => __( 'Panel', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'photo_panel',
            'name' => __( 'Photo panel', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'icon_panel',
            'name' => __( 'Icon panel', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'icon_text',
            'name' => __( 'Text with icon', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'progress_pie',
            'name' => __( 'Progress pie', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'progress_bar',
            'name' => __( 'Progress bar', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'member',
            'name' => __( 'Member', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'section',
            'name' => __( 'Section', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'pricing_table',
            'name' => __( 'Pricing table', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'testimonial',
            'name' => __( 'Testimonial', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'icon',
            'name' => __( 'Icon', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'content_slider',
            'name' => __( 'Content slider', 'shortcodes-ultimate' ),
        ),
            array(
            'id'   => 'shadow',
            'name' => __( 'Shadow', 'shortcodes-ultimate' ),
        )
        );
    }
    
    public function add_generator_cta( $shortcodes )
    {
        if ( did_action( 'su/skins/ready' ) || su_fs()->can_use_premium_code() ) {
            return $shortcodes;
        }
        $cta = sprintf(
            '<span>%1$s</span><br><a href="%3$s" target="_blank" class="button">%2$s &rarr;</a>',
            // translators: please keep the original markup with <nobr> tags
            __( 'Get more styles for this shortcode with the <nobr>PRO version</nobr>', 'shortcodes-ultimate' ),
            __( 'Upgrade to PRO', 'shortcodes-ultimate' ),
            esc_attr( su_get_utm_link(
                'https://getshortcodes.com/pricing/',
                'wp-dashboard',
                'generator',
                'style'
            ) )
        );
        foreach ( array(
            'heading',
            'tabs',
            'tab',
            'accordion',
            'spoiler',
            'quote'
        ) as $shortcode ) {
            unset( $shortcodes[$shortcode]['note'] );
            $shortcodes[$shortcode]['generator_cta'] = $cta;
        }
        return $shortcodes;
    }

}