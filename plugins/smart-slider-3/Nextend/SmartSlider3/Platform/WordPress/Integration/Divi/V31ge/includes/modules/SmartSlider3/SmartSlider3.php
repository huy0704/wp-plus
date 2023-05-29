<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\V31ge;

use ET_Builder_Module;
use Nextend\Framework\Asset\Builder\BuilderJs;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class ET_Builder_Module_SmartSlider3 extends ET_Builder_Module {

    protected $module_credits = array(
        'module_uri' => 'https://smartslider3.com',
        'author'     => 'Nextendweb',
        'author_uri' => 'https://nextendweb.com',
    );

    public function init() {
        $this->name       = 'Smart Slider 3';
        $this->slug       = 'et_pb_nextend_smart_slider_3';
        $this->vb_support = 'on';


        $this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                    'content' => esc_html__('Content', 'et_builder')
                ),
            ),
        );
    }

    public function add_styles_scripts() {
        ?>
        <script>
            window.SmartSlider3IframeUrl = <?php echo json_encode(site_url('/') . '?n2prerender=1&n2app=smartslider&n2controller=slider&n2action=iframe&h=' . sha1(NONCE_SALT . date('Y-m-d'))); ?>;

            <?php
            $path = ApplicationTypeFrontend::getAssetsPath() . '/dist/iframe.min.js';
            add_filter('js_escape', 'Nextend\Framework\Sanitize::esc_js_filter', 10, 2);
            if (file_exists($path)) {
                echo esc_js(file_get_contents($path));
            } else {
            }
            remove_filter('js_escape', 'Nextend\Framework\Sanitize::esc_js_filter', 10);
            ?>
        </script>
        <?php
    }

    public function get_fields() {

        if (et_core_is_fb_enabled()) {
            add_action('wp_footer', array(
                $this,
                'add_styles_scripts'
            ));
        }

        $applicationType = ApplicationSmartSlider3::getInstance()
                                                  ->getApplicationTypeAdmin();


        $slidersModel = new ModelSliders($applicationType);

        $options    = array();
        $options[-1] = 'None';
        foreach ($slidersModel->getAll(0, 'published') as $slider) {
            if ($slider['type'] == 'group') {

                $subChoices = array();
                if (!empty($slider['alias'])) {
                    $subChoices[$slider['alias']] = '-- ' . n2_('Whole group') . ' - ' . $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $subChoices[$slider['id']] = '-- ' . n2_('Whole group') . ' - ' . $slider['title'] . ' #' . $slider['id'];

                foreach ($slidersModel->getAll($slider['id'], 'published') as $_slider) {
                    if (!empty($_slider['alias'])) {
                        $subChoices[$_slider['alias']] = '-- ' . $_slider['title'] . ' #Alias: ' . $_slider['alias'];
                    }
                    $subChoices[$_slider['id']] = '-- ' . $_slider['title'] . ' #' . $_slider['id'];
                }

                $options[$slider['title'] . ' #' . $slider['id']] = $subChoices;
            } else {
                if (!empty($slider['alias'])) {
                    $options[$slider['alias']] = $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $options[$slider['id']] = $slider['title'] . ' #' . $slider['id'];
            }
        }

        return array(
            'slider' => array(
                'default'         => -1,
                'label'           => 'Slider',
                'option_category' => 'basic_option',
                'type'            => 'select',
                'options'         => $options,
                'description'     => esc_html__('Here you can create the content that will be used within the module.', 'et_builder'),
                'is_fb_content'   => true,
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render($attrs, $content, $render_slug) {
        if (is_numeric($this->props['slider'])) {
            return do_shortcode('[smartslider3 slider=' . $this->props['slider'] . ']');
        }

        return do_shortcode('[smartslider3 alias="' . $this->props['slider'] . '"]');
    }

    public function get_advanced_fields_config() {
        return false;
    }
}

new ET_Builder_Module_SmartSlider3;