<?php
/**
 * Copy & Delete Posts – default menu.
 *
 * @package CDP
 * @subpackage CopyModal
 * @author CopyDeletePosts
 * @since 1.0.0
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/** –– **\
 * There is constant modal html form using thickbox.
 * @since 1.0.0
 */
function cdp_modal($screen = '', $profiles = array()) {
  if (!function_exists('is_plugin_active')) require_once(ABSPATH.'wp-admin/includes/plugin.php');

  $isYoast = false; $isUSM = false; $isWoo = false;
  if (is_plugin_active('woocommerce/woocommerce.php')) $isWoo = true;
  if (is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')) $isYoast = true;
  if (
    is_plugin_active('USM-Premium/usm_premium_icons.php') ||
    is_plugin_active('Ultimate-Social-Media-Plus/ultimate_social_media_icons.php') ||
    is_plugin_active('Ultimate-Social-Media-Icons/ultimate_social_media_icons.php') ||
    is_plugin_active('Ultimate-Premium-Plugin/usm_premium_icons.php') ||
    is_plugin_active('ultimate-social-media-icons/ultimate_social_media_icons.php') ||
    is_plugin_active('ultimate-social-media-plus/ultimate-social-media-plus.php') ||
    is_plugin_active('ultimate-social-media-plus/ultimate_social_media_plus.php')
  ) $isUSM = true;

  $isMulti = is_multisite() != true ? ' disabled="disabled"' : '';

  // Ask for pro features
  $areWePro = areWePro();
  $globals = get_option('_cdp_globals');
?>
  <div id="cdp-copy-modal-global" class="cdp-modal cdp-copy-modal" style="display:none;">

    <div class="cdp-modal-content" style="padding-bottom: 15px; max-height: 90vh;">

      <div class="cdp-modal-times"></div>

      <div class="cdp-cf cdp-cp-pad" style="margin-top: 50px; padding-top: 0; padding-bottom: 10px;">
        <div class="cdp-left">
            <h2 class="cdp-f-s-16 cdp-f-w-semi-bold" style="margin: 0; line-height: 40px;"><?php _e('Elements to copy:', 'copy-delete-posts'); ?></h2>
        </div>
        <div class="cdp-right" style="width: calc(100% - 200px) !important; text-align: right !important;">
          <div class="cdp-cf cdp-inline" style="line-height: 40px">
              <div class="cdp-left cdp-f-s-16"><?php _e('Use as basis settings', 'copy-delete-posts'); ?></div>
            <?php $gepres = get_option('_cdp_preselections', array()); if (array_key_exists(get_current_user_id(), $gepres)) $preSelProf = $gepres[get_current_user_id()]; else $preSelProf = 0; ?>
            <select class="cdp-left cdp-modal-select cdp-ow-border cdp-input-dynamic cdp-modal-input-profiles-r cdp-select cdp-m-l-9-d" name="tooltip-which-profile-second-modal">
                <option value="custom"<?php echo (array_key_exists($preSelProf, $profiles) && !$profiles[$preSelProf])?' selected':''?> disabled><?php _e('–– Select ––', 'copy-delete-posts'); ?></option>
                <option value="alloptions"><?php _e('Select all', 'copy-delete-posts'); ?></option>
                <option value="clear"><?php _e('Clean slate', 'copy-delete-posts'); ?></option>
                <optgroup label="<?php _e('–– Profiles ––', 'copy-delete-posts'); ?>"></optgroup>
                <option value="custom_named" disabled><?php _e('Custom', 'copy-delete-posts'); ?></option>
              <?php
              if ($profiles != false && $areWePro) {
                foreach ($profiles as $profile => $vals):
                  $isSel = ($preSelProf == $profile);
                  ?>
                  <option value="<?php echo htmlspecialchars($profile); ?>"<?php echo ($isSel)?' selected':''?>><?php echo ucfirst(htmlspecialchars($vals['names']['display'])); ?></option>
                <?php endforeach; } else { ?>
                  <option value="default"><?php _e('Default', 'copy-delete-posts'); ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="cdp-cp-pad">
        <div class="cdp-modal-checkboxes cdp-checkboxes">
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="title">
            <span><?php _e('Title', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="date">
            <span><?php _e('Date', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="status">
            <span><?php _e('Status', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="slug">
            <span><?php _e('Slug', 'copy-delete-posts'); ?></span>
          </label>
        </div>
        <div class="cdp-modal-checkboxes cdp-checkboxes">
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="excerpt">
            <span><?php _e('Excerpt', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="content">
            <span><?php _e('Content', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="f_image">
            <span><?php _e('Feat. image', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="template">
            <span><?php _e('Template', 'copy-delete-posts'); ?></span>
          </label>
        </div>
        <div class="cdp-modal-checkboxes cdp-checkboxes">
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="format">
            <span><?php _e('Format', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="author">
            <span><?php _e('Author', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="password">
            <span><?php _e('Password', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="children">
            <span><?php _e('Children', 'copy-delete-posts'); ?></span>
          </label>
        </div>
        <div class="cdp-modal-checkboxes cdp-checkboxes">
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="comments">
            <span><?php _e('Comments', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="menu_order">
            <span><?php _e('Menu order', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="attachments">
            <span><?php _e('Attachments', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="category">
            <span><?php _e('Categories', 'copy-delete-posts'); ?></span>
          </label>
        </div>
        <div class="cdp-modal-checkboxes cdp-checkboxes">
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="post_tag">
            <span><?php _e('Tags', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="taxonomy">
            <span><?php _e('Taxonomies', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="nav_menu">
            <span><?php _e('Navigation Menus', 'copy-delete-posts'); ?></span>
          </label>
          <label>
            <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="link_category">
            <span><?php _e('Link categories', 'copy-delete-posts'); ?></span>
          </label>
        </div>
        <?php if ($isYoast || $isUSM || $isWoo || true): ?>
          <div class="cdp-modal-checkboxes cdp-checkboxes cdp-modal-checkboxes-three">
            <label class="cdp-relative"><span class="cdp-premium-icon" style="margin-left: 0"></span><b style="padding-left: 21px;" class="cdp-f-s-15 cdp-f-w-medium"><?php _e('Plugin options:', 'copy-delete-posts'); ?></b></label>
            <br>
            <?php if ($isWoo): ?>
            <label class="cdp-woo">
              <div class="cdp-inline cdp-tooltip-premium-spc">
                <input class="cdp-input-dynamic" type="checkbox" name="woo">
                <span><?php _e('Woo Settings', 'copy-delete-posts'); ?></span>
              </div>
            </label>
            <?php endif; ?>
            <?php if ($isYoast): ?>
            <label class="cdp-yoast">
              <div class="cdp-inline cdp-tooltip-premium-spc">
                <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="yoast">
                <span><?php _e('Yoast Settings', 'copy-delete-posts'); ?></span>
              </div>
            </label>
            <?php endif; ?>
            <?php if ($isUSM): ?>
            <label>
              <div class="cdp-inline cdp-tooltip-premium-spc">
                <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="usmplugin">
                <span><?php _e('USM Settings', 'copy-delete-posts'); ?></span>
              </div>
            </label>
            <?php endif; ?>
            <label>
              <div class="cdp-inline cdp-tooltip-premium-spc">
                <input class="cdp-modal-option-r cdp-input-dynamic" type="checkbox" name="all_metadata">
                <span><?php _e('All post meta', 'copy-delete-posts'); ?></span>
              </div>
            </label>
          </div>
        <?php endif; ?>
      </div>

      <div class="cdp-modal-copy-times cdp-f-s-15">
        <div class="cdp-modal-copy-times-content">
          <?php if ($areWePro && function_exists('cdpp_change_post_type')) cdpp_change_post_type(); ?>
          <div class="cdp-cf cdp-inline">
            <div class="cdp-left" style="line-height: 40px;"><?php _e('Copy', 'copy-delete-posts'); ?>&nbsp;</div>
            <div class="cdp-left" style="line-height: 40px;">
              <input class="cdp-modal-input-times cdp-input-border" style="border-width: 1px !important;" placeholder="1" type="number" value="1">
            </div>
            <div class="cdp-left" style="line-height: 40px;">
              &nbsp;<?php _e('time(s)', 'copy-delete-posts'); ?>
            </div>
            <div class="cdp-left" style="line-height: 40px;">&nbsp;to</div>
            <div class="cdp-left">
              <div class="cdp-inline cdp-tooltip-premium-spc-2 <?php echo (($isMulti != '')?' cdp-tooltip-premium-spc-3':' cdp-tooltip-premium-spc-4'); ?>">
                <select class="cdp-input-dynamic cdp-modal-select cdp-modal-select-2 cdp-ow-border cdp-modal-input-site cdp-select cdp-m-l-9-d" name="tooltip-which-site-second-modal" <?php echo $isMulti; ?>>
                  <option value="-1"><?php _e('this site', 'copy-delete-posts'); ?></option>
                  <?php if ($areWePro && function_exists('cdpp_get_sites')) echo cdpp_get_sites(true); ?>
                </select>
              </div>
            </div>
          </div>
          <?php if (is_multisite()) { ?>
            <div class="cdp-center cdp-multiple-sites-selector">
              (<?php _e('click here to select multiple sites', 'copy-delete-posts'); ?>)
            </div>
            <div class="cdp-center cdp-multi-site-at-once-selector" style="display: none;">
              <select multiple>
                <option value="-1" selected><?php _e('this site', 'copy-delete-posts'); ?></option>
                <?php if ($areWePro && function_exists('cdpp_get_sites')) echo cdpp_get_sites(true); ?>
              </select>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="cdp-center">
        <span class="cdp-error-span-tooltip"><?php _e('Making more than 50 copies will take some time. – depending on your server.', 'copy-delete-posts'); ?></span>
      </div>

      <div class="cdp-center cdp-p-25-h">
        <button class="cdp-button cdp-copy-modal-button cdp-f-s-15 cdp-f-w-regular" data-cdp-btn="copy-custom" style="height:44px; width:211px;padding:0 20px;line-height: 44px;"><?php _e('Copy it!', 'copy-delete-posts'); ?></button>
        <?php if (isset($globals) && is_array($globals) && array_key_exists('afterCopy', $globals) && $globals['afterCopy'] == '3'): ?>
        <button class="cdp-button cdp-copy-modal-button cdp-p-right-h cdp-f-s-15 cdp-f-w-regular" data-cdp-btn="copy-custom-link" style="height:44px; width:292px;padding:0 20px;line-height: 44px;margin-left: 15px !important;"><?php _e('Copy and jump to editing', 'copy-delete-posts'); ?></button>
        <?php endif; ?>
      </div>
    </div>

  </div>
<?php
}
/** –– **/
