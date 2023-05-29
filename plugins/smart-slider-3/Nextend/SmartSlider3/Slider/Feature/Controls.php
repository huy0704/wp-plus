<?php


namespace Nextend\SmartSlider3\Slider\Feature;


class Controls {

    private $slider;

    private $mousewheel = 0;

    public $drag = 0;

    public $touch = 1;

    public $keyboard = 0;

    public $blockCarouselInteraction = 1;

    public function __construct($slider) {

        $this->slider = $slider;

        $this->mousewheel               = intval($slider->params->get('controlsScroll', 0));
        $this->keyboard                 = intval($slider->params->get('controlsKeyboard', 1));
        $this->blockCarouselInteraction = intval($slider->params->get('controlsBlockCarouselInteraction', 1));
        $this->touch                    = $slider->params->get('controlsTouch', 'horizontal');
        if ($slider->getSlidesCount() < 2) {
            $this->touch = 0;
        }
    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['controls'] = array(
            'mousewheel'               => $this->mousewheel,
            'touch'                    => $this->touch,
            'keyboard'                 => $this->keyboard,
            'blockCarouselInteraction' => $this->blockCarouselInteraction
        );
    }
}