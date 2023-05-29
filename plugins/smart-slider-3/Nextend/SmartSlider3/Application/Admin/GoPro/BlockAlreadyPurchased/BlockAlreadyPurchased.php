<?php

namespace Nextend\SmartSlider3\Application\Admin\GoPro\BlockAlreadyPurchased;

use Nextend\Framework\View\AbstractBlock;

class BlockAlreadyPurchased extends AbstractBlock {

    public function display() {
        $this->renderTemplatePart('AlreadyPurchased');
    }
}