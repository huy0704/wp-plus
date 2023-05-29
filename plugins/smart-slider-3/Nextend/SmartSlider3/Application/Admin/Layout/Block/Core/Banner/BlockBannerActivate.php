<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Banner;


use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class BlockBannerActivate extends BlockBanner {

    protected function init() {
        $this->setID('n2-ss-activate-license-banner');
        $this->setImage(ResourceTranslator::toUrl('$ss3-admin$/images/activate.svg'));
        $this->setTitle(n2_('Activate Smart Slider 3 Pro'));
        $this->setDescription(n2_('Activation is required to unlock all features!') . ' ' . n2_('Register Smart Slider 3 Pro on this domain to enable auto update, slider templates and slide library.'));
        $this->setButton(array(
            'title'   => n2_('Activate'),
            'onclick' => '_N2.License.get().startActivation();return false;'
        ));
    }
}