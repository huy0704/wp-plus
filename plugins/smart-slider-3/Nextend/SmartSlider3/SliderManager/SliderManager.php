<?php

namespace Nextend\SmartSlider3\SliderManager;

use Exception;
use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Translation\Translation;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlidersXRef;
use Nextend\SmartSlider3\Slider\Cache\CacheSlider;
use Nextend\SmartSlider3\Slider\Slider;

class SliderManager {

    use MVCHelperTrait;

    protected $isAdmin;

    protected $hasError = false;

    protected $displayWhenEmpty = false;

    protected $usage = 'Unknown';

    /**
     * @var Slider
     */
    public $slider;

    public $nextCacheRefresh;

    /**
     * @var ModelSlidersXRef
     */
    private $xref;

    /**
     *
     * @param MVCHelperTrait $MVCHelper
     * @param                $sliderIDorAlias
     * @param                $isAdmin
     * @param array          $parameters
     */
    public function __construct($MVCHelper, $sliderIDorAlias, $isAdmin = false, $parameters = array()) {

        $this->setMVCHelper($MVCHelper);

        $this->isAdmin = $isAdmin;

        $sliderID = false;

        $model = new ModelSliders($this);
        if (!is_numeric($sliderIDorAlias)) {
            $slider = $model->getByAlias($sliderIDorAlias);
            if ($slider) {
                $sliderID = intval($slider['id']);
            }
        } else {
            $sliderID = intval($sliderIDorAlias);
        }

        if ($sliderID) {
            $this->init($sliderID, $parameters);
            $this->xref = new ModelSlidersXRef($model);

            AssetManager::addCachedGroup($this->slider->cacheId);
        } else {
            $this->hasError = true;
        }
    }

    protected function init($sliderID, $parameters) {
        $this->slider = new Slider($this->MVCHelper, $sliderID, $parameters, $this->isAdmin);
    }

    public function setUsage($usage) {

        $this->usage = $usage;

        if ($usage === 'iframe') {
            $this->slider->isFrame = true;
        }
    }

    /**
     * @return Slider
     */
    public function getSlider() {
        return $this->slider;
    }

    /**
     * @param $cache
     *
     * @return string contains already escaped data
     */
    public function render($cache = false) {
        if ($this->hasError) {
            return '';
        }

        try {
            if (!$cache) {
                $this->slider->initAll();
                if (($this->xref->isSliderAvailableInAnyGroups($this->slider->sliderId) || $this->isAdmin) && ($this->slider->hasSlides() || $this->displayWhenEmpty)) {

                    return $this->slider->render();
                }

                return '';
            }

            return $this->slider->addCMSFunctions($this->cacheSlider());
        } catch (Exception $e) {
            return '';
        }
    }

    private function cacheSlider() {
        $cache = new CacheSlider($this->slider->cacheId, array(
            'slider' => $this->slider
        ));
        $key   = 'slider' . Translation::getCurrentLocale();
        if ($this->slider->isFrame) {
            $key .= 'iframe';
        }

        $cachedSlider = $cache->makeCache($key, '', array(
            $this,
            'renderCachedSlider'
        ));

        $this->nextCacheRefresh = $cache->getData('nextCacheRefresh', false);

        if ($cachedSlider === false) {
            return '<!--Smart Slider #' . $this->slider->sliderId . ' does NOT EXIST or has NO SLIDES!' . $this->usage . '-->';
        }

        AssetManager::loadFromArray($cachedSlider['assets']);

        return $cachedSlider['html'];
    }

    /**
     * @return array|bool
     * @throws Exception
     */
    public function renderCachedSlider() {

        AssetManager::createStack();

        $content = array();

        try {
            $this->slider->initAll();


            if (($this->xref->isSliderAvailableInAnyGroups($this->slider->sliderId) || $this->isAdmin) && $this->slider->hasSlides()) {

                $content['html'] = $this->slider->render();
            } else {
                $content['html'] = '';
            }
        } catch (Exception $exception) {
            $content['html'] = false;
        }

        $assets = AssetManager::removeStack();

        if ($content['html'] === false) {
            return false;
        }

        $content['assets'] = $assets;

        return $content;
    }

    public function allowDisplayWhenEmpty() {
        $this->displayWhenEmpty = true;
    }
}