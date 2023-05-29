<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\BeaverBuilder;


use FLBuilder;
use Nextend\Framework\WordPress\AssetInjector;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;

class BeaverBuilder {

    public function __construct() {

        if (class_exists('\\FLBuilderModel', false)) {
            $this->init();
        }
    }

    public function init() {
        add_action('fl_builder_editing_enabled', array(
            $this,
            'forceShortcodeIframe'
        ));
        add_action('fl_builder_editing_enabled', array(
            HelperTinyMCE::getInstance(),
            "addForcedFrontend"
        ));


        add_action('fl_ajax_before_render_new_module', array(
            $this,
            'forceShortcodeIframe'
        ));
        add_action('fl_ajax_before_render_layout', array(
            $this,
            'forceShortcodeIframe'
        ));
        add_action('fl_ajax_before_render_module_settings', array(
            $this,
            'forceShortcodeIframe'
        ));
        add_action('fl_ajax_before_save_settings', array(
            $this,
            'forceShortcodeIframe'
        ));
        add_action('fl_ajax_before_copy_module', array(
            $this,
            'forceShortcodeIframe'
        ));

        add_action('init', array(
            $this,
            'action_init'
        ));

        add_action('fl_builder_control_smart-slider', array(
            $this,
            'fieldSmartSlider'
        ), 1, 3);

        /**
         * Fix for Beaver Builder 1.5
         */
        add_action('fl_ajax_fl_builder_render_new_module_settings', array(
            AssetInjector::getInstance(),
            'removeInjectCSSComment'
        ), 0);

        add_action('fl_ajax_fl_builder_save', array(
            AssetInjector::getInstance(),
            'removeInjectCSSComment'
        ), 0);
    }

    public function action_init() {
        if (class_exists('\\FLBuilder')) {

            FLBuilder::register_module(SmartSlider3::class, array(
                'general' => array(
                    'title'    => __('General', 'fl-builder'),
                    'sections' => array(
                        'general' => array(
                            'title'  => "",
                            'fields' => array(
                                'sliderid' => array(
                                    'type'    => 'smart-slider',
                                    'label'   => 'Slider ID or Alias',
                                    'default' => ''
                                ),
                            )
                        )
                    )
                )
            ));

            /**
             * Legacy
             */
            FLBuilder::register_module(SmartSlider3Legacy::class, array(
                'general' => array(
                    'title'    => __('General', 'fl-builder'),
                    'sections' => array(
                        'general' => array(
                            'title'  => "",
                            'fields' => array(
                                'sliderid' => array(
                                    'type'    => 'smart-slider',
                                    'label'   => 'Slider ID or Alias',
                                    'default' => ''
                                ),
                            )
                        )
                    )
                )
            ));
        }
    }

    public function forceShortcodeIframe() {
        remove_action('wp_enqueue_scripts', array(
            Shortcode::class,
            'shortcodeModeToNoop'
        ), 1000000);
        Shortcode::forceIframe('Beaver Builder', true);
    }

    public function fieldSmartSlider($name, $value, $field) {

        $applicationType = ApplicationSmartSlider3::getInstance()
                                                  ->getApplicationTypeAdmin();

        $slidersModel = new ModelSliders($applicationType);

        $choices = array();
        foreach ($slidersModel->getAll(0, 'published') as $slider) {
            if ($slider['type'] == 'group') {

                $subChoices = array();
                if (!empty($slider['alias'])) {
                    $subChoices[$slider['alias']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $subChoices[$slider['id']] = n2_('Whole group') . ' - ' . $slider['title'] . ' #' . $slider['id'];
                foreach ($slidersModel->getAll($slider['id'], 'published') as $_slider) {
                    if (!empty($_slider['alias'])) {
                        $subChoices[$_slider['alias']] = $_slider['title'] . ' #Alias: ' . $_slider['alias'];
                    }
                    $subChoices[$_slider['id']] = $_slider['title'] . ' #' . $_slider['id'];
                }

                $choices[$slider['id']] = array(
                    'label'   => $slider['title'] . ' #' . $slider['id'],
                    'choices' => $subChoices
                );
            } else {
                if (!empty($slider['alias'])) {
                    $choices[$slider['alias']] = $slider['title'] . ' #Alias: ' . $slider['alias'];
                }
                $choices[$slider['id']] = $slider['title'] . ' #' . $slider['id'];
            }
        }
        ?>
        <select name="<?php echo esc_attr($name); ?>">
            <option value=""><?php n2_e('None'); ?></option>
            <?php
            foreach ($choices as $id => $choice) {
                if (is_array($choice)) {
                    ?>
                    <optgroup label="<?php echo esc_attr($choice['label']); ?>">
                        <?php
                        foreach ($choice['choices'] as $_id => $_choice) {
                            ?>
                            <option <?php if ($_id == $value){ ?>selected <?php } ?>value="<?php echo esc_attr($_id); ?>"><?php echo esc_html($_choice); ?></option>
                            <?php
                        }
                        ?>
                    </optgroup>
                    <?php
                } else {
                    ?>
                    <option <?php if ($id == $value){ ?>selected <?php } ?>value="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice); ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <div style="line-height:2;padding:10px;"><?php n2_e('OR'); ?></div>

        <a href="#" onclick="NextendSmartSliderSelectModal(jQuery(this).siblings('select')) ;return false;" class="fl-builder-smart-slider-select fl-builder-button fl-builder-button-small fl-builder-button-primary" title="Select slider"><?php n2_e('Select Slider'); ?></a>
        <script>
            (function ($) {
                var value = $('select[name="<?php echo esc_js($name); ?>"]').val();
                if (value == '' || value == '0') {
                    $('.fl-builder-smart-slider-select').trigger('click');
                }
            })(jQuery);
        </script>
        <?php
    }
}