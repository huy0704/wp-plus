<?php

namespace Nextend\SmartSlider3\Renderable\Placement;

use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;

abstract class AbstractPlacement {

    /** @var  AbstractComponent */
    protected $component;

    protected $index = 1;

    protected $style = '';
    protected $attributes = '';

    /**
     *
     * @param AbstractComponent $component
     * @param int               $index
     */
    public function __construct($component, $index) {
        $this->component = $component;
        $this->index     = $index;
    }

    /**
     * @param array $attributes
     */
    public function attributes(&$attributes) {

    }

    /**
     * @param array $attributes
     */
    public function adminAttributes(&$attributes) {
    }
}