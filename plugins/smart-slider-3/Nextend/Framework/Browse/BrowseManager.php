<?php


namespace Nextend\Framework\Browse;


use Nextend\Framework\Browse\Block\BrowseManager\BlockBrowseManager;
use Nextend\Framework\Pattern\VisualManagerTrait;

class BrowseManager {

    use VisualManagerTrait;

    public function display() {

        $fontManagerBlock = new BlockBrowseManager($this->MVCHelper);
        $fontManagerBlock->display();
    }

}