<?php


namespace Nextend\Framework\Form\Container\LayerWindow;


use Nextend\Framework\Form\ContainerGeneral;
use Nextend\Framework\Form\ContainerInterface;

class ContainerSettings extends ContainerGeneral {

    public function __construct(ContainerInterface $insertAt, $name, $parameters = array()) {
        parent::__construct($insertAt, $name, false, $parameters);
    }

    public function renderContainer() {
        echo '<div class="n2_ss_layer_window__tab_panel" data-panel="' . esc_attr($this->name) . '">';
        parent::renderContainer();
        echo '</div>';
    }
}