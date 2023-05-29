<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\View\Html;

class Button extends AbstractField {

    protected $url = '';

    protected $target = '';

    protected $buttonLabel = '';

    protected $classes = array('n2_field_button');

    public function __construct($insertAt, $name = '', $label = '', $buttonLabel = '', $parameters = array()) {
        $this->buttonLabel = $buttonLabel;
        parent::__construct($insertAt, $name, $label, '', $parameters);
    }

    protected function fetchElement() {

        return Html::tag('a', $this->getAttributes(), $this->buttonLabel);
    }

    /**
     * @param $className
     */
    public function addClass($className) {
        $this->classes[] = $className;
    }

    /**
     * @return array
     */
    protected function getAttributes() {
        $attributes = array(
            'id'    => $this->fieldID,
            'class' => implode(' ', $this->classes)
        );

        if (!empty($this->url)) {
            $attributes['href'] = $this->url;
            if (!empty($this->target)) {
                $attributes['target'] = $this->target;
            }
        } else {
            $attributes['href'] = '#';
        }

        return $attributes;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setTarget($target) {
        $this->target = $target;
    }
}