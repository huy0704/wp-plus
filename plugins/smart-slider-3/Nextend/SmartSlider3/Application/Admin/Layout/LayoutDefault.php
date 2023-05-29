<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout;


use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Admin\BlockAdmin;

class LayoutDefault extends AbstractLayoutMenu {

    protected $subNavigation = '';

    protected $topBar = '';

    public function render() {
        $admin = new BlockAdmin($this);
        $admin->setLayout($this);

        foreach ($this->state as $name => $value) {
            $admin->setAttribute('data-' . $name, $value);
        }

        $admin->addClasses($this->getClasses());
        $admin->setHeader($this->getHeader());
        $admin->setSubNavigation($this->subNavigation);

        $admin->setTopBar($this->topBar);

        $admin->display();
    }

    /**
     * @param string $subNavigation
     */
    public function setSubNavigation($subNavigation) {
        $this->subNavigation = $subNavigation;
    }

    /**
     * @param string $topBar
     */
    public function setTopBar($topBar) {
        $this->topBar = $topBar;
    }
}