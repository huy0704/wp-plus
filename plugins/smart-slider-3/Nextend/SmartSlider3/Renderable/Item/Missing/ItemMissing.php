<?php

namespace Nextend\SmartSlider3\Renderable\Item\Missing;

use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemMissing extends AbstractItem {

    public function createFrontend($id, $itemData, $layer) {
        return new ItemMissingFrontend($this, $id, $itemData, $layer);
    }

    public function getTitle() {
        return n2_x('Missing', 'Layer');
    }

    public function getIcon() {
        return '';
    }

    public function getType() {
        return 'missing';
    }

    public function renderFields($container) {
    }

}