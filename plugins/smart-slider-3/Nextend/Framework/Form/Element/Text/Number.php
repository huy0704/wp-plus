<?php

namespace Nextend\Framework\Form\Element\Text;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\View\Html;

class Number extends Text {

    protected $class = 'n2_field_number ';

    protected $min = false;
    protected $max = false;
    protected $sublabel = '';

    protected $units = false;

    protected function fetchElement() {

        if ($this->min === false) {
            $this->min = '-Number.MAX_VALUE';
        }

        if ($this->max === false) {
            $this->max = 'Number.MAX_VALUE';
        }

        $this->addScript();

        $this->renderRelatedFields();

        $html = Html::openTag('div', array(
            'class' => 'n2_field_text ' . $this->getClass()
        ));

        if (!empty($this->sublabel)) {
            $html .= Html::tag('div', array(
                'class' => 'n2_field_text__pre_label'
            ), $this->sublabel);
        }

        $html .= $this->pre();

        $html .= Html::tag('input', array(
            'type'         => $this->fieldType,
            'id'           => $this->fieldID,
            'name'         => $this->getFieldName(),
            'value'        => $this->getValue(),
            'style'        => $this->getStyle(),
            'autocomplete' => 'off'
        ), false, false);

        $html .= $this->post();

        if ($this->unit) {
            $html .= Html::tag('div', array(
                'class' => 'n2_field_number__unit'
            ), $this->unit);
        }
        $html .= "</div>";

        return $html;
    }

    protected function addScript() {
        Js::addInline('new _N2.FormElementNumber("' . $this->fieldID . '", ' . $this->min . ', ' . $this->max . ', ' . json_encode($this->units) . ');');
    }

    public function setMin($min) {
        $this->min = $min;
    }

    /**
     * @param int $max
     */
    public function setMax($max) {
        $this->max = $max;
    }

    /**
     * @param string $sublabel
     */
    public function setSublabel($sublabel) {
        $this->sublabel = $sublabel;
    }

    /**
     * @param bool|array $units
     */
    public function setUnits($units) {
        $this->units = $units;
    }

    public function setWide($wide) {
        switch ($wide) {
            case 2:
                $this->style .= 'width:20px;';
                break;
            case 3:
                $this->style .= 'width:26px;';
                break;
            case 4:
                $this->style .= 'width:32px;';
                break;
            case 5:
                $this->style .= 'width:44px;';
                break;
            case 6:
                $this->style .= 'width:60px;';
                break;
        }
    }
}