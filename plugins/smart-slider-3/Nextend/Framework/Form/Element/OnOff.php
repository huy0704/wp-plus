<?php

namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;

class OnOff extends AbstractFieldHidden {

    protected $relatedFieldsOn = array();

    protected $relatedAttribute = '';

    protected $values = array(
        0 => 0,
        1 => 1
    );

    protected $customValues = false;

    protected function fetchElement() {

        $html = '<div class="n2_field_onoff' . $this->isOn() . '" role="switch" aria-checked="false" tabindex="0" aria-label="' . $this->label . '">' . parent::fetchElement() . '<div class="n2_field_onoff__slider"><div class="n2_field_onoff__slider_bullet"></div></div><div class="n2_field_onoff__labels"><div class="n2_field_onoff__label n2_field_onoff__label_off">' . n2_('Off') . '</div><div class="n2_field_onoff__label n2_field_onoff__label_on">' . n2_('On') . '</div></div></div>';

        $options = array();

        if ($this->customValues) {
            $options['values'] = $this->customValues;
        }
        if (!empty($this->relatedFieldsOff)) {
            $options['relatedFieldsOff'] = $this->relatedFieldsOff;
        }
        if (!empty($this->relatedFieldsOn)) {
            $options['relatedFieldsOn'] = $this->relatedFieldsOn;
        }
        if (!empty($this->relatedAttribute)) {
            $options['relatedAttribute'] = $this->relatedAttribute;
        }

        Js::addInline('new _N2.FormElementOnoff("' . $this->fieldID . '", ' . json_encode($options) . ');');

        return $html;
    }

    private function isOn() {
        $value = $this->getValue();
        if (($this->customValues && $this->customValues[$value]) || (!$this->customValues && $value)) {
            return ' n2_field_onoff--on';
        }

        return '';
    }

    /**
     * @param array $relatedFields
     */
    public function setRelatedFieldsOn($relatedFields) {
        $this->relatedFieldsOn = $relatedFields;
    }

    /**
     * @param array $relatedFields
     */
    public function setRelatedFieldsOff($relatedFields) {
        $this->relatedFieldsOff = $relatedFields;
    }

    public function setRelatedAttribute($relatedAttribute) {
        $this->relatedAttribute = $relatedAttribute;
    }

    public function setCustomValues($offValue = 0, $onValue = 1) {

        if ($offValue === 0 && $onValue === 1) {
            $this->customValues = false;
        } else {
            $this->customValues            = array();
            $this->customValues[$offValue] = 0;
            $this->customValues[$onValue]  = 1;
        }
    }

    public function setInvert($isInvert) {
        if ($isInvert) {
            $this->setCustomValues(1, 0);
        } else {
            $this->setCustomValues(0, 1);
        }
    }
}