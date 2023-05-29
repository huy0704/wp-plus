<?php

namespace Nextend\Framework\Form\Fieldset\LayerWindow;

use Nextend\Framework\Form\Element\Button\ButtonIcon;
use Nextend\Framework\Form\Element\Select;

class FieldsetDesign extends FieldsetLayerWindowLabelFields {

    public function __construct($insertAt, $name, $label) {
        parent::__construct($insertAt, $name, $label);

        $this->addAttribute('data-fieldset-type', 'design');

        new ButtonIcon($this->fieldsetLabel, $name . '-reset-to-normal', false, 'ssi_16 ssi_16--reset', array(
            'hoverTip'      => n2_('Reset to normal state'),
            'rowAttributes' => array(
                'data-design-feature' => 'reset-to-normal'
            )
        ));
        new Select($this->fieldsetLabel, $name . '-element', false, '', array(
            'rowAttributes' => array(
                'data-design-feature' => 'element'
            )
        ));
        new Select($this->fieldsetLabel, $name . '-state', false, '', array(
            'rowAttributes' => array(
                'data-design-feature' => 'state'
            )
        ));
    }

    protected function renderTitle() {

        echo '<div class="n2_fields_layer_window__label">' . esc_html($this->label) . '</div>';

        if ($this->fieldsetLabel->hasFields()) {
            echo '<div class="n2_fields_layer_window__title_fields">';
            $this->fieldsetLabel->renderContainer();
            echo '</div>';
        }
    }

    /**
     * @param mixed $parentDesign
     */
    public function setParentDesign($parentDesign) {
        $this->addAttribute('data-parent-design', $parentDesign);
    }
}