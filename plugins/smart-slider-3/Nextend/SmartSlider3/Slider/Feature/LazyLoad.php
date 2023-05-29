<?php


namespace Nextend\SmartSlider3\Slider\Feature;


#[\AllowDynamicProperties]
class LazyLoad {

    private $slider;

    public $isEnabled = 0, $neighborCount = 0, $layerImageOptimize = 0, $layerImageWidthNormal = 1400, $layerImageWidthTablet = 800, $layerImageWidthMobile = 425;

    public function __construct($slider) {

        $this->slider = $slider;

        $this->isEnabled     = intval($slider->params->get('imageload', 0));
        $this->neighborCount = intval($slider->params->get('imageloadNeighborSlides', 0));

        $this->layerImageOptimize = intval($slider->params->get('layer-image-optimize', 0)) && !$slider->isAdmin;
        if ($this->layerImageOptimize) {
            $this->layerImageWidthNormal = $slider->params->get('layer-image-width-normal', 1400);
            $this->layerImageWidthTablet = $slider->params->get('layer-image-width-tablet', 800);
            $this->layerImageWidthMobile = $slider->params->get('layer-image-width-mobile', 425);
        }

        $this->layerImageSizeBase64     = intval($slider->params->get('layer-image-base64', 0)) && !$slider->isAdmin;
        $this->layerImageSizeBase64Size = max(0, intval($slider->params->get('layer-image-base64-size', 5))) * 1024;

    }

    public function makeJavaScriptProperties(&$properties) {

        $properties['lazyLoad']         = $this->isEnabled;
        $properties['lazyLoadNeighbor'] = $this->neighborCount;
    }
}