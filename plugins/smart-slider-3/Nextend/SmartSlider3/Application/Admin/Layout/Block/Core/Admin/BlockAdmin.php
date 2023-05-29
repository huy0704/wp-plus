<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Admin;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\AbstractLayout;
use Nextend\Framework\View\Html;

class BlockAdmin extends AbstractBlock {

    /**
     * @var AbstractLayout
     */
    protected $layout;

    protected $id = 'n2-admin';

    protected $classes = array(
        'n2',
        'n2_admin',
        'n2_admin_ui',
        'fitvidsignore'
    );

    protected $attributes = array();

    protected $header = '';

    protected $subNavigation = '';

    /**
     * @var string
     */
    protected $topBar = '';

    /**
     * @param AbstractLayout $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function displayContent() {
        $this->layout->displayContent();
    }

    public function display() {

        $this->renderTemplatePart('Admin');
    }

    /**
     * @return string
     */
    public function getClass() {

        $this->classes = array_unique($this->classes);

        return implode(' ', $this->classes);
    }

    /**
     * @param array $classes
     */
    public function addClasses($classes) {
        $this->classes += $classes;
    }

    /**
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader($header) {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getSubNavigation() {
        return $this->subNavigation;
    }

    /**
     * @param string $subNavigation
     */
    public function setSubNavigation($subNavigation) {
        $this->subNavigation = $subNavigation;
    }

    public function displayTopBar() {
        echo wp_kses($this->topBar, Sanitize::$adminTemplateTags);
    }

    /**
     * @param string $topBar
     */
    public function setTopBar($topBar) {
        $this->topBar = $topBar;
    }

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function renderAttributes() {

        echo wp_kses(Html::renderAttributes($this->attributes + array(
                'id'    => $this->id,
                'class' => implode(' ', $this->classes)
            )), Sanitize::$adminTemplateTags);
    }
}