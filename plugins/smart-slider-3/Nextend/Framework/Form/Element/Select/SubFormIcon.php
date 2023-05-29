<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerSubform;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\Form\TraitFieldset;
use Nextend\Framework\View\Html;

abstract class SubFormIcon extends AbstractFieldHidden {

    protected $ajaxUrl = '';

    /**
     * @var ContainerSubform
     */
    protected $containerSubform;

    protected $plugins = array();

    protected $options = array();

    /**
     * SubFormIcon constructor.
     *
     * @param TraitFieldset      $insertAt
     * @param string             $name
     * @param ContainerInterface $container
     * @param string             $ajaxUrl
     * @param string             $default
     * @param array              $parameters
     */
    public function __construct($insertAt, $name, $container, $ajaxUrl, $default = '', $parameters = array()) {

        $this->ajaxUrl = $ajaxUrl;

        parent::__construct($insertAt, $name, '', $default, $parameters);

        $this->loadOptions();

        $this->containerSubform = new ContainerSubform($container, $name . '-subform');

        $this->getCurrentPlugin($this->getValue())
             ->renderFields($this->containerSubform);
    }

    protected function fetchElement() {

        $currentValue = $this->getValue();

        Js::addInline('
            new _N2.FormElementSubformIcon(
               "' . $this->fieldID . '",
              "' . $this->ajaxUrl . '",
               "' . $this->containerSubform->getId() . '",
               "' . $currentValue . '"
            );
        ');
        $html = Html::openTag('div', array(
            'class' => 'n2_field_subform_icon'
        ));
        foreach ($this->options as $value => $option) {
            $html .= Html::tag('div', array(
                'class'      => 'n2_field_subform_icon__option' . ($value == $currentValue ? ' n2_field_subform_icon__option--selected' : ''),
                'data-value' => $value
            ), Html::tag('div', array(
                    'class' => 'n2_field_subform_icon__option_icon'
                ), '<i class="' . $option['icon'] . '"></i>') . Html::tag('div', array(
                    'class' => 'n2_field_subform_icon__option_label'
                ), $option['label']));
        }

        $html .= parent::fetchElement() . '</div>';

        return $html;
    }

    protected abstract function loadOptions();


    protected function getCurrentPlugin($value) {

        if (!isset($this->plugins[$value])) {
            list($value) = array_keys($this->plugins);
            $this->setValue($value);
        }

        return $this->plugins[$value];
    }

    /**
     * @param string $option
     */
    public function removeOption($option) {
        if (isset($this->options[$option])) {
            unset($this->options[$option]);

            if ($this->getValue() === $option) {
                $this->setValue($this->defaultValue);
            }
        }
    }
}