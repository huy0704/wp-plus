<?php


namespace Nextend\SmartSlider3\Widget;


use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Slider\Slider;

class SliderWidget {

    /** @var AbstractWidgetFrontend[] */
    private $enabledWidgets = array();

    public $widgets = array();

    public $left = array();

    public $right = array();

    /**
     * @var WidgetPlacementSimple[]
     */
    private $placements = array();

    /**
     * @var WidgetPlacementAdvanced
     */
    private $placementAdvanced;

    public $slider;

    /**
     * @param $slider Slider
     */
    public function __construct($slider) {


        $this->slider = $slider;

        $params = $slider->params;

        $this->placements['above'] = new WidgetPlacementSimple('above');
        $this->placements['below'] = new WidgetPlacementSimple('below');
        $this->placements['left']  = new WidgetPlacementSimple('left');
        $this->placements['right'] = new WidgetPlacementSimple('right');

        $this->placements['absolute-left']  = new WidgetPlacementSimple('absolute-left');
        $this->placements['absolute-right'] = new WidgetPlacementSimple('absolute-right');

        $this->placements['absolute-left-top']   = new WidgetPlacementSimple('absolute-left-top');
        $this->placements['absolute-center-top'] = new WidgetPlacementSimple('absolute-center-top');
        $this->placements['absolute-right-top']  = new WidgetPlacementSimple('absolute-right-top');

        $this->placements['absolute-left-center']  = new WidgetPlacementSimple('absolute-left-center');
        $this->placements['absolute-right-center'] = new WidgetPlacementSimple('absolute-right-center');

        $this->placements['absolute-left-bottom']   = new WidgetPlacementSimple('absolute-left-bottom');
        $this->placements['absolute-center-bottom'] = new WidgetPlacementSimple('absolute-center-bottom');
        $this->placements['absolute-right-bottom']  = new WidgetPlacementSimple('absolute-right-bottom');

        $this->placementAdvanced = new WidgetPlacementAdvanced('advanced');

        $widgetGroups = WidgetGroupFactory::getGroups();

        foreach ($widgetGroups as $groupName => $group) {
            $isEnabled = false;
            if ($params->has('widget-' . $group->getName() . '-enabled')) {
                $isEnabled = $params->get('widget-' . $group->getName() . '-enabled', 0);
            } else {
                $oldValue = $params->get('widget' . $groupName);
                if ($oldValue != 'disabled' && $oldValue != '') {
                    $isEnabled = true;
                }
            }

            if ($isEnabled) {
                $widget = $group->getWidget($params->get('widget' . $groupName));
                if ($widget) {
                    $this->enabledWidgets[$groupName] = $widget->createFrontend($this, $params);
                }
            }
        }
    }

    public function addToSimplePlacement($renderCallback, $placement, $stack, $offset = 0) {

        $this->placements[$placement]->add($renderCallback, $stack, $offset);
    }

    public function addToAdvancedPlacement($renderCallback, $horizontalSide, $horizontalPosition, $horizontalUnit, $verticalSide, $verticalPosition, $verticalUnit) {

        $this->placementAdvanced->add($renderCallback, $horizontalSide, $horizontalPosition, $horizontalUnit, $verticalSide, $verticalPosition, $verticalUnit);
    }

    /**
     * @param $innerHTML
     *
     * @return mixed|string contains already escaped data
     */
    public function wrapSlider($innerHTML) {

        $insideAbsoluteHTML = '';

        $insideAbsolutes = array(
            'absolute-left-top',
            'absolute-center-top',
            'absolute-right-top',
            'absolute-left-center',
            'absolute-right-center',
            'absolute-left-bottom',
            'absolute-center-bottom',
            'absolute-right-bottom'
        );

        foreach ($insideAbsolutes as $insideAbsolute) {
            if (!$this->placements[$insideAbsolute]->empty()) {
                $insideAbsoluteHTML .= $this->placements[$insideAbsolute]->render();
            }
        }

        if (!$this->placementAdvanced->empty()) {
            $insideAbsoluteHTML .= $this->placementAdvanced->render();
        }

        if (!empty($insideAbsoluteHTML)) {

            $innerHTML = Html::tag('div', array(
                'class' => 'n2-ss-slider-wrapper-inside'
            ), $innerHTML . $insideAbsoluteHTML);
        }


        $leftHTML = '';
        if (!$this->placements['left']->empty()) {
            $leftHTML = $this->placements['left']->render();
        }
        if (!$this->placements['absolute-left']->empty()) {
            $leftHTML .= $this->placements['absolute-left']->render();
        }

        $rightHTML = '';
        if (!$this->placements['right']->empty()) {
            $rightHTML = $this->placements['right']->render();
        }
        if (!$this->placements['absolute-right']->empty()) {
            $rightHTML .= $this->placements['absolute-right']->render();
        }

        if (!empty($leftHTML) || !empty($rightHTML)) {

            $innerHTML = Html::tag('div', array(
                'class' => 'n2-ss-slider-controls-side'
            ), $leftHTML . $innerHTML . $rightHTML);
        }

        $templateRows = array();

        if (!$this->placements['above']->empty()) {
            $innerHTML      = $this->placements['above']->render() . $innerHTML;
            $templateRows[] = 'auto';
        }

        $templateRows[] = '1fr';

        if (!$this->placements['below']->empty()) {
            $innerHTML      .= $this->placements['below']->render();
            $templateRows[] = 'auto';
        }

        if (count($templateRows) > 1) {
            /**
             * Full page responsive type need this grid to properly render
             */
            $innerHTML = Html::tag('div', array(
                'class' => 'n2-ss-slider-wrapper-outside',
                'style' => 'grid-template-rows:' . implode(' ', $templateRows)
            ), $innerHTML);
        }

        return $innerHTML;
    }

}