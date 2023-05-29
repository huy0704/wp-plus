<?php


namespace Nextend\Framework\Form\Fieldset;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Sanitize;

class FieldsetRowPlain extends FieldsetRow {

    public function renderContainer() {
        echo '<div class="n2_form__table_row_plain" data-field="table-row-plain-' . esc_attr($this->name) . '">';

        $element = $this->first;
        while ($element) {
            echo wp_kses($this->decorateElement($element), Sanitize::$adminFormTags);

            $element = $element->getNext();
        }

        echo '</div>';
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        ob_start();

        $element->displayElement();

        return ob_get_clean();
    }
}