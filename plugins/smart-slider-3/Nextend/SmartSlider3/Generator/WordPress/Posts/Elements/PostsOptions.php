<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsOptions extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $options = wp_load_alloptions();

        $this->options['0'] = n2_('Nothing');
        foreach ($options as $option => $value) {
            $this->options[$option] = $option;
        }
    }
}