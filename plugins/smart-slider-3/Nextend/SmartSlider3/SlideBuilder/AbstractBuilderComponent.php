<?php

namespace Nextend\SmartSlider3\SlideBuilder;

abstract class AbstractBuilderComponent {

    protected $defaultData = array();

    protected $data = array();


    public function set($keyOrData, $value = null) {

        if (is_array($keyOrData)) {
            foreach ($keyOrData as $key => $value) {
                $this->setSingle($key, $value);
            }
        } else {
            $this->setSingle($keyOrData, $value);
        }

        return $this;
    }

    private function setSingle($key, $value) {
        $this->data[$key] = $value;
    }

    public function getData() {
        return array_merge($this->defaultData, $this->data);
    }

    /**
     * @param AbstractBuilderComponent $component
     */
    public function add($component) {
    }
}