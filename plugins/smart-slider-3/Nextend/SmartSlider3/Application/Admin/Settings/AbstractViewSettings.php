<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\MenuItem;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Generator\GeneratorFactory;

abstract class AbstractViewSettings extends AbstractView {

    use TraitAdminUrl;

    protected $active = 'general';

    /** @var LayoutDefault */
    protected $layout;

    /**
     * @var BlockHeader
     */
    protected $blockHeader;

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Settings'), 'ssi_16 ssi_16--cog', $this->getUrlSettingsDefault());

        $this->displayTopBar();

        $this->displayHeader();
    }

    protected function displayTopBar() {

        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_button--inactive');
        $buttonSave->addClass('n2_settings_save');
        $topBar->addPrimaryBlock($buttonSave);

        $this->layout->setTopBar($topBar->toHTML());
    }

    protected function displayHeader() {


        $this->blockHeader = new BlockHeader($this);
        $this->blockHeader->setHeading(n2_('Settings'));

        $general = new MenuItem(n2_('General'));
        $general->setUrl($this->getUrlSettingsDefault());
        $general->setActive($this->active == 'general');
        $this->blockHeader->addMenuItem($general);

        $framework = new MenuItem(n2_('Framework'));
        $framework->setUrl($this->getUrlSettingsFramework());
        $framework->setActive($this->active == 'framework');
        $this->blockHeader->addMenuItem($framework);

        $fonts = new MenuItem(n2_('Fonts'));
        $fonts->setUrl($this->getUrlSettingsFonts());
        $fonts->setActive($this->active == 'fonts');
        $this->blockHeader->addMenuItem($fonts);

        $itemDefaults = new MenuItem(n2_('Layer defaults'));
        $itemDefaults->setUrl($this->getUrlSettingsItemDefaults());
        $itemDefaults->setActive($this->active == 'itemDefaults');
        $this->blockHeader->addMenuItem($itemDefaults);

        foreach (GeneratorFactory::getGenerators() as $generator) {
            if ($generator->hasConfiguration()) {
                $generators = new MenuItem(n2_('Generators'));
                $generators->setUrl($this->getUrlSettingsGenerator($generator->getName()));
                $this->blockHeader->addMenuItem($generators);

                break;
            }
        }

        $this->addHeaderActions();

        $this->layout->addContentBlock($this->blockHeader);
    }

    protected function addHeaderActions() {

    }
}