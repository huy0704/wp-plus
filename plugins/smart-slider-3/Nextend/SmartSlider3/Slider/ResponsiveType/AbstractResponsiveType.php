<?php


namespace Nextend\SmartSlider3\Slider\ResponsiveType;


use Nextend\SmartSlider3\Slider\Feature\Responsive;

abstract class AbstractResponsiveType {

    public abstract function getName();

    /**
     * @param Responsive $responsive
     *
     * @return AbstractResponsiveTypeFrontend
     */
    public abstract function createFrontend($responsive);

    /**
     *
     * @return AbstractResponsiveTypeAdmin
     */
    public abstract function createAdmin();

}