<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Select;

class Skin extends Select {

    protected $fixed = false;

    protected function fetchElement() {

        $html = parent::fetchElement();

        Js::addInline('new _N2.FormElementSkin("' . $this->fieldID . '", "' . str_replace($this->name, '', $this->fieldID) . '", ' . json_encode($this->options) . ', ' . json_encode($this->fixed) . ');');

        return $html;
    }

    protected function renderOptions($options) {
        $html = '';
        if (!$this->fixed) {
            $html .= '<option value="0" selected="selected">' . n2_('Choose') . '</option>';
        }
        foreach ($options as $value => $option) {
            $html .= '<option ' . $this->isSelected($value) . ' value="' . $value . '">' . $option['label'] . '</option>';
        }

        return $html;
    }

    /**
     * @param bool $fixed
     */
    public function setFixed($fixed) {
        $this->fixed = $fixed;
    }
}