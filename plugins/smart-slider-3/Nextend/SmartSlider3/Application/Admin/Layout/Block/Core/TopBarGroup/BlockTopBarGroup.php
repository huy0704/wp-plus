<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarGroup;


use Nextend\Framework\View\AbstractBlock;

class BlockTopBarGroup extends AbstractBlock {

    /**
     * @var AbstractBlock[]
     */
    protected $blocks = array();

    protected $classes = array('n2_top_bar_group');

    public function display() {

        $this->renderTemplatePart('TopBarGroup');
    }

    /**
     * @param AbstractBlock $block
     */
    public function addBlock($block) {
        $this->blocks[] = $block;
    }

    public function displayBlocks() {

        foreach ($this->blocks as $block) {
            $block->display();
        }
    }

    public function setNarrow() {
        $this->classes[] = 'n2_top_bar_group--narrow';
    }

    /**
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }
}