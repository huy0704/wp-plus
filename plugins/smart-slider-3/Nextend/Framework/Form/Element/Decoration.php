<?php


namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Decoration extends AbstractFieldHidden {

    protected $value = null;

    protected $options = array(
        'italic'    => 'ssi_16 ssi_16--italic',
        'underline' => 'ssi_16 ssi_16--underline'
    );

    protected $style = '';

    protected function fetchElement() {

        $this->value = explode('||', $this->getValue());

        $html = Html::tag('div', array(
            'class' => 'n2_field_decoration',
            'style' => $this->style
        ), $this->renderOptions() . parent::fetchElement());

        Js::addInline('new _N2.FormElementDecoration("' . $this->fieldID . '", ' . json_encode(array_keys($this->options)) . ');');

        return $html;
    }

    /**
     *
     * @return string
     */
    protected function renderOptions() {

        $length = count($this->options) - 1;

        $html = '';
        $i    = 0;
        foreach ($this->options as $value => $class) {

            $html .= Html::tag('div', array(
                'class'      => 'n2_field_decoration__option ' . ($this->isSelected($value) ? ' n2_field_decoration__option--selected' : ''),
                'data-value' => $value
            ), Html::tag('i', array('class' => $class)));
            $i++;
        }

        return $html;
    }

    function isSelected($value) {
        if (in_array($value, $this->value)) {
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

    /**
     * @param string $style
     */
    public function setStyle($style) {
        $this->style = $style;
    }
}