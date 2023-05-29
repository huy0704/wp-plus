<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;

class LinkTarget extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '_self', array $parameters = array()) {
        $this->options = array(
            '_self'   => n2_('Self'),
            '_blank'  => n2_('New'),
            '_parent' => n2_('Parent'),
            '_top'    => n2_('Top')
        );

        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }
}