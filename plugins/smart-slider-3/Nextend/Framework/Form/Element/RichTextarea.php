<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\View\Html;

class RichTextarea extends AbstractField {

    protected $fieldStyle = '';

    protected function fetchElement() {

        Js::addInline('new _N2.FormElementRichText("' . $this->fieldID . '");');

        $tools = array(
            Html::tag('div', array(
                'class'       => 'n2_field_textarea_rich__button',
                'data-action' => 'bold'
            ), Html::tag('I', array('class' => 'ssi_16 ssi_16--bold'))),
            Html::tag('div', array(
                'class'       => 'n2_field_textarea_rich__button',
                'data-action' => 'italic'
            ), Html::tag('I', array('class' => 'ssi_16 ssi_16--italic'))),
            Html::tag('div', array(
                'class'       => 'n2_field_textarea_rich__button',
                'data-action' => 'link'
            ), Html::tag('I', array('class' => 'ssi_16 ssi_16--link')))
        );

        $buttons = Html::tag('div', array(
            'class' => 'n2_field_textarea_rich__buttons'
        ), implode('', $tools));

        return Html::tag('div', array(
            'class' => 'n2_field_textarea_rich',
            'style' => $this->style
        ), $buttons . Html::tag('textarea', array(
                'id'           => $this->fieldID,
                'name'         => $this->getFieldName(),
                'autocomplete' => 'off',
                'style'        => $this->fieldStyle
            ), $this->getValue()));
    }

    /**
     * @param string $fieldStyle
     */
    public function setFieldStyle($fieldStyle) {
        $this->fieldStyle = $fieldStyle;
    }
}