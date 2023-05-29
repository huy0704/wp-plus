<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\View\Html;

abstract class AbstractChooser extends AbstractFieldHidden {

    protected $hasClear = true;

    protected $class = '';

    protected $width;

    abstract protected function addScript();

    protected function fetchElement() {

        $this->addScript();

        $this->renderRelatedFields();

        return Html::tag('div', array(
            'class' => 'n2_field_chooser ' . $this->class
        ), $this->pre() . parent::fetchElement() . $this->field() . $this->post());
    }

    protected function pre() {

    }

    protected function field() {
        $style = '';
        if ($this->width) {
            $style = 'width: ' . $this->width . 'px;';
        }

        return '<div class="n2_field_chooser__label" style="' . $style . '"></div>';
    }

    protected function post() {

        $html = '';
        if ($this->hasClear) {
            $html .= Html::tag('a', array(
                'href'  => '#',
                'class' => 'n2_field_chooser__clear'
            ), Html::tag('i', array('class' => 'ssi_16 ssi_16--circularremove'), ''));
        }

        $html .= Html::tag('a', array(
            'href'  => '#',
            'class' => 'n2_field_chooser__choose'
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