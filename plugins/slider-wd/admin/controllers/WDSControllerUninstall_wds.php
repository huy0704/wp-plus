<?php

class WDSControllerUninstall_wds {
  public function __construct() {
    if ( WDS()->is_free ) {
      global $wds_options;
      if ( !class_exists("TenWebLibConfig") ) {
        $plugin_dir = apply_filters('tenweb_free_users_lib_path', array('version' => '1.1.1', 'path' => WDS()->plugin_dir));
        include_once($plugin_dir['path'] . "/wd/config.php");
      }
      $config = new TenWebLibConfig();
      $config->set_options($wds_options);
      $deactivate_reasons = new TenWebLibDeactivate($config);
      $deactivate_reasons->submit_and_deactivate();
    }
  }

  public function execute() {
    $task = ((isset($_POST['task'])) ? sanitize_text_field($_POST['task']) : '');
    if (method_exists($this, $task)) {
      check_admin_referer('nonce_wd', 'nonce_wd');
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  public function display() { 
    require_once WDS()->plugin_dir . "/admin/models/WDSModelUninstall_wds.php";
    $model = new WDSModelUninstall_wds();

    require_once WDS()->plugin_dir . "/admin/views/WDSViewUninstall_wds.php";
    $view = new WDSViewUninstall_wds($model);
    $view->display();
  }

  public function uninstall() { 
    require_once WDS()->plugin_dir . "/admin/models/WDSModelUninstall_wds.php";
    $model = new WDSModelUninstall_wds();

    require_once WDS()->plugin_dir . "/admin/views/WDSViewUninstall_wds.php";
    $view = new WDSViewUninstall_wds($model);
    $view->uninstall();
  }
}
