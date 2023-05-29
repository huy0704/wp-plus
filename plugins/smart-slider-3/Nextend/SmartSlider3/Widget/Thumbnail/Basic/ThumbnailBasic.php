<?php


namespace Nextend\SmartSlider3\Widget\Thumbnail\Basic;


use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\AbstractWidget;

class ThumbnailBasic extends AbstractWidget {

    protected $key = 'widget-thumbnail-';

    protected $defaults = array(
        'widget-thumbnail-position-mode'     => 'simple',
        'widget-thumbnail-position-area'     => 12,
        'widget-thumbnail-action'            => 'click',
        'widget-thumbnail-style-bar'         => '{"data":[{"backgroundcolor":"242424ff","padding":"3|*|3|*|3|*|3|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"0","extra":""}]}',
        'widget-thumbnail-style-slides'      => '{"data":[{"backgroundcolor":"00000000","padding":"0|*|0|*|0|*|0|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|ffffff00","borderradius":"0","opacity":"40","extra":"margin: 3px;\ntransition: all 0.4s;"},{"border":"0|*|solid|*|ffffffcc","opacity":"100","extra":""}]}',
        'widget-thumbnail-arrow'             => 1,
        'widget-thumbnail-arrow-image'       => '',
        'widget-thumbnail-arrow-width'       => 26,
        'widget-thumbnail-arrow-offset'      => 0,
        'widget-thumbnail-arrow-prev-alt'    => 'previous arrow',
        'widget-thumbnail-arrow-next-alt'    => 'next arrow',
        'widget-thumbnail-title-style'       => '{"data":[{"backgroundcolor":"000000ab","padding":"3|*|10|*|3|*|10|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"0","extra":"bottom: 0;\nleft: 0;"}]}',
        'widget-thumbnail-title'             => 0,
        'widget-thumbnail-title-font'        => '{"data":[{"color":"ffffffff","size":"12||px","tshadow":"0|*|0|*|0|*|000000ab","afont":"Montserrat","lineheight":"1.2","bold":0,"italic":0,"underline":0,"align":"left"},{"color":"fc2828ff","afont":"Raleway,Arial","size":"25||px"},{}]}',
        'widget-thumbnail-description'       => 0,
        'widget-thumbnail-description-font'  => '{"data":[{"color":"ffffffff","size":"12||px","tshadow":"0|*|0|*|0|*|000000ab","afont":"Montserrat","lineheight":"1.3","bold":0,"italic":0,"underline":0,"align":"left"},{"color":"fc2828ff","afont":"Raleway,Arial","size":"25||px"},{}]}',
        'widget-thumbnail-caption-placement' => 'overlay',
        'widget-thumbnail-caption-size'      => 100,
        'widget-thumbnail-group'             => 1,
        'widget-thumbnail-orientation'       => 'auto',
        'widget-thumbnail-size'              => '100%',
        'widget-thumbnail-show-image'        => 1,
        'widget-thumbnail-width'             => 100,
        'widget-thumbnail-height'            => 60,
        'widget-thumbnail-align-content'     => 'start'
    );


    public function renderFields($container) {

        $row1 = new FieldsetRow($container, 'widget-thumbnail-default-row-1');

        new WidgetPosition($row1, 'widget-thumbnail-position', n2_('Position'));

        new Select($row1, 'widget-thumbnail-align-content', n2_('Align thumbnails'), '', array(
            'options' => array(
                'start'         => n2_('Start'),
                'center'        => n2_('Center'),
                'end'           => n2_('End'),
                'space-between' => n2_('Space between'),
                'space-around'  => n2_('Space around')
            )
        ));

        new Style($row1, 'widget-thumbnail-style-bar', n2_('Bar'), '', array(
            'mode'    => 'simple',
            'style2'  => 'sliderwidget-thumbnail-style-slides',
            'preview' => 'SmartSliderAdminWidgetThumbnailBasic'
        ));

        new Style($row1, 'widget-thumbnail-style-slides', n2_('Thumbnail'), '', array(
            'mode'    => 'dot',
            'style2'  => 'sliderwidget-thumbnail-style-bar',
            'preview' => 'SmartSliderAdminWidgetThumbnailBasic'
        ));

        $rowCaption = new FieldsetRow($container, 'widget-thumbnail-default-row-caption');
        new Style($rowCaption, 'widget-thumbnail-title-style', n2_('Caption'), '', array(
            'mode'    => 'simple',
            'post'    => 'break',
            'font'    => 'sliderwidget-thumbnail-title-font',
            'preview' => 'SmartSliderAdminWidgetThumbnailBasic'
        ));

        new OnOff($rowCaption, 'widget-thumbnail-title', n2_('Title'), '', array(
            'relatedFieldsOn' => array(
                'sliderwidget-thumbnail-title-font'
            )
        ));
        new Font($rowCaption, 'widget-thumbnail-title-font', '', '', array(
            'mode'    => 'simple',
            'style'   => 'sliderwidget-thumbnail-title-style',
            'preview' => 'SmartSliderAdminWidgetThumbnailBasic'
        ));

        new OnOff($rowCaption, 'widget-thumbnail-description', n2_('Description'), '', array(
            'relatedFieldsOn' => array(
                'sliderwidget-thumbnail-description-font'
            )
        ));
        new Font($rowCaption, 'widget-thumbnail-description-font', '', '', array(
            'mode'    => 'simple',
            'style'   => 'sliderwidget-thumbnail-title-style',
            'preview' => 'SmartSliderAdminWidgetThumbnailBasic'
        ));


        new Select($rowCaption, 'widget-thumbnail-caption-placement', n2_('Placement'), '', array(
            'options' => array(
                'before'  => n2_('Before'),
                'overlay' => n2_('Overlay'),
                'after'   => n2_('After')
            )
        ));

        new Number($rowCaption, 'widget-thumbnail-caption-size', n2_('Size'), '', array(
            'wide'           => 5,
            'unit'           => 'px',
            'tipLabel'       => n2_('Size'),
            'tipDescription' => n2_('The height (horizontal orientation) or width (vertical orientation) of the caption container.')
        ));
    }


    public function prepareExport($export, $params) {

        $export->addVisual($params->get($this->key . 'style-bar'));
        $export->addVisual($params->get($this->key . 'style-slides'));
        $export->addVisual($params->get($this->key . 'title-style'));

        $export->addVisual($params->get($this->key . 'title-font'));
        $export->addVisual($params->get($this->key . 'description-font'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'style-bar', $import->fixSection($params->get($this->key . 'style-bar', '')));
        $params->set($this->key . 'style-slides', $import->fixSection($params->get($this->key . 'style-slides', '')));
        $params->set($this->key . 'title-style', $import->fixSection($params->get($this->key . 'title-style', '')));

        $params->set($this->key . 'title-font', $import->fixSection($params->get($this->key . 'title-font', '')));
        $params->set($this->key . 'description-font', $import->fixSection($params->get($this->key . 'description-font', '')));
    }
}