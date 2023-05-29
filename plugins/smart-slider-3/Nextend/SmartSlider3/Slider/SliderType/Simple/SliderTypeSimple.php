<?php


namespace Nextend\SmartSlider3\Slider\SliderType\Simple;

use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderType;

class SliderTypeSimple extends AbstractSliderType {

    public function getName() {
        return 'simple';
    }

    public function createFrontend($slider) {
        return new SliderTypeSimpleFrontend($slider);
    }

    public function createCss($slider) {
        return new SliderTypeSimpleCss($slider);
    }


    public function createAdmin() {
        return new SliderTypeSimpleAdmin($this);
    }

    public function export($export, $slider) {
        $export->addImage($slider['params']->get('background', ''));
        $export->addImage($slider['params']->get('backgroundVideoMp4', ''));
    }

    public function import($import, $slider) {

        $slider['params']->set('background', $import->fixImage($slider['params']->get('background', '')));
        $slider['params']->set('backgroundVideoMp4', $import->fixImage($slider['params']->get('backgroundVideoMp4', '')));
    }
}