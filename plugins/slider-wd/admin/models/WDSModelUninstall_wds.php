<?php

class WDSModelUninstall_wds {
  public function __construct() {
  }
  public function delete_db_tables() {
    global $wpdb;

    $wpdb->query("DROP TABLE " . $wpdb->prefix . "wdsslider");
    $wpdb->query("DROP TABLE " . $wpdb->prefix . "wdsslide");
    $wpdb->query("DROP TABLE " . $wpdb->prefix . "wdslayer");

    delete_option('tenweb_notice_status');
    delete_option("wds_version");
    delete_option("wds_theme_version");
    delete_option("wds_global_options");
    delete_option("wds_subscribe_done");

    $wds_post_type = $wpdb->get_var("SELECT id  FROM " . $wpdb->prefix . "posts WHERE post_type = 'wds-slider'");
    wp_delete_post($wds_post_type);
  }
}