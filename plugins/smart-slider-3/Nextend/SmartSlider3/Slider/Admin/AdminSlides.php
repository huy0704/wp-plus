<?php


namespace Nextend\SmartSlider3\Slider\Admin;


use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slides;

class AdminSlides extends Slides {

    /**
     * @var Slide
     */
    protected $editedSlide;

    protected function slidesWhereQuery() {
        $date = Platform::getMysqlDate();

        return "   AND ((published = 1 AND (publish_up = '1970-01-01 00:00:00' OR publish_up < '{$date}')
                   AND (publish_down = '1970-01-01 00:00:00' OR publish_down > '{$date}'))
                   OR id = " . Request::$REQUEST->getInt('slideid') . ") ";
    }

    protected function createSlide($slideRow) {
        return new AdminSlide($this->slider, $slideRow);
    }

    protected function makeSlides($slidesData, $extendGenerator = array()) {

        $slides = &$this->slides;

        $editedSlideID = $this->slider->getEditedSlideID();

        $this->legacyFixOnlyStaticOverlays($slides);

        $staticSlides = array();
        for ($j = count($slides) - 1; $j >= 0; $j--) {
            $slide = $slides[$j];
            if ($slide->isStatic()) {
                if ($slide->id == $editedSlideID) {
                    $this->editedSlide = $slide;
                }
                $staticSlides[] = $slide;
                $this->slider->addStaticSlide($slide);
                array_splice($slides, $j, 1);
            }
        }

        for ($i = 0; $i < count($slides); $i++) {
            $slides[$i]->initGenerator($extendGenerator);
        }

        for ($i = count($slides) - 1; $i >= 0; $i--) {
            if ($slides[$i]->hasGenerator()) {
                array_splice($slides, $i, 1, $slides[$i]->expandSlideAdmin());
            }
        }

        if (!$this->editedSlide) {
            for ($i = 0; $i < count($slides); $i++) {
                if ($slides[$i]->id == $editedSlideID) {
                    $this->editedSlide = $slides[$i];
                    $this->slider->setActiveSlide($this->editedSlide);
                    break;
                }
            }
        }

        // If we edit a static slide -> remove other static slides from the canvas.
        if ($this->editedSlide->isStatic()) {
            for ($i = 0; $i < count($this->slider->staticSlides); $i++) {
                if ($this->slider->staticSlides[$i]->id != $editedSlideID) {
                    array_splice($this->slider->staticSlides, $i, 1);
                    $i--;
                }
            }

            if (empty($slides)) {
                /**
                 * When the currently edited slide is static and there is not other slide, we create a temporary empty slide
                 */
                $slidesModel = new ModelSlides($this->slider);

                $slides[] = $this->createSlide($slidesModel->convertSlideDataToDatabaseRow(array(
                    'id'              => 0,
                    'title'           => 'Slide #' . 1,
                    'layers'          => '[]',
                    'description'     => '',
                    'thumbnail'       => '',
                    'published'       => 1,
                    'publish_up'      => '0000-00-00 00:00:00',
                    'publish_down'    => '0000-00-00 00:00:00',
                    'backgroundImage' => ''
                )));
            }

            $this->slider->setActiveSlide($slides[0]);
        } else {

            if ($this->maximumSlideCount > 0) {
                array_splice($slides, $this->maximumSlideCount);

                if (!in_array($this->editedSlide, $slides)) {
                    $slides[] = $this->editedSlide;
                }
            }
        }


        $this->editedSlide->setCurrentlyEdited();

        for ($i = 0; $i < count($slides); $i++) {
            $slides[$i]->setPublicID($i + 1);
        }
    }

    /**
     * @return Slide
     */
    public function getEditedSlide() {
        return $this->editedSlide;
    }
}