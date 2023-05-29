<?php


namespace Nextend\SmartSlider3\Slider\ResponsiveType;


use Nextend\SmartSlider3\Slider\Feature\Responsive;

abstract class AbstractResponsiveTypeFrontend {

    /** @var AbstractResponsiveType */
    protected $type;
    /**
     * @var Responsive
     */
    protected $responsive;

    public function __construct($type, $responsive) {
        $this->type       = $type;
        $this->responsive = $responsive;
    }

    public function getType() {
        return $this->type->getName();
    }

    public function parse($params, $responsive, $features) {

    }

}