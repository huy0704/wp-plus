<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Elementor;


use Elementor\Plugin;
use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;
use Nextend\SmartSlider3\Platform\WordPress\Widget\WidgetSmartSlider3;

class Elementor {

    public function __construct() {

        add_action('elementor/init', array(
            $this,
            'init'
        ), 0);
    }

    public function init() {

        if (!defined('SMART_SLIDER_ELEMENTOR_WIDGET_ALLOWED')) {
            add_filter('elementor/widgets/black_list', function ($black_list) {
                $black_list[] = 'N2SS3Widget';
                $black_list[] = WidgetSmartSlider3::class;

                return $black_list;
            });
        }

        add_action('template_redirect', array(
            $this,
            'action_template_redirect'
        ), -1);

        add_action('admin_action_elementor', array(
            $this,
            'forceShortcodeIframe'
        ), -10000);

        add_action('wp_ajax_elementor_ajax', array(
            $this,
            'forceShortcodeIframe'
        ), -1);

        add_action('wp_ajax_elementor_render_widget', array(
            $this,
            'forceShortcodeIframe'
        ), -1);


        if ($this->elementor_version_compare('3.5.0')) {
            add_action('elementor/widgets/register', array(
                $this,
                'action_widgets_registered'
            ), 100);

            add_action('elementor/controls/register', array(
                $this,
                'action_controls_registered'
            ));
        } else {
            add_action('elementor/widgets/widgets_registered', array(
                $this,
                'action_widgets_registered'
            ), 100);

            add_action('elementor/controls/controls_registered', array(
                $this,
                'action_controls_registered'
            ));
        }

        add_action('elementor/editor/before_enqueue_styles', array(
            $this,
            'action_editor_before_enqueue_styles'
        ));

        add_action('elementor/editor/before_enqueue_scripts', array(
            HelperTinyMCE::getInstance(),
            'addForcedFrontend'
        ));
    }

    public function action_template_redirect() {

        if (Plugin::instance()->editor->is_edit_mode() || Plugin::instance()->preview->is_preview_mode()) {
            $this->forceShortcodeIframe();
        }
    }

    public function action_widgets_registered() {

        $widget_manager = Plugin::$instance->widgets_manager;
        if ($this->elementor_version_compare('3.5.0')) {
            $widget_manager->register(new ElementorWidgetSmartSlider());

        } else if ($this->elementor_version_compare('2.9.0')) {
            $widget_manager->register_widget_type(new ElementorWidgetSmartSlider350());

        } else {
            $widget_manager->register_widget_type(new ElementorWidgetSmartSlider290());
        }
    }

    public function action_controls_registered($controls_manager) {
        if ($this->elementor_version_compare('3.5.0')) {
            $controls_manager->register(new ElementorControlSmartSlider());
        } else {
            $controls_manager->register_control('smartsliderfield', new ElementorControlSmartSlider());
        }
    }

    public function forceShortcodeIframe() {

        Shortcode::forceIframe('Elementor', true);
    }

    public function action_editor_before_enqueue_styles() {

        HelperTinyMCE::getInstance()
                     ->initButtonDialog();
    }

    public function elementor_version_compare($version, $compare = '>=') {
        if (defined('ELEMENTOR_VERSION')) {
            return version_compare(ELEMENTOR_VERSION, $version, $compare);
        } else {
            // ELEMENTOR_VERSION is only not defined in very old versions, what we don't support. So the real version number here doesn't matters.
            return version_compare('1.0.0', $version, $compare);
        }
    }
}