<?php


namespace Nextend\Framework\Form\Element\Message;


use Nextend\Framework\Form\Element\Message;

class Notice extends Message {

    public function __construct($insertAt, $name, $label, $description) {
        $this->classes[] = 'n2_field_message--notice';
        parent::__construct($insertAt, $name, $label, $description);
    }
}