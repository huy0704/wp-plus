<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Asset\Builder\BuilderJs;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Slider\Slider;
use Nextend\SmartSlider3\Widget\SliderWidget;

abstract class AbstractSliderTypeFrontend {

    /**
     * @var Slider
     */
    protected $slider;

    protected $jsDependency = array(
        'documentReady',
        'smartslider-frontend'
    );

    protected $javaScriptProperties;

    /** @var  SliderWidget */
    protected $widgets;

    protected $shapeDividerAdded = false;

    protected $style = '';

    public function __construct($slider) {
        $this->slider = $slider;

        $this->enqueueAssets();
    }

    public function addJSDependency($dependency) {
        $this->jsDependency[] = $dependency;
    }

    protected $classes = array();

    public function addClass($className) {
        $this->classes[] = $className;
    }

    /**
     * @param AbstractSliderTypeCss $css
     *
     * @return string
     */
    public function render($css) {

        $this->javaScriptProperties = $this->slider->features->generateJSProperties();

        $this->widgets = new SliderWidget($this->slider);

        ob_start();
        $this->renderType($css);

        return ob_get_clean();
    }

    /**
     * @param AbstractSliderTypeCss $css
     *
     * @return string
     */
    protected abstract function renderType($css);

    protected function getSliderClasses() {

        return $this->slider->getAlias() . ' ' . implode(' ', $this->classes);
    }

    protected function openSliderElement() {

        $attributes = array(
            'id'              => $this->slider->elementId,
            'data-creator'    => 'Smart Slider 3',
            'data-responsive' => $this->slider->features->responsive->type,
            'class'           => 'n2-ss-slider n2-ow n2-has-hover n2notransition ' . $this->getSliderClasses(),
        );

        if ($this->slider->isLegacyFontScale()) {
            $attributes['data-ss-legacy-font-scale'] = 1;
        }

        return Html::openTag('div', $attributes);
    }

    protected function closeSliderElement() {

        return '</div>';
    }

    public function getDefaults() {
        return array();
    }

    /**
     * @param $params Data
     */
    public function limitParams($params) {

    }

    protected function encodeJavaScriptProperties() {

        $initCallback = implode($this->javaScriptProperties['initCallbacks']);
        unset($this->javaScriptProperties['initCallbacks']);

        $encoded = array();
        foreach ($this->javaScriptProperties as $k => $v) {
            $encoded[] = '"' . $k . '":' . json_encode($v);
        }
        $encoded[] = '"initCallbacks":function(){' . $initCallback . '}';

        return '{' . implode(',', $encoded) . '}';
    }


    protected function initParticleJS() {
    }

    protected function renderShapeDividers() {
    }

    private function renderShapeDivider($side, $params) {
    }

    /**
     * @return string
     */
    public function getScript() {
        return '';
    }

    public function getStyle() {
        return $this->style;
    }

    public function setJavaScriptProperty($key, $value) {
        $this->javaScriptProperties[$key] = $value;
    }

    public function enqueueAssets() {

        Js::addStaticGroup(ApplicationTypeFrontend::getAssetsPath() . '/dist/smartslider-frontend.min.js', 'smartslider-frontend');
    }

    public function handleSliderMinHeight($minHeight) {

        $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slider-1{min-height:' . $minHeight . 'px;}');
    }

    public function displaySizeSVGs($css, $hasMaxWidth = false) {

        $attrs = array(
            'xmlns'               => "http://www.w3.org/2000/svg",
            'viewBox'             => '0 0 ' . $css->base['sliderWidth'] . ' ' . $css->base['sliderHeight'],
            'data-related-device' => "desktopPortrait",
            'class'               => "n2-ow n2-ss-preserve-size n2-ss-preserve-size--slider n2-ss-slide-limiter"
        );
        if ($hasMaxWidth) {
            $attrs['style'] = 'max-width:' . $css->base['sliderWidth'] . 'px';
        }

        $svgs = array(
            Html::tag('svg', $attrs, '')
        );

        foreach ($this->slider->features->responsive->sizes as $device => $size) {
            if ($device === 'desktopPortrait') continue;

            if ($size['customHeight'] && $size['width'] > 0 && $size['height'] > 0) {

                $attrs['viewBox']             = '0 0 ' . $size['width'] . ' ' . $size['height'];
                $attrs['data-related-device'] = $device;
                if ($hasMaxWidth) {
                    $attrs['style'] = 'max-width:' . $size['width'] . 'px';
                }

                $svgs[] = Html::tag('svg', $attrs, '');

                $styles = array(
                    'div#' . $this->slider->elementId . ' .n2-ss-preserve-size[data-related-device="desktopPortrait"] {display:none}',
                    'div#' . $this->slider->elementId . ' .n2-ss-preserve-size[data-related-device="' . $device . '"] {display:block}'
                );
                $this->slider->addDeviceCSS(strtolower($device), implode('', $styles));
            }

        }

        // PHPCS - Content already escaped
        echo implode('', $svgs);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    protected function initSliderBackground($selector) {

        $params = $this->slider->params;

        $backgroundImage = $params->get('background');
        $backgroundColor = $params->get('background-color', '');

        $sliderCSS2 = '';

        if (!empty($backgroundImage)) {
            $sliderCSS2 .= 'background-image: url(' . ResourceTranslator::toUrl($backgroundImage) . ');';
        }
        if (!empty($backgroundColor)) {
            $rgba = Color::hex2rgba($backgroundColor);
            if ($rgba[3] != 0) {
                $sliderCSS2 .= 'background-color:RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
            }
        }

        if (!empty($sliderCSS2)) {

            $this->slider->addCSS('div#' . $this->slider->elementId . ' ' . $selector . '{' . $sliderCSS2 . '}');
        }
    }

    protected function getBackgroundVideo($params) {
        return '';
    
    }
}