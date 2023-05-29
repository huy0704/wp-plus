<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\NavBar;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\BlockBreadCrumb\BlockBreadCrumb;
use Nextend\SmartSlider3\Application\Admin\Layout\Helper\Breadcrumb;
use Nextend\SmartSlider3\Application\Admin\Layout\Helper\MenuItem;

class BlockNavBar extends AbstractBlock {

    protected $sidebarLink = '';

    protected $logo = '';

    /**
     * @var MenuItem[]
     */
    protected $menuItems = array();

    /**
     * @var BlockBreadCrumb
     */
    protected $blockBreadCrumb;

    public function display() {

        $this->renderTemplatePart('NavBar');
    }

    protected function init() {
        $this->blockBreadCrumb = new BlockBreadCrumb($this);
    }

    /**
     * @return string
     */
    public function getSidebarLink() {
        return $this->sidebarLink;
    }

    /**
     * @param string $sidebarLink
     */
    public function setSidebarLink($sidebarLink) {
        $this->sidebarLink = $sidebarLink;
    }

    /**
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo) {
        $this->logo = $logo;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems() {
        return $this->menuItems;
    }

    /**
     * @param string $menuItem
     * @param bool   $isActive
     */
    public function addMenuItem($menuItem, $isActive = false) {
        $this->menuItems[] = new MenuItem($menuItem, $isActive);
    }

    /**
     * @param        $label
     * @param        $icon
     * @param string $url
     *
     * @return Breadcrumb
     */
    public function addBreadcrumb($label, $icon, $url = '#') {

        return $this->blockBreadCrumb->addBreadcrumb($label, $icon, $url);
    }

    public function displayBreadCrumbs() {
        $this->blockBreadCrumb->display();
    }

}