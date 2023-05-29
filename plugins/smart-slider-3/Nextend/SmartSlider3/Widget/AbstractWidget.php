<?php


namespace Nextend\SmartSlider3\Widget;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Widget\Group\AbstractWidgetGroup;

abstract class AbstractWidget {

    use GetAssetsPathTrait;

    protected $name;

    protected $key;

    protected $defaults = array();

    /**
     * AbstractWidget constructor.
     *
     * @param AbstractWidgetGroup $widgetGroup
     * @param string              $name
     * @param array               $defaults
     */
    public function __construct($widgetGroup, $name, $defaults = array()) {

        $this->name = $name;

        $this->defaults = array_merge($this->defaults, $defaults);

        $widgetGroup->addWidget($name, $this);
    }

    public function getName() {
        return $this->name;
    }

    public function getSubFormImagePath() {
        return self::getAssetsPath() . '/' . $this->name . '.png';
    }

    public function getDefaults() {
        return $this->defaults;
    }

    /**
     * @param SliderWidget $sliderWidget
     *
     * @return AbstractWidgetFrontend
     */
    public function createFrontend($sliderWidget, $params) {
        $className = static::class . 'Frontend';

        $params->fillDefault($this->getDefaults());

        return new $className($sliderWidget, $this, $params);
    }

    public function getKey() {
        return $this->key;
    }

    /**
     * @param ExportSlider $export
     * @param Data         $params
     */
    public function prepareExport($export, $params) {
    }


    /**
     * @param ImportSlider $import
     * @param Data         $params
     */
    public function prepareImport($import, $params) {

    }

    /**
     * @param ContainerInterface $container
     */
    abstract public function renderFields($container);
}