<?php

namespace Nextend\SmartSlider3\Renderable;

use Nextend\Framework\Font\FontParser;
use Nextend\Framework\Font\FontRenderer;
use Nextend\Framework\Style\StyleParser;
use Nextend\Framework\Style\StyleRenderer;
use Nextend\SmartSlider3\Slider\FeatureManager;

abstract class AbstractRenderable {

    public $isAdmin = false;

    public $less = array();
    public $css = array();

    public $cssDevice = array(
        'all'              => array(),
        'desktoplandscape' => array(),
        'desktopportrait'  => array(),
        'tabletlandscape'  => array(),
        'tabletportrait'   => array(),
        'mobilelandscape'  => array(),
        'mobileportrait'   => array(),

    );

    public $elementId = '';

    public $fontSize = 16;

    protected $images = array();

    private $fontCache = array();

    private $styleCache = array();

    public $initCallbacks = array();
    public $addedScriptResources = array();

    /**
     * @var FeatureManager
     */
    public $features;

    public function addLess($file, $context) {
        $this->less[$file] = $context;
    }

    public function addCSS($css) {
        $this->css[] = $css;
    }

    public function addDeviceCSS($device, $css) {
        $this->cssDevice[$device][] = $css;
    }

    public function getSelector() {

        return 'div#' . $this->elementId . ' ';
    }

    private function _addFontCache($font, $mode, $pre, $fontSize) {
        $cacheKey = md5($font . $mode . $pre . $fontSize);
        if (!isset($this->fontCache[$cacheKey])) {
            $fontData = FontRenderer::render($font, $mode, $pre, $fontSize);
            if ($fontData) {
                $this->addCSS($fontData[1]);

                $this->fontCache[$cacheKey] = $fontData[0];
            } else {
                $this->fontCache[$cacheKey] = '';
            }
        }

        return $this->fontCache[$cacheKey];
    }

    public function addFont($font, $mode, $pre = null) {

        $font = FontParser::parse($font);

        if ($this->isAdmin) {
            $fontData = FontRenderer::render($font, $mode, $pre == null ? $this->getSelector() : $pre, $this->fontSize);
            if ($fontData) {
                $this->addCSS($fontData[1]);

                return $fontData[0];
            }

            return '';
        }

        return $this->_addFontCache($font, $mode, $pre == null ? $this->getSelector() : $pre, $this->fontSize);
    }


    private function _addStyleCache($style, $mode, $pre) {
        $cacheKey = md5($style . $mode . $pre);
        if (!isset($this->styleCache[$cacheKey])) {
            $styleData = StyleRenderer::render($style, $mode, $pre);
            if ($styleData) {
                $this->addCSS($styleData[1]);

                $this->styleCache[$cacheKey] = $styleData[0];
            } else {
                $this->styleCache[$cacheKey] = '';
            }
        }

        return $this->styleCache[$cacheKey];
    }

    public function addStyle($style, $mode, $pre = null) {

        $style = StyleParser::parse($style);

        if ($this->isAdmin) {
            $styleData = StyleRenderer::render($style, $mode, $pre == null ? $this->getSelector() : $pre);
            if ($styleData) {
                $this->addCSS($styleData[1]);

                return $styleData[0];
            }

            return '';
        }

        return $this->_addStyleCache($style, $mode, $pre == null ? $this->getSelector() : $pre);
    }

    public function addScript($script, $name = false) {
        if ($name !== false) {
            $this->addedScriptResources[] = $name;
        }
        $this->initCallbacks[] = $script;

    }

    public function isScriptAdded($name) {
        return in_array($name, $this->addedScriptResources);
    }

    public function addImage($imageUrl) {
        $this->images[] = $imageUrl;
    }

    public function getImages() {
        return $this->images;
    }

    public abstract function render();
}