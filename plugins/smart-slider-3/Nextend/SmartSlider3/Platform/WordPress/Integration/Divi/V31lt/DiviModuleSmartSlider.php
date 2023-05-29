<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31lt;


use ET_Builder_Element;
use ET_Builder_Module;
use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;

class DiviModuleSmartSlider extends ET_Builder_Module {

    function init() {
        $this->name               = 'Smart Slider 3';
        $this->slug               = 'et_pb_nextend_smart_slider_3';
        $this->whitelisted_fields = array(
            'admin_label'
        );
        if (defined('EXTRA_LAYOUT_POST_TYPE')) {
            $this->post_types = array(EXTRA_LAYOUT_POST_TYPE);
        }

        $this->whitelisted_fields = array(
            'slider',
        );

        $this->fields_defaults = array();

        $this->advanced_options = array();

        add_action('admin_footer', array(
            $this,
            'add_admin_icon'
        ));

        HelperTinyMCE::getInstance()
                     ->addForced();
    }

    public function add_admin_icon() {
        ?>
        <style type="text/css">
            .et-pb-all-modules .et_pb_nextend_smart_slider_3::before,
            .et-pb-all-modules .et_pb_nextend_smart_slider_3_fullwidth::before {
                content: 'S';
            }
        </style>
        <?php
    }

    function get_fields() {
        $fields = array(
            'slider'      => array(
                'label'               => 'Slider',
                'option_category'     => 'basic_option',
                'type'                => 'text',
                'renderer'            => array(
                    $this,
                    'field_smart_slider_renderer'
                ),
                'renderer_with_field' => true
            ),
            'admin_label' => array(
                'label'       => esc_html__('Admin Label', 'et_builder'),
                'type'        => 'text',
                'description' => esc_html__('This will change the label of the module in the builder for easy identification.', 'et_builder'),
                'toggle_slug' => 'admin_label',
            )
        );

        return $fields;
    }

    function shortcode_callback($atts, $content, $function_name) {
        $sliderIdOrAlias = $this->shortcode_atts['slider'];
        $module_class    = '';
        $module_class    = ET_Builder_Element::add_module_order_class($module_class, $function_name);

        if (!is_numeric($sliderIdOrAlias)) {
            return '<div class="et_pb_module et-waypoint ' . $module_class . ' et_pb_animation_off">' . do_shortcode('[smartslider3 alias="' . $sliderIdOrAlias . '"]') . '</div>';
        }

        return '<div class="et_pb_module et-waypoint ' . $module_class . ' et_pb_animation_off">' . do_shortcode('[smartslider3 slider=' . $sliderIdOrAlias . ']') . '</div>';
    }

    public function field_smart_slider_renderer() {
        $output = sprintf('<input type="button" class="button button-upload" value="%1$s" onclick="NextendSmartSliderSelectModal(jQuery(this).siblings(\'.regular-text\')); return false;">', n2_('Select Slider'));

        return $output;
    }
}