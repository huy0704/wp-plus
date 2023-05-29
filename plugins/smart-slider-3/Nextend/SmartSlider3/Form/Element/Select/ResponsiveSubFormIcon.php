<?php

namespace Nextend\SmartSlider3\Form\Element\Select;

use Nextend\Framework\Form\Element\Select\SubFormIcon;
use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveTypeAdmin;
use Nextend\SmartSlider3\Slider\ResponsiveType\ResponsiveTypeFactory;

class ResponsiveSubFormIcon extends SubFormIcon {

    /** @var AbstractResponsiveTypeAdmin[] */
    protected $plugins = array();

    protected function loadOptions() {

        $this->plugins = ResponsiveTypeFactory::getAdminTypes();

        foreach ($this->plugins as $name => $type) {
            $this->options[$name] = array(
                'label' => $type->getLabel(),
                'icon'  => $type->getIcon()
            );
        }
    }
}