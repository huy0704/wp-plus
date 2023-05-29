<?php


namespace Nextend\SmartSlider3\Renderable\Item;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Font\FontParser;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Model\Section;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Style\StyleParser;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Slider\Admin\AdminSlider;

abstract class AbstractItem {

    use GetAssetsPathTrait, OrderableTrait;

    protected $layerProperties = array();

    protected $fonts = array();

    protected $styles = array();

    /**
     * AbstractItem constructor.
     *
     * @param ItemFactory $factory
     */
    public function __construct($factory) {

        $this->initDefault();

        $factory->addItem($this);
    }

    private function initDefault() {

        foreach ($this->fonts as &$fontData) {
            $this->loadDefaultFont($fontData['defaultName'], $fontData['value']);
        }

        foreach ($this->styles as &$styleData) {
            $this->loadDefaultStyle($styleData['defaultName'], $styleData['value']);
        }
    }

    protected function loadDefaultFont($name, &$value) {

        $res = Section::get('smartslider', 'default', $name);
        if (is_array($res)) {
            $value = $res['value'];
        }

        $value = FontParser::parse($value);
    }

    protected function loadDefaultStyle($name, &$value) {

        $res = Section::get('smartslider', 'default', $name);
        if (is_array($res)) {
            $value = $res['value'];
        }

        $value = StyleParser::parse($value);
    }

    /**
     * @param $id
     * @param $itemData
     * @param $layer
     *
     * @return AbstractItemFrontend
     */
    public abstract function createFrontend($id, $itemData, $layer);

    /**
     * @return string
     */
    public abstract function getTitle();

    /**
     * @return string
     */
    public abstract function getIcon();

    /**
     * @return string
     */
    public function getGroup() {
        return n2_x('Basic', 'Layer group');
    }

    public function getLayerProperties() {
        return $this->layerProperties;
    }

    public function isLegacy() {
        return false;
    }

    public function getValues() {
        $values = array();

        foreach ($this->fonts as $name => $fontData) {
            $values[$name] = $fontData['value'];
        }

        foreach ($this->styles as $name => $styleData) {
            $values[$name] = $styleData['value'];
        }

        return $values;
    }

    /**
     * @param $slide AbstractRenderableOwner
     * @param $data  Data
     *
     * @return Data
     */
    public function getFilled($slide, $data) {
        $this->upgradeData($data);

        return $data;
    }

    /**
     * @param Data $data
     */
    public function upgradeData($data) {

    }

    /**
     * Fix linked fonts/styles for the editor
     *
     * @param Data $data
     */
    public function adminNormalizeFontsStyles($data) {

        foreach ($this->fonts as $name => $fontData) {
            $data->set($name, FontParser::parse($data->get($name)));
        }

        foreach ($this->styles as $name => $styleData) {
            $data->set($name, StyleParser::parse($data->get($name)));
        }
    }

    /**
     * @param ExportSlider $export
     * @param Data         $data
     */
    public function prepareExport($export, $data) {
        $this->upgradeData($data);
    }

    /**
     * @param ImportSlider $import
     * @param Data         $data
     *
     * @return Data
     */
    public function prepareImport($import, $data) {
        $this->upgradeData($data);

        return $data;
    }

    /**
     * @param Data $data
     *
     * @return Data
     */
    public function prepareSample($data) {
        $this->upgradeData($data);

        return $data;
    }

    public function fixImage($image) {
        return ResourceTranslator::toUrl($image);
    }

    public function fixLightbox($url) {
        preg_match('/^([a-zA-Z]+)\[(.*)](.*)/', $url, $matches);
        if (!empty($matches) && $matches[1] == 'lightbox') {
            $images    = explode(',', $matches[2]);
            $newImages = array();
            foreach ($images as $image) {
                $newImages[] = ResourceTranslator::toUrl($image);
            }
            $url = 'lightbox[' . implode(',', $newImages) . ']' . $matches[3];
        }

        return $url;
    }

    /**
     * @param AdminSlider $renderable
     */
    public function loadResources($renderable) {
    }

    /**
     * @return string
     */
    public abstract function getType();

    protected function isBuiltIn() {
        return false;
    }

    /**
     * @param ContainerInterface $container
     */
    public abstract function renderFields($container);

    /**
     * @param ContainerInterface $container
     */
    public function globalDefaultItemFontAndStyle($container) {
    }
}