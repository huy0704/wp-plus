<?php

namespace Nextend\SmartSlider3\Slider\ResponsiveType\FullWidth;

use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Slider\ResponsiveType\AbstractResponsiveTypeAdmin;

class ResponsiveTypeFullWidthAdmin extends AbstractResponsiveTypeAdmin {

    protected $ordering = 2;

    public function getLabel() {
        return n2_('Full width');
    }

    public function getIcon() {
        return 'ssi_64 ssi_64--fit';
    }

    public function renderFields($container) {
        $row1 = new FieldsetRow($container, 'responsive-fullwidth-1');

        new Number($row1, 'responsiveSliderHeightMin', n2_('Min height'), 0, array(
            'unit'           => 'px',
            'wide'           => 5,
            'tipLabel'       => n2_('Min height'),
            'tipDescription' => n2_('Prevents the slider from getting smaller than the set value.')
        ));

        new OnOff($row1, 'responsiveForceFull', n2_('Force full width'), 1, array(
            'tipLabel'       => n2_('Force full width'),
            'tipDescription' => n2_('The slider tries to fill the full width of the browser.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1776-fullwidth-layout#force-full-width'
        ));

        new Select($row1, 'responsiveForceFullOverflowX', n2_('Overflow-X'), 'body', array(
            'options'        => array(
                'body' => 'body',
                'html' => 'html',
                'none' => n2_('None')
            ),
            'tipLabel'       => n2_('Overflow-X'),
            'tipDescription' => n2_('Prevents the vertical scrollbar from appear during certain slide background animations.')
        ));


        new Text($row1, 'responsiveForceFullHorizontalSelector', n2_('Adjust slider width to'), 'body', array(
            'tipLabel'       => n2_('Adjust slider width to'),
            'tipDescription' => n2_('You can make the slider fill up a selected parent element instead of the full browser width.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1776-fullwidth-layout#adjust-slider-width-to'
        ));
    }
}