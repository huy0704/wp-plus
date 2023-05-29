<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout;

use Nextend\Framework\View\AbstractLayout;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminEditor\BlockAdminEditor;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\BlockBreadCrumb\BlockBreadCrumb;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\EditorOverlay\BlockEditorOverlay;
use Nextend\SmartSlider3\Application\Admin\Layout\Helper\Breadcrumb;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class LayoutEditor extends AbstractLayout {

    use TraitAdminUrl;

    /**
     * @var BlockEditorOverlay
     */
    protected $editorOverlay;

    /**
     * @var BlockBreadCrumb
     */
    protected $blockBreadCrumb;

    public function render() {
        $admin = new BlockAdminEditor($this);
        $admin->setLayout($this);
        foreach ($this->state as $name => $value) {
            $admin->setAttribute('data-' . $name, $value);
        }

        $admin->setEditorOverlay($this->editorOverlay);

        $admin->display();
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

    /**
     * @param BlockEditorOverlay $editorOverlay
     */
    public function setEditorOverlay($editorOverlay) {
        $this->editorOverlay   = $editorOverlay;
        $this->blockBreadCrumb = $editorOverlay->getBlockBreadCrumb();
    }


}