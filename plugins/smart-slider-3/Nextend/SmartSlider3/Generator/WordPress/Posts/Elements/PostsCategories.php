<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsCategories extends Select {

    protected $isMultiple = true;

    protected $size = 10;

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $args       = array(
            'type'         => 'post',
            'child_of'     => 0,
            'parent'       => '',
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1,
            'exclude'      => '',
            'include'      => '',
            'number'       => '',
            'taxonomy'     => 'category',
            'pad_counts'   => false

        );
        $categories = get_categories($args);
        $new        = array();
        foreach ($categories as $a) {
            $new[$a->category_parent][] = $a;
        }
        $list    = array();
        $options = $this->createTree($list, $new, 0);

        $this->options['0'] = n2_('All');
        if (count($options)) {
            foreach ($options as $option) {
                $this->options[$option->cat_ID] = ' - ' . $option->treename;
            }
        }
    }

}
