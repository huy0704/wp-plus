<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsPostTypes extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);


        $this->options['0'] = n2_('All');

        $postTypes = get_post_types();
        foreach ($postTypes as $postType) {
            $this->options[$postType] = $postType;
        }

    }
}