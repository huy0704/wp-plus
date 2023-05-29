<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Form\Element\Text;

class HiddenText extends Text {

    public $fieldType = 'hidden';

    public function getRowClass() {
        return 'n2_form_element--hidden';
    }
}