<?php


namespace Nextend\SmartSlider3\Slider\ResponsiveType;

use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\OrderableTrait;

abstract class AbstractResponsiveTypeAdmin {

    use GetPathTrait;
    use OrderableTrait;

    /** @var AbstractResponsiveType */
    protected $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function getName() {
        return $this->type->getName();
    }

    /**
     * @return string
     */
    public abstract function getIcon();

    public abstract function getLabel();

    /**
     * @param ContainerInterface $container
     */
    public function renderFields($container) {

    }

}