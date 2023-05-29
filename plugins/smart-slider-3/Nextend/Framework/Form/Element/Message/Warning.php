<?php


namespace Nextend\Framework\Form\Element\Message;

use Nextend\Framework\Form\Element\Message;

class Warning extends Message {

    protected $description = '';

    public function __construct($insertAt, $name, $description) {
        $this->classes[] = 'n2_field_message--warning';
        parent::__construct($insertAt, $name, n2_('Warning'), $description);
    }

    protected function fetchElement() {
        return '<div class="' . implode(' ', $this->classes) . '">' . $this->description . '</div>';
    }
}