<?php


namespace Nextend\SmartSlider3\Widget;


class WidgetPlacement {

    protected $name;

    protected $items = array();

    public function __construct($name) {
        $this->name = $name;
    }

    public function empty() {

        return empty($this->items);
    }
}