<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout;


use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Admin\BlockAdmin;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\ContentSidebar\BlockContentSidebar;

class LayoutDefaultSidebar extends AbstractLayoutMenu {

    protected $sidebar = array();

    protected function enqueueAssets() {

        $this->getApplicationType()
             ->enqueueAssets();
    }

    public function addSidebarBlock($html) {

        $this->sidebar[] = $html;
    }

    public function renderSidebarBlock() {
        echo wp_kses($this->getSidebarBlock(), Sanitize::$adminTemplateTags);
    }

    public function getSidebarBlock() {
        return implode("\n\n", $this->sidebar);
    }

    public function render() {

        $admin = new BlockAdmin($this);
        $admin->setLayout($this);

        $admin->addClasses($this->getClasses());
        $admin->setHeader($this->getHeader());

        $content = new BlockContentSidebar($this);
        $content->setSidebar($this->getSidebarBlock());
        $this->addContentBlock($content);

        $admin->display();
    }
}