<?php

namespace Nextend\SmartSlider3\Renderable\Item\Missing;

use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemMissingFrontend extends AbstractItemFrontend {

    public function render() {
        return '';
    }

    protected function renderAdminTemplate() {
        return '<div>' . sprintf(n2_('Missing layer type: %s'), $this->data->get('type')) . '</div>';
    }
}