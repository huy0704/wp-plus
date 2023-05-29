<?php


namespace Nextend\SmartSlider3\BackupSlider;


use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class BackupData {

    public $NextendImageHelper_Export, $slider, $slides, $generators = array(), $NextendImageManager_ImageData = array(), $imageTranslation = array(), $visuals = array();

    public function __construct() {
        $this->NextendImageHelper_Export = ResourceTranslator::exportData();
    }
}