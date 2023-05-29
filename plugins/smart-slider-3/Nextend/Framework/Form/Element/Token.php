<?php


namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Form\Form;

class Token extends Hidden {

    protected function fetchElement() {

        return Form::tokenize();
    }
}