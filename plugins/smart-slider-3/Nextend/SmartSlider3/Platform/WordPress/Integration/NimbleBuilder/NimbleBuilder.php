<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\NimbleBuilder;


use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class NimbleBuilder {

    public function __construct() {

        if (defined('NIMBLE_VERSION')) {
            add_action('wp_ajax_sek_get_content', array(
                $this,
                'forceShortcodeIframe'
            ), -1);
        }
    }

    public function forceShortcodeIframe() {
        Shortcode::forceIframe('Nimble Builder', true);
    }
}