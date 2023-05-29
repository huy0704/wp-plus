<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\Framework\View\Html;

class Align {

    private $slider;

    public $align = 'normal';

    public function __construct($slider) {

        $this->slider = $slider;

        $this->align = $slider->params->get('align', 'normal');
    }

    public function renderSlider($sliderHTML, $maxWidth) {

        $htmlOptions = array(
            "id"     => $this->slider->elementId . '-align',
            "class"  => "n2-ss-align",
            "encode" => false
        );

        $htmlOptionsPadding = array(
            "class" => 'n2-padding'
        );

        if (!$this->slider->features->responsive->scaleUp && $this->align != 'normal') {
            switch ($this->align) {
                case 'left':
                case 'right':
                    $width                = $this->slider->assets->sizes['width'];
                    $htmlOptions["style"] = "float: {$this->align}; width: {$width}px; max-width:100%;";
                    break;
                case 'center':
                    $htmlOptions["style"] = "margin: 0 auto; max-width: {$maxWidth}px;";
                    break;
            }
        }

        $sliderHTML = Html::tag("div", $htmlOptions, Html::tag("div", $htmlOptionsPadding, $sliderHTML));

        if ($this->slider->params->get('clear-both-after', 1)) {
            $sliderHTML .= Html::tag("div", array("class" => "n2_clear"), "");
        }

        return $sliderHTML;
    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['align']     = $this->align;
        $properties['isDelayed'] = intval($this->slider->params->get('is-delayed', 0));
    }
}