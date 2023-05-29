<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Form\Container\LayerWindow\ContainerSettings;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\SmartSlider3\Renderable\Component\ComponentSlide;

abstract class AbstractSliderTypeAdmin {

    use GetPathTrait;
    use OrderableTrait;

    /** @var AbstractSliderType */
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

    /**
     * @return string
     */
    public abstract function getLabel();

    /**
     * @return string
     */
    public function getLabelFull() {
        return $this->getLabel();
    }

    /**
     * @param Form $form
     */
    abstract public function prepareForm($form);

    /**
     * @param ContainerSettings $container
     */
    public function renderSlideFields($container) {

    }

    /**
     * @param ComponentSlide $component
     */
    public function registerSlideAdminProperties($component) {

    }

    public function isDepreciated() {
        return false;
    }
}