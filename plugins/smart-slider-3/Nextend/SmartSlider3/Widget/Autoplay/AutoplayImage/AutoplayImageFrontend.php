<?php

namespace Nextend\SmartSlider3\Widget\Autoplay\AutoplayImage;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Cast;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class AutoplayImageFrontend extends AbstractWidgetFrontend {

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

        if (!$params->get('autoplay', 0)) {
            return '';
        }

        $sizeAttributes = array();

        $html = '';

        $playImage = $params->get($this->key . 'play-image');
        $playValue = $params->get($this->key . 'play');
        $playColor = $params->get($this->key . 'play-color');

        if (empty($playImage)) {

            if ($playValue == -1) {
                $play = null;
            } else {
                $play = ResourceTranslator::pathToResource(self::getAssetsPath() . '/play/' . basename($playValue));
            }
        } else {
            $play = $playImage;
        }

        if ($params->get($this->key . 'mirror')) {
            $pauseColor = $playColor;

            if (!empty($playImage)) {
                $pause = $playImage;
            } else {
                $pause = ResourceTranslator::pathToResource(self::getAssetsPath() . '/pause/' . basename($playValue));
            }
        } else {
            $pause      = $params->get($this->key . 'pause-image');
            $pauseColor = $params->get($this->key . 'pause-color');

            if (empty($pause)) {
                $pauseValue = $params->get($this->key . 'pause');
                if ($pauseValue == -1) {
                    $pause = null;
                } else {
                    $pause = ResourceTranslator::pathToResource(self::getAssetsPath() . '/pause/' . basename($pauseValue));
                }
            }
        }

        $ext = pathinfo($play, PATHINFO_EXTENSION);
        if ($ext == 'svg' && ResourceTranslator::isResource($play)) {

            FastImageSize::initAttributes($playColor, $sizeAttributes);

            list($color, $opacity) = Color::colorToSVG($playColor);
            $play = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                    'fill="#FFF"',
                    'opacity="1"'
                ), array(
                    'fill="#' . $color . '"',
                    'opacity="' . $opacity . '"'
                ), Filesystem::readFile(ResourceTranslator::toPath($play))));
        } else {
            FastImageSize::initAttributes($play, $sizeAttributes);
            $play = ResourceTranslator::toUrl($play);
        }

        $ext = pathinfo($pause, PATHINFO_EXTENSION);
        if ($ext == 'svg' && ResourceTranslator::isResource($pause)) {
            list($color, $opacity) = Color::colorToSVG($pauseColor);
            $pause = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                    'fill="#FFF"',
                    'opacity="1"'
                ), array(
                    'fill="#' . $color . '"',
                    'opacity="' . $opacity . '"'
                ), Filesystem::readFile(ResourceTranslator::toPath($pause))));
        } else {
            $pause = ResourceTranslator::toUrl($pause);
        }

        if ($play && $pause) {

            $desktopWidth = $params->get('widget-autoplay-desktop-image-width');
            $tabletWidth  = $params->get('widget-autoplay-tablet-image-width');
            $mobileWidth  = $params->get('widget-autoplay-mobile-image-width');

            $slider->addDeviceCSS('all', 'div#' . $id . ' .nextend-autoplay img{width: ' . $desktopWidth . 'px}');
            if ($tabletWidth != $desktopWidth) {
                $slider->addDeviceCSS('tabletportrait', 'div#' . $id . ' .nextend-autoplay img{width: ' . $tabletWidth . 'px}');
                $slider->addDeviceCSS('tabletlandscape', 'div#' . $id . ' .nextend-autoplay img{width: ' . $tabletWidth . 'px}');
            }
            if ($mobileWidth != $desktopWidth) {
                $slider->addDeviceCSS('mobileportrait', 'div#' . $id . ' .nextend-autoplay img{width: ' . $mobileWidth . 'px}');
                $slider->addDeviceCSS('mobilelandscape', 'div#' . $id . ' .nextend-autoplay img{width: ' . $mobileWidth . 'px}');
            }

            $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
                "sliderid" => $slider->elementId
            ));

            Js::addStaticGroup(self::getAssetsPath() . '/dist/w-autoplay.min.js', 'w-autoplay');


            $displayAttributes = $this->getDisplayAttributes($params, $this->key, 1);

            $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading');

            $slider->features->addInitCallback('new _N2.SmartSliderWidgetAutoplayImage(this, ' . Cast::floatToString($params->get($this->key . 'responsive-desktop')) . ', ' . Cast::floatToString($params->get($this->key . 'responsive-tablet')) . ', ' . Cast::floatToString($params->get($this->key . 'responsive-mobile')) . ');');

            $slider->sliderType->addJSDependency('SmartSliderWidgetAutoplayImage');

            $html = Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
                'class'            => $styleClass . 'nextend-autoplay n2-ow-all nextend-autoplay-image',
                'role'             => 'button',
                'aria-label'       => n2_('Play autoplay'),
                'data-pause-label' => n2_('Pause autoplay'),
                'data-play-label'  => n2_('Play autoplay'),
                'tabindex'         => '0'
            )), Html::image($play, 'Play', $sizeAttributes + HTML::addExcludeLazyLoadAttributes(array(
                        'class' => 'nextend-autoplay-play'
                    ))) . Html::image($pause, 'Pause', $sizeAttributes + HTML::addExcludeLazyLoadAttributes(array(
                        'class' => 'nextend-autoplay-pause'
                    ))));
        }

        return $html;
    }
}