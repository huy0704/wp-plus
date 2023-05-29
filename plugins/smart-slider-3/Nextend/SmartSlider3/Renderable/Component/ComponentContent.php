<?php


namespace Nextend\SmartSlider3\Renderable\Component;


use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\ComponentContainer;

class ComponentContent extends AbstractComponent {

    protected $type = 'content';

    protected $name = 'Content';

    protected $colAttributes = array(
        'class' => 'n2-ss-section-main-content n2-ss-layer-with-background n2-ss-layer-content n2-ow',
        'style' => ''
    );

    protected $localStyle = array(
        array(
            "group"    => "normal",
            "selector" => '-inner',
            "css"      => array()
        ),
        array(
            "group"    => "hover",
            "selector" => '-inner:HOVER',
            "css"      => array()
        ),
    );

    public function getPlacement() {
        return 'default';
    }

    public function __construct($index, $owner, $group, $data) {
        parent::__construct($index, $owner, $group, $data);
        $this->container = new ComponentContainer($owner, $this, $data['layers']);
        $this->data->un_set('layers');

        $this->upgradeData();

        $this->attributes['style'] = '';


        $devices = $this->owner->getAvailableDevices();

        $desktopPortraitSelfAlign = $this->data->get('desktopportraitselfalign', 'inherit');

        $desktopportraitInnerAlign = $this->data->get('desktopportraitinneralign', 'inherit');

        foreach ($devices as $device) {
            $padding = $this->data->get($device . 'padding');
            if (!empty($padding)) {
                $paddingValues = $this->spacingToPxValue($padding);

                $this->style->add($device, '-inner', 'padding:' . implode('px ', $paddingValues) . 'px');
            }

            $maxWidth = intval($this->data->get($device . 'maxwidth', 0));
            if ($maxWidth > 0) {
                $this->style->add($device, '', 'max-width: ' . $maxWidth . 'px');
            }

            $innerAlign = $this->data->get($device . 'inneralign', '');

            if ($device == 'desktopportrait') {
                if ($desktopportraitInnerAlign != 'inherit') {
                    $this->style->add($device, '-inner', AbstractComponent::innerAlignToStyle($innerAlign));
                }
            } else if ($desktopportraitInnerAlign != $innerAlign) {
                $this->style->add($device, '-inner', AbstractComponent::innerAlignToStyle($innerAlign));
            }


            $selfAlign = $this->data->get($device . 'selfalign', '');

            if ($device == 'desktopportrait') {
                if ($desktopPortraitSelfAlign != 'inherit') {
                    $this->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
                }
            } else if ($desktopPortraitSelfAlign != $selfAlign) {
                $this->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
            }


            $verticalAlign = $this->data->get($device . 'verticalalign');
            if (!empty($verticalAlign)) {
                $this->style->add($device, '-inner', 'justify-content:' . $verticalAlign);
            }
        }

        $this->renderBackground();

        $this->placement->attributes($this->attributes);

    }

    protected function upgradeData() {

        if ($this->data->has('verticalalign')) {
            /**
             * Upgrade data to device specific
             */
            $this->data->set('desktopportraitverticalalign', $this->data->get('verticalalign'));
            $this->data->un_set('verticalalign');
        }
    }

    public function render($isAdmin) {
        if ($this->isRenderAllowed()) {
            if ($isAdmin || $this->hasBackground || count($this->container->getLayers())) {

                $this->runPlugins();

                $this->serveLocalStyle();
                if ($isAdmin) {
                    $this->admin();
                }

                $this->prepareHTML();

                $this->attributes['data-hasbackground'] = $this->hasBackground ? '1' : '0';

                $html = Html::tag('div', $this->colAttributes, parent::renderContainer($isAdmin));
                $html = $this->renderPlugins($html);

                return Html::tag('div', $this->attributes, $html);
            }
        }

        return '';
    }

    protected function addUniqueClass($class) {
        $this->attributes['class']    .= ' ' . $class;
        $this->colAttributes['class'] .= ' ' . $class . '-inner';
    }

    protected function admin() {


        $this->createDeviceProperty('verticalalign', 'center');
        $this->createDeviceProperty('inneralign', 'inherit');
        $this->createDeviceProperty('selfalign', 'center');
        $this->createDeviceProperty('maxwidth', '0');
        $this->createDeviceProperty('padding', '10|*|10|*|10|*|10');

        $this->createProperty('bgimage', '');
        $this->createProperty('bgimagex', 50);
        $this->createProperty('bgimagey', 50);

        $this->createColorProperty('bgcolor', true, '00000000');
        $this->createProperty('bgcolorgradient', 'off');
        $this->createColorProperty('bgcolorgradientend', true, '00000000');
        $this->createColorProperty('bgcolor-hover', true);
        $this->createProperty('bgcolorgradient-hover');
        $this->createColorProperty('bgcolorgradientend-hover', true);

        $this->createProperty('opened', 1);


        $this->createProperty('id', '');
        $this->createProperty('uniqueclass', '');
        $this->createProperty('class', '');
        $this->createProperty('status');
        $this->createProperty('generatorvisible', '');

        $this->placement->adminAttributes($this->attributes);
    }


    /**
     * @param ExportSlider $export
     * @param array        $layer
     */
    public static function prepareExport($export, $layer) {
        if (!empty($layer['bgimage'])) {
            $export->addImage($layer['bgimage']);
        }

        $export->prepareLayer($layer['layers']);
    }

    public static function prepareImport($import, &$layer) {
        if (!empty($layer['bgimage'])) {
            $layer['bgimage'] = $import->fixImage($layer['bgimage']);
        }

        $import->prepareLayers($layer['layers']);
    }

    public static function prepareSample(&$layer) {
        if (!empty($layer['bgimage'])) {
            $layer['bgimage'] = ResourceTranslator::toUrl($layer['bgimage']);
        }

        ModelSlides::prepareSample($layer['layers']);
    }

    /**
     * @param AbstractRenderableOwner $slide
     * @param array                   $layer
     */
    public static function getFilled($slide, &$layer) {
        AbstractComponent::getFilled($slide, $layer);

        if (!empty($layer['bgimage'])) {
            $layer['bgimage'] = $slide->fill($layer['bgimage']);
        }

        $slide->fillLayers($layer['layers']);
    }
}