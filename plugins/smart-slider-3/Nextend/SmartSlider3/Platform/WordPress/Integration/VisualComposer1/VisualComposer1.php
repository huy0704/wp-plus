<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\VisualComposer1;


use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;
use function vc_add_shortcode_param;
use function vc_map;

class VisualComposer1 {

    public function __construct() {
        add_action('vc_after_set_mode', array(
            $this,
            'init'
        ));
    }

    public function init() {
        $this->vc_add_element();

        add_action('vc_frontend_editor_render', array(
            $this,
            'forceShortcodeIframe'
        ));

        add_action('vc_front_load_page_', array(
            $this,
            'forceShortcodeIframe'
        ));

        add_action('vc_load_shortcode', array(
            Shortcode::class,
            'shortcodeModeToNormal'
        ), -1000000);

        add_action('vc_load_shortcode', array(
            $this,
            'forceShortcodeIframe'
        ));


        add_action('vc_before_init_base', array(
            $this,
            'vc_before_init_base'
        ));
    }

    public function vc_before_init_base() {
        add_filter('the_excerpt', array(
            $this,
            'filter_before_the_excerpt'
        ), -10000);

        add_filter('the_excerpt', array(
            $this,
            'filter_after_the_excerpt'
        ), 10000);
    }

    public function filter_before_the_excerpt($output) {
        Shortcode::shortcodeModeToNoop();

        return $output;
    }

    public function filter_after_the_excerpt($output) {
        Shortcode::shortcodeModeToNormal();

        return $output;
    }

    private function vc_add_element() {
        vc_add_shortcode_param('smartslider', array(
            $this,
            'field_smartslider'
        ));

        vc_map(array(
            "name"     => "Smart Slider 3",
            "base"     => "smartslider3",
            "category" => __('Content'),
            "params"   => array(
                array(
                    'type'        => 'smartslider',
                    'heading'     => 'Slider ID or Alias',
                    'param_name'  => 'slider',
                    'save_always' => true,
                    'description' => 'Select a slider to add it to your post or page.',
                    'admin_label' => true,
                )
            )
        ));

        add_action('admin_footer', array(
            $this,
            'add_admin_icon'
        ));
    }

    public function add_admin_icon() {
        ?>
        <style type="text/css">
            .wpb_smartslider3 .vc_element-icon {
                background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAYFBMVEUTr+8YnOQPxPkYnOQUsO8PxPkYnOQTr+5hp8Uoncg1kryMwtqh0ePT6PFvude83ers9vlFqc4Vi8EQo9kSls4Xo+j///8XoOcYm+QVp+oQwPcStPARuvQYneUUrewPxPkMc7TJAAAACHRSTlNt9G1uG8vNAnToTbkAAAFrSURBVHgBfZPr0qowDEXrBVB6b0KgUMv7v+UJ2MFy5tOVdIbuvXT0B6Lrumszr38wN1cuRXdv1q80dxa4/2F04srftc7zdtZqSrZeRTOfWc7XRqS5BtAj1E4SdZ3ROHLq5Ig5zem9Gbymjd1JJRXvBz7gLXdaKWXJWb+UQqTC4h3XVjurjEfIqXAICczTP7SUVlsDR8rCkpZ9wQD2ypG1RE++lxULkxYGDBoi+cTnLpR+Ewqoe0cSsnek4EhrwT6IQs7emhBrIZeB4IkMZED+fD5G5A9BE6kA+UQtwJMN5zF+E0YIiohkOAkx5n0jBO8BvSMyWLLtiFhAr0mHiD2RC/HgEMbebT8wxqD/E4a4D0rOETEYIhs4KcPCG9wKaeT2P/pp+CCGcdh2CJLe2B45OVaMhQnDQypp+jCNNeI1HoMYELl+VXMR7esnrbj9Fm6ia6fyPB3zod1e3nb6Sntnoetu7eWv9tLeuPwHrqBewxDhYIoAAAAASUVORK5CYII=);
            }
        </style>
        <?php
    }


    public function field_smartslider($settings, $value) {
        $value = htmlspecialchars($value);

        HelperTinyMCE::getInstance()
                     ->addForced();

        return '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '" type="text" value="' . $value . '" style="width:100px;vertical-align:middle;">
    <a href="#" onclick="NextendSmartSliderSelectModal(jQuery(this).siblings(\'input\')); return false;" class="vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw" title="Select slider">Select slider</a>';
    }

    public function forceShortcodeIframe() {

        Shortcode::forceIframe('visualcomposer', true);
    }
}