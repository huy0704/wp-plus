<?php


namespace Nextend\Framework\Form\Fieldset;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\AbstractFieldset;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class FieldsetVisualSet extends AbstractFieldset {

    public function renderContainer() {
        echo '<div class="n2_form__visual_set" data-field="visual-set-' . esc_attr($this->name) . '">';

        echo "<div class='n2_form__visual_set_label'>" . esc_html($this->label) . '</div>';

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

        $classes = array(
            'n2_field',
            $element->getRowClass()
        );

        echo wp_kses(Html::openTag('div', array(
                'class'      => implode(' ', array_filter($classes)),
                'data-field' => $element->getID()
            ) + $element->getRowAttributes()), Sanitize::$adminFormTags);

        $element->displayElement();

        echo "</div>";

        return ob_get_clean();
    }
}