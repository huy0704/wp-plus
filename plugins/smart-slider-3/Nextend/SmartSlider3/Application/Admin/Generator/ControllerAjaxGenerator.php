<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Exception;
use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Helper\HelperSliderChanged;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSlides;

class ControllerAjaxGenerator extends AdminAjaxController {

    use TraitAdminUrl;

    public function actionCheckConfiguration() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $group = Request::$REQUEST->getVar('group');
        $this->validateVariable($group, 'group');

        $sliderID = Request::$REQUEST->getVar('sliderid');
        $this->validateVariable($sliderID, 'sliderid');

        $groupID = Request::$REQUEST->getInt('groupID');

        $generatorModel = new ModelGenerator($this);

        $generatorGroup = $generatorModel->getGeneratorGroup($group);

        $configuration = $generatorGroup->getConfiguration();
        $configuration->addData(Request::$POST->getVar('generator'));

        if ($configuration->wellConfigured()) {
            $this->redirect($this->getUrlGeneratorCreateStep2($group, $sliderID, $groupID));
        } else {
            $this->response->redirect($this->getUrlGeneratorCheckConfiguration($group, $sliderID, $groupID));
        }
    }

    public function actionCreateSettings() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $group = Request::$REQUEST->getVar('group');
        $this->validateVariable($group, 'group');

        $type = Request::$REQUEST->getVar('type');
        $this->validateVariable($type, 'type');

        $sliderID = Request::$REQUEST->getVar('sliderid');
        $this->validateVariable($sliderID, 'sliderid');

        $groupID = Request::$REQUEST->getInt('groupID');

        $generatorModel = new ModelGenerator($this);
        $result         = $generatorModel->createGenerator($sliderID, Request::$REQUEST->getVar('generator'));

        Notification::success(n2_('Generator created.'));

        $this->response->redirect($this->getUrlSlideEdit($result['slideId'], $sliderID, $groupID));
    }

    public function actionEdit() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $generatorId = Request::$REQUEST->getInt('generator_id');
        $this->validateVariable($generatorId, 'generatorId');

        $groupID = Request::$REQUEST->getInt('groupID');

        $generatorModel = new ModelGenerator($this);
        $generator      = $generatorModel->get($generatorId);
        $this->validateDatabase($generator);

        $slidesModel = new ModelSlides($this);
        $slides      = $slidesModel->getAll(-1, 'OR generator_id = ' . $generator['id'] . '');
        if (count($slides) > 0) {
            $slide = $slides[0];

            $request = new Data(Request::$REQUEST->getVar('generator'));

            $slideParams = new Data($slide['params'], true);
            $slideParams->set('record-slides', $request->get('record-slides', 1));
            $slidesModel->updateSlideParams($slide['id'], $slideParams->toArray());

            $request->un_set('record-slides');
            $generatorModel->save($generatorId, $request->toArray());

            $helper = new HelperSliderChanged($this);
            $helper->setSliderChanged($slide['slider'], 1);

            Notification::success(n2_('Generator updated and cache cleared.'));

            $this->response->respond();
        }
    }

    public function actionRecordsTable() {

        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $generatorID = Request::$REQUEST->getInt('generator_id');

        $generatorModel = new ModelGenerator($this);

        if ($generatorID > 0) {
            $generator = $generatorModel->get($generatorID);

            $this->validateDatabase($generator);
        } else {
            $info      = new Data(Request::$REQUEST->getVar('generator'));
            $generator = array(
                'group'  => $info->get('group'),
                'type'   => $info->get('type'),
                'params' => '{}'
            );
        }

        $generatorGroup = $generatorModel->getGeneratorGroup($generator['group']);

        if (!$generatorGroup) {
            Notification::notice(n2_('Generator group not found'));
            $this->response->error();
        }

        $generatorSource = $generatorGroup->getSource($generator['type']);

        if (!$generatorSource) {
            Notification::notice(n2_('Generator source not found'));
            $this->response->error();
        }

        $generator['params'] = new Data($generator['params'], true);

        $generator['params']->loadArray(Request::$REQUEST->getVar('generator'));

        $generatorSource->setData($generator['params']);

        $request = new Data(Request::$REQUEST->getVar('generator'));

        $group = max(intval($request->get('record-group', 1)), 1);

        $result = $generatorSource->getData(max($request->get('record-slides', 1), 1), max($request->get('record-start', 1), 1), $group);

        if (empty($result)) {
            Notification::notice(n2_('No records found for the filter'));
            $this->response->respond(null);

        }

        $view = new ViewAjaxGeneratorRecordsTable($this);
        $view->setRecordGroup($group);
        $view->setRecords($result);

        $this->response->respond($view->display());
    }

    public function actionGetAuthUrl() {
        $this->validateToken();
        $this->validatePermission('smartslider_config');
        $group = Request::$REQUEST->getVar('group');

        $generatorModel = new ModelGenerator($this);

        $generatorGroup = $generatorModel->getGeneratorGroup($group);

        try {
            $configuration = $generatorGroup->getConfiguration();
            $this->response->respond(array('authUrl' => $configuration->startAuth($this)));
        } catch (Exception $e) {
            Notification::error($e->getMessage());
            $this->response->error();
        }
    }

    public function actionGetRefresh() {
        $this->validateToken();
        $this->validatePermission('smartslider_config');
        $group = Request::$REQUEST->getVar('group');

        $generatorModel = new ModelGenerator($this);

        $generatorGroup = $generatorModel->getGeneratorGroup($group);

        try {
            $configuration = $generatorGroup->getConfiguration();
            $this->response->respond(array('authUrl' => $configuration->refreshToken($this)));
        } catch (Exception $e) {
            Notification::error($e->getMessage());
            $this->response->error();
        }
    }

    public function actionGetData() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $group = Request::$REQUEST->getVar('group');

        $generatorModel = new ModelGenerator($this);

        $generatorGroup = $generatorModel->getGeneratorGroup($group);

        try {
            $configuration = $generatorGroup->getConfiguration();
            $this->response->respond(call_user_func(array(
                $configuration,
                Request::$REQUEST->getCmd('method')
            )));
        } catch (Exception $e) {
            Notification::error($e->getMessage());
            $this->response->error();
        }
    }
}