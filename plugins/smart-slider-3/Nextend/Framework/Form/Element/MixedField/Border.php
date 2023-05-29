<?php


namespace Nextend\Framework\Form\Element\MixedField;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\View\Html;

class Border extends MixedField {

    protected $rowClass = 'n2_field_mixed_border ';

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        new NumberAutoComplete($this, $this->name . '-1', false, '', array(
            'values'        => array(
                0,
                1,
                3,
                5
            ),
            'min'           => 0,
            'wide'          => 3,
            'unit'          => 'px',
            'relatedFields' => array(
                $this->generateId($this->getControlName() . $this->name . '-2'),
                $this->generateId($this->getControlName() . $this->name . '-3')
            )
        ));

        new Select($this, $this->name . '-2', false, '', array(
            'options' => array(
                'none'   => n2_('None'),
                'dotted' => n2_('Dotted'),
                'dashed' => n2_('Dashed'),
                'solid'  => n2_('Solid'),
                'double' => n2_('Double'),
                'groove' => n2_('Groove'),
                'ridge'  => n2_('Ridge'),
                'inset'  => n2_('Inset'),
                'outset' => n2_('Outset')
            )
        ));

        new Color($this, $this->name . '-3', false, '', array(
            'alpha' => true
        ));
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        $elementHtml = $element->render();

        return Html::tag('div', array(
            'data-field' => $element->getID()
        ), $elementHtml[1]);
    }

    protected function decorate($html) {

        return '<div class="n2_field_mixed_border__container" style="' . $this->style . '">' . $html . '</div>';
    }
}