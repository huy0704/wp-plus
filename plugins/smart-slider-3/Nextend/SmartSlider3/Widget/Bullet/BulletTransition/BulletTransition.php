<?php


namespace Nextend\SmartSlider3\Widget\Bullet\BulletTransition;


use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\Bullet\AbstractBullet;

class BulletTransition extends AbstractBullet {

    protected $defaults = array(
        'widget-bullet-position-mode'        => 'simple',
        'widget-bullet-position-area'        => 10,
        'widget-bullet-position-offset'      => 10,
        'widget-bullet-action'               => 'click',
        'widget-bullet-style'                => '{"data":[{"backgroundcolor":"000000ab","padding":"5|*|5|*|5|*|5|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"50","extra":"margin: 4px;"},{"backgroundcolor":"1D81F9FF"}]}',
        'widget-bullet-bar'                  => '',
        'widget-bullet-align'                => 'center',
        'widget-bullet-orientation'          => 'auto',
        'widget-bullet-bar-full-size'        => 0,
        'widget-bullet-thumbnail-show-image' => 0,
        'widget-bullet-thumbnail-width'      => 60,
        'widget-bullet-thumbnail-style'      => '{"data":[{"backgroundcolor":"00000080","padding":"3|*|3|*|3|*|3|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"3","extra":"margin: 5px;"}]}',
        'widget-bullet-thumbnail-side'       => 'before'
    );

    public function renderFields($container) {

        $row1 = new FieldsetRow($container, 'widget-bullet-transition-row-1');

        new WidgetPosition($row1, 'widget-bullet-position', n2_('Position'));

        $row2 = new FieldsetRow($container, 'widget-bullet-transition-row-2');

        new Style($row2, 'widget-bullet-style', n2_('Dot'), '', array(
            'mode'    => 'dot',
            'style2'  => 'sliderwidget-bullet-bar',
            'preview' => 'SmartSliderAdminWidgetBulletTransition'
        ));

        new Style($row2, 'widget-bullet-bar', n2_('Bar'), '', array(
            'mode'    => 'simple',
            'style2'  => 'sliderwidget-bullet-style',
            'preview' => 'SmartSliderAdminWidgetBulletTransition'
        ));
    }

    public function prepareExport($export, $params) {
        $export->addVisual($params->get($this->key . 'style'));
        $export->addVisual($params->get($this->key . 'bar'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'style', $import->fixSection($params->get($this->key . 'style')));
        $params->set($this->key . 'bar', $import->fixSection($params->get($this->key . 'bar')));
    }
}