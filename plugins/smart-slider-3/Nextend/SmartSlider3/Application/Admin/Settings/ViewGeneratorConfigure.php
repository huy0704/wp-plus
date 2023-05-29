<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\MenuItem;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroupConfiguration;
use Nextend\SmartSlider3\Generator\GeneratorFactory;

class ViewGeneratorConfigure extends AbstractViewSettings {

    protected $active = 'generator';

    /** @var AbstractGeneratorGroup */
    protected $generatorGroup;

    /** @var AbstractGeneratorGroupConfiguration */
    protected $configuration;

    public function display() {

        parent::display();


        $this->layout->addBreadcrumb($this->generatorGroup->getLabel(), '');

        $this->layout->addContent($this->render('GeneratorConfigure'));

        $this->layout->render();
    }

    protected function displayTopBar() {

        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_button--inactive');
        $buttonSave->addClass('n2_generator_configuration_save');
        $topBar->addPrimaryBlock($buttonSave);

        $this->layout->setTopBar($topBar->toHTML());
    }

    protected function displayHeader() {

        $this->blockHeader = new BlockHeader($this);
        $this->blockHeader->setHeading(n2_('Generators'));

        foreach (GeneratorFactory::getGenerators() as $generatorGroup) {
            if ($generatorGroup->hasConfiguration()) {
                $button = new MenuItem($generatorGroup->getLabel());
                $button->setActive($this->generatorGroup === $generatorGroup);
                $button->setUrl($this->getUrlSettingsGenerator($generatorGroup->getName()));
                $this->blockHeader->addMenuItem($button);
            }
        }

        $this->layout->addContentBlock($this->blockHeader);
    }

    /**
     * @return AbstractGeneratorGroup
     */
    public function getGeneratorGroup() {
        return $this->generatorGroup;
    }

    /**
     * @param AbstractGeneratorGroup $generatorGroup
     */
    public function setGeneratorGroup($generatorGroup) {
        $this->generatorGroup = $generatorGroup;
    }

    /**
     * @return mixed
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * @param mixed $configuration
     */
    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
    }

    public function renderForm() {

        $this->configuration->render($this);
    }

}