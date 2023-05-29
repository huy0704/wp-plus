<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class MenuItem {

    protected $isActive = false;

    protected $label = '';

    protected $url = '#';

    protected $classes = array(
        'n2_header__menu_item'
    );

    protected $attributes = array();

    public function __construct($label) {
        $this->label = $label;
    }

    public function getHtml() {
        $attributes = $this->attributes;

        if ($this->isActive) {
            $this->classes[] = 'n2_header__menu_item--active';
        }

        if (!empty($this->classes)) {
            $attributes['class'] = implode(' ', array_unique($this->classes));
        }

        return Html::link($this->label, $this->url, $attributes);
    }

    public function display() {
        echo wp_kses($this->getHtml(), Sanitize::$adminTemplateTags);
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label) {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }
}