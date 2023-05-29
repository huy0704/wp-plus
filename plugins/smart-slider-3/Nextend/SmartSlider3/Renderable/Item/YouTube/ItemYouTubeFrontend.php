<?php

namespace Nextend\SmartSlider3\Renderable\Item\YouTube;

use Nextend\Framework\Data\Data;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\Image;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;
use Nextend\SmartSlider3\Settings;

class ItemYouTubeFrontend extends AbstractItemFrontend {

    public function render() {
        $owner = $this->layer->getOwner();
        /**
         * @var Data
         */
        $this->data->fillDefault(array(
            'image'        => '',
            'aspect-ratio' => '16:9',
            'start'        => 0,
            'volume'       => -1,
            'autoplay'     => 0,
            'ended'        => '',
            'controls'     => 1,
            'center'       => 0,
            'loop'         => 0,
            'reset'        => 0,
            'related'      => 1,
        ));

        $aspectRatio = $this->data->get('aspect-ratio', '16:9');
        if ($aspectRatio != 'fill') {
            $this->data->set('center', 0);
        }

        $rawYTUrl = $owner->fill($this->data->get('youtubeurl', ''));

        $url_parts = parse_url($rawYTUrl);
        if (!empty($url_parts['query'])) {
            parse_str($url_parts['query'], $query);
            if (isset($query['v'])) {
                unset($query['v']);
            }
            $this->data->set("query", $query);
        }

        $youTubeUrl = $this->parseYoutubeUrl($rawYTUrl);

        $start = $owner->fill($this->data->get('start', ''));
        $this->data->set("youtubecode", $youTubeUrl);
        $this->data->set("start", $start);

        $end = $owner->fill($this->data->get('end', ''));
        $this->data->set("youtubecode", $youTubeUrl);
        $this->data->set("end", $end);

        $hasImage      = 0;
        $coverImageUrl = $owner->fill($this->data->get('image'));

        $coverImage = '';
        if (!empty($coverImageUrl)) {

            $coverImageElement = $owner->renderImage($this, $coverImageUrl, array(
                'class' => 'n2_ss_video_cover',
                'alt'   => n2_('Play')
            ), array(
                'class' => 'n2-ow-all'
            ));

            $hasImage  = 1;
            $playImage = '';

            if ($this->data->get('playbutton', 1) == 1) {

                $playWidth  = intval($this->data->get('playbuttonwidth', '48'));
                $playHeight = intval($this->data->get('playbuttonheight', '48'));
                if ($playWidth > 0 && $playHeight > 0) {

                    $attributes = Html::addExcludeLazyLoadAttributes(array(
                        'style' => '',
                        'class' => 'n2_ss_video_play_btn'
                    ));

                    if ($playWidth != 48) {
                        $attributes['style'] .= 'width:' . $playWidth . 'px;';
                    }
                    if ($playHeight != 48) {
                        $attributes['style'] .= 'height:' . $playHeight . 'px;';
                    }

                    $playButtonImage = $this->data->get('playbuttonimage', '');
                    if (!empty($playButtonImage)) {
                        $image = $this->data->get('playbuttonimage', '');
                        FastImageSize::initAttributes($image, $attributes);
                        $src = ResourceTranslator::toUrl($image);
                    } else {
                        $image = '$ss3-frontend$/images/play.svg';
                        FastImageSize::initAttributes($image, $attributes);
                        $src = Image::SVGToBase64($image);
                    }

                    $playImage = Html::image($src, 'Play', $attributes);
                }
            }

            $coverImage = Html::tag('div', array(
                'class'              => 'n2_ss_video_player__cover',
                'data-force-pointer' => ''
            ), $coverImageElement . $playImage);
        }

        $this->data->set('privacy-enhanced', intval(Settings::get('youtube-privacy-enhanced', 0)));

        $owner->addScript('new _N2.FrontendItemYouTube(this, "' . $this->id . '", ' . $this->data->toJSON() . ', ' . $hasImage . ');');

        $style = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

        return Html::tag('div', array(
            'id'                => $this->id,
            'class'             => 'n2_ss_video_player n2-ss-item-content n2-ow-all',
            'data-aspect-ratio' => $aspectRatio
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . Html::tag('div', array(
                'id' => $this->id . '-frame',
            ), '') . $coverImage);
    }

    public function renderAdminTemplate() {

        $aspectRatio = $this->data->get('aspect-ratio', '16:9');

        $style = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

        $image = $this->layer->getOwner()
                             ->fill($this->data->get('image'));
        $this->data->set('image', $image);

        $playButtonImage = $this->data->get('playbuttonimage', '');
        if (!empty($playButtonImage)) {
            $playButtonImage = ResourceTranslator::toUrl($playButtonImage);
        } else {
            $playButtonImage = Image::SVGToBase64('$ss3-frontend$/images/play.svg');
        }

        $playButtonStyle  = '';
        $playButtonWidth  = intval($this->data->get('playbuttonwidth', '48'));
        $playButtonHeight = intval($this->data->get('playbuttonheight', '48'));

        if ($playButtonWidth > 0) {
            $playButtonStyle .= 'width:' . $playButtonWidth . 'px;';
        }
        if ($playButtonHeight > 0) {
            $playButtonStyle .= 'height:' . $playButtonWidth . 'px;';
        }

        $playButton = Html::image($playButtonImage, n2_('Play'), Html::addExcludeLazyLoadAttributes(array(
            'class' => 'n2_ss_video_play_btn',
            'style' => $playButtonStyle
        )));

        return Html::tag('div', array(
            'class'             => 'n2_ss_video_player n2-ow-all',
            'data-aspect-ratio' => $aspectRatio,
            "style"             => 'background: URL(' . ResourceTranslator::toUrl($this->data->getIfEmpty('image', '$ss3-frontend$/images/placeholder/video.png')) . ') no-repeat 50% 50%; background-size: cover;'
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . ($this->data->get('playbutton', 1) ? '<div class="n2_ss_video_player__cover">' . $playButton . '</div>' : ''));

    }

    private function parseYoutubeUrl($youTubeUrl) {
        preg_match('#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube(?:-nocookie)?\.com(?:/embed/|/shorts/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x', $youTubeUrl, $matches);

        if ($matches && isset($matches[1]) && strlen($matches[1]) == 11) {
            return $matches[1];
        }

        return $youTubeUrl;
    }
}