<?php

namespace Nextend\SmartSlider3\Slider\ResponsiveType\Auto;

use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveType;

class ResponsiveTypeAuto extends AbstractResponsiveType {


    public function getName() {
        return 'auto';
    }

    public function createFrontend($responsive) {

        return new ResponsiveTypeAutoFrontend($this, $responsive);
    }

    public function createAdmin() {

        return new ResponsiveTypeAutoAdmin($this);
    }


}