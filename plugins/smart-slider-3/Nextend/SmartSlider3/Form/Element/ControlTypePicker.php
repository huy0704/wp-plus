<?php


namespace Nextend\SmartSlider3\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerSubform;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\Form\TraitFieldset;
use Nextend\Framework\Url\Url;
use Nextend\SmartSlider3\Widget\Group\AbstractWidgetGroup;

class ControlTypePicker extends AbstractFieldHidden {

    protected $hasTooltip = false;

    protected $options = array();

    protected $plugins = array();

    protected $ajaxUrl = '';

    /**
     * @var ContainerSubform
     */
    protected $containerSubform;

    /** @var AbstractWidgetGroup */
    private $widgetGroup;

    /**
     * SubFormIcon constructor.
     *
     * @param TraitFieldset       $insertAt
     * @param string              $name
     * @param ContainerInterface  $container
     * @param string              $ajaxUrl
     * @param AbstractWidgetGroup $widgetGroup
     * @param string              $default
     * @param array               $parameters
     */
    public function __construct($insertAt, $name, $container, $ajaxUrl, $widgetGroup, $default = '', $parameters = array()) {

        $this->name        = $name;
        $this->widgetGroup = $widgetGroup;

        $this->ajaxUrl = $ajaxUrl;

        parent::__construct($insertAt, $name, false, $default, $parameters);

        $this->initOptions();

        $this->containerSubform = new ContainerSubform($container, $name . '-subform');

        $this->getCurrentPlugin($this->getValue())
             ->renderFields($this->containerSubform);
    }

    protected function fetchElement() {
        $html = '<div class="n2_field_control_type_picker">';
        foreach ($this->options as $key => $option) {
            $html .= '<div class="n2_field_control_type_picker__item" data-controltype="' . $key . '">';
            $html .= '<img alt="" src="' . Url::pathToUri($option['path']) . '">';
            $html .= '<div class="n2_field_control_type_picker__selected_marker"><i class="ssi_16 ssi_16--check"></i></div>';
            $html .= '</div>';
        }
        $html .= parent::fetchElement();
        $html .= '</div>';

        Js::addInline('new _N2.FormElementControlTypePicker( "' . $this->fieldID . '",  ' . json_encode(array(
                'ajaxUrl'       => $this->ajaxUrl,
                'target'        => $this->containerSubform->getId(),
                'originalValue' => $this->getValue()
            )) . ');');

        return $html;
    }


    protected function getCurrentPlugin($value) {

        if (!isset($this->plugins[$value])) {
            list($value) = array_keys($this->plugins);
        }

        return $this->plugins[$value];
    }

    private function initOptions() {

        $this->plugins = $this->widgetGroup->getWidgets();

        foreach ($this->plugins as $name => $type) {
            $this->options[$name] = array(
                'path' => $type->getSubFormImagePath()
            );
        }
        if (count($this->options) == 1) {
            $this->parent->hide();
        }
    }

}