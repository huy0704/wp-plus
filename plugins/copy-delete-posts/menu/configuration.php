<?php
/**
 * Copy & Delete Posts – default menu.
 *
 * @package CDP
 * @subpackage Configuration
 * @author CopyDeletePosts
 * @since 1.0.0
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/** –– **\
 * Adding assets.
 * @since 1.0.0
 */
  add_action('admin_enqueue_scripts', function() {
    if (cdp_check_permissions(wp_get_current_user()) == false) return;
  	$current_screen = get_current_screen();

  	if (!is_object($current_screen)) return;
  	if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return;

  	wp_enqueue_script('cdp');
  	wp_enqueue_script('jquery-ui-draggable');
  	wp_enqueue_script('jquery-ui-droppable');
  	wp_enqueue_script('jquery-ui-sortable');
  	wp_enqueue_style('cdp-css');
  });
/** –– **/

/** –– **\
 * Main plugin configuration menu.
 * @since 1.0.0
 */
function cdp_configuration() {
  if (cdp_check_permissions(wp_get_current_user()) == false) return;

  global $cdp_plug_url;
  $current_user = wp_get_current_user();
  $user_id = get_current_user_id();
  $no_intro = (get_option('_cdp_no_intro')) ? get_option('_cdp_no_intro') : array();
  $has_intro = !in_array($user_id, $no_intro) || false;
  $profiles = get_option('_cdp_profiles');

  $defaults = get_option('_cdp_profiles');
  if ($defaults && array_key_exists('default', $defaults)) $defaults = $defaults['default'];
  else $defaults = cdp_default_options();

  $globals = get_option('_cdp_globals');
  $roles = get_editable_roles();

  $isCoolInstalled = '';
  if (get_option('_cdp_cool_installation', false)) {
    delete_option('_cdp_cool_installation');
    $isCoolInstalled = ' cdp_is_cool_installed';
  }

  $names_format = false;
  $after_copy = false;
  $post_converter = false;
  $gos = cdp_default_global_options();
  if (isset($defaults['names']))
    if (isset($defaults['names']['format'])) $names_format = intval($defaults['names']['format']);

  if (isset($globals)) {
    if (isset($globals['afterCopy'])) $after_copy = $globals['afterCopy'];
    if (isset($globals['postConverter'])) $post_converter = $globals['postConverter'];
    if (isset($globals['others'])) $gos = $globals['others'];
  }

  if (!array_key_exists('cdp-display-bulk', $gos)) $gos = cdp_default_global_options();

  // Ask for pro features
  $areWePro = areWePro();

  if (!$has_intro) {
    $intro = ' style="display: none;"';
    $content = '';
  } else {
    $intro = '';
    $content = ' style="display: none; opacity: 0;"';
  }

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

  ?>

  <style>
    #wpcontent {padding-left: 2px !important;}
    /* #wpbody {overflow-y: scroll;overflow-x: auto;max-height: calc(100vh - 32px);} */
    #wpfooter { display: none !important; }
    #wpfooter #footer-left { display: none; }
    #wpfooter #footer-upgrade { display: none; }
  </style>
  <?php if ($areWePro && function_exists('cdpp_profile_manager_html')) cdpp_profile_manager_html(); ?>
  <?php if ($areWePro && function_exists('cdpp_delete_redi_modal')) cdpp_delete_redi_modal(); ?>
  <div class="cdp-preloader-c<?php echo $isCoolInstalled ?>">
      <div class="cdp-center"><?php _e('Loading...', 'copy-delete-posts'); ?></div>
    <div class="cdp-preloader"></div>
  </div>
  <div class="cdp-container cdp-main-menu-cont" style="display: none;">
    <div class="cdp-content">
      <div class="cdp-cf">
        <div class="cdp-left">
          <!-- <h1 class="cdp-f-s-30 cdp-f-w-light cdp-welcome-title-after">Welcome<?php echo ' ' . $current_user->user_login . ','; ?> to Copy & Delete Posts!</h1> -->
            <h1 class="cdp-f-s-30 cdp-f-w-medium cdp-welcome-title-after"><?php _e('Welcome to Copy & Delete Posts!', 'copy-delete-posts'); ?></h1>
        </div>
        <div class="cdp-right">
            <div class="cdp-s-i-a cdp-welcome-title-after cdp-text-right cdp-green"<?php echo $content ?>><a class="cdp-pointer" id="cdp-show-into-again"><span class="cdp-green cdp-f-s-16"><?php _e('Show intro', 'copy-delete-posts'); ?></span></a></div>
        </div>
      </div>

      <div class="cdp-intro"<?php echo $intro ?>>
        <div class="cdp-box cdp-white-bg cdp-shadow">
            <div class="cdp-font-heading cdp-f-s-21 cdp-f-w-regular"><?php _e('You can now easily copy posts & pages in various areas:', 'copy-delete-posts'); ?></div>
          <div class="cdp-cf">
            <div class="cdp-showcase-3 cdp-left">
                <div class="cdp-font-title cdp-f-s-22 cdp-f-w-bold cdp-center"><?php _e('List of posts/pages', 'copy-delete-posts'); ?></div>
              <div class="cfg-img-sc-3 cdp-intro-image cdp-intro-img-1" alt="lists">
                <img src="<?php echo $cdp_plug_url; ?>/assets/imgs/intro_1.gif" class="cdp-no-vis cfg-img-sc-3" alt="lists">
              </div>
            </div>
            <div class="cdp-showcase-3 cdp-left">
                <div class="cdp-font-title cdp-f-s-22 cdp-f-w-bold cdp-center"><?php _e('Edit screen', 'copy-delete-posts'); ?></div>
              <div class="cfg-img-sc-3 cdp-intro-image cdp-intro-img-2" alt="edit">
                <img src="<?php echo $cdp_plug_url; ?>/assets/imgs/intro_2.gif" class="cdp-no-vis cfg-img-sc-3" alt="edit">
              </div>
            </div>
            <div class="cdp-showcase-3 cdp-left">
              <div class="cdp-font-title cdp-f-s-22 cdp-f-w-bold cdp-center"><?php _e('Admin bar', 'copy-delete-posts'); ?></div>
              <div class="cfg-img-sc-3 cdp-intro-image cdp-intro-img-3" alt="adminbar">
                <img src="<?php echo $cdp_plug_url; ?>/assets/imgs/intro_3.gif" class="cdp-no-vis cfg-img-sc-3 cfg-img-sc-3-special" alt="adminbar">
              </div>
            </div>
          </div>
            <div class="cdp-center cdp-font-footer"><?php _e('…and you can also <b>delete duplicate</b> posts easily, see below :)', 'copy-delete-posts'); ?></div>
          <div class="cdp-center cdp-intro-options">
              <button class="cdp-intro-button cdp-f-s-21 cdp-f-w-bold"><?php _e('Got it, close intro!', 'copy-delete-posts'); ?></button>
            <div class="cdp-ff-b1 cdp-checkboxes cdp-hide" style="margin-top: 5px;">
              <label for="cdp-never-intro">
                <input type="checkbox" checked id="cdp-never-intro" style="margin-top: -3px !important"/>
                <?php _e('Don\'t show this intro – never again!', 'copy-delete-posts'); ?>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="cdp-cf cdp-profile-bar">
        <div class="cdp-left cdp-lh-48 cdp-f-s-20">
          <div class="cdp-cf">
            <div class="cdp-left cdp-f-w-light">
              <?php _e('Below are your', 'copy-delete-posts'); ?>
            </div>
            <select class="cdp-left cdp-profile-selected cdp-select-styled cdp-select cdp-select-padding cdp-ow-border cdp-f-s-19 cdp-color-p-i<?php echo ((!$areWePro)?' cdp-premium-in-select':''); ?>">
              <?php

              $preSelProf = 0;
              $gepres = get_option('_cdp_preselections', array());

              if (array_key_exists(get_current_user_id(), $gepres)) {
                $preSelProf = $gepres[get_current_user_id()];
              }

              if ($profiles != false && $areWePro) {
              foreach ($profiles as $profile => $vals):
                $isSel = ($preSelProf == $profile);
                ?>
                <option value="<?php echo htmlspecialchars($profile); ?>"<?php echo ($isSel)?' selected':''?>><?php echo ucfirst(htmlspecialchars($vals['names']['display'])); ?></option>
              <?php endforeach; } else { ?>
                <option value="default" selected><?php _e('Default', 'copy-delete-posts'); ?></option>
                <option value="premium" disabled><?php _e('Add new', 'copy-delete-posts'); ?></option>
              <?php } ?>
            </select>
            <div class="cdp-left cdp-f-w-light">
              <?php _e('settings', 'copy-delete-posts'); ?>
            </div>
          </div>
        </div>
        <div class="cdp-right cdp-lh-48 cdp-relative">
          <div>
            <span class="cdp-tooltip-premium" style="padding: 25px 0">
                <span class="cdp-manager-btns cdp-green cdp-hover cdp-pointer cdp-f-w-light cdp-f-s-16" style="padding-right: 33px;"><?php _e('+ Add / manage / import / export settings', 'copy-delete-posts'); ?></span>
              <span class="cdp-premium-icon cdp-big-icon" style="right: 3px;"></span>
            </span>
          </div>
        </div>
      </div>
      <div class="cdp-collapsibles" style="padding-top: 5px;">

        <!-- SETTINGS PROFILE SECTION -->
        <div class="cdp-collapsible" data-cdp-group="mains">
          <div class="cdp-collapsible-title">
            <div class="cdp-cf">
                <div class="cdp-left cdp-ff-b1"><?php _e('Which <b class="cdp-ff-b4">elements</b> shall be copied?', 'copy-delete-posts'); ?></div>
              <div class="cdp-right"><i class="cdp-arrow cdp-arrow-left"></i></div>
            </div>
          </div>
          <div class="cdp-collapsible-content cdp-nm cdp-np">
            <div style="overflow-x: auto; max-width: 100%;">
              <table class="cdp-table">
                <thead class="cdp-thead cdp-f-s-18">
                  <tr>
                    <th></th>
                    <th><?php _e('<b>If checked</b> copies will...', 'copy-delete-posts'); ?></th>
                    <th><?php _e('<b>If <u class="cdp-f-w-bold">un</u>checked</b> copies will...', 'copy-delete-posts'); ?></th>
                  </tr>
                </thead>
                <tbody class="cdp-ff-b1 cdp-f-s-18 cdp-tbody-of-settings">
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['title']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="title" type="checkbox" /><span><?php _e('Title', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the title as defined in the <a href="#" class="cdp-go-to-names-chapter"><span class="cdp-green">next section</span></a>.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…be titled “Untitled”.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['date']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="date" type="checkbox" /><span><?php _e('Date', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same creation date & time as the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the date & time at time of copying. ', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['status']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="status" type="checkbox" /><span><?php _e('Status', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get status of original article (which can be “published” or “deleted” etc.)', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the status “Draft”.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['slug']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="slug" type="checkbox" /><span><?php _e('Slug', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same <a href="https://kinsta.com/knowledgebase/wordpress-slug/" target="_blank"><span class="cdp-green">slug</span></a> of the original. (However after publishing it will give it automatically a new slug because 2 pages cannot be on the same URL).', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get a blank slug, unless the page is published, then it will generate it automatically.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['excerpt']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="excerpt" type="checkbox" /><span><?php _e('Excerpt', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the custom <a href="https://wordpress.org/support/article/excerpt/" target="_blank"><span class="cdp-green">excerpt</span></a> (post/page summary) of the original (if the original had any).', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get an empty <i>custom</i> excerpt (and default to taking the first 55 words of the post).', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['content']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="content" type="checkbox" /><span><?php _e('Content', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get all the content (text, images, videos and other elements/blocks) from the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get no content (be completely blank).', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['f_image']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="f_image" type="checkbox" /><span><?php _e('Featured image', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…it will set the same <a href="https://firstsiteguide.com/wordpress-featured-image/" target="_blank"><span class="cdp-green">featured image</span></a> as the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get no featured image.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['template']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="template" type="checkbox" /><span><?php _e('Template', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same page <a href="https://wpapprentice.com/blog/wordpress-theme-vs-template/" target="_blank"><span class="cdp-green">template</span></a> as original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the default page template.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['format']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="format" type="checkbox" /><span><?php _e('Format', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same <a href="https://wordpress.org/support/article/post-formats/" target="_blank"><span class="cdp-green">post format</span></a> as original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the standard post format.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['author']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="author" type="checkbox" /><span><?php _e('Author', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same author as original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the user that is duplicating as an author.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['password']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="password" type="checkbox" /><span><?php _e('Password', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the same <a href="https://wordpress.org/support/article/using-password-protection/" target="_blank"><span class="cdp-green">password</span></a> as original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get no password.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['attachments']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="attachments" type="checkbox" /><span><?php _e('Attachments', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…create new <a href="https://wordpress.org/support/article/using-image-and-file-attachments/#attachment-to-a-post" target="_blank"><span class="cdp-green">attachments</span></a> (duplicates in Media Library) as well. <i>Recommended only for Multisites.</i>', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get existing attachments from the original.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['children']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="children" type="checkbox" /><span><?php _e('Children', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get their <a href="https://phppot.com/wordpress/how-to-create-a-child-page-in-wordpress/" target="_blank"><span class="cdp-green">child pages</span></a> copied as well, with all current settings applied to child-duplicates (if the page is a parent).', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…not get their child pages copied along (if the page is a parent).', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['comments']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="comments" type="checkbox" /><span><?php _e('Comments', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get all comments from the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get no comments from the original.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['menu_order']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="menu_order" type="checkbox" /><span><?php _e('Menu order', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get the <a href="https://wordpress.stackexchange.com/questions/25202/how-to-change-order-of-menu-items" target="_blank"><span class="cdp-green">menu order</span></a> from the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…get the menu order set to default (0).', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['category']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="category" type="checkbox" /><span><?php _e('Categories', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get all categories from the original post.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…be Uncategorized, no categories will be copied.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['post_tag']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="post_tag" type="checkbox" /><span><?php _e('Tags', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get all tags of the original post.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…be without any tags.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['taxonomy']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="taxonomy" type="checkbox" /><span><?php _e('Taxonomies', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get all custom taxonomy from the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…be without custom taxonomy.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                    <td>
                      <label>
                        <div class="cdp-cf">
                          <div class="cdp-left">
                            <input <?php echo $defaults['nav_menu']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="nav_menu" type="checkbox" />
                          </div>
                          <div class="cdp-left cdp-relative" style="width: calc(100% - 45px)">
                              <span><?php _e('Navigation Menus', 'copy-delete-posts'); ?> <span class="cdp-info-icon" style="top: calc(50% + 4px) !important;"></span> </span>
                          </div>
                        </div>
                      </label>
                    </td>
                    <td><?php _e('…get this private taxonomy from the original.', 'copy-delete-posts'); ?></td>
                    <td><?php _e('…be without private taxonomy.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr>
                      <td><label class="cdp-relative"><input <?php echo $defaults['link_category']=='true'?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="link_category" type="checkbox" /><span><?php _e('Link categories', 'copy-delete-posts'); ?> <span class="cdp-info-icon"></span></span></label></td>
                      <td><?php _e('…get this private taxonomy from the original.', 'copy-delete-posts'); ?></td>
                      <td><?php _e('…be without private taxonomy.', 'copy-delete-posts'); ?></td>
                  </tr>

                  <tr>
                    <td class="cdp-tooltip-premium">
                      <label>
                        <div class="cdp-cf">
                          <div class="cdp-left">
                            <input <?php echo (array_key_exists('all_metadata', $defaults) && $defaults['all_metadata']=='true' && $areWePro == true)?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="all_metadata" type="checkbox" />
                          </div>
                          <div class="cdp-left cdp-relative" style="width: calc(100% - 45px)">
                              <span><?php _e('All post meta', 'copy-delete-posts'); ?><span class="cdp-premium-icon cdp-big-icon"></span> </span>
                          </div>
                        </div>
                      </label>
                    </td>
                    <td><?php _e('…clone all metadata tags assigned to post/page.', 'copy-delete-posts'); ?></td>
                    <td><?php _e('…copy only required post/page metadata.', 'copy-delete-posts'); ?></td>
                  </tr>
                  <tr<?php echo (!$isWoo)?' style="display: none;"':'' ?>>
                    <td class="cdp-tooltip-premium">
                      <label>
                        <div class="cdp-cf">
                          <div class="cdp-left">
                            <input <?php echo (array_key_exists('woo', $defaults) && $defaults['woo']=='true' && $areWePro == true)?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="woo" type="checkbox" />
                          </div>
                          <div class="cdp-left cdp-relative" style="width: calc(100% - 45px)">
                              <span><?php _e('WooCommerce Settings', 'copy-delete-posts'); ?><span class="cdp-premium-icon cdp-big-icon" style="top: calc(50% + 2px) !important;"></span> </span>
                          </div>
                        </div>
                      </label>
                    </td>
                    <td><?php _e('…the same settings from the <a href="https://wordpress.org/plugins/woocommerce/" target="_blank"><span class="cdp-green">WooCommerce plugin</span></a> as the original.', 'copy-delete-posts'); ?></td>
                    <td><?php _e('…get empty settings.', 'copy-delete-posts'); ?></td>
                  </tr>

                  <tr<?php echo (!$isUSM)?' style="display: none;"':'' ?>>
                    <td class="cdp-tooltip-premium">
                      <label>
                        <div class="cdp-cf">
                          <div class="cdp-left">
                            <input <?php echo ($defaults['usmplugin']=='true' && $areWePro == true)?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="usmplugin" type="checkbox" />
                          </div>
                          <div class="cdp-left cdp-relative" style="width: calc(100% - 45px)">
                              <span><?php _e('Ultimate Social Media Settings', 'copy-delete-posts'); ?><span class="cdp-premium-icon cdp-big-icon" style="top: calc(50% + 2px) !important;"></span> </span>
                          </div>
                        </div>
                      </label>
                    </td>
                    <td><?php _e('…the same settings from the <a href="https://www.ultimatelysocial.com/usm-premium/" target="_blank"><span class="cdp-green">Ultimate Social Media plugin</span></a> as the original.', 'copy-delete-posts'); ?></td>
                    <td><?php _e('…get empty settings.', 'copy-delete-posts'); ?></td>
                  </tr>

                  <tr<?php echo (!$isYoast)?' style="display: none;"':'' ?>>
                    <td class="cdp-tooltip-premium">
                      <label>
                        <div class="cdp-cf">
                          <div class="cdp-left">
                            <input <?php echo ($defaults['yoast']=='true' && $areWePro == true)?'checked ':''; ?>class="cdp-data-set" data-cdp-opt="yoast" type="checkbox" />
                          </div>
                          <div class="cdp-left cdp-relative" style="width: calc(100% - 45px)">
                              <span><?php _e('Yoast SEO Settings', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon" style="top: calc(50% + 2px) !important;"></span> </span>
                          </div>
                        </div>
                      </label>
                    </td>
                    <td><?php _e('…the same settings from the <a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank"><span class="cdp-green">Yoast SEO plugin</span></a> as the original.', 'copy-delete-posts'); ?></td>
                    <td><?php _e('…get empty settings.', 'copy-delete-posts'); ?></td>
                  </tr>

                </tbody>
              </table>
            <div class="cdp-pad-lin cdp-gray cdp-f-s-18 cdp-lh-24 cdp-center" style="padding-top: 40px; padding-bottom: 20px;">
                <i><?php _e('Do you know anything else you want to have copied (e.g. data added by a different plugin)? <br />
                Please <a href="mailto:hi@copy-delete-posts.com" target="_blank"><span class="cdp-green">tell us about it</span></a>, we always want to further improve this plugin! :) ', 'copy-delete-posts'); ?></i>
            </div>
            <div class="cdp-center cdp-padding cdp-p-35-b">
                <button class="cdp-button cdp-save-options"><?php _e('Save', 'copy-delete-posts'); ?></button>
              <div class="cdp-padding cdp-f-s-17">
                  <a href="#" class="cdp-close-chapter"><?php _e('Close section', 'copy-delete-posts'); ?></a>
              </div>
            </div>
          </div>
        </div>
        </div>

        <!-- OTHER SETTINGS PROFILE SECTION -->
        <div class="cdp-collapsible" data-cdp-group="mains">
          <div class="cdp-collapsible-title cdp-name-section-cnx">
            <div class="cdp-cf">
                <div class="cdp-left cdp-ff-b1"><?php _e('What <b class="cdp-ff-b4">name(s)</b> should the copies have?', 'copy-delete-posts'); ?></div>
              <div class="cdp-right"><i class="cdp-arrow cdp-arrow-left"></i></div>
            </div>
          </div>
          <div class="cdp-collapsible-content cdp-np cdp-drags-cont">
          <div class="cdp-pad-lin cdp-f-s-18 cdp-f-w-light">
            <?php _e('Build your preferred naming logic for the copies:', 'copy-delete-posts'); ?>
          </div>
          <div class="cdp-green-bg cdp-pad-lin" style="padding-bottom: 20px;">
            <div class="cdp-cf cdp-center">
                <div class="cdp-left cdp-names-input cdp-f-s-16"><?php _e('Prefix', 'copy-delete-posts'); ?></div>
              <div class="cdp-left cdp-names-divider cdp-nlh"></div>
              <div class="cdp-left cdp-names-input cdp-f-s-16"><?php _e('Suffix', 'copy-delete-posts'); ?></div>
            </div>
            <div class="cdp-cf cdp-center">
              <div class="cdp-left cdp-names-input">
                <div strip-br="true" class="cdp-magic-input cdp-shadow cdp-sorts cdp-names-real-input cdp-names-prefix" wrap="off" contenteditable="true" style="margin-right: 0">
                  <?php echo (isset($defaults['names']) && isset($defaults['names']['prefix']))?$defaults['names']['prefix']:''; ?>
                </div>
              </div>
              <div class="cdp-left cdp-names-divider cdp-f-s-19 cdp-f-w-light">
                  <span class="cdp-tooltip-top cdp-togglable-name-b" title="<?php _e('Change to blank', 'copy-delete-posts'); ?>"><?php _e('(Name of original)', 'copy-delete-posts'); ?></span>
              </div>
              <div class="cdp-left cdp-names-input">
                <div strip-br="true" class="cdp-magic-input cdp-shadow cdp-sorts cdp-names-real-input cdp-names-suffix" wrap="off" contenteditable="true" style="margin-left: 0">
                  <?php echo (isset($defaults['names']) && isset($defaults['names']['suffix']))?$defaults['names']['suffix']:''; ?>
                </div>
              </div>
            </div>
            <div class="cdp-curr-cont">
                <span class="cdp-f-s-18"><?php _e('Example based on current selections:', 'copy-delete-posts'); ?></span>
              <span class="cdp-f-s-16 cdp-padding-10-h">
                  <span class="cdp-example-name cdp-f-w-bold"><?php _e('(Name of original)', 'copy-delete-posts'); ?></span>
              </span>
            </div>
          </div>
          <div class="cdp-pad-lin cdp-f-s-18">
              <div class="cdp-padding-23-h"><?php _e('Drag & drop the automatic elements into the Prefix/Suffix fields to add them.', 'copy-delete-posts'); ?></div>
            <div class="">
              <div class="cdp-cf cdp-padding-10-h">
                <div class="cdp-left">
                    <div class="cdp-name-box cdp-drag-name cdp-name-clickable" oncontextmenu="return false;" data-cdp-val="0"><?php _e('Counter', 'copy-delete-posts'); ?></div>
                </div>
                  <div class="cdp-left cdp-names-text-info"><?php _e('Adds an <b class="cdp-f-w-semi-bold">incremental counter</b> (starting with “2”)', 'copy-delete-posts'); ?></div>
              </div>
              <div class="cdp-cf cdp-padding-10-h">
                <div class="cdp-left" style="margin-top: 6px;">
                    <div class="cdp-name-box cdp-drag-name cdp-name-clickable" oncontextmenu="return false;" data-cdp-val="2"><?php _e('CurrentDate', 'copy-delete-posts'); ?></div>
                </div>
                <div class="cdp-left cdp-names-text-info">
                  <div class="cdp-cf" style="line-height: 49px !important;">
                    <div class="cdp-left">
                      <?php _e('Adds the <b class="cdp-f-w-semi-bold">current date</b> in', 'copy-delete-posts'); ?>
                    </div>
                    <select class="cdp-left cdp-select-styled cdp-date-picked cdp-select cdp-dd-p-43 cdp-select-padding cdp-ow-border cdp-f-s-19 cdp-select-black cdp-option-premium" name="cdp-date-option">
                        <option value="1"<?php echo ($names_format == 1 || $names_format == false || (!$areWePro && $names_format == 3))?' selected':''; ?>><?php _e('mm/dd/yyyy', 'copy-delete-posts'); ?></option>
                        <option value="2"<?php echo ($names_format == 2)?' selected':''; ?>><?php _e('dd/mm/yyyy', 'copy-delete-posts'); ?></option>
                        <option value="3"<?php echo (($areWePro && $names_format == 3)?' selected':''); ?>><?php _e('Custom', 'copy-delete-posts'); ?></option>
                    </select>
                    <?php if ($areWePro && function_exists('cdpp_custom_date')) cdpp_custom_date($names_format, $defaults); ?>
                      <div class="cdp-left" style="padding-left: 15px;"><?php _e('format.', 'copy-delete-posts'); ?></div>
                  </div>
                  <?php if ($areWePro && function_exists('cdpp_custom_date_info')) cdpp_custom_date_info(); ?>
                </div>
              </div>
              <div class="cdp-cf cdp-padding-10-h" style="padding-bottom: 0; margin-top: 6px;">
                <div class="cdp-left">
                    <div class="cdp-name-box cdp-drag-name cdp-name-clickable" oncontextmenu="return false;" data-cdp-val="1"><?php _e('CurrentTime', 'copy-delete-posts'); ?></div>
                </div>
                  <div class="cdp-left cdp-names-text-info"><?php _e('Adds the <b class="cdp-f-w-semi-bold">current time</b> in hh:mm:ss format', 'copy-delete-posts'); ?></div>
              </div>
            </div>
            <div class="cdp-padding-23-h">
                <p class="cdp-f-s-18"><?php _e('You can also type tailored text into the fields above.', 'copy-delete-posts'); ?></p>
                <p class="cdp-f-s-18"><?php _e('If you’re not of the drag & droppy-type, you can also enter shortcodes [Counter], [CurrentDate] and [CurrentTime].', 'copy-delete-posts'); ?></p>
                <p class="cdp-f-s-18"><?php _e('If you make multiple copies in one go, use the Counter-option as otherwise some copies will have the same name.', 'copy-delete-posts'); ?></p>
            </div>
            <div class="cdp-center">
                <button class="cdp-button cdp-save-options"><?php _e('Save', 'copy-delete-posts'); ?></button>
              <div class="cdp-padding cdp-f-s-17">
                  <a href="#" class="cdp-close-chapter"><?php _e('Close section', 'copy-delete-posts'); ?></a>
              </div>
            </div>
          </div>
        </div>
        </div>

        <!-- GLOBAL SECTION -->
        <div class="cdp-collapsible" data-cdp-group="mains">
          <div class="cdp-collapsible-title" id="cdp-global-section-collapser">
            <div class="cdp-cf">
                <div class="cdp-left cdp-ff-b1"><?php _e('<b class="cdp-ff-b4">Other</b> options', 'copy-delete-posts'); ?></div>
              <div class="cdp-right"><i class="cdp-arrow cdp-arrow-left"></i></div>
            </div>
          </div>
          <div class="cdp-collapsible-content cdp-oth-section cdp-np cdp-special-cb-p">
            <div class="cdp-pad-lin">
                <div><h2 class="cdp-f-s-18"><b class="cdp-f-w-bold"><?php _e('Navigation after copying', 'copy-delete-posts'); ?></b></h2></div>
              <div class="cdp-padding-15-h">
                <div class="cdp-con-cen">
                  <select class="cdp-other-options cdp-select cdp-select-centered cdp-sel-separat cdp-select-large cdp-dd-p-40 cdp-c-x-a-v" name="after_copy">
                      <option value="1"<?php echo ($after_copy == '1' || $after_copy == false)?' selected':''; ?>><?php _e('Leave me where I was', 'copy-delete-posts'); ?></option>
                      <option value="2"<?php echo ($after_copy == '2')?' selected':''; ?>><?php _e('Take me to the edit-page of the created copy', 'copy-delete-posts'); ?></option>
                      <option value="3"<?php echo ($after_copy == '3')?' selected':''; ?>><?php _e('Decide on a case-by-case basis (adds new button on copy screen)', 'copy-delete-posts'); ?></option>
                  </select>
                </div>
                  <div class="cdp-if-edit-page-selected cdp-con-cen cdp-f-s-18 cdp-f-w-light cdp-p-25-40" style="display: none;"><?php _e('If you created multiple copies in one go, you’ll be taken to the first copy.', 'copy-delete-posts'); ?> </div>
              </div>
              <div>
                <h2>
                    <b class="cdp-relative cdp-f-s-18 cdp-f-w-bold cdp-tooltip-premium" data-top="5" style="padding-right: 30px;"><?php _e('Pages vs. Posts converter', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon"></span></b>
                </h2>
              </div>
              <div class="cdp-f-s-18 cdp-f-w-light">
                  <p class="cdp-f-s-18 cdp-f-w-light"><?php _e('By default, the type of what you copy does not change, i.e. if you copy a post the new version will also be a post, and the same for pages.', 'copy-delete-posts'); ?></p>
                  <p class="cdp-padding-15-h cdp-f-s-18 cdp-f-w-light"><?php _e('If you want to make a page out of a post, or vice versa, then you can do this on a <b class="cdp-f-w-bold">case by case basis</b> if you select the option “Define it specifically for this case” in the copy-tooltip, and then select this option on the following screen in the tooltip.', 'copy-delete-posts'); ?></p>
                  <p class="cdp-f-s-18 cdp-f-w-light"><?php _e('However, if you want it as a <b class="cdp-f-w-bold">default setting option</b>, then please select it below: ', 'copy-delete-posts'); ?></p>
              </div>
              <div class="cdp-con-cen">
                <div class="cdp-tooltip-premium" style="width: 663px; margin: 0 auto; height: 60px;" data-top="-10">
                  <select class="cdp-other-options cdp-select cdp-select-centered cdp-sel-separat cdp-select-large cdp-c-x-a-v" name="post_converter">
                      <option value="1"<?php echo ($post_converter == '1' || $post_converter == false)?' selected':''; ?>><?php _e('Copies will be the same type as the original', 'copy-delete-posts'); ?></option>
                      <option value="2"<?php echo ($post_converter == '2')?' selected':''; ?>><?php _e('ALWAYS change the type when copied (posts will become pages, pages will become posts)', 'copy-delete-posts'); ?></option>
                  </select>
                </div>
              </div>
                <div class=""><h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('User level permissions', 'copy-delete-posts'); ?></b></h2></div>
                <div class="cdp-f-s-18 cdp-p-15-25 cdp-f-w-light"><?php _e('Which user role(s) should be able to copy & delete? <i style="color: gray">– The role also must have access to the dashboard.</i>', 'copy-delete-posts'); ?></div>
              <div class="cdp-p-25-40 cdp-f-s-18 cdp-f-w-light">
                <?php
                $isSaved = false;

                if (isset($globals)) $isSaved = true;
                foreach ($roles as $role => $value) {
                  $checked = '';
                  $rn = sanitize_text_field($role);
                  $role = sanitize_text_field($value['name']);
                  $d = ($role == 'Administrator')?' disabled checked="checked"':'';

                  if ($isSaved && $role != 'Administrator')
                    if (isset($globals['roles'][$rn]) && $globals['roles'][$rn] == 'true')
                      $checked = ' checked';

                  echo('<label for="cdp-roles-'.$rn.'"><input class="cdp-other-roles" id="cdp-roles-'.$rn.'"'.$checked.' type="checkbox"'.$d.' name="'.$rn.'">'.$role.'</label>');
                }
                ?>
              </div>
                <div class=""><h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('Content types which can be copied', 'copy-delete-posts'); ?></b></h2></div>
              <div class="cdp-p-25-40 cdp-f-s-18 cdp-f-w-light">
                  <label for="cdp-o-pages"><input <?php echo ($gos['cdp-content-pages'] == 'true')?'checked ':''; ?>id="cdp-o-pages" type="checkbox" class="cdp-other-inputs" name="cdp-content-pages"><?php _e('Pages', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-posts"><input <?php echo ($gos['cdp-content-posts'] == 'true')?'checked ':''; ?>id="cdp-o-posts" type="checkbox" class="cdp-other-inputs" name="cdp-content-posts"><?php _e('Posts', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-custom"><input <?php echo ($gos['cdp-content-custom'] == 'true')?'checked ':''; ?>id="cdp-o-custom" type="checkbox" class="cdp-other-inputs" name="cdp-content-custom"><?php _e('Custom posts types', 'copy-delete-posts'); ?></label>
              </div>
                <div class=""><h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('Display copy option on...', 'copy-delete-posts'); ?></b></h2></div>
              <div class="cdp-p-25-40 cdp-f-s-18 cdp-f-w-light">
                  <label for="cdp-o-postspages"><input <?php echo ($gos['cdp-display-posts'] == 'true')?'checked ':''; ?>id="cdp-o-postspages" type="checkbox" class="cdp-other-inputs" name="cdp-display-posts"><?php _e('Posts/pages lists', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-edit"><input <?php echo ($gos['cdp-display-edit'] == 'true')?'checked ':''; ?>id="cdp-o-edit" type="checkbox" class="cdp-other-inputs" name="cdp-display-edit"><?php _e('Edit screens', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-admin"><input <?php echo ($gos['cdp-display-admin'] == 'true')?'checked ':''; ?>id="cdp-o-admin" type="checkbox" class="cdp-other-inputs" name="cdp-display-admin"><?php _e('Admin bar', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-bulk"><input <?php echo ($gos['cdp-display-bulk'] == 'true')?'checked ':''; ?>id="cdp-o-bulk" type="checkbox" class="cdp-other-inputs" name="cdp-display-bulk"><?php _e('Bulk actions menu', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-gutenberg"><input <?php echo ($gos['cdp-display-gutenberg'] == 'true')?'checked ':''; ?>id="cdp-o-gutenberg" type="checkbox" class="cdp-other-inputs" name="cdp-display-gutenberg"><?php _e('Gutenberg editor', 'copy-delete-posts'); ?></label>
              </div>
                <div class=""><h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('Show reference to original item?', 'copy-delete-posts'); ?></b></h2></div>
                <div class="cdp-f-s-18 cdp-f-w-light cdp-p-15-25"><?php _e('If checked, you will see a reference to the original post/page (on the copied version).', 'copy-delete-posts'); ?></div>
              <div class="cdp-p-25-40 cdp-f-s-18 cdp-f-w-light">
                  <label for="cdp-o-posts2"><input <?php echo ($gos['cdp-references-post'] == 'true')?'checked ':''; ?>id="cdp-o-posts2" type="checkbox" class="cdp-other-inputs" name="cdp-references-post"><?php _e('Posts/pages lists', 'copy-delete-posts'); ?></label>
                  <label for="cdp-o-edits2"><input <?php echo ($gos['cdp-references-edit'] == 'true')?'checked ':''; ?>id="cdp-o-edits2" type="checkbox" class="cdp-other-inputs" name="cdp-references-edit"><?php _e('Edit screens', 'copy-delete-posts'); ?></label>
              </div>

              <?php
                $tifmdisabled = 'false';
                if (get_option('_tifm_feature_enabled') === 'disabled') {
                  $tifmdisabled = 'true';
                }

                $tifmscrollTo = false;
                if (isset($_GET['scrollToSection']) && $_GET['scrollToSection'] === 'testPlugins') {
                  $tifmscrollTo = true;
                }
              ?>

              <div id="tifmSectionInOptions">
                <div>
                  <h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('Test new plugins before installing:', 'copy-delete-posts'); ?></b></h2>
                </div>

                <div class="cdp-f-s-18 cdp-f-w-light cdp-p-15-25" style="line-height: 25px;">
                  <?php _e('If this feature is activated, you’ll see “Try it out”-buttons on the screen where you can', 'copy-delete-posts'); ?> <a href="<?php echo admin_url('plugin-install.php') ?>"><?php _e('add new plugins', 'copy-delete-posts'); ?></a>.<br />
                  <?php
                  $tifm_translated = __('Clicking on it will spin up a new WordPress instance with the respective plugin installed. Powered by %s.', 'copy-delete-posts');
                  echo str_replace('%s', '<a href="https://tastewp.com" target="_blank">TasteWP</a>', $tifm_translated);
                  ?>
                </div>

                <div class="cdp-f-s-18 cdp-f-w-light" style="padding-top: 10px; padding-bottom: 10px;">
                  <label for="cdp-tifm-enabled">
                    <input <?php echo ($tifmdisabled === 'false')?'checked ':''; ?>id="cdp-tifm-enabled" type="checkbox" class="cdp-other-inputs" name="cdp-tifm-enabled">
                    <?php _e('Enable "Try it out" buttons in add new plugin screen.', 'copy-delete-posts'); ?>
                  </label>
                </div>
              </div>

              <div><h2><b class="cdp-f-s-18 cdp-f-w-bold"><?php _e('Additional features', 'copy-delete-posts'); ?></b></h2></div>
              <div class="cdp-p-25-40 cdp-f-s-18 cdp-f-w-light">
                <label for="cdp-o-premium-hide-tooltip">
                  <?php if (!isset($gos['cdp-premium-hide-tooltip'])) $gos['cdp-premium-hide-tooltip'] = false; ?>
                  <input id="cdp-o-premium-hide-tooltip"<?php echo ((!$areWePro)?' disabled="true"':''); ?> <?php echo ($areWePro && $gos['cdp-premium-hide-tooltip'] == 'true')?'checked ':''; ?> type="checkbox" class="cdp-other-inputs" name="cdp-premium-hide-tooltip" />
                  <span class="cdp-relative cdp-tooltip-premium" data-top="5"><?php _e('Hide copy tooltip on hover and only show the button', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon" style="right: -30px"></span></span>
                </label>
                <?php if (is_multisite() || !$areWePro) { ?>
                <label for="cdp-o-premium-replace-domain">
                  <?php if (!isset($gos['cdp-premium-replace-domain'])) $gos['cdp-premium-replace-domain'] = false; ?>
                  <input id="cdp-o-premium-replace-domain"<?php echo ((!$areWePro)?' disabled="true"':''); ?> <?php echo ($areWePro && $gos['cdp-premium-replace-domain'] == 'true')?'checked ':''; ?> type="checkbox" class="cdp-other-inputs" name="cdp-premium-replace-domain" />
                  <span class="cdp-relative cdp-tooltip-premium" data-top="5"><?php _e('Adjust URLs in post content for new subsite', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon" style="right: -30px"></span></span>
                </label>
                <?php } ?>
                <label for="cdp-o-premium-import">
                  <?php if (!isset($gos['cdp-premium-import'])) $gos['cdp-premium-import'] = false; ?>
                  <input id="cdp-o-premium-import"<?php echo ((!$areWePro)?' disabled="true"':''); ?> <?php echo ($areWePro && $gos['cdp-premium-import'] == 'true')?'checked ':''; ?> type="checkbox" class="cdp-other-inputs" name="cdp-premium-import" />
                  <span class="cdp-relative cdp-tooltip-premium" data-top="5"><?php _e('Show post export & import buttons', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon" style="right: -30px"></span></span>
                </label>
                <label for="cdp-o-menu-in-settings">
                  <input <?php echo (isset($gos['cdp-menu-in-settings']) && $gos['cdp-menu-in-settings'] == 'true')?'checked ':''; ?>id="cdp-o-menu-in-settings" type="checkbox" class="cdp-other-inputs" name="cdp-menu-in-settings">
                  <?php _e('Move <b>Copy & Delete Posts</b> <u>menu item</u> under <b>Tools</b> dropdown', 'copy-delete-posts'); ?>
                </label>
              </div>
              <div class="cdp-center cdp-padding-15-h">
                  <button class="cdp-button cdp-save-options"><?php _e('Save', 'copy-delete-posts'); ?></button>
                <div class="cdp-padding cdp-f-s-17">
                    <a href="#" class="cdp-close-chapter"><?php _e('Close section', 'copy-delete-posts'); ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="cdp-f-s-20 cdp-p-hh">
          <?php _e('...and after your copy frenzy, you may need to…', 'copy-delete-posts'); ?>
        </div>

        <!-- DELETE SECTION -->
        <div class="cdp-collapsible" data-cdp-group="mains">
          <div class="cdp-d-xclicked cdp-collapsible-title">
            <div class="cdp-cf">
                <div class="cdp-left cdp-ff-b1"><b class="cdp-ff-b4"><?php _e('Delete duplicate posts/pages', 'copy-delete-posts'); ?></b></div>
              <div class="cdp-right"><i class="cdp-arrow cdp-arrow-left"></i></div>
            </div>
          </div>
          <div class="cdp-collapsible-content cdp-d-section cdp-np">

            <div class="cdp-padding">
              <div class="cdp-backup-alert cdp-f-s-20 cdp-f-w-light">
                <?php _e('Before you delete anything here (which cannot be undone!) we <b class="cdp-f-w-bold">strongly suggest</b><br />
                that you create a backup, for example with <a href="https://wordpress.org/plugins/backup-backup/" target="_blank">this plugin</a>', 'copy-delete-posts'); ?>
              </div>
              <div class="cdp-cf cdp-tab-list">
                <div class="cdp-left cdp-tab-element cdp-tab-active" data-box="cdp-tabox-manual">
                    <span><?php _e('Manual Cleanup', 'copy-delete-posts'); ?></span>
                </div>
                <div class="cdp-left cdp-tab-element cdp-tooltip-premium" data-top="-4" data-box="cdp-tabox-automatic"<?php echo ((!$areWePro)?' data-disabled="true"':''); ?>>
                    <span class="cdp-relative"><?php _e('Automatic Cleanup', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon"></span></span>
                </div>
                <div class="cdp-left cdp-tab-element" data-box="cdp-tabox-redirects">
                    <span class="cdp-relative"><?php _e('Redirection', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon"></span></span>
                </div>
              </div>
              <div class="cdp-cont-d-box-tabed" id="cdp-tabox-redirects">
                <?php if ($areWePro && function_exists('cdpp_automated_redirects')) { ?>
                <?php cdpp_automated_redirects($cdp_plug_url); ?>
                <?php } else { ?>
                <div class="cdp-con-cen">
                  <div class="cdp-center cdp-padding" style="padding-top: 50px; padding-bottom: 30px;">
                    <img src="<?php echo $cdp_plug_url; ?>/assets/imgs/redirections.png" alt="">
                  </div>
                  <div class="cdp-lh-24 cdp-black-all" style="max-width: 82%; margin: 0 auto;">
                    <div class="cdp-f-s-19 cdp-f-w-regular cdp-padding">
                      <?php _e('As part of the <span class="cdp-green">premium plugin</span> you can enable redirects, so that the URLs of your deleted posts/pages automatically take visitors to the version which you decided to keep.', 'copy-delete-posts'); ?>
                    </div>
                    <div class="cdp-f-s-19 cdp-f-w-regular cdp-padding">
                      <?php _e('This isn’t just good for your visitors, but also for SEO: the “link juice” from your old (deleted) articles will be forwarded to the versions you keep, helping you to rank higher in search engines.', 'copy-delete-posts'); ?>
                    </div>
                    <div class="cdp-f-s-19 cdp-f-w-regular cdp-padding">
                      <?php _e('And: you can also use this feature for any other redirections you may need
                      (not only redirects from deleted posts/pages)!', 'copy-delete-posts'); ?>
                    </div>
                  </div>
                  <br />
                  <div class="cdp-center cdp-padding-15-h" style="padding-bottom: 60px;">
                    <a href="https://sellcodes.com/CylMIdJD" target="_blank">
                        <button class="cdp-button cdp-f-s-21 cdp-f-w-medium" style="width: 465px; height: 70px; border-radius: 35px;"><?php _e('Go premium now', 'copy-delete-posts'); ?></button>
                    </a>
                  </div>
                </div>
                <?php } ?>
              </div>
              <div class="cdp-cont-d-box-tabed" id="cdp-tabox-automatic">
                <?php if ($areWePro && function_exists('cdpp_automated_deletion')) { ?>
                <?php cdpp_automated_deletion($cdp_plug_url); ?>
                <?php } ?>
              </div>
              <div class="cdp-cont-d-box-tabed" id="cdp-tabox-manual">

                <!-- ABOVE DELETION TABLE -->
                <div class="cdp-d-pad-sp" style="padding-top: 20px">
                  <div class="cdp-special-cb-p">
                    <div class="cdp-d-header cdp-f-s-19">
                      <?php _e('Scan for duplicates in...', 'copy-delete-posts'); ?>
                    </div>
                    <div class="cdp-p-25-40 cdp-f-s-18">
                        <label><input type="checkbox" name="cdp-d-a-posts" checked class="cdp-d-option cdp-d-first-chapter-cb" /><?php _e('Posts', 'copy-delete-posts'); ?></label>
                        <label><input type="checkbox" name="cdp-d-a-pages" checked class="cdp-d-option cdp-d-first-chapter-cb" /><?php _e('Pages', 'copy-delete-posts'); ?></label>
                        <label><input type="checkbox" name="cdp-d-a-customs" checked class="cdp-d-option cdp-d-first-chapter-cb" /><?php _e('Custom posts', 'copy-delete-posts'); ?></label>
                    </div>
                  </div>
                  <div class="cdp-special-cb-p">
                    <div class="cdp-d-header cdp-f-s-19">
                      <?php _e('Count them as duplicates if they are identical with respect to <u class="cdp-f-w-bold">all</u> of the below...', 'copy-delete-posts'); ?>
                    </div>
                    <div>
                      <div class="cdp-p-25-t cdp-cf">
                        <div class="cdp-left cdp-f-s-18">
                            <label style="margin-right: 5px;"><input type="checkbox" checked name="cdp-d-b-title" class="cdp-d-option"/><?php _e('Title', 'copy-delete-posts'); ?></label>
                        </div>
                        <div class="cdp-left" style="margin-top: 1px; margin-left: 5px; font-size: 13px;">
                            <a href="#" class="cdp-show-more-d-title cdp-f-s-16" style="line-height: 43px;"><?php _e('(show more options)', 'copy-delete-posts'); ?></a>
                        </div>
                        <div class="cdp-left cdp-f-s-18" style="margin-left: 50px;">
                            <label><input type="checkbox" name="cdp-d-b-slug" class="cdp-d-option" /><?php _e('Similar slug', 'copy-delete-posts'); ?> <span class="cdp-tooltip-top" title="<?php _e('Slugs are never 100% identical (i.e. Wordpress adds a counter automatically to ensure they are unique). The rule to only have them at least 85% identical does the job fine (you can see after the scan which posts are considered identical).', 'copy-delete-posts'); ?>">(x ≥ 85%)</span></label>
                        </div>
                      </div>
                      <div class="cdp-p-20-h cdp-more-d-title" style="display: none; padding-left: 37px;">
                        <div class="cdp-f-s-17 cdp-p-20-b cdp-lh-24">
                          <?php _e('Do you want to consider different titles still to be identical if a) the copied posts/pages<br />were created by this plugin and b) they were not modified thereafter?', 'copy-delete-posts'); ?>
                        </div>
                        <div class="cdp-cf">
                            <label class="cdp-left cdp-f-s-18"><input type="radio" class="cdp-d-option cdp-radio" value="0" name="cdp-radio-btn-dtitles" checked><?php _e('No', 'copy-delete-posts'); ?></label>
                            <label class="cdp-left cdp-f-s-18"><input type="radio" class="cdp-d-option cdp-radio" value="1" name="cdp-radio-btn-dtitles"><?php _e('Yes', 'copy-delete-posts'); ?></label>
                            <span class="cdp-green cdp-f-s-17 cdp-tooltip-top cdp-left" title="<?php _e('The copies you created may have been given different titles automatically (according to the rules in <a href="#" class="cdp-go-to-names-chapter">this section</a>) and therefore would not count as duplicates as they have different titles.<br /><br />To remedy this, you can select “Yes” here so that those posts/pages also get considered as duplicates.', 'copy-delete-posts'); ?>" style="line-height: 44px;"><?php _e('When does “yes” make sense here?', 'copy-delete-posts'); ?></span>
                        </div>
                      </div>
                      <!-- <div class="cdp-padding-15-h cdp-f-s-18">
                      </div> -->
                      <div class="cdp-cf cdp-p-40-b">
                        <label class="cdp-left cdp-f-s-18" style="margin-right: 21px;">
                            <input type="checkbox" name="cdp-d-c-excerpt" class="cdp-d-option" /><?php _e('Excerpt (<span class="cdp-no-empty-text"><b>including</b> empty</span>)', 'copy-delete-posts'); ?>
                        </label>
                          <label class="cdp-left cdp-f-s-18" style="margin-right: 90px !important;"><input type="checkbox" name="cdp-d-c-count" class="cdp-d-option"/><?php _e('Word count', 'copy-delete-posts'); ?></label>
                        <div class="cdp-left cdp-f-s-17" style="line-height: 43px;">
                          <?php _e('...need others? <a href="mailto:hi@copy-delete-posts.com" target="_blank">Suggest them!</a>', 'copy-delete-posts'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="cdp-d-option-select-parent cdp-padding-15-h cdp-center cdp-ntp">
                      <div class="cdp-inline cdp-cf">
                        <select class="cdp-left cdp-d-option-select cdp-pad-49-list cdp-select-large cdp-max-600 cdp-select cdp-select-centered cdp-sel-separat" name="cdp-d-sels-diftyp">
                            <option value="1"><?php _e('Only count pages/posts of the same type as duplicates', 'copy-delete-posts'); ?></option>
                            <option value="2"><?php _e('Also count pages/posts of different types as duplicates', 'copy-delete-posts'); ?></option>
                        </select>
                        <span class="cdp-left cdp-green">
                          <div style="margin-left: 15px; line-height: 51px;">
                              <span class="cdp-tooltip-top" title='Select the “same type”-option if the pages/posts have to be of the same type (i.e. post / page / specific custom post category) in order to count as duplicates. If you select “cross-type” then pages/posts of different types will also be considered as duplicates.'><?php _e('Huh?', 'copy-delete-posts'); ?></span>
                          </div>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="">
                    <div class="cdp-d-header cdp-f-s-19">
                      <?php _e('Which version do you want to keep?', 'copy-delete-posts'); ?>
                    </div>
                    <div class="cdp-p-30-h cdp-center">
                      <select class="cdp-d-option-select cdp-pad-49-list cdp-select-large cdp-select cdp-select-centered cdp-sel-separat" name="cdp-d-d-sel-which">
                          <option value="1"><?php _e('Keep the oldest duplicate (typically the original)', 'copy-delete-posts'); ?></option>
                          <option value="2"><?php _e('Keep the newest duplicate (typically the last copy you made)', 'copy-delete-posts'); ?></option>
                          <option value="3"><?php _e('Delete ALL duplicates, don’t keep any (Be careful!)', 'copy-delete-posts'); ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="cdp-relative cdp-f-s-19">
                    <span class="cdp-tooltip-premium" data-top="0">
                      <?php _e('<b>Filter results (optional)</b>: Only list them, if they<span id="cdpp-switch-mf"> […]</span>', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon"></span>
                    </span>
                  </div>
                  <?php if ($areWePro && function_exists('cdpp_more_filters')) cdpp_more_filters(); ?>
                  <br />
                  <div class="cdp-center cdp-p-30-h">
                      <button class="cdp-button cdp-delete-btn cdp-d-search cdp-rl-round cdp-f-w-bold" type="button" name="button"><?php _e('Scan for duplicates now!<br /><small class="cdp-sm-d cdp-f-s-17 cdp-f-w-medium">(at this point nothing gets deleted)</small>', 'copy-delete-posts'); ?></button>
                  </div>
                </div>

                <div class="cdp-padding-15-h">
                  <div class="cdp-cf cdp-d-pad-sp cdp-not-yet-search" style="display: none; padding-bottom: 30px;">
                    <div class="cdp-left cdp-f-s-19 cdp-f-w-bold" style="line-height: 41px;">
                      <?php _e('Scan has found [<span id="cdp-d-table-pagi-ilosc-wynikow" class="cdp-f-w-bold"></span>] duplicates', 'copy-delete-posts'); ?>
                    </div>
                    <div class="cdp-right">
                      <input type="text" class="cdp-d-searchbox-c" name="cdp-d-searchbox" placeholder="Search...">
                    </div>
                    <div class="cdp-right cdp-f-s-19" style="padding-right: 45px;">
                      <div class="cdp-cf" style="line-height: 41px;">
                        <div class="cdp-left">
                          <?php _e('Show', 'copy-delete-posts'); ?>
                        </div>
                        <select class="cdp-left cdp-select cdp-ow-border cdp-per-page-select-show cdp-width-166">
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option value="25" selected>25</option>
                          <option value="40">40</option>
                          <option value="50">50</option>
                          <option value="60">60</option>
                          <option value="75">75</option>
                          <option value="100">100</option>
                        </select>
                        <div class="cdp-left">
                            <?php _e('per page', 'copy-delete-posts'); ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- DELETION TABLE -->
                  <div class="cdp-table-cont cdp-not-yet-search" style="display: none;">
                    <table id="cdp-d-table" class="cdp-delete-table">
                      <thead>
                        <tr class="cdp-f-s-19 cdp-f-w-medium">
                          <th><label><input type="checkbox" class="cdp-d-checkbox-all"/></label></th>
                          <th><?php _e('Title', 'copy-delete-posts'); ?></th>
                          <th><?php _e('Slug/URL', 'copy-delete-posts'); ?></th>
                          <th><?php _e('Type', 'copy-delete-posts'); ?></th>
                          <th><?php _e('Date created', 'copy-delete-posts'); ?></th>
                          <th><?php _e('# of words', 'copy-delete-posts'); ?></th>
                        </tr>
                      </thead>
                      <thead>
                        <tr data-ignore="true"><td class="cdp-h-tbe" colspan="6"></td></tr>
                      </thead>
                      <tbody id="cdp-d-table-tbody"></tbody>
                      <tfoot>
                        <tr data-ignore="true"><td class="cdp-h-tbe" colspan="6"></td></tr>
                      </tfoot>
                    </table>
                  </div>

                  <!-- BELOW DELETION TABLE -->
                  <div class="cdp-d-pad-sp">
                    <div class="cdp-cf cdp-not-yet-search" style="display: none;">
                      <div class="cdp-d-sel-all-con cdp-left cdp-f-w-light cdp-f-s-17">
                        <?php _e('<u class="cdp-d-select-all cdp-f-w-light">Select all</u> (also from other pages)', 'copy-delete-posts'); ?>
                      </div>
                      <div class="cdp-center cdp-d-pagi-cent cdp-left">
                        <div id="cdp-d-table-pagi" class="cdp-pagination"></div>
                      </div>
                    </div>
                    <div class="cdp-center cdp-p-10-h cdp-not-yet-search" style="display: none;">
                      <div class="cdp-delete-info cdp-f-w-light cdp-f-s-19">
                        <?php _e('You selected <b class="cdp-t-d-ct cdp-f-w-light">0</b> pages/posts to be deleted', 'copy-delete-posts'); ?>
                      </div>
                    </div>
                    <div class="cdp-p-10-h cdp-not-yet-search" style="display: none;">
                      <div class="cdp-d-header-2 cdp-f-s-19 cdp-f-w-light">
                        <?php _e('Steps to deletion:', 'copy-delete-posts'); ?>
                      </div>
                      <div class="cdp-margin-left cdp-f-s-19">
                        <div class="cdp-p-10-h">
                          <div class="cdp-cf cdp-low-margin-bot" style="line-height: 28px;">
                            <div class="cdp-left cdp-blue-circle">1</div>
                            <div class="cdp-left">&nbsp;<?php _e('Make sure you created a backup with, e.g. with <a href="https://wordpress.org/plugins/backup-backup/" target="_blank">this plugin</a>.', 'copy-delete-posts'); ?></div>
                          </div>
                          <div class="cdp-cf cdp-low-margin-bot" style="line-height: 28px;">
                            <div class="cdp-left cdp-blue-circle">2</div>
                            <div class="cdp-left">&nbsp;<?php _e('Select all the posts & pages which should be deleted (by ticking the checkboxes in the table above).', 'copy-delete-posts'); ?></div>
                          </div>
                          <div class="cdp-cf cdp-low-margin-bot" style="line-height: 28px;">
                            <div class="cdp-left cdp-blue-circle">3</div>
                            <div class="cdp-left">&nbsp;<?php _e('Check if you need these features (optional):', 'copy-delete-posts'); ?></div>
                          </div>
                        </div>
                        <div class="cdp-margin-left-25 cdp-p-20-h cdp-nbp" style="padding-top: 0px">
                          <table>
                            <tbody>
                              <tr>
                                  <td class="cdp-vtop-pad"><?php _e('Automatic redirection', 'copy-delete-posts'); ?></td>
                                <td>
                                  <div class="cdp-relative">
                                    <span class="cdp-tooltip-premium" style="padding: 25px 0;">
                                      <select class="cdp-p-redirections cdp-select cdp-ow-border cdp-dis-en-opt" name="cdp-redirections">
                                          <option value="0"><?php _e('Disabled', 'copy-delete-posts'); ?></option>
                                          <option value="1"><?php _e('Enabled', 'copy-delete-posts'); ?></option>
                                      </select>
                                    </span>
                                    <div class="cdp-premium-icon cdp-big-icon" style="margin-left: 17px;"></div>
                                  </div>
                                    <div class="cdp-d-tp-pad cdp-f-s-17 cdp-lh-24"><?php _e('Enable this if you want to redirect the urls from your deleted posts/pages to the main one you decided to keep.', 'copy-delete-posts'); ?></div>
                                </td>
                              </tr>
                              <tr>
                                  <td class="cdp-vtop-pad"><?php _e('Deletion throttling', 'copy-delete-posts'); ?></td>
                                <td>
                                  <div class="cdp-cf">
                                    <select class="cdp-left cdp-d-throttling cdp-select cdp-ow-border cdp-dis-en-opt" name="cdp-throttling">
                                        <option value="0"><?php _e('Disabled', 'copy-delete-posts'); ?></option>
                                        <option value="1"><?php _e('Enabled', 'copy-delete-posts'); ?></option>
                                    </select>
                                    <div class="cdp-left cdp-inline cdp-cf cdp-d-throttling-count-p" style="display: none; line-height: 41px;">
                                      <div class="cdp-left">
                                          <span style="padding: 0px 15px;"><?php _e('Delete', 'copy-delete-posts'); ?></span>
                                      </div>
                                      <div class="cdp-left">
                                        <input type="number" class="cdp-d-throttling-count cdp-number-field-styled" name="cdp-throttling-count" min="1" max="10240" placeholder="50">
                                      </div>
                                      <div class="cdp-left">
                                          <span style="padding: 0px 15px;"><?php _e('per minute', 'copy-delete-posts'); ?></span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="cdp-d-tp-pad cdp-f-s-17 cdp-lh-24">
                                    <?php _e('Enable this if you want to have your posts/pages getting deleted in batches (instead of all at once).<br />This reduces the risk of timeouts if you have a lot to delete.<br />', 'copy-delete-posts'); ?>
                                    <span class="cdp-even-more-th-info" style="display: none">
                                      <?php _e('If it’s necessary the process will dynamically slow down - depending on your server’s resource consumption. For example, if you’re using another plugin which is running a background process and it takes a lot of resources (+50%), our plugin will wait/slow down until the other process is complete.', 'copy-delete-posts'); ?>
                                    </span>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                  <td class="cdp-vtop-pad" style="padding-top: 4px"><?php _e('Move post(s) to trash?', 'copy-delete-posts'); ?></td>
                                <td>
                                  <div class="">
                                    <span class="cdp-tooltip-premium" style="padding: 25px 0">
                                        <label class="cdp-relative" style="padding-right: 25px;"><input type="checkbox" class="cdp-p-just-trash-them" /> <?php _e('Yes, keep deleted posts in trash!', 'copy-delete-posts'); ?> <span class="cdp-premium-icon cdp-big-icon"></span></label>
                                    </span>
                                      <div class="cdp-d-tp-pad cdp-f-s-17 cdp-lh-24"><?php _e('Select this option to move deleted posts to trash (instead of deleting them permanently right away).', 'copy-delete-posts'); ?></div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="">
                      <div class="cdp-not-yet-search" style="display: none;">
                        <hr class="cdp-hr">
                        <div class="cdp-center cdp-padding-15-h cdp-f-s-19">
                            <label><input type="checkbox" class="cdp-d-just-check-it"> <?php _e('I completed <u>all</u> steps, it’s ok!', 'copy-delete-posts'); ?></label>
                        </div>
                        <div class="cdp-center cdp-p-10-h">
                            <button type="button" class="cdp-button cdp-red-bg cdp-d-real-delete cdp-f-s-19" name="button"><?php _e('Delete selected pages/posts!', 'copy-delete-posts'); ?></button>
                        </div>
                        <div class="cdp-center cdp-padding-15-h cdp-f-s-19">
                          <?php _e('You will be notified when the deletion process ends via <span class="cdp-green">Admin Bar Menu</span>.', 'copy-delete-posts'); ?>
                        </div>
                      </div>
                      <div class="cdp-padding cdp-f-s-17 cdp-center">
                          <a href="#" class="cdp-close-chapter"><?php _e('Close section', 'copy-delete-posts'); ?></a>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>

        <?php if (function_exists('cdpp_license_status')) cdpp_license_status(); ?>
        <?php if (function_exists('cdpp_license')) cdpp_license(); ?>
      </div>

      <div class="cdp-f-s-20 cdp-p-hh cdp-center cdp-relative">
        <?php _e('<b>Questions?</b> We\'re happy to help in the <a href="https://wordpress.org/support/plugin/copy-delete-posts/#new-topic-0" target="_blank" style="text-decoration: none;">Support Forum</a>.', 'copy-delete-posts'); ?> <span class="cdp-info-icon cdp-tooltip-top" title="<?php _e('Your account on Wordpress.org (where you open a new support thread) is different to the one you login to your WordPress dashboard (where you are now). If you don\'t have a WordPress.org account yet, please sign up at the top right on here. It only takes a minute :) Thank you!', 'copy-delete-posts'); ?>"></span>
      </div>

      <jdiv class="label_e50 _bottom_ea7 notranslate" id="cdp_jvlabelWrap-fake" style="background: linear-gradient(95deg, rgb(47, 50, 74) 20%, rgb(66, 72, 103) 80%);right: 30px;bottom: 0px;width: 310px;">
    		<jdiv class="hoverl_bc6"></jdiv>
    		<jdiv class="text_468 _noAd_b4d contentTransitionWrap_c73" style="font-size: 15px;font-family: Arial, Arial;font-style: normal;color: rgb(240, 241, 241);position: absolute;top: 8px;line-height: 13px;">
    			<span><?php _e('Connect with support (click to load)', 'copy-delete-posts'); ?></span><br>
    			<span style="color: #eee;font-size: 10px;">
    				<?php _e('This will establish connection to the chat servers', 'copy-delete-posts'); ?>
    			</span>
    		</jdiv>
    		<jdiv class="leafCont_180">
    			<jdiv class="leaf_2cc _bottom_afb">
    				<jdiv class="cssLeaf_464"></jdiv>
    			</jdiv>
    		</jdiv>
    	</jdiv>

    </div>
  </div>

  <div id="cdp_carrousel" style="display: none; margin-top: 100px;">
    <?php do_action('ins_global_print_carrousel'); ?>
  </div>

  <?php if ($tifmscrollTo === true) { ?>
    <script type="text/javascript">
      setTimeout(function () {
        if (document.querySelector('#cdp-global-section-collapser')) {
          document.querySelector('#cdp-global-section-collapser').click();
          setTimeout(function () {
            document.querySelector('#tifmSectionInOptions').scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
            jQuery('#tifmSectionInOptions').css({'transition': '.3s all'});
            setTimeout(function () {
              jQuery('#tifmSectionInOptions').css({'transform': 'scale(1.05)'});
              setTimeout(function () {
                jQuery('#tifmSectionInOptions').css({'transform': 'scale(1)'});
              }, 300);
            }, 100);
          }, 450);
        }
      }, 300)
    </script>
  <?php } ?>

  <?php
}
/** –– **/

/** –– **\
 * This function will convert bytes to human readable
 * @return void
 */
function cdp_human_readable_bytes($bytes) {
  $label = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);

  return (round($bytes, 2) . " " . $label[$i]);
}

/** –– * */
