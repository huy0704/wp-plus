<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi;


use Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31ge\DiviExtensionSmartSlider3;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31lt\DiviV31lt;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class Divi {

    public function __construct() {

        add_action('et_builder_ready', array(
            $this,
            'action_et_builder_ready'
        ));

        add_action('divi_extensions_init', array(
            $this,
            'action_divi_extensions_init'
        ));

        add_action('et_fb_framework_loaded', array(
            $this,
            'forceShortcodeIframe'
        ));
    }

    public function action_et_builder_ready() {

        if (version_compare(ET_CORE_VERSION, '3.1', '<')) {

            new DiviV31lt();
        }

        if (is_et_pb_preview()) {
            $this->forceShortcodeIframe();
        }
    }

    public function action_divi_extensions_init() {

        if (version_compare(ET_CORE_VERSION, '3.1', '>=')) {

            new DiviExtensionSmartSlider3();
        }
    }

    public function forceShortcodeIframe() {
        Shortcode::forceIframe('Divi', true);
    }
}