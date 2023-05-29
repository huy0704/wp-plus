<?php


namespace Nextend\SmartSlider3\Slider\Cache;


use Nextend\Framework\Cache\Manifest;
use Nextend\Framework\Platform\Platform;
use Nextend\SmartSlider3\Generator\Generator;
use Nextend\SmartSlider3\Slider\Slider;

class CacheGenerator extends Manifest {

    /**
     * @var Slider
     */
    private $slider;

    private $generator;

    protected $_storageEngine = 'database';

    /**
     * @param Slider    $slider
     * @param Generator $generator
     */
    public function __construct($slider, $generator) {
        parent::__construct($slider->cacheId, false);
        $this->slider    = $slider;
        $this->generator = $generator;
    }

    protected function decode($data) {
        return json_decode($data, true);
    }

    protected function isCacheValid(&$manifestData) {
        $nextRefresh = $manifestData['cacheTime'] + max(0, floatval($this->generator->currentGenerator['params']->get('cache-expiration', 1))) * 60 * 60;
        if ($manifestData['cacheTime'] + max(0, floatval($this->generator->currentGenerator['params']->get('cache-expiration', 1))) * 60 * 60 < Platform::getTimestamp()) {
            return false;
        }
        $this->generator->setNextCacheRefresh($nextRefresh);

        return true;
    }

    protected function addManifestData(&$manifestData) {
        $manifestData['cacheTime'] = Platform::getTimestamp();
        $this->generator->setNextCacheRefresh($manifestData['cacheTime'] + max(0, floatval($this->generator->currentGenerator['params']->get('cache-expiration', 1))) * 60 * 60);
    }
}