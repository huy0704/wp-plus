<?php

/**
 * Class WDSControllerposts
 */
class WDSControllerposts {
  public function execute() {
    $this->display();
  }

  /**
   * Display.
   */
  public function display() {
    require_once WDS()->plugin_dir . "/admin/models/posts.php";
    $model = new WDSModelposts();

    require_once WDS()->plugin_dir . "/admin/views/posts.php";
    $view = new WDSViewposts($model);
    $view->display();
  }
}
