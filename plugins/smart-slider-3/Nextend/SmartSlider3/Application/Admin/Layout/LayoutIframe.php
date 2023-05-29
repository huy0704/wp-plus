<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout;


use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\AbstractLayout;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminIframe\BlockAdminIframe;

class LayoutIframe extends AbstractLayout {

    protected $label = '';

    /**
     * @var AbstractBlock[]
     */
    protected $actions = array();

    public function render() {

        $admin = new BlockAdminIframe($this);
        $admin->setLayout($this);
        $admin->setLabel($this->label);
        $admin->setActions($this->actions);

        $admin->display();
    }

    /**
     * @param string $label
     */
    public function setLabel($label) {
        $this->label = $label;
    }

    /**
     * @param AbstractBlock $block
     */
    public function addAction($block) {
        $this->actions[] = $block;
    }
}