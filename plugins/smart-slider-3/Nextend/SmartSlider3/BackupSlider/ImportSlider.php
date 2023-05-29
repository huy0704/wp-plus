<?php


namespace Nextend\SmartSlider3\BackupSlider;


use Nextend\Framework\Cache\StoreImage;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Image\ImageManager;
use Nextend\Framework\Misc\Zip\Reader;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Url\Url;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Renderable\Component\ComponentCol;
use Nextend\SmartSlider3\Renderable\Component\ComponentContent;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;
use Nextend\SmartSlider3\Renderable\Component\ComponentRow;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;

class ImportSlider {

    use MVCHelperTrait;

    /**
     * @var BackupData
     */
    private $backup;
    private $imageTranslation = array();
    private $sectionTranslation = array();

    private $sliderId = 0;

    private $replace = false;

    public function __construct($MVCHelper) {

        $this->setMVCHelper($MVCHelper);
    }

    public function enableReplace() {
        $this->replace = true;
    }

    public function import($filePathOrData, $groupID = 0, $imageImportMode = 'clone', $linkedVisuals = 1, $isFilePath = true) {
        if (!$isFilePath) {
            $tmp = Filesystem::tempnam();
            file_put_contents($tmp, $filePathOrData);
            $filePathOrData = $tmp;
        }
        $importData = Reader::read($filePathOrData);
        if (!$isFilePath) {
            @unlink($tmp);
        }
        if (!is_array($importData)) {
            Notification::error(n2_('The importing failed at the unzipping part.'));

            return false;
        } else if (!isset($importData['data'])) {
            if (array_key_exists("slider.ss2", $importData)) {
                Notification::error(n2_('You can\'t import sliders from Smart Slider 2.'));
            } else if (empty($importData)) {
                Notification::error(n2_('Export file corrupted! Slider data is missing.'));
            }

            return false;
        }


        $parser = new Serialize\Parser;
        if (!$parser->isValidData($importData['data'])) {
            return false;
        }

        $this->backup = unserialize($importData['data']);

        if (!empty($this->backup->slider['type']) && $this->backup->slider['type'] == 'group') {
            // Groups can not be imported into groups
            $groupID = 0;
        }

        $this->sectionTranslation = array();
        $this->importVisuals($this->backup->visuals, $linkedVisuals);


        $sliderModel = new ModelSliders($this);


        if ($this->replace) {
            $this->sliderId = $sliderModel->replace($this->backup->slider, $groupID);
        } else {
            $this->sliderId = $sliderModel->import($this->backup->slider, $groupID);
        }

        if (!$this->sliderId) {
            return false;
        }

        switch ($imageImportMode) {
            case 'clone':
                $images     = isset($importData['images']) ? $importData['images'] : array();
                $imageStore = new StoreImage('slider' . $this->sliderId, true);
                foreach ($images as $file => $content) {
                    $localImage = $imageStore->makeCache($file, $content);
                    if ($localImage) {
                        $this->imageTranslation[$file] = ResourceTranslator::urlToResource(Url::pathToUri($localImage));
                    } else {
                        $this->imageTranslation[$file] = $file;
                    }
                    if (!$this->imageTranslation[$file]) {
                        $this->imageTranslation[$file] = array_search($file, $this->backup->imageTranslation);
                    }
                }
                break;
            case 'update':
                $keys   = array_keys($this->backup->NextendImageHelper_Export);
                $values = array_values($this->backup->NextendImageHelper_Export);
                foreach ($this->backup->imageTranslation as $image => $value) {
                    $this->imageTranslation[$value] = str_replace($keys, $values, $image);
                }
                break;
            default:
                break;
        }
        if (!empty($this->backup->slider['thumbnail'])) {
            $sliderModel->setThumbnail($this->sliderId, $this->fixImage($this->backup->slider['thumbnail']));
        }

        foreach ($this->backup->NextendImageManager_ImageData as $image => $data) {
            $data['tablet']['image'] = $this->fixImage($data['tablet']['image']);
            $data['mobile']['image'] = $this->fixImage($data['mobile']['image']);
            $fixedImage              = $this->fixImage($image);
            if (!ImageManager::hasImageData($fixedImage)) {
                ImageManager::addImageData($this->fixImage($image), $data);
            }
        }

        if (empty($this->backup->slider['type'])) {
            $this->backup->slider['type'] = 'simple';
        }


        if ($this->backup->slider['type'] == 'group') {
            /**
             * Import the sliders for the group!
             */
            foreach ($importData['sliders'] as $k => $slider) {
                $import = new self($this);
                if ($this->replace) {
                    $import->enableReplace();
                }
                $import->import($slider, $this->sliderId, $imageImportMode, $linkedVisuals, false);
            }
        } else {

            unset($importData);

            $sliderType = SliderTypeFactory::getType($this->backup->slider['type']);
            $sliderType->import($this, $this->backup->slider);


            $enabledWidgets = array();
            $widgetGroups   = WidgetGroupFactory::getGroups();

            $params = $this->backup->slider['params'];
            foreach ($widgetGroups as $groupName => $group) {
                $widgetName = $params->get('widget' . $groupName);
                if ($widgetName && $widgetName != 'disabled') {
                    $widget = $group->getWidget($widgetName);
                    if ($widget) {
                        $enabledWidgets[$groupName] = $widget;
                    }
                }
            }

            foreach ($enabledWidgets as $k => $widget) {
                $params->fillDefault($widget->getDefaults());

                $widget->prepareImport($this, $params);
            }


            $sliderModel->importUpdate($this->sliderId, $params);

            $generatorTranslation = array();
            $generatorModel       = new ModelGenerator($this);
            foreach ($this->backup->generators as $generator) {
                $generatorTranslation[$generator['id']] = $generatorModel->import($generator);
            }


            $slidesModel = new ModelSlides($this);
            for ($i = 0; $i < count($this->backup->slides); $i++) {
                $slide              = $this->backup->slides[$i];
                $slide['params']    = new Data($slide['params'], true);
                $slide['thumbnail'] = $this->fixImage($slide['thumbnail']);
                $slide['params']->set('backgroundImage', $this->fixImage($slide['params']->get('backgroundImage')));
                $slide['params']->set('ligthboxImage', $this->fixImage($slide['params']->get('ligthboxImage')));

                if ($slide['params']->has('link')) {
                    // Compatibility fix for the old SS3 import files
                    $slide['params']->set('link', $this->fixLightbox($slide['params']->get('link')));
                }
                if ($slide['params']->has('href')) {
                    $slide['params']->set('href', $this->fixLightbox($slide['params']->get('href')));
                }

                $layers = json_decode($slide['slide'], true);

                $this->prepareLayers($layers);

                $slide['slide'] = $layers;

                if (isset($generatorTranslation[$slide['generator_id']])) {
                    $slide['generator_id'] = $generatorTranslation[$slide['generator_id']];
                }
                $slidesModel->import($slide, $this->sliderId);
            }
        }

        return $this->sliderId;
    }

    /**
     * @param array $layers
     */
    public function prepareLayers(&$layers) {
        for ($i = 0; $i < count($layers); $i++) {

            if (isset($layers[$i]['type'])) {
                switch ($layers[$i]['type']) {
                    case 'content':
                        ComponentContent::prepareImport($this, $layers[$i]);
                        break;
                    case 'row':
                        ComponentRow::prepareImport($this, $layers[$i]);
                        break;
                    case 'col':
                        ComponentCol::prepareImport($this, $layers[$i]);
                        break;
                    case 'group':
                        $this->prepareLayers($layers[$i]['layers']);
                        break;
                    default:
                        ComponentLayer::prepareImport($this, $layers[$i]);
                }
            } else {
                ComponentLayer::prepareImport($this, $layers[$i]);
            }
        }
    }

    public function fixImage($image) {
        if (isset($this->backup->imageTranslation[$image]) && isset($this->imageTranslation[$this->backup->imageTranslation[$image]])) {
            return $this->imageTranslation[$this->backup->imageTranslation[$image]];
        }

        return $image;
    }

    public function fixSection($idOrRaw) {
        if (isset($this->sectionTranslation[$idOrRaw])) {
            return $this->sectionTranslation[$idOrRaw];
        }

        return $idOrRaw;
    }

    public function fixLightbox($url) {
        preg_match('/^([a-zA-Z]+)\[(.*)]/', $url, $matches);
        if (!empty($matches) && $matches[1] == 'lightbox') {
            $data = json_decode($matches[2]);
            if ($data) {
                $newImages = array();
                foreach ($data->urls as $image) {
                    $newImages[] = $this->fixImage($image);
                }
                $data->urls = $newImages;
                $url        = 'lightbox[' . json_encode($data) . ']';
            }
        }

        return $url;
    }

    private function importVisuals($records, $linkedVisuals) {
        if (count($records)) {
            if (!$linkedVisuals) {
                foreach ($records as $record) {
                    $this->sectionTranslation[$record['id']] = $record['value'];
                }
            } else {
                $sets = array();
                foreach ($records as $record) {
                    $storage = StorageSectionManager::getStorage($record['application']);
                    if (!isset($sets[$record['application'] . '_' . $record['section']])) {
                        $sets[$record['application'] . '_' . $record['section']] = $storage->add($record['section'] . 'set', '', $this->backup->slider['title']);
                    }
                    $this->sectionTranslation[$record['id']] = $storage->add($record['section'], $sets[$record['application'] . '_' . $record['section']], $record['value']);
                }
            }
        }
    }
}