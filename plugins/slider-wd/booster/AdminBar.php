<?php
/**
 * Class OptimizerAdminBar
 */
class TWBAdminBar
{
    private $booster;

    private $page_speed_status;

    /* Keeping ongoing data of progresses to show notification popup*/
    private $notification_data = array( 'inprogress' => array(), 'completed' => array() );
    /* Set 1 if need to show notification */
    private $show_notification_popup = 0;

    function __construct( $wp_admin_bar, $booster ) {
      $this->twb_get_notification_data();
      /* No need to add adminbar menu in admin if there is no notification data to show */
      if ( is_admin() && !$this->show_notification_popup ) {
        return;
      }
      $this->booster = $booster;
      /* Ajax action which checking notification status and show if response has data */
      if ( isset($_POST['action']) && sanitize_text_field($_POST['action']) == "twb_notif_check" ) {
          $html = '';
          $clearInterval = 1;
          if ( !empty($this->notification_data['completed']) ) {
            $html = $this->twb_popup_notification();
            $changeLogo = 1;
          }
          if ( !empty($this->notification_data['inprogress']) ) {
            $html = $this->twb_popup_notification();
            $clearInterval = 0;
            $changeLogo = 0;
          }
          echo wp_json_encode(array('html' => $html, 'clearInterval' => $clearInterval, 'changeLogo' => $changeLogo));
          die;
      } else {
          global $post;
          if ( !empty($post) ) {
            if ( get_post_status($post->ID) != 'publish' ) {
              return;
            }
          }

          $this->page_speed_status = TWBLibrary::get_page_speed_status();
          $this->include_style_scripts();
          $wp_admin_bar->add_menu(array(
                                    'id' => 'twb_adminbar_info',
                                    'title' => $this->twb_admin_menu(),
                                    'meta' => array(
                                      'target' => '_blank',
                                      'class' => $this->booster->cta_button['class'],
                                      'html' => $this->twb_admin_bar_menu_content(),
                                    ),
                                  ));
      }
    }

    public function include_style_scripts() {
      wp_enqueue_style(TenWebBooster::PREFIX . '-global');
      wp_enqueue_script(TenWebBooster::PREFIX . '-global');
    }

    /**
     * Admin bar menu.
     *
     * @return string
    */
    public function twb_admin_menu() {
        if ( !is_admin() ) {
          $img = '';
            $className = '';
            $title = '';
            if ( $this->page_speed_status == 'notstarted' ) {
                $img = '<img class="twb_menu_logo" src="' . $this->booster->plugin_url . '/assets/images/logo_white.svg" />';
                $img .= '<img class="twb-hidden twb_not_optimized_logo" src="' . $this->booster->plugin_url . '/assets/images/not_optimized.svg" />';
                $title = '<div class="twb_menu_logo">'.__("Check PageSpeed Score", 'tenweb-booster').'</div>';
                $title .= '<div class="twb-hidden twb_not_optimized_logo">'.__("Not optimized", 'tenweb-booster').'</div>';
            } elseif ( $this->page_speed_status ==  'inprogress' ) {
                $img = '<img class="twb_menu_logo" src="' . $this->booster->plugin_url . '/assets/images/logo_white.svg" />';
                $img .= '<img class="twb-hidden twb_not_optimized_menu_logo" src="' . $this->booster->plugin_url . '/assets/images/not_optimized.svg" />';
                $className = ' twb_score_inprogress';
                $title = '<div class="twb_menu_logo">'.__("Check PageSpeed Score", 'tenweb-booster').'</div>';
                $title .= '<div class="twb-hidden twb_not_optimized_logo">'.__("Not optimized", 'tenweb-booster').'</div>';
            } elseif ( $this->page_speed_status == 'completed' || $this->page_speed_status == 'error' ) {
                $img = '<img src="' . $this->booster->plugin_url . '/assets/images/not_optimized.svg" />';
                $title = __("Not optimized", 'tenweb-booster');
                $className = ' twb_not_optimized';
            }
            $twb_admin_bar_menu = '<div class="twb_admin_bar_menu twb_frontend' . $className . '"><div class="twb_admin_bar_menu_header' . $className . '">' . $img .  " " . $title . '</div><div class="twb_vr"></div><span></span></div>';
        } else {
            if ( $this->show_notification_popup ) {
              $className = 'twb_backend_optimizing_logo';
              if( empty($this->notification_data['inprogress']) ) {
                $className = "twb_backend_not_optimized_logo";
              }
              $twb_admin_bar_menu = '<div class="twb_admin_bar_menu twb_backend"><div class="twb_admin_bar_menu_header"><span class="'.$className.'"></span></div></div>';
            }
        }
        return $twb_admin_bar_menu;
    }

    /**
     * Adminbar menu content.
     *
     * @return string
    */
    public function twb_admin_bar_menu_content()
    {
      ob_start();
      if( is_admin() ) {
        /* Notification for in progress optimizing */
        echo $this->twb_popup_notification();
      }
      ?>
      <div class="twb_admin_bar_menu_main twb-hidden">
      <?php

      if ( !is_admin() ) {
        if ( $this->page_speed_status == 'notstarted' ) {
            $this->twb_front_score_not_counted_content();
        } elseif ( $this->page_speed_status == 'inprogress' ) {
            $this->twb_front_score_in_progress_content();
        } elseif ( $this->page_speed_status == 'error' ) {
            $this->twb_front_score_error_content();
        } else {
            $this->twb_front_score_counted_content();
        }
      }
      ?>
      </div>
      <?php
      return ob_get_clean();
    }

    /* Content of admin menu if score not counted*/
    public function twb_front_score_not_counted_content() {
      global $post;
      if ( empty($post) ) {
        return false;
      }

      $post_id = $post->ID;
      $checkout_url = '';
      ?>
      <div class="twb_admin_bar_menu_content twb-notoptimized twb_not_optimized_content">
        <p class="twb_status_title"><?php echo __('Check the PageSpeed score', 'tenweb-booster'); ?></p>
        <p class="twb_status_description"><?php _e('PageSpeed score is an essential attribute to your website’s performance. It affects both the user experience and SEO rankings.', 'tenweb-booster') ?></p>
        <div class="twb_check_score_button_cont">
          <a data-post_id="<?php echo esc_attr($post_id); ?>"
             data-initiator="admin-bar" target="_blank"
             class="twb_check_score_button"><?php _e('Check PageSpeed Score', 'tenweb-booster') ?></a>
        </div>
          <?php
          echo TWBLibrary::dismiss_info_content( $this->booster );
          ?>
      </div>
      <?php
      $this->twb_front_score_in_progress_content();
      $this->twb_front_score_counted_content();
    }

    /* Content of admin menu if score counting in progress */
    public function twb_front_score_in_progress_content() {
      global $post;
      if ( empty($post) ) {
        return false;
      }

      $post_id = $post->ID;
      $page_title = get_the_title( $post_id );
      ?>
      <div class="twb_admin_bar_menu_content twb-optimizing <?php echo $this->page_speed_status == 'notstarted' ? 'twb-hidden' : ''; ?>">
        <p class="twb_status_title twb_status_title_inprogress twb_score_inprogress"><span></span><?php echo __('Checking...', 'tenweb-booster'); ?></p>
        <p class="twb_status_description"><?php echo sprintf(__('We are checking the PageSpeed score of your %s page.', 'tenweb-booster'), $page_title); ?></p>
      </div>
      <?php
    }

    /* Content of admin menu if score counted */
    public function twb_front_score_counted_content() {
      global $post;
      if ( empty($post) ) {
        return false;
      }

      $post_id = $post->ID;
      $page_score = get_post_meta( $post_id, 'two_page_speed' );

      $score = array(
        'mobile_score' => 0,
        'mobile_tti' => 0,
        'desktop_score' => 0,
        'desktop_tti' => 0,
      );

      if ( !empty($page_score) ) {
        $page_score = end($page_score);
        if ( !empty($page_score['previous_score']) && !empty($page_score['previous_score']['mobile_score']) ) {
          $page_score = $page_score['previous_score'];
          $score = array(
            'mobile_score' => $page_score['mobile_score'],
            'mobile_tti' => $page_score['mobile_tti'],
            'desktop_score' => $page_score['desktop_score'],
            'desktop_tti' => $page_score['desktop_tti'],
          );
        }
      }


      $page_title = get_the_title( $post_id );
      $url = $this->booster->submenu_url;
      ?>
      <div class="twb_admin_bar_menu_content twb-optimized twb_counted <?php echo $this->page_speed_status != 'completed' ? 'twb-hidden' : ''; ?>">
        <?php
        $title = sprintf(__('%s page', 'tenweb-booster'), $page_title);
        TWBLibrary::score( $score, $url, $post_id, $title, 0 );
        echo TWBLibrary::dismiss_info_content( $this->booster );
        ?>
      </div>
      <?php
    }

    /* Content of admin menu if score counted */
    public function twb_front_score_error_content() {
      global $post;
      if ( empty($post) ) {
        return false;
      }

      $post_id = $post->ID;
      $page_title = get_the_title( $post_id );
      $url = $this->booster->submenu_url;

      $score = array(
        'error' => 1,
      );
      ?>
      <div class="twb_admin_bar_menu_content twb-optimized twb_counted <?php echo $this->page_speed_status != 'error' ? 'twb-hidden' : ''; ?>">
        <?php
        $title = sprintf(__('%s page', 'tenweb-booster'), $page_title);
        TWBLibrary::score( $score, $url, $post_id, $title, 0 );
        echo TWBLibrary::dismiss_info_content( $this->booster );
        ?>
      </div>
      <?php
    }

    /* Getting data inprogress and counted scores  */
    public function twb_get_notification_data() {
      global $wpdb;
      $posts = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='two_page_speed'", ARRAY_A);
      if( empty($posts) ) {
        return;
      }
      foreach ( $posts as $post ) {
        $page_score = unserialize($post['meta_value']);
        if( isset($page_score['previous_score']) ) {
          $page_score = $page_score['previous_score'];
        } else {
          continue;
        }

        $page_title = get_the_title($post['post_id']);
        $post_id = $post['post_id'];
        if ( isset($page_score["status"]) && $page_score["status"] == "inprogress" ) {
          $this->show_notification_popup = 1;
          $this->notification_data['inprogress'][] = array(
            'post_id' => $post_id,
            'post_title' => $page_title,
          );
        }
        $shown = isset($page_score["shown"]) ? $page_score["shown"] : 0;
        if (isset($page_score["status"]) && $page_score["status"] == "completed" && !isset($page_score["error"]) && !$shown ) {
          $this->show_notification_popup = 1;
          $this->notification_data['completed'][] = array(
            'post_id' => $post_id,
            'post_title' => $page_title,
            'mobile_score' => $page_score['mobile_score'],
            'mobile_tti' => $page_score['mobile_tti'],
            'desktop_score' => $page_score['desktop_score'],
            'desktop_tti' => $page_score['desktop_tti'],
          );
          $page_score["shown"] = 1;
          $data['previous_score'] = $page_score;
          update_post_meta($post_id, 'two_page_speed', $data);
        }

      }
    }

    /* Show notification during the page load if there is optimizing page in progress */
    public function twb_popup_notification() {
      if ( !$this->show_notification_popup ) {
        return '';
      }
      ob_start();
      ?>
      <div class="twb_admin_bar_menu_main twb_admin_bar_menu_main_notif">
        <?php if ( !empty($this->notification_data['completed']) ) { ?>
        <div class="twb_admin_bar_menu_content twb_counted">
          <?php
          $url = $this->booster->submenu_url;
          $i = 1;
          foreach ( $this->notification_data['completed'] as $score ) { ?>
            <div class="twb_counted_cont">
              <div class="twb_score_block_container">
                <?php
                $title = sprintf( __('%s page', 'tenweb-booster'), esc_html($score['post_title']) );
                TWBLibrary::score( $score, $url, $score['post_id'], $title, 0 );
                echo TWBLibrary::dismiss_info_content( $this->booster );
                ?>
              </div>
            </div>
            <?php
            $i++;
          }
          ?>
        </div>
        <?php }  ?>
        <?php if ( !empty($this->notification_data['inprogress']) ) { ?>
        <div class="twb_counting_container">
          <?php foreach ( $this->notification_data['inprogress'] as $checking ) { ?>
            <p class="twb_counting_title"><span></span><?php _e('Checking…', 'tenweb-booster'); ?></p>
            <p class="twb_counting_descr"><?php echo sprintf(__('We are checking the PageSpeed score of your %s page.', 'tenweb-booster'), '<span>'.esc_html($checking['post_title']).'</span>'); ?></p>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <?php
      return ob_get_clean();
    }

}
