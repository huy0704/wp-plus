<?php


namespace Nextend\SmartSlider3\Platform\WordPress\Integration\Elementor;


use Elementor\Base_Data_Control;
use Elementor\Control_Base;

if (class_exists('\Elementor\Base_Data_Control')) {

    abstract class AbstractControl extends Base_Data_Control {

    }
} else {

    abstract class AbstractControl extends Control_Base {

    }
}

class_exists('\Elementor\Group_Control_Background');