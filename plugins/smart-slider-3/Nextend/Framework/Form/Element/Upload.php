<?php

namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\View\Html;

class Upload extends AbstractField {

    protected $class = 'n2-form-element-file ';

    protected function fetchElement() {

        $html = '';
        $html .= '<div class="n2_field_chooser__label"></div>';
        $html .= Html::tag('a', array(
            'href'  => '#',
            'class' => 'n2_field_chooser__choose'
        ), '<i class="ssi_16 ssi_16--plus"></i>');

        $html .= Html::tag('input', array(
            'type'         => 'file',
            'id'           => $this->fieldID,
            'name'         => $this->getFieldName(),
            'value'        => $this->getValue(),
            'autocomplete' => 'off'
        ), false, false);

        Js::addInline('new _N2.FormElementUpload("' . $this->fieldID . '");');

        return Html::tag('div', array(
            'class' => 'n2_field_chooser n2_field_upload '
        ), $html);
    }
}