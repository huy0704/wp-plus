<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization and hooks.
 *
 * @since        5.0.0
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/includes
 */
class Shortcodes_Ultimate
{
    /**
     * The path to the main plugin file.
     *
     * @since    5.0.0
     * @access   private
     * @var      string      $plugin_file   The path to the main plugin file.
     */
    private  $plugin_file ;
    /**
     * The current version of the plugin.
     *
     * @since    5.0.0
     * @access   private
     * @var      string      $plugin_version   The current version of the plugin.
     */
    private  $plugin_version ;
    /**
     * The path to the plugin folder.
     *
     * @since    5.0.0
     * @access   private
     * @var      string      $plugin_path   The path to the plugin folder.
     */
    private  $plugin_path ;
    /**
     * The basename of the plugin.
     */
    private  $plugin_basename ;
    /**
     * The prefix of the plugin.
     *
     * @since    5.0.8
     * @access   private
     * @var      string      $plugin_prefix   The prefix of the plugin.
     */
    private  $plugin_prefix ;
    /**
     * Class instance.
     *
     * @since  5.1.0
     * @access private
     * @var    null      The single class instance.
     */
    private static  $instance ;
    /**
     * Upgrader class instance.
     *
     * @since  5.1.0
     * @var    Shortcodes_Ultimate_Upgrade  Upgrader class instance.
     */
    public  $upgrade ;
    /**
     * Menu classes instances.
     *
     * @since  5.1.0
     */
    public  $top_level_menu ;
    public  $shortcodes_menu ;
    public  $settings_menu ;
    /**
     * Notices classes instances.
     *
     * @since  5.1.0
     */
    public  $rate_notice ;
    /**
     * Suggest Pro features.
     *
     * @since  5.6.0
     */
    public  $admin_pro_features ;
    /**
     * Get class instance.
     *
     * @since  5.1.0
     * @return Shortcodes_Ultimate
     */
    public static function get_instance()
    {
        return self::$instance;
    }
    
    /**
     * Define the core functionality of the plugin.
     *
     * @since   5.0.0
     * @param string  $plugin_file    The path to the main plugin file.
     * @param string  $plugin_version The current version of the plugin.
     * @param string  $plugin_prefix  The prefix of the plugin.
     */
    public function __construct( $plugin_file, $plugin_version, $plugin_prefix )
    {
        $this->plugin_file = $plugin_file;
        $this->plugin_version = $plugin_version;
        $this->plugin_path = plugin_dir_path( $plugin_file );
        $this->plugin_prefix = $plugin_prefix;
        $this->plugin_basename = plugin_basename( $this->plugin_file );
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_common_hooks();
        self::$instance = $this;
    }
    
    /**
     * Load the required dependencies for the plugin.
     *
     * @since    5.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * Legacy dependencies.
         */
        require_once $this->plugin_path . 'inc/core/assets.php';
        require_once $this->plugin_path . 'inc/core/tools.php';
        require_once $this->plugin_path . 'inc/core/generator-views.php';
        require_once $this->plugin_path . 'inc/core/generator.php';
        /**
         * The class responsible for adding, storing and accessing shortcodes data.
         */
        require_once $this->plugin_path . 'includes/class-shortcodes-ultimate-shortcodes.php';
        /**
         * The class responsible for plugin upgrades.
         */
        require_once $this->plugin_path . 'includes/class-shortcodes-ultimate-upgrade.php';
        /**
         * Classes responsible for defining admin menus.
         */
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-admin.php';
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-admin-top-level.php';
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-admin-about.php';
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-admin-settings.php';
        /**
         * Classes responsible for displaying admin notices.
         */
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-notice.php';
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-notice-rate.php';
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-notice-unsafe-features.php';
        /**
         * Register custom widget
         */
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-widget.php';
        /**
         * Suggest Pro features
         */
        require_once $this->plugin_path . 'admin/class-shortcodes-ultimate-admin-pro-features.php';
        /**
         * Filters.
         */
        require_once $this->plugin_path . 'includes/filters.php';
        /**
         * Functions.
         */
        require_once $this->plugin_path . 'includes/functions-helpers.php';
        require_once $this->plugin_path . 'includes/functions-html.php';
        require_once $this->plugin_path . 'includes/functions-shortcodes.php';
        require_once $this->plugin_path . 'includes/functions-galleries.php';
        require_once $this->plugin_path . 'includes/functions-colors.php';
        require_once $this->plugin_path . 'includes/functions-styles.php';
        /**
         * Deprecated stuff.
         */
        require_once $this->plugin_path . 'includes/deprecated/class-su-data.php';
        require_once $this->plugin_path . 'includes/deprecated/class-su-tools.php';
        require_once $this->plugin_path . 'includes/deprecated/class-su-widget.php';
        require_once $this->plugin_path . 'includes/deprecated/functions.php';
    }
    
    /**
     * Register all of the hooks related to the admin area functionality of the
     * plugin.
     *
     * @since    5.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        /**
         * Upgrades.
         */
        $this->upgrade = new Shortcodes_Ultimate_Upgrade( $this->plugin_version );
        add_action( 'admin_init', array( $this->upgrade, 'maybe_upgrade' ) );
        /**
         * Top-level menu: Shortcodes
         * admin.php?page=shortcodes-ultimate
         */
        $this->top_level_menu = new Shortcodes_Ultimate_Admin_Top_Level( $this->plugin_file, $this->plugin_version, $this->plugin_prefix );
        add_action( 'admin_menu', array( $this->top_level_menu, 'add_menu_pages' ), 0 );
        /**
         * Submenu: About
         * admin.php?page=shortcodes-ultimate
         */
        $this->about_menu = new Shortcodes_Ultimate_Admin_About( $this->plugin_file, $this->plugin_version, $this->plugin_prefix );
        add_action( 'admin_menu', array( $this->about_menu, 'add_menu_pages' ), 0 );
        add_action( 'admin_enqueue_scripts', array( $this->about_menu, 'enqueue_scripts' ) );
        /**
         * Submenu: Settings
         * admin.php?page=shortcodes-ultimate-settings
         */
        $this->settings_menu = new Shortcodes_Ultimate_Admin_Settings( $this->plugin_file, $this->plugin_version, $this->plugin_prefix );
        add_action( 'admin_menu', array( $this->settings_menu, 'add_menu_pages' ), 20 );
        add_action( 'admin_init', array( $this->settings_menu, 'add_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this->settings_menu, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this->settings_menu, 'maybe_disable_unsafe_features' ) );
        /**
         * Notice: Rate plugin
         */
        $this->rate_notice = new Shortcodes_Ultimate_Notice_Rate( 'rate', $this->plugin_path . 'admin/partials/notices/rate.php' );
        add_action( 'load-plugins.php', array( $this->rate_notice, 'defer_first_time' ) );
        add_action( 'admin_notices', array( $this->rate_notice, 'display_notice' ) );
        add_action( 'admin_post_su_dismiss_notice', array( $this->rate_notice, 'dismiss_notice' ) );
        /**
         * Notice: Unsafe features
         */
        $this->unsafe_features_notice = new Shortcodes_Ultimate_Notice_Unsafe_Features( 'unsafe-features', $this->plugin_path . 'admin/partials/notices/unsafe-features.php' );
        add_action( 'admin_notices', array( $this->unsafe_features_notice, 'display_notice' ) );
        add_action( 'admin_post_su_dismiss_notice', array( $this->unsafe_features_notice, 'dismiss_notice' ) );
        add_action(
            'update_option_su_option_unsafe_features',
            array( $this->unsafe_features_notice, 'hide_notice_on_option_change' ),
            10,
            3
        );
        /**
         * Add/Save 'Slide link' field on attachment page.
         */
        add_filter(
            'attachment_fields_to_edit',
            'su_slide_link_input',
            10,
            2
        );
        add_filter(
            'attachment_fields_to_save',
            'su_slide_link_save',
            10,
            2
        );
        /**
         * Register custom widget
         */
        $this->widget = new Shortcodes_Ultimate_Widget( $this->plugin_prefix );
        add_action( 'widgets_init', array( $this->widget, 'register' ) );
        /**
         * Suggest PRO features
         */
        $this->admin_pro_features = new Shortcodes_Ultimate_Admin_Pro_Features();
        add_action( 'admin_init', array( $this->admin_pro_features, 'register_shortcodes' ) );
        add_filter( 'su/data/groups', array( $this->admin_pro_features, 'register_group' ) );
        add_filter( 'su/data/shortcodes', array( $this->admin_pro_features, 'add_generator_cta' ) );
    }
    
    /**
     * Register all of the hooks related to both admin area and public part of
     * the plugin.
     *
     * @since    5.0.4
     * @access   private
     */
    private function define_common_hooks()
    {
        /**
         * Add default shortcodes.
         */
        add_action( 'init', array( 'Shortcodes_Ultimate_Shortcodes', 'add_default_shortcodes' ), 20 );
        /**
         * Register all added shortcodes in WordPress.
         */
        add_action( 'init', array( 'Shortcodes_Ultimate_Shortcodes', 'register' ), 40 );
        /**
         * Disable wptexturize filter for nestable shortcodes.
         */
        add_filter( 'no_texturize_shortcodes', 'su_filter_disable_wptexturize', 10 );
        /**
         * Enable shortcodes in text widgets and category descriptions.
         */
        $enable_shortcodes_in = (array) get_option( 'su_option_enable_shortcodes_in' );
        if ( in_array( 'term_description', $enable_shortcodes_in, true ) ) {
            add_filter( 'term_description', 'do_shortcode' );
        }
        if ( in_array( 'widget_text', $enable_shortcodes_in, true ) ) {
            add_filter( 'widget_text', 'do_shortcode' );
        }
        /**
         * Enable custom formatting.
         */
        if ( get_option( 'su_option_custom-formatting' ) === 'on' ) {
            add_filter( 'the_content', 'su_filter_custom_formatting' );
        }
    }

}