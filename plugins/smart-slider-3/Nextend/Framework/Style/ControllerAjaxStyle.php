<?php


namespace Nextend\Framework\Style;


use Nextend\Framework\Controller\Admin\AdminVisualManagerAjaxController;

class ControllerAjaxStyle extends AdminVisualManagerAjaxController {

    protected $type = 'style';

    public function getModel() {

        return new ModelStyle($this);
    }
}