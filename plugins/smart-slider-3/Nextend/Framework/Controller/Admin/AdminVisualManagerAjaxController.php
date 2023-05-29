<?php


namespace Nextend\Framework\Controller\Admin;

use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Visual\ModelVisual;

abstract class AdminVisualManagerAjaxController extends AdminAjaxController {

    protected $type = '';

    /**
     * @return ModelVisual
     */
    public abstract function getModel();

    public function actionCreateSet() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $name = Request::$REQUEST->getVar('name');
        $this->validateVariable(!empty($name), 'set name');

        $model = $this->getModel();
        if (($set = $model->createSet($name))) {
            $this->response->respond(array(
                'set' => $set
            ));
        }

        Notification::error(n2_('Unexpected error'));
        $this->response->error();
    }

    public function actionRenameSet() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $setId = Request::$REQUEST->getInt('setId');
        $this->validateVariable($setId > 0, 'set');

        $name = Request::$REQUEST->getVar('name');
        $this->validateVariable(!empty($name), 'set name');

        $model = $this->getModel();

        if (($set = $model->renameSet($setId, $name))) {
            $this->response->respond(array(
                'set' => $set
            ));
        }

        Notification::error(n2_('Set is not editable'));
        $this->response->error();
    }

    public function actionDeleteSet() {
        $this->validateToken();

        $this->validatePermission('smartslider_delete');

        $setId = Request::$REQUEST->getInt('setId');
        $this->validateVariable($setId > 0, 'set');

        $model = $this->getModel();

        if (($set = $model->deleteSet($setId))) {
            $this->response->respond(array(
                'set' => $set
            ));
        }

        Notification::error(n2_('Set is not editable'));
        $this->response->error();
    }

    public function actionLoadVisualsForSet() {
        $this->validateToken();


        $setId = Request::$REQUEST->getInt('setId');
        $this->validateVariable($setId > 0, 'set');

        $model   = $this->getModel();
        $visuals = $model->getVisuals($setId);
        if (is_array($visuals)) {
            $this->response->respond(array(
                'visuals' => $visuals
            ));
        }

        Notification::error(n2_('Unexpected error'));
        $this->response->error();
    }

    public function actionLoadSetByVisualId() {
        $this->validateToken();

        $visualId = Request::$REQUEST->getInt('visualId');
        $this->validateVariable($visualId > 0, 'visual');

        $model = $this->getModel();

        $set = $model->getSetByVisualId($visualId);

        if (is_array($set) && is_array($set['visuals'])) {
            $this->response->respond(array(
                'set' => $set
            ));
        }

        Notification::error(n2_('Visual do not exists'));
        $this->response->error();
    }

    public function actionAddVisual() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $setId = Request::$REQUEST->getInt('setId');
        $this->validateVariable($setId > 0, 'set');

        $model = $this->getModel();

        if (($visual = $model->addVisual($setId, Request::$REQUEST->getVar('value')))) {
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

        $visualId = Request::$REQUEST->getInt('visualId');
        $this->validateVariable($visualId > 0, 'visual');

        $model = $this->getModel();

        if (($visual = $model->deleteVisual($visualId))) {
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

        $visualId = Request::$REQUEST->getInt('visualId');
        $this->validateVariable($visualId > 0, 'visual');

        $model = $this->getModel();

        if (($visual = $model->changeVisual($visualId, Request::$REQUEST->getVar('value')))) {
            $this->response->respond(array(
                'visual' => $visual
            ));
        }

        Notification::error(n2_('Unexpected error'));
        $this->response->error();
    }

}