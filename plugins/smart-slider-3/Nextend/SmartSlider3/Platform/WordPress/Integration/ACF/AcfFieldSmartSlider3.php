<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\ACF;

use acf_field;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class AcfFieldSmartSlider3 extends acf_field {

    public $label = 'Smart Slider 3';

    function __construct() {
        $this->name = 'acf_smartslider3';

        parent::__construct();
    }

    function load_value($value, $post_id, $field) {
        return $value;
    }

    function update_value($value, $field, $post_id) {
        return $value;
    }

    public function format_value($value, $field) {
        if (is_admin()) {
            return $value;
        }

        if (!$value) {
            return false;
        }

        if (!is_numeric($value)) {
            return do_shortcode('[smartslider3 alias="' . $value . '"]');
        }

        return do_shortcode('[smartslider3 slider=' . $value . ']');
    }

    public function format_value_for_api($value, $field) {

        if (is_admin()) {
            return $value;
        }

        return $this->format_value($value, $field);
    }

    function load_field($field) {
        return $field;
    }

    public function create_field($field) {
        $this->render_field($field);
    }

    public function render_field($field) {

        $applicationType = ApplicationSmartSlider3::getInstance()
                                                  ->getApplicationTypeAdmin();

        $slidersModel = new ModelSliders($applicationType);

        $choices = array();
        foreach ($slidersModel->getAll(0, 'published') as $slider) {
            if ($slider['type'] == 'group') {

                $subChoices = array();
                if (!empty($slider['alias'])) {
                    $subChoices[$slider['alias']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $subChoices[$slider['id']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #' . $slider['id'];
                foreach ($slidersModel->getAll($slider['id'], 'published') as $_slider) {
                    if (!empty($_slider['alias'])) {
                        $subChoices[$_slider['alias']] = $_slider['title'] . ' #Alias: ' . $_slider['alias'];
                    }
                    $subChoices[$_slider['id']] = $_slider['title'] . ' #' . $_slider['id'];
                }

                $choices[$slider['id']] = array(
                    'label'   => $slider['title'] . ' #' . $slider['id'],
                    'choices' => $subChoices
                );
            } else {
                if (!empty($slider['alias'])) {
                    $choices[$slider['alias']] = $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $choices[$slider['id']] = $slider['title'] . ' #' . $slider['id'];
            }
        }

        ?>
        <table style="width:100%;border:0;">
            <tr>
                <td style="white-space: nowrap;">
                    <a href="#" onclick="<?php echo "NextendSmartSliderSelectModal(jQuery('#" . esc_js($field['id']) . "'));"; ?>return false;" class="button" title="<?php n2_e('Select Slider'); ?>"><?php n2_e('Select Slider'); ?></a>
                    <span style="line-height:2;padding:10px;"><?php n2_e('OR'); ?></span>
                </td>
                <td style="width:90%;">
                    <select id="<?php echo esc_attr($field['id']); ?>" class="<?php echo esc_attr($field['class']); ?>" name="<?php echo esc_attr($field['name']); ?>">
                        <?php if (!isset($field['required']) || !$field['required']): ?>
                            <option value=""><?php n2_e('None'); ?></option>
                        <?php endif; ?>
                        <?php
                        foreach ($choices as $id => $choice) {
                            if (is_array($choice)) {
                                ?>
                                <optgroup label="<?php echo esc_attr($choice['label']); ?>">
                                    <?php
                                    foreach ($choice['choices'] as $_id => $_choice) {
                                        ?>
                                        <option <?php if ($_id == $field['value']){ ?>selected <?php } ?>value="<?php echo esc_attr($_id); ?>"><?php echo esc_html($_choice); ?></option>
                                        <?php
                                    }
                                    ?>
                                </optgroup>
                                <?php
                            } else {
                                ?>
                                <option <?php if ($id == $field['value']){ ?>selected <?php } ?>value="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    function create_options($field) {

    }

    function input_admin_enqueue_scripts() {

    }

    function input_admin_head() {

    }

    function field_group_admin_enqueue_scripts() {

    }

    function field_group_admin_head() {

    }
}