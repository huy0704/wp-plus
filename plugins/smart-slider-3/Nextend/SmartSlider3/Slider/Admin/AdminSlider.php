<?php


namespace Nextend\SmartSlider3\Slider\Admin;


use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class AdminSlider extends Slider {

    public $isAdmin = true;

    protected $editedSlideID;

    /**
     * @var AdminSlides
     */
    protected $slidesBuilder;

    public function __construct($MVCHelper, $sliderId, $parameters) {
        parent::__construct($MVCHelper, $sliderId, $parameters, true);
    }

    public static function getCacheId($sliderId) {
        return self::$_identifier . '-admin-' . $sliderId;
    }

    public function setElementId() {
        $this->elementId = self::$_identifier . '-' . 0;
    }

    public function initSlides() {
        if ($this->loadState < self::LOAD_STATE_SLIDES) {

            $this->initSlider();

            if (!$this->isGroup) {
                $this->slidesBuilder = new AdminSlides($this);

                $this->slidesBuilder->initSlides($this->parameters['slidesData'], $this->parameters['generatorData']);
            }

            $this->loadState = self::LOAD_STATE_SLIDES;
        }
    }

    /**
     * @return Slide
     */
    public function getEditedSlide() {
        return $this->slidesBuilder->getEditedSlide();
    }

    /**
     * @return int
     */
    public function getEditedSlideID() {
        return $this->editedSlideID;
    }

    /**
     * @param int $editedSlideID
     */
    public function setEditedSlideID($editedSlideID) {
        $this->editedSlideID = $editedSlideID;
    }


}