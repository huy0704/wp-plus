<?php


namespace Nextend\Framework\Form;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Insert\AbstractInsert;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

abstract class AbstractField implements ContainedInterface {

    /**
     * @var AbstractField;
     */
    private $previous, $next;

    public function getPrevious() {
        return $this->previous;
    }

    /**
     * @param AbstractField|null $element
     */
    public function setPrevious($element = null) {
        $this->previous = $element;
    }

    public function getNext() {
        return $this->next;
    }

    /**
     * @param AbstractField|null $element
     */
    public function setNext($element = null) {
        $this->next = $element;
        if ($element) {
            $element->setPrevious($this);
        }
    }

    public function remove() {
        $this->getParent()
             ->removeElement($this);
    }

    /**
     * @var TraitFieldset
     */
    protected $parent;

    protected $name = '';

    protected $label = '';

    protected $controlName = '';

    protected $defaultValue;

    protected $fieldID;

    private $exposeName = true;

    protected $tip = '';

    protected $tipLabel = '';

    protected $tipDescription = '';

    protected $tipLink = '';

    protected $rowClass = '';

    protected $rowAttributes = array();

    protected $class = '';

    protected $style = '';

    protected $post = '';

    protected $relatedFields = array();

    protected $relatedFieldsOff = array();

    /**
     * AbstractField constructor.
     *
     * @param TraitFieldset|AbstractInsert $insertAt
     * @param string                       $name
     * @param string                       $label
     * @param string                       $default
     * @param array                        $parameters
     */
    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {

        $this->name  = $name;
        $this->label = $label;

        if ($insertAt instanceof ContainerInterface) {
            $this->parent = $insertAt;
            $this->parent->addElement($this);
        } else if ($insertAt instanceof AbstractInsert) {
            $this->parent = $insertAt->insert($this);
        }

        $this->controlName = $this->parent->getControlName();

        $this->fieldID = $this->generateId($this->controlName . $this->name);

        $this->defaultValue = $default;

        foreach ($parameters as $option => $value) {
            $option = 'set' . $option;
            $this->{$option}($value);
        }
    }

    /**
     * @return string
     */
    public function getID() {
        return $this->fieldID;
    }

    public function setDefaultValue($defaultValue) {
        $this->defaultValue = $defaultValue;
    }

    public function setExposeName($exposeName) {
        $this->exposeName = $exposeName;
    }

    public function getPost() {
        return $this->post;
    }

    public function setPost($post) {
        $this->post = $post;
    }

    /**
     * @param string $tip
     */
    public function setTip($tip) {
        $this->tip = $tip;
    }

    /**
     * @param string $tipLabel
     */
    public function setTipLabel($tipLabel) {
        $this->tipLabel = $tipLabel;
    }

    /**
     * @param string $tipDescription
     */
    public function setTipDescription($tipDescription) {
        $this->tipDescription = $tipDescription;
    }

    /**
     * @param string $tipLink
     */
    public function setTipLink($tipLink) {
        $this->tipLink = $tipLink;
    }

    public function setRowClass($rowClass) {
        $this->rowClass .= $rowClass;
    }

    public function getRowClass() {
        return $this->rowClass;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    protected function getFieldName() {
        if ($this->exposeName) {
            return $this->controlName . '[' . $this->name . ']';
        }

        return '';
    }

    public function render() {

        return array(
            $this->fetchTooltip(),
            $this->fetchElement()
        );
    }

    public function displayLabel() {
        echo wp_kses($this->fetchTooltip(), Sanitize::$adminFormTags);
    }

    public function displayElement() {
        echo wp_kses($this->fetchElement(), Sanitize::$adminFormTags);
    }

    protected function fetchTooltip() {

        if ($this->label === false || $this->label === '') {
            return '';
        }

        $attributes = array(
            'for' => $this->fieldID
        );


        $post = '';
        if (!empty($this->tipDescription)) {
            $tipAttributes = array(
                'class'                => 'ssi_16 ssi_16--info',
                'data-tip-description' => $this->tipDescription
            );
            if (!empty($this->tipLabel)) {
                $tipAttributes['data-tip-label'] = $this->tipLabel;
            }
            if (!empty($this->tipLink)) {
                $tipAttributes['data-tip-link'] = $this->tipLink;
            }
            $post .= Html::tag('i', $tipAttributes);
        }

        return Html::tag('label', $attributes, $this->label) . $post;
    }

    protected function fetchNoTooltip() {
        return "";
    }

    /**
     * @return string
     */
    abstract protected function fetchElement();

    public function getValue() {
        return $this->getForm()
                    ->get($this->name, $this->defaultValue);
    }

    public function setValue($value) {
        $this->parent->getForm()
                     ->set($this->name, $value);
    }

    /**
     * @param array $rowAttributes
     */
    public function setRowAttributes($rowAttributes) {
        $this->rowAttributes = $rowAttributes;
    }

    /**
     * @return array
     */
    public function getRowAttributes() {
        return $this->rowAttributes;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

    protected function getStyle() {
        return $this->style;
    }

    /**
     * @param string $relatedFields
     */
    public function setRelatedFields($relatedFields) {
        $this->relatedFields = $relatedFields;
    }

    public function setRelatedFieldsOff($relatedFieldsOff) {
        $this->relatedFieldsOff = $relatedFieldsOff;
    }

    protected function renderRelatedFields() {
        if (!empty($this->relatedFields) || !empty($this->relatedFieldsOff)) {
            $options = array(
                'relatedFieldsOn'  => $this->relatedFields,
                'relatedFieldsOff' => $this->relatedFieldsOff
            );
            Js::addInline('new _N2.FormRelatedFields("' . $this->fieldID . '", ' . json_encode($options) . ');');
        }
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function generateId($name) {

        return str_replace(array(
            '[',
            ']',
            ' '
        ), array(
            '',
            '',
            ''
        ), $name);
    }

    public function getLabelClass() {
        if ($this->label === false) {
            return 'n2_field--label-none';
        } else if ($this->label === '') {
            return 'n2_field--label-placeholder';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function hasLabel() {
        return !empty($this->label);
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return Form
     */
    public function getForm() {
        return $this->parent->getForm();
    }

    /**
     * @return string
     */
    public function getControlName() {
        return $this->controlName;
    }

    /**
     * @param string $controlName
     */
    public function setControlName($controlName) {
        $this->controlName = $controlName;
    }

    /**
     * @return TraitFieldset
     */
    public function getParent() {
        return $this->parent;
    }

    public function getPath() {
        return $this->parent->getPath() . '/' . $this->name;
    }
}