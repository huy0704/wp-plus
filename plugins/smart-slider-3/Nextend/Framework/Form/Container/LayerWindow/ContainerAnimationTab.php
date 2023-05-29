<?php


namespace Nextend\Framework\Form\Container\LayerWindow;


use Nextend\Framework\Form\ContainerGeneral;

class ContainerAnimationTab extends ContainerGeneral {

    public function renderContainer() {
        echo '<div class="n2_container_animation__tab" data-tab="' . esc_attr($this->name) . '">';
        parent::renderContainer();
        echo '</div>';
    }
}