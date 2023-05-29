<?php


namespace Nextend\SmartSlider3\Application\Admin\Slides;


use Nextend\Framework\Request\Request;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\FormManager\FormManagerSlide;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarGroup\BlockTopBarGroup;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSpacer;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\AddLayer\BlockAddLayer;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\EditorOverlay\BlockEditorOverlay;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\BlockLayerWindow;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\BlockSlideManager;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\DeviceZoom\BlockDeviceZoom;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutEditor;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Slider\Admin\AdminSlider;
use Nextend\SmartSlider3\Slider\Slider;

class ViewSlidesEdit extends AbstractView {

    use TraitAdminUrl;

    /** @var LayoutEditor */
    protected $layout;

    /**
     * @var BlockEditorOverlay
     */
    protected $editorOverlay;

    /** @var ModelSlides */
    protected $model;

    /**
     * @var Slider
     */
    protected $frontendSlider;

    /**
     * @var string contains already escaped data
     */
    protected $renderedSlider;

    protected $groupID = 0;

    protected $groupTitle = '';

    protected $slider;

    protected $slide = false;

    /**
     * @var BlockHeader
     */
    protected $blockHeader;

    /**
     * @var FormManagerSlide
     */
    protected $formManager;

    public function __construct($controller) {
        parent::__construct($controller);

        $this->model = new ModelSlides($this);

    }

    public function display() {

        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, "C");

        $this->layout = new LayoutEditor($this);

        $this->editorOverlay = new BlockEditorOverlay($this);
        $this->layout->setEditorOverlay($this->editorOverlay);

        $this->frontendSlider = new AdminSlider($this->MVCHelper, Request::$GET->getInt('sliderid'), array(
            'disableResponsive' => true
        ));
        $this->frontendSlider->setEditedSlideID($this->getSlideID());
        $this->frontendSlider->initSlider();
        $this->frontendSlider->initSlides();

        /**
         * Layer window should be rendered before the slider render as layers items might add CSS and JS codes to it.
         */
        $layerWindowBlock = new BlockLayerWindow($this);
        $layerWindowBlock->setRenderableAdminSlider($this->frontendSlider);
        $this->editorOverlay->setContentLayerWindow($layerWindowBlock->toHTML());

        $this->frontendSlider->initAll();
        $this->frontendSlider->addScript('new _N2.DeviceChanger(this);');
        $this->renderedSlider = $this->frontendSlider->render();

        $this->formManager = new FormManagerSlide($this, $this->groupID, $this->frontendSlider, $this->slide);

        $this->layout->addBreadcrumb(n2_('Dashboard'), 'ssi_16 ssi_16--dashboard', $this->getUrlDashboard());

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));

        $this->addActiveBreadcrumb();

        if (!empty($this->slide['generator_id'])) {
            $this->layout->addBreadcrumb(n2_('Generator'), 'ssi_16 ssi_16--cog', $this->getUrlGeneratorEdit($this->slide['generator_id'], $this->groupID));
        }


        $slideManager = new BlockSlideManager($this);
        $slideManager->setGroupID($this->groupID);
        $slideManager->setSliderID($this->slider['id']);
        $slideManager->setBreadcrumbOpener(true);

        $this->editorOverlay->setSlideManager($slideManager);


        $this->renderTopBar();

        $blockAddLayer = new BlockAddLayer($this);
        $blockAddLayer->setSliderType($this->frontendSlider->data->get('type'));

        $this->editorOverlay->setBlockAddLayer($blockAddLayer);

        $this->layout->addContent($this->render('Edit'));

        $this->renderLayout();

        setlocale(LC_NUMERIC, $locale);
    }

    protected function renderLayout() {

        $this->layout->render();
    }

    protected function addActiveBreadcrumb() {

        $breadCrumb = $this->layout->addBreadcrumb(n2_('Slides') . '<i class="ssi_16 ssi_16--selectarrow"></i>', 'ssi_16 ssi_16--slides', '#');

        $breadCrumb->addClass('n2_nav_bar__breadcrumb_button_slides');
        $breadCrumb->setIsActive(true);
    }

    private function renderTopBar() {

        $topBar = $this->editorOverlay->getTopBar();

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_button--inactive');
        $buttonSave->addClass('n2_slide_settings_save');
        $topBar->addPrimaryBlock($buttonSave);

        $buttonBack = new BlockButtonBack($this);
        $buttonBack->setUrl($this->getUrlSliderEdit($this->getSliderID(), $this->groupID));
        $buttonBack->addClass('n2_slide_settings_back');
        $topBar->addPrimaryBlock($buttonBack);

        if ($this->slide && $this->slide['generator_id'] > 0) {
            $buttonStaticSave = new BlockButton($this);
            $buttonStaticSave->setLabel(n2_('Static save'));
            $buttonStaticSave->addClass('n2_slide_generator_static_save');
            $topBar->addPrimaryBlock($buttonStaticSave);
        }

        $narrowGroup = new BlockTopBarGroup($this);
        $narrowGroup->setNarrow();

        $buttonRedo = new BlockButtonPlainIcon($this);
        $buttonRedo->addClass('n2_top_bar_button_icon');
        $buttonRedo->addClass('n2_ss_history_action');
        $buttonRedo->addClass('n2_ss_history_action--redo');
        $buttonRedo->setIcon('ssi_24 ssi_24--redo');
        $buttonRedo->addAttribute('data-n2tip', n2_('Redo'));
        $buttonRedo->addAttribute('data-n2tipv', -20);
        $narrowGroup->addBlock($buttonRedo);

        $buttonUndo = new BlockButtonPlainIcon($this);
        $buttonUndo->addClass('n2_top_bar_button_icon');
        $buttonUndo->addClass('n2_ss_history_action');
        $buttonUndo->addClass('n2_ss_history_action--undo');
        $buttonUndo->setIcon('ssi_24 ssi_24--undo');
        $buttonUndo->addAttribute('data-n2tip', n2_('Undo'));
        $buttonUndo->addAttribute('data-n2tipv', -20);
        $narrowGroup->addBlock($buttonUndo);

        $topBar->addPrimaryBlock($narrowGroup);

        $spacer = new BlockButtonSpacer($this);
        $spacer->setIsVisible(true);
        $topBar->addPrimaryBlock($spacer);

        $deviceZoom = new BlockDeviceZoom($this);
        $topBar->addPrimaryBlock($deviceZoom);

        $buttonPreview = new BlockButtonPlainIcon($this);
        $buttonPreview->addAttribute('id', 'n2-ss-preview');
        $buttonPreview->addClass('n2_top_bar_button_icon');
        $buttonPreview->addClass('n2_top_bar_main__preview');
        $buttonPreview->setIcon('ssi_24 ssi_24--preview');
        $buttonPreview->addAttribute('data-n2tip', n2_('Preview'));
        $buttonPreview->setUrl($this->getUrlPreviewIndex($this->getSliderID()));
        $topBar->addPrimaryBlock($buttonPreview);

    }

    public function getModel() {

        return $this->model;
    }

    /**
     * @param int    $groupID
     * @param string $groupTitle
     */
    public function setGroupData($groupID, $groupTitle) {
        $this->groupID    = $groupID;
        $this->groupTitle = $groupTitle;
    }

    public function getSliderID() {
        return $this->slider['id'];
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @param array $slide
     */
    public function setSlide($slide) {
        $this->slide = $slide;
    }

    public function getSlideID() {

        return $this->slide['id'];
    }

    public function getAjaxUrl() {
        if ($this->slide) {
            return $this->createAjaxUrl(array(
                'slides/edit',
                array(
                    'groupID'  => $this->groupID,
                    'sliderid' => $this->getSliderID(),
                    'slideid'  => $this->getSlideID()
                )
            ));
        }

        return $this->createAjaxUrl(array(
            'slides/create',
            array(
                'groupID'  => $this->groupID,
                'sliderid' => $this->getSliderID(),
                'slideid'  => $this->getSlideID()
            )
        ));
    }
}