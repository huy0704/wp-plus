<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Widget;


use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;
use WP_Widget;

class WidgetSmartSlider3 extends WP_Widget {

    private $preventRender = false;

    function __construct() {

        parent::__construct('smartslider3', 'Smart Slider', array(
            'show_instance_in_rest' => true,
            'description'           => 'Displays a Smart Slider'
        ));

        add_filter('widget_types_to_hide_from_legacy_widget_block', function ($widget_types) {

            $widget_types[] = 'smartslider3';

            return $widget_types;
        });

        // YOAST SEO fix
        add_action('wpseo_head', array(
            $this,
            'preventRender'
        ), 0);
        add_action('wpseo_head', array(
            $this,
            'notPreventRender'
        ), 10000000000);
    }

    public function preventRender() {
        $this->preventRender = true;
    }

    public function notPreventRender() {
        $this->preventRender = false;
    }

    function widget($args, $instance) {
        global $wpdb;
        if ($this->preventRender) {
            return;
        }
        $instance = array_merge(array(
            'id'     => md5(time()),
            'slider' => 0,
            'title'  => ''
        ), $instance);

        if ($instance['slider'] === 0) {

            $instance['slider'] = $wpdb->get_var('SELECT id FROM ' . $wpdb->prefix . 'nextend2_smartslider3_sliders WHERE slider_status = \'published\' LIMIT 0,1');
        }

        $slider = do_shortcode('[smartslider3 slider=' . $instance['slider'] . ']');

        if ($slider != '') {

            $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

            echo wp_kses($args['before_widget'], Sanitize::$basicTags);
            if (!empty($title)) echo wp_kses($args['before_title'] . $title . $args['after_title'], Sanitize::$basicTags);

            // PHPCS - Content already escaped
            echo $slider; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            echo wp_kses($args['after_widget'], Sanitize::$basicTags);
        }
    }

    function form($instance) {
        $instance = wp_parse_args((array)$instance, array(
            'title'  => '',
            'slider' => 0
        ));
        $title    = $instance['title'];

        HelperTinyMCE::getInstance()
                     ->addForced();

        ?>

        <p>
            <?php

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
            $value = $instance['slider'];

            $_title = '';
            ?>
            <select id="<?php echo esc_attr($this->get_field_id('slider')); ?>" name="<?php echo esc_attr($this->get_field_name('slider')); ?>" class="widefat">
                <?php if (empty($choices)): ?>
                    <option value=""><?php n2_e('None'); ?></option>
                <?php else: ?>
                    <?php
                    if ($instance['slider'] === 0) {
                        global $wpdb;
                        $value = $wpdb->get_var('SELECT id FROM ' . $wpdb->prefix . 'nextend2_smartslider3_sliders WHERE slider_status = \'published\' LIMIT 0,1');

                    }
                    foreach ($choices as $id => $choice) {
                        if (is_array($choice)) {
                            ?>
                            <optgroup label="<?php echo esc_attr($choice['label']); ?>">
                                <?php
                                foreach ($choice['choices'] as $_id => $_choice) {
                                    ?>
                                    <option <?php if ($_id == $value){
                                            $_title = $_choice; ?>selected <?php } ?>value="<?php echo esc_attr($_id); ?>"><?php echo esc_html($_choice); ?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                            <?php
                        } else {
                            ?>
                            <option <?php if ($id == $value){
                                    $_title = $choice; ?>selected <?php } ?>value="<?php echo esc_attr($id); ?>"><?php echo esc_html($choice); ?></option>
                            <?php
                        }
                    }
                    ?>
                <?php endif; ?>
            </select>
            <input id="<?php echo esc_attr($this->get_field_id('temp-title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('temp-title')); ?>" type="hidden"
                   value="<?php echo esc_attr($_title); ?>">

        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                Title:
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </label>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance           = $old_instance;
        $instance['title']  = $new_instance['title'];
        $instance['slider'] = $new_instance['slider'];

        return $instance;
    }
}