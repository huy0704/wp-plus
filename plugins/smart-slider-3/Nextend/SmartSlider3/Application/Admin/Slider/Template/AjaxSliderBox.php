<?php

namespace Nextend\SmartSlider3\Application\Admin\Slider;


use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderBox\BlockSliderBox;

/**
 * @var $this ViewAjaxSliderBox
 */

$blockSliderBox = new BlockSliderBox($this);
$blockSliderBox->setSlider($this->getSlider());
$blockSliderBox->display();

