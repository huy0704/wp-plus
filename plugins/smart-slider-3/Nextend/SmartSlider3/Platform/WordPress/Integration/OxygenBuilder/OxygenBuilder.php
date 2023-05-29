<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\OxygenBuilder;


use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class OxygenBuilder {

    public function __construct() {
        if (defined('CT_VERSION') && (Request::$REQUEST->getCmd('action') == 'ct_render_shortcode' || Request::$REQUEST->getCmd('action') == 'ct_get_post_data')) {
            self::forceShortcodeIframe();
        }
    }

    public function forceShortcodeIframe() {

        Shortcode::forceIframe('OxygenBuilder', true);
    }
}