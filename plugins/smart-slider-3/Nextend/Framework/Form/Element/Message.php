<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Form\AbstractField;

abstract class Message extends AbstractField {

    protected $description = '';

    protected $classes = array('n2_field_message');

    public function __construct($insertAt, $name, $label, $description) {
        $this->description = $description;
        parent::__construct($insertAt, $name, $label);
    }

    protected function fetchElement() {
        return '<div class="' . implode(' ', $this->classes) . '">' . $this->description . '</div>';
    }
}