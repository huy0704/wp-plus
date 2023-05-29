<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsTags extends Select {

    protected $isMultiple = true;

    protected $size = 10;

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $this->options['0'] = n2_('All');

        $terms = get_terms('post_tag');

        if (count($terms)) {
            foreach ($terms as $term) {
                $this->options[$term->term_id] = '- ' . $term->name;
            }
        }
    }

}
