<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\SmartSlider3\Renderable\Item\ItemFactory;

class ViewSettingsItemDefaults extends AbstractViewSettings {

    protected $active = 'itemDefaults';

    public function display() {

        parent::display();

        $this->layout->addBreadcrumb(n2_('Layer defaults'), '');

        $this->layout->addContent($this->render('ItemDefaults'));

        $this->layout->render();
    }

    public function renderForm() {

        $form = new Form($this, 'defaults');

        new Token($form->getFieldsetHidden());

        foreach (ItemFactory::getItems() as $item) {
            $item->globalDefaultItemFontAndStyle($form->getContainer());
        }

        $form->render();
    }
}