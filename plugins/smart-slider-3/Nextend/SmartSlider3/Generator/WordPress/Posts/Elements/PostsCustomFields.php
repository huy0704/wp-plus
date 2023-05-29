<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Elements;

use Nextend\Framework\Form\Element\Select;


class PostsCustomFields extends Select {

    protected $postType = '';

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
            LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
            WHERE $wpdb->posts.post_type = '%s' ORDER BY $wpdb->postmeta.meta_key ASC";
        $meta_keys = $wpdb->get_col($wpdb->prepare($query, $this->postType));

        return $meta_keys;
    }

    /**
     * @param string $postType
     */
    public function setPostType($postType) {
        $this->postType = $postType;
    }
}