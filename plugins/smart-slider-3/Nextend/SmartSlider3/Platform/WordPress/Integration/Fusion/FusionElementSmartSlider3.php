<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Fusion;


use Fusion_Element;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class FusionElementSmartSlider3 extends Fusion_Element {

    public function __construct() {
        parent::__construct();

        add_action('fusion_load_module', array(
            $this,
            'force_iframe'
        ));

        add_shortcode('fusion_smartslider3', array(
            $this,
            'render'
        ));
    }

    public function render($args, $content = '') {

        if (!empty($args)) {
            return do_shortcode('[smartslider3 slider="' . $args['slider'] . '"]');
        } else {
            return '<!-- Avada Builder empty Smart Slider element -->';
        }
    }

    public function force_iframe() {
        Shortcode::forceIframe('fusion', true);
    }
}