<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideBox;


use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Slider\Feature\Optimize;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class BlockSlideBox extends AbstractBlock {

    use TraitAdminUrl;

    protected $groupID = 0;

    /** @var Slider */
    protected $slider;

    /** @var Slide */
    protected $slide;

    /** @var Optimize */
    protected $optimize;

    public function display() {

        $this->renderTemplatePart('SlideBox');
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

    /**
     * @param Slider $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @param Slide $slide
     */
    public function setSlide($slide) {
        $this->slide = $slide;
    }

    /**
     * @param Optimize $optimize
     */
    public function setOptimize($optimize) {
        $this->optimize = $optimize;
    }

    public function getSlideId() {

        return $this->slide->id;
    }

    public function getSlideTitle() {

        return $this->slide->getTitle(true);
    }

    public function getEditUrl() {

        return $this->getUrlSlideEdit($this->slide->id, $this->slider->sliderId, $this->groupID);
    }

    public function getThumbnailOptimized() {
        $image = $this->slide->getThumbnailDynamic();
        if (empty($image)) {
            $image = ResourceTranslator::toUrl('$ss3-frontend$/images/placeholder/image.png');
        }

        return $this->optimize->adminOptimizeThumbnail($image);
    }

    public function getPublishUrl() {

        return $this->getUrlSlidePublish($this->slide->id, $this->slider->sliderId, $this->groupID);
    }

    public function getUnPublishUrl() {

        return $this->getUrlSlideUnPublish($this->slide->id, $this->slider->sliderId, $this->groupID);
    }

    public function getClasses() {
        $classes = array();

        if ($this->slide->isStatic()) {
            $classes[] = 'n2_slide_box--static-overlay';
        }

        if ($this->slide->isFirst()) {
            $classes[] = 'n2_slide_box--first-slide';
        }

        if ($this->slide->published) {
            $classes[] = 'n2_slide_box--published';
        }

        if ($this->slide->hasGenerator()) {
            $classes[] = 'n2_slide_box--has-generator';
        }

        if ($this->slide->isCurrentlyEdited()) {
            $classes[] = 'n2_slide_box--currently-edited';
        }

        return $classes;
    }

    public function isStaticSlide() {
        return !!$this->slide->parameters->get('static-slide', 0);
    }

    public function hasGenerator() {
        return $this->slide->hasGenerator();
    }

    public function getGeneratorLabel() {
        return $this->slide->getGeneratorLabel() . ' [' . $this->slide->getSlideStat() . ']';
    }

    public function getGeneratorAttributeUrl() {
        return $this->getUrlGeneratorEdit($this->slide->generator_id, $this->groupID) . '"';
    }

    public function getHiddenDeviceText() {
        $hiddenViews = array();
        if (!$this->slide->isVisibleDesktopPortrait()) {
            $hiddenViews[] = n2_('Desktop');
        }
        if (!$this->slide->isVisibleTabletPortrait()) {
            $hiddenViews[] = n2_('Tablet');
        }
        if (!$this->slide->isVisibleMobilePortrait()) {
            $hiddenViews[] = n2_('Mobile');
        }

        if (!empty($hiddenViews)) {
            return sprintf(n2_('This slide is hidden on the following devices: %s'), implode(', ', $hiddenViews));
        }

        return '';
    }
}