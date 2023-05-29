<?php


namespace Nextend\SmartSlider3\Renderable\Component;


use Nextend\Framework\Parser\Color;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Parser\Link;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\ComponentContainer;

class ComponentCol extends AbstractComponent {

    protected $type = 'col';

    protected $colAttributes = array(
        'class' => 'n2-ss-layer-col n2-ss-layer-with-background n2-ss-layer-content',
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

    protected $width;

    public function __construct($index, $owner, $group, $data) {
        parent::__construct($index, $owner, $group, $data);

        $this->container = new ComponentContainer($owner, $this, $data['layers']);
        $this->data->un_set('layers');

        $this->upgradeData();

        $this->attributes['style'] = '';

        $devices = $this->owner->getAvailableDevices();

        $desktopportraitInnerAlign = $this->data->get('desktopportraitinneralign', 'inherit');
        $desktopPortraitMaxWidth   = intval($this->data->get('desktopportraitmaxwidth'));

        foreach ($devices as $device) {

            if ($this->data->has($device . 'padding')) {
                $padding = $this->data->get($device . 'padding');
                if (!empty($padding)) {
                    $paddingValues = $this->spacingToPxValue($padding);

                    $this->style->add($device, '-inner', 'padding:' . implode('px ', $paddingValues) . 'px');
                }
            }

            if ($this->data->has($device . 'maxwidth')) {
                $maxWidth = intval($this->data->get($device . 'maxwidth'));
                if ($maxWidth > 0) {
                    $this->style->add($device, '', 'max-width: ' . $maxWidth . 'px');
                } else if ($device != 'desktopportrait' && $maxWidth != $desktopPortraitMaxWidth) {
                    $this->style->add($device, '', 'max-width: none');
                }
            }

            $innerAlign = $this->data->get($device . 'inneralign', '');

            if ($device == 'desktopportrait') {
                if ($desktopportraitInnerAlign != 'inherit') {
                    $this->style->add($device, '-inner', AbstractComponent::innerAlignToStyle($innerAlign));
                }
            } else if ($desktopportraitInnerAlign != $innerAlign) {
                $this->style->add($device, '-inner', AbstractComponent::innerAlignToStyle($innerAlign));
            }


            $verticalAlign = $this->data->get($device . 'verticalalign');
            if (!empty($verticalAlign)) {
                $this->style->add($device, '-inner', 'justify-content:' . $verticalAlign);
            }


            if ($this->data->has($device . 'order')) {
                $order = intval($this->data->get($device . 'order'));
                if ($order > 0) {
                    $this->style->add($device, '', 'order: ' . $order);
                }
            }
        }

        $this->renderBackground();

        $borderRadius = intval($this->data->get('borderradius', '0'));
        $this->addLocalStyle('normal', 'borderradius', $this->getBorderRadiusCSS($borderRadius));

        $borderRadiusHover = intval($this->data->get('borderradius-hover'));
        if (!empty($borderRadiusHover) && $borderRadiusHover != $borderRadius) {
            $this->addLocalStyle('hover', 'borderradius', $this->getBorderRadiusCSS($borderRadiusHover));
        }


        $boxShadow = $this->data->get('boxshadow', '0|*|0|*|0|*|0|*|00000080');
        $this->addLocalStyle('normal', 'boxshadow', $this->getBoxShadowCSS($boxShadow));

        $boxShadowHover = $this->data->get('boxshadow-hover');
        if (!empty($boxShadowHover) && $boxShadowHover != $boxShadow) {
            $this->addLocalStyle('hover', 'boxshadow', $this->getBoxShadowCSS($boxShadowHover));
        }


        $borderWidth = $this->data->get('borderwidth', '1|*|1|*|1|*|1');
        $borderStyle = $this->data->get('borderstyle', 'none');
        $borderColor = $this->data->get('bordercolor', 'ffffffff');

        if ($borderStyle != 'none') {
            $this->addLocalStyle('normal', 'border', $this->getBorderCSS($borderWidth, $borderStyle, $borderColor));
        }

        $borderWidthHover = $this->data->get('borderwidth-hover');
        $borderStyleHover = $this->data->get('borderstyle-hover');
        $borderColorHover = $this->data->get('bordercolor-hover');
        $isHoverDifferent = false;
        if (!empty($borderWidthHover) || $borderWidthHover != $borderWidth) {
            $isHoverDifferent = true;
        }
        if (!empty($borderStyleHover) || $borderStyleHover != $borderStyle) {
            $isHoverDifferent = true;
        }
        if (!empty($borderColorHover) || $borderColorHover != $borderColor) {
            $isHoverDifferent = true;
        }
        if ($isHoverDifferent) {
            if (empty($borderWidthHover)) $borderWidthHover = $borderWidth;
            if (empty($borderStyleHover)) $borderStyleHover = $borderStyle;
            if (empty($borderColorHover)) $borderColorHover = $borderColor;

            $this->addLocalStyle('hover', 'border', $this->getBorderCSS($borderWidthHover, $borderStyleHover, $borderColorHover));
        }

        $this->placement->attributes($this->attributes);


        $width = explode('/', $this->data->get('colwidth', 1));
        if (count($width) == 2) {
            if ($width[0] == 0 || $width[1] == 0) {
                $width[0] = 1;
                $width[1] = 2;
                $this->data->set('colwidth', '1/2');
            }
            $width = floor($width[0] / $width[1] * 1000) / 10;
        } else {
            $width = 100;
        }

        $this->width = $width;

        if (!AbstractComponent::$isAdmin) {
            $this->makeLink();
        }
    }

    public function setWidth($device) {
        $this->style->add($device, '', 'width:' . $this->width . '%');
    }

    public function setWidthAuto($device) {
        $this->style->add($device, '', 'width:auto');
    }

    public function setWrapAfterWidth($device, $width, $gutter) {
        $this->style->add($device, '', 'width:calc(' . $width . '% - ' . $gutter . 'px)');

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

    public function getPlacement() {
        return 'default';
    }

    private function getBorderRadiusCSS($borderRadius) {
        if ($borderRadius > 0) {
            return 'border-radius:' . $borderRadius . 'px;';
        }

        return '';
    }

    private function getBoxShadowCSS($boxShadow) {
        $boxShadowArray = explode('|*|', $boxShadow);
        if (count($boxShadowArray) == 5 && ($boxShadowArray[0] != 0 || $boxShadowArray[1] != 0 || $boxShadowArray[2] != 0 || $boxShadowArray[3] != 0) && Color::hex2alpha($boxShadowArray[4]) != 0) {
            return 'box-shadow:' . $boxShadowArray[0] . 'px ' . $boxShadowArray[1] . 'px ' . $boxShadowArray[2] . 'px ' . $boxShadowArray[3] . 'px ' . Color::colorToRGBA($boxShadowArray[4]) . ';';
        }

        return '';
    }

    private function getBorderCSS($width, $style, $color) {
        if ($style != 'none') {

            $values    = explode('|*|', $width);
            $unit      = 'px';
            $values[4] = '';
            $css       = 'border-width:' . implode($unit . ' ', $values) . ';';

            $css .= 'border-style:' . $style . ';';
            $css .= 'border-color:' . Color::colorToRGBA($color) . ';';

            return $css;
        }

        return '';
    }

    private function makeLink() {

        $linkV1 = $this->data->get('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');
            $this->data->un_set('link');
            $this->data->set('href', $link);
            $this->data->set('href-target', $target);
        }

        $link = $this->data->get('href');

        if (($link != '#' && !empty($link))) {
            $target = $this->data->get('href-target');

            $link                          = Link::parse($this->owner->fill($link), $this->attributes);
            $this->attributes['data-href'] = $link;
            $this->attributes['tabindex']  = 0;
            $this->attributes['role']      = 'button';

            $ariaLabel = $this->data->get('aria-label');
            if (!empty($ariaLabel)) {
                $this->attributes['aria-label'] = $ariaLabel;
            }

            if (!isset($this->attributes['onclick']) && !isset($this->attributes['data-n2-lightbox'])) {
                if (!empty($target) && $target != '_self') {
                    $this->attributes['data-target'] = $target;
                }
                $this->attributes['data-n2click'] = "url";
            }

            $this->attributes['data-force-pointer'] = "";
        }
    }

    public function render($isAdmin) {
        if ($this->isRenderAllowed()) {

            $this->runPlugins();

            $this->serveLocalStyle();
            if ($isAdmin) {
                $this->admin();
            }
            $this->prepareHTML();

            $html = Html::tag('div', $this->colAttributes, parent::renderContainer($isAdmin));
            $html = $this->renderPlugins($html);

            return Html::tag('div', $this->attributes, $html);
        }

        return '';
    }

    protected function addUniqueClass($class) {
        $this->attributes['class']    .= ' ' . $class;
        $this->colAttributes['class'] .= ' ' . $class . '-inner';
    }

    protected function admin() {

        $linkV1 = $this->data->get('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');
            $this->data->un_set('link');
            $this->data->set('href', $link);
            $this->data->set('href-target', $target);
        }

        $this->createProperty('href', '');
        $this->createProperty('href-target', '_self');
        $this->createProperty('aria-label', '');

        $this->createProperty('colwidth');

        $this->createProperty('bgimage', '');
        $this->createProperty('bgimagex', 50);
        $this->createProperty('bgimagey', 50);
        $this->createColorProperty('bgcolor', true, '00000000');
        $this->createProperty('bgcolorgradient', 'off');
        $this->createColorProperty('bgcolorgradientend', true, '00000000');
        $this->createColorProperty('bgcolor-hover', true);
        $this->createProperty('bgcolorgradient-hover');
        $this->createColorProperty('bgcolorgradientend-hover', true);

        $this->createProperty('borderradius', '0');
        $this->createProperty('borderradius-hover');

        $this->createProperty('boxshadow', '0|*|0|*|0|*|0|*|00000080');
        $this->createProperty('boxshadow-hover');

        $this->createProperty('borderwidth', '1|*|1|*|1|*|1');
        $this->createProperty('borderstyle', 'none');
        $this->createProperty('bordercolor', 'FFFFFFFF');
        $this->createProperty('borderwidth-hover');
        $this->createProperty('borderstyle-hover');
        $this->createProperty('bordercolor-hover');

        $this->createProperty('opened', 1);

        $this->createDeviceProperty('maxwidth', '0');
        $this->createDeviceProperty('padding', '10|*|10|*|10|*|10');
        $this->createDeviceProperty('verticalalign', 'flex-start');
        $this->createDeviceProperty('inneralign', 'inherit');
        $this->createDeviceProperty('order');

        parent::admin();
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

        $fields = array(
            'bgimage',
            'href'
        );

        foreach ($fields as $field) {
            if (!empty($layer[$field])) {
                $layer[$field] = $slide->fill($layer[$field]);
            }
        }

        $slide->fillLayers($layer['layers']);
    }

    public function getOrder($device) {

        $order = intval($this->data->get($device . 'order'));
        if ($order > 0) {
            return $order;
        }

        return 10;
    }

    public function getWidth() {

        return $this->width;
    }

    public static $compareOrderDevice;

    /**
     * @param ComponentCol $column1
     * @param ComponentCol $column2
     *
     * @return int
     */
    public static function compareOrder($column1, $column2) {

        $order1 = $column1->getOrder(self::$compareOrderDevice);
        $order2 = $column2->getOrder(self::$compareOrderDevice);

        if ($order1 == $order2) {
            return 0;
        }

        return ($order1 < $order2) ? -1 : 1;
    }
}