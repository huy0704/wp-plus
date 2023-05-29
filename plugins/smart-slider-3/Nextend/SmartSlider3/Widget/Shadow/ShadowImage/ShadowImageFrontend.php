<?php


namespace Nextend\SmartSlider3\Widget\Shadow\ShadowImage;


use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ShadowImageFrontend extends AbstractWidgetFrontend {

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->addToPlacement($this->key . 'position-', array(
            $this,
            'render'
        ));
    }

    public function render($attributes = array()) {

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        $shadow = $params->get($this->key . 'shadow-image');
        if (empty($shadow)) {
            $shadow = $params->get($this->key . 'shadow');
            if ($shadow == -1) {
                $shadow = null;
            } else {
                $shadow = self::getAssetsUri() . '/shadow/' . basename($shadow);
            }
        }
        if (!$shadow) {
            return '';
        }

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        $displayAttributes = $this->getDisplayAttributes($params, $this->key);

        $slider->features->addInitCallback("new _N2.SmartSliderWidget(this, 'shadow', '.nextend-shadow');");

        $slider->sliderType->addJSDependency('SmartSliderWidget');

        $sizeAttributes = array();
        FastImageSize::initAttributes(ResourceTranslator::urlToResource($shadow), $sizeAttributes);

        return Html::tag('div', Html::mergeAttributes($displayAttributes, array(
            'class' => "nextend-shadow n2-ow-all"
        )), Html::image(ResourceTranslator::toUrl($shadow), 'Shadow', $sizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                'style'   => 'display: block; width:100%;max-width:none;',
                'class'   => 'nextend-shadow-image',
                'loading' => 'lazy'
            ))));
    }
}