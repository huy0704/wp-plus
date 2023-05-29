<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;

class NumberSlider extends Number {

    protected $step = 1;

    protected $sliderMax;

    protected function fetchElement() {
        $html = parent::fetchElement();

        Js::addInline('new _N2.FormElementNumberSlider("' . $this->fieldID . '", ' . json_encode(array(
                'min'   => floatval($this->min),
                'max'   => floatval($this->sliderMax),
                'step'  => floatval($this->step),
                'units' => $this->units
            )) . ');');

        return $html;
    }

    /**
     * @param int $step
     */
    public function setStep($step) {
        $this->step = $step;
    }

    /**
     * @param int $sliderMax
     */
    public function setSliderMax($sliderMax) {
        $this->sliderMax = $sliderMax;
    }

    /**
     * @param int $max
     */
    public function setMax($max) {
        parent::setMax($max);

        if ($this->sliderMax === null) {
            $this->sliderMax = $max;
        }
    }
}