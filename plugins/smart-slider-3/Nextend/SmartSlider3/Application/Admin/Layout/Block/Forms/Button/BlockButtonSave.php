<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonSave extends BlockButton {

    protected function init() {
        parent::init();

        $this->setLabel(n2_('Save'));
        $this->setBig();
        $this->setGreen();
    }
}