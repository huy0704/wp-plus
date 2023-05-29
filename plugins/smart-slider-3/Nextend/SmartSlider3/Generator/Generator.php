<?php

namespace Nextend\SmartSlider3\Generator;

use Nextend\Framework\Data\Data;
use Nextend\Framework\Notification\Notification;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Slider\Cache\CacheGenerator;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class Generator {

    private static $localCache = array();

    /**
     * @var Slide
     */
    private $slide;

    private $generatorModel;

    public $currentGenerator;

    private $slider;

    /** @var  AbstractGenerator */
    private $dataSource;

    /**
     * @param Slide                              $slide
     * @param Slider                             $slider
     * @param                                    $extend
     */
    public function __construct($slide, $slider, $extend) {

        $this->slide  = $slide;
        $this->slider = $slider;

        $this->generatorModel             = new ModelGenerator($slider);
        $this->currentGenerator           = $this->generatorModel->get($this->slide->generator_id);
        $this->currentGenerator['params'] = new Data($this->currentGenerator['params'], true);

        if (isset($extend[$this->slide->generator_id])) {
            $extend = new Data($extend[$this->slide->generator_id]);
            $slide->parameters->set('record-slides', $extend->get('record-slides', 1));
            $extend->un_set('record-slides');
            $this->currentGenerator['params']->loadArray($extend->toArray());
        }
    }

    public function getSlides() {
        $slides = array();
        $data   = $this->getData();
        for ($i = 0; $i < count($data); $i++) {
            $newSlide = clone $this->slide;
            $newSlide->setVariables($data[$i]);
            if ($i > 0) {
                $newSlide->unique = $i;
            }
            $slides[] = $newSlide;
        }
        if (count($slides) == 0) {
            $slides = null;
        }

        return $slides;
    }

    public function getSlidesAdmin() {
        $slides = array();
        $data   = $this->getData();
        for ($i = 0; $i < count($data); $i++) {
            $newSlide = clone $this->slide;
            $newSlide->setVariables($data[$i]);
            if ($i > 0) {
                $newSlide->unique = $i;
            }
            $slides[] = $newSlide;
        }
        if (count($slides) == 0) {
            $slides[] = $this->slide;
        }

        return $slides;
    }

    public function fillSample() {
        $data = $this->getData();
        if (count($data) > 0) {
            $this->slide->setVariables($data[0]);
        }
    }

    /**
     * @return bool|false|AbstractGenerator
     */
    public function getSource() {
        $generatorGroup = $this->generatorModel->getGeneratorGroup($this->currentGenerator['group']);
        if (!$generatorGroup) {
            Notification::notice(n2_('Generator group not found') . ': ' . $this->currentGenerator['group']);

            return false;
        }
        $source = $generatorGroup->getSource($this->currentGenerator['type']);
        if (!$source) {
            Notification::notice(n2_('Generator type not found') . ': ' . $this->currentGenerator['type']);

            return false;
        }

        return $source;
    }

    private function getData() {
        if (!isset(self::$localCache[$this->slide->generator_id])) {


            $this->slider->manifestData['generator'][] = array(
                $this->currentGenerator['group'],
                $this->currentGenerator['type'],
                $this->currentGenerator['params']->toArray()
            );

            $generatorGroup = $this->generatorModel->getGeneratorGroup($this->currentGenerator['group']);
            if (!$generatorGroup) {
                return array();
            }

            $this->dataSource = $generatorGroup->getSource($this->currentGenerator['type']);
            if ($this->dataSource) {
                $this->dataSource->setData($this->currentGenerator['params']);

                $cache = new CacheGenerator($this->slider, $this);
                $name  = $this->dataSource->filterName('generator' . $this->currentGenerator['id']);

                self::$localCache[$this->slide->generator_id] = $cache->makeCache($name, $this->dataSource->hash(json_encode($this->currentGenerator) . max($this->slide->parameters->get('record-slides'), 1)), array(
                    $this,
                    'getNotCachedData'
                ));
            } else {
                self::$localCache[$this->slide->generator_id] = array();
                Notification::error(sprintf(n2_('%1$s generator missing the following source: %2$s'), $generatorGroup->getLabel(), $this->currentGenerator['type']));
            }
        }

        return self::$localCache[$this->slide->generator_id];
    }

    public function getNotCachedData() {
        return $this->dataSource->getData(max($this->slide->parameters->get('record-slides'), 1), max($this->currentGenerator['params']->get('record-start'), 1), $this->getSlideGroup());
    }

    public function setNextCacheRefresh($time) {
        $this->slide->setNextCacheRefresh($time);
    }

    public function getSlideCount() {
        return max($this->slide->parameters->get('record-slides'), 1);
    }

    public function getSlideGroup() {
        return max($this->currentGenerator['params']->get('record-group'), 1);
    }

    public function getSlideStat() {
        return count($this->getData()) . '/' . $this->getSlideCount();
    }
}