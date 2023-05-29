<?php

namespace Nextend\SmartSlider3\Slider\SliderType\Block;

use Nextend\Framework\Parser\Color;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeCss;

class SliderTypeBlockCss extends AbstractSliderTypeCss {

    public function __construct($slider) {
        parent::__construct($slider);
        $params = $this->slider->params;

        $width  = intval($this->context['width']);
        $height = intval($this->context['height']);

        $borderWidth                 = $params->getIfEmpty('border-width', 0);
        $borderColor                 = $params->get('border-color');
        $this->context['border']     = $borderWidth . 'px';
        $rgba                        = Color::hex2rgba($borderColor);
        $this->context['borderrgba'] = 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';

        $this->context['borderRadius'] = $params->get('border-radius') . 'px';

        $this->context['backgroundSize']       = $params->getIfEmpty('background-size', 'inherit');
        $this->context['backgroundAttachment'] = $params->get('background-fixed') ? 'fixed' : 'scroll';

        $this->context['canvaswidth']  = $width . "px";
        $this->context['canvasheight'] = $height . "px";

        $this->base = array(
            'slideOuterWidth'  => $width,
            'slideOuterHeight' => $height,
            'sliderWidth'      => $width,
            'sliderHeight'     => $height,
            'slideWidth'       => $width,
            'slideHeight'      => $height
        );

        $this->initSizes();

        $this->slider->addLess(SliderTypeBlock::getAssetsPath() . '/style.n2less', $this->context);
    }
}