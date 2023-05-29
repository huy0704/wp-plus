<?php

function su_get_available_styles_for( $shortcode )
{
    $styles = su_get_available_styles();
    return ( isset( $styles[$shortcode] ) ? $styles[$shortcode] : array() );
}

function su_get_available_styles()
{
    $styles = array(
        'heading' => array(
        'default' => __( 'Default', 'shortcodes-ultimate' ),
    ),
        'quote'   => array(
        'default' => __( 'Default', 'shortcodes-ultimate' ),
    ),
        'tabs'    => array(
        'default' => __( 'Default', 'shortcodes-ultimate' ),
    ),
        'spoiler' => array(
        'default' => __( 'Default', 'shortcodes-ultimate' ),
        'fancy'   => __( 'Fancy', 'shortcodes-ultimate' ),
        'simple'  => __( 'Simple', 'shortcodes-ultimate' ),
    ),
    );
    return $styles;
}
