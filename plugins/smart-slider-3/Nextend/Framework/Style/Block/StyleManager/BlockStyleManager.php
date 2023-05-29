<?php


namespace Nextend\Framework\Style\Block\StyleManager;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Style\ModelStyle;
use Nextend\Framework\Style\StyleRenderer;
use Nextend\Framework\Visual\AbstractBlockVisual;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonApply;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;

class BlockStyleManager extends AbstractBlockVisual {

    /** @var ModelStyle */
    protected $model;

    /**
     * @return ModelStyle
     */
    public function getModel() {
        return $this->model;
    }

    public function display() {

        $this->model = new ModelStyle($this);

        $this->renderTemplatePart('Index');
    }

    public function displayTopBar() {

        $buttonCancel = new BlockButtonCancel($this);
        $buttonCancel->addClass('n2_fullscreen_editor__cancel');
        $buttonCancel->display();

        $buttonApply = new BlockButtonApply($this);
        $buttonApply->addClass('n2_fullscreen_editor__save');
        $buttonApply->display();
    }

    public function displayContent() {
        $model = $this->getModel();

        Js::addFirstCode("
            _N2.CSSRendererStyle.rendererModes =  " . json_encode(StyleRenderer::$mode) . ";
            _N2.CSSRendererStyle.pre =  " . json_encode(StyleRenderer::$pre) . ";
            new _N2.NextendStyleManager();
        ");

        $model->renderForm();
    }
}