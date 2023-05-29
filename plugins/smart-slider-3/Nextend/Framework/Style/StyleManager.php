<?php


namespace Nextend\Framework\Style;


use Nextend\Framework\Pattern\VisualManagerTrait;
use Nextend\Framework\Style\Block\StyleManager\BlockStyleManager;

class StyleManager {

    use VisualManagerTrait;

    public function display() {

        $fontManagerBlock = new BlockStyleManager($this->MVCHelper);
        $fontManagerBlock->display();
    }
}