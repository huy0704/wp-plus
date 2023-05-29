<?php

namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Form\AbstractField;

class Connected extends MixedField {

    protected $rowClass = 'n2_field_connected ';

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        $elementHtml = $element->render();

        return $elementHtml[1];
    }

    protected function decorate($html) {

        return '<div class="n2_field_connected__container" style="' . $this->style . '">' . $html . '</div>';
    }
}