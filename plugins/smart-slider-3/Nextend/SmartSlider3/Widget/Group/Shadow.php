<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Shadow\ShadowImage\ShadowImage;

class Shadow extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 7;

    public function __construct() {
        parent::__construct();

        new ShadowImage($this, 'shadow');

        $this->makePluggable('SliderWidgetShadow');
    }

    public function getName() {
        return 'shadow';
    }

    public function getLabel() {
        return n2_('Shadow');
    }

    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        $table = new ContainerTable($container, 'widgetshadow', n2_('Shadow'));
        new OnOff($table->getFieldsetLabel(), 'widget-shadow-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widgetshadow'
            )
        ));

        $row1 = $table->createRow('widget-shadow-1');

        $url = $form->createAjaxUrl(array("slider/renderwidgetshadow"));
        new ControlTypePicker($row1, 'widgetshadow', $table, $url, $this);


        $row2 = $table->createRow('widget-shadow-2');

        $this->addHideOnFeature('widget-shadow-display-', $row2);
    }
}