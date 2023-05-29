<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Form\Container\ContainerTab;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Arrow\ArrowImage\ArrowImage;

class Arrow extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 1;

    public function __construct() {
        parent::__construct();

        new ArrowImage($this, 'imageSmallRectangle', array(
            'widget-arrow-desktop-image-width' => 26,
            'widget-arrow-tablet-image-width'  => 26,
            'widget-arrow-previous'            => '$ss$/plugins/widgetarrow/image/image/previous/full.svg',
            'widget-arrow-next'                => '$ss$/plugins/widgetarrow/image/image/next/full.svg',
            'widget-arrow-style'               => '{"data":[{"backgroundcolor":"000000ab","padding":"2|*|2|*|2|*|2|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"3","extra":""},{"backgroundcolor":"FF9139FF"}]}'
        ));

        new ArrowImage($this, 'imageEmpty', array(
            'widget-arrow-previous' => '$ss$/plugins/widgetarrow/image/image/previous/thin-horizontal.svg',
            'widget-arrow-next'     => '$ss$/plugins/widgetarrow/image/image/next/thin-horizontal.svg',
            'widget-arrow-style'    => ''
        ));

        $this->makePluggable('SliderWidgetArrow');
    }

    public function getName() {
        return 'arrow';
    }

    public function getLabel() {
        return n2_('Arrows');
    }

    /**
     * @param ContainerTab $container
     */
    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        /**
         * Used for field removal: /controls/widget-arrow
         */
        $table = new ContainerTable($container, 'widget-arrow', n2_('Arrow'));

        new OnOff($table->getFieldsetLabel(), 'widget-arrow-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widget-arrow'
            )
        ));

        $row1 = $table->createRow('widget-arrow-1');

        $ajaxUrl = $form->createAjaxUrl(array("slider/renderwidgetarrow"));
        new ControlTypePicker($row1, 'widgetarrow', $table, $ajaxUrl, $this, 'imageEmpty');


        $row2 = $table->createRow('widget-arrow-2');

        new OnOff($row2, 'widget-arrow-display-hover', n2_('Shows on hover'), 0);

        $this->addHideOnFeature('widget-arrow-display-', $row2);

    }
}