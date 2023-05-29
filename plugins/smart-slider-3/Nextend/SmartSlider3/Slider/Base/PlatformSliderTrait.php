<?php


namespace Nextend\SmartSlider3\Slider\Base;

use Nextend\SmartSlider3\Slider;

trait PlatformSliderTrait {

    /**
     * @var PlatformSliderBase
     */
    private $platformSlider;

    public function initPlatformSlider() {
        $this->platformSlider = new Slider\WordPress\PlatformSlider();
    }

    public function addCMSFunctions($text) {

        return $this->platformSlider->addCMSFunctions($text);
    }
}