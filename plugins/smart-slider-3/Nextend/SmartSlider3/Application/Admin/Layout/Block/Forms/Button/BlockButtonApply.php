<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonApply extends BlockButton {

    protected function init() {
        parent::init();

        $this->setLabel(n2_('Apply'));
        $this->setBig();
        $this->setGreen();
    }
}