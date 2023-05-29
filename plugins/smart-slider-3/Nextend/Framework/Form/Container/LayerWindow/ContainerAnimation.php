<?php


namespace Nextend\Framework\Form\Container\LayerWindow;


use Nextend\Framework\Form\ContainerGeneral;

class ContainerAnimation extends ContainerGeneral {

    /**
     * @param $name
     * @param $label
     *
     * @return ContainerAnimationTab
     */
    public function createTab($name, $label) {
        return new ContainerAnimationTab($this, $name, $label);
    }

    public function renderContainer() {
        echo '<div class="n2_container_animation" data-field="animation-' . esc_attr($this->name) . '">';
        echo '<div class="n2_container_animation__buttons">';

        $element = $this->first;
        while ($element) {

            if ($element instanceof ContainerAnimationTab) {
                echo '<div class="n2_container_animation__button" data-related-tab="' . esc_attr($element->getName()) . '">' . esc_html($element->getLabel()) . '</div>';
            }

            $element = $element->getNext();
        }

        echo '</div>';
        echo '<div class="n2_container_animation__tabs">';
        parent::renderContainer();
        echo '</div>';
        echo '</div>';
    }
}