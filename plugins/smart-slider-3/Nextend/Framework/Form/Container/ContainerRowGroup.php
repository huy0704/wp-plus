<?php


namespace Nextend\Framework\Form\Container;


use Nextend\Framework\Form\ContainerGeneral;
use Nextend\Framework\Form\Fieldset\FieldsetRow;

class ContainerRowGroup extends ContainerGeneral {

    public function renderContainer() {
        echo '<div class="n2_form__table_row_group" data-field="table-row-group-' . esc_attr($this->name) . '">';
        if ($this->label !== false) {
            echo '<div class="n2_form__table_row_group_label">';
            echo esc_html($this->label);
            echo '</div>';
        }

        echo '<div class="n2_form__table_row_group_rows" data-field="table-row-group-rows-' . esc_attr($this->name) . '">';
        parent::renderContainer();
        echo '</div>';
        echo '</div>';
    }

    /**
     * @param $name
     *
     * @return FieldsetRow
     */
    public function createRow($name) {

        return new FieldsetRow($this, $name);
    }
}