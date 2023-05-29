<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;

class MixedField extends AbstractFieldHidden implements ContainerInterface {

    use TraitFieldset;

    private $separator = '|*|';

    protected $style = '';

    protected $rowClass = 'n2_field_mixed ';

    protected function fetchElement() {

        $default = explode($this->separator, $this->defaultValue);
        $value   = explode($this->separator, $this->getValue());
        $value   = $value + $default;

        $html        = '';
        $subElements = array();
        $i           = 0;


        $element = $this->first;
        while ($element) {
            $element->setExposeName(false);
            if (isset($value[$i])) {
                $element->setDefaultValue($value[$i]);
            }

            $html .= $this->decorateElement($element);

            $subElements[$i] = $element->getID();
            $i++;

            $element = $element->getNext();
        }

        $html .= parent::fetchElement();

        Js::addInline('new _N2.FormElementMixed("' . $this->fieldID . '", ' . json_encode($subElements) . ', "' . $this->separator . '");');

        return $this->decorate($html);
    }

    /**
     * @param string $style
     */
    public function setStyle($style) {
        $this->style = $style;
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

        return $this->parent->decorateElement($element);
    }

    protected function decorate($html) {

        return '<div class="n2_field_mixed__container" style="' . $this->style . '">' . $html . '</div>';
    }
}