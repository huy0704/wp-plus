<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardInfo;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockDashboardInfo extends AbstractBlock {

    use TraitAdminUrl;

    public function display() {

        $this->renderTemplatePart('DashboardInfo');
    }
}