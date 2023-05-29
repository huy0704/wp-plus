<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\Slider\Slider;

class Focus {

    /**
     * @var Slider
     */
    private $slider;

    private $focusOffsetTop = '';

    private $focusOffsetBottom = '';


    public function __construct($slider) {

        $this->slider = $slider;
        $responsiveHeightOffsetValue = '#wpadminbar';

        $this->focusOffsetTop    = Settings::get('responsive-focus-top', $responsiveHeightOffsetValue);
        $this->focusOffsetBottom = Settings::get('responsive-focus-bottom', '');
    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['responsive']['focus'] = array(
            'offsetTop'    => $this->focusOffsetTop,
            'offsetBottom' => $this->focusOffsetBottom
        );

        $params = $this->slider->params;

        if ($params->get('responsive-mode') == 'fullpage') {
            if (!$params->has('responsive-focus') && $params->has('responsiveHeightOffset')) {
                $old = $params->get('responsiveHeightOffset');

                $oldDefault = '';
                $oldDefault = '#wpadminbar';
            

                if ($old !== $oldDefault) {
                    $params->set('responsive-focus', 1);
                    $params->set('responsive-focus-top', $old);
                }
            }

            if ($params->get('responsive-focus', 0)) {
                $properties['responsive']['focus'] = array(
                    'offsetTop'    => $params->get('responsive-focus-top', ''),
                    'offsetBottom' => $params->get('responsive-focus-bottom', '')
                );
            }
        }
    }
}