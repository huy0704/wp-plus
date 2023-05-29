<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\View\Html;

class EmptyArea extends AbstractField {

    protected function fetchElement() {

        return Html::tag('div', array(
            'id' => $this->fieldID
        ));
    }
}