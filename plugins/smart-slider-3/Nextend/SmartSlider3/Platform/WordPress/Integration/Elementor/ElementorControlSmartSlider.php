<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Elementor;

class_exists('\Elementor\Group_Control_Background');

class ElementorControlSmartSlider extends AbstractControl {

    public function get_type() {
        return 'smartsliderfield';
    }

    public function content_template() {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <a style="margin-bottom:10px;" href="#" onclick="NextendSmartSliderSelectModal(jQuery(this).siblings('input')); return false;" class="button button-primary elementor-button elementor-button-smartslider" title="Select slider">Select
                    slider</a>
                <input type="{{ data.input_type }}" title="{{ data.title }}" data-setting="{{ data.name }}">
            </div>
        </div>
        <# if(data.controlValue == ''){NextendSmartSliderSelectModal(function(){return jQuery('[data-setting="smartsliderid"]')})} #>
        <?php
    }

    public function get_default_settings() {
        return [
            'input_type' => 'text',
        ];
    }
}