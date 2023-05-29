<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Exception;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Generator\GeneratorFactory;

class ControllerGenerator extends AbstractControllerAdmin {

    public function actionCreate() {
        if ($this->validatePermission('smartslider_edit')) {

            $sliderID     = Request::$REQUEST->getInt("sliderid", 0);
            $slidersModel = new ModelSliders($this);
            $slider       = $slidersModel->get($sliderID);
            if ($this->validateDatabase($slider)) {

                $groupData = $this->getGroupData($sliderID);

                $view = new ViewGeneratorCreateStep1Groups($this);
                $view->setGroupData($groupData['group_id'], $groupData['title']);
                $view->setSlider($slider);
                $view->display();
            }
        }
    }

    public function actionCreateStep2() {
        if ($this->validatePermission('smartslider_edit')) {

            $sliderID     = Request::$REQUEST->getInt("sliderid", 0);
            $slidersModel = new ModelSliders($this);
            $slider       = $slidersModel->get($sliderID);
            if ($this->validateDatabase($slider)) {

                $groupData = $this->getGroupData($sliderID);

                $generatorGroup = GeneratorFactory::getGenerator(Request::$REQUEST->getCmd('group'));
                if (!$generatorGroup) {
                    $this->redirect($this->getUrlGeneratorCreate($sliderID, $groupData['group_id']));
                }

                $sources = $generatorGroup->getSources();
                if (empty($sources)) {
                    Notification::error($generatorGroup->getError());
                    $this->redirect($this->getUrlGeneratorCreate($sliderID, $groupData['group_id']));
                }

                if (count($sources) == 1) {

                    /**
                     * There is only one source in this generator. Skip to the next step.
                     */
                    reset($sources);

                    $this->redirect($this->getUrlGeneratorCreateSettings($generatorGroup->getName(), $sources[key($sources)]->getName(), $sliderID, $groupData['group_id']));
                }

                $view = new ViewGeneratorCreateStep3Sources($this);
                $view->setGroupData($groupData['group_id'], $groupData['title']);
                $view->setSlider($slider);
                $view->setGeneratorGroup($generatorGroup);

                $view->display();
            }
        }
    }

    public function actionEdit() {
        if ($this->validatePermission('smartslider_edit')) {

            $generatorId = Request::$REQUEST->getInt('generator_id');

            $generatorModel = new ModelGenerator($this);
            $generator      = $generatorModel->get($generatorId);
            if ($this->validateDatabase($generator)) {

                Request::$REQUEST->set('group', $generator['group']);
                Request::$REQUEST->set('type', $generator['type']);

                $slidesModel = new ModelSlides($this);
                $slides      = $slidesModel->getAll(-1, 'OR generator_id = ' . $generator['id'] . '');
                if (count($slides) > 0) {
                    $slide = $slides[0];

                    Request::$REQUEST->set('sliderid', $slide['slider']);

                    $slidersModel = new ModelSliders($this);
                    $slider       = $slidersModel->get($slide['slider']);

                    $groupData = $this->getGroupData($slider['id']);

                    $group = $generator['group'];
                    $type  = $generator['type'];

                    $generatorGroup = $generatorModel->getGeneratorGroup($group);
                    if (!$generatorGroup) {
                        $this->redirect($this->getUrlSlideEdit($slide['id'], $slider['id'], $groupData['group_id']));
                    }

                    $generatorSource = $generatorGroup->getSource($type);
                    if (!$generatorSource) {
                        $this->redirect($this->getUrlSlideEdit($slide['id'], $slider['id'], $groupData['group_id']));
                    }

                    $view = new ViewGeneratorEdit($this);
                    $view->setGroupData($groupData['group_id'], $groupData['title']);
                    $view->setSlider($slider);
                    $view->setSlide($slide);
                    $view->setGenerator($generator);
                    $view->setGeneratorGroup($generatorGroup);
                    $view->setGeneratorSource($generatorSource);

                    $view->display();

                } else {
                    $this->redirect($this->getUrlDashboard());
                }
            } else {
                $this->redirect($this->getUrlDashboard());

            }
        }
    }

    public function actionCreateSettings() {
        if ($this->validatePermission('smartslider_edit')) {

            $slidersModel = new ModelSliders($this);
            $sliderID     = Request::$REQUEST->getInt('sliderid');

            if (!($slider = $slidersModel->get($sliderID))) {
                $this->redirectToSliders();
            }

            $groupData = $this->getGroupData($slider['id']);

            $generatorGroup = GeneratorFactory::getGenerator(Request::$REQUEST->getCmd('group'));
            $source         = $generatorGroup->getSource(Request::$REQUEST->getVar('type'));
            if ($source) {

                $view = new ViewGeneratorCreateStep4Settings($this);

                $view->setGroupData($groupData['group_id'], $groupData['title']);
                $view->setSlider($slider);
                $view->setGeneratorGroup($generatorGroup);
                $view->setGeneratorSource($source);

                $view->display();

            } else {

                $this->redirect($this->getUrlSliderEdit($slider['id'], $groupData['group_id']));
            }
        }
    }

    public function actionCheckConfiguration() {
        if ($this->validatePermission('smartslider_config') && $this->validatePermission('smartslider_edit')) {

            $group = Request::$REQUEST->getVar('group');

            $generatorGroup = GeneratorFactory::getGenerator($group);

            $configuration = $generatorGroup->getConfiguration();

            $slidersModel = new ModelSliders($this);
            $sliderID     = Request::$REQUEST->getInt('sliderid');
            if (!($slider = $slidersModel->get($sliderID))) {
                $this->redirectToSliders();
            }

            $groupData = $this->getGroupData($sliderID);

            if ($configuration->wellConfigured()) {
                $this->redirect($this->getUrlGeneratorCreateStep2($group, $sliderID, $groupData['group_id']));
            }

            $view = new ViewGeneratorCreateStep2Configure($this);
            $view->setGroupData($groupData['group_id'], $groupData['title']);
            $view->setSlider($slider);
            $view->setGeneratorGroup($generatorGroup);
            $view->setConfiguration($configuration);

            $view->display();


        }
    }

    public function actionFinishAuth() {
        if ($this->validatePermission('smartslider_config')) {

            $generatorModel = new ModelGenerator($this);

            $group = Request::$REQUEST->getVar('group');

            $generatorGroup = $generatorModel->getGeneratorGroup($group);

            $configuration = $generatorGroup->getConfiguration();
            $result        = $configuration->finishAuth($this);
            if ($result === true) {
                Notification::success(n2_('Authentication successful.'));
                echo '<script>window.opener.location.reload();self.close();</script>';
            } else {
                if ($result instanceof Exception) {
                    $message = $result->getMessage();
                } else {
                    $message = 'Something wrong with the credentials';
                }
                echo '<script>window.opener._N2.Notification.error("' . esc_html($message) . '");self.close();</script>';
            }
            PageFlow::exitApplication();
        }
    }
}