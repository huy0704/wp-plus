<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Brizy;

use Brizy_Editor;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class Brizy {

    public function __construct() {
        if (class_exists('Brizy_Editor') && Request::$REQUEST->getVar('action') == Brizy_Editor::prefix() . '_shortcode_content') {
            Shortcode::forceIframe('Brizy', true);
        }
    }
}