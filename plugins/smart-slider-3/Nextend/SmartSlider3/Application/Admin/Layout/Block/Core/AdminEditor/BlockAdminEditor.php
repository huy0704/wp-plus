<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminEditor;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\EditorOverlay\BlockEditorOverlay;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutEditor;

class BlockAdminEditor extends AbstractBlock {

    /**
     * @var LayoutEditor
     */
    protected $layout;


    protected $id = 'n2-admin';

    protected $classes = array(
        'n2',
        'n2_admin',
        'n2_admin_ui',
        'n2_admin_editor',
        'fitvidsignore'
    );

    protected $attributes = array();

    /**
     * @var BlockEditorOverlay
     */
    protected $editorOverlay;

    /**
     * @param LayoutEditor $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function displayContent() {
        $this->layout->displayContent();
    }

    public function display() {

        $this->renderTemplatePart('AdminEditor');
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

    public function displayEditorOverlay() {
        $this->editorOverlay->display();
    }

    /**
     * @param BlockEditorOverlay $editorOverlay
     */
    public function setEditorOverlay($editorOverlay) {
        $this->editorOverlay = $editorOverlay;
    }
}