<?php


namespace Nextend\SmartSlider3\Renderable\Placement;


class PlacementDefault extends AbstractPlacement {

    public function attributes(&$attributes) {

        $attributes['data-pm'] = 'default';
    }
}