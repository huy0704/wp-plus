<?php


namespace Nextend\SmartSlider3\Slider\WordPress;


use Jetpack_Photon;
use Nextend\SmartSlider3\Slider\Base\PlatformSliderBase;

class PlatformSlider extends PlatformSliderBase {

    public function addCMSFunctions($text) {

        $text = do_shortcode(preg_replace('/\[smartslider3 slider=[0-9]+\]/', '', preg_replace('/\[smartslider3 slider="[0-9]+"\]/', '', $text)));

        return $this->applyFilters($text);
    }

    private function applyFilters($text) {
        $text = apply_filters('translate_text', $text);

        if (function_exists('jetpack_photon_url')) {
            $text = Jetpack_Photon::filter_the_content(preg_replace_callback('/data-(desktop|tablet|mobile)="(.*?)"/', array(
                $this,
                'deviceImageReplaceCallback'
            ), $text));
        }

        return $text;
    }

    public function deviceImageReplaceCallback($matches) {

        if (apply_filters('jetpack_photon_skip_image', false, $matches[2], $matches[2])) {
            return $matches[0];
        }

        return 'data-' . $matches[1] . '="' . jetpack_photon_url($matches[2]) . '"';
    }

}