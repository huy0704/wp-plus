<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\LayerWindowFocus;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\Select\Gradient;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\HiddenText;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowLabelFields;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowStyleMode;
use Nextend\SmartSlider3\Form\Element\Radio\FlexAlign;
use Nextend\SmartSlider3\Form\Element\Radio\HorizontalAlign;
use Nextend\SmartSlider3\Form\Element\Radio\InnerAlign;

class LayerWindowSettingsContent extends AbstractLayerWindowSettings {

    public function getName() {
        return 'content';
    }

    protected function extendContent() {


        $general = new FieldsetLayerWindowLabelFields($this->contentContainer, 'fields-content-general', n2_('General'));

        new Hidden($general, 'content-opened', 1);

        new InnerAlign($general, 'content-inneralign', n2_('Inner align'), 'inherit', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Inner align'),
            'tipDescription' => n2_('Positions the layers inside horizontally.')
        ));
        new FlexAlign($general, 'content-verticalalign', n2_('Vertical align'), 'center', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Vertical align'),
            'tipDescription' => n2_('Positions the layers inside vertically.')
        ));
    }

    protected function extendStyle() {

        $this->backgroundImage($this->styleContainer);
        $this->background($this->styleContainer);
        $this->spacing($this->styleContainer);
        $this->position($this->styleContainer);
        $this->size($this->styleContainer);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function backgroundImage($container) {

        $backgroundImage = new FieldsetLayerWindowLabelFields($container, 'fields-content-background-image', n2_('Background image'));

        $fieldImage = new FieldImage($backgroundImage, 'content-background-image', n2_('Background image'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'layercontent-background-focus'
            )
        ));

        $fieldFocusX = new HiddenText($backgroundImage, 'content-background-focus-x', 50);
        $fieldFocusY = new HiddenText($backgroundImage, 'content-background-focus-y', 50);

        $focusField = new LayerWindowFocus($backgroundImage, 'content-background-focus', n2_('Focus'), array(
            'tipLabel'       => n2_('Focus'),
            'tipDescription' => n2_('You can set the starting position of a background image. This makes sure that the selected part will always remain visible, so you should pick the most important part.')
        ));

        $focusField->setFields($fieldImage, $fieldFocusX, $fieldFocusY);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function background($container) {

        $background = new FieldsetLayerWindowStyleMode($container, 'fields-content-background', n2_('Content background'), array(
            ''       => 'Normal',
            '-hover' => 'Hover'
        ));

        new Color($background, 'content-background-color', n2_('Background color'), 'ffffff00', array(
            'alpha' => true
        ));

        new Gradient($background, 'content-background-gradient', n2_('Gradient'), 'off', array(
            'relatedFields' => array(
                'layercontent-background-color-end'
            )
        ));

        new Color($background, 'content-background-color-end', n2_('Color end'), 'ffffff00', array(
            'alpha' => true
        ));

    }

    /**
     * @param ContainerInterface $container
     */
    protected function spacing($container) {

        $spacing = new FieldsetLayerWindowLabelFields($container, 'fields-content-spacing', n2_('Spacing'));

        $padding = new MarginPadding($spacing, 'content-padding', n2_('Padding'), '5|*|5|*|5|*|5', array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        $padding->setUnit('px');

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'content-padding-' . $i, false, '', array(
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

    /**
     * @param ContainerInterface $container
     */
    protected function position($container) {

        $position = new FieldsetLayerWindowLabelFields($container, 'fields-content-position', n2_('Position'));

        new HorizontalAlign($position, 'content-selfalign', n2_('Align'), 'center', array(
            'inherit'       => true,
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function size($container) {

        $size = new FieldsetLayerWindowLabelFields($container, 'fields-content-size', n2_('Size'));

        new Number($size, 'content-maxwidth', n2_('Max width'), 0, array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            ),
            'unit'          => 'px',
            'wide'          => 5
        ));
    }
}