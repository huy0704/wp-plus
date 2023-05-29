<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class BlockButtonPlainIcon extends AbstractButton {

    protected $baseClass = 'n2_button_plain_icon';

    protected $icon = '';

    protected function getContent() {

        return '<i class="' . $this->icon . '"></i>';
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }
}