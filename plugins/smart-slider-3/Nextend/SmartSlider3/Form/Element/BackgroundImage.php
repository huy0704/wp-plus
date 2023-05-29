<?php

namespace Nextend\SmartSlider3\Form\Element;

use Nextend\Framework\Form\Element\IconTab;

class BackgroundImage extends IconTab {

    protected $relatedAttribute = 'slide-background-type';

    protected function fetchElement() {
        $this->options = array(
            'image' => 'ssi_16 ssi_16--image',
            'color' => 'ssi_16 ssi_16--color'
        );
    

        $this->tooltips = array(
            'image' => n2_('Image'),
            'video' => n2_('Video'),
            'color' => n2_('Color')
        );

        return parent::fetchElement();
    }
}