<?php


namespace Nextend\SmartSlider3\BackgroundAnimation\Block\BackgroundAnimationManager;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Visual\AbstractBlockVisual;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonApply;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonCancel;
use Nextend\SmartSlider3\BackgroundAnimation\ModelBackgroundAnimation;

class BlockBackgroundAnimationManager extends AbstractBlockVisual {

    /** @var ModelBackgroundAnimation */
    protected $model;

    /**
     * @return ModelBackgroundAnimation
     */
    public function getModel() {
        return $this->model;
    }

    public function display() {

        $this->model = new ModelBackgroundAnimation($this);

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
            new _N2.BgAnimationManager({
                setsIdentifier: '" . $model->getType() . "set',
                sets: " . json_encode($model->getSets()) . ",
                visuals: {},
                ajaxUrl: '" . $this->createAjaxUrl(array('backgroundanimation/index')) . "'
            });
        ");

        $model->renderForm();
    }
}