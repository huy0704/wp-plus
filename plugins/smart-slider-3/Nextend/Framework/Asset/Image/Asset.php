<?php

namespace Nextend\Framework\Asset\Image;

class Asset {

    protected $images = array();

    public function add($images) {
        if (!is_array($images)) {
            $images = array($images);
        }

        $this->images = array_unique(array_merge($this->images, $images));
    }

    public function get() {
        return $this->images;
    }

    public function match($url) {
        return in_array($url, $this->images);
    }

    public function serialize() {
        return array(
            'images' => $this->images
        );
    }

    public function unSerialize($array) {
        if (!empty($array['images'])) {
            $this->add($array['images']);
        }
    }
}