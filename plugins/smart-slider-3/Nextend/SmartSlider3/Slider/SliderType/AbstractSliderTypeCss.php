<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Asset\Builder\BuilderCss;
use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Asset\Css\Less\LessCompiler;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Slider\Feature\Responsive;
use Nextend\SmartSlider3\Slider\Slider;

abstract class AbstractSliderTypeCss {

    /**
     * @var Slider
     */
    protected $slider;

    public $sizes = array();

    protected $context = array();

    public $base = array();

    /**
     * AbstractSliderTypeCss constructor.
     *
     * @param Slider $slider
     */
    public function __construct($slider) {
        $this->slider = $slider;


        $params = $slider->params;

        if (!Platform::needStrongerCSS()) {
            Css::addStaticGroupPreload(ApplicationTypeFrontend::getAssetsPath() . '/dist/smartslider.min.css', 'smartslider');
        }

        $width  = intval($params->get('width', 900));
        $height = intval($params->get('height', 500));
        if ($width < 10 || $height < 10) {
            Notification::error(n2_('Slider size is too small!'));
        }
        $this->context = array_merge($this->context, array(
            'sliderid'       => "~'#{$slider->elementId}'",
            'width'          => $width . 'px',
            'height'         => $height . 'px',
            'canvas'         => 0,
            'count'          => $slider->getSlidesCount(),
            'margin'         => '0px 0px 0px 0px',
            'hasPerspective' => 0
        ));

        $perspective = intval($params->get('perspective', 1500));
        if ($perspective > 0) {
            $this->context['hasPerspective'] = 1;
            $this->context['perspective']    = $perspective . 'px';
        }
    }

    public function getCSS() {
        $css = '';
        if (Platform::needStrongerCSS()) {
            $cssPath = ApplicationTypeFrontend::getAssetsPath() . '/dist/smartslider.min.css';
            if (file_exists($cssPath)) {
                $css = file_get_contents($cssPath);
            }
        }

        foreach ($this->slider->less as $file => $context) {
            $compiler = new LessCompiler();
            $compiler->setVariables($context);
            $css .= $compiler->compileFile($file);
        }
        $css .= implode('', $this->slider->css);

        $mediaQueries = $this->slider->features->responsive->mediaQueries;

        foreach ($this->slider->cssDevice as $device => $styles) {
            if (!empty($styles) && isset($mediaQueries[$device])) {
                if (empty($mediaQueries[$device])) {
                    $css .= implode('', $styles);
                } else {
                    if (!$this->slider->isAdmin) {
                        $css .= '@media ' . implode(',', $mediaQueries[$device]) . '{' . implode('', $styles) . '}';
                    } else {
                        $css .= str_replace('div#' . $this->slider->elementId, 'body[data-device="' . Responsive::$translation[$device] . '"] div#' . $this->slider->elementId, implode('', $styles));
                    }
                }
            }
        }

        if (Platform::needStrongerCSS()) {
            $css = preg_replace(array(
                '/' . preg_quote('#' . $this->slider->elementId) . '([\#\. \{,\[])/',
                '/' . preg_quote('#' . $this->slider->elementId) . '\.n2-ss-slider([\#\. \{,\[])/',
                '/\.n2-ss-align([\#\. \{,\[])/',
                '/\.n2-ss-slider([\#\. \{,\[])/'
            ), array(
                '#' . $this->slider->elementId . '#' . $this->slider->elementId . '#' . $this->slider->elementId . '$1',
                '#' . $this->slider->elementId . '#' . $this->slider->elementId . '$1',
                '#' . $this->slider->elementId . '-align#' . $this->slider->elementId . '-align$1',
                '#' . $this->slider->elementId . '#' . $this->slider->elementId . '$1'
            ), $css);
        }

        $css .= Sanitize::remove_closing_style_tag($this->slider->params->get('custom-css-codes', ''));

        return $css;
    }

    public function initSizes() {

        $this->sizes['marginVertical']   = 0;
        $this->sizes['marginHorizontal'] = 0;

        $this->sizes['width']        = intval($this->context['width']);
        $this->sizes['height']       = intval($this->context['height']);
        $this->sizes['canvasWidth']  = intval($this->context['canvaswidth']);
        $this->sizes['canvasHeight'] = intval($this->context['canvasheight']);
    }
}