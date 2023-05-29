<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class CheckboxOnOff extends AbstractFieldHidden {

    /**
     * @var string
     */
    protected $icon;

    protected $invert = false;

    protected $checkboxTip;

    public function __construct($insertAt, $name, $label, $icon, $default = 0, $parameters = array()) {

        $this->icon = $icon;

        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }

    protected function fetchElement() {

        $options = array(
            'invert'        => $this->invert,
            'relatedFields' => $this->relatedFields
        );

        Js::addInline('new _N2.FormElementCheckboxOnOff("' . $this->fieldID . '", ' . json_encode($options) . ');');

        $attr = array(
            'class' => 'n2_field_checkbox_onoff' . ($this->isActive() ? ' n2_field_checkbox_onoff--active' : '')
        );

        if (!empty($this->checkboxTip)) {
            $attr['data-n2tip'] = $this->checkboxTip;
        }

        return Html::tag('div', $attr, '<i class="' . $this->icon . '"></i>' . parent::fetchElement());
    }

    protected function isActive() {

        $value = $this->getValue();

        if (!$this->invert && $value) {
            return true;
        } else if ($this->invert && !$value) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $invert
     */
    public function setInvert($invert) {
        $this->invert = $invert;
    }

    /**
     * @param string $tip
     */
    public function setCheckboxTip($tip) {
        $this->checkboxTip = $tip;
    }

}