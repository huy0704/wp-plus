<?php

class WDSModelWDSShortcode {

  public function __construct() {
  }

  public function get_row_data() {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT `id`, `name` FROM `' . $wpdb->prefix . 'wdsslider` ORDER BY `name` ASC');
    return $rows;
  }
}