<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Helper;


use Nextend\Framework\Sanitize;

class MenuItem {

    protected $html = '';

    protected $isActive = false;

    public function __construct($html, $isActive = false) {

        $this->html     = $html;
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->isActive;
    }

    public function display() {
        echo wp_kses($this->html, Sanitize::$adminTemplateTags);
    }
}