<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu;

use Nextend\Framework\View\AbstractBlock;

class BlockFloatingMenuItemSeparator extends AbstractBlock {

    protected $classes = array();

    public function display() {

        echo '<div class="' . esc_attr(implode(' ', array_merge(array(
                'n2_floating_menu__item_separator'
            ), $this->classes))) . '"></div>';
    }

    /**
     * @param array $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }


}