<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\Html;

abstract class AbstractButton extends AbstractBlock {

    protected $url = '#';

    protected $attributes = array();

    protected $classes = array();

    protected $baseClass = '';

    protected $size = 'medium';

    protected $tabindex = 0;

    public function display() {

        echo wp_kses(Html::link($this->getContent(), $this->getUrl(), $this->getAttributes()), Sanitize::$adminTemplateTags);
    }

    abstract protected function getContent();

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

    /**
     * @param $className
     */
    public function addClass($className) {
        $this->classes[] = $className;
    }

    public function addAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function getAttributes() {

        $classes = array_merge(array($this->baseClass), $this->getClasses());

        return $this->attributes + array('class' => implode(' ', $classes));
    }

    /**
     * @param string $target
     */
    public function setTarget($target) {
        $this->addAttribute('target', $target);
    }

    /**
     * @return array
     */
    public function getClasses() {

        $classes   = $this->classes;
        $classes[] = $this->baseClass . '--' . $this->size;

        return $classes;
    }

    public function setSmall() {
        $this->size = 'small';
    }

    public function setMedium() {
        $this->size = 'medium';
    }

    public function setBig() {
        $this->size = 'big';
    }

    /**
     * @param integer $tabIndex
     */
    public function setTabIndex($tabIndex) {
        $this->tabindex = $tabIndex;

        if ($this->tabindex === 0) {
            unset($this->attributes['tabindex']);
        } else {
            $this->attributes['tabindex'] = $this->tabindex;
        }
    }
}