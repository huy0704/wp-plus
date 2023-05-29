<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class SelectIcon extends AbstractFieldHidden {

    protected $options;

    public function __construct($insertAt, $name = '', $label = false, $options = array(), $default = '', $parameters = array()) {

        $this->options = $options;

        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }


    protected function fetchElement() {

        $currentValue = $this->getValue();

        $html = Html::openTag('div', array(
            'class' => 'n2_field_select_icon'
        ));

        foreach ($this->options as $value => $option) {

            $classes = array('n2_field_select_icon__option');
            if ($currentValue == $value) {
                $classes[] = 'n2_field_select_icon__option--selected';
            }

            $html .= Html::tag('div', array(
                'class'      => implode(' ', $classes),
                'data-value' => $value
            ), Html::tag('div', array(
                    'class' => 'n2_field_select_icon__option_icon'
                ), '<i class="' . $option['icon'] . '"></i>') . Html::tag('div', array(
                    'class' => 'n2_field_select_icon__option_label'
                ), $option['label']) . Html::tag('div', array(
                    'class' => 'n2_field_select_icon__selected_marker'
                ), '<i class="ssi_16 ssi_16--check"></i>'));
        }

        $html .= Html::closeTag('div');

        $html .= parent::fetchElement();

        Js::addInline('new _N2.FormElementSelectIcon("' . $this->fieldID . '", ' . json_encode(array()) . ');');

        return $html;
    }
}