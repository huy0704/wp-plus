<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Form\Element\Devices;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowLabelFields;
use Nextend\SmartSlider3\Form\Element\Radio\HorizontalAlign;
use Nextend\SmartSlider3\Form\Element\Radio\VerticalAlign;
use Nextend\SmartSlider3Pro\Form\Element\CanvasLayerParentPicker;

class LayerWindowSettingsCommon extends AbstractLayerWindowSettings {

    public function getName() {
        return 'common';
    }

    protected function extendStyle() {

        $this->responsive($this->styleContainer);

        $this->effect($this->styleContainer);

        $this->normalPosition($this->styleContainer);

        $this->normalSize($this->styleContainer);

        $this->absolutePosition($this->styleContainer);

        $this->absoluteSize($this->styleContainer);

        $this->advanced($this->styleContainer);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function normalPosition($container) {

        $position = new FieldsetLayerWindowLabelFields($container, 'fields-common-placement-content-position', n2_('Position'), array(
            'attributes' => array(
                'data-placement' => 'normal'
            )
        ));

        new Select($position, 'position-default', n2_('Position'), 'default', array(
            'options'        => array(
                'default'  => n2_('Default'),
                'absolute' => n2_('Absolute')
            ),
            'tipLabel'       => n2_('Position'),
            'tipDescription' => n2_('The editing mode the layer is positioned in.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1916-slide-editing-in-smart-slider-3'
        ));

        new HorizontalAlign($position, 'normal-selfalign', n2_('Align'), 'inherit', array(
            'inherit'        => true,
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Align'),
            'tipDescription' => n2_('Positions the layer horizontally within its parent.')
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function normalSize($container) {

        $size = new FieldsetLayerWindowLabelFields($container, 'fields-common-placement-content-size', n2_('Size'), array(
            'attributes' => array(
                'data-placement' => 'normal'
            )
        ));

        new Number($size, 'normal-maxwidth', n2_('Max width'), 0, array(
            'wide'          => 4,
            'unit'          => 'px',
            'min'           => 0,
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));

        new Number($size, 'normal-height', n2_('Height'), 0, array(
            'wide'           => 4,
            'unit'           => 'px',
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Height'),
            'tipDescription' => n2_('You can set a fix height for your layer.')
        ));

        $margin = new MarginPadding($size, 'normal-margin', n2_('Margin'), '0|*|0|*|0|*|0', array(
            'rowAttributes'  => array(
                'data-devicespecific' => ''
            ),
            'tipLabel'       => n2_('Margin'),
            'tipDescription' => n2_('With margins you can create distance between your layers.')
        )); // spacing

        $margin->setUnit('px');

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($margin, 'normal-margin-' . $i, false, '', array(
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
    protected function absolutePosition($container) {

        $position = new FieldsetLayerWindowLabelFields($container, 'fields-common-placement-absolute-position', n2_('Position'), array(
            'attributes' => array(
                'data-placement' => 'absolute'
            )
        ));

        new Hidden($position, 'adaptive-font', 1);

        new Select($position, 'position-absolute', n2_('Position'), 'absolute', array(
            'options' => array(
                'default'  => n2_('Default'),
                'absolute' => n2_('Absolute')
            )
        ));

        new HorizontalAlign($position, 'align', n2_('Align'), 'left', array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));

        new VerticalAlign($position, 'valign', n2_('Vertical align'), 'top', array(
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));

        $row2 = new Grouping($position, 'absolute-position-row2', false);

        new Number($row2, 'left', n2_('Left'), '', array(
            'unit'          => 'px',
            'wide'          => 4,
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        new Number($row2, 'top', n2_('Top'), '', array(
            'unit'          => 'px',
            'wide'          => 4,
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        new OnOff($row2, 'responsive-position', n2_('Responsive'), 1);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function absoluteSize($container) {

        $size = new FieldsetLayerWindowLabelFields($container, 'fields-common-placement-absolute-size', n2_('Size'), array(
            'attributes' => array(
                'data-placement' => 'absolute'
            )
        ));
        new Text($size, 'width', n2_('Width'), '', array(
            'unit'          => 'px',
            'style'         => 'width:32px;',
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        new Text($size, 'height', n2_('Height'), '', array(
            'unit'          => 'px',
            'style'         => 'width:32px;',
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));
        new OnOff($size, 'responsive-size', n2_('Responsive'), 1);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function responsive($container) {

        $responsive = new FieldsetLayerWindowLabelFields($container, 'fields-common-responsive', n2_('Responsive'));

        new Text($responsive, 'generator-visible', n2_('Hide when variable empty'), '', array(
            'rowAttributes' => array(
                'data-generator-related' => '1'
            ),
            'style'         => 'width:280px;'
        ));

        new Devices($responsive, 'show', n2_('Hide on'));

        new NumberSlider($responsive, 'font-size', n2_('Text scale'), 100, array(
            'min'           => 10,
            'max'           => 200,
            'step'          => 10,
            'unit'          => '%',
            'wide'          => 3,
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));

        new Button($responsive, '-clear-device-specific-changes', n2_('Device specific settings'), n2_('Clear'), array(
            'tipLabel'       => n2_('Clear device specific settings'),
            'tipDescription' => n2_('Erases all device specific changes you made on the current device.'),
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function effect($container) {

        $effect = new FieldsetLayerWindowLabelFields($container, 'fields-common-effect', n2_('Effect'));

        new Select($effect, 'crop', n2_('Crop'), 'visible', array(
            'options'        => array(
                'visible' => n2_('Off'),
                'hidden'  => n2_('On'),
                'auto'    => n2_('Scroll'),
                'mask'    => n2_('Mask')
            ),
            'tipLabel'       => n2_('Crop'),
            'tipDescription' => n2_('If your content is larger than the layer, you can crop it to fit.')
        ));

        new Number($effect, 'rotation', n2_('Rotation'), 0, array(
            'wide' => 3,
            'unit' => '°'
        ));
    }

    /**
     * @param ContainerInterface $container
     */
    protected function advanced($container) {

        $advanced = new FieldsetLayerWindowLabelFields($container, 'fields-common-advanced', n2_('Advanced'));

        new Number($advanced, 'zindex', 'Z Index', 2, array(
            'wide' => 4
        ));

        new Text($advanced, 'class', n2_('CSS Class'), '', array(
            'style'          => 'width:220px;',
            'tipLabel'       => n2_('CSS Class'),
            'tipDescription' => n2_('You can add a custom CSS class on the layer container.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1812-layer-style#css-class',
        ));

        new Hidden($advanced, 'id');

        new Hidden($advanced, 'uniqueclass');
    }
}