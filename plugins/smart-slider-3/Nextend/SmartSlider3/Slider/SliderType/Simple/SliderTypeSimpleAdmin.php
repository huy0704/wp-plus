<?php

namespace Nextend\SmartSlider3\Slider\SliderType\Simple;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerRowGroup;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Easing;
use Nextend\Framework\Form\Element\Select\Skin;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindow;
use Nextend\Framework\Form\Insert\InsertAfter;
use Nextend\Framework\Form\Insert\InsertBefore;
use Nextend\SmartSlider3\BackgroundAnimation\BackgroundAnimationManager;
use Nextend\SmartSlider3\Form\Element\BackgroundAnimation;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeAdmin;
use Nextend\SmartSlider3Pro\Form\Element\PostBackgroundAnimation;
use Nextend\SmartSlider3Pro\PostBackgroundAnimation\PostBackgroundAnimationManager;

class SliderTypeSimpleAdmin extends AbstractSliderTypeAdmin {

    protected $ordering = 1;

    public function getLabel() {
        return n2_('Simple');
    }

    public function getLabelFull() {
        return n2_x('Simple slider', 'Slider type');
    }

    public function getIcon() {
        return 'ssi_64 ssi_64--slider';
    }

    public function prepareForm($form) {

        $tableMainAnimation = new ContainerTable(new InsertBefore($form->getElement('/animations/effects')), 'slider-type-simple-main-animation', n2_('Main animation'));

        $rowMainAnimation = new FieldsetRow($tableMainAnimation, 'slider-type-simple-main-animation-1');

        new Select($rowMainAnimation, 'animation', n2_('Main animation'), 'horizontal', array(
            'options'            => array(
                'no'                  => n2_('No animation'),
                'fade'                => n2_('Fade'),
                'crossfade'           => n2_('Crossfade'),
                'horizontal'          => n2_('Horizontal'),
                'vertical'            => n2_('Vertical'),
                'horizontal-reversed' => n2_('Horizontal - reversed'),
                'vertical-reversed'   => n2_('Vertical - reversed')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'fade',
                        'crossfade',
                        'horizontal',
                        'vertical',
                        'horizontal-reversed',
                        'vertical-reversed'
                    ),
                    'field'  => array(
                        'slideranimation-duration',
                        'slideranimation-delay',
                        'slideranimation-easing'
                    )
                )
            )
        ));


        new NumberAutoComplete($rowMainAnimation, 'animation-duration', n2_('Duration'), 800, array(
            'min'    => 0,
            'values' => array(
                800,
                1500,
                2000
            ),
            'unit'   => 'ms',
            'wide'   => 5
        ));

        $tableBackground = new ContainerTable(new InsertBefore($form->getElement('/animations/effects')), 'slider-type-simple-background', n2_('Background animation'));

        $rowBackgroundAnimation = new FieldsetRow($tableBackground, 'slider-type-simple-background-animation');

        new BackgroundAnimation($rowBackgroundAnimation, 'background-animation', n2_('Background animation'), '', array(
            'relatedFields'  => array(
                'sliderbackground-animation-speed',
                'slideranimation-shifted-background-animation'
            ),
            'tipLabel'       => n2_('Background animation'),
            'tipDescription' => n2_('Background animations only work on the slide background images, which have Fill selected at their Fill mode. They don\'t affect any images if the background parallax is enabled.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1780-simple-slider-type#background-animation',
        ));
        new Hidden($rowBackgroundAnimation, 'background-animation-color', '333333ff');

        new Select($rowBackgroundAnimation, 'background-animation-speed', n2_('Speed'), 'normal', array(
            'options' => array(
                'superSlow10' => n2_('Super slow') . ' 10x',
                'superSlow'   => n2_('Super slow') . ' 3x',
                'slow'        => n2_('Slow') . ' 1.5x',
                'normal'      => n2_('Normal') . ' 1x',
                'fast'        => n2_('Fast') . ' 0.75x',
                'superFast'   => n2_('Super fast') . ' 0.5x'
            )
        ));
        $form->getElement('/animations/effects')
             ->remove();
    
    }

    public function renderSlideFields($container) {

        $dataToFields = array();

        $tableAnimation = new FieldsetLayerWindow($container, 'fields-slide-animation', n2_('Animation'));

        // Background animations are required for simple type. We need to load the lightbox, because it is not working over AJAX slider type change.
        BackgroundAnimationManager::enqueue($container->getForm());

        $rowBackgroundAnimation = new Grouping($tableAnimation, 'slide-settings-animation-background-animation');

        new BackgroundAnimation($rowBackgroundAnimation, 'slide-background-animation', n2_('Background animation'), '', array(
            'relatedFields' => array(
                'layerslide-background-animation-speed'
            )
        ));
        $dataToFields[] = [
            'name' => 'background-animation',
            'id'   => 'layerslide-background-animation',
            'def'  => ''
        ];

        new Hidden($rowBackgroundAnimation, 'slide-background-animation-color', '');
        $dataToFields[] = [
            'name' => 'background-animation-color',
            'id'   => 'layerslide-background-animation-color',
            'def'  => '333333ff'
        ];

        new Select($rowBackgroundAnimation, 'slide-background-animation-speed', n2_('Speed'), '', array(
            'options' => array(
                'default'     => n2_('Default'),
                'superSlow10' => n2_('Super slow') . ' 10x',
                'superSlow'   => n2_('Super slow') . ' 3x',
                'slow'        => n2_('Slow') . ' 1.5x',
                'normal'      => n2_('Normal') . ' 1x',
                'fast'        => n2_('Fast') . ' 0.75x',
                'superFast'   => n2_('Super fast') . ' 0.5x'
            )
        ));
        $dataToFields[] = [
            'name' => 'background-animation-speed',
            'id'   => 'layerslide-background-animation-speed',
            'def'  => 'default'
        ];

        Js::addInline("_N2.r('SectionSlide', function(){ _N2.SectionSlide.addExternalDataToField(" . json_encode($dataToFields) . ");});");
    }

    public function registerSlideAdminProperties($component) {

        $component->createProperty('background-animation', '');
        $component->createProperty('background-animation-color', '333333ff');
        $component->createProperty('background-animation-speed', 'default');
    }
}