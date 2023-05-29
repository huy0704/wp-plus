<?php

/**
 * Class BoosterView
 */
class BoosterView {

  public function __construct() {
    wp_enqueue_style(TenWebBooster::PREFIX);
    wp_enqueue_script(TenWebBooster::PREFIX . '-script');
  }

  /**
   * Display page.
   *
   * @param $params
  */
  public function display( $params = array() ) {
    ?>
    <div class="wrap">
      <?php //echo $this->popup(); ?>
      <?php
      if ( !$params['twb_dismiss'] ) {
        ?>
        <div class="twb-speed-header">
          <?php
          $this->header( $params );
          ?>
        </div>
        <?php
        if ( $params['submenu_section_optimize_images'] || $params['submenu_section_analyze'] ) {
          ?>
        <div class="twb-speed-body">
          <div class="twb-speed-body-container">
            <?php
            if ( $params['submenu_section_optimize_images'] ) {
              if ( $params['booster_is_connected'] && !$params['tenweb_is_paid'] ) {
                $this->optimizer_on_free_connected($params);
              }
              elseif ( $params['booster_is_connected'] && $params['tenweb_is_paid'] ) {
                $this->optimizer_on_pro($params);
              }
              else {
                $this->optimizer_on_free_not_connected($params);
              }
            }
            if ( $params['submenu_section_analyze'] ) {
              $this->analyzer($params);
            }
            ?>
          </div>
        </div>
          <?php
        }
      }
      // If CTAs disable option is enabled.
      if ( $params['show_cta_option'] && $params['booster_plugin_status'] != 2 ) {
        ?>
        <div class="twb-speed-footer">
          <input type="checkbox" id="twb-show-cta" <?php echo $params['show_cta'] == 1 ? 'checked' : ''; ?>>
          <label for="twb-show-cta"><p><?php _e("Show all PageSpeeds score elements in WordPress admin.", "tenweb-booster"); ?></p></label>
        </div>
        <?php
      }

      ?>
    </div>
  <?php
  }

  public function analyzer( $params ) {
    ?>
    <div class="twb-analyze">
      <p class="twb-section-title"><?php esc_html_e('PageSpeed optimization', 'tenweb-booster'); ?></p>
      <p class="twb-description"><?php echo $params['section_analyze_desc']; ?></p>
      <div class="twb-analyze-input-container">
        <input type="url" class="twb-analyze-input <?php esc_attr_e( ( $params['page_is_public'] === 0 ) ? 'twb-analyze-input-error' : ''); ?>" placeholder="<?php esc_html_e('Page URL', 'tenweb-booster') ?>" value="" />
        <?php if ( $params['page_is_public'] === 0 ) { ?>
          <p class="twb-error-msg"><?php esc_html_e('This page is not public. Please publish the page to check the score.', 'tenweb-booster'); ?></p>
        <?php } ?>
        <a class="twb-analyze-input-button <?php esc_attr_e( ( !$params['page_is_public'] ) ? 'twb-disable-analyze' : ''); ?>"><?php esc_html_e('Analyze', 'tenweb-booster') ?></a>
      </div>
      <div class="twb-analyze-info-container">
        <div class="twb-analyze-info-left">
          <div class="twb-analyze-info-left-cont">
            <div class="twb-analyze-mobile-score">
              <div class="speed_circle" data-thickness="6" data-id="mobile">
                <p class="circle_animated"><?php echo esc_html($params['twb_speed_score']['mobile_score']); ?></p>
              </div>
              <p class="twb-score-name"><?php esc_html_e('Mobile Score',  'tenweb-booster'); ?></p>
              <p class="twb-load-time twb-load-time-mobile"><?php esc_html_e('Load Time:', 'tenweb-booster'); ?> <span><?php echo esc_html($params['twb_speed_score']['mobile_loading_time']); ?></span></p>
            </div>
            <div class="twb-analyze-desktop-score">
              <div class="speed_circle" data-thickness="6" data-id="desktop">
                <p class="circle_animated"><?php echo esc_html($params['twb_speed_score']['desktop_score']); ?></p>
              </div>
              <p class="twb-score-name"><?php esc_html_e('Desktop Score',  'tenweb-booster'); ?></p>
              <p class="twb-load-time twb-load-time-desktop"><?php esc_html_e('Load Time:', 'tenweb-booster'); ?> <span><?php echo esc_html($params['twb_speed_score']['desktop_loading_time']); ?></span></p>
            </div>
          </div>
          <div class="twb-analyze-score-info">
            <span><?php esc_html_e('Scale:', 'tenweb-booster') ?></span>
            <span class="twb-fast-icon twb-score-icon"></span>90-100 <?php esc_html_e('(fast)', 'tenweb-booster'); ?>
            <span class="twb-averege-icon twb-score-icon"></span>50-89 <?php esc_html_e('(average)', 'tenweb-booster'); ?>
            <span class="twb-slow-icon twb-score-icon"></span>0-49 <?php esc_html_e('(slow)', 'tenweb-booster'); ?>
          </div>
        </div>
        <div class="twb-analyze-info-right">
          <p class="twb-analyze-info-right-sub-title"><?php echo sprintf(__('Check your score with %s', 'tenweb-booster'), '<a href="https://pagespeed.web.dev/" target="_blank">' . __('Google PageSpeed Insights',  'tenweb-booster') . '</a>'); ?></p>
          <hr>
          <h3><?php esc_html_e('Analyzed page:', 'tenweb-booster'); ?></h3>
          <p class="twb-last-analyzed-page" title="<?php echo esc_html($params['twb_speed_score']['url']); ?>"><?php echo esc_html($params['twb_speed_score']['url']); ?></p>
          <div class="twb-last-analyzed-date-container">
            <h3><?php esc_html_e('Last analyzed:', 'tenweb-booster'); ?></h3>
            <p class="twb-last-analyzed-date"><?php echo esc_html($params['twb_speed_score']['last_analyzed_time']); ?></p>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function header( $params ) {
    $status = $params['status'];
    if ( $status == 'not_installed' ) {
      $step = 1;
      $connected = FALSE;
      $cont_class = '';
      $title = $params['section_booster_title'];
      $description = $params['section_booster_desc'];
      ob_start();
      ?>
      <div class="twb-button-container-parent">
        <div class="button-container">
          <a class="twb-install-booster" onclick="twb_install_plugin( this )"><?php echo sprintf(__('%s 10Web Booster plugin', 'tenweb-booster'), ($params['booster_plugin_status'] == 0 ? __('Install', 'tenweb-booster') : __('Activate', 'tenweb-booster'))); ?></a>
          <p><?php esc_html_e('Installing from WordPress repository', 'tenweb-booster') ?></p>
        </div>
      </div>
      <?php
      $html = ob_get_clean();
    }
    elseif ( $status == 'sign_up' ) {
      $step = 2;
      $connected = FALSE;
      $cont_class = 'twb-sign_up-booster-container';
      $title = __('10Web Booster plugin is installed!', 'tenweb-booster');
      $description = $params['section_booster_desc'];
      ob_start();
      ?>
      <input type="email" class="twb-sign-up-input" placeholder="<?php esc_html_e('Email address', 'tenweb-booster'); ?>" />
      <div class="twb-sign-up-dashboard-button-container">
        <a class="twb-booster-button twb-sign-up-dashboard-button"
           data-parent_slug="<?php echo esc_attr($params['submenu_parent_slug']); ?>"
           data-slug="<?php echo esc_attr($params['slug']); ?>"
           data-is_plugin="<?php echo esc_attr($params['is_plugin']); ?>"
           onclick="twb_sign_up_dashboard( this )"><?php esc_html_e('Sign up', 'tenweb-booster'); ?></a>
        <div>
          <?php
          $terms_of_services = '<br /><a href="https://10web.io/terms-of-service/" target="_blank">' . __('Terms of Services', 'tenweb-booster') . '</a>';
          $privacy_policy = '<a href="https://10web.io/privacy-policy/" target="_blank">' . __('Privacy Policy', 'tenweb-booster') . '</a>';
          echo sprintf(__('By signing up, you agree to 10Web’s %s and %s', 'tenweb-booster'), $terms_of_services, $privacy_policy); ?>
        </div>
      </div>
      <?php
      $html = ob_get_clean();
    }
    elseif ( $status == 'connect' ) {
      $step = 0;
      $connected = FALSE;
      $cont_class = 'twb-connect-to-dashboard-container';
      $title = __('10Web Booster plugin is installed!', 'tenweb-booster');
      $description = __('Connect to 10Web dashboard to activate 10Web Booster on your website and start optimization process. Optimization will start automatically.', 'tenweb-booster');
      ob_start();
      ?>
      <div class="twb-sign-up-dashboard-button-container">
        <a class="twb-booster-button twb-connect-to-dashboard-button" onclick="twb_connect_to_dashboard( this )"><?php esc_html_e('Connect', 'tenweb-booster'); ?></a>
      </div>
      <?php
      $html = ob_get_clean();
    }
    elseif ( $status == 'connected' ) {
      $step = $params['tenweb_is_paid'] ? 0 : 2;
      $connected = TRUE;
      $cont_class = 'twb-connected-booster-container' . (!$params['tenweb_is_paid'] ? ' twb-is-free' : '');
      $title = __('10Web Booster is active', 'tenweb-booster');
      $description = __('Our plugin is now optimizing your website.', 'tenweb-booster');
      $description .= __('Manage optimization settings from the 10Web dashboard.', 'tenweb-booster');
      ob_start();
      ?>
      <div class="button-container">
        <a href="<?php echo esc_url($params['dashboard_booster_url']); ?>" target="_blank" class="twb-manage-booster">
          <?php esc_html_e('Manage', 'tenweb-booster'); ?>
        </a>
      </div>
      <?php
      $html = ob_get_clean();
    }
    ?>
    <div class="twb-page-header <?php echo esc_html($cont_class); ?>">
      <?php if ($connected) { ?>
      <div class="twb-connected-booster-done-cont">
        <?php esc_html_e('Site is connected', 'tenweb-booster'); ?>
      </div>
      <?php } ?>
      <?php if ($title) { ?>
      <p class="twb-section-title"><?php echo esc_html($title); ?></p>
      <?php } ?>
      <?php if ($description) { ?>
      <p class="twb-header-description"><?php echo esc_html($description); ?></p>
      <?php } ?>
      <?php if ($step) { ?>
      <ul class="twb-install-booster-steps">
        <li class="<?php echo esc_html($step > 1 ? 'twb_so_check_active' : ''); ?>">
          <?php esc_html_e('Install 10Web Booster', 'tenweb-booster') ?>
          <span><?php esc_html_e('Activate plugin on the website', 'tenweb-booster'); ?></span>
        </li>
        <li class="<?php echo esc_html($step > 2 ? 'twb_so_check_active' : ''); ?>">
          <?php esc_html_e('Sign up and connect', 'tenweb-booster'); ?>
          <span><?php esc_html_e('Start the optimization process', 'tenweb-booster') ?></span>
        </li>
        <li class="<?php echo esc_html($step > 3 ? 'twb_so_check_active' : ''); ?>">
          <?php echo esc_html($params['section_booster_success_title']); ?>
          <span><?php echo esc_html($params['section_booster_success_desc']); ?></span>
        </li>
      </ul>
      <?php } ?>
      <?php if ($html) {
        echo $html;
      } ?>
    </div>
    <?php
  }

  /**
   * Optimizer section view when booster connected and is pro.
   *
   * @param string $params
  */
  public function optimizer_on_pro( $params ) {
    ?>
    <div class="twb-analyze-img_optimizer-container twb-optimize_on twb-optimize_done">
      <div>
        <p class="twb-section-title"><?php esc_html_e('You’re all set!', 'tenweb-booster') ?></p>
        <p class="twb-header-description"><?php esc_html_e('All images in media library are optimized.', 'tenweb-booster') ?></p>
        <ul>
          <li><span></span><?php esc_html_e('Auto-optimize all uploaded images.', 'tenweb-booster') ?></li>
          <li><span></span><?php esc_html_e('Configure WebP format conversion', 'tenweb-booster') ?></li>
        </ul>
      </div>
      <div class="twb-optimize_on-button-cont">
        <a href="<?php echo esc_url($params['dashboard_booster_url']); ?>" target="_blank"
           class="twb-optimize-add-pages"><?php esc_html_e('Manage', 'tenweb-booster') ?></a>
      </div>
    </div>
    <?php
  }

  /**
   * Optimizer section view when booster connected and is free.
   *
   * @param string $params
  */
  public function optimizer_on_free_not_connected( $params ) {
    ?>
    <div class="twb-img_optimizer-container twb-img_optimizer-not-container">
      <div class="twb-img_optimizer-left">
        <span class="twb-not-optimized-info"><?php esc_html_e('Not Optimized', 'tenweb-booster') ?></span>
        <h5><?php echo sprintf(_n('%d <span>image</span>', '%d <span>images</span>', $params['images_count'], 'tenweb-booster'), $params['images_count']); ?></h5>
        <ul>
          <li><?php esc_html_e('Optimize all uploaded images', 'tenweb-booster'); ?></li>
          <li><?php esc_html_e('Serve Images in WebP format', 'tenweb-booster'); ?></li>
          <li><?php esc_html_e('Speed up website and reduce load time', 'tenweb-booster'); ?></li>
        </ul>
      </div>
      <div class="twb-img_optimizer-right">
        <div class="twb-img_optimizer-button-container">
          <div class="twb-img_optimizer-info">
            <p class="twb-section-description"><?php esc_html_e('Reduce image size by up to 40% without compromising the quality.', 'tenweb-booster'); ?></p>
            <p class="twb-total_size"><?php esc_html_e('Total size:', 'tenweb-booster'); ?></p>
            <p class="twb-total_size_value"><?php echo esc_html($params['images_total_size']); ?></p>
          </div>
          <a class="twb-img_optimize-now-button"><?php esc_html_e('Optimize Now', 'tenweb-booster'); ?></a>
        </div>
      </div>
    </div>
    <?php
  }

  /**
   * Optimizer section view when booster connected and is free.
   *
   * @param string $params
  */
  public function optimizer_on_free_connected( $params ) {
    $pages_compressed = $params['pages_compressed'];
    ?>
    <div class="twb-img_optimizer-container">
      <div class="twb-img_optimizer-left">
        <span class="twb-optimized-info"><?php esc_html_e('Optimized for free', 'tenweb-booster') ?></span>
        <h5><?php echo sprintf(_n('%d <span>image</span>', '%d <span>images</span>', $pages_compressed['total_compressed_images'], 'tenweb-booster'), $pages_compressed['total_compressed_images']); ?></h5>
        <p class="twb-section-description"><?php esc_html_e('Image optimization is performed only on 6 pages included in Free plan.', 'tenweb-booster') ?></p>
        <div class="twb-line_info_container">
          <span><?php esc_html_e('Optimized pages', 'tenweb-booster') ?></span>
          <span><?php echo sprintf(__('%d of 6', 'tenweb-booster'), $pages_compressed['compressed_pages_count']); ?></span>
        </div>
        <div class="twb-line_container"><span class="twb-size_<?php echo esc_attr($pages_compressed['compressed_pages_count']); ?>"></span></div>
        <div class="twb-section-bottom">
        <?php
        $home_page = '';
        if ( !empty($pages_compressed['pages']) ) {
          foreach ( $pages_compressed['pages'] as $page ) {
            if ( $page['permalink'] == 'Homepage' ) {
              $home_page = 'twb-hompage-path';
              $path = $page['permalink'];
            }
            else {
              $path = parse_url($page['permalink']);
              $path["path"] = rtrim($path["path"], "/");
              $explode = explode("/", $path["path"]);
              if ( count($explode) > 1 ) {
                $path = '.../' . end($explode);
              }
              else {
                $path = '...' . $path['path'];
              }
            }
            ?>
            <div class="twb-most-image-cont">
              <div class="twb-most-image-cont-path <?php echo esc_attr($home_page); ?>"><?php echo esc_html($path); ?></div>
              <div class="twb-most-image-cont-img-count twb-optimized">
                <?php
                if ( $page['images_count'] ) {
                  echo sprintf(_n('%d image', '%d images', $page['images_count'], 'tenweb-booster'), $page['images_count']);
                }
                ?>
              </div>
            </div>
            <?php
          }
        }
        ?>
        </div>
      </div>
      <div class="twb-img_optimizer-right">
        <span class="twb-not-optimized-info"><?php esc_html_e('Not Optimized', 'tenweb-booster') ?></span>
        <h5><?php echo sprintf(_n('%d <span>image</span>', '%d <span>images</span>', $pages_compressed['total_not_compressed_images_count'], 'tenweb-booster'), $pages_compressed['total_not_compressed_images_count']); ?></h5>
        <ul>
          <li><?php esc_html_e('Specify the most image-heavy pages', 'tenweb-booster') ?></li>
          <li><?php esc_html_e('Optimize pages with photo galleries', 'tenweb-booster') ?></li>
        </ul>
        <p><?php esc_html_e('Add pages with images you’d like to optimize.', 'tenweb-booster') ?></p>
        <div class="twb-img_optimizer-button-container">
          <div class="twb-img_optimizer-info">
            <p><?php esc_html_e('Total size:', 'tenweb-booster') ?></p>
            <p><?php echo esc_html($pages_compressed['total_not_compressed_images_size']) ?></p>
          </div>
          <a class="twb-img_add_pages_button" target="_blank" href="<?php echo esc_url($params['dashboard_booster_url']); ?>"><?php esc_html_e('Add pages', 'tenweb-booster'); ?></a>
        </div>
      </div>
    </div>
    <?php
  }

  /**
   * Top banner.
   *
   * @param array $params
   */
  public function top_banner( $params = array() ) {
    wp_enqueue_style(TenWebBooster::PREFIX . '-top-banner');
    $button = $params['button'];
    ?>
    <div class="twb-booster-top-banner">
      <?php if ( $params['notice']  ) { ?>
        <p class="twb-booster-top-banner-wrapper-note">
          <span class="twb-booster-top-banner-wrapper-note--text">
            <?php echo esc_html($params['notice']); ?>
          </span>
        </p>
      <?php } ?>
      <div class="twb-booster-top-banner-wrapper">
        <div>
          <p class="twb-booster-top-banner-wrappe--images-count">
            <?php echo esc_html($params['title']); ?>
          </p>
          <p>
            <?php echo esc_html($params['desc']); ?>
          </p>
        </div>
        <div>
          <a href="<?php echo esc_url($button['url']); ?>" <?php echo esc_attr($button['target']); ?> class="twb-booster-top-banner-wrappe--button"><?php echo esc_html($button['name']); ?></a>
        </div>
      </div>
    </div>
    <?php
  }

  /**
   * @param $params
   *
   * @return false|string
   */
  protected function popup() {
    $params = array(
        'title' => __('Image optimization is not active',  'tenweb-booster'),
        'description' => __('Complete the sign up process to optimize your images for better website performance.', 'tenweb-booster'),
        'html' => '<input type="email" class="twb-sign-up-input" placeholder="Email address" />',
        'button1' => array(
          'title' => __('Sign up',  'tenweb-booster'),
          'action' => 'onclick=\'twb_sign_up_dashboard( this );\'',
          'class' => 'twb-primary',
        ),
        'html2' => sprintf(__('By signing up, you agree to 10Web’s. %s and %s', 'tenweb-booster'),
             '<a href="https://10web.io/terms-of-service/" target="_blank">' . __('Terms of Services',  'tenweb-booster') . '</a>',
             '<a href="https://10web.io/privacy-policy/" target="_blank">' . __('Privacy Policy',  'tenweb-booster') . '</a>'),
        'dismiss' => array(
          'action' => 'onclick=\'twb_leaving_popup = true; jQuery(".twb-popup-overlay").addClass("twb-hidden");\'',
        ),
      );
    ob_start();
    ?>
    <div class="twb-hidden twb-popup-overlay">
      <?php
      if (isset($params['dismiss'])) {
        ?>
      <div class="twb-popup-dismiss" <?php echo $params['dismiss']['action']; ?>></div>
        <?php
      }
      ?>
      <div class="twb-popup">
        <div class="twb-popup-content">
          <div class="twb-popup-title"><?php echo esc_html($params['title']); ?></div>
          <div class="twb-popup-description"><?php echo esc_html($params['description']); ?></div>
          <?php
          if (isset($params['html'])) {
            ?>
            <div class="twb-html"><?php echo ($params['html']); ?></div>
            <?php
          }
          ?>
        </div>
        <?php
        if (isset($params['button1']) || isset($params['button2'])) {
          ?>
        <div class="twb-popup-button">
          <?php
          if (isset($params['button1'])) {
            ?>
          <a <?php echo $params['button1']['action']; ?> class="wd-float-left <?php echo esc_html($params['button1']['class']); ?>"><?php echo esc_html($params['button1']['title']); ?></a>
            <?php
          }
          if (isset($params['button2'])) {
            ?>
            <a <?php echo $params['button2']['action']; ?> class="wd-float-right <?php echo esc_html($params['button2']['class']); ?>"><?php echo esc_html($params['button2']['title']); ?></a>
            <?php
          }
          if (isset($params['html2'])) {
            ?>
            <span class="twb-html"><?php echo ($params['html2']); ?></span>
            <?php
          }
          ?>
        </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
}
