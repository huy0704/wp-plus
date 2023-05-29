<?php

/**
 * Plugin Name: Copy & Delete Posts
 * Plugin URI: https://copy-delete-posts.com
 * Description: The best solution to easily make duplicates of your posts & pages, and delete them in one go.
 * Version: 1.3.9
 * Author: Copy Delete Posts
 * Author URI: https://copy-delete-posts.com/
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH'))
    exit;

/** –– **\
 * Opt-in.
 * @since 1.1.2
 */
require_once 'analyst/main.php';
analyst_init(array(
    'client-id' => 'ovgxe3xq075ladbp',
    'client-secret' => 'b4de5ed2ba7be687e233d152ec1e8fd116052ab0',
    'base-dir' => __FILE__
));

/** –– * */
/** –– **\
 * Global variables and constants.
 * @since 1.0.0
 */
// Plugin constants
define('CDP_VERSION', '1.3.9');
define('CDP_WP_VERSION', get_bloginfo('version'));
define('CDP_SCRIPT_DEBUG', false);
define('CDP_ROOT_DIR', __DIR__);
define('CDP_ROOT_FILE', __FILE__);
define('CDP_MODULES_DIR', CDP_ROOT_DIR . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);

$cdp_plug_url = plugins_url('', __FILE__);

load_plugin_textdomain('copy-delete-posts', null, dirname(plugin_basename(__FILE__)) . '/languages');

// Other only admin stuff
if (is_admin()) {

    // Set constant variables for this file
    $cdp_dir = dirname(__FILE__);
    $cdp_globals = get_option('_cdp_globals');
    $cdp_premium = 'copy-delete-posts-premium/copy-delete-posts-premium.php';

    // Try to show error while debugging
    if (CDP_SCRIPT_DEBUG === true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
}

/** –– * */
/** –– **\
 * Fired on plugin activation.
 * @since 1.0.0
 */
register_activation_hook(__FILE__, function () {
    if (function_exists('activate_plugin')) {
        add_option('_cdp_redirect', true);
        $cdp_premium_path = WP_PLUGIN_DIR . '/copy-delete-posts-premium';
        $plugin = 'copy-delete-posts-premium/copy-delete-posts-premium.php';
        if (!is_plugin_active($plugin) && is_dir($cdp_premium_path))
            activate_plugin($plugin);
    }

    if (get_option('_cdp_review', false) == false) {
        $review = array(
            'installed' => time(),
            'users' => array()
        );

        update_option('_cdp_review', $review);
    }

    do_action('cdp_plugin_setup');
});
/** –– * */
/** –– **\
 * Fired on plugin deactivation.
 * @since 1.0.0
 */
register_deactivation_hook(__FILE__, function () {
    if (function_exists('deactivate_plugins')) {
        $plugin = 'copy-delete-posts-premium/copy-delete-posts-premium.php';
        if (is_plugin_active($plugin))
            add_action('update_option_active_plugins', function () {
                $plugin = 'copy-delete-posts-premium/copy-delete-posts-premium.php';
                deactivate_plugins($plugin);
            });
    }
});
/** –– * */
/** –– **\
 * Fired on plugin load and check permissions.
 * @since 1.0.0
 */
add_action('plugins_loaded', function () {
    do_action('cdp_loaded');
});
/** –– * */
/** –– **\
 * Fired on any plugin upgrade (in case if it's ours)
 * @since 1.0.6
 */
add_action('upgrader_process_complete', function () {
    if (get_option('_cdp_review', false) == false) {
        $review = array(
            'installed' => time(),
            'users' => array()
        );

        update_option('_cdp_review', $review);
    }
});
/** –– * */
/** –– **\
 * Include all menus.
 * @since 1.0.0
 */
	add_action('cdp_loaded', function () {

    if (get_option('_cdp_globals', false) === false) {
      do_action('cdp_plugin_setup');
    }

		// Include footer banner
		include_once trailingslashit(__DIR__) . '/banner/misc.php';

		// Others
		if (cdp_check_permissions(wp_get_current_user()) == false) return;
		require_once plugin_dir_path(__FILE__) . 'menu/configuration.php';
		require_once plugin_dir_path(__FILE__) . 'menu/tooltips.php';
		require_once plugin_dir_path(__FILE__) . 'menu/variables.php';
		require_once plugin_dir_path(__FILE__) . 'menu/modal.php';
		require_once plugin_dir_path(__FILE__) . 'menu/notifications.php';
		require_once plugin_dir_path(__FILE__) . 'post/handler.php';

		// Review banner
		if (!is_dir(WP_PLUGIN_DIR . '/copy-delete-posts-premium')) {
			if (!(class_exists('Inisev\Subs\Inisev_Review') || class_exists('Inisev_Review'))) require_once CDP_ROOT_DIR . '/modules/review/review.php';
			$review_banner = new \Inisev\Subs\Inisev_Review(CDP_ROOT_FILE, CDP_ROOT_DIR, 'copy-delete-posts', 'Copy & Delete Posts', 'https://bit.ly/2VeAf2E', 'copy-delete-posts');
		}

	});
/** –– **/

/** –– **\
 * Admin Init
 * @since 1.0.0
 */
add_action('admin_init', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    if (get_option('_cdp_redirect', false)) {
        delete_option('_cdp_redirect', false);
        wp_redirect(admin_url('admin.php?page=copy-delete-posts'));
    }

    global $cdp_premium;
    $cdp_premium_path = WP_PLUGIN_DIR . '/copy-delete-posts-premium';
    $cdp_premium_ver_path = WP_PLUGIN_DIR . '/copy-delete-posts-premium/version.txt';
    if (defined('CDP_PREMIUM_VERSION') && version_compare(CDP_PREMIUM_VERSION, CDP_VERSION, '!=')) {
        update_option('_cdp_mishmash', true);
    } else {
        if (is_dir($cdp_premium_path) && file_exists($cdp_premium_ver_path)) {
            $cdp_prem_ver_file = fopen($cdp_premium_ver_path, 'r') or false;
            $cdp_prem_ver = fgets($cdp_prem_ver_file);
            fclose($cdp_prem_ver_file);

            if ((trim($cdp_prem_ver) == CDP_VERSION) && !is_plugin_active($cdp_premium)) {
                activate_plugin($cdp_premium, '', false, true);
                // add_option('_cdp_redirect', false);
            }
            if (trim($cdp_prem_ver) == CDP_VERSION)
                update_option('_cdp_mishmash', false);
        }
    }
    if (is_plugin_active($cdp_premium) && !file_exists($cdp_premium_ver_path)) {
        if (is_plugin_active($cdp_premium))
            deactivate_plugins($cdp_premium, true);
    }
});
/** –– * */
/** –– **\
 * Setup assets.
 * @since 1.0.0
 */
add_action('admin_enqueue_scripts', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;
    if (function_exists('wp_doing_ajax') && wp_doing_ajax())
        return;
    global $cdp_plug_url;

    global $pagenow, $post;
    $screen = get_current_screen();
    $min = defined('CDP_SCRIPT_DEBUG') && CDP_SCRIPT_DEBUG ? '' : '.min';
    $allowed = ['post', 'edit-post', 'toplevel_page_copy-delete-posts', 'edit-page', 'page'];
    $at = ($screen->id != 'attachment');

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    if (is_object($post))
        $type = $post->post_type;
    else
        $type = false;

    if (isset($g['cdp-menu-in-settings']) && $g['cdp-menu-in-settings'] == 'true') {
        ?>
        <style media="screen">
            #toplevel_page_copy-delete-posts { display: none; visibility: hidden; }
        </style>
        <?php

    }

    $ver = preg_replace('#[^\pL\pN/-]+#', '', CDP_VERSION);
    wp_enqueue_style('cdp-css-global', "{$cdp_plug_url}/assets/css/cdp-global{$min}.css", '', $ver);
    wp_enqueue_script('cdp-js-global', "{$cdp_plug_url}/assets/js/cdp-global{$min}.js", ['jquery'], $ver, true);
    cdp_load_localize_global_js();
    wp_enqueue_style('cdp-css-select', "{$cdp_plug_url}/assets/css/cdp-select{$min}.css", '', $ver);
    wp_enqueue_script('cdp-js-select', "{$cdp_plug_url}/assets/js/cdp-select{$min}.js", '', $ver, true);
    wp_enqueue_style('cdp-tooltips-css', "{$cdp_plug_url}/assets/css/cdp.tooltip{$min}.css", '', $ver);
    wp_enqueue_script('cdp-tooltips', "{$cdp_plug_url}/assets/js/cdp.tooltip{$min}.js", '', $ver, true);
    if (method_exists($screen, 'is_block_editor')) {
        if (!$screen->is_block_editor() && $pagenow == 'post.php' && $at) {
            if (isset($g['cdp-display-edit']) && isset($g['cdp-content-posts']) && isset($g['cdp-content-pages']) && isset($g['cdp-content-custom']) && $g['cdp-display-edit'] == 'true') {
                $a = ($type == 'post' && $g['cdp-content-posts'] == 'true');
                $b = ($type == 'page' && $g['cdp-content-pages'] == 'true');
                $c = ($type != 'post' && $type != 'page' && $g['cdp-content-custom'] == 'true');
                if (($a || $b || $c) && $pagenow != 'post-new.php')
                    wp_enqueue_style('cdp-editor', "{$cdp_plug_url}/assets/css/cdp-editor{$min}.css", '', $ver);
            }
        }
    }

    $ver = preg_replace('#[^\pL\pN/-]+#', '', CDP_VERSION);
    if ((!$screen || !in_array($screen->id, $allowed)) && !($pagenow == 'edit.php' || $pagenow == 'post.php'))
        return;
    wp_enqueue_style('cdp-css', "{$cdp_plug_url}/assets/css/cdp{$min}.css", '', $ver);
    wp_enqueue_style('cdp-css-user', "{$cdp_plug_url}/assets/css/cdp-user{$min}.css", '', $ver);
    wp_enqueue_script('cdp-icPagination', "{$cdp_plug_url}/assets/js/cdp-icPagination{$min}.js", '', $ver);
    wp_enqueue_script('cdp', "{$cdp_plug_url}/assets/js/cdp{$min}.js", '', $ver, true);

    wp_enqueue_script('cdp-modal', "{$cdp_plug_url}/assets/js/cdp-modal{$min}.js", '', $ver, true);
    if (isset($g['cdp-content-custom']) && $g['cdp-content-custom'] == 'true') {
      wp_enqueue_script('cdp-bulk', "{$cdp_plug_url}/assets/js/cdp-bulk{$min}.js", '', $ver, true);
      $cdpBulkJSArgs = ['strOptCopy' => __('Copy', 'copy-delete-posts')];
      wp_localize_script('cdp-bulk', 'cdpBulkJS', $cdpBulkJSArgs);
    }
    cdp_load_localize_end_js();
}, 1000);
/** –– * */
/** –– **\
 * Setup assets (not admin).
 * @since 1.0.0
 */
add_action('wp_enqueue_scripts', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;
    if ((function_exists('wp_doing_ajax') && wp_doing_ajax()) || (!(is_single() || is_page())))
        return;
    global $cdp_plug_url;

    $ver = preg_replace('#[^\pL\pN/-]+#', '', CDP_VERSION);
    $min = defined('CDP_SCRIPT_DEBUG') && CDP_SCRIPT_DEBUG ? '' : '.min';
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style('cdp-css-global', "{$cdp_plug_url}/assets/css/cdp-global{$min}.css", '', $ver);
    wp_enqueue_script('cdp-js-global', "{$cdp_plug_url}/assets/js/cdp-global{$min}.js", ['jquery'], $ver, true);
    wp_enqueue_style('cdp-css', "{$cdp_plug_url}/assets/css/cdp{$min}.css", '', $ver);
    wp_enqueue_style('cdp-css-user', "{$cdp_plug_url}/assets/css/cdp-user{$min}.css", '', $ver);
    wp_enqueue_script('cdp', "{$cdp_plug_url}/assets/js/cdp{$min}.js", ['jquery'], $ver, true);
    wp_enqueue_script('cdp-tooltips', "{$cdp_plug_url}/assets/js/cdp.tooltip{$min}.js", '', $ver, true);
    wp_enqueue_style('cdp-tooltips-css', "{$cdp_plug_url}/assets/css/cdp.tooltip{$min}.css", '', $ver);
    wp_enqueue_script('cdp-modal', "{$cdp_plug_url}/assets/js/cdp-modal{$min}.js", ['jquery'], $ver, true);
    wp_enqueue_script('cdp-js-user', "{$cdp_plug_url}/assets/js/cdp-user{$min}.js", '', $ver, true);
    wp_enqueue_style('cdp-css-select', "{$cdp_plug_url}/assets/css/cdp-select{$min}.css", '', $ver);
    wp_enqueue_script('cdp-js-select', "{$cdp_plug_url}/assets/js/cdp-select{$min}.js", '', $ver, true);
    cdp_load_localize_global_js();
    cdp_load_localize_end_js();
});
/** –– * */
/** –– **\
 * Setup assets (for gutenberg).
 * @since 1.0.0
 */
add_action('enqueue_block_editor_assets', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    global $post;
    global $cdp_plug_url;
    $min = defined('CDP_SCRIPT_DEBUG') && CDP_SCRIPT_DEBUG ? '' : '.min';

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    $ver = preg_replace('#[^\pL\pN/-]+#', '', CDP_VERSION);
    if (is_object($post)) {
      $type = $post->post_type;
    } else {
      $type = false;
    }

    $a = ($type == 'post' && (isset($g['cdp-content-posts']) && $g['cdp-content-posts'] == 'true'));
    $b = ($type == 'page' && (isset($g['cdp-content-pages']) && $g['cdp-content-pages'] == 'true'));
    $c = ($type != 'post' && $type != 'page' && (isset($g['cdp-content-custom']) && $g['cdp-content-custom'] == 'true'));
    if ((isset($g['cdp-display-gutenberg']) && $g['cdp-display-gutenberg'] == 'true') && ($a || $b || $c)) {
        wp_enqueue_style('cdp-gutenberg', "{$cdp_plug_url}/assets/css/cdp-gutenberg{$min}.css", '', $ver);
        wp_enqueue_script('cdp-js-gutenberg', "{$cdp_plug_url}/assets/js/cdp-gutenberg{$min}.js", ['jquery'], $ver, true);
        $cdpGutenbergJSArgs = ['cdpCopy' => __('Copy this', 'copy-delete-posts')];
        wp_localize_script('cdp-js-gutenberg', 'cdpGutenbergJS', $cdpGutenbergJSArgs);
    }
});
/** –– * */
/** –– **\
 * Settings and menu initializer.
 * @since 1.0.0
 */
add_action('admin_menu', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    // Menu icon
    $icon_url = plugin_dir_url(__FILE__) . 'assets/imgs/icon.png';

    // Main menu slug
    $parentSlug = 'copy-delete-posts';

    // Globals
    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    // Main menu hook
    add_menu_page(__('Copy & Delete Posts', 'copy-delete-posts'), __('<span id="cdp-menu">Copy & Delete Posts</span>', 'copy-delete-posts'), 'read', $parentSlug, 'cdp_configuration', $icon_url, $position = 98);
    if (isset($g['cdp-menu-in-settings']) && $g['cdp-menu-in-settings'] == 'true') {
        add_submenu_page('tools.php', __('Copy & Delete Posts Menu', 'copy-delete-posts'), __('<span id="cdp-menu">Copy & Delete Posts</span>', 'copy-delete-posts'), 'read', 'copy-delete-posts', 'cdp_configuration', 3);
    }

    // Remove default submenu by menu
    remove_submenu_page($parentSlug, $parentSlug);
});
/** –– * */
/** –– **\
 * Add copy option to Quick Actions of Posts.
 * @since 1.0.0
 */
add_filter('post_row_actions', function ($actions, $post) {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return $actions;

    // Get global options and post type
    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    $type = $post->post_type;

    // If user want to see the copy buton here pass
    if (isset($g['cdp-display-posts']) && $g['cdp-display-posts'] == 'true') {
      if (($type == 'post' && isset($g['cdp-content-posts']) && $g['cdp-content-posts'] == 'true') || ($type != 'post' && isset($g['cdp-content-custom']) && $g['cdp-content-custom'] == 'true')) {
        $actions['cdp_copy'] = "<a href='#'><span class='cdp-copy-button cdp-tooltip-copy' x='" . $type . "' title='" . __('test', 'copy-delete-posts') . "' data-cdp-id='{$post->ID}'>" . __('Copy', 'copy-delete-posts') . "</span></a>";
      }
    }

    return $actions;
}, 10, 2);
/** –– * */
/** –– **\
 * Add copy option to Quick Actions of Pages.
 * @since 1.0.0
 */
add_filter('page_row_actions', function ($actions, $page) {

    if (cdp_check_permissions(wp_get_current_user()) == false) {
      return $actions;
    }

    // Get global options and post type
    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    $type = $page->post_type;

    // If user want to see the copy buton here pass
    if (isset($g['cdp-display-posts']) && $g['cdp-display-posts'] == 'true') {
      if (($type == 'page' && $g['cdp-content-pages'] == 'true') || ($type != 'page' && $g['cdp-content-custom'] == 'true')) {
        $actions['cdp_copy'] = "<a href='#'><span class='cdp-copy-button cdp-tooltip-copy' title='" . __('test', 'copy-delete-posts') . "' data-cdp-id='{$page->ID}'>" . __('Copy', 'copy-delete-posts') . "</span></a>";
      }
    }

    return $actions;

}, 10, 2);
/** –– * */
/** –– **\
 * Add copy option to Bulk Actions of Posts.
 * @since 1.0.0
 */
add_filter('bulk_actions-edit-post', function ($bulk_actions) {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    if (isset($g['cdp-display-bulk']) && $g['cdp-display-bulk'] == 'true' && isset($g['cdp-content-posts']) && $g['cdp-content-posts'] == 'true') {
      $bulk_actions['cdp_bulk_copy'] = __('Copy', 'copy-delete-posts');
    }

    return $bulk_actions;
});
/** –– * */
/** –– **\
 * Add copy option to Bulk Actions of Pages.
 * @since 1.0.0
 */
add_filter('bulk_actions-edit-page', function ($bulk_actions) {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    if (isset($g['cdp-display-bulk']) && $g['cdp-display-bulk'] == 'true' && isset($g['cdp-content-pages']) && $g['cdp-content-pages'] == 'true') {
      $bulk_actions['cdp_bulk_copy'] = __('Copy', 'copy-delete-posts');
    }

    return $bulk_actions;
});
/** –– * */
/** –– **\
 * Add copy option to admin bar inside preview.
 * @since 1.0.0
 */
add_action('admin_bar_menu', function ($admin_bar) {
    if (cdp_check_permissions(wp_get_current_user()) == false) {
      return;
    }

    $screen = ((function_exists('get_current_screen') ? get_current_screen() : false));
    $screens = ['post', 'page'];

    if (!(is_single() || is_page() || (isset($screen) && ($screen != false && in_array($screen->id, $screens))))) {
      return;
    }

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    global $post;
    if (is_object($post)) {
      $type = $post->post_type;
    } else {
      $type = false;
    }

    $a = ($type == 'post' && (isset($g['cdp-content-posts']) && $g['cdp-content-posts'] == 'true'));
    $b = ($type == 'page' && (isset($g['cdp-content-pages']) && $g['cdp-content-pages'] == 'true'));
    $c = ($type != 'post' && $type != 'page' && (isset($g['cdp-content-custom']) && $g['cdp-content-custom'] == 'true'));

    if (isset($g['cdp-display-admin']) && $g['cdp-display-admin'] == 'false') {
      return;
    }

    global $cdp_plug_url;

    if ($a || $b || $c) {
        $icon = '<span class="cdp-admin-bar-icon" data-plug-path="' . $cdp_plug_url . '" data-this-id="' . get_the_ID() . '"></span>';
        $admin_bar->add_menu(array(
            'id' => '#cdp-copy-bar-x',
            'parent' => null,
            'group' => null,
            'title' => $icon . __('Copy this', 'copy-delete-posts'),
            'href' => '#',
            'meta' => array('class' => 'cdp-admin-bar-copy', 'target' => '_self')
        ));
    }
}, 80);
/** –– * */
/** –– **\
 * Add notification to admin bar.
 * @since 1.0.0
 */
add_action('admin_bar_menu', function ($admin_bar) {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;
    if (!is_admin())
        return;

    if (!function_exists('cdp_notifications_menu'))
        require_once plugin_dir_path(__FILE__) . 'menu/notifications.php';

    if (function_exists('cdp_notifications_menu')) {
        $data = cdp_notifications_menu();
        $admin_bar->add_menu(array(
            'id' => 'wp-admin-copy-and-delete-posts',
            'parent' => null,
            'group' => null,
            'title' => $data['html'],
            'href' => '#',
            'meta' => array(
                'class' => 'cdp-admin-bar-noti menupop' . (($data['in_list'] == 0) ? ' cdp-noti-hide' : ''),
                'target' => '_self'
            )
        ));
    }
}, 80);
/** –– * */
/** –– **\
 * This function adds version info.
 * @since 1.0.0
 */
add_action('wp_head', function () {
    echo '<meta name="cdp-version" content="' . CDP_VERSION . '" />';
});
/** –– * */
/** –– **\
 * This function adds thickbox modal to preview and view pages.
 * @since 1.0.0
 */
add_action('wp_footer', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;

    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    if (isset($g['cdp-display-admin']) && $g['cdp-display-admin'] == 'false')
        return;

    global $cdp_plug_url, $post;

    $post_id = false;
    if (isset($post->ID))
        $post_id = $post->ID;


    $screen = ((function_exists('get_current_screen')) ? get_current_screen() : false);
    $profiles = get_option('_cdp_profiles');

    if (is_single() || is_page()) {

      $hx = false;

      if (isset($g['cdp-premium-hide-tooltip']) && $g['cdp-premium-hide-tooltip'] == 'true') {
        $hx = true;
      }

      cdp_vars($hx, $cdp_plug_url, $post_id);
      cdp_modal($screen, $profiles);

    }
});
/** –– * */
/** –– **\
 * Add prepared HTML for tooltips and other info.
 * @since 1.0.0
 */
add_action('admin_init', function () {
  add_action('admin_notices', function () {
      if (cdp_check_permissions(wp_get_current_user()) == false)
          return;

      global $post, $cdp_plug_url, $cdp_globals, $pagenow;
      $post_id = false;
      $hasParent = false;
      $screen = get_current_screen();
      $profiles = get_option('_cdp_profiles', array());
      $deny = ['edit-page', 'edit-post'];
      $hx = false;

      if (!in_array($screen->id, $deny)) {
          if (isset($post->ID)) {
              $post_id = $post->ID;
              $meta = get_post_meta($post->ID, '_cdp_origin');
              $site = get_post_meta($post->ID, '_cdp_origin_site');
              if ($cdp_globals && isset($cdp_globals['others']) && isset($cdp_globals['others']['cdp-references-post'])) {
                  if ($cdp_globals['others']['cdp-references-edit'] == 'true') {
                      if (function_exists('switch_to_blog') && $site)
                          switch_to_blog($site);
                      if (isset($meta[0]) && get_post_status($meta[0])) {
                          $parentTitle = get_the_title($meta[0]);
                          $link = get_post_permalink($meta[0]);
                          $hasParent = array(
                              'title' => $parentTitle,
                              'link' => $link
                          );
                      }
                      if (function_exists('restore_current_blog') && $site)
                          restore_current_blog();
                  }
              }
          }
      }
      if ($cdp_globals && isset($cdp_globals['others']) && isset($cdp_globals['others']['cdp-premium-hide-tooltip']) && $cdp_globals['others']['cdp-premium-hide-tooltip'] == 'true') {
          $hx = true;
      }

      if (get_option('_cdp_show_copy', false)) {
          echo '<span style="display: none; visibility: hidden;" id="cdp-show-copy-banner" data-value="true"></span>';
          delete_option('_cdp_show_copy');
      }

      if ($pagenow == 'edit.php')
          $post_id = false;

      cdp_vars($hx, $cdp_plug_url, $post_id, $hasParent, true);
      cdp_tooltip_content($profiles);
      cdp_modal($screen, $profiles);
  });
}, 10000);
/** –– * */
/** –– **\
 * Add button in standard editor.
 * @since 1.0.0
 */
add_action('post_submitbox_start', function () {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return;
    $g = get_option('_cdp_globals', array());
    if (isset($g['others'])) {
      $g = $g['others'];
    } else {
      $g = cdp_default_global_options();
    }

    if (isset($g['cdp-display-edit']) && $g['cdp-display-edit'] == 'false') {
      return;
    }

    global $post, $pagenow;
    if (is_object($post)) {
      $type = $post->post_type;
    } else {
      $type = false;
    }

    $a = ($type == 'post' && (isset($g['cdp-content-posts']) && $g['cdp-content-posts'] == 'true'));
    $b = ($type == 'page' && (isset($g['cdp-content-pages']) && $g['cdp-content-pages'] == 'true'));
    $c = ($type != 'post' && $type != 'page' && (isset($g['cdp-content-custom']) && $g['cdp-content-custom'] == 'true'));

    if (($a || $b || $c) && $pagenow != 'post-new.php')
        echo '<div id="cdp-copy-btn"><a class="cdp-copy-btn-editor" href="#">' . __('Copy this post', 'copy-delete-posts') . '</a></div>';
});
/** –– * */
/** –– **\
 * Add hook for cron (deletion).
 * @since 1.0.0
 */
add_action('cdp_cron_delete', function ($args = false) {

    $ids = $args['ids'];
    $trash = $args['trash'];
    $site = (isset($args['site'])) ? $args['site'] : '-1';
    $tok = $args['token']['token'];
    $tsk = $args['token']['tsk'];

    if ($args == false)
        return;

    $cdp_cron = get_option('_cdp_crons');

    if (!isset($cdp_cron[$tok]) || !$cdp_cron[$tok])
        return;

    $auit = $cdp_cron[$tok]['auit'];
    $auitd = $cdp_cron[$tok]['auitd'];

    $areWePro = areWePro(true, '/handler/crons.php', true);

    if ($auit == true && $areWePro && function_exists('cdpp_make_redirect'))
        cdpp_make_redirect($ids, $auitd);
    if ($trash == true && $areWePro && function_exists('cdpp_crons_trash_post'))
        cdpp_crons_trash_post($ids);
    else
        foreach ($ids as $i => $id)
            wp_delete_post($id, true);

    $cdp_cron[$tok]['tasks'][$tsk] = current_time('timestamp');

    $falsed = false;
    $last = false;
    $i = 0;
    $size = sizeof($cdp_cron[$tok]['tasks']);
    foreach ($cdp_cron[$tok]['tasks'] as $otsk => $val) {
        if ($val == false)
            $falsed = true;
        $i++;
        if ($i == ($size - 1))
            $last = $otsk;
    }

    if ($tsk == '-0' || $last == $tsk || $falsed == false || $cdp_cron[$tok]['tasks'][$last] != false) {
        $cdp_cron[$tok]['done'] = current_time('timestamp');
        $cdp_cron[$tok]['data'] = array('formated-date' => date('d M Y, H:i:s'));
        if (isset($cdp_cron[$tok]['del_size'])) {
            $cdp_cron[$tok]['data']['amount'] = $cdp_cron[$tok]['del_size'];
            $cdp_cron[$tok]['data']['text'] = __('Manual Cleanup removed ', 'copy-delete-posts') . $cdp_cron[$tok]['del_size'] . ' post(s).';
        }
        $cdp_cron[$tok]['shown'] = false;
    }

    update_option('_cdp_crons', $cdp_cron);
});
/** –– * */

/** –– **\
 * Check if user is permmited to use this plugin.
 * @since 1.0.0
 * @param $user = current_user = object
 *
 * @return boolean
 */
function cdp_check_permissions($user = array()) {
    if (!isset($user) || empty($user))
        return false;

    $access = false;
    $access_roles = get_option('_cdp_globals');
    if (!isset($access_roles['roles']))
        $access_roles = array();

    foreach ($user->roles as $role => $name) {
        if ($name == 'administrator' || (isset($access_roles['roles'][$name]) && $access_roles['roles'][$name] == 'true')) {
            $access = true;
            break;
        }
    }

    return $access;
}

/** –– * */
/** –– **\
 * First run of the plugin setup default options for Default profile
 * @since 1.0.0
 */
add_action('cdp_plugin_setup', 'cdp_setup_default_profile');

function cdp_setup_default_profile() {
    $current = get_option('_cdp_globals');
    $isSetup = get_option('_cdp_default_setup');
    if (($current != false && isset($current['default'])) || $isSetup)
        return;

    $already = array();
    $globals = array();
    $already['default'] = cdp_default_options();
    $globals['others'] = cdp_default_global_options();

    $s1 = update_option('_cdp_globals', $globals);
    $s2 = update_option('_cdp_profiles', $already);

    if ($s1 || $s2)
        update_option('_cdp_default_setup', true);
}

/** –– * */

function cdp_load_localize_global_js() {

  $cdpGlobalJSArgs = [
    'strDissmisIssue' => __('There was an issue while dismissing banner, please do it next time again.', 'copy-delete-posts'),
    'strDissmisRequestIssue' => __('Cannot send dismiss request. Are you connected to the internet?', 'copy-delete-posts'),
    'strReviewReview' => __('Thank you very much for the review, this banner will not appear again!', 'copy-delete-posts'),
    'strReviewAlready' => __('Thank you very much for the review, this banner will not appear again!', 'copy-delete-posts'),
    'strReviewLater' => __('Alright! We will ask you again in 14 days!', 'copy-delete-posts'),
    'strReviewDefault' => __('This banner will not appear again, if any feature is missing you can ask for it on our support forum!', 'copy-delete-posts'),
    'strTasksCleared' => __('Tasks cleared!', 'copy-delete-posts'),
    'strTasksUpdateIssue' => __('We couldn\'t update task list, please try again later or kill tasks individualy.', 'copy-delete-posts'),
    'strTasksUpdateIssue2' => __('We couldn\'t update task list, please try again later or kill tasks individualy.', 'copy-delete-posts'),
    'strTaskHide' => __('The task has been hidden', 'copy-delete-posts'),
    'strTaskHideIssue' => __('There was an error and we couldn\'t hide the task.', 'copy-delete-posts'),
    'strTaskHideIssue2' => __('There was an error and we couldn\'t hide the task.', 'copy-delete-posts'),
    'strTaskKill' => __('The task has been killed', 'copy-delete-posts'),
    'strTaskKillIssue' => __('There was an error and we couldn\'t kill the task.', 'copy-delete-posts'),
    'strTaskKillIssue2' => __('There was an error and we couldn\'t kill the task.', 'copy-delete-posts'),
    'strCDPInstallSuccess' => __('Plugin has been installed successfully, feel free to check those awesome features!', 'copy-delete-posts'),
    'strCDPInfo' => __('Copy & Delete Posts', 'copy-delete-posts'),
    'strCDPInfoPremium' => __('Premium', 'copy-delete-posts'),
    'strHideBannerNow' => __('It will hide this banner just for now.', 'copy-delete-posts'),
    'strErrWithNotif' => __('There was an error with this notification.', 'copy-delete-posts'),
    'strCDPInfo2' => __('Copy & Delete Posts', 'copy-delete-posts'),
  ];
  wp_localize_script('cdp-js-global', 'cdpGlobalJS', $cdpGlobalJSArgs);

}

function cdp_load_localize_end_js() {

  $cdpJSArgs = [
    'strNoItems' => __('No items to show, try with other filters!', 'copy-delete-posts'),
    'strIntroSaveIssue' => __('Something went wrong and we couldn\'t save intro settings!', 'copy-delete-posts'),
    'strIntroSaveIssue2' => __('Something went wrong and we couldn\'t save intro settings.', 'copy-delete-posts'),
    'strIntroUpdateIssue' => __('Something went wrong and we couldn\'t update intro settings – try again!', 'copy-delete-posts'),
    'strIntroUpdateIssue2' => __('Something went wrong and we couldn\'t update intro settings. – Please check console for more info.', 'copy-delete-posts'),
    'strSettingsSaved' => __('All your settings were saved!', 'copy-delete-posts'),
    'strSettingsSaveIssue' => __('Something went wrong and we couldn\'t save your settings.', 'copy-delete-posts'),
    'strSettingsSaveIssue2' => __('Something went wrong and we couldn\'t save your settings. – Please check console for more info.', 'copy-delete-posts'),
    'strTogglableName' => __('(Name of original)', 'copy-delete-posts'),
    'strCopyNumIssue' => __('You need to insert positive number for copies.', 'copy-delete-posts'),
    'strCopyPostIssue' => __('Something went wrong and we couldn\'t copy this post – please try again!', 'copy-delete-posts'),
    'strCopyPostIssueConsInfo' => __('Something went wrong and we couldn\'t copy this post. – See dev console for more info.', 'copy-delete-posts'),
    'strTooltipBtnIssue' => __('Couldn\'t recognize that button, please try again.', 'copy-delete-posts'),
    'strSelectedProfIssue' => __('Unable to recognize selected profile, please try again.', 'copy-delete-posts'),
    'strSelectedProfGetIssue' => __('Something went wrong and we couldn\'t get profile settings. – Check console for more details', 'copy-delete-posts'),
    'strConsLogErr' => __('Copy & Delete Post – Error', 'copy-delete-posts'),
    'strSelectedProfGetIssue2' => __('Something went wrong and we couldn\'t get profile settings – please try again!', 'copy-delete-posts'),
    'strCopyInProgress' => __('Copy in progress...', 'copy-delete-posts'),
    'strServerDataIssue' => __('There was an error while getting data from server, please try again.', 'copy-delete-posts'),
    'strPostListIssue' => __('Something went wrong and we couldn\'t get post list. – Please check console for more info.', 'copy-delete-posts'),
    'strCheckDuplicatesSearch' => __('Please, check for: which duplicates you are looking for', 'copy-delete-posts'),
    'strServerConnectIssue' => __('Cannot connect to the server, premium filters will not be applied.', 'copy-delete-posts'),
    'strFilterNotSelected' => __('You did not select any filter.<br>Click <a href="#" class="cdp-show-the-copies">here</a> to show all copies (of the selected post type) made by this plugin.', 'copy-delete-posts'),
    'strNoDataForFilter' => __('No posts/pages meeting the current filter conditions.', 'copy-delete-posts'),
    'strCheckSteps' => __('Please check that you completed all steps, before this task!', 'copy-delete-posts'),
    'strSelectPostToDel' => __('Nothing to do here, please select at least one post to delete.', 'copy-delete-posts'),
    'strFieldWrongCount' => __('You have wrong count in throttling field, please fix.', 'copy-delete-posts'),
    'strDeletionProcessStarted' => __('Deletion process started, you can close this page now and wait till the process ends!', 'copy-delete-posts'),
    'strDeletionTaskStart' => __('Something went wrong and we couldn\'t setup the deletion task.', 'copy-delete-posts'),
    'strDeletionTaskEnd' => __('– Please try without throttling.', 'copy-delete-posts'),
    'strDeletionTaskConsInfo' => __('Something went wrong and we couldn\'t setup the deletion task. – Please check console for more info.', 'copy-delete-posts'),
    'strLicenseKeyEmpty' => __('Please enter license key, currently it is empty.', 'copy-delete-posts'),
    'strLicenseActiveReloading' => __('License is already active, let us reload the page!', 'copy-delete-posts'),
    'strLicenseValidPressBtn' => __('License is valid, press the button again to activate!', 'copy-delete-posts'),
    'strUnknownRespRefreshPage' => __('Unknown response, please try refresh the page!', 'copy-delete-posts'),
    'strSendRequestIssue' => __('Something went wrong and we couldn\'t send request, please try refresh page and try again.', 'copy-delete-posts'),
    'strSendRequestIssue2' => __('Something went wrong and we couldn\'t send request, are you connected to the internet?', 'copy-delete-posts'),
    'strIncludingHtml' => __('<b>including</b> empty', 'copy-delete-posts'),
    'strIncludingHtml2' => __('only if <u><b>not</b></u> empty', 'copy-delete-posts'),
    'strTogglableNameChange' => __('Change it to (Name of original)', 'copy-delete-posts'),
    'strTogglableNameChangeBlank' => __('Change it to blank', 'copy-delete-posts'),
    'strDeletingPosts' => __('Deleting Posts', 'copy-delete-posts'),
    'strAutoCleanPosts' => __('Auto Cleaning-up', 'copy-delete-posts'),
    'strKill' => __('Kill', 'copy-delete-posts'),
    'strDeletedPosts' => __('Deleted Posts', 'copy-delete-posts'),
    'strAutoCleanedPosts' => __('Auto Cleaned-up', 'copy-delete-posts'),
    'strHide' => __('Hide', 'copy-delete-posts'),
    'strDefCopySettingsStart' => __('Define your copy settings', 'copy-delete-posts'),
    'strDefCopySettingsLink' => __('here', 'copy-delete-posts'),
    'strCopyToOtherSiteHtml' => __('If you’re on a multisite you can copy it to other sites with the Premium Plugin. <a href="https://sellcodes.com/CylMIdJD" target="_blank">Order it now!</a>', 'copy-delete-posts'),
    'strPremFeatureHtml' => __('This is a Premium Feature.<br><a href="https://sellcodes.com/CylMIdJD" target="_blank">Check it out and upgrade today</a>,<br>we made it <b>really affordable!</b>', 'copy-delete-posts'),
    'strPreOrderHtml' => __('This is a Premium Feature.<br><a href="https://sellcodes.com/CylMIdJD" target="_blank">Check it out and upgrade today</a>,<br>we made it <b>really affordable!</b>', 'copy-delete-posts'),
    'strMultisiteCanCopy' => __('If you’re on a multisite you can copy it to other sites.', 'copy-delete-posts'),
    'strChangeTypeOfCopy' => __('Change the type of this copy <span class="cdp-premium-icon cdp-big-icon"></span>', 'copy-delete-posts'),
    'strPostDuplicateOf' => __('This post is a duplicate of', 'copy-delete-posts'),
    'strAreYouSure' => __('Are you REALLY sure?', 'copy-delete-posts'),
    'strYouAreCrazy' => __('You\'re crazy! – Here is the output:', 'copy-delete-posts'),
    'strDebugOutputEnd' => __('–––––––––––––- END OF THE DEBUG OUTPUT –––––––––––––- ', 'copy-delete-posts'),
    'copySuccessfull' => __('Copy to clipboard was successfull.', 'copy-delete-posts'),
    'copyFailed' => __('Copy to clipboard failed, your browser does not support this function.', 'copy-delete-posts'),
  ];
  wp_localize_script('cdp', 'cdpJS', $cdpJSArgs);

  $cdpModalJSArgs = [
    'strRowCopyIssue' => __('Please select at least one row to copy.', 'copy-delete-posts'),
    'strRowCopyTryAgain' => __('Please select at least one row to copy and try again.', 'copy-delete-posts'),
    'strSettCopyIssue' => __('Please select basic settings for this copy.', 'copy-delete-posts'),
    'strPostCopyIssue' => __('Something went wrong and we couldn\'t copy this post – please try again!', 'copy-delete-posts'),
    'strPostCopyIssueConsoleInfo' => __('Something went wrong and we couldn\'t copy this post. – See dev console for more info.', 'copy-delete-posts'),
    'strRecogBtnIssue' => __('Couldn\'t recognize that button, please try again.', 'copy-delete-posts'),
    'strCopyInProgress' => __('Copy in progress...', 'copy-delete-posts'),
  ];
  wp_localize_script('cdp-modal', 'cdpModalJS', $cdpModalJSArgs);

}

/** –– **\
 * Default settings for unset profiles
 * @since 1.0.0
 */
function cdp_default_options() {
    return array(
        'title' => 'true',
        'date' => 'false',
        'status' => 'false',
        'slug' => 'true',
        'excerpt' => 'true',
        'content' => 'true',
        'f_image' => 'true',
        'template' => 'true',
        'format' => 'true',
        'author' => 'true',
        'password' => 'true',
        'attachments' => 'false',
        'children' => 'false',
        'comments' => 'false',
        'menu_order' => 'true',
        'category' => 'true',
        'post_tag' => 'true',
        'taxonomy' => 'true',
        'nav_menu' => 'true',
        'link_category' => 'true',
        'all_metadata' => 'false',
        'names' => array(
            'prefix' => '',
            'suffix' => '#[Counter]',
            'format' => '1',
            'custom' => 'm/d/Y',
            'display' => 'Default'
        ),
        'usmplugin' => 'false',
        'yoast' => 'false',
        'woo' => 'false'
    );
}

function cdp_default_global_options() {
    return array(
        'cdp-content-pages' => 'true',
        'cdp-content-posts' => 'true',
        'cdp-content-custom' => 'true',
        'cdp-display-posts' => 'true',
        'cdp-display-edit' => 'true',
        'cdp-display-admin' => 'true',
        'cdp-display-bulk' => 'true',
        'cdp-display-gutenberg' => 'true',
        'cdp-references-post' => 'false',
        'cdp-references-edit' => 'false',
        'cdp-premium-import' => 'false',
        'cdp-premium-hide-tooltip' => 'false',
        'cdp-premium-replace-domain' => 'false',
        'cdp-menu-in-settings' => 'false'
    );
}

/** –– * */
/** –– **\
 * Add state info if user want it (the reference to original)
 * @since 1.0.0
 */
if (is_admin()) {
    if ($cdp_globals && isset($cdp_globals['others']) && isset($cdp_globals['others']['cdp-references-post'])) {
        if ($cdp_globals['others']['cdp-references-post'] == 'true')
            add_filter('display_post_states', 'cdp_state_post_add', 1, 10);
    }
}

function cdp_state_post_add($post_states, $post) {
    if (cdp_check_permissions(wp_get_current_user()) == false)
        return $post_states;

    $meta = get_post_meta($post->ID, '_cdp_origin');
    $site = get_post_meta($post->ID, '_cdp_origin_site');

    if (function_exists('switch_to_blog') && $site)
        switch_to_blog($site);
    for ($i = sizeof($meta); $i >= 0; --$i) {
        if (isset($meta[($i - 1)]) && get_post_status($meta[$i - 1])) {
            $link = get_post_permalink($meta[$i - 1]);
            $title = get_the_title($meta[$i - 1]) . " – " . "ID: " . $meta[$i - 1];
            $post_states['_cdp'] = __('Copy of', 'copy-delete-posts') . " <a class='cdp-tooltip-top' title='$title' href='$link' target='_blank'>" . __('this', 'copy-delete-posts') . "</a> " . __('post', 'copy-delete-posts');
            break;
        }
    }
    if (function_exists('restore_current_blog') && $site)
        restore_current_blog();

    return $post_states;
}

/** –– * */

/** –– **\
 * Check the premium status and correction
 * @since 1.0.0
 */
function areWePro($include = true, $file = '/content/premium.php', $ignore_perms = false) {
    if (!$ignore_perms && cdp_check_permissions(wp_get_current_user()) == false)
        return false;

    // Get WP-Plugin path
    $premium_plugin = 'copy-delete-posts-premium/copy-delete-posts-premium.php';
    $premium_dir = WP_PLUGIN_DIR . '/' . 'copy-delete-posts-premium';
    $pplugin_path = $premium_dir . $file;
    $squirrel = 'cdpp_squirrelicense';
    $core = '/includes/core.php';
    $areWePro = false;

    // Load premium content if the plugin is here
    if (is_dir($premium_dir) && ((function_exists('is_plugin_active') && is_plugin_active($premium_plugin)) || (!function_exists('is_plugin_active')))) {

        // Include cool features
        if ($include && file_exists($pplugin_path))
            require_once($pplugin_path);

        // Is premium function
        if (file_exists($pplugin_path))
            $areWePro = true;

        // Has special functions
        if (file_exists($pplugin_path) && file_exists($premium_dir . $core)) {
            require_once($premium_dir . $core);
            if (function_exists($squirrel))
                $areWePro = $squirrel()['p'];
            else
                $areWePro = false;
        } else
            $areWePro = false;
    }

    // Return answer
    return $areWePro;
}

/** –– * */
/** –– **\
 * It adds button on plugin list below plugin
 * @since 1.0.0
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $links['cdp-settings-link'] = '<a href="' . admin_url('/admin.php?page=copy-delete-posts') . '">' . __('Settings', 'copy-delete-posts') . '</a>';
    return $links;
});
/** –– * */

/** –– **\
 * This function fixes Wordpress wp_upload_dir function
 * @since 1.0.0
 */
function cdp_fix_upload_paths($data) {
    // Check if the base URL matches the format
    $needs_fixing = preg_match("/wp-content\/blogs\.dir\/(\d+)\/files/", $data['baseurl'], $uri_part);

    if ($needs_fixing) {
        $data['url'] = str_replace($uri_part[0], 'files', $data['url']);
        $data['baseurl'] = str_replace($uri_part[0], 'files', $data['baseurl']);
    }

    return $data;
}

/** –– * */

/** –– **\
 * This function will sanitize whole array with sanitize_text_field – by RECURSION
 * @since 1.0.0
 */
function cdp_sanitize_array($data = null) {
    $array = array();
    if (is_array($data) || is_object($data))
        foreach ($data as $key => $value) {
            $key = ((is_numeric($key)) ? intval($key) : sanitize_text_field($key));
            if (is_array($value) || is_object($value))
                $array[$key] = cdp_sanitize_array($value);
            else
                $array[$key] = sanitize_text_field($value);
        }
    else if (is_string($data))
        return sanitize_text_field($data);
    else if (is_bool($data))
        return $data;
    else if (is_null($data))
        return 'false';
    else {
        error_log('Copy & Delete Posts[copy-delete-posts.php:1068]: Unknown AJaX datatype – ' . gettype($data));
        echo 'error – invalid data';
        wp_die();
    }

    return $array;
}

/** –– **/

/** –– **\
 * Activation of tryOutPlugins module
 * @since 1.3.6
 */
add_action('plugins_loaded', function () {

  if (!(class_exists('\Inisev\Subs\Inisev_Try_Out_Plugins') || class_exists('Inisev\Subs\Inisev_Try_Out_Plugins') || class_exists('Inisev_Try_Out_Plugins'))) {
    require_once __DIR__ . '/modules/tryOutPlugins/tryOutPlugins.php';
    $try_out_plugins = new \Inisev\Subs\Inisev_Try_Out_Plugins(__FILE__, __DIR__, 'Duplicate Posts', 'admin.php?page=copy-delete-posts');
  }

});

if (!has_action('wp_ajax_tifm_save_decision')) {
  add_action('wp_ajax_tifm_save_decision', function () {

    // Nonce verification
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'tifm_notice_nonce')) {
      wp_send_json_error();
      return;
    }

    if (isset($_POST['decision'])) {

      if ($_POST['decision'] == 'true') {
        update_option('_tifm_feature_enabled', 'enabled');
        delete_option('_tifm_disable_feature_forever', true);
        wp_send_json_success();
        exit;
      } else if ($_POST['decision'] == 'false') {
        update_option('_tifm_feature_enabled', 'disabled');
        update_option('_tifm_disable_feature_forever', true);
        wp_send_json_success();
        exit;
      } else if ($_POST['decision'] == 'reset') {
        delete_option('_tifm_feature_enabled');
        delete_option('_tifm_hide_notice_forever');
        delete_option('_tifm_disable_feature_forever');
        wp_send_json_success();
        exit;
      }

      wp_send_json_error();
      exit;

    }

  });
}
/** –– **/
