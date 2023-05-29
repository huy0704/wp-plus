<?php


namespace Nextend\SmartSlider3\Application\Admin\Slides;


use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\SmartSlider3Info;

class ControllerSlides extends AbstractControllerAdmin {

    public function initialize() {
        parent::initialize();

        SmartSlider3Info::$forceDesktop    = true;
        SmartSlider3Info::$forceAllDevices = true;
    }

    public function actionEdit() {
        if ($this->validatePermission('smartslider_edit')) {
            $slidersModel = new ModelSliders($this);

            $sliderID = Request::$REQUEST->getInt('sliderid');
            $slider   = $slidersModel->get($sliderID);

            if ($this->validateDatabase($slider, false)) {

                $slidesModel = new ModelSlides($this);

                $slideID = Request::$REQUEST->getInt('slideid');
                $slide   = $slidesModel->get($slideID);

                if ($slide) {

                    $groupData = $this->getGroupData($sliderID);

                    $view = new ViewSlidesEdit($this);
                    $view->setGroupData($groupData['group_id'], $groupData['title']);
                    $view->setSlider($slider);
                    $view->setSlide($slide);
                    $view->display();
                } else {

                    $this->redirect($this->getUrlDashboard());
                }
            } else {

                $this->redirect($this->getUrlDashboard());
            }
        }
    }

    public function actionDelete() {
        if ($this->validateToken() && $this->validatePermission('smartslider_delete')) {
            if ($slideId = Request::$REQUEST->getInt('slideid')) {
                $slidesModel = new ModelSlides($this);
                $slidesModel->delete($slideId);
            }

            $sliderId = Request::$REQUEST->getInt("sliderid");
            if ($sliderId) {
                $groupData = $this->getGroupData($sliderId);
                $this->redirect($this->getUrlSliderEdit($sliderId, $groupData['group_id']));
            }

            $this->redirect($this->getUrlDashboard());
        }
    }

    public function actionDuplicate() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            if ($slideId = Request::$REQUEST->getInt('slideid')) {
                $slidesModel = new ModelSlides($this);
                $newSlideId  = $slidesModel->copyTo($slideId);

                Notification::success(n2_('Slide duplicated.'));

                $sliderID = Request::$REQUEST->getInt("sliderid");

                $groupData = $this->getGroupData($sliderID);

                $this->redirect($this->getUrlSlideEdit($newSlideId, $sliderID, $groupData['group_id']));
            }

            $this->redirect($this->getUrlDashboard());
        }
    }
}