<?php


namespace Nextend\SmartSlider3\Application\Frontend\Slider;


use Nextend\Framework\Controller\AbstractController;

class ControllerSlider extends AbstractController {

    public function actionDisplay($sliderID, $usage) {

        $view = new ViewDisplay($this);

        $view->setSliderIDorAlias($sliderID);
        $view->setUsage($usage);

        $view->display();
    }
}