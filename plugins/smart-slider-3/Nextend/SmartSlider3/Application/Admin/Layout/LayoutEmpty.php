<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout;


use Nextend\Framework\View\AbstractLayout;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminEmpty\BlockAdminEmpty;

class LayoutEmpty extends AbstractLayout {

    public function render() {
        $admin = new BlockAdminEmpty($this);
        $admin->setLayout($this);

        $admin->display();
    }

}