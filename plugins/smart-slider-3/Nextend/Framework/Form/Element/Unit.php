<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Unit extends AbstractFieldHidden {

    protected $style = '';

    protected $units = array();

    protected function fetchElement() {

        $values = array();

        $html = "<div class='n2_field_unit' style='" . $this->style . "'>";

        $currentValue = $this->getValue();
        $currentLabel = '';

        $html .= Html::openTag('div', array(
            'class' => 'n2_field_unit__units'
        ));
        foreach ($this->units as $unit) {
            $values[] = $unit;

            $html .= Html::tag('div', array(
                'class' => 'n2_field_unit__unit'
            ), $unit);

            if ($currentValue == $unit) {
                $currentLabel = $unit;
            }
        }

        $html .= "</div>";

        $html .= Html::tag('div', array(
            'class' => 'n2_field_unit__current_unit'
        ), $currentLabel);

        $html .= parent::fetchElement();

        $html .= "</div>";

        Js::addInline('new _N2.FormElementUnits("' . $this->fieldID . '", ' . json_encode($values) . ');');

        return $html;
    }

    /**
     * @param string $style
     */
    public function setStyle($style) {
        $this->style = $style;
    }

    /**
     * @param array $units
     */
    public function setUnits($units) {
        $this->units = $units;
    }
}