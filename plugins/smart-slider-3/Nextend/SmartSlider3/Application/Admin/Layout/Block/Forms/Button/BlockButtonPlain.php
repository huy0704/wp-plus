<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonPlain extends AbstractButtonLabel {

    protected $baseClass = 'n2_button_plain';

    protected $color = '';

    public function setColorBlue() {
        $this->color = 'blue';
    }

    /**
     * @return array
     */
    public function getClasses() {

        $classes = parent::getClasses();

        if (!empty($this->color)) {
            $classes[] = $this->baseClass . '--color-' . $this->color;
        }

        return $classes;
    }
}