<?php

namespace Nextend\Framework\Font;

use Nextend\Framework\Controller\Admin\AdminVisualManagerAjaxController;

class ControllerAjaxFont extends AdminVisualManagerAjaxController {

    protected $type = 'font';

    public function getModel() {

        return new ModelFont($this);
    }
}