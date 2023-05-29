<?php


namespace Nextend\Framework\Font\Block\FontManager;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontRenderer;
use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Font\ModelFont;
use Nextend\Framework\Visual\AbstractBlockVisual;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonApply;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;

class BlockFontManager extends AbstractBlockVisual {

    /** @var ModelFont */
    protected $model;

    /**
     * @return ModelFont
     */
    public function getModel() {
        return $this->model;
    }

    public function display() {

        $this->model = new ModelFont($this);

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
            _N2.CSSRendererFont.defaultFamily = " . json_encode(FontSettings::getDefaultFamily()) . ";
            _N2.CSSRendererFont.rendererModes = " . json_encode(FontRenderer::$mode) . ";
            _N2.CSSRendererFont.pre = " . json_encode(FontRenderer::$pre) . ";
            new _N2.NextendFontManager();
        ");

        $model->renderForm();
    }
}