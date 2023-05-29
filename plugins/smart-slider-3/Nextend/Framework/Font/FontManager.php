<?php

namespace Nextend\Framework\Font;

use Nextend\Framework\Font\Block\FontManager\BlockFontManager;
use Nextend\Framework\Pattern\VisualManagerTrait;

class FontManager {

    use VisualManagerTrait;

    public function display() {

        $fontManagerBlock = new BlockFontManager($this->MVCHelper);
        $fontManagerBlock->display();
    }
}