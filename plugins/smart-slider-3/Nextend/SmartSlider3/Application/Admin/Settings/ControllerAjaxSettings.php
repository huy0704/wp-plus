<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Cache\AbstractCache;
use Nextend\Framework\Cache\CacheImage;
use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Settings;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSettings;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class ControllerAjaxSettings extends AdminAjaxController {

    use TraitAdminUrl;

    public function actionDefault() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $settingsModel = new ModelSettings($this);
        if ($settingsModel->save()) {
            $this->invalidateSliderCache();

            Notification::success(n2_('Saved and slider cache invalidated.'));
        }

        $this->response->redirect($this->getUrlSettingsDefault());
    }

    public function actionFramework() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $data = Request::$POST->getVar('global');
        if (is_array($data)) {
            Settings::setAll($data);
            $this->invalidateSliderCache();

            Notification::success(n2_('Saved and slider cache invalidated.'));
        }

        $this->response->redirect($this->getUrlSettingsFramework());
    }

    public function actionFonts() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $fonts = Request::$REQUEST->getVar('fonts', false);

        if ($fonts) {
            FontSettings::store($fonts);

            $this->invalidateSliderCache();

            Notification::success(n2_('Saved and slider cache invalidated.'));
        }

        $this->response->redirect($this->getUrlSettingsFonts());
    }

    public function actionItemDefaults() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $settingsModel = new ModelSettings($this);
        $settingsModel->saveDefaults(Request::$REQUEST->getVar('defaults', array()));

        $this->response->redirect($this->getUrlSettingsItemDefaults());
    }

    public function actionGeneratorConfigure() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $group = Request::$REQUEST->getVar('group');
        $this->validateVariable($group, 'group');

        $generatorModel = new ModelGenerator($this);

        $generatorGroup = $generatorModel->getGeneratorGroup($group);

        $configuration = $generatorGroup->getConfiguration();
        $configuration->addData(Request::$POST->getVar('generator'));

        $this->response->redirect($this->getUrlSettingsGenerator($generatorGroup->getName()));
    }

    public function actionDismissUpgradePro() {
        $this->validateToken();
        $storage = StorageSectionManager::getStorage('smartslider');
        $storage->set('free', 'upgrade-pro', 1);
        $this->response->respond();
    }

    public function actionRated() {
        $this->validateToken();
        $storage = StorageSectionManager::getStorage('smartslider');
        $storage->set('free', 'rated', 1);
        $this->response->respond();
    }

    public function actionDismissNewsletterSampleSliders() {
        $this->validateToken();

        $storage = StorageSectionManager::getStorage('smartslider');
        $storage->set('free', 'dismissNewsletterSampleSliders', 1);

        $this->response->respond();
    }

    public function actionDismissNewsletterDashboard() {
        $this->validateToken();

        $storage = StorageSectionManager::getStorage('smartslider');
        $storage->set('free', 'dismissNewsletterDashboard', 1);

        $this->response->respond();
    }

    public function actionSubscribed() {
        $this->validateToken();

        $storage = StorageSectionManager::getStorage('smartslider');
        $storage->set('free', 'subscribeOnImport', 1);

        $this->response->respond();
    }

    private function invalidateSliderCache() {

        $slidersModel = new ModelSliders($this);
        $slidersModel->invalidateCache();
    }

    public function actionClearCache() {

        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $formData = new Data(Request::$POST->getVar('clear_cache', array()));
        if ($formData->get('delete-image-cache')) {

            $imageCachePath = CacheImage::getStorage()
                                        ->getPath('slider/cache', '', 'image');
            if (Filesystem::existsFolder($imageCachePath) && Filesystem::is_writable($imageCachePath)) {
                Filesystem::deleteFolder($imageCachePath);
            }
        }

        $slidersModel = new ModelSliders($this);
        foreach ($slidersModel->_getAll() as $slider) {
            $slidersModel->refreshCache($slider['id']);
        }
        AbstractCache::clearGroup('n2-ss-0');
        AbstractCache::clearGroup('combined');
        AbstractCache::clearAll();
        Notification::success(n2_('Cache cleared.'));

        Request::redirect($this->getUrlSettingsDefault());
    }
}