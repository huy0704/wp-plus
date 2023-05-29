<?php


namespace Nextend\SmartSlider3\Form\Element;


use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderPublish\BlockPublishSlider;

class PublishSlider extends AbstractFieldHidden {

    protected $hasTooltip = false;

    protected function fetchElement() {
        ob_start();

        $blockPublishSlider = new BlockPublishSlider($this->getForm());
        $blockPublishSlider->setSliderID(Request::$GET->getInt('sliderid'));

        $sliderAliasOrID = Request::$GET->getVar('slideraliasorid');
        if (!empty($sliderAliasOrID)) {
            if (is_numeric($sliderAliasOrID)) {
                $blockPublishSlider->setSliderID($sliderAliasOrID);
            } else {
                $blockPublishSlider->setSliderAlias($sliderAliasOrID);
            }
        }

        $blockPublishSlider->display();

        return ob_get_clean();
    }
}