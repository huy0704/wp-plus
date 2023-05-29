<?php


namespace Nextend\SmartSlider3\Renderable\Item\Image;


use Nextend\Framework\Parser\Common;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemImageFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml() {
        $owner = $this->layer->getOwner();

        $styles     = array();
        $linkStyles = array();

        $size = (array)Common::parse($this->data->get('size', ''));
        for ($i = 0; $i < 2; $i++) {
            if (is_numeric($size[$i])) {
                $size[$i] = $size[$i] . 'px';
            }
        }

        if (!empty($size[0]) && $size[0] != 'auto') {
            $styles[] = 'width:' . $size[0];
            if ($this->hasLink() && substr($size[0], -1) == '%') {
                $linkStyles[] = 'width:100%';
            }
            if (empty($size[1]) || $size[1] == 'auto') {
                $styles[] = 'height:auto';
            }
        }
        if (!empty($size[1]) && $size[1] != 'auto') {
            if (empty($size[0]) || $size[0] == 'auto') {
                $styles[] = 'width:auto';
            }
            $styles[] = 'height:' . $size[1];
        }

        $imageUrl = $this->data->get('image', '');

        if (empty($imageUrl)) {

            return '';
        }

        $image = $owner->fill($this->data->get('image', ''));

        $imageAttributes = array(
            "id"    => $this->id,
            "alt"   => htmlspecialchars($owner->fill($this->data->get('alt', ''))),
            "class" => $owner->fill($this->data->get('cssclass', ''))
        );

        if (!empty($styles)) {
            $imageAttributes['style'] = implode(';', $styles);
        }

        $linkAttributes = array();
        if (!empty($linkStyles)) {
            $linkAttributes['style'] = implode(';', $linkStyles);
        }

        $title = htmlspecialchars($owner->fill($this->data->get('title', '')));
        if (!empty($title)) {
            $imageAttributes['title'] = $title;
        }

        $html = $owner->renderImage($this, $image, $imageAttributes);

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        return Html::tag("div", array(
            "class" => $style . ' n2-ss-item-image-content n2-ss-item-content n2-ow-all'
        ), $this->getLink($html, $linkAttributes));
    }
}