<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\BoldGrid;

use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class BoldGrid {

    public function __construct() {
        if (class_exists('Boldgrid_Editor') && (Request::$REQUEST->getCmd('action') == 'boldgrid_shortcode_smartslider3' || Request::$REQUEST->getCmd('action') == 'boldgrid_component_wp_smartslider3')) {
            Shortcode::forceIframe('Boldgrid', true);
        }
    }
}