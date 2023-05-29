<?php

namespace Nextend\SmartSlider3\Slider\ResponsiveType\FullWidth;

use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveTypeFrontend;

class ResponsiveTypeFullWidthFrontend extends AbstractResponsiveTypeFrontend {


    public function parse($params, $responsive, $features) {
        $features->align->align = 'normal';

        $responsive->minimumHeight = intval($params->get('responsiveSliderHeightMin', 0));

        $responsive->forceFull = intval($params->get('responsiveForceFull', 1));

        $responsive->forceFullOverflowX = $params->get('responsiveForceFullOverflowX', 'body');

        $responsive->forceFullHorizontalSelector = $params->get('responsiveForceFullHorizontalSelector', 'body');
    }
}