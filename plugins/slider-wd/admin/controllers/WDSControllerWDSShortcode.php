<?php

class WDSControllerWDSShortcode {

  public function __construct() {
  }

  public function execute() {
    $this->display();
  }

  public function display() {
    require_once WDS()->plugin_dir . "/admin/models/WDSModelWDSShortcode.php";
    $model = new WDSModelWDSShortcode();

    require_once WDS()->plugin_dir . "/admin/views/WDSViewWDSShortcode.php";
    $view = new WDSViewWDSShortcode($model);
    $view->display();
  }
}