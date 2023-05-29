<?php

namespace Nextend\SmartSlider3\BackgroundAnimation;

use Nextend\Framework\Controller\Admin\AdminVisualManagerAjaxController;

class ControllerAjaxBackgroundAnimation extends AdminVisualManagerAjaxController {

    protected $type = 'backgroundanimation';

    public function getModel() {

        return new ModelBackgroundAnimation($this);
    }
}