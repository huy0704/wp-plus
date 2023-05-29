<?php

namespace Nextend\SmartSlider3\Slider\ResponsiveType\FullWidth;

use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveType;

class ResponsiveTypeFullWidth extends AbstractResponsiveType {


    public function getName() {
        return 'fullwidth';
    }

    public function createFrontend($responsive) {

        return new ResponsiveTypeFullWidthFrontend($this, $responsive);
    }

    public function createAdmin() {

        return new ResponsiveTypeFullWidthAdmin($this);
    }
}