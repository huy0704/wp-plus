<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\AbstractLayerWindowSettings;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsColumn;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsCommon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsContent;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsItem;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsItemCommon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsRow;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings\LayerWindowSettingsSlide;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab\AbstractTab;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab\TabAnimation;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab\TabContent;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab\TabGoPro;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab\TabStyle;
use Nextend\SmartSlider3\Renderable\Item\ItemFactory;
use Nextend\SmartSlider3\Slider\Admin\AdminSlider;

class BlockLayerWindow extends AbstractBlock {

    /**
     * @var AdminSlider
     */
    protected $renderableAdminSlider;

    /**
     * @var AbstractTab[]
     */
    protected $tabs = array();

    /**
     * @var AbstractLayerWindowSettings[]
     */
    protected $settings = array();

    /**
     * @param AdminSlider $renderableAdminSlider
     */
    public function setRenderableAdminSlider($renderableAdminSlider) {
        $this->renderableAdminSlider = $renderableAdminSlider;
    }

    public function display() {


        $this->tabs['content'] = new TabContent($this);
        $this->tabs['style']   = new TabStyle($this);
        $this->tabs['animation'] = new TabGoPro($this);
    


        $this->settings[] = new LayerWindowSettingsSlide($this, $this->renderableAdminSlider);
        $this->settings[] = new LayerWindowSettingsContent($this);
        $this->settings[] = new LayerWindowSettingsRow($this);
        $this->settings[] = new LayerWindowSettingsColumn($this);

        foreach (ItemFactory::getItems() as $type => $item) {
            $this->settings[] = new LayerWindowSettingsItem($type, $item, $this, $this->renderableAdminSlider);
        }

        $this->settings[] = new LayerWindowSettingsItemCommon($this);

        $this->settings[] = new LayerWindowSettingsCommon($this);

        foreach ($this->settings as $setting) {
            $setting->extendForm($this->tabs['content']->getContainer(), $this->tabs['style']->getContainer());
        }

        $this->renderTemplatePart('LayerWindow');
    }

    /**
     * @return AbstractTab[]
     */
    public function getTabs() {

        return $this->tabs;
    }
}