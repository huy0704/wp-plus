<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\AddSlide;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockAddSlide extends AbstractBlock {

    use TraitAdminUrl;

    protected $groupID = 0;

    protected $sliderID = 0;

    public function display() {
        $this->renderTemplatePart('AddSlide');
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

    /**
     * @return int
     */
    public function getSliderID() {
        return $this->sliderID;
    }

    /**
     * @param int $sliderID
     */
    public function setSliderID($sliderID) {
        $this->sliderID = $sliderID;
    }

    public function getDynamicSlidesUrl() {

        return $this->getUrlGeneratorCreate($this->getSliderID(), $this->groupID);
    }

}