<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardManager\Boxes;


use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\View\AbstractBlock;

class BlockDashboardNewsletter extends AbstractBlock {


    public function display() {
        $storage = StorageSectionManager::getStorage('smartslider');

        if (!$storage->get('free', 'subscribeOnImport') && !$storage->get('free', 'dismissNewsletterDashboard')) {
            $this->renderTemplatePart('DashboardNewsletter');
        }
    }
}