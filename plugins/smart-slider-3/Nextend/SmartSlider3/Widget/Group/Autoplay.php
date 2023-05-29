<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\SmartSlider3\Form\Element\ControlTypePicker;
use Nextend\SmartSlider3\Widget\Autoplay\AutoplayImage\AutoplayImage;

class Autoplay extends AbstractWidgetGroup {

    use PluggableTrait;

    public $ordering = 3;

    protected $showOnMobileDefault = 1;

    public function __construct() {
        parent::__construct();

        new AutoplayImage($this, 'image');

        $this->makePluggable('SliderWidgetAutoplay');
    }

    public function getName() {
        return 'autoplay';
    }

    public function getLabel() {
        return n2_('Autoplay');
    }

    public function renderFields($container) {

        $form = $container->getForm();

        $this->compatibility($form);

        $autoplayFinish = $form->get('autoplayfinish');
        if (!$form->has('autoplayLoop') && !empty($autoplayFinish)) {
            $this->upgradeData($form);
        }

        $table = new ContainerTable($container, 'widget-autoplay', n2_('Button'));

        new OnOff($table->getFieldsetLabel(), 'widget-autoplay-enabled', false, 0, array(
            'relatedFieldsOn' => array(
                'table-rows-widget-autoplay'
            )
        ));

        $row1 = $table->createRow('widget-bullet-1');

        $url = $form->createAjaxUrl(array("slider/renderwidgetautoplay"));
        new ControlTypePicker($row1, 'widgetautoplay', $table, $url, $this, 'image');


        $row2 = $table->createRow('widget-bullet-2');

        new OnOff($row2, 'widget-autoplay-display-hover', n2_('Shows on hover'), 0);

        $this->addHideOnFeature('widget-autoplay-display-', $row2);
    }


    /**
     * For compatibility with legacy autoplay values.
     *
     * @param Data $data
     */
    protected function upgradeData($data) {
        if (!$data->has('autoplayLoop')) {
            list($interval, $intervalModifier, $intervalSlide) = (array)Common::parse($data->get('autoplayfinish', '1|*|loop|*|current'));
            if ($interval <= 0) {
                // 0|*|slide|*|current -> In old versions it brought to the Next slide.
                if ($interval <= 0 && $intervalModifier === 'slide' && $intervalSlide === 'next') {
                    $data->set('autoplayfinish', '1|*|slide|*|current');
                    $data->set('autoplayLoop', 0);
                }

                // 0|*|loop/slide/slideindex|*|current -> Infinite loop
                // 0|*|loop|*|next -> Infinite loop
                if ($intervalSlide === 'current' || ($intervalModifier === 'loop' && $intervalSlide === 'next')) {
                    $data->set('autoplayfinish', '1|*|loop|*|current');
                    $data->set('autoplayLoop', 1);
                }

                // 0|*|slideindex|*|next -> In old versions it always brought to the 2nd slide.
                if ($intervalModifier === 'slideindex' && $intervalSlide === 'next') {
                    $data->set('autoplayfinish', '2|*|slideindex|*|current');
                    $data->set('autoplayLoop', '0');
                }
            } else {
                //next is not allowed for "slide" and "slideindex" interval modifiers
                if ($intervalModifier === 'slide' || $intervalModifier === 'slideindex') {
                    $data->set('autoplayfinish', $interval . '|*|' . $intervalModifier . '|*|current');
                }
                // turn off Loop, and work with the original settings
                $data->set('autoplayLoop', '0');
            }
        }
    }
}