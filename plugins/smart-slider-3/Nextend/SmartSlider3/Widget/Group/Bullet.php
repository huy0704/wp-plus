<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Bullet\BulletTransition\BulletTransition;

class Bullet extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 2;

    protected $showOnMobileDefault = 1;

    public function __construct() {
        parent::__construct();

        new BulletTransition($this, 'transition');

        $this->makePluggable('SliderWidgetBullet');
    }

    public function getName() {
        return 'bullet';
    }

    public function getLabel() {
        return n2_('Bullets');
    }

    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        /**
         * Used for field removal: /controls/widget-bullet
         */
        $table = new ContainerTable($container, 'widget-bullet', n2_('Bullets'));

        new OnOff($table->getFieldsetLabel(), 'widget-bullet-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widget-bullet'
            )
        ));

        $row1 = $table->createRow('widget-bullet-1');


        $url = $form->createAjaxUrl(array("slider/renderwidgetbullet"));

        new ControlTypePicker($row1, 'widgetbullet', $table, $url, $this);

        $row2 = $table->createRow('widget-bullet-2');
        new OnOff($row2, 'widget-bullet-thumbnail-show-image', n2_('Image'), 0, array(
            'relatedFieldsOn' => array(
                'sliderwidget-bullet-thumbnail-width',
                'sliderwidget-bullet-thumbnail-height',
                'sliderwidget-bullet-thumbnail-style',
                'sliderwidget-bullet-thumbnail-side'
            )
        ));

        new NumberAutoComplete($row2, 'widget-bullet-thumbnail-width', n2_('Width'), 100, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-bullet-thumbnail-height', n2_('Height'), 60, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new Style($row2, 'widget-bullet-thumbnail-style', n2_('Style'), '{"data":[{"backgroundcolor":"00000080","padding":"3|*|3|*|3|*|3|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"3","extra":"margin: 5px;background-size:cover;"}]}', array(
            'mode'    => 'simple',
            'preview' => 'SmartSliderAdminWidgetBulletThumbnail'
        ));

        new Select($row2, 'widget-bullet-thumbnail-side', n2_('Side'), 'before', array(
            'options' => array(
                'before' => n2_('Before'),
                'after'  => n2_('After')
            )
        ));

        $row3 = $table->createRow('widget-bullet-3');

        new OnOff($row3, 'widget-bullet-display-hover', n2_('Shows on hover'), 0);

        $this->addHideOnFeature('widget-bullet-display-', $row3);

    }

}