<?php


namespace Nextend\SmartSlider3\Widget\Thumbnail\Basic;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ThumbnailBasicFrontend extends AbstractWidgetFrontend {

    private static $thumbnailTypes = array(
        'videoDark' => '<svg class="n2-thumbnail-dot-type" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><circle cx="24" cy="24" r="24" fill="#000" opacity=".6"/><path fill="#FFF" d="M19.8 32c-.124 0-.247-.028-.36-.08-.264-.116-.436-.375-.44-.664V16.744c.005-.29.176-.55.44-.666.273-.126.592-.1.84.07l10.4 7.257c.2.132.32.355.32.595s-.12.463-.32.595l-10.4 7.256c-.14.1-.31.15-.48.15z"/></svg>'
    );

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

        $showThumbnail   = intval($params->get($this->key . 'show-image'));
        $showTitle       = intval($params->get($this->key . 'title'));
        $showDescription = intval($params->get($this->key . 'description'));

        if (!$showThumbnail && !$showTitle && !$showDescription) {
            // Nothing to show
            return '';
        }

        $parameters = array(
            'action'                => $params->get($this->key . 'action'),
            'minimumThumbnailCount' => max(1, intval($params->get($this->key . 'minimum-thumbnail-count')))
        );

        $displayAttributes = $this->getDisplayAttributes($params, $this->key);

        $barStyle   = $slider->addStyle($params->get($this->key . 'style-bar'), 'simple');
        $slideStyle = $slider->addStyle($params->get($this->key . 'style-slides'), 'dot');

        $width  = intval($slider->params->get($this->key . 'width', 100));
        $height = intval($slider->params->get($this->key . 'height', 60));

        $css = '';
        if ($showThumbnail) {
            $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot img{width:' . $width . 'px;height:' . $height . 'px}';
        } else {
            $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot{min-width:' . $width . 'px;min-height:' . $height . 'px}';
        }
        if (!empty($css)) {
            $this->slider->addDeviceCSS('all', $css);
        }

        $tabletWidth  = intval($slider->params->get($this->key . 'tablet-width', $width));
        $tabletHeight = intval($slider->params->get($this->key . 'tablet-height', $height));

        if ($tabletWidth !== $width || $tabletHeight !== $height) {

            $css = '';
            if ($showThumbnail) {
                $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot img{width:' . $tabletWidth . 'px;height:' . $tabletHeight . 'px}';
            } else {
                $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot{min-width:' . $tabletWidth . 'px;min-height:' . $tabletHeight . 'px}';
            }
            if (!empty($css)) {
                $this->slider->addDeviceCSS('tabletportrait', $css);
                $this->slider->addDeviceCSS('tabletlandscape', $css);
            }
        }

        $mobileWidth  = intval($slider->params->get($this->key . 'mobile-width', $width));
        $mobileHeight = intval($slider->params->get($this->key . 'mobile-height', $height));
        if ($mobileWidth !== $width || $mobileHeight !== $height) {

            $css = '';
            if ($showThumbnail) {
                $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot img{width:' . $mobileWidth . 'px;height:' . $mobileHeight . 'px}';
            } else {
                $css .= 'div#' . $this->slider->elementId . ' .n2-thumbnail-dot{min-width:' . $mobileWidth . 'px;min-height:' . $mobileHeight . 'px}';
            }
            if (!empty($css)) {
                $this->slider->addDeviceCSS('mobileportrait', $css);
                $this->slider->addDeviceCSS('mobilelandscape', $css);
            }
        }


        $captionPlacement = $slider->params->get($this->key . 'caption-placement', 'overlay');
        if (!$showThumbnail) {
            $captionPlacement = 'before';
        }

        if (!$showTitle && !$showDescription) {
            $captionPlacement = 'overlay';
        }

        $captionSize = intval($slider->params->get($this->key . 'caption-size', 100));


        $orientation = $params->get($this->key . 'orientation');
        $orientation = $this->getOrientationByPosition($params->get($this->key . 'position-mode'), $params->get($this->key . 'position-area'), $orientation, 'vertical');

        $captionExtraStyle = '';
        switch ($captionPlacement) {
            case 'before':
            case 'after':
                switch ($orientation) {
                    case 'vertical':
                        $captionExtraStyle .= "width: " . $captionSize . "px";
                        break;
                    default:
                        $captionExtraStyle .= "height: " . $captionSize . "px";
                }
                break;
        }


        if ($orientation == 'vertical') {

            Js::addStaticGroup(self::getAssetsPath() . '/dist/w-thumbnail-vertical.min.js', 'w-thumbnail-vertical');

            $slider->features->addInitCallback('new _N2.SmartSliderWidgetThumbnailDefaultVertical(this, ' . json_encode($parameters) . ');');
            $slider->sliderType->addJSDependency('SmartSliderWidgetThumbnailDefaultVertical');
        } else {

            Js::addStaticGroup(self::getAssetsPath() . '/dist/w-thumbnail-horizontal.min.js', 'w-thumbnail-horizontal');

            $slider->features->addInitCallback('new _N2.SmartSliderWidgetThumbnailDefaultHorizontal(this, ' . json_encode($parameters) . ');');
            $slider->sliderType->addJSDependency('SmartSliderWidgetThumbnailDefaultHorizontal');
        }

        $group = max(1, intval($params->get($this->key . 'group')));

        $style = '';

        $size = $params->get($this->key . 'size');
        if (is_numeric($size)) {
            $size .= '%';
        }
        if ($orientation == 'horizontal') {
            if (substr($size, -1) == '%' || substr($size, -2) == 'px') {
                $style .= 'width:' . $size . ';';
                if (substr($size, -1) == '%') {
                    $attributes['data-width-percent'] = substr($size, 0, -1);
                }
            }

            $scrollerStyle = 'grid-template-rows:repeat(' . $group . ', 1fr)';
        } else {
            if (substr($size, -1) == '%' || substr($size, -2) == 'px') {
                $style .= 'height:' . $size . ';';
            }

            $scrollerStyle = 'grid-template-columns:repeat(' . $group . ', 1fr)';
        }

        $previous = $next = '';

        $nextSizeAttributes     = array();
        $previousSizeAttributes = array();

        $showArrow = intval($slider->params->get($this->key . 'arrow', 1));
        if ($showArrow) {
            $arrowImagePrevious = $arrowImageNext = ResourceTranslator::toUrl($slider->params->get($this->key . 'arrow-image', ''));
            $arrowWidth         = intval($slider->params->get($this->key . 'arrow-width', 26));
            $commonStyle        = '';
            if (!empty($arrowWidth)) {
                $commonStyle = 'width:' . $arrowWidth . 'px;';
            }
            $previousStyle = $nextStyle = $commonStyle;
            if (empty($arrowImagePrevious)) {
                $image = self::getAssetsPath() . '/thumbnail-up-arrow.svg';
                FastImageSize::initAttributes($image, $previousSizeAttributes);
                $arrowImagePrevious = 'data:image/svg+xml;base64,' . Base64::encode(Filesystem::readFile($image));
                if ($orientation === 'horizontal') {
                    $previousStyle .= 'transform:rotateZ(-90deg);';
                }
            } else {
                FastImageSize::initAttributes(ResourceTranslator::urlToResource($arrowImagePrevious), $previousSizeAttributes);
                switch ($orientation) {
                    case 'vertical':
                        $previousStyle .= 'transform:rotateY(180deg) rotateX(180deg);';
                        break;
                    default:
                        $previousStyle .= 'transform:rotateZ(180deg);';
                }
            }
            if (empty($arrowImageNext)) {
                $image = self::getAssetsPath() . '/thumbnail-down-arrow.svg';
                FastImageSize::initAttributes($image, $nextSizeAttributes);
                $arrowImageNext = 'data:image/svg+xml;base64,' . Base64::encode(Filesystem::readFile($image));
                if ($orientation === 'horizontal') {
                    $nextStyle .= 'transform:rotateZ(-90deg);';
                }
            } else {
                $nextStyle .= 'transform:none;';
                FastImageSize::initAttributes(ResourceTranslator::urlToResource($arrowImageNext), $nextSizeAttributes);
            }

            $previous = Html::tag('div', array(
                'class' => 'nextend-thumbnail-button nextend-thumbnail-previous'
            ), Html::image($arrowImagePrevious, $slider->params->get($this->key . 'arrow-prev-alt', 'previous arrow'), $previousSizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                    'style'   => $previousStyle,
                    'loading' => 'lazy'
                ))));
            $next     = Html::tag('div', array(
                'class' => 'nextend-thumbnail-button nextend-thumbnail-next'
            ), Html::image($arrowImageNext, $slider->params->get($this->key . 'arrow-next-alt', 'next arrow'), $nextSizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                    'style'   => $nextStyle,
                    'loading' => 'lazy'
                ))));
        }

        $captionStyle = '';
        if ($showTitle || $showDescription) {
            $captionStyle = $slider->addStyle($params->get($this->key . 'title-style'), 'simple');
        }

        $titleFont = '';
        if ($showTitle) {
            $titleFont = $slider->addFont($params->get($this->key . 'title-font'), 'simple');
        }

        $descriptionFont = '';
        if ($showDescription) {
            $descriptionFont = $slider->addFont($params->get($this->key . 'description-font'), 'simple');
        }

        $dots   = array();
        $slides = $slider->getSlides();
        foreach ($slides as $slide) {

            $dotHTML = array();

            if ($showThumbnail) {

                $dotHTML[] = $slide->renderThumbnailImage($width, $height, array(
                    'alt' => $slide->getThumbnailAltDynamic()
                ));

                $thumbnailType = $slide->getThumbnailType();
                if (isset(self::$thumbnailTypes[$thumbnailType])) {
                    $dotHTML[] = self::$thumbnailTypes[$thumbnailType];
                }
            }

            if ($showTitle || $showDescription) {
                $captionHTML = '';
                if ($showTitle) {
                    $title = $slide->getTitle();
                    if (!empty($title)) {
                        $captionHTML .= '<div class="' . $titleFont . '">' . $title . '</div>';
                    }
                }

                if ($showDescription) {
                    $description = $slide->getDescription();
                    if (!empty($description)) {
                        $captionHTML .= '<div class="' . $descriptionFont . '">' . $description . '</div>';
                    }
                }

                if (!empty($captionHTML)) {
                    $dotHTML[] = Html::tag('div', array(
                        'class' => $captionStyle . ' n2-ss-caption n2-ow n2-caption-' . $captionPlacement,
                        'style' => $captionExtraStyle
                    ), $captionHTML);
                }
            }


            $dots[] = Html::tag('div', $slide->showOnAttributes + array(
                    'class'                => 'n2-thumbnail-dot ' . $slideStyle,
                    'data-slide-public-id' => $slide->getPublicID(),
                    'role'                 => 'button',
                    'aria-label'           => $slide->getTitle(),
                    'tabindex'             => '0'
                ), implode('', $dotHTML));
        }

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            'class'             => 'nextend-thumbnail nextend-thumbnail-default nextend-thumbnail-' . $orientation . ' n2-ow-all',
            'data-has-next'     => 0,
            'data-has-previous' => 0,
            'style'             => $style
        )), Html::tag('div', array(
                'class' => 'nextend-thumbnail-inner ' . $barStyle
            ), Html::tag('div', array(
                'class' => 'nextend-thumbnail-scroller n2-align-content-' . $params->get('widget-thumbnail-align-content'),
                'style' => $scrollerStyle
            ), implode('', $dots))) . $previous . $next);
    }

    protected function translateArea($area) {

        if ($area == 5) {
            return 'left';
        } else if ($area == 8) {
            return 'right';
        }

        return parent::translateArea($area);
    }
}