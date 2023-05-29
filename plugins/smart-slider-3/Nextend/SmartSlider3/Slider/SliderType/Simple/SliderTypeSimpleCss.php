<?php


namespace Nextend\SmartSlider3\Slider\SliderType\Simple;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeCss;

class SliderTypeSimpleCss extends AbstractSliderTypeCss {

    public function __construct($slider) {
        parent::__construct($slider);

        $params = $this->slider->params;

        $width  = intval($this->context['width']);
        $height = intval($this->context['height']);

        $this->base = array(
            'slideOuterWidth'  => $width,
            'slideOuterHeight' => $height,
            'sliderWidth'      => $width,
            'sliderHeight'     => $height,
            'slideWidth'       => $width,
            'slideHeight'      => $height
        );

        $this->context['backgroundSize']       = $params->getIfEmpty('background-size', 'inherit');
        $this->context['backgroundAttachment'] = $params->get('background-fixed') ? 'fixed' : 'scroll';

        $borderWidth                   = $params->getIfEmpty('border-width', 0);
        $borderColor                   = $params->get('border-color');
        $this->context['borderRadius'] = $params->get('border-radius') . 'px';

        $padding                   = Common::parse($params->get('padding'));
        $this->context['paddingt'] = max(0, $padding[0]) . 'px';
        $this->context['paddingr'] = max(0, $padding[1]) . 'px';
        $this->context['paddingb'] = max(0, $padding[2]) . 'px';
        $this->context['paddingl'] = max(0, $padding[3]) . 'px';

        if ($this->context['canvas']) {
            $width  += 2 * $borderWidth + max(0, $padding[1]) + max(0, $padding[3]);
            $height += 2 * $borderWidth + max(0, $padding[0]) + max(0, $padding[2]);

            $this->context['width']  = $width . "px";
            $this->context['height'] = $height . "px";
        }


        $this->context['border'] = $borderWidth . 'px';

        $rgba                        = Color::hex2rgba($borderColor);
        $this->context['borderrgba'] = 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';

        $width                         = $width - (max(0, $padding[1]) + max(0, $padding[3])) - $borderWidth * 2;
        $height                        = $height - (max(0, $padding[0]) + max(0, $padding[2])) - $borderWidth * 2;
        $this->context['inner1height'] = $height . 'px';

        $this->context['canvaswidth']  = $width . "px";
        $this->context['canvasheight'] = $height . "px";

        $this->initSizes();

        $this->slider->addLess(SliderTypeSimple::getAssetsPath() . '/style.n2less', $this->context);
    }
}