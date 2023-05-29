<?php


namespace Nextend\SmartSlider3\Application\Model;


use Nextend\Framework\Model\AbstractModel;
use Nextend\Framework\Model\Section;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Helper\HelperSliderChanged;
use Nextend\SmartSlider3\Settings;

class ModelSettings extends AbstractModel {

    public function save() {
        $namespace = Request::$REQUEST->getCmd('namespace', 'default');
        $settings  = Request::$REQUEST->getVar('settings');
        if ($namespace && $settings) {
            if ($namespace == 'default') $namespace = 'settings';
            if ($namespace == 'font' && Request::$REQUEST->getInt('sliderid')) {
                $namespace .= Request::$REQUEST->getInt('sliderid');

                $helper = new HelperSliderChanged($this);
                $helper->setSliderChanged(Request::$REQUEST->getInt('sliderid'), 1);
            }

            Settings::store($namespace, json_encode($settings));
        }

        return true;
    }

    public function saveDefaults($defaults) {
        if (!empty($defaults)) {
            foreach ($defaults as $referenceKey => $value) {
                Section::set('smartslider', 'default', $referenceKey, $value);
            }
        }

        return true;
    }
}