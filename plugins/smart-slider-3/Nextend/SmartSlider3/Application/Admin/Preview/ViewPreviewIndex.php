<?php


namespace Nextend\SmartSlider3\Application\Admin\Preview;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutIframe;
use Nextend\SmartSlider3\Application\Admin\Preview\Block\PreviewToolbar\BlockPreviewToolbar;
use Nextend\SmartSlider3\Application\Admin\Settings\ViewSettingsGeneral;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\Slider\SliderParams;

class ViewPreviewIndex extends AbstractView {

    use TraitAdminUrl;

    /** @var integer */
    protected $sliderID;

    /** @var array */
    protected $sliderData = array();

    /** @var array */
    protected $slidesData = array();

    /** @var array */
    protected $generatorData = array();

    protected $isIframe = false;

    public function display() {
        $this->layout = new LayoutIframe($this);

        $this->layout->setLabel(n2_('Preview'));

        $blockPreviewToolbar = new BlockPreviewToolbar($this);
        $blockPreviewToolbar->setSliderID($this->sliderID);
        $this->layout->addAction($blockPreviewToolbar);

        if ($this->isIframe) {
            $buttonClose = new BlockButton($this);
            $buttonClose->addClass('n2_preview_slider__close');
            $buttonClose->setLabel(n2_('Close'));
            $buttonClose->setBig();
            $buttonClose->setGreyDark();
            $this->layout->addAction($buttonClose);
        }

        $this->layout->addContent($this->render('Index'));

        $this->layout->render();
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

    /**
     * @return array
     */
    public function getSliderData() {
        return $this->sliderData;
    }

    /**
     * @param array $sliderData
     */
    public function setSliderData($sliderData) {
        $this->sliderData = $sliderData;
    }

    public function getWidthCSS() {
        if ($this->sliderData) {
            $sliderParams = new SliderParams($this->sliderID, $this->sliderData['type'], $this->sliderData);
        } else {
            $model        = new ModelSliders($this);
            $slider       = $model->get($this->sliderID);
            $sliderParams = new SliderParams($this->sliderID, $slider['type'], $slider['params'], true);
        }

        if ($sliderParams->get('responsive-mode') == 'fullwidth' || $sliderParams->get('responsive-mode') == 'fullpage') {
            return '';
        }

        $minScreenWidth = $sliderParams->get('width');

        if (intval($sliderParams->get('responsive-breakpoint-tablet-landscape-enabled', 0))) {
            $useLocalBreakpoints = !$sliderParams->get('responsive-breakpoint-global', 0);

            $minScreenWidth = max($minScreenWidth, 1 + intval($useLocalBreakpoints ? $sliderParams->get('responsive-breakpoint-tablet-landscape', ViewSettingsGeneral::defaults['tablet-large-portrait']) : Settings::get('responsive-screen-width-tablet-landscape', ViewSettingsGeneral::defaults['tablet-large-portrait'])));
            $minScreenWidth = max($minScreenWidth, 1 + ($useLocalBreakpoints ? $sliderParams->get('responsive-breakpoint-tablet-landscape-landscape', ViewSettingsGeneral::defaults['tablet-large-landscape']) : Settings::get('responsive-screen-width-tablet-landscape-landscape', ViewSettingsGeneral::defaults['tablet-large-landscape'])));

        }
        if (intval($sliderParams->get('responsive-breakpoint-tablet-portrait-enabled', 0))) {
            $useLocalBreakpoints = !$sliderParams->get('responsive-breakpoint-global', 0);

            $minScreenWidth = max($minScreenWidth, 1 + intval($useLocalBreakpoints ? $sliderParams->get('responsive-breakpoint-tablet-portrait', ViewSettingsGeneral::defaults['tablet-portrait']) : Settings::get('responsive-screen-width-tablet-portrait', ViewSettingsGeneral::defaults['tablet-portrait'])));
            $minScreenWidth = max($minScreenWidth, 1 + intval($useLocalBreakpoints ? $sliderParams->get('responsive-breakpoint-tablet-portrait-landscape', ViewSettingsGeneral::defaults['tablet-landscape']) : Settings::get('responsive-screen-width-tablet-portrait-landscape', ViewSettingsGeneral::defaults['tablet-landscape'])));

        }

        return 'max-width:' . $minScreenWidth . 'px;';
    }

    /**
     * @return array
     */
    public function getSlidesData() {
        return $this->slidesData;
    }

    /**
     * @param array $slidesData
     */
    public function setSlidesData($slidesData) {
        $this->slidesData = $slidesData;
    }

    /**
     * @return array
     */
    public function getGeneratorData() {
        return $this->generatorData;
    }

    /**
     * @param array $generatorData
     */
    public function setGeneratorData($generatorData) {
        $this->generatorData = $generatorData;
    }

    /**
     * @return bool
     */
    public function isIframe() {
        return $this->isIframe;
    }

    /**
     * @param bool $isIframe
     */
    public function setIsIframe($isIframe) {
        $this->isIframe = $isIframe;
    }
}