<?php


namespace Nextend\SmartSlider3\Widget\Shadow\ShadowImage;


use Nextend\Framework\Form\Element\Radio\ImageListFromFolder;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Widget\Shadow\AbstractWidgetShadow;

class ShadowImage extends AbstractWidgetShadow {

    protected $defaults = array(
        'widget-shadow-position-mode'  => 'simple',
        'widget-shadow-position-area'  => 12,
        'widget-shadow-position-stack' => 3,
        'widget-shadow-shadow-image'   => '',
        'widget-shadow-shadow'         => '$ss$/plugins/widgetshadow/shadow/shadow/shadow/dark.png'
    );

    public function renderFields($container) {

        $row1        = new FieldsetRow($container, 'widget-shadow-row-1');
        $fieldShadow = new ImageListFromFolder($row1, 'widget-shadow-shadow', n2_('Shadow'), '', array(
            'folder'      => self::getAssetsPath() . '/shadow/',
            'width'       => 582,
            'column'      => 1,
            'hasDisabled' => false
        ));
    }

    public function prepareExport($export, $params) {
        $export->addImage($params->get($this->key . 'shadow-image', ''));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'shadow-image', $import->fixImage($params->get($this->key . 'shadow-image', '')));
    }
}