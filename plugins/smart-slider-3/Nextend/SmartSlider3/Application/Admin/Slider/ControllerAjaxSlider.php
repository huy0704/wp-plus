<?php


namespace Nextend\SmartSlider3\Application\Admin\Slider;


use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelLicense;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Slider\ResponsiveType\ResponsiveTypeFactory;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;
use Nextend\SmartSlider3\SmartSlider3Info;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;

class ControllerAjaxSlider extends AdminAjaxController {

    use TraitAdminUrl;

    public function actionRestore() {

        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $sliderID = Request::$REQUEST->getVar('slider');
        $this->validateVariable(!empty($sliderID), 'slider');

        $slidersModel = new ModelSliders($this);
        $slidersModel->restore($sliderID);


        Notification::success(n2_('Slider restored.'));

        $this->response->respond();
    }

    public function actionDeletePermanently() {

        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $sliderID = Request::$REQUEST->getVar('slider');
        $this->validateVariable(!empty($sliderID), 'slider');

        $slidersModel     = new ModelSliders($this);
        $deletedSliderIDs = $slidersModel->deletePermanently($sliderID);


        Notification::success(n2_('Slider permanently deleted.'));

        $this->response->respond(array(
            'sliderIDs' => $deletedSliderIDs
        ));
    }

    public function actionCreate() {

        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $slidersModel = new ModelSliders($this);

        $projectName = Request::$REQUEST->getVar('projectName');
        $this->validateVariable(!empty($projectName), 'projectName');

        $slider = array(
            'title'                     => $projectName,
            'width'                     => max(Request::$REQUEST->getInt('sliderWidth', 1200), 200),
            'height'                    => max(Request::$REQUEST->getInt('sliderHeight', 600), 100),
            'responsiveLimitSlideWidth' => 1
        );

        $projectType = Request::$REQUEST->getVar('projectType', 'slider');

        if ($projectType == 'block') {
            $slider['type'] = 'block';
        } else {

            switch (Request::$REQUEST->getVar('sliderType', 'simple')) {

                case 'carousel':
                    $slider['type']               = 'carousel';
                    $slider['maximum-pane-width'] = $slider['width'];
                    $slider['slide-width']        = max(Request::$REQUEST->getInt('slideWidth', 600), 200);
                    $slider['slide-height']       = max(Request::$REQUEST->getInt('slideHeight', 400), 100);

                    $slider['widget-bullet-enabled'] = 1;
                    $slider['widgetbullet']          = 'transitionRectangle';

                    $slider['widget-arrow-enabled'] = 1;
                    $slider['widgetarrow']          = 'imageEmpty';
                    break;

                case 'showcase':
                    $slider['type']         = 'showcase';
                    $slider['slide-width']  = max(Request::$REQUEST->getInt('slideWidth', 600), 200);
                    $slider['slide-height'] = max(Request::$REQUEST->getInt('slideHeight', 400), 100);

                    $slider['widget-bullet-enabled'] = 1;
                    $slider['widgetbullet']          = 'transitionRectangle';
                    break;

                case 'simple':
                default:
                    $slider['type'] = 'simple';

                    $slider['widget-arrow-enabled'] = 1;
                    $slider['widgetarrow']          = 'imageEmpty';
                    break;
            }
        }

        switch (Request::$REQUEST->getVar('responsiveMode', 'fullwidth')) {
            case 'fullpage':
                $slider['responsive-mode'] = 'fullpage';
                break;
            case 'boxed':
                $slider['responsive-mode'] = 'auto';
                break;
            case 'fullwidth':
            default:
                $slider['responsive-mode'] = 'fullwidth';
                break;
        }

        $groupID = Request::$REQUEST->getVar('groupID', 0);

        $sliderid = $slidersModel->create($slider, $groupID);


        Notification::success(n2_('Slider created.'));

        $this->response->redirect($this->getUrlSliderEdit($sliderid, $groupID));
    }

    public function actionRename() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $sliderId = Request::$REQUEST->getInt('sliderid');
        $this->validateVariable($sliderId > 0, 'Slider');

        $title = Request::$REQUEST->getVar('title');

        $slidersModel = new ModelSliders($this);
        $slidersModel->setTitle($sliderId, $title);

        Notification::success(n2_('Slider renamed.'));

        $this->response->respond();
    }

    public function actionEdit() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $slidersModel = new ModelSliders($this);

        $slider = $slidersModel->get(Request::$REQUEST->getInt('sliderid'));
        $this->validateDatabase($slider);

        $responseData = $slidersModel->save($slider['id'], Request::$REQUEST->getVar('slider'));
        if ($responseData !== false) {
            Notification::success(n2_('Slider saved.'));
            $this->response->respond($responseData);
        }
    }

    public function actionImportDemo() {
        $this->validateToken();
        $this->validatePermission('smartslider_edit');

        $key = 'http:' . Base64::decode(Request::$REQUEST->getVar('key'));
        if (strpos($key, 'http://smartslider3.com/') !== 0) {
            Notification::error(sprintf(n2_('Import url is not valid: %s'), $key));
            $this->response->error();
        }

        $posts  = array(
            'action'  => 'asset',
            'asset'   => $key,
            'version' => SmartSlider3Info::$version
        );
        $result = SmartSlider3Info::api($posts);

        if (!is_string($result)) {
            $hasError = SmartSlider3Info::hasApiError($result['status'], array(
                'key' => $key
            ));

            if ($hasError == 'dashboard') {
                $this->redirect($this->getUrlDashboard());
            } else if ($hasError !== false) {
                $this->response->error();
            }
        } else {

            $import = new ImportSlider($this);

            $groupID = Request::$REQUEST->getVar('groupID', 0);

            $sliderId = $import->import($result, $groupID, 'clone', 1, false);

            if ($sliderId !== false) {
                Notification::success(n2_('Slider imported.'));

                $this->response->redirect($this->getUrlSliderEdit($sliderId, $groupID));
            } else {
                Notification::error(n2_('Import error!'));
                $this->response->error();
            }
        }

        $this->response->respond();
    }


    public function actionDuplicate() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $sliderId = Request::$REQUEST->getInt('sliderid');
        $this->validateVariable($sliderId > 0, 'Slider');

        $slidersModel = new ModelSliders($this);
        $newSliderId  = $slidersModel->duplicate($sliderId, true);
        $slider       = $slidersModel->getWithThumbnail($newSliderId);

        $this->validateDatabase($slider);

        Notification::success(n2_('Slide duplicated.'));

        $view = new ViewAjaxSliderBox($this);
        $view->setSlider($slider);

        $this->response->respond(array(
            'html'        => $view->display(),
            'sliderCount' => $slidersModel->getSlidersCount('published', true)
        ));
    }

    public function actionChangeSliderType() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $sliderID = Request::$GET->getInt('sliderID');
        if ($sliderID > 0) {
            $targetSliderType = Request::$POST->getVar('targetSliderType');
            $availableTypes   = SliderTypeFactory::getAdminTypes();
            if (isset($availableTypes[$targetSliderType])) {
                $slidersModel = new ModelSliders($this);
                $slidersModel->changeSliderType($sliderID, $targetSliderType);

                $this->response->respond();
            } else {
                Notification::error(sprintf(n2_('%s slider type is not available.'), ucfirst($targetSliderType)));
                $this->response->error();
            }

        } else {
            Notification::error('Slider ID error: ' . $sliderID);
            $this->response->error();
        }
    }

    public function actionRenderResponsiveType() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $responsiveType = ResponsiveTypeFactory::getType(Request::$POST->getVar('value'))
                                               ->createAdmin();
        if ($responsiveType) {
            $values = Request::$REQUEST->getVar('values', array());

            $form = new Form($this->applicationType, 'slider');
            $form->loadArray($values);

            PageFlow::cleanOutputBuffers();
            ob_start();

            $responsiveType->renderFields($form->getContainer());
            $form->render();

            $scripts = AssetManager::generateAjaxJS();
            $html    = ob_get_clean();
            $this->response->respond(array(
                'html'    => $html,
                'scripts' => $scripts
            ));
        } else {

            Notification::error('Responsive type not found: ' . Request::$POST->getVar('value'));
            $this->response->error();
        }
    }

    public function actionRenderWidgetArrow() {

        $this->renderWidgetForm('arrow');
    }

    public function actionRenderWidgetAutoplay() {

        $this->renderWidgetForm('autoplay');
    }

    public function actionRenderWidgetBar() {

        $this->renderWidgetForm('bar');
    }

    public function actionRenderWidgetBullet() {

        $this->renderWidgetForm('bullet');
    }

    public function actionRenderWidgetFullscreen() {

        $this->renderWidgetForm('fullscreen');
    }

    public function actionRenderWidgetHtml() {

        $this->renderWidgetForm('html');
    }

    public function actionRenderWidgetIndicator() {

        $this->renderWidgetForm('indicator');
    }

    public function actionRenderWidgetShadow() {

        $this->renderWidgetForm('shadow');
    }

    public function actionRenderWidgetThumbnail() {

        $this->renderWidgetForm('thumbnail');
    }

    private function renderWidgetForm($type) {
        $this->validateToken();
        
        $this->validatePermission('smartslider_config');

        $group = WidgetGroupFactory::getGroup($type);

        $value  = Request::$POST->getVar('value');
        $widget = $group->getWidget($value);
        if ($widget) {
            $values = Request::$REQUEST->getVar('values', array());

            $form = new Form($this->applicationType, 'slider');

            $values = array_merge($widget->getDefaults(), $values);
            $form->loadArray($values);

            PageFlow::cleanOutputBuffers();
            ob_start();

            $widget->renderFields($form->getContainer());
            $form->render();

            $scripts = AssetManager::generateAjaxJS();
            $html    = ob_get_clean();
            $this->response->respond(array(
                'html'    => $html,
                'scripts' => $scripts
            ));
        } else {
            Notification::error('Not found: ' . $value);
            $this->response->error();
        }
    }
}