<?php


namespace Nextend\Framework\Image\Block\ImageManager;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Image\ImageManager;
use Nextend\Framework\Image\ModelImage;
use Nextend\Framework\Visual\AbstractBlockVisual;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonApply;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class BlockImageManager extends AbstractBlockVisual {

    use TraitAdminUrl;

    /** @var ModelImage */
    protected $model;

    /**
     * @return ModelImage
     */
    public function getModel() {
        return $this->model;
    }

    public function display() {

        $this->model = new ModelImage($this);

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
        Js::addFirstCode("
            new _N2.NextendImageManager({
                visuals: " . json_encode(ImageManager::$loaded) . ",
                ajaxUrl: '" . $this->getAjaxUrlImage() . "'
            });
        ");

        $this->getModel()
             ->renderForm();
    }
}