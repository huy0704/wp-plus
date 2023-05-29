<?php


namespace Nextend\SmartSlider3\Form\Element\Group;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Unit;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Form\Element\WidgetArea;

class WidgetPosition extends Grouping {

    protected $rowClass = '';

    protected function fetchElement() {

        $this->addSimple();

        Js::addInline('new _N2.FormElementWidgetPosition("' . $this->fieldID . '");');

        $html = '';

        $element = $this->first;
        while ($element) {

            $html .= $this->decorateElement($element);

            $element = $element->getNext();
        }

        return Html::tag('div', array(
            'id'    => $this->fieldID,
            'class' => 'n2_field_widget_position'
        ), Html::tag('div', array(
                'class' => 'n2_field_widget_position__label'
            ), '') . '<i class="n2_field_widget_position__arrow ssi_16 ssi_16--selectarrow"></i>' . Html::tag('div', array(
                'class' => 'n2_field_widget_position__popover'
            ), $html));
    }

    protected function addSimple() {

        $simple = new Grouping($this, $this->name . '-simple');

        new WidgetArea($simple, $this->name . '-area', false);
        new Select($simple, $this->name . '-stack', n2_('Stack'), 1, array(
            'options' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            )
        ));
        new Number($simple, $this->name . '-offset', n2_('Offset'), 0, array(
            'wide' => 4,
            'unit' => 'px'
        ));
    }

    protected function addAdvanced() {
    }
}