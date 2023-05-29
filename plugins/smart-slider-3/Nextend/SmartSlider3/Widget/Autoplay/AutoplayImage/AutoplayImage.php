<?php

namespace Nextend\SmartSlider3\Widget\Autoplay\AutoplayImage;

use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio\ImageListFromFolder;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\Autoplay\AbstractWidgetAutoplay;

class AutoplayImage extends AbstractWidgetAutoplay {

    protected $defaults = array(
        'widget-autoplay-desktop-image-width' => 16,
        'widget-autoplay-tablet-image-width'  => 16,
        'widget-autoplay-mobile-image-width'  => 8,
        'widget-autoplay-play-image'          => '',
        'widget-autoplay-play-color'          => 'ffffffcc',
        'widget-autoplay-play'                => '$ss$/plugins/widgetautoplay/image/image/play/small-light.svg',
        'widget-autoplay-style'               => '{"data":[{"backgroundcolor":"000000ab","padding":"10|*|10|*|10|*|10|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"3","extra":""},{"backgroundcolor":"000000ab"}]}',
        'widget-autoplay-position-mode'       => 'simple',
        'widget-autoplay-position-area'       => 4,
        'widget-autoplay-position-offset'     => 15,
        'widget-autoplay-mirror'              => 1,
        'widget-autoplay-pause-image'         => '',
        'widget-autoplay-pause-color'         => 'ffffffcc',
        'widget-autoplay-pause'               => '$ss$/plugins/widgetautoplay/image/image/pause/small-light.svg'
    );


    public function renderFields($container) {

        $rowIcon = new FieldsetRow($container, 'widget-autoplay-image-row-icon');

        $fieldPlay = new ImageListFromFolder($rowIcon, 'widget-autoplay-play', n2_('Play'), '', array(
            'folder'      => self::getAssetsPath() . '/play/',
            'hasDisabled' => false
        ));

        new Color($rowIcon, 'widget-autoplay-play-color', n2_('Color'), '', array(
            'alpha' => true
        ));

        new Style($rowIcon, 'widget-autoplay-style', n2_('Style'), '', array(
            'mode'    => 'button',
            'preview' => 'SmartSliderAdminWidgetAutoplayImage'
        ));

        $row2 = new FieldsetRow($container, 'widget-autoplay-image-row-2');

        new WidgetPosition($row2, 'widget-autoplay-position', n2_('Position'));

    }

    public function prepareExport($export, $params) {
        $export->addImage($params->get($this->key . 'play-image', ''));
        $export->addImage($params->get($this->key . 'pause-image', ''));

        $export->addVisual($params->get($this->key . 'style'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'play-image', $import->fixImage($params->get($this->key . 'play-image', '')));
        $params->set($this->key . 'pause-image', $import->fixImage($params->get($this->key . 'pause-image', '')));

        $params->set($this->key . 'style', $import->fixSection($params->get($this->key . 'style', '')));
    }
}