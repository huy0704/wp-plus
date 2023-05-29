<?php


namespace Nextend\SmartSlider3\Application\Admin\Slides;


use Nextend\Framework\View\AbstractViewAjax;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideBox\BlockSlideBox;
use Nextend\SmartSlider3\Slider\Feature\Optimize;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class ViewAjaxSlideBox extends AbstractViewAjax {

    protected $groupID = 0;

    /** @var Slider */
    protected $slider;

    /** @var Slide */
    protected $slide;

    /** @var Optimize */
    protected $optimize;

    public function display() {

        return $this->render('AjaxSlideBox');
    }

    public function renderSlideBlock() {

        $blockSlideBox = new BlockSlideBox($this);

        $blockSlideBox->setGroupID($this->groupID);
        $blockSlideBox->setSlider($this->slider);
        $blockSlideBox->setSlide($this->slide);
        $blockSlideBox->setOptimize($this->optimize);

        $blockSlideBox->display();
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

    /**
     * @return Slider
     */
    public function getSlider() {
        return $this->slider;
    }

    /**
     * @param Slider $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @return Slide
     */
    public function getSlide() {
        return $this->slide;
    }

    /**
     * @param Slide $slide
     */
    public function setSlide($slide) {
        $this->slide = $slide;
    }

    /**
     * @return Optimize
     */
    public function getOptimize() {
        return $this->optimize;
    }

    /**
     * @param Optimize $optimize
     */
    public function setOptimize($optimize) {
        $this->optimize = $optimize;
    }
}