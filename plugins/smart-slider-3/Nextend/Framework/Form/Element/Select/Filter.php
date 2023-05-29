<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;

class Filter extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $no_label = strtolower($this->label);

        $this->options = array(
            '0'  => n2_('All'),
            '1'  => $this->label,
            '-1' => sprintf(n2_('Not %s'), $no_label)
        );
    }
}