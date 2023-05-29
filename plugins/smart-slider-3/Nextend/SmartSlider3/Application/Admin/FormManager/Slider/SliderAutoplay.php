<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\FormTabbed;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;
use Nextend\SmartSlider3Pro\Form\Element\AutoplayPicker;

class SliderAutoplay extends AbstractSliderTab {

    /**
     * SliderAutoplay constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->autoplay();

        $plugins = WidgetGroupFactory::getGroups();

        if (isset($plugins['autoplay'])) {
            $plugins['autoplay']->renderFields($this->tab);
        }

        if (isset($plugins['indicator'])) {
            $plugins['indicator']->renderFields($this->tab);
        }
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'autoplay';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Autoplay');
    }

    protected function autoplay() {

        $table = new ContainerTable($this->tab, 'autoplay', n2_('Autoplay'));

        new OnOff($table->getFieldsetLabel(), 'autoplay', n2_('Enable'), 0, array(
            'relatedAttribute' => 'autoplay',
            'relatedFieldsOn'  => array(
                'table-rows-autoplay',
                'table-widget-autoplay',
                'table-widget-indicator',
                'autoplay-single-slide-notice'
            )
        ));

        $row2 = $table->createRow('row-2');
        new Number($row2, 'autoplayDuration', n2_('Slide duration'), 8000, array(
            'wide' => 5,
            'unit' => 'ms'
        ));

        $row3 = $table->createRow('row-3');
        new OnOff($row3, 'autoplayStopClick', n2_('Stop on click'), 1);
        new Select($row3, 'autoplayStopMouse', n2_('Stop on mouse'), 0, array(
            'options' => array(
                '0'     => n2_('Off'),
                'enter' => n2_('Enter'),
                'leave' => n2_('Leave')
            )
        ));
        new OnOff($row3, 'autoplayStopMedia', n2_('Stop on media'), 1);

        $row4 = $table->createRow('row-4');
        new OnOff($row4, 'autoplayResumeClick', n2_('Resume on click'), 0);
        new Select($row4, 'autoplayResumeMouse', n2_('Resume on mouse'), 0, array(
            'options' => array(
                '0'     => n2_('Off'),
                'leave' => n2_('Leave'),
                'enter' => n2_('Enter')
            )
        ));
        new OnOff($row4, 'autoplayResumeMedia', n2_('Resume on media'), 1);
    }
}