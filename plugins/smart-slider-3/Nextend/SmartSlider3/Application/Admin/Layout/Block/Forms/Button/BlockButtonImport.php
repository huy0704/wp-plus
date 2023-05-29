<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonImport extends BlockButton {

    protected function init() {
        parent::init();

        $this->setLabel(n2_('Import'));
        $this->setBig();
        $this->setGreen();
    }
}