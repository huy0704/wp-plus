<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Select extends AbstractFieldHidden {

    public $value;

    protected $values = array();
    protected $options = array();
    protected $optgroup = array();
    protected $isMultiple = false;
    protected $size = '';

    protected $relatedValueFields = array();
    protected $relatedAttribute = '';

    protected function fetchElement() {

        $this->values = explode('||', $this->getValue());
        if (!is_array($this->values)) {
            $this->values = array();
        }

        $html = Html::openTag("div", array(
            "class" => "n2_field_select",
            "style" => $this->style
        ));

        $selectAttributes = array(
            'id'              => $this->fieldID . '_select',
            'name'            => 'select' . $this->getFieldName(),
            'aria-labelledby' => $this->fieldID,
            'autocomplete'    => 'off'
        );

        if (!empty($this->size)) {
            $selectAttributes['size'] = $this->size;
        }

        if ($this->isMultiple) {
            $selectAttributes['multiple'] = 'multiple';
            $selectAttributes['class']    = 'nextend-element-hastip';
            $selectAttributes['title']    = n2_('Hold down the ctrl (Windows) or command (MAC) button to select multiple options.');
        }

        $html .= Html::tag('select', $selectAttributes, $this->renderOptions($this->options) . (!empty($this->optgroup) ? $this->renderOptgroup() : ''));

        $html .= Html::closeTag("div");

        $html .= parent::fetchElement();

        $options = array();

        if (!empty($this->relatedFields)) {
            $options['relatedFields'] = $this->relatedFields;
        }

        if (!empty($this->relatedValueFields)) {
            $options['relatedValueFields'] = $this->relatedValueFields;
        }

        if (!empty($this->relatedAttribute)) {
            $options['relatedAttribute'] = $this->relatedAttribute;
        }

        Js::addInline('new _N2.FormElementList("' . $this->fieldID . '", ' . json_encode($options) . ');');

        return $html;
    }

    /**
     *
     * @return string
     */
    protected function renderOptgroup() {
        $html = '';
        foreach ($this->optgroup as $label => $options) {
            if (is_array($options)) {
                $html .= "<optgroup label='" . $label . "'>";
                $html .= $this->renderOptions($options);
                $html .= "</optgroup>";
            } else {
                $html .= $this->renderOption($label, $options);
            }
        }

        return $html;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function renderOptions($options) {
        $html = '';
        foreach ($options as $value => $label) {
            $html .= $this->renderOption($value, $label);
        }

        return $html;
    }

    protected function renderOption($value, $label) {

        return '<option value="' . esc_attr($value) . '" ' . $this->isSelected($value) . '>' . $label . '</option>';
    }

    protected function isSelected($value) {
        if (in_array($value, $this->values)) {
            return ' selected="selected"';
        }

        return '';
    }

    /**
     * @param array $options
     */
    public function setOptions($options) {

        $this->options = $options;
    }

    /**
     * @param array $optgroup
     */
    public function setOptgroup($optgroup) {
        $this->optgroup = $optgroup;
    }

    /**
     * @param bool $isMultiple
     */
    public function setIsMultiple($isMultiple) {
        $this->isMultiple = $isMultiple;
        $this->size       = 10;
    }

    /**
     * @param string $size
     */
    public function setSize($size) {
        $this->size = $size;
    }

    protected function createTree(&$list, &$new, $parent, $cindent = '', $indent = '- ') {

        if (isset($new[$parent])) {
            for ($i = 0; $i < count($new[$parent]); $i++) {
                $new[$parent][$i]->treename = $cindent . $new[$parent][$i]->name;
                $list[]                     = $new[$parent][$i];
                $this->createTree($list, $new, $new[$parent][$i]->cat_ID, $cindent . $indent, $indent);
            }
        }

        return $list;
    }

    public function setRelatedValueFields($relatedValueFields) {
        $this->relatedValueFields = $relatedValueFields;
    }

    public function setRelatedAttribute($relatedAttribute) {
        $this->relatedAttribute = $relatedAttribute;
    }
}