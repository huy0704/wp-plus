<?php

require_once dirname( __FILE__ ) . '/includes/class-shortcodes-ultimate-activator.php';
require_once dirname( __FILE__ ) . '/includes/class-shortcodes-ultimate.php';
register_activation_hook( SU_PLUGIN_FILE, array( 'Shortcodes_Ultimate_Activator', 'activate' ) );
call_user_func( function () {
    // don't run the plugin during activation (after plugins_loaded)
    if ( did_action( 'plugins_loaded' ) ) {
        return;
    }
    $plugin = new Shortcodes_Ultimate( SU_PLUGIN_FILE, SU_PLUGIN_VERSION, 'shortcodes-ultimate-' );
    do_action( 'su/ready', $plugin );
} );