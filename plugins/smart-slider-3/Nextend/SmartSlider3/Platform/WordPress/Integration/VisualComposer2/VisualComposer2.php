<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\VisualComposer2;

use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class VisualComposer2 {

    public function __construct() {
        if (class_exists('VcvEnv') && Request::$REQUEST->getInt('vcv-ajax')) {
            Shortcode::forceIframe('VisualComposer2', true);
        }
    }
}