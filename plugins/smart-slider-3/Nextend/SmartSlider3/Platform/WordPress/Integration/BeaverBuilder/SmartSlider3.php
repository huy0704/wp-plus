<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\BeaverBuilder;


use FLBuilderModule;

class SmartSlider3 extends FLBuilderModule {

    public function __construct() {
        parent::__construct(array(
            'name'          => 'Smart Slider',
            'description'   => 'Display the selected slider from Smart Slider plugin.',
            'category'      => __('Basic Modules', 'fl-builder'),
            'dir'           => plugin_dir_path(__FILE__),
            'url'           => plugins_url('/', __FILE__),
            'editor_export' => true,
            'enabled'       => true
        ));

        $this->slug = 'SmartSlider3';
    }

    public function get_icon($icon = '') {

        return file_get_contents(dirname(__FILE__) . '/icon.svg');
    }
}