<?php


namespace Nextend\Framework\Form\Element\Group;


use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\View\Html;

class GroupCheckboxOnOff extends Grouping {

    protected function fetchElement() {
        return Html::tag('div', array(
            'class' => 'n2_field_group_checkbox_onoff'
        ), parent::fetchElement());
    }

}