<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;

class FontWeight extends Select {

    public function __construct($insertAt, $name = '', $label = false, $default = '', $parameters = array()) {
        $this->options = array(
            '0'   => n2_('Normal'),
            '1'   => n2_('Bold'),
            '100' => '100',
            '200' => '200 - ' . n2_('Extra light'),
            '300' => '300 - ' . n2_('Light'),
            '400' => '400 - ' . n2_('Normal'),
            '500' => '500',
            '600' => '600 - ' . n2_('Semi bold'),
            '700' => '700 - ' . n2_('Bold'),
            '800' => '800 - ' . n2_('Extra bold'),
            '900' => '900'
        );
        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }
}