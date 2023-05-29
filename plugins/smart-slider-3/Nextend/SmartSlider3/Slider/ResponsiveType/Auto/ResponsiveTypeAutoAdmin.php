<?php

namespace Nextend\SmartSlider3\Slider\ResponsiveType\Auto;

use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveTypeAdmin;

class ResponsiveTypeAutoAdmin extends AbstractResponsiveTypeAdmin {

    protected $ordering = 1;

    public function getLabel() {
        return n2_('Boxed');
    }

    public function getIcon() {
        return 'ssi_64 ssi_64--auto';
    }

    public function renderFields($container) {

        $row1 = new FieldsetRow($container, 'responsive-auto-1');

        new OnOff($row1, 'responsiveScaleDown', n2_('Downscale'), 1, array(
            'tipLabel'       => n2_('Downscale'),
            'tipDescription' => n2_('Allows the slider to scale down for smaller screens.')
        ));
        new OnOff($row1, 'responsiveScaleUp', n2_('Upscale'), 1, array(
            'tipLabel'       => n2_('Upscale'),
            'tipDescription' => n2_('Allows the slider to scale up for larger screens.')
        ));


        new Number($row1, 'responsiveSliderHeightMin', n2_('Min height'), 0, array(
            'wide'           => 5,
            'unit'           => 'px',
            'tipLabel'       => n2_('Min height'),
            'tipDescription' => n2_('Prevents the slider from getting smaller than the set value.')
        ));

    }
}