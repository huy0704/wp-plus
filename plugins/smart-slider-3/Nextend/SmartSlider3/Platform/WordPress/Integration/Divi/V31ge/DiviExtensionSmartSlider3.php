<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31ge;


use DiviExtension;

class DiviExtensionSmartSlider3 extends DiviExtension {

    public $gettext_domain = 'smart-slider-3';

    public $name = 'smart-slider-3';

    public $version = '1.0.0';

    public function __construct($name = 'smart-slider-3', $args = array()) {
        $this->plugin_dir     = plugin_dir_path(__FILE__);
        $this->plugin_dir_url = plugin_dir_url(__FILE__);

        $this->_builder_js_data = array(
            'iframeUrl' => site_url('/') . '?n2prerender=1&n2app=smartslider&n2controller=slider&n2action=iframe&h=' . sha1(NONCE_SALT . date('Y-m-d'))
        );

        parent::__construct($name, $args);

        add_action('admin_enqueue_scripts', array(
            $this,
            'admin_enqueue_scripts'
        ));

        add_action('smartslider3_slider_changed', array(
            $this,
            'clearDiviCache'
        ));
    }

    public function admin_enqueue_scripts() {

        $styles_url = "{$this->plugin_dir_url}styles/admin/style.min.css";

        wp_enqueue_style("{$this->name}-admin-styles", $styles_url, array(), $this->version);

        ?>
        <script>
            if (typeof localStorage !== 'undefined') {
                localStorage.removeItem('et_pb_templates_et_pb_nextend_smart_slider_3');
                localStorage.removeItem('et_pb_templates_et_pb_nextend_smart_slider_3_fullwidth');
            }
        </script>
        <?php
    }

    public function clearDiviCache() {
        if (function_exists('et_fb_delete_builder_assets')) {
            /**
             * We must delete the js files in wp-content/cache/et/ folder to refresh the slider list in Divi module
             */
            et_fb_delete_builder_assets();
        }
    }

    public function wp_hook_enqueue_scripts() {
        parent::wp_hook_enqueue_scripts();

        if (!et_core_is_fb_enabled()) {
            wp_dequeue_style("{$this->name}-styles");
        }
    }

    protected function _enqueue_bundles() {
        parent::_enqueue_bundles();

        if (!et_core_is_fb_enabled()) {
            wp_dequeue_script("{$this->name}-frontend-bundle");
        }
    }
}