<?php

/**
 * Class WDSModelposts
 */
class WDSModelposts {
  public function get_rows_data() {
    $search_value = WDW_S_Library::esc_sanitize_data($_POST, 'search_value', 'sanitize_text_field');
    $category_id = WDW_S_Library::esc_sanitize_data($_POST, 'category_id', 'sanitize_text_field');
    if ( $category_id == -1 ) {
      $category_id = '';
    }
    $category_name = $category_id ? get_the_category_by_ID($category_id) : '';
    $asc_or_desc = WDW_S_Library::esc_sanitize_data($_POST, 'asc_or_desc', 'sanitize_text_field', 'ASC');
    $order_by = WDW_S_Library::esc_sanitize_data($_POST, 'order_by', 'sanitize_text_field', 'date');
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_limit = (int) ($limit / 20 + 1);
    $args = array(
      'posts_per_page' => 255,
      'category_name' => $category_name,
      'orderby' => $order_by,
      'order' => $asc_or_desc,
      'post_status' => 'publish',
    );

    $posts = get_posts($args);
    $row = array();
    $counter = 0;
    for ($i = 0; $i < count($posts); $i++) {
      $post = $posts[$i];
      if (has_post_thumbnail($post->ID) && (!$search_value || (stristr($post->post_title, $search_value) !== FALSE))) {
        $counter++;
        if ($counter > $limit && $counter <= $limit + 20) {
          $row[$post->ID] = new stdClass();
          $row[$post->ID]->id = $post->ID;
          $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
          $row[$post->ID]->image_url = $image_url[0];
          $thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
          $row[$post->ID]->thumb_url = $thumb_url[0] ? $thumb_url[0] : $image_url[0];
          $row[$post->ID]->title = $post->post_title;
          $row[$post->ID]->date = $post->post_date;
          $row[$post->ID]->modified = $post->post_modified;
          $row[$post->ID]->type = $post->post_type;
          $row[$post->ID]->author = get_the_author_meta('display_name', $post->post_author);
          $row[$post->ID]->link = get_permalink($post->ID);
          $row[$post->ID]->content = $this->add_more_link(strip_tags($post->post_content), 250);
        }
      }
    }

    return array($row, $counter, $page_limit);
  }

  public function add_more_link($content, $charlength) {
    if (mb_strlen($content) > $charlength) {
      $subex = mb_substr($content, 0, $charlength);
      return $subex . '<a target="_blank" class="wds_more"> ...</a>';
    }
    else {
      return $content;
    }
  }
}
