<?php


namespace Nextend\SmartSlider3\Widget\Bar\BarHorizontal;


use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\Bar\AbstractWidgetBar;

class BarHorizontal extends AbstractWidgetBar {

    protected $defaults = array(
        'widget-bar-position-mode'    => 'simple',
        'widget-bar-position-area'    => 10,
        'widget-bar-position-offset'  => 30,
        'widget-bar-style'            => '{"data":[{"backgroundcolor":"000000ab","padding":"5|*|20|*|5|*|20|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"40","extra":""}]}',
        'widget-bar-show-title'       => 1,
        'widget-bar-font-title'       => '{"data":[{"color":"ffffffff","size":"14||px","tshadow":"0|*|0|*|0|*|000000c7","afont":"Montserrat","lineheight":"1.3","bold":0,"italic":0,"underline":0,"align":"left","extra":"vertical-align: middle;"},{"color":"fc2828ff","afont":"Raleway,Arial","size":"25||px"},{}]}',
        'widget-bar-show-description' => 1,
        'widget-bar-font-description' => '{"data":[{"color":"ffffffff","size":"14||px","tshadow":"0|*|0|*|0|*|000000c7","afont":"Montserrat","lineheight":"1.3","bold":0,"italic":1,"underline":0,"align":"left","extra":"vertical-align: middle;"},{"color":"fc2828ff","afont":"Raleway,Arial","size":"25||px"},{}]}',
        'widget-bar-slide-count'      => 0,
        'widget-bar-width'            => '100%',
        'widget-bar-full-width'       => 0,
        'widget-bar-separator'        => ' - ',
        'widget-bar-align'            => 'center',
        'widget-bar-animate'          => 0
    );


    public function renderFields($container) {

        $row1 = new FieldsetRow($container, 'widget-bar-horizontal-row-1');

        new WidgetPosition($row1, 'widget-bar-position', n2_('Position'));

        new OnOff($row1, 'widget-bar-animate', n2_('Animate'));

        new Style($row1, 'widget-bar-style', n2_('Bar'), '', array(
            'mode'    => 'simple',
            'font'    => 'sliderwidget-bar-font-title',
            'font2'   => 'sliderwidget-bar-font-description',
            'preview' => 'SmartSliderAdminWidgetBarHorizontal'
        ));

        $rowTitle = new FieldsetRow($container, 'widget-bar-horizontal-row-title');
        new OnOff($rowTitle, 'widget-bar-show-title', n2_('Title'), 0, array(
            'relatedFieldsOn' => array(
                'sliderwidget-bar-font-title',
                'sliderwidget-bar-slide-count-container'
            )
        ));
        new Font($rowTitle, 'widget-bar-font-title', '', '', array(
            'mode'    => 'simple',
            'style'   => 'sliderwidget-bar-style',
            'preview' => 'SmartSliderAdminWidgetBarHorizontal'
        ));


        $rowDescription = new FieldsetRow($container, 'widget-bar-horizontal-row-description');
        new OnOff($rowDescription, 'widget-bar-show-description', n2_('Description'), 0, array(
            'relatedFieldsOn' => array(
                'sliderwidget-bar-font-description',
                'sliderwidget-bar-slide-count'
            )
        ));
        new Font($rowDescription, 'widget-bar-font-description', '', '', array(
            'mode'    => 'simple',
            'style'   => 'sliderwidget-bar-style',
            'preview' => 'SmartSliderAdminWidgetBarHorizontal'
        ));

        $row4 = new FieldsetRow($container, 'widget-bar-horizontal-row-4');

        $slideCountContainer = new Grouping($row4, 'widget-bar-slide-count-container');
        new OnOff($slideCountContainer, 'widget-bar-slide-count', n2_('Slide count'), 0, array(
            'tipLabel'       => n2_('Slide count'),
            'tipDescription' => n2_('The "Title" will be the index of the slide and "Description" will be the total number of slides.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1856-text-bar#slide-count'
        ));
        new OnOff($row4, 'widget-bar-full-width', n2_('Full width'));


        new Text($row4, 'widget-bar-separator', n2_('Separator'), 0, array(
            'tipLabel'       => n2_('Separator'),
            'tipDescription' => sprintf(n2_('You can set what separates the Tex bar Title and Description. This separator is used at the Slide count option, too, to separate the current and total slide number. %1$s To put the Description to a new line, use the &lt;br&gt; HTML tag.'), '<br>'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1856-text-bar#separator'
        ));

        new Select($row4, 'widget-bar-align', n2_('Align'), '', array(
            'options' => array(
                'left'   => n2_('Left'),
                'center' => n2_('Center'),
                'right'  => n2_('Right')
            )
        ));
    }

    public function prepareExport($export, $params) {
        $export->addVisual($params->get($this->key . 'style'));
        $export->addVisual($params->get($this->key . 'font-title'));
        $export->addVisual($params->get($this->key . 'font-description'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'style', $import->fixSection($params->get($this->key . 'style', '')));
        $params->set($this->key . 'font-title', $import->fixSection($params->get($this->key . 'font-title', '')));
        $params->set($this->key . 'font-description', $import->fixSection($params->get($this->key . 'font-description', '')));
    }
}