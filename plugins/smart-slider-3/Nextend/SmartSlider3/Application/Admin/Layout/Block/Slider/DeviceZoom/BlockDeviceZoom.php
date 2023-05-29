<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\DeviceZoom;


use Nextend\Framework\View\AbstractBlock;

class BlockDeviceZoom extends AbstractBlock {

    public function display() {
        $this->renderTemplatePart('DeviceZoom');
    }
}