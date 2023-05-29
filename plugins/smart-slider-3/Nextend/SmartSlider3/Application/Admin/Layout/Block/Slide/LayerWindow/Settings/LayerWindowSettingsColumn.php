<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\LayerWindowFocus;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\MixedField\BoxShadow;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Gradient;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\HiddenText;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowLabelFields;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowStyleMode;
use Nextend\SmartSlider3\Form\Element\Radio\FlexAlign;
use Nextend\SmartSlider3\Form\Element\Radio\InnerAlign;

class LayerWindowSettingsColumn extends AbstractLayerWindowSettings {

    public function getName() {
        return 'column';
    }

    protected function extendContent() {

        $general = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-col-general', n2_('General'));

        new Hidden($general, 'col-order', '0');
        new Hidden($general, 'col-opened', 1);
        new Hidden($general, 'col-colwidth', '');

        new InnerAlign($general, 'col-inneralign', n2_('Inner align'), 'inherit', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Inner align'),
            'tipDescription' => n2_('Positions the layers inside horizontally.')
        ));

        new FlexAlign($general, 'col-verticalalign', n2_('Vertical align'), 'center', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Vertical align'),
            'tipDescription' => n2_('Positions the layers inside vertically.')
        ));


        $link = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-col-link', n2_('Link'));

        new Url($link, 'col-href', n2_('Link'), '', array(
            'relatedFields' => array(
                'layercol-href-target',
                'layercol-aria-label'
            ),
            'width'         => 248
        ));
        new LinkTarget($link, 'col-href-target', n2_('Target window'));

        new Text($link, 'col-aria-label', n2_('ARIA label'), '', array(
            'style'    => 'width:190px;',
            'tipLabel' => n2_('ARIA label')
        ));
    }

    protected function extendStyle() {

        $this->backgroundImage($this->styleContainer);
        $this->background($this->styleContainer);
        $this->border($this->styleContainer);
        $this->size($this->styleContainer);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function backgroundImage($container) {

        $backgroundImage = new FieldsetLayerWindowLabelFields($container, 'fields-col-background-image', n2_('Background image'));
        $fieldImage      = new FieldImage($backgroundImage, 'col-background-image', n2_('Background image'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'layercol-background-focus'
            )
        ));

        $fieldFocusX = new HiddenText($backgroundImage, 'col-background-focus-x', 50);
        $fieldFocusY = new HiddenText($backgroundImage, 'col-background-focus-y', 50);

        $focusField = new LayerWindowFocus($backgroundImage, 'col-background-focus', n2_('Focus'), array(
            'tipLabel'       => n2_('Focus'),
            'tipDescription' => n2_('You can set the starting position of a background image. This makes sure that the selected part will always remain visible, so you should pick the most important part.')
        ));

        $focusField->setFields($fieldImage, $fieldFocusX, $fieldFocusY);

    }

    /**
     * @param ContainerInterface $container
     */
    protected function background($container) {

        $background = new FieldsetLayerWindowStyleMode($container, 'fields-col-background', n2_('Background'), array(
            ''       => 'Normal',
            '-hover' => 'Hover'
        ));

        new Color($background, 'col-background-color', n2_('Background color'), 'ffffff00', array(
            'alpha' => true
        ));

        new Gradient($background, 'col-background-gradient', n2_('Gradient'), 'off', array(
            'relatedFields' => array(
                'layercol-background-color-end'
            )
        ));

        new Color($background, 'col-background-color-end', n2_('Color end'), 'ffffff00', array(
            'alpha' => true
        ));

        new BoxShadow($background, 'col-boxshadow', n2_('Box shadow'), '0|*|0|*|0|*|0|*|00000080');

    }

    /**
     * @param ContainerInterface $container
     */
    protected function border($container) {

        $border = new FieldsetLayerWindowStyleMode($container, 'fields-col-border', n2_('Border'), array(
            ''       => 'Normal',
            '-hover' => 'Hover'
        ));

        $borderWidth = new MarginPadding($border, 'col-border-width', n2_('Border'), '0|*|0|*|0|*|0', array(
            'unit'          => 'px',
            'relatedFields' => array(
                'layercol-border-style',
                'layercol-border-color'
            )
        ));

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($borderWidth, 'col-border-width-' . $i, false, '', array(
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

        new Select($border, 'col-border-style', n2_('Style'), 'none', array(
            'options' => array(
                'none'   => n2_('None'),
                'solid'  => n2_('Solid'),
                'dashed' => n2_('Dashed'),
                'dotted' => n2_('Dotted'),
            )
        ));

        new Color($border, 'col-border-color', n2_('Color'), 'ffffffff', array(
            'alpha' => true
        ));

        new NumberAutoComplete($border, 'col-border-radius', n2_('Border radius'), 0, array(
            'values' => array(
                0,
                3,
                5,
                10,
                99
            ),
            'wide'   => 3,
            'unit'   => 'px'
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function size($container) {

        $size = new FieldsetLayerWindowLabelFields($container, 'fields-col-size', n2_('Size'));

        new Number($size, 'col-maxwidth', n2_('Max width'), 0, array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            ),
            'wide'          => 5,
            'unit'          => 'px'
        ));


        $padding = new MarginPadding($size, 'col-padding', n2_('Padding'), '5|*|5|*|5|*|5', array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        $padding->setUnit('px');

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'col-padding-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'wide'   => 3
            ));
        }
    }
}