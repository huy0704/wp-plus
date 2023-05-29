<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\EditorOverlay;

use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\BlockBreadCrumb\BlockBreadCrumb;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\TopBarMainEditor\BlockTopBarMainEditor;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\AddLayer\BlockAddLayer;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\BlockSlideManager;

class BlockEditorOverlay extends AbstractBlock {

    /**
     * @var BlockTopBarMainEditor
     */
    protected $topBar;

    /**
     * @var BlockBreadCrumb
     */
    protected $blockBreadCrumb;

    /**
     * @var BlockSlideManager
     */
    protected $slideManager;

    /**
     * @var BlockAddLayer
     */
    protected $blockAddLayer;

    /**
     * @var string
     */
    protected $contentLayerWindow;

    protected function init() {
        $this->topBar = new BlockTopBarMainEditor($this);

        $this->blockBreadCrumb = new BlockBreadCrumb($this);
        $this->topBar->addSecondaryBlock($this->blockBreadCrumb);
    }

    public function display() {

        $this->renderTemplatePart('EditorOverlay');
    }

    /**
     * @param BlockSlideManager $slideManager
     */
    public function setSlideManager($slideManager) {
        $this->slideManager = $slideManager;
        $slideManager->addClass('n2_admin_editor__ui_slide_manager');
    }

    public function displaySlideManager() {
        $this->slideManager->display();
    }

    /**
     * @return BlockTopBarMainEditor
     */
    public function getTopBar() {
        return $this->topBar;
    }

    public function displayTopBar() {
        $this->topBar->display();
    }

    /**
     * @return BlockBreadCrumb
     */
    public function getBlockBreadCrumb() {
        return $this->blockBreadCrumb;
    }

    /**
     * @param BlockAddLayer $blockAddLayer
     */
    public function setBlockAddLayer($blockAddLayer) {
        $this->blockAddLayer = $blockAddLayer;
    }

    public function displayBlockAddLayer() {
        $this->blockAddLayer->display();
    }

    /**
     * @param string $contentLayerWindow
     */
    public function setContentLayerWindow($contentLayerWindow) {
        $this->contentLayerWindow = $contentLayerWindow;
    }

    public function displayBlockLayerWindow() {
        echo wp_kses($this->contentLayerWindow, Sanitize::$adminFormTags);
    }

}