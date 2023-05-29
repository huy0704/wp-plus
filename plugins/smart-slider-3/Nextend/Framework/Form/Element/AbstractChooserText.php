<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\View\Html;

abstract class AbstractChooserText extends AbstractFieldHidden {

    protected $hasClear = true;

    protected $width = 130;

    protected $class = '';

    protected $type = 'text';

    abstract protected function addScript();

    protected function fetchElement() {

        $this->addScript();

        $this->renderRelatedFields();

        return Html::tag('div', array(
            'class' => 'n2_field_text' . $this->class
        ), $this->pre() . $this->field() . $this->post());
    }

    protected function pre() {

    }

    protected function field() {

        if ($this->type === 'hidden') {
            return Html::tag('input', array(
                'type'     => 'text',
                'style'    => 'width: ' . $this->width . 'px;',
                'disabled' => 'disabled'
            ), false, false);
        }

        return Html::tag('input', array(
            'id'           => $this->fieldID,
            'name'         => $this->getFieldName(),
            'value'        => $this->getValue(),
            'type'         => $this->type,
            'style'        => 'width: ' . $this->width . 'px;',
            'autocomplete' => 'off'
        ), false, false);

    }

    protected function post() {

        $html = '';
        if ($this->hasClear) {
            $html .= Html::tag('a', array(
                'href'     => '#',
                'class'    => 'n2_field_text__clear',
                'tabindex' => -1
            ), Html::tag('i', array('class' => 'ssi_16 ssi_16--circularremove'), ''));
        }

        $html .= Html::tag('a', array(
            'href'       => '#',
            'class'      => 'n2_field_text__choose',
            'aria-label' => n2_('Choose')
        ), '<i class="ssi_16 ssi_16--plus"></i>');

        return $html;
    }

    /**
     * @param bool $hasClear
     */
    public function setHasClear($hasClear) {
        $this->hasClear = $hasClear;
    }

    /**
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }
}