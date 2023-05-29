<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Tab;


class TabStyle extends AbstractTab {

    /**
     * @return string
     */
    public function getName() {
        return 'style';
    }

    /**
     * @return string
     */
    public function getLabel() {
        return n2_('Style');
    }

    /**
     * @return string
     */
    public function getIcon() {
        return 'ssi_24 ssi_24--style';
    }
}