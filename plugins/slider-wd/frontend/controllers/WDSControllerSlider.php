<?php
class WDSControllerSlider {

  public function __construct() {
  }

  public function execute( $id = 0, $from_shortcode = 0, $wds = 0 ) {
    $this->display($id, $from_shortcode, $wds);
  }

  public function display( $id, $from_shortcode = 0, $wds = 0 ) {
    require_once WDS()->plugin_dir . "/frontend/models/WDSModelSlider.php";
    $model = new WDSModelSlider();

    require_once WDS()->plugin_dir . "/frontend/views/WDSViewSlider.php";
    $view = new WDSViewSlider($model);
    
    $view->display( $id, $from_shortcode, $wds );
  }
}