<?php

namespace Nextend\SmartSlider3\Widget;

use Nextend\Framework\Data\Data;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\SmartSlider3\Slider\Slider;

abstract class AbstractWidgetFrontend {

    use GetAssetsPathTrait;

    /** @var SliderWidget */
    protected $sliderWidget;

    /**
     * @var Slider
     */
    protected $slider;

    /** @var AbstractWidget */
    protected $widget;

    protected $key;

    /**
     * @var Data
     */
    protected $params;

    /**
     * AbstractWidgetFrontend constructor.
     *
     * @param SliderWidget   $sliderWidget
     * @param AbstractWidget $widget
     */
    public function __construct($sliderWidget, $widget, $params) {
        $this->sliderWidget = $sliderWidget;
        $this->slider       = $sliderWidget->slider;
        $this->widget       = $widget;

        $this->params = $params;

        $this->key = $widget->getKey();
    }

    protected function addToPlacement($key, $renderCallback) {

        $params = $this->params;

        if ($params->get($key . 'mode') == 'simple') {

            $area  = intval($params->get($key . 'area'));
            $stack = intval($params->get($key . 'stack', 1));

            $this->sliderWidget->addToSimplePlacement($renderCallback, $this->translateArea($area), $stack, $params->get($key . 'offset', 0));
        } else {
            $horizontalSide     = $params->get($key . 'horizontal', 'left');
            $horizontalPosition = $params->get($key . 'horizontal-position', 0);
            $horizontalUnit     = $params->get($key . 'horizontal-unit', 'px');

            $verticalSide     = $params->get($key . 'vertical', 'top');
            $verticalPosition = $params->get($key . 'vertical-position', 0);
            $verticalUnit     = $params->get($key . 'vertical-unit', 'px');

            $this->sliderWidget->addToAdvancedPlacement($renderCallback, $horizontalSide, $horizontalPosition, $horizontalUnit, $verticalSide, $verticalPosition, $verticalUnit);
        }
    }

    protected function translateArea($area) {
        static $areas = array(
            1  => 'above',
            2  => 'absolute-left-top',
            3  => 'absolute-center-top',
            4  => 'absolute-right-top',
            5  => 'absolute-left',
            6  => 'absolute-left-center',
            7  => 'absolute-right-center',
            8  => 'absolute-right',
            9  => 'absolute-left-bottom',
            10 => 'absolute-center-bottom',
            11 => 'absolute-right-bottom',
            12 => 'below',
        );

        return $areas[$area];
    }

    public function getDefaults() {
        return $this->widget->getDefaults();
    }

    /**
     * @param Data    $params
     * @param string  $key
     * @param integer $showOnMobileDefault
     *
     * @return array
     */
    protected function getDisplayAttributes($params, $key, $showOnMobileDefault = 0) {

        $attributes = array(
            'class' => 'n2-ss-widget'
        );

        if (!$params->get($key . 'display-desktopportrait', 1)) {
            $attributes['data-hide-desktopportrait'] = 1;
        }

        if (!$params->get($key . 'display-tabletportrait', 1)) {
            $attributes['data-hide-tabletportrait'] = 1;
        }

        if (!$params->get($key . 'display-mobileportrait', $showOnMobileDefault)) {
            $attributes['data-hide-mobileportrait'] = 1;
        }

        if ($params->get($key . 'display-hover', 0)) {
            $attributes['class'] .= ' n2-ss-widget-display-hover';
        }


        $excludeSlides = $params->get($key . 'exclude-slides', '');
        if (!empty($excludeSlides)) {
            $attributes['data-exclude-slides'] = $excludeSlides;
        }

        return $attributes;
    }

    public static function getOrientationByPosition($mode, $area, $set = 'auto', $default = 'horizontal') {
        if ($mode == 'advanced') {
            if ($set == 'auto') {
                return $default;
            }

            return $set;
        }
        if ($set != 'auto') {
            return $set;
        }
        switch ($area) {
            case '5':
            case '6':
            case '7':
            case '8':
                return 'vertical';
                break;
        }

        return 'horizontal';
    }
}