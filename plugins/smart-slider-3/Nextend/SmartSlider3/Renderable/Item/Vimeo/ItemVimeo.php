<?php


namespace Nextend\SmartSlider3\Renderable\Item\Vimeo;


use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemVimeo extends AbstractItem {

    protected $ordering = 20;

    protected $layerProperties = array(
        "desktopportraitwidth"  => 300,
        "desktopportraitheight" => 'auto'
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'vimeo';
    }

    public function getTitle() {
        return 'Vimeo';
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--vimeo';
    }

    public function getGroup() {
        return n2_x('Media', 'Layer group');
    }

    /**
     * @param Data $data
     */
    public function upgradeData($data) {
        if (!$data->has('aspect-ratio')) {
            $data->set('aspect-ratio', 'fill');
        }
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemVimeoFrontend($this, $id, $itemData, $layer);
    }

    public function getValues() {
        return parent::getValues() + array(
                'vimeourl'         => '75251217',
                'privateid'        => '',
                'image'            => '$ss3-frontend$/images/placeholder/video.png',
                'aspect-ratio'     => '16:9',
                'autoplay'         => 0,
                'ended'            => '',
                'title'            => 1,
                'byline'           => 1,
                'portrait'         => 0,
                'color'            => '00adef',
                'loop'             => 0,
                'start'            => 0,
                'playbutton'       => 1,
                'playbuttonwidth'  => 48,
                'playbuttonheight' => 48,
                'playbuttonimage'  => '',
                'scroll-pause'     => 'partly-visible',
            );
    }


    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('image', $slide->fill($data->get('image', '')));
        $data->set('vimeourl', $slide->fill($data->get('vimeourl', '')));

        return $data;
    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addImage($data->get('image'));
        $export->addImage($data->get('playbuttonimage'));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('image', $import->fixImage($data->get('image')));
        $data->set('playbuttonimage', $import->fixImage($data->get('playbuttonimage')));

        return $data;
    }

    public function prepareSample($data) {
        $data->set('image', ResourceTranslator::toUrl($data->get('image')));

        return $data;
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-vimeo', n2_('General'));

        new Text($settings, 'vimeourl', n2_('Vimeo url or Video ID'), '', array(
            'style' => 'width:302px;'
        ));

        new FieldImage($settings, 'image', n2_('Cover image'), '', array(
            'width' => 220
        ));

        new Select($settings, 'aspect-ratio', n2_('Aspect ratio'), '16:9', array(
            'options'            => array(
                '16:9'   => '16:9',
                '16:10'  => '16:10',
                '4:3'    => '4:3',
                'custom' => n2_('Custom'),
                'fill'   => n2_('Fill layer height')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'custom'
                    ),
                    'field'  => array(
                        'item_vimeoaspect-ratio-width',
                        'item_vimeoaspect-ratio-height'
                    )
                ),
                array(
                    'values' => array(
                        'fill'
                    ),
                    'field'  => array(
                        'item_vimeoaspect-ratio-notice'
                    )
                )
            )
        ));

        new Text\Number($settings, 'aspect-ratio-width', n2_('Width'), '16', array(
            'wide' => 4,
            'min'  => 1
        ));

        new Text\Number($settings, 'aspect-ratio-height', n2_('Height'), '9', array(
            'wide' => 4,
            'min'  => 1
        ));

        new Notice($settings, 'aspect-ratio-notice', n2_('Fill layer height'), n2_('Set on Style tab.'));

        $misc = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-vimeo-misc', n2_('Video settings'));

        new Warning($misc, 'slide-background-notice', sprintf(n2_('Video autoplaying has a lot of limitations made by browsers. %1$sLearn about them.%2$s'), '<a href="https://smartslider.helpscoutdocs.com/article/1919-video-autoplay-handling" target="_blank">', '</a>'));

        new OnOff($misc, 'autoplay', n2_('Autoplay'), 0, array(
            'relatedFieldsOn' => array(
                'item_vimeoautoplay-notice'
            )
        ));

        new Select($misc, 'ended', n2_('When ended'), '', array(
            'options' => array(
                ''     => n2_('Do nothing'),
                'next' => n2_('Go to next slide')
            )
        ));

        new Number($misc, 'start', n2_('Start time'), 0, array(
            'min'  => 0,
            'unit' => 'sec',
            'wide' => 5
        ));

        new Select($misc, 'volume', n2_('Volume'), 1, array(
            'options' => array(
                '0'    => n2_('Mute'),
                '0.25' => '25%',
                '0.5'  => '50%',
                '0.75' => '75%',
                '1'    => '100%',
                '-1'   => n2_('Default')
            )
        ));

        new Select($misc, 'scroll-pause', n2_('Pause on scroll'), 'partly-visible', array(
            'options'        => array(
                ''               => n2_('Never'),
                'partly-visible' => n2_('When partly visible'),
                'not-visible'    => n2_('When not visible'),
            ),
            'tipLabel'       => n2_('Pause on scroll'),
            'tipDescription' => n2_('You can pause the video when the visitor scrolls away from the slider')
        ));

        new OnOff($misc, 'reset', n2_('Restart on slide change'), 0, array(
            'tipLabel'       => n2_('Restart on slide change'),
            'tipDescription' => n2_('Starts the video from the beginning when the slide is viewed again.')
        ));

        $display = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-youtube-display', n2_('Display'));
        new Color($display, 'color', n2_('Color'), 0, array(
            'tipLabel'       => n2_('Color'),
            'tipDescription' => n2_('Only works on videos of Vimeo Pro users.')
        ));

        new OnOff($display, 'background', n2_('Remove controls'), 0, array(
            'tipLabel'       => n2_('Remove controls'),
            'tipDescription' => n2_('Removes the controls of the video, but it only works on videos of Vimeo Pro users.')
        ));

        new OnOff($display, 'title', n2_('Title'), 1, array(
            'tipLabel'       => n2_('Title'),
            'tipDescription' => n2_('Hides the title of the video, but only if video owner allows it.')
        ));
        new OnOff($display, 'byline', n2_('Users byline'), 1, array(
            'tipLabel'       => n2_('Users byline'),
            'tipDescription' => n2_('Hides the user\'s byline of the video, but only if video owner allows it.')
        ));
        new OnOff($display, 'portrait', n2_('Portrait'), 1, array(
            'tipLabel'       => n2_('Portrait'),
            'tipDescription' => n2_('Hides the profile image of the author, but only if video owner allows it. ')
        ));
        new Select($display, 'quality', n2_('Quality'), '-1', array(
            'options'        => array(
                '270p'  => '270p',
                '360p'  => '360p',
                '720p'  => '720p',
                '1080p' => '1080p',
                '-1'    => n2_('Default')
            ),
            'tipLabel'       => n2_('Quality'),
            'tipDescription' => n2_('Only works on videos of Vimeo Pro users.')
        ));
    }

}