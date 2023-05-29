<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class Textarea extends AbstractField {

    protected $width = 200;

    protected $height = 44;

    protected $minHeight = 44;

    protected $classes = array(
        'n2_field_textarea'
    );

    protected function fetchElement() {

        Js::addInline('new _N2.FormElementText("' . $this->fieldID . '");');

        return Html::tag('div', array(
            'class' => implode(' ', $this->classes),
            'style' => $this->style
        ), Html::tag('textarea', array(
            'id'           => $this->fieldID,
            'name'         => $this->getFieldName(),
            'autocomplete' => 'off',
            'style'        => 'width:' . $this->width . 'px;height:' . $this->height . 'px;min-height:' . $this->minHeight . 'px;'
        ), Sanitize::esc_textarea($this->getValue())));
    }

    /**
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = $height;
        if ($this->minHeight > $height) {
            $this->minHeight = $height;
        }
    }

    /**
     * @param int $minHeight
     */
    public function setMinHeight($minHeight) {
        $this->minHeight = $minHeight;
    }

    public function setFieldStyle($style) {

    }
}