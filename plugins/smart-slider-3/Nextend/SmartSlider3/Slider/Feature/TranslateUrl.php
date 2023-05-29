<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\Framework\Parser\Common;
use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Settings;

class TranslateUrl {

    private $slider;

    public $from = '';

    public $to = '';

    public function __construct($slider) {

        $this->slider = $slider;
        list($this->from, $this->to) = (array)Common::parse(esc_attr(Settings::get('translate-url', '||')));
    }

    public function replaceUrl($string) {

        if (!$this->slider->isAdmin && !empty($this->from) && !empty($this->to)) {
            return str_replace($this->from, $this->to, $string);
        }

        return $string;
    }
}