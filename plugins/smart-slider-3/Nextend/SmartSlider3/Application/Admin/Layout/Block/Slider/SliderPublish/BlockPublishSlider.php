<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderPublish;

use Nextend\Framework\View\AbstractBlock;

class BlockPublishSlider extends AbstractBlock {

    /** @var int */
    protected $sliderID;

    /** @var string */
    protected $sliderAlias;

    public function display() {

        $this->renderTemplatePart('Common');
        $this->renderTemplatePart('WordPress');
    }

    /**
     * @return int
     */
    public function getSliderID() {
        return $this->sliderID;
    }

    /**
     * @return string
     */
    public function getSliderAlias() {
        return $this->sliderAlias;
    }

    /**
     * @param int $sliderID
     */
    public function setSliderID($sliderID) {
        $this->sliderID = $sliderID;
    }

    /**
     * @param string $sliderAlias
     */
    public function setSliderAlias($sliderAlias) {
        $this->sliderAlias = $sliderAlias;
    }


}