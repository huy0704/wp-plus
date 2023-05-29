<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminIframe;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\AbstractLayout;
use Nextend\Framework\View\Html;

class BlockAdminIframe extends AbstractBlock {

    /**
     * @var AbstractLayout
     */
    protected $layout;

    protected $id = 'n2-admin';

    protected $classes = array(
        'n2',
        'n2_admin',
        'n2_admin_ui',
        'n2_iframe_application',
        'fitvidsignore'
    );

    protected $attributes = array();

    protected $label = '';

    /**
     * @var AbstractBlock[]
     */
    protected $actions = array();

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

        $this->renderTemplatePart('AdminIframe');
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
     * @return AbstractBlock[]
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @param AbstractBlock[] $actions
     */
    public function setActions($actions) {
        $this->actions = $actions;
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