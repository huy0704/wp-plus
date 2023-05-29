<?php


namespace Nextend\SmartSlider3\Slider;


use Nextend\SmartSlider3\Application\Model\ModelSlides;

class Slides {

    /**
     * @var Slider
     */
    protected $slider;

    /**
     * @var Slide[]
     */
    protected $slides = array();

    /**
     * @var Slide[]
     */
    protected $allEnabledSlides = array();

    protected $maximumSlideCount = 10000;

    /**
     * Slides constructor.
     *
     * @param Slider $slider
     */
    public function __construct($slider) {
        $this->slider = $slider;

        $this->maximumSlideCount = intval($slider->params->get('maximumslidecount', 10000));
    }


    public function initSlides($slidesData = array(), $generatorData = array()) {

        $this->loadSlides($slidesData);

        $this->makeSlides($slidesData, $generatorData);

        return $this->slides;
    }

    /**
     * @param Slide[] $slides
     */
    protected function legacyFixOnlyStaticOverlays(&$slides) {

        if (count($slides)) {
            $hasNonStaticSlide = false;
            foreach ($slides as $slide) {
                if (!$slide->isStatic()) {
                    $hasNonStaticSlide = true;
                }
            }

            if (!$hasNonStaticSlide) {
                foreach ($slides as $slide) {
                    $slide->forceNonStatic();
                }
            }
        }
    }

    protected function makeSlides($slidesData, $extendGenerator = array()) {

        $slides = &$this->slides;

        for ($i = 0; $i < count($slides); $i++) {
            $slides[$i]->initGenerator($extendGenerator);
        }

        for ($i = count($slides) - 1; $i >= 0; $i--) {
            if ($slides[$i]->hasGenerator()) {
                array_splice($slides, $i, 1, $slides[$i]->expandSlide());
            }
        }

        $this->legacyFixOnlyStaticOverlays($slides);

        $staticSlides = array();
        for ($j = count($slides) - 1; $j >= 0; $j--) {
            $slide = $slides[$j];
            if ($slide->isStatic()) {
                $staticSlides[] = $slide;
                $this->slider->addStaticSlide($slide);
                array_splice($slides, $j, 1);
            }
        }

        $randomize      = intval($this->slider->params->get('randomize', 0));
        $randomizeFirst = intval($this->slider->params->get('randomizeFirst', 0));
        $randomizeCache = intval($this->slider->params->get('randomize-cache', 0));
        if (!$randomizeCache && $randomize) {
            shuffle($slides);
        }

        $reverse = intval($this->slider->params->get('reverse-slides', 0));
        if ($reverse) {
            $slides = array_reverse($slides);
        }

        if ($this->maximumSlideCount > 0) {
            $mustShowSlides = array();
            if (!empty($slidesData)) {
                for ($i = count($slides) - 1; $i >= 0; $i--) {
                    if (isset($slidesData[$slides[$i]->id])) {
                        $mustShowSlides[] = $slides[$i];
                    }
                }
            }
            array_splice($slides, $this->maximumSlideCount);

            if (!empty($mustShowSlides)) {
                for ($i = count($mustShowSlides) - 1; $i >= 0; $i--) {
                    if (!in_array($mustShowSlides[$i], $slides)) {
                        array_pop($slides);
                    } else {
                        array_splice($mustShowSlides, $i, 1);
                    }
                }
                $slides = array_merge($slides, $mustShowSlides);
            }

        }

        if (!$randomizeCache && $randomizeFirst) {
            $this->slider->setActiveSlide($slides[mt_rand(0, count($slides) - 1)]);
        } else {
            for ($i = 0; $i < count($slides); $i++) {
                if ($slides[$i]->isFirst()) {
                    $this->slider->setActiveSlide($slides[$i]);
                    break;
                }
            }
        }

        if (count($slides) == 1 && $this->slider->params->get('autoplay', 0) && $this->slider->data->get('type') === 'simple' && !$slides[0]->hasGenerator()) {
            $slides[1] = clone $slides[0];
        }

        for ($i = 0; $i < count($slides); $i++) {
            $slides[$i]->setPublicID($i + 1);
        }
    }


    public function addDummySlides() {
        /**
         * When the currently edited slide is static and there is not other slide, we create a temporary empty slide
         */
        $slidesModel = new ModelSlides($this->slider);

        $images = array(
            '$ss3-frontend$/images/placeholder/placeholder1.png',
            '$ss3-frontend$/images/placeholder/placeholder2.png'
        );
        for ($i = 0; $i < count($images); $i++) {

            $this->slides[] = $this->createSlide($slidesModel->convertSlideDataToDatabaseRow(array(
                'id'                    => $i,
                'title'                 => 'Slide #' . $i,
                'layers'                => '[]',
                'description'           => '',
                'thumbnail'             => $images[$i],
                'published'             => 1,
                'publish_up'            => '0000-00-00 00:00:00',
                'publish_down'          => '0000-00-00 00:00:00',
                'backgroundImage'       => $images[$i],
                "backgroundFocusX"      => 50,
                "backgroundFocusY"      => 100,
                'slide-background-type' => 'image'
            )));
        }

        $this->makeSlides(array());
    }

    protected function loadSlides($extend) {

        $where = $this->slidesWhereQuery();

        $slidesModel = new ModelSlides($this->slider);
        $slideRows   = $slidesModel->getAll($this->slider->sliderId, $where);

        for ($i = 0; $i < count($slideRows); $i++) {
            if (isset($extend[$slideRows[$i]['id']])) {
                $slideRows[$i] = array_merge($slideRows[$i], $extend[$slideRows[$i]['id']]);
            }
            $slide = $this->createSlide($slideRows[$i]);
            if ($slide->isVisible()) {
                $this->slides[] = $slide;
            }
            $this->allEnabledSlides[$i] = $slide;
        }
    }

    protected function createSlide($slideRow) {
        return new Slide($this->slider, $slideRow);
    }

    protected function slidesWhereQuery() {
        return " AND published = 1 ";
    }

    public function getNextCacheRefresh() {
        $earlier = 2145916800;
        for ($i = 0; $i < count($this->allEnabledSlides); $i++) {
            $earlier = min($this->allEnabledSlides[$i]->nextCacheRefresh, $earlier);
        }

        return $earlier;
    }

    /**
     * @return Slide[]
     */
    public function getSlides() {
        return $this->slides;
    }

    /**
     * @return int
     */
    public function getSlidesCount() {
        return count($this->slides);
    }

    /**
     * @return bool
     */
    public function hasSlides() {
        return !empty($this->slides);
    }

    public function prepareRender() {

        for ($i = 0; $i < count($this->slides); $i++) {
            $this->slides[$i]->setIndex($i);
            $this->slides[$i]->prepare();
            $this->slides[$i]->setSlidesParams();
        }
    }
}