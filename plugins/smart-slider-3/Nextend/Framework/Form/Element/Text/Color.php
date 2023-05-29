<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Text;

class Color extends Text {

    protected $alpha = false;

    protected $class = 'n2_field_color ';

    protected function fetchElement() {

        if ($this->alpha) {
            $this->class .= 'n2_field_color--alpha ';
        }

        $html = parent::fetchElement();
        Js::addInline('new _N2.FormElementColor("' . $this->fieldID . '", ' . intval($this->alpha) . ');');

        return $html;
    }

    protected function pre() {

        return '<div class="n2-field-color-preview n2_checker_box"><div class="n2-field-color-preview-inner"></div></div>';
    }

    protected function post() {
        return '';
    }

    /**
     * @param boolean $alpha
     */
    public function setAlpha($alpha) {
        $this->alpha = $alpha;
    }
}