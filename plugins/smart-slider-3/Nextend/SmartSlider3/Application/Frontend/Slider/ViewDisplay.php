<?php


namespace Nextend\SmartSlider3\Application\Frontend\Slider;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\SliderManager\SliderManager;

class ViewDisplay extends AbstractView {

    /** @var string|integer */
    protected $sliderIDorAlias;

    /** @var string */
    protected $usage;

    public function display() {

        $this->getApplicationType()
             ->enqueueAssets();

        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, "C");

        $sliderManager = new SliderManager($this, $this->sliderIDorAlias, false);
        $sliderManager->setUsage($this->usage);

        // PHPCS - Content already escaped
        echo $sliderManager->render(true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        setlocale(LC_NUMERIC, $locale);
    }

    /**
     * @return string|integer
     */
    public function getSliderIDorAlias() {
        return $this->sliderIDorAlias;
    }

    /**
     * @param string|integer $sliderIDorAlias
     */
    public function setSliderIDorAlias($sliderIDorAlias) {
        $this->sliderIDorAlias = $sliderIDorAlias;
    }

    /**
     * @return string
     */
    public function getUsage() {
        return $this->usage;
    }

    /**
     * @param string $usage
     */
    public function setUsage($usage) {
        $this->usage = $usage;
    }


}