<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow\Settings;


use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Form\Element\Devices;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\LayerWindowFocus;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Gradient;
use Nextend\Framework\Form\Element\Select\LinkTarget;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\FieldImageResponsive;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Element\Text\Url;
use Nextend\Framework\Form\Element\Text\Video;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindow;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindowLabelFields;
use Nextend\SmartSlider3\Form\Element\BackgroundImage;
use Nextend\SmartSlider3\Form\Element\DatePicker;
use Nextend\SmartSlider3\Slider\Admin\AdminSlider;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;

class LayerWindowSettingsSlide extends AbstractLayerWindowSettings {

    /** @var AdminSlider */
    protected $renderableAdminSlider;

    /**
     * LayerWindowSettingsSlide constructor.
     *
     * @param                    $blockLayerWindow
     * @param AdminSlider        $renderableAdminSlider
     */
    public function __construct($blockLayerWindow, $renderableAdminSlider) {
        $this->renderableAdminSlider = $renderableAdminSlider;
        parent::__construct($blockLayerWindow);
    }

    public function getName() {
        return 'slide';
    }

    protected function extendContent() {

        $general = new FieldsetLayerWindow($this->contentContainer, 'fields-slide-general', n2_('General'));
        new Text($general, 'slide-title', n2_('Slide title'), n2_('Slide'), array(
            'style' => 'width:302px;',
        ));
        new Textarea($general, 'slide-description', n2_('Description'), '', array(
            'width' => 314
        ));

        new FieldImage($general, 'slide-thumbnail', n2_('Thumbnail'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'layerslide-thumbnailAlt'
            )
        ));
        new Text($general, 'slide-thumbnailAlt', n2_('Thumbnail alt') . ' [SEO]', '', array(
            'style' => "width:133px;"
        ));


        if (!$this->renderableAdminSlider->getEditedSlide()
                                         ->isStatic()) {
            $link = new FieldsetLayerWindow($this->contentContainer, 'fields-slide-link', n2_('Link'));

            new Url($link, 'slide-href', n2_('Link'), '', array(
                'relatedFields' => array(
                    'layerslide-href-target',
                    'layerslide-aria-label'
                ),
                'width'         => 248
            ));
            new LinkTarget($link, 'slide-href-target', n2_('Target window'));

            new Text($link, 'slide-aria-label', n2_('ARIA label'), '', array(
                'style'    => 'width:190px;',
                'tipLabel' => n2_('ARIA label')
            ));
        }


        if (!$this->renderableAdminSlider->getEditedSlide()
                                         ->isStatic()) {
            SliderTypeFactory::getType($this->renderableAdminSlider->data->get('type'))
                             ->createAdmin()
                             ->renderSlideFields($this->contentContainer);
        }

        if ($this->renderableAdminSlider->getEditedSlide()
                                        ->hasGenerator()) {
            $generator = new FieldsetLayerWindow($this->contentContainer, 'fields-slide-generator', n2_('Generator'));
            new Number($generator, 'slide-slide-generator-slides', n2_('Slides'), 5, array(
                'unit' => n2_x('slides', 'Unit'),
                'wide' => 3
            ));
        }


        $advanced = new FieldsetLayerWindow($this->contentContainer, 'fields-slide-advanced', n2_('Advanced'));

        if ($this->renderableAdminSlider->params->get('global-lightbox', 0)) {
            new FieldImageResponsive($advanced, 'slide-ligthboxImage', n2_('Custom lightbox image'), '', array(
                'width' => 180
            ));
        }

        new OnOff($advanced, 'slide-published', n2_('Published'), 1);

        if (!$this->renderableAdminSlider->getEditedSlide()
                                         ->isStatic() && $this->renderableAdminSlider->params->get('autoplay')) {
            new Number($advanced, 'slide-slide-duration', n2_('Slide duration'), 0, array(
                'unit' => 'ms',
                'wide' => 5
            ));
        }

        if (!$this->renderableAdminSlider->getEditedSlide()
                                         ->isStatic()) {
            new Select($advanced, 'slide-thumbnailType', n2_('Thumbnail type'), 'default', array(
                'options'        => array(
                    'default'   => n2_('Default'),
                    'videoDark' => n2_('Video')
                ),
                'tipLabel'       => n2_('Thumbnail type'),
                'tipDescription' => n2_('If you have a video on your slide, you can put a play icon on the thumbnail image to indicate that.'),
                'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1724-slide#thumbnail-type'
            ));
        }
    }

    protected function extendStyle() {
        if (!$this->renderableAdminSlider->getEditedSlide()
                                         ->isStatic()) {
            $this->background();
        }

        $spacing = new FieldsetLayerWindowLabelFields($this->styleContainer, 'fields-slide-spacing', n2_('Spacing'));

        $padding = new MarginPadding($spacing, 'slide-padding', n2_('Padding'), '10|*|10|*|10|*|10', array(
            'unit'          => 'px',
            'rowAttributes' => array(
                'data-devicespecific' => ''
            )
        ));

        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'slide-padding-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'style'  => 'width: 22px;'
            ));
        }

        new Button($spacing, '-slide-clear-device-specific-changes', n2_('Device specific settings'), n2_('Clear'), array(
            'tipLabel'       => n2_('Clear device specific settings'),
            'tipDescription' => n2_('Erases all device specific changes you made on the current device.'),
        ));
    }

    private function background() {

        $background = new FieldsetLayerWindowLabelFields($this->styleContainer, 'fields-slide-background', n2_('Background'));
        new BackgroundImage($background->getFieldsetLabel(), 'slide-background-type', false, 'color', array(
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'image',
                        'video'
                    ),
                    'field'  => array(
                        'layer-slide-background-image',
                        'layerslide-backgroundColorOverlay',
                        'fieldset-layer-window-fields-slide-seo'
                    )
                ),
                array(
                    'values' => array(
                        'image'
                    ),
                    'field'  => array(
                        'layerslide-kenburns-animation'
                    )
                ),
                array(
                    'values' => array(
                        'video'
                    ),
                    'field'  => array(
                        'layer-slide-background-video'
                    )
                ),
            )
        ));

        $rowImage = new Grouping($background, '-slide-background-image');

        $slideBackgroundAttr = array(
            'width'         => 180,
            'relatedFields' => array(
                'layerslide-background-focus',
                'layerslide-backgroundFocusX',
                'layerslide-backgroundFocusY',
                'layerslide-backgroundImageOpacity',
                'layerslide-backgroundImageBlur',
                'layerslide-backgroundMode',
                'layerslide-background-notice-image',
            )
        );
        $fieldImage = new FieldImageResponsive($rowImage, 'slide-backgroundImage', n2_('Slide background'), '', $slideBackgroundAttr);

        $focusField = new LayerWindowFocus($rowImage, 'slide-background-focus', n2_('Focus'));

        $fieldFocusX = new Number($rowImage, 'slide-backgroundFocusX', false, 50, array(
            'wide'     => 3,
            'sublabel' => 'X',
            'unit'     => '%'
        ));
        $fieldFocusY = new Number($rowImage, 'slide-backgroundFocusY', false, 50, array(
            'wide'     => 3,
            'sublabel' => 'Y',
            'unit'     => '%'
        ));

        $focusField->setFields($fieldImage, $fieldFocusX, $fieldFocusY);

        new Warning($rowImage, 'slide-background-notice-image', sprintf(n2_('Please read %1$sour detailed guide%2$s about setting your own slide background correctly.'), '<a href="https://smartslider.helpscoutdocs.com/article/1922-how-to-set-your-background-image" target="_blank">', '</a>'));


        new Select\FillMode($rowImage, 'slide-backgroundMode', n2_('Fill mode'), 'default', array(
            'useGlobal'          => true,
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'blurfit'
                    ),
                    'field'  => array(
                        'layerslide-backgroundBlurFit'
                    )
                )
            )
        ));
        new NumberSlider($rowImage, 'slide-backgroundBlurFit', n2_('Background blur'), 7, array(
            'unit' => 'px',
            'min'  => 7,
            'max'  => 50,
            'wide' => 3
        ));

        new NumberSlider($rowImage, 'slide-backgroundImageOpacity', n2_('Opacity'), 100, array(
            'unit'  => '%',
            'min'   => 0,
            'max'   => 100,
            'style' => 'width:33px;'
        ));

        new NumberSlider($rowImage, 'slide-backgroundImageBlur', n2_('Blur'), 0, array(
            'unit'  => 'px',
            'min'   => 0,
            'max'   => 50,
            'style' => 'width:33px;'
        ));

        $rowColor = new Grouping($background, '-slide-background-color');

        new Color($rowColor, 'slide-backgroundColor', n2_('Color'), 'ffffff00', array(
            'alpha' => true
        ));

        new Gradient($rowColor, 'slide-backgroundGradient', n2_('Gradient'), 'off', array(
            'relatedFields' => array(
                'layerslide-backgroundColorEnd'
            )
        ));

        new Color($rowColor, 'slide-backgroundColorEnd', n2_('Color end'), 'ffffff00', array(
            'alpha' => true
        ));

        new OnOff($rowColor, 'slide-backgroundColorOverlay', n2_('Overlay'), 0, array(
            'tipLabel'       => n2_('Overlay'),
            'tipDescription' => n2_('Puts the color in front of the image.')
        ));


        $seo = new FieldsetLayerWindowLabelFields($this->styleContainer, 'fields-slide-seo', n2_('SEO'));
        new Text($seo, 'slide-backgroundAlt', n2_('Image alt') . ' [SEO]', '', array(
            'style' => "width:133px;"
        ));
        new Text($seo, 'slide-backgroundTitle', n2_('Image title') . ' [SEO]', '', array(
            'style' => "width:133px;"
        ));
    }
}