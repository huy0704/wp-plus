<?php


namespace Nextend\SmartSlider3\Widget\Group;


use Nextend\Framework\Form\Container\ContainerTab;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Thumbnail\Basic\ThumbnailBasic;

class Thumbnail extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 6;

    public function __construct() {
        parent::__construct();

        new ThumbnailBasic($this, 'default');

        $this->makePluggable('SliderWidgetThumbnail');
    }

    public function getName() {
        return 'thumbnail';
    }

    public function getLabel() {
        return n2_('Thumbnails');
    }

    /**
     * @param ContainerTab $container
     */
    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        /**
         * Used for field removal: /controls/widget-thumbnail
         */
        $table = new ContainerTable($container, 'widget-thumbnail', n2_('Thumbnails'));

        new OnOff($table->getFieldsetLabel(), 'widget-thumbnail-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widget-thumbnail'
            )
        ));

        $row1 = $table->createRow('widget-thumbnail-1');

        $row2 = $table->createRow('widget-thumbnail-2');

        new NumberAutoComplete($row2, 'widget-thumbnail-width', n2_('Desktop width'), 100, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-thumbnail-height', n2_('Height'), 60, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-thumbnail-tablet-width', n2_('Tablet width'), 100, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-thumbnail-tablet-height', n2_('Height'), 60, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-thumbnail-mobile-width', n2_('Mobile width'), 100, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));

        new NumberAutoComplete($row2, 'widget-thumbnail-mobile-height', n2_('Height'), 60, array(
            'unit'   => 'px',
            'values' => array(
                60,
                100,
                150,
                200
            ),
            'wide'   => 4
        ));


        $ajaxUrl = $form->createAjaxUrl(array("slider/renderwidgetthumbnail"));
        new ControlTypePicker($row1, 'widgetthumbnail', $table, $ajaxUrl, $this, 'default');


        $row3 = $table->createRow('widget-thumbnail-3');

        new OnOff($row3, 'widget-thumbnail-display-hover', n2_('Shows on hover'), 0);

        $this->addHideOnFeature('widget-thumbnail-display-', $row3);
    }
}