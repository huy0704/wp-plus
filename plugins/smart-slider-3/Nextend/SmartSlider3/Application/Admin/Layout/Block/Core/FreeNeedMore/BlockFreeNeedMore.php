<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\FreeNeedMore;


use Nextend\Framework\View\AbstractBlock;

class BlockFreeNeedMore extends AbstractBlock {

    protected $source;

    public function display() {

        $this->renderTemplatePart('FreeNeedMore');
    }

    /**
     * @return string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source) {
        $this->source = $source;
    }
}