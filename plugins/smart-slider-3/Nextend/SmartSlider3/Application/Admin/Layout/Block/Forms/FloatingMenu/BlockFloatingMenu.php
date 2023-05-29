<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\AbstractButton;

class BlockFloatingMenu extends AbstractBlock {

    /**
     * @var AbstractButton
     */
    protected $button;

    protected $classes = array(
        'n2_popover',
        'n2_floating_menu'
    );

    protected $attributes = array();

    /**
     * @var AbstractBlock[]
     */
    protected $menuItems = array();

    protected $contentID;

    public function display() {
        $this->renderTemplatePart('FloatingMenu');
    }

    public function displayButton() {
        $this->button->display();
    }

    /**
     * @param AbstractButton $button
     */
    public function setButton($button) {

        $button->setTabIndex(-1);
        $button->addClass('n2_floating_menu__button n2_popover__trigger');
        $this->button = $button;
    }

    /**
     * @param AbstractBlock $item
     */
    public function addMenuItem($item) {
        $this->menuItems[] = $item;
    }

    public function addSeparator($classes = array()) {

        $separator = new BlockFloatingMenuItemSeparator($this);
        $separator->setclasses($classes);
        $this->menuItems[] = $separator;
    }

    /**
     * @return AbstractBlock[]
     */
    public function getMenuItems() {
        return $this->menuItems;
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }

    public function getClasses() {

        return $this->classes;
    }

    /**
     * @return mixed
     */
    public function getContentID() {
        return $this->contentID;
    }

    /**
     * @param mixed $contentID
     */
    public function setContentID($contentID) {
        $this->contentID = $contentID;
    }

    public function renderAttributes() {

        echo wp_kses(Html::renderAttributes($this->attributes + array(
                'class' => implode(' ', $this->classes)
            )), Sanitize::$adminTemplateTags);
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function setLeft() {
        $this->setAttribute('data-horizontal', 'left');
    }

    public function setRight() {
        $this->setAttribute('data-horizontal', 'right');
    }

    public function setAbove() {
        $this->setAttribute('data-vertical', 'above');
    }

    public function setBelow() {
        $this->setAttribute('data-vertical', 'below');
    }

    public function setRelatedClass($selector) {
        $this->setAttribute('data-relatedclass', $selector);
    }
}

Js::addInline('_N2.r(\'$\', function () {_N2.$(".n2_floating_menu").nextendPopover();});');