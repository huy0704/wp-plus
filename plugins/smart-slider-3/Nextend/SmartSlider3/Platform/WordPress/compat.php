<?php

use Nextend\SmartSlider3\Platform\WordPress\SmartSlider3PlatformWordPress;

if (!class_exists('SmartSlider3', false)) {

    class SmartSlider3 {

        public static function import($file) {

            return SmartSlider3PlatformWordPress::importSlider($file);
        }
    }

}