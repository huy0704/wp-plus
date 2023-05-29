<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;

class MarginPadding extends AbstractFieldHidden implements ContainerInterface {

    use TraitFieldset;

    private static $separator = '|*|';

    protected $unit = false;

    protected function fetchElement() {
        $default = explode(self::$separator, $this->defaultValue);
        $value   = explode(self::$separator, $this->getValue());
        $value   = $value + $default;

        $html = "<div class='n2_field_margin_padding' style='" . $this->style . "'>";

        $html        .= '<div class="n2_field_margin_padding__pre_label"><i class="ssi_16 ssi_16--unlink"></i></div>';
        $subElements = array();
        $i           = 0;

        $element = $this->first;
        while ($element) {
            $element->setExposeName(false);
            if (isset($value[$i])) {
                $element->setDefaultValue($value[$i]);
            }

            $html            .= $this->decorateElement($element);
            $subElements[$i] = $element->getID();
            $i++;

            $element = $element->getNext();
        }

        if ($this->unit) {
            $html .= '<div class="n2_field_unit"><div class="n2_field_unit__current_unit">' . $this->unit . '</div></div>';
        }

        $html .= parent::fetchElement();
        $html .= "</div>";

        Js::addInline('new _N2.FormElementMarginPadding("' . $this->fieldID . '", ' . json_encode($subElements) . ', "' . self::$separator . '");');

        $this->renderRelatedFields();

        return $html;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function getControlName() {
        return $this->name . $this->controlName;
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        $elementHtml = $element->render();

        return $elementHtml[1];
    }
}