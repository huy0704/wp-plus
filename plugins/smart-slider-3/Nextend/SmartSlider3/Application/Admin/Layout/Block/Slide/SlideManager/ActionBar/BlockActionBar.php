<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\ActionBar;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenu;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenuItem;

class BlockActionBar extends AbstractBlock {

    /**
     * @var BlockFloatingMenu
     */
    protected $blockBulkActions;

    public function display() {

        $this->renderTemplatePart('ActionBar');
    }

    public function displayBulkActions() {

        $this->blockBulkActions = new BlockFloatingMenu($this);
        $this->blockBulkActions->setRelatedClass('n2_slide_manager__action_bar_bulk_actions');
        $this->blockBulkActions->addClass('n2_slide_manager__action_bar_bulk_actions');
        $this->blockBulkActions->setContentID('n2_slide_manager_bulk_actions');

        $blockButton = new BlockButtonPlain($this);
        $blockButton->setLabel(n2_('Bulk actions'));
        $blockButton->setIcon('ssi_16 ssi_16--selectarrow');
        $blockButton->setSmall();
        $this->blockBulkActions->setButton($blockButton);


        /**
         * Bulk actions
         */
        $class = 'n2_slide_manager__action_bar_bulk_action';

        $this->createAction(n2_('Duplicate'), 'duplicate', $class);
        $this->createAction(n2_('Copy'), 'copy', $class);
        $this->createAction(n2_('Delete'), 'delete', $class)
             ->setRed();
        $this->createAction(n2_('Publish'), 'publish', $class);
        $this->createAction(n2_('Unpublish'), 'unpublish', $class);


        $this->blockBulkActions->addSeparator(array(
            'n2_slide_manager__action_bar_bulk_action'
        ));

        /**
         * Quick selection
         */
        $this->createAction(n2_('Select all'), 'select-all', false, true);
        $this->createAction(n2_('Select none'), 'select-none', false);
        $this->createAction(n2_('Select published'), 'select-published', false, true);
        $this->createAction(n2_('Select unpublished'), 'select-unpublished', false, true);


        $this->blockBulkActions->display();
    }

    /**
     * @param             $label
     * @param             $action
     * @param bool|string $class
     * @param bool        $stayOpen
     *
     * @return BlockFloatingMenuItem
     */
    private function createAction($label, $action, $class = false, $stayOpen = false) {

        $item = new BlockFloatingMenuItem($this);
        $item->setLabel($label);
        $item->addAttribute('data-action', $action);

        if ($class) {
            $item->addClass($class);
        }

        if ($stayOpen) {
            $item->setStayOpen();
        }

        $this->blockBulkActions->addMenuItem($item);

        return $item;
    }
}