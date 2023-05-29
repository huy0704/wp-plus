<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerRowGroup;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Breakpoint;
use Nextend\Framework\Form\Element\CheckboxOnOff;
use Nextend\Framework\Form\Element\Group\GroupCheckboxOnOff;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Hidden\HiddenOnOff;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text\HiddenText;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\FormTabbed;
use Nextend\SmartSlider3\Application\Admin\Settings\ViewSettingsGeneral;
use Nextend\SmartSlider3\Form\Element\Select\ResponsiveSubFormIcon;
use Nextend\SmartSlider3\Settings;

class SliderSize extends AbstractSliderTab {


    /**
     * SliderSize constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->size();
        $this->breakpoints();
        $this->layout();
        $this->customSize();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'size';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Size');
    }

    protected function size() {

        $table = new ContainerTable($this->tab, 'size', n2_('Slider size'));


        /**
         * Used for field injection: /size/size/size-1
         */
        $row1 = $table->createRow('size-1');

        new NumberAutoComplete($row1, 'width', n2_('Width'), 900, array(
            'wide'   => 5,
            'min'    => 10,
            'values' => array(
                1920,
                1400,
                1000,
                800,
                600,
                400
            ),
            'unit'   => 'px'
        ));
        new NumberAutoComplete($row1, 'height', n2_('Height'), 500, array(
            'wide'   => 5,
            'min'    => 10,
            'values' => array(
                800,
                600,
                500,
                400,
                300,
                200
            ),
            'unit'   => 'px'
        ));

        /**
         * Used for field removal: /size/size/size-2
         */
        $row2 = $table->createRow('size-2');

        new OnOff($row2, 'responsiveLimitSlideWidth', n2_('Limit slide width'), 1, array(
            'relatedFieldsOn' => array(
                'slidergrouping-responsive-slide-width'
            ),
            'tipLabel'        => n2_('Limit slide width'),
            'tipDescription'  => n2_('Limits the width of the slide and prevents the slider from getting too tall.'),
            'tipLink'         => 'https://smartslider.helpscoutdocs.com/article/1774-slider-settings-size#limit-slide-width'
        ));

        $slideMaxWidthGroup = new Grouping($row2, 'grouping-responsive-slide-width');

        new OnOff($slideMaxWidthGroup, 'responsiveSlideWidth', n2_('Desktop'), 0, array(
            'relatedFieldsOn' => array(
                'sliderresponsiveSlideWidthMax'
            )
        ));
        new NumberAutoComplete($slideMaxWidthGroup, 'responsiveSlideWidthMax', n2_('Max'), 3000, array(
            'min'    => 0,
            'values' => array(
                3000,
                980
            ),
            'unit'   => 'px',
            'wide'   => 5
        ));

        new OnOff($slideMaxWidthGroup, 'responsiveSlideWidthTablet', n2_('Tablet'), 0, array(
            'relatedFieldsOn' => array(
                'sliderresponsiveSlideWidthMaxTablet'
            )
        ));
        new NumberAutoComplete($slideMaxWidthGroup, 'responsiveSlideWidthMaxTablet', n2_('Max'), 3000, array(
            'min'    => 0,
            'values' => array(
                3000,
                980
            ),
            'unit'   => 'px',
            'wide'   => 5
        ));

        new OnOff($slideMaxWidthGroup, 'responsiveSlideWidthMobile', n2_('Mobile'), 0, array(
            'relatedFieldsOn' => array(
                'sliderresponsiveSlideWidthMaxMobile'
            )
        ));
        new NumberAutoComplete($slideMaxWidthGroup, 'responsiveSlideWidthMaxMobile', n2_('Max'), 480, array(
            'min'    => 0,
            'values' => array(
                3000,
                480
            ),
            'unit'   => 'px',
            'wide'   => 5
        ));

    }

    protected function breakpoints() {

        $table = new ContainerTable($this->tab, 'breakpoints', n2_('Breakpoints'));

        $tableFieldset = $table->getFieldsetLabel();

        new HiddenText($tableFieldset, 'responsive-breakpoint-tablet-portrait', false, ViewSettingsGeneral::defaults['tablet-portrait']);
        new HiddenText($tableFieldset, 'responsive-breakpoint-tablet-portrait-landscape', false, ViewSettingsGeneral::defaults['tablet-landscape']);

        new HiddenText($tableFieldset, 'responsive-breakpoint-mobile-portrait', false, ViewSettingsGeneral::defaults['mobile-portrait']);
        new HiddenText($tableFieldset, 'responsive-breakpoint-mobile-portrait-landscape', false, ViewSettingsGeneral::defaults['mobile-landscape']);
        new HiddenOnOff($tableFieldset, 'responsive-breakpoint-tablet-portrait-enabled', n2_('Tablet'), 1, array(
            'relatedFieldsOn' => array(
                'sliderresponsive-breakpoint-notice-tablet-portrait',
                'table-row-override-slider-size-tablet-portrait-row'
            )
        ));
        new HiddenOnOff($tableFieldset, 'responsive-breakpoint-mobile-portrait-enabled', n2_('Mobile'), 1, array(
            'relatedFieldsOn' => array(
                'sliderresponsive-breakpoint-notice-mobile-portrait',
                'table-row-override-slider-size-mobile-portrait-row'
            )
        ));

        $row1 = $table->createRow('breakpoints-row-1');

        $instructions = n2_('Breakpoints define the browser width in pixel when the slider switches to a different device.');

        new Notice($row1, 'breakpoints-instructions', n2_('Instruction'), $instructions);

        $row2 = $table->createRow('breakpoints-row-2');

        new OnOff($row2, 'responsive-breakpoint-global', n2_('Global breakpoints'), 0, array(
            'tipLabel'       => n2_('Global breakpoints'),
            'tipDescription' => sprintf(n2_('You can use the global breakpoints, or adjust them locally here. You can configure the Global breakpoints at %1$sGlobal settings%2$s > General > Breakpoints'), sprintf('<a href="%s" target="_blank">', $this->form->getMVCHelper()
                                                                                                                                                                                                                                                                 ->getUrlSettingsDefault()), '</a>')
        ));
        new Breakpoint($row2, 'breakpoints', array(
            'tabletportrait-portrait'  => 'sliderresponsive-breakpoint-tablet-portrait',
            'tabletportrait-landscape' => 'sliderresponsive-breakpoint-tablet-portrait-landscape',
            'mobileportrait-portrait'  => 'sliderresponsive-breakpoint-mobile-portrait',
            'mobileportrait-landscape' => 'sliderresponsive-breakpoint-mobile-portrait-landscape'
        ), array(), array(
            'field'  => 'sliderresponsive-breakpoint-global',
            'values' => array(
                'tabletportrait-portrait'  => Settings::get('responsive-screen-width-tablet-portrait', ViewSettingsGeneral::defaults['tablet-portrait']),
                'tabletportrait-landscape' => Settings::get('responsive-screen-width-tablet-portrait-landscape', ViewSettingsGeneral::defaults['tablet-landscape']),
                'mobileportrait-portrait'  => Settings::get('responsive-screen-width-mobile-portrait', ViewSettingsGeneral::defaults['mobile-portrait']),
                'mobileportrait-landscape' => Settings::get('responsive-screen-width-mobile-portrait-landscape', ViewSettingsGeneral::defaults['mobile-landscape'])
            )
        ));
    
    }

    protected function layout() {

        $table = new ContainerTable($this->tab, 'responsive-mode', n2_('Layout'));

        $row1 = $table->createRow('responsive-mode-row-1');

        /**
         * Used for option removal: /size/responsive-mode/responsive-mode-row-1/responsive-mode
         */
        new ResponsiveSubFormIcon($row1, 'responsive-mode', $table, $this->form->createAjaxUrl(array("slider/renderresponsivetype")), 'auto');

    }

    protected function customSize() {
    }

    /**
     * @param ContainerRowGroup $rowGroup
     */
    protected function desktopLandscape($rowGroup) {
    }

    /**
     * @param ContainerRowGroup $rowGroup
     */
    protected function tabletLandscape($rowGroup) {
    }

    /**
     * @param ContainerRowGroup $rowGroup
     */
    protected function tabletPortrait($rowGroup) {

        $row = $rowGroup->createRow('override-slider-size-tablet-portrait-row');

        new OnOff($row, 'slider-size-override-tablet-portrait', n2_('Tablet'), 0, array(
            'relatedFieldsOn' => array(
                'slidertablet-portrait-width',
                'slidertablet-portrait-height'
            )
        ));

        new Number($row, 'tablet-portrait-width', n2_('Width'), 768, array(
            'wide' => 5,
            'unit' => 'px'
        ));
        new Number($row, 'tablet-portrait-height', n2_('Height'), 1024, array(
            'wide' => 5,
            'unit' => 'px'
        ));
    }

    /**
     * @param ContainerRowGroup $rowGroup
     */
    protected function mobileLandscape($rowGroup) {
    }

    /**
     * @param ContainerRowGroup $rowGroup
     */
    protected function mobilePortrait($rowGroup) {

        $row = $rowGroup->createRow('override-slider-size-mobile-portrait-row');

        new OnOff($row, 'slider-size-override-mobile-portrait', n2_('Mobile'), 0, array(
            'relatedFieldsOn' => array(
                'slidermobile-portrait-width',
                'slidermobile-portrait-height'
            )
        ));

        new Number($row, 'mobile-portrait-width', n2_('Width'), 320, array(
            'wide' => 5,
            'unit' => 'px'
        ));
        new Number($row, 'mobile-portrait-height', n2_('Height'), 568, array(
            'wide' => 5,
            'unit' => 'px'
        ));
    }
}