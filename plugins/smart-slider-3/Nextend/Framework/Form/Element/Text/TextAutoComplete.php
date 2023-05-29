<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Text;

class TextAutoComplete extends Text {

    protected $class = 'n2_field_autocomplete n2_autocomplete_position_to';

    protected $values = array();

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