<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain;


use Nextend\Framework\View\AbstractBlock;

class BlockTopBarMain extends AbstractBlock {

    private static $idCounter = 1;

    protected $id = 0;

    /**
     * @var AbstractBlock[]
     */
    protected $primaryBlocks = array();

    /**
     * @var AbstractBlock[]
     */
    protected $secondaryBlocks = array();

    protected $content = '';

    protected function init() {
        $this->id = self::$idCounter++;
        parent::init();
    }

    public function display() {

        $this->renderTemplatePart('TopBarMain');
    }

    public function addPrimaryBlock($block) {
        $this->primaryBlocks[] = $block;
    }

    public function displayPrimary() {

        foreach ($this->primaryBlocks as $block) {
            $block->display();
        }
    }

    public function addSecondaryBlock($block) {
        $this->secondaryBlocks[] = $block;
    }

    public function displaySecondary() {

        foreach ($this->secondaryBlocks as $block) {
            $block->display();
        }
    }

    public function getID() {
        return 'n2_top_bar_main_' . $this->id;
    }
}