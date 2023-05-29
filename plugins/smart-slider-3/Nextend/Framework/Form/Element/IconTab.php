<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class IconTab extends AbstractFieldHidden {

    protected $options = array();
    protected $relatedValueFields = array();
    protected $relatedAttribute = '';
    protected $tooltips = array();

    protected function fetchElement() {

        $value = $this->getValue();
        if (!empty($value)) {
            $this->defaultValue = $value;
        } else if (empty($this->defaultValue)) {
            $this->defaultValue = array_keys($this->options)[0];
        }

        $html = Html::openTag("div", array(
            "id"    => $this->fieldID . "_icon_tab",
            "class" => "n2_field_icon_tab",
            "style" => $this->style
        ));

        $html .= $this->renderOptions();

        $html .= Html::closeTag("div");

        $html .= parent::fetchElement();

        if (!empty($this->relatedAttribute)) {
            $options['relatedAttribute'] = $this->relatedAttribute;
        }

        $options = array();

        if (!empty($this->relatedValueFields)) {
            $options['relatedValueFields'] = $this->relatedValueFields;
        }

        Js::addInline('new _N2.FormElementIconTab("' . $this->fieldID . '", ' . json_encode($options) . ');');

        return $html;
    }

    /**
     * @param array $options
     */
    public function setOptions($options) {

        $this->options = $options;
    }

    /**
     * @param array $tooltips
     */
    public function setTooltips($tooltips) {

        $this->tooltips = $tooltips;
    }

    /**
     * @param $relatedValueFields
     */
    public function setRelatedValueFields($relatedValueFields) {
        $this->relatedValueFields = $relatedValueFields;
    }

    public function renderOptions() {
        $html = '';
        foreach ($this->options as $option => $icon) {
            $class = 'n2_field_icon_tab__option';
            if ($option == $this->defaultValue) {
                $class .= ' n2_field_icon_tab__option--selected';
            }

            $element = array(
                "class"         => $class,
                "data-ssoption" => $option
            );

            if (isset($this->tooltips[$option])) {
                $element += array(
                    "data-n2tip" => $this->tooltips[$option]
                );

            }
            $html .= Html::openTag("div", $element);
            $html .= Html::openTag("i", array(
                "class" => $icon
            ));
            $html .= Html::closeTag("i");
            $html .= Html::closeTag("div");
        }

        return $html;
    }
}