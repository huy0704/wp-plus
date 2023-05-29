<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Jetpack;


use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Plugin;

class Jetpack {

    public function __construct() {

        if (defined('JETPACK__VERSION')) {

            Plugin::addAction('n2_assets_manager_started', array(
                $this,
                'action_assets_manager_started'
            ));

            add_filter('kses_allowed_protocols', array(
                $this,
                'kses_allowed_protocols'
            ), 10, 1);
        }
    }

    public function action_assets_manager_started() {

        add_filter('jetpack_photon_skip_image', array(
            $this,
            'filter_jetpack_photon_skip_image'
        ), 10, 3);
    }

    public function filter_jetpack_photon_skip_image($val, $src, $tag) {

        if (AssetManager::$image->match($src)) {
            return true;
        }

        return $val;
    }

    public function kses_allowed_protocols($protocols) {
        $protocols[] = 'data';

        return $protocols;
    }
}