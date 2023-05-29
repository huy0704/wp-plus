<?php

/**
 * Class TWBGutenberg
 */
class TWBList {
  private $booster;
  protected static $_instance = null;

  function __construct( $booster ) {
    $this->booster = $booster;
    $this->booster->register_scripts();
    wp_enqueue_style(TenWebBooster::PREFIX . '-global');
    wp_enqueue_script(TenWebBooster::PREFIX . '-global');
    // Add column to the posts list table.
    add_filter('manage_post_posts_columns', array( $this, 'add_column' ));
    add_filter('manage_page_posts_columns', array( $this, 'add_column' ));
    add_action('manage_post_posts_custom_column', array( $this, 'manage_column' ), 10, 2);
    add_action('manage_page_posts_custom_column', array( $this, 'manage_column' ), 10, 2);
  }

  /**
   * Main Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return Main instance.
   */
  public static function instance($booster) {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self($booster);
    }
    return self::$_instance;
  }

  public function add_column( $columns ) {
    $offset = array_search('author', array_keys($columns));

    return array_merge(array_slice($columns, 0, $offset), [ 'twb-speed-' . $this->booster->submenu['parent_slug'] => __('PageSpeed score', 'tenweb-booster') . TWBLibrary::dismiss_info_content( $this->booster, TRUE ) ], array_slice($columns, $offset, NULL));
  }

  public function manage_column( $column_key, $post_id ) {
    if ( $column_key == 'twb-speed-' . $this->booster->submenu['parent_slug'] ) {
      if ( get_post_status($post_id) != 'publish' ) {
        return;
      }
      $this->display($post_id);
    }
  }

  private function display( $post_id ) {
    $page_score = get_post_meta($post_id, 'two_page_speed', TRUE);
    $status = 'notstarted';
    $score = array();
    if ( isset($page_score['previous_score']['status']) ) {
      $status = $page_score['previous_score']['status'];
      $score = $page_score['previous_score'];
    }
    ?>
    <span class="twb-page-speed twb-optimized <?php echo $status == 'completed' ? '' : 'twb-hidden'; ?>">
      <a class="twb-see-score" target="_balnk" href="<?php echo esc_url($this->booster->submenu_url); ?>"><?php _e('Optimize images and speed', 'tenweb-booster'); ?></a>
    </span>
    <span data-status="<?php echo $status; ?>" class="twb-page-speed twb-notoptimized <?php echo $status == 'notstarted' ? '' : 'twb-hidden'; ?>">
      <a class="twb_check_score_button" data-post_id="<?php echo esc_attr($post_id); ?>"><?php _e('Check score', 'tenweb-booster'); ?></a>
    </span>
    <span class="twb-page-speed twb-optimizing <?php echo $status == 'inprogress' ? '' : 'twb-hidden'; ?>">
      <?php _e('Checking...', 'tenweb-booster'); ?>
      <p class="twb-description"></p>
    </span>
    <?php echo TWBLibrary::score($score, '', $post_id); ?>
    <div class="twb-score-disabled-container twb-hidden">
      <div class="twb-score-title"><?php _e('Checking PageSpeed score', 'tenweb-booster'); ?></div>
      <div class="twb-score-desc"><?php _e('We are checking the PageSpeed score of a different page, please wait until the process is complete to run PageSpeed check on another page.', 'tenweb-booster'); ?></div>
      <div class="twb-score-bottom"><a onclick="jQuery('.twb-score-disabled-container').addClass('twb-hidden')"><?php _e('Got it', 'tenweb-booster'); ?></a></div>
    </div>
    <?php
  }
}

function TWBList($booster) {
  return TWBList::instance($booster);
}


