<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Form\Element\Text;

class Family extends Text {

    protected $class = 'n2_field_autocomplete n2_autocomplete_position_to';

    protected function addScript() {
        parent::addScript();

        $families = FontSettings::getPresetFamilies();
        Js::addInline('_N2.AutocompleteSimple("' . $this->fieldID . '", ' . json_encode($families) . ');');
    }
}