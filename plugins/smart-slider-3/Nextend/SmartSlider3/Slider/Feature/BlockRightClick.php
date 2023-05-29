<?php


namespace Nextend\SmartSlider3\Slider\Feature;


class BlockRightClick {

    private $slider;

    public $isEnabled = 0;

    public function __construct($slider) {

        $this->slider = $slider;

        $this->isEnabled = intval($slider->params->get('blockrightclick', 0));
    }

    public function makeJavaScriptProperties(&$properties) {

        $properties['blockrightclick'] = $this->isEnabled;
    }
}