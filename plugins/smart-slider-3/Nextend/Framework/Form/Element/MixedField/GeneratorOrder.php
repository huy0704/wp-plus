<?php


namespace Nextend\Framework\Form\Element\MixedField;


use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Radio;
use Nextend\Framework\Form\Element\Select;

class GeneratorOrder extends MixedField {

    protected $rowClass = 'n2_field_mixed_generator_order ';

    protected $options = array();

    public function __construct($insertAt, $name = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, false, $default, $parameters);

        new Select($this, $name . '-1', n2_('Field'), '', $this->options);

        new Radio($this, $name . '-2', n2_('Order'), '', array(
            'options' => array(
                'asc'  => n2_('Ascending'),
                'desc' => n2_('Descending')
            )
        ));
    }

    protected function decorate($html) {

        return '<div class="n2_field_mixed_generator_order__container" style="' . $this->style . '">' . $html . '</div>';
    }

    protected function setOptions($options) {
        $this->options = array(
            'options' => $options
        );
    }
}