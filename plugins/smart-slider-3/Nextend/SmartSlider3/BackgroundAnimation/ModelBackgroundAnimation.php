<?php


namespace Nextend\SmartSlider3\BackgroundAnimation;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Fieldset\FieldsetVisualSet;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Visual\ModelVisual;

class ModelBackgroundAnimation extends ModelVisual {

    protected $type = 'backgroundanimation';

    protected function init() {

        BackgroundAnimationStorage::getInstance();

        $this->storage = StorageSectionManager::getStorage('smartslider');
    }

    public function renderSetsForm() {

        $form = new Form($this, $this->type . 'set');
        $form->addClass('n2_fullscreen_editor__content_sidebar_top_bar');
        $form->setDark();

        $setsTab = new FieldsetVisualSet($form->getContainer(), 'backgroundanimation-sets', n2_('Animation type'));
        new Select($setsTab, 'sets', false);

        $form->render();
    }

    public function renderForm() {
        $form = new Form($this, 'n2-background-animation');

        $table = new ContainerTable($form->getContainer(), 'background-animation-preview', n2_('Preview'));

        $table->setFieldsetPositionEnd();

        new Color($table->getFieldsetLabel(), 'color', false, '333333ff', array(
            'alpha' => true
        ));

        $form->render();
    }
}