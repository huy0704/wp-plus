<?php

namespace Nextend\SmartSlider3\Widget\Arrow\ArrowImage;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ArrowImageFrontend extends AbstractWidgetFrontend {

    protected $rendered = false;

    protected $previousArguments;
    protected $nextArguments;

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);


        if ($this->isRenderable('previous')) {
            $this->addToPlacement($this->key . 'previous-position-', array(
                $this,
                'renderPrevious'
            ));
        }

        if ($this->isRenderable('next')) {
            $this->addToPlacement($this->key . 'next-position-', array(
                $this,
                'renderNext'
            ));
        }
    }

    private function isRenderable($side) {
        $arrow = $this->params->get($this->key . $side . '-image');
        if (empty($arrow)) {
            $arrow = $this->params->get($this->key . $side);
            if ($arrow == -1) {
                $arrow = null;
            }
        }

        return !!$arrow;
    }

    public function renderPrevious($attributes = array()) {

        $this->render();

        if ($this->previousArguments) {

            array_unshift($this->previousArguments, $attributes);

            return call_user_func_array(array(
                $this,
                'getHTML'
            ), $this->previousArguments);
        }

        return '';
    }

    public function renderNext($attributes = array()) {

        $this->render();

        if ($this->nextArguments) {

            array_unshift($this->nextArguments, $attributes);

            return call_user_func_array(array(
                $this,
                'getHTML'
            ), $this->nextArguments);
        }

        return '';
    }

    private function render() {

        if ($this->rendered) return;

        $this->rendered = true;

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        if ($slider->getSlidesCount() <= 1) {
            return '';
        }

        $previousImage      = $params->get($this->key . 'previous-image');
        $previousValue      = $params->get($this->key . 'previous');
        $previousColor      = $params->get($this->key . 'previous-color');
        $previousHover      = $params->get($this->key . 'previous-hover');
        $previousHoverColor = $params->get($this->key . 'previous-hover-color');

        if (empty($previousImage)) {

            if ($previousValue == -1) {
                $previous = false;
            } else {
                $previous = ResourceTranslator::pathToResource(self::getAssetsPath() . '/previous/' . basename($previousValue));
            }
        } else {
            $previous = $previousImage;
        }

        if ($params->get($this->key . 'mirror')) {
            $nextColor      = $previousColor;
            $nextHover      = $previousHover;
            $nextHoverColor = $previousHoverColor;

            if (empty($previousImage)) {
                if ($previousValue == -1) {
                    $next = false;
                } else {
                    $next = ResourceTranslator::pathToResource(self::getAssetsPath() . '/next/' . basename($previousValue));
                }
            } else {
                $next = $previousImage;
                $slider->addCSS('#' . $id . '-arrow-next' . '{transform: rotate(180deg);}');
            }
        } else {
            $next           = $params->get($this->key . 'next-image');
            $nextColor      = $params->get($this->key . 'next-color');
            $nextHover      = $params->get($this->key . 'next-hover');
            $nextHoverColor = $params->get($this->key . 'next-hover-color');

            if (empty($next)) {
                $nextValue = $params->get($this->key . 'next');
                if ($nextValue == -1) {
                    $next = false;
                } else {
                    $next = ResourceTranslator::pathToResource(self::getAssetsPath() . '/next/' . basename($nextValue));
                }
            }
        }
        if ($previous || $next) {

            $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
                "sliderid" => $slider->elementId
            ));


            Js::addStaticGroup(self::getAssetsPath() . '/dist/w-arrow-image.min.js', 'w-arrow-image');

            $displayAttributes = $this->getDisplayAttributes($params, $this->key);

            $animation = $params->get($this->key . 'animation');

            if ($animation == 'none' || $animation == 'fade') {
                $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading');
            } else {
                $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading-active');
            }

            if ($previous) {
                $this->previousArguments = array(
                    $id,
                    $animation,
                    'previous',
                    $previous,
                    $displayAttributes,
                    $styleClass,
                    $previousColor,
                    $previousHover,
                    $previousHoverColor
                );
            }

            if ($next) {
                $this->nextArguments = array(
                    $id,
                    $animation,
                    'next',
                    $next,
                    $displayAttributes,
                    $styleClass,
                    $nextColor,
                    $nextHover,
                    $nextHoverColor
                );
            }

            $desktopWidth = $params->get('widget-arrow-desktop-image-width');
            $tabletWidth  = $params->get('widget-arrow-tablet-image-width');
            $mobileWidth  = $params->get('widget-arrow-mobile-image-width');

            $slider->addDeviceCSS('all', 'div#' . $id . ' .nextend-arrow img{width: ' . $desktopWidth . 'px}');
            if ($tabletWidth != $desktopWidth) {
                $slider->addDeviceCSS('tabletportrait', 'div#' . $id . ' .nextend-arrow img{width: ' . $tabletWidth . 'px}');
                $slider->addDeviceCSS('tabletlandscape', 'div#' . $id . ' .nextend-arrow img{width: ' . $tabletWidth . 'px}');
            }
            if ($mobileWidth != $desktopWidth) {
                $slider->addDeviceCSS('mobileportrait', 'div#' . $id . ' .nextend-arrow img{width: ' . $mobileWidth . 'px}');
                $slider->addDeviceCSS('mobilelandscape', 'div#' . $id . ' .nextend-arrow img{width: ' . $mobileWidth . 'px}');
            }

            $slider->features->addInitCallback('new _N2.SmartSliderWidgetArrowImage(this);');
            $slider->sliderType->addJSDependency('SmartSliderWidgetArrowImage');
        }
    }

    /**
     * @param array  $attributes
     * @param string $id
     * @param string $animation
     * @param string $side
     * @param string $imageRaw
     * @param string $displayAttributes
     * @param string $styleClass
     * @param string $color
     * @param int    $hover
     * @param string $hoverColor
     *
     * @return string
     */
    private function getHTML($attributes, $id, $animation, $side, $imageRaw, $displayAttributes, $styleClass, $color = 'ffffffcc', $hover = 0, $hoverColor = 'ffffffcc') {

        $imageHover = null;

        $ext = pathinfo($imageRaw, PATHINFO_EXTENSION);

        /**
         * We can not colorize SVGs when base64 disabled.
         */
        if ($ext == 'svg' && ResourceTranslator::isResource($imageRaw) && $this->params->get($this->key . 'base64', 1)) {

            list($color, $opacity) = Color::colorToSVG($color);
            $content = Filesystem::readFile(ResourceTranslator::toPath($imageRaw));
            $image   = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                    'fill="#FFF"',
                    'opacity="1"'
                ), array(
                    'fill="#' . $color . '"',
                    'opacity="' . $opacity . '"'
                ), $content));

            if ($hover) {
                list($color, $opacity) = Color::colorToSVG($hoverColor);
                $imageHover = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                        'fill="#FFF"',
                        'opacity="1"'
                    ), array(
                        'fill="#' . $color . '"',
                        'opacity="' . $opacity . '"'
                    ), $content));
            }
        } else {
            $image = ResourceTranslator::toUrl($imageRaw);
        }

        $alt = $this->params->get($this->key . $side . '-alt', $side . ' arrow');


        $sizeAttributes = array();
        FastImageSize::initAttributes($imageRaw, $sizeAttributes);

        if ($imageHover === null) {
            $image = Html::image($image, $alt, $sizeAttributes + Html::addExcludeLazyLoadAttributes());
        } else {
            $image = Html::image($image, $alt, $sizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                        'class' => 'n2-arrow-normal-img'
                    ))) . Html::image($imageHover, $alt, $sizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                        'class' => 'n2-arrow-hover-img'
                    )));
        }

        if ($animation == 'none' || $animation == 'fade') {
            return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
                'id'         => $id . '-arrow-' . $side,
                'class'      => $styleClass . 'nextend-arrow n2-ow-all nextend-arrow-' . $side . '  nextend-arrow-animated-' . $animation,
                'role'       => 'button',
                'aria-label' => $alt,
                'tabindex'   => '0'
            )), $image);
        }


        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            'id'         => $id . '-arrow-' . $side,
            'class'      => 'nextend-arrow nextend-arrow-animated n2-ow-all nextend-arrow-animated-' . $animation . ' nextend-arrow-' . $side,
            'role'       => 'button',
            'aria-label' => $alt,
            'tabindex'   => '0'
        )), Html::tag('div', array(
                'class' => $styleClass
            ), $image) . Html::tag('div', array(
                'class' => $styleClass . ' n2-active'
            ), $image));
    }
}