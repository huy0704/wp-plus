<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31lt;


class DiviModuleSmartSliderFullwidth extends DiviModuleSmartSlider {

    function init() {
        parent::init();
        $this->fullwidth = true;
        $this->slug      = 'et_pb_nextend_smart_slider_3_fullwidth';
    }
}