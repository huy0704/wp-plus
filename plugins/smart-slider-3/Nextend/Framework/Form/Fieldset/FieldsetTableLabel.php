<?php


namespace Nextend\Framework\Form\Fieldset;


use Nextend\Framework\Form\AbstractFieldset;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class FieldsetTableLabel extends AbstractFieldset {

    public function renderContainer() {

        $element = $this->first;
        while ($element) {

            echo wp_kses(Html::openTag('div', array(
                        'class'      => 'n2_form__table_label_field ' . $element->getRowClass(),
                        'data-field' => $element->getID()
                    ) + $element->getRowAttributes()) . $this->decorateElement($element) . "</div>", Sanitize::$adminFormTags);

            $element = $element->getNext();
        }
    }
}