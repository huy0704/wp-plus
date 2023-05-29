<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


use Nextend\Framework\View\AbstractBlock;

class BlockButtonSpacer extends AbstractBlock {

    protected $isVisible = false;

    public function display() {

        $classes = array('n2_button_spacer');

        if ($this->isVisible) {
            $classes[] = 'n2_button_spacer--visible';
        }

        echo '<div class="' . esc_attr(implode(' ', $classes)) . '"></div>';
    }

    /**
     * @param bool $isVisible
     */
    public function setIsVisible($isVisible) {
        $this->isVisible = $isVisible;
    }
}