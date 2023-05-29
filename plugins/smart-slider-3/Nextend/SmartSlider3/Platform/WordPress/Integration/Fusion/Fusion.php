<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Fusion;


class Fusion {

    public function __construct() {
        add_action('fusion_builder_shortcodes_init', array(
            $this,
            'init'
        ));
    }

    public function init() {

        new FusionElementSmartSlider3();

        add_action('fusion_builder_before_init', array(
            $this,
            'action_fusion_builder_before_init'
        ));

        add_filter('fusion_builder_fields', array(
            $this,
            'filter_fusion_builder_fields'
        ));
    }

    public function action_fusion_builder_before_init() {

        fusion_builder_map(array(
            'name'            => 'Smart Slider 3',
            'shortcode'       => 'fusion_smartslider3',
            'icon'            => 'fusiona-uniF61C',
            'allow_generator' => true,
            'params'          => array(
                array(
                    'type'       => 'smartslider3',
                    'heading'    => 'Slider',
                    'param_name' => 'slider',
                    'value'      => '',
                )
            ),
        ));
    }

    public function filter_fusion_builder_fields($fields) {

        $fields[] = array(
            'smartslider3',
            dirname(__FILE__) . '/field-smartslider3.php'
        );

        return $fields;
    }
}