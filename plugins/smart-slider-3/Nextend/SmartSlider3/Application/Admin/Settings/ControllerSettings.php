<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Settings;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class ControllerSettings extends AbstractControllerAdmin {

    public function actionDefault() {

        if ($this->validatePermission('smartslider_config')) {

            $view = new ViewSettingsGeneral($this);
            $view->display();

        }
    }

    public function actionFramework() {
        if ($this->canDo('smartslider_config')) {

            $data = Request::$POST->getVar('global');
            if (is_array($data)) {
                if ($this->validateToken()) {
                    Settings::setAll($data);
                    $this->invalidateSliderCache();

                    Notification::success(n2_('Saved and slider cache invalidated.'));
                }

                $this->redirect($this->getUrlSettingsFramework());
            }


            $view = new ViewSettingsFramework($this);
            $view->display();

        }
    }

    public function actionFonts() {
        if ($this->canDo('smartslider_config')) {

            $view = new ViewSettingsFonts($this);
            $view->display();

        }
    }

    public function actionItemDefaults() {

        if ($this->validatePermission('smartslider_config')) {

            $view = new ViewSettingsItemDefaults($this);
            $view->display();

        }
    }

    public function actionGeneratorConfigure() {
        if ($this->validatePermission('smartslider_config')) {

            $view = new ViewGeneratorConfigure($this);

            $generatorModel = new ModelGenerator($this);

            $group = Request::$REQUEST->getVar('group');

            $generatorGroup = $generatorModel->getGeneratorGroup($group);

            $configuration = $generatorGroup->getConfiguration();

            $view->setGeneratorGroup($generatorGroup);
            $view->setConfiguration($configuration);

            $view->display();
        }
    }

    public function actionClearCache() {
        if ($this->validatePermission('smartslider_config')) {
            $view = new ViewSettingsClearCache($this);
            $view->display();
        }
    }

    private function invalidateSliderCache() {

        $slidersModel = new ModelSliders($this);
        $slidersModel->invalidateCache();
    }
}