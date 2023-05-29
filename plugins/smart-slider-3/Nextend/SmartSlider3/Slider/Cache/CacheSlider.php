<?php


namespace Nextend\SmartSlider3\Slider\Cache;


use Nextend\Framework\Cache\Manifest;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\SmartSlider3\Application\Helper\HelperSliderChanged;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\SmartSlider3Info;

class CacheSlider extends Manifest {

    private $parameters = array();

    protected $_storageEngine = 'database';

    private $isExtended = false;

    public function __construct($cacheId, $parameters = array()) {
        parent::__construct($cacheId, false);
        $this->parameters = $parameters;

    }

    protected function decode($data) {
        return json_decode($data, true);
    }

    /**
     * @param          $fileName
     * @param          $hash
     * @param callback $callable
     *
     * @return bool
     */
    public function makeCache($fileName, $hash, $callable) {

        $variations = 1;
        if ($this->exists($this->getManifestKey('variations'))) {
            $variations = intval($this->get($this->getManifestKey('variations')));
        }
        $fileName = $fileName . mt_rand(1, $variations);

        if ($this->exists($this->getManifestKey('data'))) {
            $data     = json_decode($this->get($this->getManifestKey('data')), true);
            $fileName = $this->extendFileName($fileName, $data);
        } else {
            $this->clearCurrentGroup();
        }

        $output = parent::makeCache($fileName, $hash, $callable);

        return $output;
    }

    protected function createCacheFile($fileName, $hash, $content) {

        $this->set($this->getManifestKey('data'), json_encode($this->parameters['slider']->manifestData));

        $fileName = $this->extendFileName($fileName, $this->parameters['slider']->manifestData);

        return parent::createCacheFile($fileName, $hash, $content);
    }

    private function extendFileName($fileName, $manifestData) {

        if ($this->isExtended) {
            return $fileName;
        }

        $this->isExtended = true;

        $generators = $manifestData['generator'];

        if (count($generators)) {
            $generatorModel = new ModelGenerator($this->parameters['slider']);

            foreach ($generators as $generator) {
                list($group, $type, $params) = $generator;

                $generatorGroup = $generatorModel->getGeneratorGroup($group);
                if (!$generatorGroup) {
                    echo esc_html(n2_('Slider error! Generator group not found: ') . $group);
                } else {

                    $generatorSource = $generatorGroup->getSource($type);
                    if (!$generatorSource) {
                        echo esc_html(n2_('Slider error! Generator source not found: ') . $type);
                    } else {

                        $fileName .= call_user_func(array(
                            $generatorSource,
                            'cacheKey'
                        ), $params);
                    }
                }
            }
        }

        return $fileName;
    }

    protected function isCacheValid(&$manifestData) {

        if (!isset($manifestData['version']) || $manifestData['version'] != SmartSlider3Info::$version) {
            return false;
        }

        $helper = new HelperSliderChanged($this->parameters['slider']);

        if ($helper->isSliderChanged($this->parameters['slider']->sliderId)) {
            $this->clearCurrentGroup();
            $helper->setSliderChanged($this->parameters['slider']->sliderId, 0);

            return false;
        }

        $time = Platform::getTimestamp();

        if ($manifestData['nextCacheRefresh'] < $time) {
            return false;
        }

        if (!isset($manifestData['currentPath']) || $manifestData['currentPath'] != md5(__FILE__)) {
            return false;
        }

        return true;
    }

    protected function addManifestData(&$manifestData) {

        $manifestData['nextCacheRefresh'] = Plugin::applyFilters('SSNextCacheRefresh', $this->parameters['slider']->getNextCacheRefresh(), array($this->parameters['slider']));
        $manifestData['currentPath']      = md5(__FILE__);
        $manifestData['version']          = SmartSlider3Info::$version;

        $variations = 1;

        $params = $this->parameters['slider']->params;
        if (!$params->get('randomize-cache', 0) && ($params->get('randomize', 0) || $params->get('randomizeFirst', 0))) {
            $variations = intval($params->get('variations', 5));
            if ($variations < 1) {
                $variations = 1;
            }
        }

        $this->set($this->getManifestKey('variations'), $variations);
    }
}