<?php


namespace Nextend\Framework\Form\Element\Button;


use Nextend\Framework\Form\Element\Button;

class ButtonIcon extends Button {

    protected $hoverTip = '';

    public function __construct($insertAt, $name = '', $label = '', $icon = '', $parameters = array()) {

        $this->classes[] = 'n2_field_button--icon';
        parent::__construct($insertAt, $name, $label, '<i class="' . $icon . '"></i>', $parameters);
    }

    protected function getAttributes() {
        $attributes = parent::getAttributes();

        if (!empty($this->hoverTip)) {
            $attributes['data-n2tip'] = $this->hoverTip;
        }

        return $attributes;
    }

    /**
     * @param string $hoverTip
     */
    public function setHoverTip($hoverTip) {
        $this->hoverTip = $hoverTip;
    }
}