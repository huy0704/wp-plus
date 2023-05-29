<?php

namespace Nextend\SmartSlider3\Application\Admin\Generator;

use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroupConfiguration;

class ViewGeneratorCreateStep2Configure extends AbstractView {

    use TraitAdminUrl;

    protected $active = 'general';

    /** @var LayoutDefault */
    protected $layout;

    /**
     * @var BlockHeader
     */
    protected $blockHeader;

    protected $groupID;

    protected $groupTitle;

    /** @var array */
    protected $slider;

    /** @var AbstractGeneratorGroup */
    protected $generatorGroup;

    /** @var AbstractGeneratorGroupConfiguration */
    protected $configuration;

    /**
     * @param int    $groupID
     * @param string $groupTitle
     */
    public function setGroupData($groupID, $groupTitle) {
        $this->groupID    = $groupID;
        $this->groupTitle = $groupTitle;
    }

    /**
     * @return array
     */
    public function getSlider() {
        return $this->slider;
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    public function hasSlider() {

        return !is_null($this->slider);
    }

    public function getSliderID() {

        return $this->slider['id'];
    }

    /**
     * @return mixed
     */
    public function getGroupID() {
        return $this->groupID;
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

    public function display() {

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb(n2_('Add dynamic slides'), '', $this->getUrlGeneratorCreate($this->slider['id'], $this->groupID));


        $this->layout->addBreadcrumb(n2_('Configure') . ' - ' . $this->generatorGroup->getLabel(), '');


        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_button--inactive');
        $buttonSave->addClass('n2_generator_configuration_save');
        $topBar->addPrimaryBlock($buttonSave);

        $buttonCancel = new BlockButtonCancel($this);
        $buttonCancel->addClass('n2_generator_configuration_cancel');
        $buttonCancel->setUrl($this->getUrlGeneratorCreate($this->slider['id'], $this->groupID));
        $topBar->addPrimaryBlock($buttonCancel);

        $this->layout->setTopBar($topBar->toHTML());

        $blockHeader = new BlockHeader($this);
        $blockHeader->setHeading(n2_('Configure') . ': ' . $this->generatorGroup->getLabel());

        $this->layout->addContentBlock($blockHeader);

        $this->layout->addContent($this->render('CreateStep2Configure'));

        $this->layout->render();
    }
}