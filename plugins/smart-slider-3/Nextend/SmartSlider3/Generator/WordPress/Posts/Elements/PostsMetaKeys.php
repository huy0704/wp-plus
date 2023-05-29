<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsMetaKeys extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $this->options['0'] = n2_('Nothing');

        $metaKeys = $this->generate_meta_keys();
        foreach ($metaKeys as $metaKey) {
            $this->options[$metaKey] = $metaKey;
        }
    }

    function generate_meta_keys() {
        global $wpdb;
        $query     = "SELECT DISTINCT($wpdb->postmeta.meta_key) FROM $wpdb->posts
            LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ORDER BY $wpdb->postmeta.meta_key ASC";
        $meta_keys = $wpdb->get_results($query, ARRAY_A);
        $return    = array();
        foreach ($meta_keys as $num => $array) {
            if (!empty($array['meta_key'])) {
                $return[] = $array['meta_key'];
            }
        }

        return $return;
    }
}