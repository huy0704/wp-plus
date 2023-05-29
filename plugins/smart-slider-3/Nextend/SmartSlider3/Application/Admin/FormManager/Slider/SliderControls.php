<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\FormTabbed;
use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;

class SliderControls extends AbstractSliderTab {

    use OrderableTrait;

    /**
     * SliderControls constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->general();
        $this->controls();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'controls';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Controls');
    }

    protected function general() {
        /**
         * Used for field removal: /controls/general
         */

        $table = new ContainerTable($this->tab, 'general', n2_('General'));
        $row1  = $table->createRow('general-1');

        new Select($row1, 'controlsTouch', n2_('Drag'), 'horizontal', array(
            'options'        => array(
                '0'          => n2_('Disabled'),
                'horizontal' => n2_('Horizontal'),
                'vertical'   => n2_('Vertical')
            ),
            'tipLabel'       => n2_('Drag'),
            'tipDescription' => n2_('Defines the drag (and touch) direction for your slider.')
        ));

        new Select($row1, 'controlsScroll', n2_('Mouse wheel'), '0', array(
            'options'        => array(
                '0' => n2_('Disabled'),
                '1' => n2_('Vertical'),
                '2' => n2_('Horizontal')
            ),
            'tipLabel'       => n2_('Mouse wheel'),
            'tipDescription' => n2_('Allows switching slides with the mouse wheel.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1778-slider-settings-controls#mouse-wheel'
        ));

        new OnOff($row1, 'controlsKeyboard', n2_('Keyboard'), 1, array(
            'tipLabel'       => n2_('Keyboard'),
            'tipDescription' => n2_('Allows switching slides with the keyboard.')
        ));
    }

    protected function controls() {

        $plugins = WidgetGroupFactory::getGroups();

        self::uasort($plugins);

        unset($plugins['autoplay']);
        unset($plugins['indicator']);

        foreach ($plugins as $name => $widgetGroup) {
            $widgetGroup->renderFields($this->tab);
        }
    }
}