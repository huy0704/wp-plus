<?php


namespace Nextend\Framework\Form\Fieldset\LayerWindow;


use Nextend\Framework\Form\Container\ContainerAlternative;

class FieldsetLayerWindowLabelFields extends FieldsetLayerWindow {

    /**
     * @var FieldsetInsideLabel
     */
    protected $fieldsetLabel;

    public function __construct($insertAt, $name, $label, $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $parameters);

        $labelContainer      = new ContainerAlternative($this->parent, $name . '-container-label');
        $this->fieldsetLabel = new FieldsetInsideLabel($labelContainer, $name . '-label', false);
    }

    /**
     * @return FieldsetInsideLabel
     */
    public function getFieldsetLabel() {
        return $this->fieldsetLabel;
    }

    protected function renderTitle() {
        echo '<div class="n2_fields_layer_window__label">' . esc_html($this->label) . '</div>';

        echo '<div class="n2_fields_layer_window__title_fields">';
        if ($this->fieldsetLabel->hasFields()) {
            $this->fieldsetLabel->renderContainer();
        }
        echo '</div>';
    }
}