<?php


namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Radio extends AbstractFieldHidden {

    protected $options = array();

    protected $class = 'n2_field_radio';

    protected $style = '';

    protected $value;

    protected function addScript() {
        Js::addInline('new _N2.FormElementRadio("' . $this->fieldID . '", ' . json_encode(array_keys($this->options)) . ', ' . json_encode($this->relatedFields) . ');');
    }

    protected function fetchElement() {

        $this->value = $this->getValue();

        $html = Html::tag('div', array(
            'class' => $this->class,
            'style' => $this->style
        ), $this->renderOptions() . parent::fetchElement());

        $this->addScript();

        return $html;
    }

    /**
     * @return string
     */
    protected function renderOptions() {

        $html = '';
        $i    = 0;
        foreach ($this->options as $value => $label) {
            $html .= Html::tag('div', array(
                'class' => 'n2_field_radio__option' . ($this->isSelected($value) ? ' n2_field_radio__option--selected' : '')
            ), Html::tag('div', array(
                    'class' => 'n2_field_radio__option_marker'
                ), '<i class="ssi_16 ssi_16--check"></i>') . '<div class="n2_field_radio__option_label">' . $label . '</div>');
            $i++;
        }

        return $html;
    }

    function isSelected($value) {
        if ((string)$value == $this->value) {
            return true;
        }

        return false;
    }

    /**
     * @param array $options
     */
    public function setOptions($options) {
        $this->options = $options;
    }

    public function setStyle($style) {
        $this->style = $style;
    }
}