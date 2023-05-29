<?php


namespace Nextend\Framework\Form\Fieldset\LayerWindow;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\AbstractFieldset;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class FieldsetLayerWindow extends AbstractFieldset {

    protected $attributes = array();

    public function renderContainer() {

        echo wp_kses(Html::openTag('div', array(
                'class'      => 'n2_fields_layer_window',
                'data-field' => 'fieldset-layer-window-' . $this->name
            ) + $this->attributes), Sanitize::$adminFormTags);

        if (!empty($this->label)) {
            echo '<div class="n2_fields_layer_window__title">';
            $this->renderTitle();
            echo '</div>';
        }

        echo '<div class="n2_fields_layer_window__fields">';

        $element = $this->first;
        while ($element) {
            echo wp_kses($this->decorateElement($element), Sanitize::$adminFormTags);

            $element = $element->getNext();
        }

        echo '</div>';
        echo '</div>';
    }

    protected function renderTitle() {

        echo '<div class="n2_fields_layer_window__label">' . esc_html($this->label) . '</div>';
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

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }
}