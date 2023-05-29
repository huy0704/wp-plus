<?php


namespace Nextend\Framework\Form\Element;


class Hidden extends AbstractFieldHidden {

    protected $hasTooltip = false;

    public function __construct($insertAt, $name = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, false, $default, $parameters);
    }

    public function getRowClass() {
        return 'n2_form_element--hidden';
    }
}