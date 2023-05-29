<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\MotoPressCE;


use MPCEShortcode;

class MotoPressCE {

    public function __construct() {

        if (class_exists('MPCEShortcode', false)) {
            $this->init();
        }
    }

    public function init() {

        if (MPCEShortcode::isContentEditor()) {
            remove_shortcode('smartslider3');
        }
    }
}