<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\Html;

class BlockFloatingMenuItem extends AbstractBlock {

    protected $label = '';

    protected $url = '#';

    protected $icon = '';

    protected $isActive = false;

    protected $attributes = array();

    protected $classes = array(
        'n2_floating_menu__item'
    );

    protected $color = 'grey';

    public function display() {

        $label = '';
        if (!empty($this->icon)) {
            $label .= '<i class="' . $this->icon . '"></i>';
        }
        $label .= '<div class="n2_floating_menu__item_label">' . $this->label . '</div>';

        echo wp_kses(Html::link($label, $this->url, $this->attributes + array('class' => implode(' ', $this->getClasses()))), Sanitize::$adminTemplateTags);
    }

    /**
     * @param string $label
     */
    public function setLabel($label) {
        $this->label = $label;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function addAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }

    /**
     * @param string $target
     */
    public function setTarget($target) {
        $this->addAttribute('target', $target);
    }

    public function setRed() {
        $this->color = 'red';
    }

    public function setGrey() {
        $this->color = 'grey';
    }

    public function getClasses() {

        $classes = $this->classes;

        if ($this->isActive) {
            $classes[] = 'n2_floating_menu__item--active';
        }

        $classes[] = 'n2_floating_menu__item--' . $this->color;

        return $classes;
    }

    public function setState($state) {
        $this->attributes['data-state'] = $state;
    }

    public function setStayOpen() {
        $this->addAttribute('data-stay-open', 1);
    }
}