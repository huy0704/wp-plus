<?php


namespace Nextend\SmartSlider3\Application\Admin\Preview;


use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\SmartSlider3Info;

class ControllerPreview extends AbstractControllerAdmin {

    private $sliderId = 0;

    public function initialize() {
        parent::initialize();

        $this->sliderId = Request::$REQUEST->getInt('sliderid');

        SmartSlider3Info::$forceDesktop = true;
    }

    public function actionIndex() {

        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {

            $view = new ViewPreviewIndex($this);

            $view->setSliderID($this->sliderId);


            $sliderData = Request::$POST->getVar('slider', false);
            if (!is_array($sliderData)) {
                $sliderData = false;
            }
            $view->setSliderData($sliderData);

            $view->display();

        } else {

            $this->permissionError();
        }
    }

    public function actionFull() {

        if ($this->validateToken()) {
            $view = new ViewPreviewFull($this);

            $view->setSliderData(json_decode(Request::$POST->getVar('sliderData', '[]'), true));
            $view->setSlidesData(json_decode(Request::$POST->getVar('slidesData', '[]'), true));
            $view->setGeneratorData(json_decode(Request::$POST->getVar('generatorData', '[]'), true));
            $view->setSliderID($this->sliderId);

            $view->display();
        } else {

            $this->permissionError();
        }
    }

    public function actionSlider() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {

            $view = new ViewPreviewIndex($this);
            $view->setIsIframe(true);
            $view->setSliderID($this->sliderId);


            $sliderData = Request::$POST->getVar('slider', false);
            if (!is_array($sliderData)) {
                $sliderData = false;
            }
            $view->setSliderData($sliderData);

            $view->display();

        } else {

            $this->permissionError();
        }
    }

    public function actionSlide() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            $slideId = Request::$REQUEST->getInt('slideId');
            if ($this->sliderId) {
                $slidesData  = array();
                $slidesModel = new ModelSlides($this);
                $slideData   = Request::$REQUEST->getVar('slide');
                if (!empty($slideData)) {
                    $slide           = $slidesModel->convertSlideDataToDatabaseRow(json_decode(Base64::decode($slideData), true));
                    $slide['slide']  = json_encode($slide['slide']);
                    $slide['params'] = json_encode($slide['params']);
                    if ($slideId) {
                        $slide['id']          = $slideId;
                        $slidesData[$slideId] = $slide;
                    }
                }

                $view = new ViewPreviewIndex($this);
                if (Request::$REQUEST->getVar('frame')) {
                    $view->setIsIframe(true);
                }
                $view->setSliderID($this->sliderId);
                $view->setSlidesData($slidesData);

                $view->display();
            }
        } else {

            $this->permissionError();
        }
    }

    public function actionGenerator() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            $generator_id = Request::$REQUEST->getInt('generator_id');

            $generatorModel = new ModelGenerator($this);
            $sliderID       = $generatorModel->getSliderId($generator_id);

            if ($sliderID) {
                $generatorData = array();

                $generatorData[$generator_id] = Request::$REQUEST->getVar('generator');


                $view = new ViewPreviewIndex($this);
                $view->setIsIframe(true);
                $view->setSliderID($sliderID);
                $view->setGeneratorData($generatorData);

                $view->display();
            }
        } else {

            $this->permissionError();
        }
    }

    private function permissionError() {

        $this->redirectToSliders();
    }
}