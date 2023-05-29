<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Helper;


use Nextend\Framework\Sanitize;

class Breadcrumb {

    protected $label = '';
    protected $icon = '';
    protected $url = '#';

    protected $isActive = false;

    protected $classes = array('n2_breadcrumbs__breadcrumb_button');

    public function __construct($label, $icon, $url = '#') {

        $this->label = $label;
        $this->icon  = $icon;
        $this->url   = $url;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->isActive;
    }

    public function display() {
        $html = '';
        if (!empty($this->icon)) {
            $html .= '<i class="' . $this->icon . '"></i>';
        }

        $html .= '<span>' . $this->label . '</span>';

        if ($this->url == '#') {
            echo wp_kses('<div class="' . $this->getClass() . '">' . $html . '</div>', Sanitize::$adminTemplateTags);
        } else {
            echo wp_kses('<a class="' . $this->getClass() . '" href="' . $this->url . '">' . $html . '</a>', Sanitize::$adminTemplateTags);
        }
    }

    protected function getClass() {

        return implode(' ', $this->classes);
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }
}