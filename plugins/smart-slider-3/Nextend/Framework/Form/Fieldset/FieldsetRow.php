<?php


namespace Nextend\Framework\Form\Fieldset;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\AbstractFieldset;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class FieldsetRow extends AbstractFieldset {

    public function __construct($insertAt, $name, $parameters = array()) {
        parent::__construct($insertAt, $name, false, $parameters);
    }

    public function renderContainer() {

        $classes = array('n2_form__table_row');
        if (!$this->isVisible) {
            $classes[] = 'n2_form__table_row--hidden';
        }

        echo wp_kses(Html::openTag('div', array(
            'class'      => implode(' ', $classes),
            'data-field' => 'table-row-' . $this->name
        )), Sanitize::$adminFormTags);

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

        $hasLabel = $element->hasLabel();

        $classes = array(
            'n2_field',
            $element->getLabelClass(),
            $element->getRowClass()
        );

        echo wp_kses(Html::openTag('div', array(
                'class'      => implode(' ', array_filter($classes)),
                'data-field' => $element->getID()
            ) + $element->getRowAttributes()), Sanitize::$adminFormTags);

        if ($hasLabel) {
            echo "<div class='n2_field__label'>";
            $element->displayLabel();
            echo "</div>";
        }

        echo "<div class='n2_field__element'>";
        $element->displayElement();
        echo "</div>";

        echo "</div>";

        return ob_get_clean();
    }
}