<?php


namespace Nextend\SmartSlider3\Slider;


use Exception;
use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Settings;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Renderable\AbstractRenderable;
use Nextend\SmartSlider3\Slider\Base\PlatformSliderTrait;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeCss;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeFrontend;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;


class Slider extends AbstractRenderable {

    use PlatformSliderTrait, MVCHelperTrait;

    const LOAD_STATE_NONE = 0;
    const LOAD_STATE_SLIDER = 1;
    const LOAD_STATE_SLIDES = 2;
    const LOAD_STATE_ALL = 3;

    protected $loadState;

    protected $isAdminArea = false;

    public $manifestData = array(
        'generator' => array()
    );

    protected $isGroup = false;

    public $hasError = false;

    public $sliderId = 0;

    public $cacheId = '';

    public $isFrame = false;

    /** @var  Data */
    public $data;

    public $disableResponsive = false;

    protected $parameters = array(
        'disableResponsive' => false,
        'sliderData'        => array(),
        'slidesData'        => array(),
        'generatorData'     => array()
    );

    public $fontSize = 16;

    /**
     * @var Slides
     */
    protected $slidesBuilder;

    protected $cache = false;

    public static $_identifier = 'n2-ss';

    /** @var Slide[] */
    public $staticSlides = array();

    /** @var  AbstractSliderTypeFrontend */
    public $sliderType;

    /**
     * @var AbstractSliderTypeCss
     */
    public $assets;

    /**
     * @var string contains already escaped data
     */
    public $staticHtml = '';

    private $sliderRow;

    private $fallbackId;

    public $exposeSlideData = array(
        'title'         => true,
        'description'   => false,
        'thumbnail'     => false,
        'lightboxImage' => false
    );

    /**
     * @var Data
     */
    public $params;

    /**
     * @var Slide
     */
    protected $activeSlide;

    /**
     * Slider constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     * @param                $sliderId
     * @param                $parameters
     * @param                $isAdminArea
     */
    public function __construct($MVCHelper, $sliderId, $parameters, $isAdminArea = false) {
        $this->loadState = self::LOAD_STATE_NONE;

        $this->isAdminArea = $isAdminArea;

        $this->setMVCHelper($MVCHelper);

        $this->initPlatformSlider();

        $this->sliderId = $sliderId;

        $this->setElementId();

        $this->cacheId = static::getCacheId($this->sliderId);

        $this->parameters = array_merge($this->parameters, $parameters);

        $this->disableResponsive = $this->parameters['disableResponsive'];
    }


    public function setElementId() {
        $this->elementId = self::$_identifier . '-' . $this->sliderId;
    }

    public static function getCacheId($sliderId) {
        return self::$_identifier . '-' . $sliderId;
    }

    public function getAlias() {
        return $this->data->get('alias', '');
    }

    /**
     * @throws Exception
     */
    public function initSlider() {
        if ($this->loadState < self::LOAD_STATE_SLIDER) {

            $slidersModel = new ModelSliders($this->MVCHelper);
            $sliderRow    = $slidersModel->get($this->sliderId);

            if (empty($sliderRow)) {
                $this->hasError = true;
                throw new Exception('Slider does not exists!');
            } else {
                if (!$this->isAdminArea && $sliderRow['slider_status'] != 'published') {
                    $this->hasError = true;
                    throw new Exception('Slider is not published!');
                }

                if (!empty($this->parameters['sliderData'])) {
                    $sliderData         = $this->parameters['sliderData'];
                    $sliderRow['title'] = $sliderData['title'];
                    unset($sliderData['title']);
                    $sliderRow['type'] = $sliderData['type'];
                    unset($sliderData['type']);

                    $this->data   = new Data($sliderRow);
                    $this->params = new SliderParams($this->sliderId, $sliderRow['type'], $sliderData);
                } else {
                    $this->data   = new Data($sliderRow);
                    $this->params = new SliderParams($this->sliderId, $sliderRow['type'], $sliderRow['params'], true);
                }

                switch ($sliderRow['type']) {
                    case 'group':
                        throw new Exception(n2_('Groups are only available in the Pro version.'));
                    
                        $this->isGroup = true;
                        break;
                }
            }

            $this->loadState = self::LOAD_STATE_SLIDER;
        }
    }

    /**
     * @throws Exception
     */
    public function initSlides() {
        if ($this->loadState < self::LOAD_STATE_SLIDES) {

            $this->initSlider();

            if (!$this->isGroup) {
                $this->slidesBuilder = new Slides($this);

                $this->slidesBuilder->initSlides($this->parameters['slidesData'], $this->parameters['generatorData']);
            }

            $this->loadState = self::LOAD_STATE_SLIDES;
        }
    }

    /**
     * @throws Exception
     */
    public function initAll() {
        if ($this->loadState < self::LOAD_STATE_ALL) {

            $this->initSlides();
            $this->loadState = self::LOAD_STATE_ALL;
        }
    }


    private function setSliderIDFromAlias($slider) {
        if (is_numeric($slider)) {
            return $slider;
        } else {
            $slidersModel = new ModelSliders($this->MVCHelper);
            $slider       = $slidersModel->getByAlias($slider);

            return $slider['id'];
        }

    }

    private function loadSlider() {

        $this->sliderType = SliderTypeFactory::createFrontend($this->data->get('type', 'simple'), $this);
        $defaults         = $this->sliderType->getDefaults();

        $this->params->fillDefault($defaults);
        $this->sliderType->limitParams($this->params);

        if (!$this->isGroup) {
            $this->features = new FeatureManager($this);
        }

        return true;
    }

    public function getNextCacheRefresh() {
        if ($this->isGroup) {
            return $this->sliderType->getNextCacheRefresh();
        }

        return $this->slidesBuilder->getNextCacheRefresh();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render() {
        if ($this->loadState < self::LOAD_STATE_ALL) {
            throw new Exception('Load state not reached all!');
        }

        if (!$this->loadSlider()) {
            return false;
        }

        if (!$this->isGroup) {
            if (!$this->hasSlides()) {
                $this->slidesBuilder->addDummySlides();
            }

            if (!$this->getActiveSlide()) {
                $slides = $this->getSlides();
                $this->setActiveSlide($slides[0]);
            }

            $this->getActiveSlide()
                 ->setFirst();
        }

        $this->assets = SliderTypeFactory::createCss($this->data->get('type', 'simple'), $this);

        if (!$this->isGroup) {

            $this->slidesBuilder->prepareRender();

            $this->renderStaticSlide();
        }
        $slider = $this->sliderType->render($this->assets);

        $slider = str_replace('n2-ss-0', $this->elementId, $slider);

        $rockedLoader = false;
        if (!$this->isAdmin) {
            $rocketAttributes = '';

            $loadingType = $this->params->get('loading-type');
            if ($loadingType == 'afterOnLoad') {
                $rocketAttributes .= ' data-loading-type="' . $loadingType . '"';
            } else if ($loadingType == 'afterDelay') {

                $delay = max(0, intval($this->params->get('delay'), 0));
                if ($delay > 0) {
                    $rocketAttributes .= ' data-loading-type="' . $loadingType . '"';
                    $rocketAttributes .= ' data-loading-delay="' . $delay . '"';
                }
            }

            if (!empty($rocketAttributes)) {
                $slider       = '<template id="' . $this->elementId . '_t"' . $rocketAttributes . '>' . $slider . '</template>';
                $rockedLoader = true;
            }
        }
        if (!$this->isGroup) {
            $slider = $this->features->translateUrl->replaceUrl($slider) . HTML::tag('ss3-loader', array(), '');

            $slider = $this->features->align->renderSlider($slider, $this->assets->sizes['width']);
            $slider = $this->features->margin->renderSlider($slider);


            Css::addInline($this->features->translateUrl->replaceUrl($this->sliderType->getStyle()), $this->elementId);
            /**
             * On WordPress, we need to add the slider's Inline JavaScript into the Head.
             *
             * @see SSDEV-3540
             */
            Js::addInline($this->sliderType->getScript());
        }

        $html = '';

        $classes = array(
            'n2-section-smartslider',
            'fitvidsignore',
            $this->params->get('classes', '')
        );

        if (intval($this->params->get('clear-both', 1))) {
            $classes[] = 'n2_clear';
        }


        $sliderAttributes = array(
            'class'     => implode(' ', $classes),
            'data-ssid' => $this->sliderId
        );

        if ($this->fallbackId) {
            $sliderAttributes['data-fallback-for'] = $this->fallbackId;
        }

        $ariaLabel = $this->params->get('aria-label', 'Slider');
        if (!empty($ariaLabel)) {
            $sliderAttributes['tabindex']   = '0';
            $sliderAttributes['role']       = 'region';
            $sliderAttributes['aria-label'] = $ariaLabel;
        }

        $alias = $this->getAlias();
        if (!empty($alias)) {
            $sliderAttributes['data-alias'] = $alias;

            if (intval($this->params->get('alias-id', 0))) {
                $sliderAttributes['id'] = $alias;

                if (intval($this->params->get('alias-slideswitch-scroll', 1))) {
                    $slideAnchorHTML = '';
                    $slideCount      = $this->getSlidesCount();
                    for ($i = 1; $i <= $slideCount; $i++) {
                        $slideAnchorHTML .= Html::tag('div', array(
                            'id' => $alias . '-' . $i
                        ));
                    }

                    $slider = $slideAnchorHTML . $slider;
                }
            }
        }

        $sizes = $this->assets->sizes;

        if ($rockedLoader && !empty($sizes['width']) && !empty($sizes['height'])) {
            $sliderAttributes['style'] = 'height:' . $sizes['height'] . 'px;';
        }

        $html .= Html::tag("div", $sliderAttributes, $slider);

        if (!$this->params->get('optimize-jetpack-photon', 0)) {
            AssetManager::$image->add($this->images);
        }

        $needDivWrap = false;

        if (!$this->isGroup && !$this->isAdmin && $this->features->responsive->forceFull) {
            $html        = Html::tag("ss3-force-full-width", array(
                'data-overflow-x'          => $this->features->responsive->forceFullOverflowX,
                'data-horizontal-selector' => $this->features->responsive->forceFullHorizontalSelector
            ), $html);
            $needDivWrap = true;
        }

        if ($needDivWrap) {
            $attr = array();
            if ($this->params->get('clear-both', 1)) {
                $attr['class'] = 'n2_clear';
            }

            return Html::tag("div", $attr, $html);
        }

        return $html;
    }

    public function addStaticSlide($slide) {
        $this->staticSlides[] = $slide;
    }

    public function renderStaticSlide() {
        $this->staticHtml = '';
        if (count($this->staticSlides)) {
            for ($i = 0; $i < count($this->staticSlides); $i++) {
                $this->staticHtml .= $this->staticSlides[$i]->getAsStatic();
            }
        }
    }

    public static function removeShortcode($content) {
        $content = preg_replace('/smartslider3\[([0-9]+)\]/', '', $content);
        $content = preg_replace('/\[smartslider3 slider="([0-9]+)"\]/', '', $content);
        $content = preg_replace('/\[smartslider3 slider=([0-9]+)\]/', '', $content);

        return $content;
    }

    /**
     * @return Slide
     */
    public function getActiveSlide() {
        return $this->activeSlide;
    }

    /**
     * @param Slide $activeSlide
     */
    public function setActiveSlide($activeSlide) {
        $this->activeSlide = $activeSlide;
    }

    /**
     * @return Slide[]
     */
    public function getSlides() {
        return $this->slidesBuilder->getSlides();
    }

    /**
     * @return bool
     */
    public function hasSlides() {
        if ($this->isGroup) {
            return true;
        }

        return $this->slidesBuilder->hasSlides();
    }

    /**
     * @return int
     */
    public function getSlidesCount() {
        if ($this->isGroup) {
            return 0;
        }

        return $this->slidesBuilder->getSlidesCount();
    }

    public function isGroup() {
        $this->initSlider();

        return $this->isGroup;
    }

    public function isLegacyFontScale() {
        return !!$this->params->get('legacy-font-scale', 0);
    }
}