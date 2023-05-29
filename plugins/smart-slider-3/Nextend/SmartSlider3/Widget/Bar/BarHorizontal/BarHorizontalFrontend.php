<?php


namespace Nextend\SmartSlider3\Widget\Bar\BarHorizontal;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class BarHorizontalFrontend extends AbstractWidgetFrontend {

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        if (intval($params->get($this->key . 'show-description'))) {
            $this->slider->exposeSlideData['description'] = true;
        }

        $this->addToPlacement($this->key . 'position-', array(
            $this,
            'render'
        ));
    }

    public function render($attributes = array()) {

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-bar-horizontal.min.js', 'w-bar-horizontal');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key, 1);

        $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'simple');

        $fontTitle       = $slider->addFont($params->get($this->key . 'font-title'), 'simple');
        $fontDescription = $slider->addFont($params->get($this->key . 'font-description'), 'simple');

        $style = 'text-align: ' . $params->get($this->key . 'align') . ';';

        $width = $params->get($this->key . 'width');
        if (is_numeric($width) || substr($width, -1) == '%' || substr($width, -2) == 'px') {
            $style .= 'width:' . $width . ';';
        }

        $innerStyle = '';
        if (!$params->get($this->key . 'full-width')) {
            $innerStyle = 'display: inline-block;';
        }

        $showTitle = intval($params->get($this->key . 'show-title'));

        $showDescription = intval($params->get($this->key . 'show-description'));

        $parameters = array(
            'area'            => intval($params->get($this->key . 'position-area')),
            'animate'         => intval($params->get($this->key . 'animate')),
            'showTitle'       => $showTitle,
            'fontTitle'       => $fontTitle,
            'slideCount'      => intval($params->get($this->key . 'slide-count', 0)),
            'showDescription' => $showDescription,
            'fontDescription' => $fontDescription,
            'separator'       => $params->get($this->key . 'separator')
        );

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetBarHorizontal(this, ' . json_encode($parameters) . ');');

        $slider->sliderType->addJSDependency('SmartSliderWidgetBarHorizontal');

        return Html::tag("div", Html::mergeAttributes($attributes, $displayAttributes, array(
            "class" => "nextend-bar nextend-bar-horizontal n2-ss-widget-hidden n2-ow-all",
            "style" => $style
        )), Html::tag("div", array(
            "class" => $styleClass,
            "style" => $innerStyle
        ), '<span class="' . $fontTitle . '">&nbsp;</span>'));
    }
}