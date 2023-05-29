<?php

/**
 * Plugin Name: Shortcodes Ultimate
 * Plugin URI: https://getshortcodes.com/
 * Author: Vova Anokhin
 * Author URI: https://getshortcodes.com/
 * Description: A comprehensive collection of visual components for WordPress
 * Text Domain: shortcodes-ultimate
 * License: GPLv3
 * Version: 5.12.11
 * Requires PHP: 5.4
 * Requires at least: 5.0
 * Tested up to: 6.2
 *
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'su_fs' ) ) {
    su_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'su_fs' ) ) {
        
        if ( !function_exists( 'su_fs' ) ) {
            // Create a helper function for easy SDK access.
            function su_fs()
            {
                global  $su_fs ;
                
                if ( !isset( $su_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $su_fs = fs_dynamic_init( array(
                        'id'                => '7180',
                        'slug'              => 'shortcodes-ultimate',
                        'premium_slug'      => 'shortcodes-ultimate-pro',
                        'type'              => 'plugin',
                        'public_key'        => 'pk_c9ecad02df10f17e67880ac6bd8fc',
                        'is_premium'        => false,
                        'premium_suffix'    => 'Pro',
                        'has_addons'        => false,
                        'has_paid_plans'    => true,
                        'menu'              => array(
                        'slug'       => 'shortcodes-ultimate',
                        'first-path' => 'admin.php?page=shortcodes-ultimate',
                        'contact'    => false,
                        'support'    => false,
                    ),
                        'opt_in_moderation' => array(
                        'new'       => 100,
                        'updates'   => 0,
                        'localhost' => true,
                    ),
                        'is_live'           => true,
                    ) );
                }
                
                return $su_fs;
            }
            
            // Init Freemius.
            su_fs();
            // Signal that SDK was initiated.
            do_action( 'su_fs_loaded' );
        }
    
    }
    define( 'SU_PLUGIN_FILE', __FILE__ );
    define( 'SU_PLUGIN_VERSION', '5.12.11' );
    require_once dirname( __FILE__ ) . '/plugin.php';
}
