<?php


namespace Nextend\Framework\Form\Container;


use Nextend\Framework\Form\ContainerGeneral;

class ContainerSubform extends ContainerGeneral {

    public function renderContainer() {
        echo '<div id="' . esc_attr($this->getId()) . '" class="n2_form__subform">';
        parent::renderContainer();
        echo '</div>';
    }

    public function getId() {
        return 'n2_form__subform_' . $this->controlName . '_' . $this->name;
    }
}