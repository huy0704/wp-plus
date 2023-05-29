<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\ACF;

class ACF {

    public function __construct() {

        if (class_exists('acf', false)) {

            add_action('acf/register_fields', array(
                $this,
                'registerFields'
            ));

            add_action('acf/include_fields', array(
                $this,
                'registerFields'
            ));

        }
    }

    public function registerFields() {

        new AcfFieldSmartSlider3();
    }
}