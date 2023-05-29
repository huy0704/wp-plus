<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\TatsuBuilder;

use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class TatsuBuilder {

    public function __construct() {
        if (class_exists('Tatsu_Builder') && Request::$REQUEST->getCmd('action') == 'tatsu_module' && Request::$REQUEST->getVar('module') !== null) {
            $tatsuModuleData = json_decode(Request::$REQUEST->getVar('module'));
            if ($tatsuModuleData && is_object($tatsuModuleData) && isset($tatsuModuleData->name) && $tatsuModuleData->name === 'tatsu_text_with_shortcodes') {
                Shortcode::forceIframe('TatsuBuilder', true);
            }
        }
    }
}