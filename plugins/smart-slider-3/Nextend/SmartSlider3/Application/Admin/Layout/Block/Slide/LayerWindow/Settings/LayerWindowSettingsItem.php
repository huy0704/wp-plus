<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\BlockLayerWindow;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;
use Nextend\SmartSlider3\Slider\Admin\AdminSlider;

class LayerWindowSettingsItem extends AbstractLayerWindowSettings {

    protected $type;

    /**
     * @var AbstractItem
     */
    protected $item;

    /**
     * LayerWindowSettingsItem constructor.
     *
     * @param string           $type
     * @param AbstractItem     $item
     * @param BlockLayerWindow $blockLayerWindow
     * @param AdminSlider      $renderableAdminSlider
     */
    public function __construct($type, $item, $blockLayerWindow, $renderableAdminSlider) {

        $this->type = $type;

        $this->item = $item;

        Js::addGlobalInline('window["itemValues/' . $this->type . '"]=' . json_encode($item->getValues()) . ';');

        $item->loadResources($renderableAdminSlider);

        parent::__construct($blockLayerWindow);
    }

    public function getName() {
        return 'item/' . $this->type;
    }

    protected function createContentContainer($container) {
        parent::createContentContainer($container);
        $this->contentContainer->setControlName('item_' . $this->type);
    }

    protected function createStyleContainer($container) {
        parent::createStyleContainer($container);
        $this->styleContainer->setControlName('item_' . $this->type);
    }

    protected function extendContent() {

        $this->item->renderFields($this->contentContainer);
    }

    protected function extendStyle() {

    }
}