<?php
defined('ABSPATH') || die('Access Denied');

/**
 * Admin view class.
 */
class AdminView_wds {

	protected $default_global_options = array();

	public function __construct() {
		$default_global_options = get_option("wds_global_options", 0);
		$this->default_global_options = json_decode($default_global_options);
		wp_enqueue_style(WDS()->prefix . '_tables');
		wp_enqueue_script(WDS()->prefix . '_admin');
	}
  /**
   * Generate form.
   *
   * @param string $content
   * @param array  $attr
   *
   * @return string Form html.
   */
  protected function form($content = '', $attr = array()) {
    ob_start();
    // Form.
    $action = isset($attr['action']) ? esc_attr($attr['action']) : '';
    $method = isset($attr['method']) ? esc_attr($attr['method']) : 'post';
    $name = isset($attr['name']) ? esc_attr($attr['name']) : WDS()->prefix . '_form';
    $id = isset($attr['id']) ? esc_attr($attr['id']) : '';
    $class = isset($attr['class']) ? esc_attr($attr['class']) : WDS()->prefix . '_form';
    $style = isset($attr['style']) ? esc_attr($attr['style']) : '';
    $current_id = isset($attr['current_id']) ? esc_attr($attr['current_id']) : '';
    $task = isset($attr['task']) ? esc_attr($attr['task']) : '';
    ?><div class="wrap<?php echo (isset($_GET['action']) ? ' wd-wrap-ajax' : ''); ?>">
    <?php
    // Generate message container by message id or directly by message.
    $message_id = WDW_S_Library::get('message', 0);
    $message = WDW_S_Library::get('msg', '');
    echo WDW_S_Library::message_id($message_id, $message);
    ?>
    <form
      <?php echo $action ? 'action="' . $action . '"' : ''; ?>
      <?php echo $method ? 'method="' . $method . '"' : ''; ?>
      <?php echo $name ? ' name="' . $name . '"' : ''; ?>
      <?php echo $id ? ' id="' . $id . '"' : ''; ?>
      <?php echo $class ? ' class="' . $class . '"' : ''; ?>
      <?php echo $style ? ' style="' . $style . '"' : ''; ?>
    ><h1 class="hidden"></h1><?php
      echo $content;
      // Add nonce to form.
      wp_nonce_field(WDS()->nonce, WDS()->nonce);
      ?>
      <input id="task" name="task" type="hidden" value="<?php echo $task; ?>"/>
      <input id="current_id" name="current_id" type="hidden" value="<?php echo $current_id; ?>"/>
    </form>
    </div><?php
    return ob_get_clean();
  }

  /**
   * Generate title.
   *
   * @param array $params
   *
   * @return string Title html.
   */
  protected function title($params) {
    $title = !empty($params['title']) ? $params['title'] : '';
    $title_class = !empty($params['title_class']) ? $params['title_class'] : '';
    $title_name = !empty($params['title_name']) ? $params['title_name'] : '';
    $title_id = !empty($params['title_id']) ? $params['title_id'] : '';
    $title_value = !empty($params['title_value']) ? $params['title_value'] : '';
    $add_new_button = !empty($params['add_new_button']) ? $params['add_new_button'] : '';

    $attributes = '';
    if ( !empty($add_new_button) && is_array($add_new_button) ) {
      foreach ( $add_new_button as $key => $val ) {
        $attributes .= $key . '="' . $val . '"';
      }
    }

    ob_start();
    ?><div class="wd-page-title <?php echo $title_class; ?>">
    <h1 class="wp-heading-inline"><?php echo $title; ?>
      <?php
      if ( $title_name || $title_id || $title_value ) {
        ?>
        <span id="fm-title-edit">
          <input type="text" id="<?php echo $title_id; ?>" name="<?php echo $title_name; ?>" value="<?php echo $title_value; ?>" />
        </span>
        <?php
      }
      if ( $add_new_button ) {
        ?>
        <a class="page-title-action" <?php echo $attributes; ?>>
          <?php _e('Add New', WDS()->prefix); ?>
        </a>
        <?php
      }
      ?>
    </h1>
    </div><?php
    return ob_get_clean();
  }

  /**
   * Generate buttons.
   *
   * @param array $buttons
   * @param bool $single
   * @param array $parent
   *
   * @return array Buttons html.
   */
  protected function buttons($buttons = array(), $single = FALSE, $parent = array()) {
    ob_start();
    if ( !$single ) {
      $parent_id = isset($parent['id']) ? esc_attr($parent['id']) : '';
      $parent_class = isset($parent['class']) ? esc_attr($parent['class']) : 'wd-buttons';
      $parent_style = isset($parent['style']) ? esc_attr($parent['style']) : '';
      ?>
      <div
      <?php echo $parent_id ? 'id="' . $parent_id . '"' : ''; ?>
      <?php echo $parent_class ? ' class="' . $parent_class . '"' : ''; ?>
      <?php echo $parent_style ? ' style="' . $parent_style . '"' : ''; ?>
      >
      <?php
    }
    foreach ($buttons as $button) {
      $title = isset($button['title']) ? esc_attr($button['title']) : '';
      $value = isset($button['value']) ? esc_attr($button['value']) : '';
      $name = isset($button['name']) ? esc_attr($button['name']) : '';
      $id = isset($button['id']) ? esc_attr($button['id']) : '';
      $class = isset($button['class']) ? esc_attr($button['class']) : '';
      $style = isset($button['style']) ? esc_attr($button['style']) : '';
      $onclick = isset($button['onclick']) ? esc_attr($button['onclick']) : '';
      ?><button type="submit"
      <?php echo $value ? ' value="' . $value . '"' : ''; ?>
      <?php echo $name ? ' name="' . $name . '"' : ''; ?>
      <?php echo $id ? ' id="' . $id . '"' : ''; ?>
                class="wd-button <?php echo $class; ?>"
      <?php echo $style ? ' style="' . $style . '"' : ''; ?>
      <?php echo $onclick ? ' onclick="' . $onclick . '"' : ''; ?>
      ><?php echo $title; ?></button><?php
    }
    if ( !$single ) {
      ?>
      </div>
      <?php
    }
    return ob_get_clean();
  }

  /**
   * Search.
   *
   * @param  array $params
   * @return string
   */
  protected function search( $params = array() ) {
    $search = WDW_S_Library::get('s', '');
    ob_start();
    ?>
    <p class="search-box">
      <input name="s" value="<?php echo $search; ?>" type="search" onkeypress="return input_search(event, this)" />
      <input class="button" value="<?php echo __('Search', WDS()->prefix) . ' ' . ( !empty( $params['search_item_name'] ) ? $params['search_item_name'] : '' ); ?>" type="button" onclick="search(this)" />
    </p>
    <?php
    return ob_get_clean();
  }

  /**
   * Pagination.
   *
   * @param     $page_url
   * @param     $total
   * @param int $items_per_page
   *
   * @return string
   */
  protected function pagination($page_url, $total, $items_per_page = 20) {
    $page_number = WDW_S_Library::get('paged', 1);
    $search = WDW_S_Library::get('s', '');
    $orderby = WDW_S_Library::get('orderby', '');
    $order = WDW_S_Library::get('order', '');
    $url_arg = array();
    if( !empty($search) ) {
      $url_arg['s'] = $search;
    }
    if( !empty($orderby) ) {
      $url_arg['orderby'] = $orderby;
    }
    if( !empty($order) ) {
      $url_arg['order'] = $order;
    }
    $page_url = add_query_arg($url_arg, $page_url);

    if ( $total ) {
      if ( $total % $items_per_page ) {
        $pages_count = ($total - $total % $items_per_page) / $items_per_page + 1;
      }
      else {
        $pages_count = ($total - $total % $items_per_page) / $items_per_page;
      }
    }
    else {
      $pages_count = 1;
    }
    ob_start();
    ?>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php printf(_n('%s item', '%s items', $total, WDS()->prefix), $total); ?>
      </span>
      <?php
      if ( $total > $items_per_page ) {
        ?>
        <span class="pagination-links" data-pages-count="<?php echo $pages_count; ?>">
        <?php
        if ( $page_number == 1 ) {
          ?>
          <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
          <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
          <?php
        }
        else {
          ?>
          <a data-paged="<?php echo 1; ?>" href="<?php echo add_query_arg(array('paged' => 1), $page_url); ?>" class="wd-page first-page"><span class="screen-reader-text"><?php _e('First page', WDS()->prefix); ?></span><span aria-hidden="true">«</span></a>
          <a data-paged="<?php echo ($page_number == 1 ? 1 : ($page_number - 1)); ?>" href="<?php echo add_query_arg(array('paged' => ($page_number == 1 ? 1 : ($page_number - 1))), $page_url); ?>" class="wd-page previous-page"><span class="screen-reader-text"><?php _e('Previous page', WDS()->prefix); ?></span><span aria-hidden="true">‹</span></a>
          <?php
        }
        ?>
          <span class="paging-input">
          <label for="current-page-selector" class="screen-reader-text"><?php _e('Current Page', WDS()->prefix); ?></label>
          <input type="text" class="current-page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return input_pagination(event, this)" size="1" />
          <span class="tablenav-paging-text">
             <?php _e('of', WDS()->prefix); ?>
            <span class="total-pages"><?php echo $pages_count; ?></span>
          </span>
        </span>
          <?php
          if ( $page_number >= $pages_count ) {
            ?>
            <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
            <span class="tablenav-pages-navspan" aria-hidden="true">»</span>
            <?php
          }
          else {
            ?>
            <a data-paged="<?php echo ($page_number >= $pages_count ? $pages_count : ($page_number + 1)); ?>" href="<?php echo add_query_arg(array('paged' => ($page_number >= $pages_count ? $pages_count : ($page_number + 1))), $page_url); ?>" class="wd-page next-page"><span class="screen-reader-text"><?php _e('Next page', WDS()->prefix); ?></span><span aria-hidden="true">›</span></a>
            <a data-paged="<?php echo $pages_count; ?>" href="<?php echo add_query_arg(array('paged' => $pages_count), $page_url); ?>" class="wd-page last-page"><span class="screen-reader-text"><?php _e('Last page', WDS()->prefix); ?></span><span aria-hidden="true">»</span></a>
            <?php
          }
          ?>
      </span>
        <?php
      }
      ?>
    </div>
    <?php

    return ob_get_clean();
  }

  /**
   * Bulk actions list.
   *
   * @param        $actions
   * @param bool   $select_all
   * @param string $name
   *
   * @return string
   */
  protected function bulk_actions($actions, $select_all = FALSE, $name = "bulk_action") {
    ob_start();
    ?>
    <div class="alignleft actions bulkactions">
      <?php if ( $select_all ) { ?>
      <span class="button wd-check-all" onclick="spider_check_all_items(event)">
        <input type="checkbox" id="check_all_items" name="check_all_items" onclick="spider_check_all_items_checkbox(event)" />
        <span><?php _e('Select All', WDS()->prefix); ?></span>
      </span>
	  <?php } ?>
      <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e('Select bulk action', WDS()->prefix); ?></label>
      <select name="<?php echo $name; ?>" id="bulk-action-selector-top">
        <option value="-1"><?php _e('Bulk Actions', WDS()->prefix); ?></option>
        <?php
        foreach ( $actions as $key => $action ) {
          ?>
          <option value="<?php echo $key; ?>"><?php echo $action['title']; ?></option>
          <?php
        }
        ?>
      </select>
      <input type="button" id="doaction" class="button action" onclick="wd_bulk_action('#bulk-action-selector-top')" value="<?php _e('Apply', WDS()->prefix); ?>" />
    </div>
    <?php
    return ob_get_clean();
  }
  
  /**
   * Filters.
   *
   * @param  array $params
   * @return string
   */
  protected function filters( $params = array() ) {
    ob_start();
    if ( !empty($params['filters']) ) {
      $filters = $params['filters'];
      ?>
      <div class="alignleft actions">
        <?php
        foreach ( $filters as $filter_key => $filter_values ) {
          $filter_by_key = 'filter-by-' . $filter_key;
          $filter_by = WDW_S_Library::get($filter_by_key, '');
          ?>
          <label for="filter-by-<?php echo $filter_key ?>" class="screen-reader-text"><?php echo $filter_values['label']; ?></label>
          <select class="wd-filter" name="filter[filter-by-<?php echo $filter_key ?>]" id="filter-by-<?php echo $filter_key ?>">
            <?php
            foreach ( $filter_values['items'] as $item_key => $item_value ) {
              $selected = ($filter_by == $item_key ? 'selected' : '');
              ?>
              <option <?php echo $selected; ?> value="<?php echo $item_key ?>"><?php echo $item_value ?></option>
              <?php
            }
            ?>
          </select>
          <?php
        }
        ?>
      </div>
      <?php
    }

    return ob_get_clean();
  }
}
