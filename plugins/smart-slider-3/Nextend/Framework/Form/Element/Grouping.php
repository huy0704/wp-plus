<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;

class Grouping extends AbstractField implements ContainerInterface {

    use TraitFieldset;

    protected $rowClass = 'n2_field__grouping';

    public function __construct($insertAt, $name = '', $label = false, $parameters = array()) {
        parent::__construct($insertAt, $name, $label, '', $parameters);
    }

    protected function fetchElement() {

        $html = '';

        $element = $this->first;
        while ($element) {
            $html .= $this->decorateElement($element);

            $element = $element->getNext();
        }

        return $html;
    }

    public function decorateElement($element) {

        return $this->parent->decorateElement($element);
    }
}