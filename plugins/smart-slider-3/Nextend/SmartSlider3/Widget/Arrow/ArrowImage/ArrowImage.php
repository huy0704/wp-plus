<?php


namespace Nextend\SmartSlider3\Widget\Arrow\ArrowImage;


use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio\ImageListFromFolder;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\Arrow\AbstractWidgetArrow;

class ArrowImage extends AbstractWidgetArrow {

    protected $defaults = array(
        'widget-arrow-desktop-image-width'      => 32,
        'widget-arrow-tablet-image-width'       => 32,
        'widget-arrow-mobile-image-width'       => 16,
        'widget-arrow-previous-image'           => '',
        'widget-arrow-previous'                 => '$ss$/plugins/widgetarrow/image/image/previous/normal.svg',
        'widget-arrow-previous-color'           => 'ffffffcc',
        'widget-arrow-previous-hover'           => 0,
        'widget-arrow-previous-hover-color'     => 'ffffffcc',
        'widget-arrow-style'                    => '{"data":[{"backgroundcolor":"000000ab","padding":"20|*|10|*|20|*|10|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"5","extra":""},{"backgroundcolor":"000000cf"}]}',
        'widget-arrow-previous-position-mode'   => 'simple',
        'widget-arrow-previous-position-area'   => 6,
        'widget-arrow-previous-position-offset' => 15,
        'widget-arrow-next-position-mode'       => 'simple',
        'widget-arrow-next-position-area'       => 7,
        'widget-arrow-next-position-offset'     => 15,
        'widget-arrow-animation'                => 'fade',
        'widget-arrow-mirror'                   => 1,
        'widget-arrow-next-image'               => '',
        'widget-arrow-next'                     => '$ss$/plugins/widgetarrow/image/image/next/normal.svg',
        'widget-arrow-next-color'               => 'ffffffcc',
        'widget-arrow-next-hover'               => 0,
        'widget-arrow-next-hover-color'         => 'ffffffcc',
        'widget-arrow-previous-alt'             => 'previous arrow',
        'widget-arrow-next-alt'                 => 'next arrow',
        'widget-arrow-base64'                   => 1
    );

    public function renderFields($container) {

        $rowPrevious = new FieldsetRow($container, 'widget-arrow-image-row-previous');

        $fieldPrevious = new ImageListFromFolder($rowPrevious, 'widget-arrow-previous', n2_x('Previous', 'Arrow direction'), '', array(
            'folder' => self::getAssetsPath() . '/previous/'
        ));
        $fieldPrevious->setHasDisabled(false);
    

        $groupingPreviousColorContainer = new Grouping($rowPrevious, 'widget-arrow-image-row-icon-grouping-previous-color-container');
        $groupingPreviousColor          = new Grouping($groupingPreviousColorContainer, 'widget-arrow-image-row-icon-grouping-previous-color');
        new Color($groupingPreviousColor, 'widget-arrow-previous-color', n2_('Color'), '', array(
            'alpha' => true
        ));
        new OnOff($groupingPreviousColor, 'widget-arrow-previous-hover', n2_('Hover'), 0, array(
            'relatedFieldsOn' => array(
                'sliderwidget-arrow-previous-hover-color'
            )
        ));
        new Color($groupingPreviousColor, 'widget-arrow-previous-hover-color', n2_('Hover color'), '', array(
            'alpha' => true
        ));

        $row2 = new FieldsetRow($container, 'widget-arrow-image-row-2');

        new Style($row2, 'widget-arrow-style', n2_('Arrow'), '', array(
            'mode'    => 'button',
            'preview' => 'SmartSliderAdminWidgetArrowImage',
        ));

        new WidgetPosition($row2, 'widget-arrow-previous-position', n2_('Previous position'));
        new WidgetPosition($row2, 'widget-arrow-next-position', n2_('Next position'));

        $row3 = new FieldsetRow($container, 'widget-arrow-image-row-3');

        new Text($row3, 'widget-arrow-previous-alt', n2_('Previous alt tag'), 'previous arrow');
        new Text($row3, 'widget-arrow-next-alt', n2_('Next alt tag'), 'next arrow');
        new OnOff($row3, 'widget-arrow-base64', n2_('Base64'), 1, array(
            'tipLabel'        => n2_('Base64'),
            'tipDescription'  => n2_('Base64 encoded arrow images are loading faster and they are colorable. But optimization plugins often have errors in their codes related to them, so if your arrow won\'t load, turn this option off.'),
            'tipLink'         => 'https://smartslider.helpscoutdocs.com/article/1782-arrow#base64',
            'relatedFieldsOn' => array(
                'sliderwidget-arrow-image-row-icon-grouping-previous-color',
                'sliderwidget-arrow-image-row-icon-grouping-next-color'
            )
        ));

    }

    public function prepareExport($export, $params) {
        $export->addImage($params->get($this->key . 'previous-image', ''));
        $export->addImage($params->get($this->key . 'next-image', ''));

        $export->addVisual($params->get($this->key . 'style'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'previous-image', $import->fixImage($params->get($this->key . 'previous-image', '')));
        $params->set($this->key . 'next-image', $import->fixImage($params->get($this->key . 'next-image', '')));

        $params->set($this->key . 'style', $import->fixSection($params->get($this->key . 'style', '')));
    }
}