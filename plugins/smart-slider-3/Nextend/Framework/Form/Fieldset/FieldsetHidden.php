<?php


namespace Nextend\Framework\Form\Fieldset;

use Nextend\Framework\Form\AbstractFieldset;
use Nextend\Framework\Sanitize;

class FieldsetHidden extends AbstractFieldset {

    public function __construct($insertAt) {

        parent::__construct($insertAt, '');
    }

    public function renderContainer() {

        if ($this->first) {
            echo '<div class="n2_form_element--hidden">';

            $element = $this->first;
            while ($element) {
                echo wp_kses($this->decorateElement($element), Sanitize::$adminFormTags);

                $element = $element->getNext();
            }

            echo '</div>';
        }
    }

}