<?php


namespace Nextend\SmartSlider3\Widget\Bullet\BulletTransition;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\Bullet\AbstractBulletFrontend;

class BulletTransitionFrontend extends AbstractBulletFrontend {

    public function render($attributes = array()) {

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        if ($slider->getSlidesCount() <= 1) {
            return '';
        }

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        Js::addStaticGroup($this->getCommonAssetsPath() . '/dist/w-bullet.min.js', 'w-bullet');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key, 1);

        $bulletStyle = $slider->addStyle($params->get($this->key . 'style'), 'dot');
        $barStyle    = $slider->addStyle($params->get($this->key . 'bar'), 'simple');

        $orientation = $this->getOrientationByPosition($params->get($this->key . 'position-mode'), $params->get($this->key . 'position-area'), $params->get($this->key . 'orientation'), 'horizontal');


        $parameters = array(
            'area'       => intval($params->get($this->key . 'position-area')),
            'dotClasses' => $bulletStyle,
            'mode'       => '',
            'action'     => $params->get($this->key . 'action')
        );

        if ($params->get($this->key . 'thumbnail-show-image')) {

            $parameters['thumbnail']       = 1;
            $parameters['thumbnailWidth']  = intval($params->get($this->key . 'thumbnail-width'));
            $parameters['thumbnailHeight'] = intval($params->get($this->key . 'thumbnail-height'));
            $parameters['thumbnailStyle']  = $slider->addStyle($params->get($this->key . 'thumbnail-style'), 'simple', '');
            $side                          = $params->get($this->key . 'thumbnail-side');


            if ($side == 'before') {
                if ($orientation == 'vertical') {
                    $position = 'left';
                } else {
                    $position = 'top';
                }
            } else {
                if ($orientation == 'vertical') {
                    $position = 'right';
                } else {
                    $position = 'bottom';
                }
            }
            $parameters['thumbnailPosition'] = $position;
        }

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetBulletTransition(this, ' . json_encode($parameters) . ');');
        $slider->sliderType->addJSDependency('SmartSliderWidgetBulletTransition');

        $fullSize = intval($params->get($this->key . 'bar-full-size'));

        return Html::tag("div", Html::mergeAttributes($attributes, $displayAttributes, array(
            "class" => 'n2-ss-control-bullet n2-ow-all n2-ss-control-bullet-' . $orientation . ($fullSize ? ' n2-ss-control-bullet-fullsize' : '')
        )), Html::tag("div", array(
            "class" => $barStyle . " nextend-bullet-bar n2-bar-justify-content-" . $params->get($this->key . 'align')
        ), '<div class="n2-bullet ' . $bulletStyle . '" style="visibility:hidden;"></div>'));
    }
}