<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderTrash;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class BlockSliderTrash extends AbstractBlock {

    use TraitAdminUrl;

    /** @var array */
    protected $slider;

    public function display() {

        $options = array(
            'ajaxUrl'    => $this->getAjaxUrlSlidesCreate(),
            'previewUrl' => $this->getUrlPreviewIndex(0)
        );

        Js::addInline("new _N2.SlidersTrash(" . json_encode($options) . ");");


        $this->renderTemplatePart('SliderTrash');
    }

    public function getSliders() {

        $slidersModel = new ModelSliders($this);

        return $slidersModel->getAll('*', 'trash');
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
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
}