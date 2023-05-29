<?php


namespace Nextend\SmartSlider3\Renderable\Item\YouTube;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemYouTube extends AbstractItem {

    protected $ordering = 20;

    protected $layerProperties = array(
        "desktopportraitwidth"  => 300,
        "desktopportraitheight" => 'auto'
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'youtube';
    }

    public function getTitle() {
        return 'YouTube';
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--youtube';
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
        return new ItemYouTubeFrontend($this, $id, $itemData, $layer);
    }

    public function getValues() {
        return parent::getValues() + array(
                'code'             => 'qesNtYIBDfs',
                'aspect-ratio'     => '16:9',
                'youtubeurl'       => 'https://www.youtube.com/watch?v=3PPtkRU7D74',
                'image'            => '$ss3-frontend$/images/placeholder/video.png',
                'autoplay'         => 0,
                'ended'            => '',
                'controls'         => 1,
                'defaultimage'     => 'hqdefault',
                'related'          => '1',
                'center'           => 0,
                'loop'             => 0,
                'modestbranding'   => 1,
                'reset'            => 0,
                'start'            => '0',
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
        $data->set('youtubeurl', $slide->fill($data->get('youtubeurl', '')));

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
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-youtube', n2_('General'));
        new Text($settings, 'youtubeurl', n2_('YouTube URL or Video ID'), '', array(
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
                        'item_youtubeaspect-ratio-width',
                        'item_youtubeaspect-ratio-height'
                    )
                ),
                array(
                    'values' => array(
                        'fill'
                    ),
                    'field'  => array(
                        'item_youtubeaspect-ratio-notice'
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


        $misc = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-youtube-misc', n2_('Video settings'));

        new Warning($misc, 'slide-background-notice', sprintf(n2_('Video autoplaying has a lot of limitations made by browsers. %1$sLearn about them.%2$s'), '<a href="https://smartslider.helpscoutdocs.com/article/1919-video-autoplay-handling" target="_blank">', '</a>'));

        new OnOff($misc, 'autoplay', n2_('Autoplay'), 0, array(
            'relatedFieldsOn' => array(
                'item_youtubeautoplay-notice'
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
        new Number($misc, 'end', n2_('End time'), 0, array(
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

        new OnOff($misc, 'loop', n2_x('Loop', 'Video/Audio play'), 0, array(
            'relatedFieldsOff' => array(
                'item_youtubeended'
            )
        ));

        new OnOff($misc, 'reset', n2_('Restart on slide change'), 0, array(
            'tipLabel'       => n2_('Restart on slide change'),
            'tipDescription' => n2_('Starts the video from the beginning when the slide is viewed again.')
        ));

        $display = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-youtube-display', n2_('Display'));
        new OnOff($display, 'controls', n2_('Controls'), 1);
        new OnOff($display, 'center', n2_('Centered'), 0, array(
            'tipLabel'       => n2_('Centered'),
            'tipDescription' => n2_('Scales up and crops the video to cover the whole layer.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1846-youtube-layer#centered'
        ));

        new Select($display, 'related', n2_('Show related videos'), 1, array(
            'options'        => array(
                '0' => n2_('Anywhere'),
                '1' => n2_('Same channel')
            ),
            'tipLabel'       => n2_('Show related videos'),
            'tipDescription' => n2_('YouTube no longer allows hiding the related videos at the end of the video. This setting defines whether the videos should come from the same channel as the video that was just played or from any other channel.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1846-youtube-layer#show-related-videos',
        ));
    }
}