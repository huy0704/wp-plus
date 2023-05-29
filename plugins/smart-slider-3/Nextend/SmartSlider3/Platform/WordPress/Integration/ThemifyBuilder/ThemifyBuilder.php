<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\ThemifyBuilder;

use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;
use Themify_Builder_Model;

class ThemifyBuilder {

    public function __construct() {

        add_action('themify_builder_setup_modules', array(
            $this,
            'init'
        ));
    }

    //WORKING
    public function init() {

        /**
         * Fix for slider shortcode appearance in Themmify Builder frontend editor
         */
        add_action('wp_ajax_tb_render_element_shortcode', array(
            $this,
            'forceShortcodeIframe'
        ));
        /**
         * Fix for newly added slider widget appearance in Themmify Builder frontend editor
         */
        add_action('wp_ajax_tb_load_module_partial', array(
            $this,
            'forceShortcodeIframe'
        ));
        /**
         * Fix for already added slider widget appearance in Themmify Builder frontend editor
         */
        add_action('wp_ajax_tb_render_element', array(
            $this,
            'forceShortcodeIframe'
        ));


    }

    public function forceShortcodeIframe() {
        Shortcode::forceIframe('Themify Builder', true);
    }
}