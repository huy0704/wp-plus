<?php


namespace Nextend\SmartSlider3\Application\Admin\Slides;


use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\Slider\Feature\Optimize;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class ControllerAjaxSlides extends AdminAjaxController {

    use TraitAdminUrl;

    public function actionEdit() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $groupID = Request::$REQUEST->getInt('groupID', 0);
        $this->validateVariable($groupID >= 0, 'groupID');

        $slidersModel = new ModelSliders($this);
        $sliderId     = Request::$REQUEST->getInt('sliderid');
        $slider       = $slidersModel->get($sliderId);

        $this->validateDatabase($slider);

        $slidesModel = new ModelSlides($this);
        $this->validateDatabase($slidesModel->get(Request::$REQUEST->getInt('slideid')));

        $response = array();

        $file = Request::$FILES->getVar('slide');
        if (Settings::get('slide-as-file', 0) && $file !== null) {
            $slide = Filesystem::readFile($file['tmp_name']);
        } else {
            $slide = Request::$REQUEST->getVar('slide');
        }

        $guides = Request::$REQUEST->getVar('guides');

        if ($slidesModel->save(Request::$REQUEST->getInt('slideid'), $slide, $guides)) {
            Notification::success(n2_('Slide saved.'));

            if (Request::$REQUEST->getInt('generatorStatic') == 1) {
                $slideCount = $slidesModel->convertDynamicSlideToSlides(Request::$REQUEST->getInt('slideid'));
                if ($slideCount) {
                    Notification::success(sprintf(n2_('%d static slides generated.'), $slideCount));

                    $this->response->redirect($this->getUrlSliderEdit($sliderId, $groupID));
                }
            }
        }
        $this->response->respond($response);
    }

    public function actionRename() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $slideID = Request::$REQUEST->getInt('slideid');
        $this->validateVariable($slideID > 0, 'Slide');

        $title = Request::$REQUEST->getVar('title');

        $slidersModel = new ModelSlides($this);
        $slidersModel->setTitle($slideID, $title);

        Notification::success(n2_('Slide renamed.'));

        $this->response->respond();
    }

    public function actionFirst() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $slideId = Request::$REQUEST->getInt('id');
        $this->validateVariable($slideId > 0, 'Slide id');

        $slidesModel = new ModelSlides($this);
        $slidesModel->first($slideId);
        Notification::success(n2_('First slide changed.'));

        $this->response->respond();
    }

    public function actionConvertToSlide() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $slideId = Request::$REQUEST->getInt('slideid');
        $this->validateVariable($slideId > 0, 'Slide id');

        $slidesModel = new ModelSlides($this);
        $slidesModel->convertToSlide($slideId);

        Notification::success(n2_('Static overlay converted to slide.'));

        $this->response->respond();
    }

    public function actionPublish() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $ids = array_map('intval', array_filter((array)Request::$REQUEST->getVar('slides'), 'is_numeric'));

        $this->validateVariable(count($ids), 'Slides');

        $slidesModel = new ModelSlides($this);
        foreach ($ids as $id) {
            if ($id > 0) {
                $slidesModel->publish($id);
            }
        }
        Notification::success(n2_('Slide published.'));
        $this->response->respond();
    }

    public function actionUnPublish() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $ids = array_map('intval', array_filter((array)Request::$REQUEST->getVar('slides'), 'is_numeric'));
        $this->validateVariable(count($ids), 'Slides');

        $slidesModel = new ModelSlides($this);
        foreach ($ids as $id) {
            if ($id > 0) {
                $slidesModel->unPublish($id);
            }
        }
        Notification::success(n2_('Slide unpublished.'));
        $this->response->respond();
    }

    public function actionOrder() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $sliderid = Request::$REQUEST->getInt('sliderid');
        $this->validateVariable($sliderid > 0, 'Slider');

        $slidesModel = new ModelSlides($this);

        $result = $slidesModel->order($sliderid, Request::$REQUEST->getVar('slideorder'));
        $this->validateDatabase($result);

        Notification::success(n2_('Slide order saved.'));
        $this->response->respond();
    }

    public function actionCopy() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $groupID = Request::$REQUEST->getInt('targetGroupID', 0);
        $this->validateVariable($groupID >= 0, 'targetGroupID');

        $slideId = Request::$REQUEST->getInt('slideid');
        $this->validateVariable($slideId > 0, 'Slide');

        $sliderID = Request::$REQUEST->getInt('targetSliderID');
        $this->validateVariable($sliderID > 0, 'Slider ID');

        $slidesModel = new ModelSlides($this);
        $newSlideId  = $slidesModel->copyTo($slideId, false, $sliderID);
        $slide       = $slidesModel->get($newSlideId);

        $this->validateDatabase($slide);

        Notification::success(n2_('Slide(s) copied.'));


        $this->response->redirect($this->getUrlSliderEdit($sliderID, $groupID));
    }

    public function actionCopySlides() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $groupID = Request::$REQUEST->getInt('targetGroupID', 0);
        $this->validateVariable($groupID >= 0, 'targetGroupID');

        $ids = array_map('intval', array_filter((array)Request::$REQUEST->getVar('slides'), 'is_numeric'));

        $this->validateVariable(count($ids), 'Slides');

        $sliderID = Request::$REQUEST->getInt('targetSliderID');
        $this->validateVariable($sliderID > 0, 'Slider ID');

        $slidesModel = new ModelSlides($this);
        foreach ($ids as $id) {
            $slidesModel->copyTo($id, false, $sliderID);
        }
        Notification::success(n2_('Slide(s) copied.'));

        $this->response->redirect($this->getUrlSliderEdit($sliderID, $groupID));
    }

    public function actionDuplicate() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $groupID = Request::$REQUEST->getInt('groupID');
        $this->validateVariable($groupID >= 0, 'groupID');

        $slideId = Request::$REQUEST->getInt('slideid');
        $this->validateVariable($slideId > 0, 'Slide');

        $slidesModel = new ModelSlides($this);
        $newSlideId  = $slidesModel->copyTo($slideId);
        $slide       = $slidesModel->get($newSlideId);

        $this->validateDatabase($slide);

        Notification::success(n2_('Slide duplicated.'));

        $sliderObj = new Slider($this, $slide['slider'], array(), true);
        $sliderObj->initSlider();
        $optimize = new Optimize($sliderObj);

        $slideObj = new Slide($sliderObj, $slide);
        $slideObj->initGenerator();
        $slideObj->fillSample();

        $view = new ViewAjaxSlideBox($this);
        $view->setGroupID($groupID);
        $view->setSlider($sliderObj);
        $view->setSlide($slideObj);
        $view->setOptimize($optimize);

        $this->response->respond($view->display());
    }


    public function actionDelete() {
        $this->validateToken();

        $this->validatePermission('smartslider_delete');

        $ids = array_map('intval', array_filter((array)Request::$REQUEST->getVar('slides'), 'is_numeric'));

        $this->validateVariable(count($ids), 'Slide');

        $slidesModel = new ModelSlides($this);
        foreach ($ids as $id) {
            if ($id > 0) {
                $slidesModel->delete($id);
            }
        }
        Notification::success(n2_('Slide deleted.'));
        $this->response->respond();
    }

    public function actionCreate() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $type = Request::$REQUEST->getVar('type');

        $groupID = Request::$REQUEST->getInt('groupID');
        $this->validateVariable($groupID >= 0, 'groupID');

        $sliderId = Request::$REQUEST->getInt('sliderid');
        $this->validateVariable($sliderId > 0, 'Slider');

        $slidesModel = new ModelSlides($this);

        $createdSlidesID = array();
        switch ($type) {
            case 'image':
                $images = json_decode(Base64::decode(Request::$REQUEST->getVar('images')), true);
                $this->validateVariable(count($images), 'Images');
                foreach ($images as $image) {
                    $createdSlidesID[] = $slidesModel->createQuickImage($image, $sliderId);
                }
                break;
            case 'empty-slide':
                $createdSlidesID[] = $slidesModel->createQuickEmptySlide($sliderId);
                break;
            case 'video':
                $video = json_decode(urldecode(Base64::decode(Request::$REQUEST->getVar('video'))), true);
                $this->validateVariable($video, 'Video');

                $createdSlidesID[] = $slidesModel->createQuickVideo($video, $sliderId);
                break;
            case 'post':
                $post = Request::$REQUEST->getVar('post');
                $this->validateVariable($post, 'Post');

                $createdSlidesID[] = $slidesModel->createQuickPost($post, $sliderId);
                break;
            case 'static-overlay':
                $createdSlidesID[] = $slidesModel->createQuickStaticOverlay($sliderId);
                break;
        }

        if (!empty($createdSlidesID)) {

            $sliderObj = new Slider($this, $sliderId, array());
            $sliderObj->initSlider();
            $optimize = new Optimize($sliderObj);

            $responseBody = '';
            foreach ($createdSlidesID as $slideID) {
                $slide = $slidesModel->get($slideID);

                $slideObj = new Slide($sliderObj, $slide);
                $slideObj->initGenerator();
                $slideObj->fillSample();

                $view = new ViewAjaxSlideBox($this);
                $view->setGroupID($groupID);
                $view->setSlider($sliderObj);
                $view->setSlide($slideObj);
                $view->setOptimize($optimize);

                $responseBody .= $view->display();
            }

            if (count($createdSlidesID) > 1) {
                Notification::success(n2_('Slides created.'));
            } else {
                Notification::success(n2_('Slide created.'));
            }

            $this->response->respond($responseBody);
        } else {

            Notification::error(n2_('Failed to create slides.'));
            $this->response->respond();
        }
    }
}