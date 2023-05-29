<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Tab extends AbstractFieldHidden {

    protected $options = array();
    protected $relatedValueFields = array();

    protected function fetchElement() {

        if (empty($this->defaultValue) && !empty($this->options)) {
            $this->defaultValue = array_keys($this->options)[0];
        }

        $html = Html::openTag("div", array(
            "id"    => $this->fieldID . "_tab",
            "class" => "n2_field_tab",
            "style" => $this->style
        ));

        $html .= $this->renderOptions();

        $html .= Html::closeTag("div");

        $html .= parent::fetchElement();

        Js::addInline('new _N2.FormElementTab("' . $this->fieldID . '", ' . json_encode($this->relatedValueFields) . ');');

        return $html;
    }

    /**
     * @param array $options
     */
    public function setOptions($options) {

        $this->options = $options;
    }

    /**
     * @param $relatedValueFields
     */
    public function setRelatedValueFields($relatedValueFields) {
        $this->relatedValueFields = $relatedValueFields;
    }

    public function renderOptions() {
        $html = '';
        foreach ($this->options as $option => $label) {
            $class = 'n2_field_tab__option';
            if ($option == $this->defaultValue) {
                $class .= ' n2_field_tab__option--selected';
            }
            $html .= Html::openTag("div", array(
                "class"         => $class,
                "data-ssoption" => $option
            ));
            $html .= $label;
            $html .= Html::closeTag("div");
        }

        return $html;
    }
}