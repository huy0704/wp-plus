<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Elementor;


use Elementor\Plugin;
use Elementor\Widget_Base;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class ElementorWidgetSmartSlider350 extends Widget_Base {

    public function get_name() {
        return 'smartslider';
    }

    public function get_title() {
        return 'Smart Slider';
    }

    public function get_icon() {
        return 'eicon-slider-3d';
    }

    protected function _register_controls() {

        $this->start_controls_section('section_smart_slider_elementor', [
            'label' => esc_html('Smart Slider'),
        ]);

        $this->add_control('smartsliderid', [
            'label'   => 'Slider ID or Alias',
            'type'    => 'smartsliderfield',
            'default' => '',
            'title'   => 'Slider ID or Alias',
        ]);

        $this->end_controls_section();

    }

    protected function render() {
        if (Plugin::instance()->editor->is_edit_mode() || Plugin::instance()->preview->is_preview_mode()) {

            // PHPCS - Content already escaped
            echo Shortcode::renderIframe($this->get_settings('smartsliderid')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            $sliderIDorAlias = $this->get_settings('smartsliderid');
            if (is_numeric($sliderIDorAlias)) {
                echo do_shortcode('[smartslider3 slider=' . $sliderIDorAlias . ']');
            } else {
                echo do_shortcode('[smartslider3 alias="' . $sliderIDorAlias . '"]');
            }
        }
    }

    /**
     * Must be declared as empty method to prevent issues with SEO plugins.
     */
    public function render_plain_content() {
    }

    protected function content_template() {

        // PHPCS - Content already escaped
        echo Shortcode::renderIframe('{{{settings.smartsliderid}}}'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}