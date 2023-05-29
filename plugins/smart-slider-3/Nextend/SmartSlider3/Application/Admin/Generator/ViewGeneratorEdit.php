<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;

class ViewGeneratorEdit extends AbstractView {

    use TraitAdminUrl;

    protected $groupID;

    protected $groupTitle;

    /** @var array */
    protected $slider;

    /** @var array */
    protected $slide;

    /** @var array */
    protected $generator;

    /** @var AbstractGeneratorGroup */
    protected $generatorGroup;

    /** @var AbstractGenerator */
    protected $generatorSource;

    public function display() {

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->getSliderID(), $this->groupID));

        $this->layout->addBreadcrumb(n2_('Slide'), 'ssi_16 ssi_16--slides', $this->getUrlSlideEdit($this->slide['id'], $this->getSliderID(), $this->groupID));

        $this->layout->addBreadcrumb(n2_('Generator'), 'ssi_16 ssi_16--cog');

        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_button--inactive');
        $buttonSave->addClass('n2_generator_settings_save');
        $topBar->addPrimaryBlock($buttonSave);

        $buttonBack = new BlockButtonBack($this);
        $buttonBack->setUrl($this->getUrlSlideEdit($this->slide['id'], $this->getSliderID(), $this->groupID));
        $buttonBack->addClass('n2_generator_settings_back');
        $topBar->addPrimaryBlock($buttonBack);

        $buttonPreview = new BlockButtonPlainIcon($this);
        $buttonPreview->addAttribute('id', 'n2-ss-preview');
        $buttonPreview->addClass('n2_top_bar_button_icon');
        $buttonPreview->addClass('n2_top_bar_main__preview');
        $buttonPreview->setIcon('ssi_24 ssi_24--preview');
        $buttonPreview->addAttribute('data-n2tip', n2_('Preview'));
        $buttonPreview->setUrl($this->getUrlPreviewIndex($this->slider['id']));
        $topBar->addPrimaryBlock($buttonPreview);

        $this->layout->setTopBar($topBar->toHTML());

        $blockHeader = new BlockHeader($this);
        $blockHeader->setHeading(n2_('Generator') . ': ' . $this->generatorGroup->getLabel() . ' - ' . $this->generatorSource->getLabel());

        $this->layout->addContentBlock($blockHeader);

        $this->layout->addContent($this->render('Edit'));

        $this->layout->render();
    }

    public function renderForm() {

        $params = new Data($this->generator['params'], true);

        $slideParams = new Data($this->slide['params'], true);
        $params->set('record-slides', $slideParams->get('record-slides', 1));

        $form = new Form($this, 'generator');
        new Token($form->getFieldsetHidden());

        $form->loadArray($params->toArray());

        $this->generatorSource->renderFields($form->getContainer());

        $generatorModel = new ModelGenerator($this);
        $generatorModel->renderFields($form->getContainer());

        $form->render();
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
     * @return mixed
     */
    public function getGroupID() {
        return $this->groupID;
    }

    /**
     * @return array
     */
    public function getSlide() {
        return $this->slide;
    }

    /**
     * @param array $slide
     */
    public function setSlide($slide) {
        $this->slide = $slide;
    }

    /**
     * @return array
     */
    public function getGenerator() {
        return $this->generator;
    }

    /**
     * @param array $generator
     */
    public function setGenerator($generator) {
        $this->generator = $generator;
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

}