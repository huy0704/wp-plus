<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Slider\Slider;

abstract class AbstractSliderType {

    use GetAssetsPathTrait;

    public abstract function getName();

    /**
     * @param Slider $slider
     *
     * @return AbstractSliderTypeFrontend
     */
    public abstract function createFrontend($slider);

    /**
     * @param Slider $slider
     *
     * @return AbstractSliderTypeCss
     */
    public abstract function createCss($slider);

    /**
     *
     * @return AbstractSliderTypeAdmin
     */
    public abstract function createAdmin();

    /**
     * @param ExportSlider $export
     * @param              $slider
     */
    public function export($export, $slider) {
    }

    /**
     * @param ImportSlider $import
     * @param              $slider
     */
    public function import($import, $slider) {
    }

    public function getItemDefaults() {

        return array();
    }
}