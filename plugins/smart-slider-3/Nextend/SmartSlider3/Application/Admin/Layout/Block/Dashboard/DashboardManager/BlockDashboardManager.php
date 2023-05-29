<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardManager;


use Nextend\Framework\View\AbstractBlock;

class BlockDashboardManager extends AbstractBlock {

    public function display() {

        $this->renderTemplatePart('DashboardManager');
    }

}