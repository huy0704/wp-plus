<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\Container\LayerWindow\ContainerDesign;
use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Form\Element\Decoration;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\MixedField\Border;
use Nextend\Framework\Form\Element\MixedField\BoxShadow;
use Nextend\Framework\Form\Element\MixedField\FontSize;
use Nextend\Framework\Form\Element\MixedField\TextShadow;
use Nextend\Framework\Form\Element\Radio\TextAlign;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\FontWeight;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\Family;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Element\Text\TextAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Unit;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetDesign;

class LayerWindowSettingsItemCommon extends AbstractLayerWindowSettings {

    public function getName() {
        return 'item';
    }

    protected function extendStyle() {

        $designContainer = new ContainerDesign($this->styleContainer, 'layer_window_design');

        $this->font($designContainer);
        $this->style($designContainer);
    }

    /**
     * @param ContainerDesign $container
     */
    protected function font($container) {

        $font = new FieldsetDesign($container, 'basiccss-font', n2_('Typography'));

        new Family($font, '-font-family', n2_('Family'), 'Arial, Helvetica', array(
            'style'          => 'width:168px;',
            'tipLabel'       => n2_('Family'),
            'tipDescription' => n2_('You can select a font family from the preset, or type your custom family.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1828-using-your-own-fonts',
        ));
        new Color($font, '-font-color', n2_('Color'), '000000FF', array(
            'alpha' => true
        ));

        new FontSize($font, '-font-size', n2_('Size'), '14|*|px', array(
            'tipLabel'       => n2_('Size'),
            'tipDescription' => n2_('Need to change the font size device specifically? Use the Text scale option.')
        ));

        new FontWeight($font, '-font-weight', n2_('Font weight'), '');

        new TextAutoComplete($font, '-font-lineheight', n2_('Line height'), '18px', array(
            'values' => array(
                'normal',
                '1',
                '1.2',
                '1.5',
                '1.8',
                '2'
            ),
            'style'  => 'width:50px;'
        ));

        new TextAlign($font, '-font-textalign', n2_('Text align'), 'inherit');

        new Decoration($font, '-font-decoration', n2_('Decoration'));

        new Button\ButtonMoreLess($font, '-font-more', '', array(
            'relatedFields' => array(
                'layer-font-letterspacing',
                'layer-font-wordspacing',
                'layer-font-texttransform',
                'layer-font-tshadow',
                'layer-font-extracss'
            )
        ));

        new TextAutoComplete($font, '-font-letterspacing', n2_('Letter spacing'), 'normal', array(
            'values' => array(
                'normal',
                '1px',
                '2px',
                '5px',
                '10px',
                '15px'
            ),
            'style'  => 'width:73px;'
        ));

        new TextAutoComplete($font, '-font-wordspacing', n2_('Word spacing'), 'normal', array(
            'values' => array(
                'normal',
                '2px',
                '5px',
                '10px',
                '15px'
            ),
            'style'  => 'width:72px;'
        ));

        new Select($font, '-font-texttransform', n2_('Transform'), 'none', array(
            'options' => array(
                'none'       => n2_('None'),
                'capitalize' => n2_('Capitalize'),
                'uppercase'  => n2_('Uppercase'),
                'lowercase'  => n2_('Lowercase')
            )
        ));

        new TextShadow($font, '-font-tshadow', n2_('Text shadow'), '0|*|0|*|1|*|000000FF');

        new Textarea($font, '-font-extracss', 'CSS', '', array(
            'width'  => 314,
            'height' => 80
        ));
    }


    /**
     * @param ContainerDesign $container
     */
    protected function style($container) {

        $backgroundFieldset = new FieldsetDesign($container, 'basiccss-style', n2_('Background'));

        new Color($backgroundFieldset, '-style-backgroundcolor', n2_('Background color'), '000000FF', array(
            'alpha' => true
        ));

        new NumberSlider($backgroundFieldset, '-style-opacity', n2_('Opacity'), '100', array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3
        ));

        new Button\ButtonMoreLess($backgroundFieldset, '-style-more', '', array(
            'relatedFields' => array(
                'layer-style-extracss',
                'layer-style-boxshadow'
            )
        ));

        new BoxShadow($backgroundFieldset, '-style-boxshadow', n2_('Box shadow'), '0|*|0|*|0|*|0|*|000000ff');

        new Textarea($backgroundFieldset, '-style-extracss', 'CSS', '', array(
            'width'  => 314,
            'height' => 80
        ));


        $borderFieldset = new FieldsetDesign($container, 'basiccss-style-border', n2_('Border'));
        $borderFieldset->setParentDesign('fieldset-layer-window-basiccss-style');
        $borderFieldset->addAttribute('data-singular', 'style-border');

        new Border($borderFieldset, '-style-border', n2_('Border'), '0|*|solid|*|000000ff');

        new NumberAutoComplete($borderFieldset, '-style-borderradius', n2_('Border radius'), '0', array(
            'min'    => 0,
            'values' => array(
                0,
                3,
                5,
                10,
                99
            ),
            'unit'   => 'px',
            'wide'   => 3
        ));


        $spacingFieldset = new FieldsetDesign($container, 'basiccss-style-spacing', n2_('Spacing'));
        $spacingFieldset->setParentDesign('fieldset-layer-window-basiccss-style');
        $spacingFieldset->addAttribute('data-singular', 'style-spacing');

        $padding = new MarginPadding($spacingFieldset, '-style-padding', n2_('Padding'), '0|*|0|*|0|*|0|*|px');
        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'padding-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'style'  => 'width: 22px;'
            ));
        }

        new Unit($padding, 'padding-5', '', '', array(
            'units' => array(
                'px',
                'em',
                '%'
            )
        ));
    }
}