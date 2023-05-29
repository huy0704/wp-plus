<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;

class ViewGeneratorCreateStep4Settings extends AbstractView {

    use TraitAdminUrl;

    protected $groupID = 0;

    protected $groupTitle = '';

    /** @var array */
    protected $slider;

    /** @var AbstractGeneratorGroup */
    protected $generatorGroup;

    /** @var AbstractGenerator */
    protected $generatorSource;

    public function display() {

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb(n2_('Add dynamic slides'), '', $this->getUrlGeneratorCreate($this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb($this->generatorGroup->getLabel(), '', $this->getUrlGeneratorCreateStep2($this->generatorGroup->getName(), $this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb($this->generatorSource->getLabel(), '');


        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_generator_add');
        $buttonSave->setLabel(n2_('Add'));
        $topBar->addPrimaryBlock($buttonSave);

        $buttonCancel = new BlockButtonCancel($this);
        $buttonCancel->addClass('n2_generator_add_cancel');
        $buttonCancel->setUrl($this->getUrlSliderEdit($this->slider['id'], $this->groupID));
        $topBar->addPrimaryBlock($buttonCancel);

        $this->layout->setTopBar($topBar->toHTML());

        $blockHeader = new BlockHeader($this);
        $blockHeader->setHeading(n2_('Add dynamic slides') . ': ' . $this->generatorGroup->getLabel() . ' - ' . $this->generatorSource->getLabel());

        $this->layout->addContentBlock($blockHeader);

        $this->layout->addContent($this->render('CreateStep4Settings'));

        $this->layout->render();
    }

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
     * @return int
     */
    public function getGroupID() {
        return $this->groupID;
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @return integer
     */
    public function getSliderID() {

        return $this->slider['id'];
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
     * @return AbstractGenerator
     */
    public function getGeneratorSource() {
        return $this->generatorSource;
    }

    /**
     * @param AbstractGenerator $generatorSource
     */
    public function setGeneratorSource($generatorSource) {
        $this->generatorSource = $generatorSource;
    }

    public function displayForm() {
        $form = new Form($this, 'generator');
        new Token($form->getFieldsetHidden());

        $this->generatorSource->renderFields($form->getContainer());

        $generatorModel = new ModelGenerator($this);
        $generatorModel->renderFields($form->getContainer());
        $form->render();
    }

}