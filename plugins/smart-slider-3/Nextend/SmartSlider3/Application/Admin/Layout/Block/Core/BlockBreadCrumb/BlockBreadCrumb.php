<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\BlockBreadCrumb;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Helper\Breadcrumb;

class BlockBreadCrumb extends AbstractBlock {

    /**
     * @var Breadcrumb[]
     */
    protected $breadCrumbs = array();

    public function display() {
        $this->renderTemplatePart('BreadCrumb');
    }

    /**
     * @return Breadcrumb[]
     */
    public function getBreadCrumbs() {

        /**
         * If there is no activate item in the menu or in the breadcrumb, mark the last breadcrumb as active.
         */
        if (!$this->hasActiveItem()) {
            $this->breadCrumbs[count($this->breadCrumbs) - 1]->setIsActive(true);
        }

        return $this->breadCrumbs;
    }

    /**
     * @param        $label
     * @param        $icon
     * @param string $url
     *
     * @return Breadcrumb
     */
    public function addBreadcrumb($label, $icon, $url = '#') {

        $breadCrumb          = new Breadcrumb($label, $icon, $url);
        $this->breadCrumbs[] = $breadCrumb;

        return $breadCrumb;
    }

    private function hasActiveItem() {

        foreach ($this->breadCrumbs as $breadCrumb) {
            if ($breadCrumb->isActive()) {
                return true;
            }
        }

        return false;
    }
}