<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;

class NumberAutoComplete extends Number {

    protected $values = array();

    protected $class = 'n2_field_number n2_autocomplete_position_to ';

    protected function addScript() {
        parent::addScript();

        Js::addInline('_N2.AutocompleteSimple("' . $this->fieldID . '", ' . json_encode($this->values) . ');');
    }

    /**
     * @param array $values
     */
    public function setValues($values) {
        $this->values = $values;
    }
}