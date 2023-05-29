<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderTrash;

/**
 * @var BlockSliderTrash $this
 */

$sliders = $this->getSliders();
?>
<div class="n2_slider_trash">

    <div class="n2_slider_manager__box n2_slider_manager__dummy_slider">
        <i class="n2_slider_manager__dummy_slider_icon ssi_48 ssi_48--delete"></i>
        <div class="n2_slider_manager__dummy_slider_label">
            <?php n2_e('Trash is empty.'); ?>
        </div>
    </div>

    <?php
    foreach ($sliders as $sliderObj) {

        $blockSliderBox = new BlockSliderTrashBox($this);
        $blockSliderBox->setSlider($sliderObj);
        $blockSliderBox->display();
    }
    ?>
</div>
