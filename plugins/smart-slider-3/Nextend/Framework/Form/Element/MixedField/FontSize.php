<?php


namespace Nextend\Framework\Form\Element\MixedField;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Element\Unit;

class FontSize extends MixedField {

    protected $rowClass = 'n2_field_mixed_font_size ';

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        new NumberSlider($this, $this->name . '-1', false, '', array(
            'min'       => 1,
            'max'       => 10000,
            'sliderMax' => 100,
            'units'     => array(
                'pxMin'       => 1,
                'pxMax'       => 10000,
                'pxSliderMax' => 100,
                '%Min'        => 1,
                '%Max'        => 10000,
                '%SliderMax'  => 600
            ),
            'style'     => 'width: 22px;'
        ));
        new Unit($this, $this->name . '-2', false, '', array(
            'units' => array(
                'px' => 'px',
                '%'  => '%'
            )
        ));
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {

        $elementHtml = $element->render();

        return $elementHtml[1];
    }

    protected function decorate($html) {

        return '<div class="n2_field_mixed_font_size__container" style="' . $this->style . '">' . $html . '</div>';
    }
}