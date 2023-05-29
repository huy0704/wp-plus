<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\ActionBar;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenu;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenuItem;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\BlockSliderManager;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockActionBar extends AbstractBlock {

    use TraitAdminUrl;

    /**
     * @var BlockSliderManager
     */
    protected $sliderManager;

    public function display() {

        $this->renderTemplatePart('ActionBar');
    }

    /**
     * @param BlockSliderManager $sliderManager
     */
    public function setSliderManager($sliderManager) {
        $this->sliderManager = $sliderManager;
    }

    public function displayCreateGroup() {
    }

    public function displayTrash() {
        if ($this->sliderManager->getGroupID() == 0) {

            $blockButton = new BlockButtonPlain($this);
            $blockButton->setUrl($this->getUrlTrash());
            $blockButton->setLabel(n2_('View trash'));
            $blockButton->addClass('n2_slider_trash');
            $blockButton->setSmall();
            $blockButton->setIconBefore('ssi_16 ssi_16--delete', 'n2_slider_icon--blue');
            $blockButton->setTabIndex(-1);
            $blockButton->display();
        }
    }

    public function displayOrderBy() {
        if ($this->sliderManager->getGroupID() == 0) {

            $orderBy          = $this->sliderManager->getOrderBy();
            $orderByDirection = $this->sliderManager->getOrderByDirection();

            $blockOrderBy = new BlockFloatingMenu($this);

            $blockButton = new BlockButtonPlain($this);
            $blockButton->setLabel(n2_('Order by'));
            $blockButton->setIcon('ssi_16 ssi_16--selectarrow');
            $blockButton->setIconBefore('ssi_16 ssi_16--order', 'n2_slider_icon--blue');
            $blockButton->addClass('n2_slider_order');
            $blockButton->setSmall();
            $blockOrderBy->setButton($blockButton);

            $manualOrder = new BlockFloatingMenuItem($this);
            $manualOrder->setLabel(n2_('Manual order'));
            $manualOrder->setIsActive($orderBy == 'ordering' && $orderByDirection == 'ASC');
            $manualOrder->addAttribute('data-ordering', 'ordering');
            $manualOrder->addAttribute('data-orderdirection', 'ASC');
            $manualOrder->addClass('n2_floating_menu__item-order');
            $manualOrder->setUrl('#');
            $blockOrderBy->addMenuItem($manualOrder);

            $orderAZ = new BlockFloatingMenuItem($this);
            $orderAZ->setLabel(n2_('A-Z'));
            $orderAZ->setIsActive($orderBy == 'title' && $orderByDirection == 'ASC');
            $orderAZ->addAttribute('data-ordering', 'title');
            $orderAZ->addAttribute('data-orderdirection', 'ASC');
            $orderAZ->addClass('n2_floating_menu__item-order');
            $orderAZ->setUrl('#');
            $blockOrderBy->addMenuItem($orderAZ);

            $orderZA = new BlockFloatingMenuItem($this);
            $orderZA->setLabel(n2_('Z-A'));
            $orderZA->setIsActive($orderBy == 'title' && $orderByDirection == 'DESC');
            $orderZA->addAttribute('data-ordering', 'title');
            $orderZA->addAttribute('data-orderdirection', 'DESC');
            $orderZA->addClass('n2_floating_menu__item-order');
            $orderZA->setUrl('#');
            $blockOrderBy->addMenuItem($orderZA);

            $orderNewest = new BlockFloatingMenuItem($this);
            $orderNewest->setLabel(n2_('Newest first'));
            $orderNewest->setIsActive($orderBy == 'time' && $orderByDirection == 'DESC');
            $orderNewest->addAttribute('data-ordering', 'time');
            $orderNewest->addAttribute('data-orderdirection', 'DESC');
            $orderNewest->addClass('n2_floating_menu__item-order');
            $orderNewest->setUrl('#');
            $blockOrderBy->addMenuItem($orderNewest);

            $orderOldest = new BlockFloatingMenuItem($this);
            $orderOldest->setLabel(n2_('Oldest first'));
            $orderOldest->setIsActive($orderBy == 'time' && $orderByDirection == 'ASC');
            $orderOldest->addAttribute('data-ordering', 'time');
            $orderOldest->addAttribute('data-orderdirection', 'ASC');
            $orderOldest->addClass('n2_floating_menu__item-order');
            $orderOldest->setUrl('#');
            $blockOrderBy->addMenuItem($orderOldest);

            $blockOrderBy->display();
        }
    }

    public function displayBulkActions() {

        $blockBulkActions = new BlockFloatingMenu($this);
        $blockBulkActions->setRelatedClass('n2_slider_manager__action_bar_bulk_actions');
        $blockBulkActions->addClass('n2_slider_manager__action_bar_bulk_actions');
        $blockBulkActions->setContentID('n2_slider_manager_bulk_actions');

        $blockButton = new BlockButtonPlain($this);
        $blockButton->setLabel(n2_('Bulk actions'));
        $blockButton->setSmall();
        $blockButton->setIcon('ssi_16 ssi_16--selectarrow');
        $blockButton->setIconBefore('ssi_16 ssi_16--slides', 'n2_slider_icon--blue');

        $blockBulkActions->setButton($blockButton);

        $duplicate = new BlockFloatingMenuItem($this);
        $duplicate->addClass('n2_slider_manager__action_bar_bulk_action');
        $duplicate->setLabel(n2_('Duplicate'));
        $duplicate->addAttribute('data-action', 'duplicate');
        $blockBulkActions->addMenuItem($duplicate);

        $trash = new BlockFloatingMenuItem($this);
        $trash->setRed();
        $trash->addClass('n2_slider_manager__action_bar_bulk_action');
        $trash->setLabel(n2_('Move to trash'));
        $trash->addAttribute('data-action', 'trash');
        $blockBulkActions->addMenuItem($trash);

        $export = new BlockFloatingMenuItem($this);
        $export->addClass('n2_slider_manager__action_bar_bulk_action');
        $export->setLabel(n2_('Export'));
        $export->addAttribute('data-action', 'export');
        $blockBulkActions->addMenuItem($export);

        $blockBulkActions->addSeparator(array(
            'n2_slider_manager__action_bar_bulk_action'
        ));

        $selectAll = new BlockFloatingMenuItem($this);
        $selectAll->setLabel(n2_('Select all'));
        $selectAll->addAttribute('data-action', 'select-all');
        $selectAll->setStayOpen();
        $blockBulkActions->addMenuItem($selectAll);

        $selectNone = new BlockFloatingMenuItem($this);
        $selectNone->setLabel(n2_('Select none'));
        $selectNone->addAttribute('data-action', 'select-none');
        $blockBulkActions->addMenuItem($selectNone);

        $blockBulkActions->display();
    }

}