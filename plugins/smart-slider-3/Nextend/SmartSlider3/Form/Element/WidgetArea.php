<?php


namespace Nextend\SmartSlider3\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\View\Html;

class WidgetArea extends AbstractFieldHidden {

    protected $hasTooltip = false;

    protected function fetchElement() {
        $areas = '';
        for ($i = 1; $i <= 12; $i++) {
            $areas .= Html::tag('div', array(
                'class'     => 'n2_field_widget_area__area' . $this->isSelected($i),
                'data-area' => $i
            ));
        }

        $html = Html::tag('div', array(
            'class' => 'n2_field_widget_area'
        ), Html::tag('div', array(
                'class' => 'n2_field_widget_area__inner'
            )) . $areas . parent::fetchElement());

        Js::addInline('new _N2.FormElementSliderWidgetArea("' . $this->fieldID . '");');

        return $html;
    }

    function isSelected($i) {
        if ($i == $this->getValue()) {
            return ' n2_field_widget_area__area--selected';
        }

        return '';
    }
}