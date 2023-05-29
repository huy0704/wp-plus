<?php

namespace Nextend\SmartSlider3\Widget\Group;

use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\CheckboxOnOff;
use Nextend\Framework\Form\Element\Group\GroupCheckboxOnOff;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\SmartSlider3\Widget\AbstractWidget;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;

abstract class AbstractWidgetGroup {

    use OrderableTrait;

    /** @var AbstractWidget[] */
    private $widgets = array();

    protected $showOnMobileDefault = 0;

    public function __construct() {
        WidgetGroupFactory::addGroup($this);
    }

    public abstract function getName();

    public abstract function getLabel();

    /**
     * @param                $name
     * @param AbstractWidget $widget
     */
    public function addWidget($name, $widget) {
        $this->widgets[$name] = $widget;
    }

    /**
     * @return AbstractWidget[]
     */
    public function getWidgets() {
        return $this->widgets;
    }

    /**
     * @param $name
     *
     * @return AbstractWidget
     */
    public function getWidget($name) {
        return $this->widgets[$name];
    }

    /**
     * @param ContainerInterface $container
     */
    abstract public function renderFields($container);

    /**
     * @param Form $form
     */
    protected function compatibility($form) {
        $name = $this->getName();

        /**
         * Convert to the new control form with the enable field
         */
        if (!$form->has('widget-' . $name . '-enabled')) {

            if ($form->get('widget' . $name, 'disabled') !== 'disabled') {
                $form->set('widget-' . $name . '-enabled', 1);

            } else {
                $form->set('widget-' . $name . '-enabled', 0);
            }
        }

        $widgets = $this->getWidgets();

        $widgetPreset = $form->get('widget' . $name);

        if (!isset($widgets[$widgetPreset])) {
            $widgetPreset = key($widgets);
            $form->set('widget' . $name, $widgetPreset);
        }

        $widget = $widgets[$widgetPreset];

        $form->fillDefault($widget->getDefaults());
    }

    protected function addHideOnFeature($key, $row) {

        $groupShowOn = new GroupCheckboxOnOff($row, $key, n2_('Hide on'));
        new CheckboxOnOff($groupShowOn, $key . 'mobileportrait', false, 'ssi_16 ssi_16--mobileportrait', $this->showOnMobileDefault, array(
            'invert'      => true,
            'checkboxTip' => n2_('Mobile')
        ));


        new CheckboxOnOff($groupShowOn, $key . 'tabletportrait', false, 'ssi_16 ssi_16--tabletportrait', 1, array(
            'invert'      => true,
            'checkboxTip' => n2_('Tablet')
        ));


        new CheckboxOnOff($groupShowOn, $key . 'desktopportrait', false, 'ssi_16 ssi_16--desktopportrait', 1, array(
            'invert'      => true,
            'checkboxTip' => n2_('Desktop')
        ));
    }
}