<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;
use Nextend\Framework\View\Html;

class Text extends AbstractField implements ContainerInterface {

    use TraitFieldset;

    protected $attributes = array();

    public $fieldType = 'text';

    protected $unit = false;

    protected function addScript() {
        Js::addInline('new _N2.FormElementText("' . $this->fieldID . '");');
    }

    protected function fetchElement() {

        $this->addScript();

        if ($this->getValue() === '') {
            $this->class .= 'n2_field_text--empty ';
        }

        $html = Html::openTag('div', array(
            'class' => 'n2_field_text ' . $this->getClass()
        ));

        $html .= $this->pre();
        $html .= Html::tag('input', $this->attributes + array(
                'type'         => $this->fieldType,
                'id'           => $this->fieldID,
                'name'         => $this->getFieldName(),
                'value'        => $this->getValue(),
                'style'        => $this->getStyle(),
                'autocomplete' => 'off'
            ), false, false);

        $html .= $this->post();

        if (!empty($this->unit)) {
            $html .= Html::tag('div', array(
                'class' => 'n2_field_text__unit'
            ), $this->unit);
        }
        $html .= "</div>";

        return $html;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    protected function pre() {
        return '';
    }

    protected function post() {

        if ($this->first) {
            $html = '';

            $element = $this->first;
            while ($element) {

                $html .= $this->decorateElement($element);

                $element = $element->getNext();
            }

            return '<div class="n2_field_text__post">' . $html . '</div>';
        }

        return '';
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        list($label, $fieldHTML) = $element->render();

        return $fieldHTML;
    }
}