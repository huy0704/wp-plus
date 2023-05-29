<?php


namespace Nextend\SmartSlider3\Application\Admin\Slider;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;

class ControllerSlider extends AbstractControllerAdmin {

    protected $sliderID = 0;

    protected $sliderAliasOrID = 0;

    protected $groupID = 0;

    public function initialize() {
        parent::initialize();

        $this->sliderID = Request::$REQUEST->getInt('sliderid');
        $this->groupID  = Request::$REQUEST->getInt('groupID', 0);

        $this->setSliderIDFromAlias();
    }

    /**
     * @return int
     */
    public function getSliderID() {
        return $this->sliderID;
    }

    public function setSliderIDFromAlias() {
        $this->sliderAliasOrID = Request::$REQUEST->getVar('slideraliasorid');
        if (!empty($this->sliderAliasOrID)) {
            if (is_numeric($this->sliderAliasOrID)) {
                $this->sliderID = $this->sliderAliasOrID;
            } else {
                $slidersModel   = new ModelSliders($this);
                $slider         = $slidersModel->getByAlias($this->sliderAliasOrID);
                $this->sliderID = $slider['id'];
            }
        }
    }

    public function actionClearCache() {
        if ($this->validateToken()) {
            $slidersModel = new ModelSliders($this);
            $slider       = $slidersModel->get($this->sliderID);
            if ($this->validateDatabase($slider)) {

                $slidersModel->refreshCache($this->sliderID);
                Notification::success(n2_('Cache cleared.'));

                $groupData = $this->getGroupData($this->sliderID);

                $this->redirect($this->getUrlSliderEdit($this->sliderID, $groupData['group_id']));
            }
        }
    }

    public function actionEdit() {


        if ($this->validatePermission('smartslider_edit')) {

            $slidersModel = new ModelSliders($this);

            $slider = $slidersModel->get($this->sliderID);

            if (!$slider) {
                $this->redirectToSliders();
            }

            if ($slider['type'] == 'group') {
                Notification::error(n2_('Groups are only available in the Pro version.'));
                $this->redirectToSliders();
            

                if (N2SSPRO) {
                    $this->doAction('editGroup', array(
                        $slider
                    ));
                }  //N2SSPRO

            } else {

                $groupData = $this->getGroupData($this->sliderID);

                $view = new ViewSliderEdit($this);
                $view->setGroupData($groupData['group_id'], $groupData['title']);
                $view->setSlider($slider);
                $view->display();

            }
        }
    }

    public function actionSimpleEdit() {

        if ($this->validatePermission('smartslider_edit')) {

            $slidersModel = new ModelSliders($this);

            $slider = $slidersModel->get($this->sliderID);

            if (!$slider) {
                $this->redirectToSliders();
            }

            $groupData = $this->getGroupData($this->sliderID);

            if (Request::$POST->getInt('save') && $this->validateToken()) {
                $sliderData = new Data(Request::$POST->getVar('slider'));

                if ($sliderData->get('delete-slider') == 1) {
                    $slidersModel->trash($this->sliderID, $groupData['group_id']);
                    $this->redirectToSliders();
                } else {

                    $params = json_decode($slider['params'], true);

                    $params['aria-label'] = $sliderData->get('aria-label', '');

                    $slidersModel->saveSimple($this->sliderID, $sliderData->get('title'), $params);

                    $slidesModel = new ModelSlides($this);

                    $slides = Request::$POST->getVar('slide');

                    $ordering = array();
                    foreach ($slides as $slideID => $slide) {
                        $slideData = new Data($slide);
                        if ($slideData->get('delete-slide') == 1) {
                            $slidesModel->delete($slideID);
                        } else {

                            $ordering[$slideID] = $slideData->get('ordering');

                            $slideRow = $slidesModel->get($slideID);

                            $slideParamsData = new Data($slideRow['params']);

                            $linkV1 = $slideParamsData->get('link', '');
                            if (!empty($linkV1)) {
                                list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');
                                $slideParamsData->un_set('link');
                                $slideParamsData->set('href', $link);
                                $slideParamsData->set('href-target', $target);
                            }

                            $slideParamsData->set('href', $slideData->get('href'));
                            $slideParamsData->set('href-target', $slideData->get('href-target'));
                            $slideParamsData->set('thumbnailType', $slideData->get('thumbnailType'));
                            $slideParamsData->set('backgroundImage', $slideData->get('backgroundImage'));

                            $slidesModel->saveSimple($slideID, $slideData->get('title'), $slideData->get('description'), $slideParamsData->toArray());
                        }
                    }
                    asort($ordering, SORT_NUMERIC);

                    $slidesModel->order($this->sliderID, array_keys($ordering));


                    $this->redirect($this->getUrlSliderSimpleEdit($this->sliderID, $groupData['group_id']));
                }
            }

            $view = new ViewSliderSimpleEdit($this);
            $view->setGroupData($groupData['group_id'], $groupData['title']);
            $view->setSlider($slider);
            $view->display();
        }
    }

    public function actionSimpleEditAddSlide() {

        if ($this->validatePermission('smartslider_edit')) {

            $slidersModel = new ModelSliders($this);

            $slider = $slidersModel->get($this->sliderID);

            if (!$slider) {
                $this->redirectToSliders();
            }

            $groupData = $this->getGroupData($this->sliderID);

            if (Request::$POST->getInt('save') && $this->validateToken()) {

                $slidesModel = new ModelSlides($this);
                $slidesModel->createSimpleEditAdd(Request::$POST->getVar('slide'), $this->sliderID);

                $this->redirect($this->getUrlSliderSimpleEdit($this->sliderID, $groupData['group_id']));
            }

            $view = new ViewSliderSimpleEditAddSlide($this);
            $view->setGroupData($groupData['group_id'], $groupData['title']);
            $view->setSlider($slider);
            $view->display();
        }
    }

    public function actionTrash() {
        if ($this->validateToken() && $this->validatePermission('smartslider_delete')) {
            $slidersModel = new ModelSliders($this);
            $mode         = $slidersModel->trash($this->sliderID, $this->groupID);
            switch ($mode) {
                case 'trash':
                    Notification::success(n2_('Slider moved to the trash.'));
                    break;
                case 'unlink':
                    Notification::success(n2_('Slider removed from the group.'));
                    break;
            }

            if ($this->groupID > 0) {
                $this->redirect($this->getUrlSliderEdit($this->groupID));
            } else {
                $this->redirectToSliders();
            }
        }
    }

    public function actionDuplicate() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            $slidersModel = new ModelSliders($this);
            if (($sliderid = Request::$REQUEST->getInt('sliderid')) && $slidersModel->get($sliderid)) {
                $newSliderId = $slidersModel->duplicate($sliderid);
                if ($newSliderId) {
                    Notification::success(n2_('Slider duplicated.'));

                    $groupData = $this->getGroupData($newSliderId);

                    $this->redirect($this->getUrlSliderEdit($newSliderId, $groupData['group_id']));
                } else {
                    Notification::error(n2_('Database error'));
                }

            }
            $this->redirectToSliders();
        }
    }

    public function actionExport() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            $export = new ExportSlider($this, $this->sliderID);
            $export->create();
        }
    
    }

    public function actionExportHTML() {
        if ($this->validateToken() && $this->validatePermission('smartslider_edit')) {
            $export = new ExportSlider($this, $this->sliderID);
            $export->createHTML();
        }
    
    }
}