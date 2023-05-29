<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Unyson;


class Unyson {

    public function __construct() {
        add_filter('fw_extensions_locations', array(
            $this,
            'filter_fw_extensions_locations'
        ));
    }

    public function filter_fw_extensions_locations($locations) {

        if (version_compare(fw()->manifest->get_version(), '2.6.0', '>=')) {
            $path             = dirname(__FILE__);
            $locations[$path] = plugin_dir_url(__FILE__);
        }

        return $locations;
    }
}