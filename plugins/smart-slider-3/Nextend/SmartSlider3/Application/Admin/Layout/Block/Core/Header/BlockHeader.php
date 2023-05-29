<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;

class BlockHeader extends AbstractBlock {

    protected $heading = '';

    protected $headingAfter = '';

    protected $actions = array();

    /**
     * @var MenuItem[]
     */
    protected $menuItems = array();

    public function display() {

        $this->renderTemplatePart('Header');
    }

    /**
     * @return string
     */
    public function getHeading() {
        return $this->heading;
    }

    /**
     * @param string $heading
     */
    public function setHeading($heading) {
        $this->heading = Sanitize::esc_html($heading);
    }

    /**
     * @return string
     */
    public function getHeadingAfter() {
        return $this->headingAfter;
    }

    public function hasHeadingAfter() {
        return !empty($this->headingAfter);
    }

    /**
     * @param string $headingAfter
     */
    public function setHeadingAfter($headingAfter) {
        $this->headingAfter = $headingAfter;
    }

    /**
     * @return array
     */
    public function getActions() {
        return $this->actions;
    }

    public function hasActions() {
        return !empty($this->actions);
    }

    /**
     * @param string $action
     */
    public function addAction($action) {
        $this->actions[] = $action;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems() {
        return $this->menuItems;
    }

    public function hasMenuItems() {
        return !empty($this->menuItems);
    }

    /**
     * @param MenuItem $menuItem
     */
    public function addMenuItem($menuItem) {
        $this->menuItems[] = $menuItem;
    }


}