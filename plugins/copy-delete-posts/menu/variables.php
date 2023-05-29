<?php
/**
 * Copy & Delete Posts – default menu.
 *
 * @package CDP
 * @subpackage SendingVariables
 * @author CopyDeletePosts
 * @since 1.0.0
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/** –– **\
 * Adding assets.
 * @since 1.2.2
 */
  add_action('cdp_notices_special', function() {

    if (cdp_check_permissions(wp_get_current_user()) == false) return;
    if (!get_option('cdp_dismiss_perf_notice', false) && get_option('cdp_latest_slow_performance', false)) {

      cdp_render_performance_notice();

    }

  });
/** –– **/

/** –– **\
 * Notice about performance.
 * @since 1.2.2
 */
function cdp_render_performance_notice() {

  global $wp_version;
  global $wpdb;
  global $cdp_plug_url;

  $mysqlVersion = $wpdb->db_version();

  $cdp_notice2 = __('%b_start%Please%b_end% copy below logs %a_start%into the forum%a_end% so that we can make the plugin even better (for free)!', 'copy-delete-posts');
  $cdp_notice2 = str_replace('%a_start%', '<a target="_blank" href="https://wordpress.org/support/plugin/copy-delete-posts/#new-topic-0">', $cdp_notice2);
  $cdp_notice2 = str_replace('%a_end%', '</a>', $cdp_notice2);
  $cdp_notice2 = str_replace('%b_start%', '<b class="cdp-please-big">', $cdp_notice2);
  $cdp_notice2 = str_replace('%b_end%', '</b>', $cdp_notice2);

  $logs = get_option('cdp_copy_logs_times', array());

  $theLog = '';

  $theLog .= 'The OS: ' . PHP_OS . "\n";
  $theLog .= 'PHP Version: ' . PHP_VERSION . "\n";
  $theLog .= 'WP Version: ' . $wp_version . "\n";
  $theLog .= 'MySQL Version: ' . $mysqlVersion . "\n";
  $theLog .= 'Directory Separator: ' . DIRECTORY_SEPARATOR . "\n\n";

  $theLog .= 'Copy logs:' . "\n";

  foreach ($logs as $key => $value) {
    $amount = isset($value['amount']) ? $value['amount'] : 1;
    $time = $value['time'];
    $perOne = $value['perOne'];
    $data = date('d-m-Y H:i:s', $value['data']);
    $memory = cdp_human_readable_bytes(intval($value['memory']));
    $peak = cdp_human_readable_bytes(intval($value['peak']));

    $theLog .= $data . ' - ' . $amount . 'x, [total: ' . $time . ', avg: ' . $perOne . '] (mem: ' . $memory . ' - ' . $value['memory'] . ', peak: ' . $peak . ' - ' . $value['peak'] . ')' . "\n";
  }

  ?>

  <div id="cdp_notice_error_modal" style="display: none; opacity: 0;">

    <div class="cdp_notice_content" style="opacity: 0; top: 45%;">

      <div class="cdp_notice_heading">
        <div class="cdp_notice_image">
          <img src="<?php echo $cdp_plug_url; ?>/assets/imgs/smile.svg" class="" alt="happy face">
        </div>
        <div class="cdp_notice_heading_text">
          <?php _e('Copy worked!', 'copy-delete-posts'); ?>
        </div>
        <div class="cdp-modal-times"></div>
      </div>

      <div class="cdp_notice_the_text">
        <div><?php _e('However, we noticed some optimization potential on your server.', 'copy-delete-posts'); ?></div>
        <div><?php echo $cdp_notice2; ?></div>
      </div>

      <div class="cdp-relative">
        <textarea readonly class="cdp_notice_logs"><?php echo $theLog ?></textarea>
        <div class="cdp-copy-notice-logs"><?php _e('Copy logs', 'copy-delete-posts'); ?></div>
      </div>

      <a href="https://wordpress.org/support/plugin/copy-delete-posts/#new-topic-0" class="cdp-nodec" style="text-decoration: none;" target="_blank">
        <div class="cdp_notice_goto_forum">
            <?php _e('Go to forum', 'copy-delete-posts'); ?>
        </div>
      </a>

      <div class="cdp-notice-troubles">
        <?php _e('Trouble logging in there?', 'copy-delete-posts'); ?>
        <span class="cdp-info-icon cdp-tooltip-top" title="<?php _e('Your account on Wordpress.org (where you open a new support thread) is different to the one you login to your WordPress dashboard (where you are now). If you don\'t have a WordPress.org account yet, please sign up at the top right on here. It only takes a minute :) Thank you!', 'copy-delete-posts'); ?>"></span>
      </div>

      <div class="cdp-cf">
        <div class="cdp-left cdp-notice-nope cdp_notice_perf_close">
          <span><?php _e('No, I don\'t want to help you to improve the plugin.', 'copy-delete-posts'); ?></span>
        </div>
        <div class="cdp-left cdp-notice-yeah cdp_notice_perf_close">
          <?php _e('Ok, done!', 'copy-delete-posts'); ?>
        </div>
      </div>
    </div>

  </div>
  <?php
}
/** –– **/

/** –– **\
 * There are constant (but dynamic per blog) variables.
 * @since 1.0.0
 */
function cdp_vars($hideTT = false, $cdp_plug_url = 'x', $post_id = false, $parent = false, $notify = false) {
  ?>

  <script>
    if (typeof ajaxurl === 'undefined') ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
  </script>
  <div class="cdp-copy-alert-success" style="top: -28px; opacity: 0; display: none;">
    <img src="<?php echo $cdp_plug_url ?>/assets/imgs/copy.png" alt="<?php _e('Successfull copy image', 'copy-delete-posts'); ?>">
  </div>
  <?php do_action('cdp_notices_special'); ?>
  <div class="cdp-copy-loader-overlay" style="opacity: 0">
    <div class="cdp-text-overlay">
      <h1 style="color: white; font-size: 25px;"><?php _e('Please wait, copying in progress...', 'copy-delete-posts'); ?></h1>
      <p>
        <?php _e('If you’re making a lot of copies it can take a while
        <br>(up to 5 minutes if you’re on a slow server).', 'copy-delete-posts'); ?>
      </p>
      <span><?php _e('Average time is 8 copies per second.', 'copy-delete-posts'); ?></span>
    </div>
    <div class="cdp-spinner"></div>
  </div>
  <input type="text" hidden id="cdp-purl" style="display: none; visibility: hidden;" value="<?php echo $cdp_plug_url ?>">
  <?php if ($hideTT == true): ?>
  <input type="text" hidden id="cdp-hideTT" style="display: none; visibility: hidden;" value="true">
  <?php endif; ?>

  <?php if ($post_id != false): ?>
  <input type="text" hidden id="cdp-current-post-id" style="display: none; visibility: hidden;" value="<?php echo $post_id ?>">
  <?php endif;?>

  <?php if ($parent != false): ?>
  <input type="text" hidden id="cdp-original-post" style="display: none; visibility: hidden;" data-cdp-parent="<?php echo $parent['title'] ?>" data-cdp-parent-url="<?php echo $parent['link'] ?>">
  <?php endif;?>

  <?php
}
/** –– **/
