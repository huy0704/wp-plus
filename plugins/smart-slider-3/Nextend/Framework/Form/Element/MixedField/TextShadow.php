<?php


namespace Nextend\Framework\Form\Element\MixedField;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;

class TextShadow extends MixedField {

    protected $rowClass = 'n2_field_mixed_text_shadow ';

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        for ($i = 1; $i < 4; $i++) {
            new NumberAutoComplete($this, $this->name . '-' . $i, false, 0, array(
                'wide' => 3
            ));
        }
        new Color($this, $this->name . '-4', false, '', array(
            'alpha' => true
        ));
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        $elementHtml = $element->render();

        return $elementHtml[1];
    }

    protected function decorate($html) {

        return '<div class="n2_field_mixed_text_shadow__container" style="' . $this->style . '">' . $html . '</div>';
    }
}