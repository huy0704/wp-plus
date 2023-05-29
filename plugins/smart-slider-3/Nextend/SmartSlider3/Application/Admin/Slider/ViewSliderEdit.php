<?php


namespace Nextend\SmartSlider3\Application\Admin\Slider;


use Nextend\Framework\Acl\Acl;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\FormManager\FormManagerSlider;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Banner\BlockBannerActivate;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenu;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenuItem;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\BlockSlideManager;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelLicense;

class ViewSliderEdit extends AbstractView {

    use TraitAdminUrl;

    protected $groupID = 0;

    protected $groupTitle = '';

    protected $slider;

    /**
     * @var BlockHeader
     */
    protected $blockHeader;

    /**
     * @var FormManagerSlider
     */
    protected $formManager;

    /**
     * @param int    $groupID
     * @param string $groupTitle
     */
    public function setGroupData($groupID, $groupTitle) {
        $this->groupID    = $groupID;
        $this->groupTitle = $groupTitle;
    }

    /**
     * @param mixed $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    public function display() {
        $this->formManager = new FormManagerSlider($this, $this->slider);

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));


        $slideManager = new BlockSlideManager($this);
        $slideManager->setGroupID($this->groupID);
        $slideManager->setSliderID($this->slider['id']);

        $subNavigationHTML = '';

        $subNavigationHTML .= $slideManager->toHTML();

        $this->layout->setSubNavigation($subNavigationHTML);


        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_slider_settings_save');
        $buttonSave->addClass('n2_button--inactive');
        $topBar->addPrimaryBlock($buttonSave);


        $buttonBack = new BlockButtonBack($this);
        if ($this->groupID != 0) {
            $buttonBack->setUrl($this->getUrlSliderEdit($this->groupID));
        } else {
            $buttonBack->setUrl($this->getUrlDashboard());
        }
        $buttonBack->addClass('n2_slider_settings_back');
        $topBar->addPrimaryBlock($buttonBack);

        $buttonPreview = new BlockButtonPlainIcon($this);
        $buttonPreview->addClass('n2_top_bar_button_icon');
        $buttonPreview->addClass('n2_top_bar_main__preview');
        $buttonPreview->setIcon('ssi_24 ssi_24--preview');
        $buttonPreview->addAttribute('data-n2tip', n2_('Preview'));
        $buttonPreview->setUrl($this->getUrlPreviewIndex($this->slider['id']));
        $topBar->addPrimaryBlock($buttonPreview);


        $this->layout->setTopBar($topBar->toHTML());

        $this->displayHeader();

        $this->layout->addContent($this->render('Edit'));

        $this->layout->render();
    }

    protected function displayHeader() {


        $this->blockHeader = new BlockHeader($this);
        $this->blockHeader->setHeading($this->slider['title']);
        $this->blockHeader->setHeadingAfter('ID: ' . $this->slider['id']);

        $this->formManager->addTabsToHeader($this->blockHeader);

        $this->addHeaderActions();

        $this->layout->addContentBlock($this->blockHeader);
    }

    public function getSlider() {

        return $this->slider;
    }

    private function addHeaderActions() {

        $accessEdit   = Acl::canDo('smartslider_edit', $this);
        $accessDelete = Acl::canDo('smartslider_delete', $this);

        if ($accessEdit || $accessDelete) {

            $sliderid = $this->slider['id'];

            $actionsMenu = new BlockFloatingMenu($this);

            $actions = new BlockButton($this);
            $actions->setBig();
            $actions->setLabel(n2_('Actions'));
            $actions->setIcon('ssi_16 ssi_16--buttonarrow');
            $actionsMenu->setButton($actions);


            if ($accessEdit) {

                $item = new BlockFloatingMenuItem($this);
                $item->setLabel(n2_('Change slider type'));
                $item->setIcon('ssi_16 ssi_16--arrowright');
                $item->addClass('n2_slider_action__change_slider_type');
                $actionsMenu->addMenuItem($item);

                $item = new BlockFloatingMenuItem($this);
                $item->setLabel(n2_('Clear cache'));
                $item->setIcon('ssi_16 ssi_16--reset');
                $item->setUrl($this->getUrlSliderClearCache($sliderid));
                $actionsMenu->addMenuItem($item);

                $item = new BlockFloatingMenuItem($this);
                $item->setLabel(sprintf(n2_('Export %1$s as HTML'), n2_('Slider')));
                $item->setIcon('ssi_16 ssi_16--download');
                $item->setUrl($this->getUrlSliderExportHtml($sliderid));
                $actionsMenu->addMenuItem($item);

                $item = new BlockFloatingMenuItem($this);
                $item->setLabel(n2_('Export'));
                $item->setIcon('ssi_16 ssi_16--download');
                $item->setUrl($this->getUrlSliderExport($sliderid));
                $actionsMenu->addMenuItem($item);
            


                $item = new BlockFloatingMenuItem($this);
                $item->setLabel(n2_('Duplicate slider'));
                $item->setIcon('ssi_16 ssi_16--duplicate');
                $item->setUrl($this->getUrlSliderDuplicate($sliderid, $this->groupID));
                $actionsMenu->addMenuItem($item);
            }

            if ($accessDelete) {

                $item = new BlockFloatingMenuItem($this);
                $item->setRed();
                $item->setLabel(n2_('Move to trash'));
                $item->setIcon('ssi_16 ssi_16--delete');
                $item->setUrl($this->getUrlSliderMoveToTrash($sliderid, $this->groupID));
                $actionsMenu->addMenuItem($item);

            }

            $this->blockHeader->addAction($actionsMenu->toHTML());
        }
    }

    public function renderForm() {

        $this->formManager->render();
    }

}