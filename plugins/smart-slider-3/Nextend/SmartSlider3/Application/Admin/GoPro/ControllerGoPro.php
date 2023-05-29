<?php


namespace Nextend\SmartSlider3\Application\Admin\GoPro;

use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;

class ControllerGoPro extends AbstractControllerAdmin {

    public function actionIndex() {

        $view = new ViewGoProIndex($this);
        $view->display();

    }
}