<?php


namespace Nextend\SmartSlider3\Renderable\Component;


use Nextend\Framework\Parser\Common;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\ComponentContainer;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;

class ComponentSlide extends AbstractComponent {

    protected $type = 'slide';

    /**
     * @var Slide
     */
    protected $owner;

    /**
     * ComponentSlide constructor.
     *
     * @param Slide $owner
     * @param       $data
     */
    public function __construct($owner, $data) {
        if (!$owner->underEdit) {
            $data['layers'] = AbstractComponent::translateUniqueIdentifier($data['layers'], false);
        }

        parent::__construct(0, $owner, false, $data);

        $this->container = new ComponentContainer($owner, $this, $data['layers']);
        $this->data->un_set('layers');

        $this->container->addContentLayer($owner, $this);

        $this->upgradeData();

        $devices = $this->owner->getAvailableDevices();

        foreach ($devices as $device) {
            $padding = $this->data->get($device . 'padding');
            if (!empty($padding)) {
                $this->style->add($device, '', 'padding:' . implode('px ', explode('|*|', $padding)) . 'px');
            }
        }
    }

    protected function upgradeData() {

        if ($this->data->get('background-type') == '') {
            $this->data->set('background-type', 'color');
            if ($this->data->get('backgroundVideoMp4')) {
                $this->data->set('background-type', 'video');
            } else if ($this->data->get('backgroundImage')) {
                $this->data->set('background-type', 'image');
            }
        }

        $linkV1 = $this->data->getIfEmpty('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');
            $this->data->un_set('link');
            $this->data->set('href', $link);
            $this->data->set('href-target', $target);
        }
    }

    public function getPlacement() {
        return 'default';
    }

    protected function admin() {
        /**
         * Hide on properties
         */
        $this->createDeviceProperty('', 1);

        $this->createProperty('title', '');
        $this->createProperty('publish_up', '0000-00-00 00:00:00');
        $this->createProperty('publish_down', '0000-00-00 00:00:00');
        $this->createProperty('published', 1);
        $this->createProperty('description', '');
        $this->createProperty('thumbnail', '');
        $this->createProperty('thumbnailAlt', '');
        $this->createProperty('thumbnailType', 'default');

        $this->createProperty('static-slide', 0);
        $this->createProperty('slide-duration', 0);
        $this->createProperty('ligthboxImage', '');

        $this->createProperty('record-slides', 0);

        SliderTypeFactory::getType($this->owner->getSlider()->data->get('type'))
                         ->createAdmin()
                         ->registerSlideAdminProperties($this);

        $this->createProperty('href', '');
        $this->createProperty('href-target', '');
        $this->createProperty('aria-label', '');


        $this->createProperty('background-type', 'color');

        $this->createProperty('backgroundColor', 'ffffff00');
        $this->createProperty('backgroundGradient', 'off');
        $this->createProperty('backgroundColorEnd', 'ffffff00');
        $this->createProperty('backgroundColorOverlay', 0);

        $this->createProperty('backgroundImage', '');
        $this->createProperty('backgroundFocusX', 50);
        $this->createProperty('backgroundFocusY', 50);
        $this->createProperty('backgroundImageOpacity', 100);
        $this->createProperty('backgroundImageBlur', 0);
        $this->createProperty('backgroundAlt', '');
        $this->createProperty('backgroundTitle', '');
        $this->createProperty('backgroundMode', 'default');
        $this->createProperty('backgroundBlurFit', 7);


        $this->createProperty('backgroundVideoMp4', '');
        $this->createProperty('backgroundVideoOpacity', 100);
        $this->createProperty('backgroundVideoLoop', 1);
        $this->createProperty('backgroundVideoReset', 1);
        $this->createProperty('backgroundVideoMode', 'fill');

        $this->createDeviceProperty('padding', '10|*|10|*|10|*|10');
    }

    public function render($isAdmin) {
        $this->attributes['data-sstype'] = $this->type;

        $this->placement->attributes($this->attributes);

        $this->serveLocalStyle();

        if ($isAdmin) {
            $this->admin();
        }

        $uniqueClass = $this->data->get('uniqueclass', '');
        if (!empty($uniqueClass)) {
            $this->addUniqueClass($uniqueClass . $this->owner->unique);
        }

        return Html::tag('div', $this->attributes, parent::renderContainer($isAdmin));
    }
}