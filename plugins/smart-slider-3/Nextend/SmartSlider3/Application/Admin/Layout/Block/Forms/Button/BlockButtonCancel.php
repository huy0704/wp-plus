<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonCancel extends BlockButton {

    protected function init() {
        parent::init();

        $this->setLabel(n2_('Cancel'));
        $this->setBig();
        $this->setRed();
    }
}