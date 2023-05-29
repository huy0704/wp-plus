<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonBack extends BlockButton {

    protected function init() {
        parent::init();

        $this->setLabel(n2_('Back'));
        $this->setBig();
        $this->setGreyDark();
    }
}