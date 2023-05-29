<?php


namespace Nextend\SmartSlider3\Application\Admin\Preview;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutEmpty;
use Nextend\SmartSlider3\SliderManager\SliderManager;

class ViewPreviewFull extends AbstractView {

    /** @var integer */
    protected $sliderID;

    /** @var array */
    protected $sliderData;

    /** @var array */
    protected $slidesData;

    /** @var array */
    protected $generatorData;

    public function display() {
        $this->layout = new LayoutEmpty($this);

        $this->layout->addContent($this->render('Full'));

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
     * @return string Return value is already escaped
     */
    public function renderSlider() {

        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, "C");

        $sliderManager = new SliderManager($this, $this->sliderID, true, array(
            'sliderData'    => $this->sliderData,
            'slidesData'    => $this->slidesData,
            'generatorData' => $this->generatorData
        ));
        $sliderManager->allowDisplayWhenEmpty();

        $sliderHTML = $sliderManager->render();

        setlocale(LC_NUMERIC, $locale);

        return $sliderHTML;
    }
}