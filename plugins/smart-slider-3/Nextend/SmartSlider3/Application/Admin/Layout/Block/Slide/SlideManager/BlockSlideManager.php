<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Slider\Slider;

class BlockSlideManager extends AbstractBlock {

    use TraitAdminUrl;

    protected $groupID = 0;

    /** @var  integer */
    protected $sliderID;

    protected $breadcrumbOpener = false;

    protected $classes = array(
        'n2_slide_manager'
    );

    /**
     * @return Slider
     */
    public function getSliderObject() {

        $sliderObj = new Slider($this, $this->sliderID, array(), true);
        $sliderObj->initSlider();

        return $sliderObj;
    }

    public function setGroupID($groupID) {

        $this->groupID = $groupID;
    }

    public function setSliderID($sliderID) {

        $this->sliderID = $sliderID;
    }

    public function display() {
        $this->renderTemplatePart('SlideManager');
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }

    public function getClass() {
        return implode(' ', $this->classes);
    }

    /**
     * @return bool
     */
    public function hasBreadcrumbOpener() {
        return $this->breadcrumbOpener;
    }

    /**
     * @param bool $breadcrumbOpener
     */
    public function setBreadcrumbOpener($breadcrumbOpener) {
        $this->breadcrumbOpener = $breadcrumbOpener;
    }

}