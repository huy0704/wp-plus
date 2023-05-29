<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardManager\Boxes;


use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Model\ModelSliders;

class BlockDashboardReview extends AbstractBlock {


    public function display() {
        if (!StorageSectionManager::getStorage('smartslider')
                                  ->get('free', 'rated')) {

            $modelSliders = new ModelSliders($this);
            if ($modelSliders->getSlidersCount() >= 3) {
                $this->renderTemplatePart('DashboardReview');
            }
        }
    }
}