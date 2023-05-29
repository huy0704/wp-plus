<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Exception;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\ImageEdit;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class Optimize {

    private $slider;

    private $playWhenVisible = 1;

    private $playWhenVisibleAt = 0.5;

    private $backgroundImageWidthNormal = 1920, $quality = 70, $thumbnailWidth = 100, $thumbnailHeight = 60, $thumbnailQuality = 70;

    public function __construct($slider) {

        $this->slider = $slider;

        $this->playWhenVisible   = intval($slider->params->get('playWhenVisible', 1));
        $this->playWhenVisibleAt = max(0, min(100, intval($slider->params->get('playWhenVisibleAt', 50)))) / 100;

        $this->backgroundImageWidthNormal = intval($slider->params->get('optimize-slide-width-normal', 1920));
        $this->quality                    = intval($slider->params->get('optimize-quality', 70));

        $this->thumbnailWidth   = $slider->params->get('optimizeThumbnailWidth', 100);
        $this->thumbnailHeight  = $slider->params->get('optimizeThumbnailHeight', 60);
        $this->thumbnailQuality = $slider->params->get('optimize-thumbnail-quality', 70);


    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['playWhenVisible']   = $this->playWhenVisible;
        $properties['playWhenVisibleAt'] = $this->playWhenVisibleAt;
    }

    public function optimizeBackground($image, $x = 50, $y = 50) {
        try {
            $imageSize = FastImageSize::getSize($image);
            if ($imageSize) {
                $optimizeScale = $this->slider->params->get('optimize-scale', 0);

                $targetWidth  = $imageSize['width'];
                $targetHeight = $imageSize['height'];
                if ($optimizeScale && $targetWidth > $this->backgroundImageWidthNormal) {
                    $targetHeight = ceil($this->backgroundImageWidthNormal / $targetWidth * $targetHeight);
                    $targetWidth  = $this->backgroundImageWidthNormal;
                }

                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $targetWidth, $targetHeight, false, 'normal', 'ffffff', true, $this->quality, true, $x, $y);
            }

            return $image;

        } catch (Exception $e) {
            return $image;
        }
    }

    public function optimizeThumbnail($image) {
        if ($this->slider->params->get('optimize-thumbnail-scale', 0)) {
            try {
                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $this->thumbnailWidth, $this->thumbnailHeight, false, 'normal', 'ffffff', true, $this->thumbnailQuality, true);
            } catch (Exception $e) {

                return ResourceTranslator::toUrl($image);
            }
        }

        return ResourceTranslator::toUrl($image);
    }

    public function adminOptimizeThumbnail($image) {
        if ($this->slider->params->get('optimize-thumbnail-scale', 0)) {
            try {
                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $this->thumbnailWidth, $this->thumbnailHeight, true, 'normal', 'ffffff', true, $this->thumbnailQuality, true);
            } catch (Exception $e) {

                return ResourceTranslator::toUrl($image);
            }
        }

        return ResourceTranslator::toUrl($image);
    }


    public function optimizeImageWebP($src, $options) {

        $options = array_merge(array(
            'optimize'         => false,
            'quality'          => 70,
            'resize'           => false,
            'defaultWidth'     => 1920,
            'mediumWidth'      => 1200,
            'mediumHeight'     => 0,
            'smallWidth'       => 500,
            'smallHeight'      => 0,
            'focusX'           => 50,
            'focusY'           => 50,
            'compressOriginal' => false
        ), $options);
    }
}