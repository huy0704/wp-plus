<?php


namespace Nextend\SmartSlider3\Application\Admin\Visuals;


use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Style\ModelCss;

class ControllerAjaxCss extends AdminAjaxController {

    public function getModel() {
        return new ModelCss($this);
    }

    public function actionLoadVisuals() {
        $this->validateToken();


        $type = Request::$REQUEST->getCmd('type');
        $this->validateVariable(!empty($type), 'type');

        $model   = $this->getModel();
        $visuals = $model->getVisuals($type);
        if (is_array($visuals)) {
            $this->response->respond(array(
                'visuals' => $visuals
            ));
        }

        Notification::error(n2_('Unexpected error'));
        $this->response->error();
    }

    public function actionAddVisual() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $type = Request::$REQUEST->getCmd('type');
        $this->validateVariable(!empty($type), 'type');

        $model = $this->getModel();

        if (($visual = $model->addVisual($type, Request::$REQUEST->getVar('value')))) {
            $this->response->respond(array(
                'visual' => $visual
            ));
        }

        Notification::error(n2_('Not editable'));
        $this->response->error();
    }

    public function actionDeleteVisual() {
        $this->validateToken();

        $this->validatePermission('smartslider_delete');

        $type = Request::$REQUEST->getCmd('type');
        $this->validateVariable(!empty($type), 'type');

        $visualId = Request::$REQUEST->getInt('visualId');
        $this->validateVariable($visualId > 0, 'visual');

        $model = $this->getModel();

        if (($visual = $model->deleteVisual($type, $visualId))) {
            $this->response->respond(array(
                'visual' => $visual
            ));
        }

        Notification::error(n2_('Not editable'));
        $this->response->error();
    }

    public function actionChangeVisual() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $type = Request::$REQUEST->getCmd('type');
        $this->validateVariable(!empty($type), 'type');

        $visualId = Request::$REQUEST->getInt('visualId');
        $this->validateVariable($visualId > 0, 'visual');

        $model = $this->getModel();

        if (($visual = $model->changeVisual($type, $visualId, Request::$REQUEST->getVar('value')))) {
            $this->response->respond(array(
                'visual' => $visual
            ));
        }

        Notification::error(n2_('Unexpected error'));
        $this->response->error();
    }
}