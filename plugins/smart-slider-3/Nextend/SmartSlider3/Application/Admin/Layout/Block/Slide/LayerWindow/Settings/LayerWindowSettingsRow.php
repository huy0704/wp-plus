<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\LayerWindowFocus;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\MixedField\BoxShadow;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Gradient;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\HiddenText;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowLabelFields;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowStyleMode;
use Nextend\SmartSlider3\Form\Element\Columns;
use Nextend\SmartSlider3\Form\Element\Radio\InnerAlign;

class LayerWindowSettingsRow extends AbstractLayerWindowSettings {

    public function getName() {
        return 'row';
    }

    protected function extendContent() {

        $structure = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-row-structure', n2_('Columns'));

        new Columns($structure, 'row-columns', '1');

        new Hidden($structure, 'row-opened', 1);


        $rowGeneral = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-row-general', n2_('General'));

        new InnerAlign($rowGeneral, 'row-inneralign', n2_('Inner align'), 'inherit', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Inner align'),
            'tipDescription' => n2_('Positions the layers inside horizontally.')
        ));

        new NumberSlider($rowGeneral, 'row-gutter', n2_('Gutter'), '', array(
            'min'            => 0,
            'max'            => 300,
            'sliderMax'      => 160,
            'unit'           => 'px',
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'style'          => 'width: 22px;',
            'tipLabel'       => n2_('Gutter'),
            'tipDescription' => n2_('Creates space between the columns')
        ));

        new NumberSlider($rowGeneral, 'row-wrap-after', n2_('Wrap after'), 0, array(
            'min'            => 0,
            'max'            => 10,
            'style'          => 'width:22px;',
            'unit'           => n2_('Column'),
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Wrap after'),
            'tipDescription' => n2_('Breaks the columns to the given amount of rows.')
        ));

        new OnOff($rowGeneral, 'row-fullwidth', n2_('Full width'), 1, array(
            'relatedFieldsOn' => array(
                'layerrow-wrap-after'
            )
        ));

        new OnOff($rowGeneral, 'row-stretch', n2_('Stretch'), 0, array(
            'tipLabel'       => n2_('Stretch'),
            'tipDescription' => n2_('Makes the row fill the available vertical space')
        ));


        $link = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-row-link', n2_('Link'));

        new Url($link, 'row-href', n2_('Link'), '', array(
            'relatedFields' => array(
                'layerrow-href-target',
                'layerrow-aria-label'
            ),
            'width'         => 248
        ));
        new LinkTarget($link, 'row-href-target', n2_('Target window'));

        new Text($link, 'row-aria-label', n2_('ARIA label'), '', array(
            'style'    => 'width:190px;',
            'tipLabel' => n2_('ARIA label')
        ));

    }

    protected function extendStyle() {

        $this->backgroundImage($this->styleContainer);
        $this->background($this->styleContainer);
        $this->border($this->styleContainer);
        $this->spacing($this->styleContainer);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function backgroundImage($container) {

        $backgroundImage = new FieldsetLayerWindowLabelFields($container, 'fields-row-background-image', n2_('Background image'));
        $fieldImage      = new FieldImage($backgroundImage, 'row-background-image', n2_('Background image'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'layerrow-background-focus'
            )
        ));

        $fieldFocusX = new HiddenText($backgroundImage, 'row-background-focus-x', 50);
        $fieldFocusY = new HiddenText($backgroundImage, 'row-background-focus-y', 50);

        $focusField = new LayerWindowFocus($backgroundImage, 'row-background-focus', n2_('Focus'), array(
            'tipLabel'       => n2_('Focus'),
            'tipDescription' => n2_('You can set the starting position of a background image. This makes sure that the selected part will always remain visible, so you should pick the most important part.')
        ));

        $focusField->setFields($fieldImage, $fieldFocusX, $fieldFocusY);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function background($container) {

        $background = new FieldsetLayerWindowStyleMode($container, 'fields-row-background', n2_('Background'), array(
            ''       => 'Normal',
            '-hover' => 'Hover'
        ));

        new Color($background, 'row-background-color', n2_('Background color'), 'ffffff00', array(
            'alpha' => true
        ));

        new Gradient($background, 'row-background-gradient', n2_('Gradient'), 'off', array(
            'relatedFields' => array(
                'layerrow-background-color-end'
            )
        ));

        new Color($background, 'row-background-color-end', n2_('Color end'), 'ffffff00', array(
            'alpha' => true
        ));

        new BoxShadow($background, 'row-boxshadow', n2_('Box shadow'), '0|*|0|*|0|*|0|*|00000080');
    }

    /**
     * @param ContainerInterface $container
     */
    protected function border($container) {

        $border = new FieldsetLayerWindowStyleMode($container, 'fields-row-border', n2_('Border'), array(
            ''       => 'Normal',
            '-hover' => 'Hover'
        ));


        $borderWidth = new MarginPadding($border, 'row-border-width', n2_('Border'), '0|*|0|*|0|*|0', array(
            'unit'          => 'px',
            'relatedFields' => array(
                'layerrow-border-style',
                'layerrow-border-color'
            )
        ));

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($borderWidth, 'row-border-width-' . $i, false, '', array(
                'values' => array(
                    0,
                    1,
                    2,
                    3,
                    5
                ),
                'wide'   => 3
            ));
        }

        new Select($border, 'row-border-style', n2_('Style'), 'none', array(
            'options' => array(
                'none'   => n2_('None'),
                'solid'  => n2_('Solid'),
                'dashed' => n2_('Dashed'),
                'dotted' => n2_('Dotted'),
            )
        ));

        new Color($border, 'row-border-color', n2_('Color'), 'ffffffff', array(
            'alpha' => true
        ));

        new NumberAutoComplete($border, 'row-border-radius', n2_('Border radius'), 0, array(
            'values' => array(
                0,
                3,
                5,
                10,
                99
            ),
            'style'  => 'width: 22px;',
            'unit'   => 'px'
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function spacing($container) {

        $spacing = new FieldsetLayerWindowLabelFields($container, 'fields-row-spacing', n2_('Spacing'));

        $padding = new MarginPadding($spacing, 'row-padding', n2_('Padding'), '10|*|10|*|10|*|10', array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        $padding->setUnit('px');

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'row-padding-' . $i, false, '', array(
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
    }
}