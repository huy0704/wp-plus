<?php


namespace Nextend\Framework\Image;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\EmptyArea;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Visual\ModelVisual;

class ModelImage extends ModelVisual {

    protected $type = 'image';

    /** @var ImageStorage */
    protected $storage;

    protected function init() {

        $this->storage = new ImageStorage();
    }


    public function renderForm() {
        $form      = new Form($this, 'n2-image-editor');
        $container = $form->getContainer();

        $desktopTable = new ContainerTable($container, 'desktop', n2_('Desktop'));

        $previewRow = $desktopTable->createRow('desktop-preview');

        new EmptyArea($previewRow, 'desktop-preview', n2_('Preview'));

        $this->renderDeviceTab($container, 'desktop-retina', n2_('Desktop retina'));
        $this->renderDeviceTab($container, 'tablet', n2_('Tablet'));
        $this->renderDeviceTab($container, 'mobile', n2_('Mobile'));

        $form->render();
    }

    /**
     * @param ContainerInterface $container
     */
    private function renderDeviceTab($container, $name, $label) {

        $table = new ContainerTable($container, $name, $label);

        $row1 = $table->createRow('desktop-row-1');

        new FieldImage($row1, $name . '-image', n2_('Image'));

        $previewRow = $table->createRow($name . '-preview');
        new EmptyArea($previewRow, $name . '-preview', n2_('Preview'));

    }

    public function addVisual($image, $visual) {

        $visualId = $this->storage->add($image, $visual);

        $visual = $this->storage->getById($visualId);
        if (!empty($visual)) {
            return $visual;
        }

        return false;
    }

    public function getVisual($image) {
        return $this->storage->getByImage($image);
    }

    public function deleteVisual($id) {
        $visual = $this->storage->getById($id);
        $this->storage->deleteById($id);

        return $visual;
    }

    public function changeVisual($id, $value) {
        if ($this->storage->setById($id, $value)) {
            return $this->storage->getById($id);
        }

        return false;
    }

    public function getVisuals($setId) {
        return $this->storage->getAll();
    }
}