<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31lt;


use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class DiviV31lt {

    public function __construct() {

        add_filter('et_builder_get_child_modules', array(
            $this,
            'filter_et_builder_get_child_modules'
        ));

        if (function_exists('et_fb_is_enabled') && et_fb_is_enabled()) {
            $this->forceShortcodeIframe();
        }

        if (function_exists('is_et_pb_preview') && is_et_pb_preview()) {
            $this->forceShortcodeIframe();
        }

        add_action('wp_ajax_et_fb_retrieve_builder_data', array(
            $this,
            'forceShortcodeIframe'
        ), 9);

        new DiviModuleSmartSlider();
        new DiviModuleSmartSliderFullwidth();
    }

    public function filter_et_builder_get_child_modules($child_modules) {

        if ($child_modules === '') {
            $child_modules = array();
        }

        return $child_modules;
    }

    public function forceShortcodeIframe() {

        Shortcode::forceIframe('divi', true);
    }
}