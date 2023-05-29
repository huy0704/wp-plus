<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderBox;

use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockSliderBox extends AbstractBlock {

    use TraitAdminUrl;

    protected $groupID = 0;

    /** @var array */
    protected $slider;

    public function display() {

        $this->renderTemplatePart('SliderBox');
    }

    /**
     * @return array
     */
    public function getSlider() {
        return $this->slider;
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    public function getEditUrl() {

        return $this->getUrlSliderEdit($this->slider['id'], $this->groupID);
    }

    public function getSimpleEditUrl() {

        return $this->getUrlSliderSimpleEdit($this->slider['id'], $this->groupID);
    }

    public function isGroup() {
        return $this->slider['type'] == 'group';
    }

    public function getSliderTitle() {

        return $this->slider['title'];
    }

    public function getSliderID() {
        return $this->slider['id'];
    }

    public function hasSliderAlias() {
        return !empty($this->slider['alias']);
    }

    public function getSliderAlias() {
        return $this->slider['alias'];
    }

    public function getThumbnail() {

        $thumbnail = $this->slider['thumbnail'];
        if (empty($thumbnail)) {
            return '';
        } else {
            return ResourceTranslator::toUrl($thumbnail);
        }
    }

    public function isThumbnailEmpty() {
        return empty($this->slider['thumbnail']);
    }

    public function getChildrenCount() {
        if ($this->slider['slides'] > 0) {

            return $this->slider['slides'];
        }

        return 0;
    }

    /**
     * @return int
     */

    public function getOrdering() {
        return $this->slider['ordering'];
    }

    /**
     * @return int
     */
    public function getGroupID() {
        return $this->groupID;
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

}