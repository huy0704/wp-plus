<?php


namespace Nextend\SmartSlider3\Renderable\Item\Vimeo;


use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\Image;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;
use Nextend\SmartSlider3\Settings;

class ItemVimeoFrontend extends AbstractItemFrontend {

    public function render() {
        $owner = $this->layer->getOwner();

        $url = $owner->fill($this->data->get("vimeourl"));

        $urlParts = explode('?', $url);

        $privateID = '';
        if (preg_match('/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $urlParts[0], $matches)) {
            $videoID   = $matches[3];
            $privateID = str_replace($matches, '', $urlParts[0]);
        } else {
            $videoID = preg_replace('/\D/', '', $urlParts[0]);
        }

        $this->data->set("vimeocode", $videoID);

        if (isset($urlParts[1])) {
            $parsedUrl = parse_url('https://player.vimeo.com/video/' . $videoID . '?' . $urlParts[1]);
            parse_str($parsedUrl['query'], $query);
            if (isset($query['h'])) {
                $privateID = $query['h'];
            }
        }

        $this->data->set("privateid", $privateID);

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

        $owner->addScript('new _N2.FrontendItemVimeo(this, "' . $this->id . '", "' . $owner->getElementID() . '", ' . $this->data->toJSON() . ', ' . $hasImage . ', ' . $owner->fill($this->data->get('start', '0')) . ');');

        $aspectRatio = $this->data->get('aspect-ratio', '16:9');
        $style       = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

        return Html::tag('div', array(
            'id'                => $this->id,
            'class'             => 'n2_ss_video_player n2-ss-item-content n2-ow-all',
            'data-aspect-ratio' => $aspectRatio
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . $coverImage);
    }

    public function renderAdminTemplate() {

        $aspectRatio = $this->data->get('aspect-ratio', '16:9');

        $owner = $this->layer->getOwner();

        $style = '';
        if ($aspectRatio == 'custom') {
            $style = 'style="padding-top:' . ($this->data->get('aspect-ratio-height', '9') / $this->data->get('aspect-ratio-width', '16') * 100) . '%"';
        }

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
            $playButtonStyle .= 'height:' . $playButtonHeight . 'px;';
        }

        $playButton = Html::image($playButtonImage, n2_('Play'), Html::addExcludeLazyLoadAttributes(array(
            'class' => 'n2_ss_video_play_btn',
            'style' => $playButtonStyle
        )));

        return Html::tag('div', array(
            "class"             => 'n2_ss_video_player n2-ow-all',
            'data-aspect-ratio' => $aspectRatio,
            "style"             => 'background: URL(' . ResourceTranslator::toUrl($owner->fill($this->data->getIfEmpty('image', '$ss3-frontend$/images/placeholder/video.png'))) . ') no-repeat 50% 50%; background-size: cover;'
        ), '<div class="n2_ss_video_player__placeholder" ' . $style . '></div>' . '<div class="n2_ss_video_player__cover">' . $playButton . '</div>');

    }
}