<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Bar\BarHorizontal\BarHorizontal;

class Bar extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 5;

    protected $showOnMobileDefault = 1;

    public function __construct() {
        parent::__construct();

        new BarHorizontal($this, 'horizontal');

        new BarHorizontal($this, 'horizontalFull', array(
            'widget-bar-position-offset' => 0,
            'widget-bar-style'           => '{"data":[{"backgroundcolor":"000000ab","padding":"20|*|20|*|20|*|20|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"0","extra":""}]}',
            'widget-bar-full-width'      => 1,
            'widget-bar-align'           => 'left'
        ));

        $this->makePluggable('SliderWidgetBar');
    }

    public function getName() {
        return 'bar';
    }

    public function getLabel() {
        return n2_('Text bar');
    }

    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        /**
         * Used for field removal: /controls/widget-bar
         */
        $table = new ContainerTable($container, 'widget-bar', n2_('Text bar'));

        new OnOff($table->getFieldsetLabel(), 'widget-bar-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widget-bar'
            )
        ));

        $row1 = $table->createRow('widget-bar-1');

        $ajaxUrl = $form->createAjaxUrl(array("slider/renderwidgetbar"));
        new ControlTypePicker($row1, 'widgetbar', $table, $ajaxUrl, $this, 'horizontal');


        $row2 = $table->createRow('widget-bar-2');

        new OnOff($row2, 'widget-bar-display-hover', n2_('Shows on hover'), 0);

        $this->addHideOnFeature('widget-bar-display-', $row2);

    }
}