<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\View\Html;

class AbstractFieldHidden extends AbstractField {

    protected $hasTooltip = true;

    protected $type = 'hidden';

    public function __construct($insertAt, $name = '', $label = false, $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }

    protected function fetchTooltip() {
        if ($this->hasTooltip) {
            return parent::fetchTooltip();
        } else {
            return $this->fetchNoTooltip();
        }
    }

    protected function fetchElement() {

        return Html::tag('input', array(
            'id'           => $this->fieldID,
            'name'         => $this->getFieldName(),
            'value'        => $this->getValue(),
            'type'         => $this->type,
            'autocomplete' => 'off'
        ), false, false);
    }
}