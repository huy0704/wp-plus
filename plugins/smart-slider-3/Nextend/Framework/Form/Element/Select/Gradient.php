<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;

class Gradient extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $this->options = array(
            'off'        => n2_('Off'),
            'vertical'   => '&darr;',
            'horizontal' => '&rarr;',
            'diagonal1'  => '&#8599;',
            'diagonal2'  => '&#8600;'
        );
    }
}