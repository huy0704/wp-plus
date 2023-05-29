<?php


namespace Nextend\Framework\Form\Element\Radio;


use Nextend\Framework\Form\Element\Radio;
use Nextend\Framework\View\Html;

abstract class AbstractRadioIcon extends Radio {

    protected $class = 'n2_field_radio_icon';

    protected function renderOptions() {

        $html = '';
        $i    = 0;
        foreach ($this->options as $value => $class) {

            $html .= Html::tag('div', array(
                'class' => 'n2_field_radio__option' . ($this->isSelected($value) ? ' n2_field_radio__option--selected' : '')
            ), Html::tag('i', array('class' => $class)));
            $i++;
        }

        return $html;
    }
}