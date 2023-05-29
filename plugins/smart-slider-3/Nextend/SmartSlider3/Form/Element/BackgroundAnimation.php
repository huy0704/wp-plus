<?php


namespace Nextend\SmartSlider3\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\AbstractChooser;
use Nextend\SmartSlider3\Slider\SliderType\Simple\SliderTypeSimple;

class BackgroundAnimation extends AbstractChooser {


    protected function addScript() {

        Js::addStaticGroup(SliderTypeSimple::getAssetsPath() . '/dist/smartslider-backgroundanimation.min.js', 'smartslider-backgroundanimation');

        Js::addInline('new _N2.FormElementAnimationManager("' . $this->fieldID . '", "backgroundanimationManager");');
    }
}