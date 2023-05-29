<?php

namespace Nextend\SmartSlider3\Application\Admin\Preview;

use Nextend\Framework\Asset\Js\Js;

/**
 * @var $this ViewPreviewIndex
 */

JS::addGlobalInline('document.documentElement.classList.add("n2_html--application-only");');

Js::addFirstCode("new _N2.SliderPreview();");

?>
<div class="n2_preview">
    <form target="n2_preview__device_screen_inner_frame" action="<?php echo esc_url($this->getUrlPreviewFull($this->getSliderID())); ?>" method="post">
        <input type="hidden" name="sliderData" value="<?php echo esc_attr(json_encode($this->sliderData)); ?>">
        <input type="hidden" name="slidesData" value="<?php echo esc_attr(json_encode($this->slidesData)); ?>">
        <input type="hidden" name="generatorData" value="<?php echo esc_attr(json_encode($this->generatorData)); ?>">
    </form>
    <div class="n2_preview__ruler">
        <div class="n2_preview__ruler_label"></div>
    </div>
    <div class="n2_preview__device_info">
        <div class="n2_preview__device_info_label"><?php n2_e('State'); ?>:&nbsp;</div>
        <div class="n2_preview__device_info_state"><?php n2_e('Desktop'); ?></div>
        <i class="ssi_16 ssi_16--info" data-tip-description="" data-tip-label="<?php n2_e('Reason'); ?>"></i>
    </div>

    <div class="n2_preview__device_screen">
        <div class="n2_preview__device_screen_inner" style="<?php echo esc_attr($this->getWidthCSS()); ?>">
            <iframe name="n2_preview__device_screen_inner_frame"></iframe>
            <div class="n2_preview__frame_overlay"></div>
            <div class="n2_preview__resize_width">
            </div>

            <div class="n2_preview__resize_height">

            </div>
        </div>
    </div>
</div>