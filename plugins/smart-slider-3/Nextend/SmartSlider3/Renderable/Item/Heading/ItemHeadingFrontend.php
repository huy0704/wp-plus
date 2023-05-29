<?php


namespace Nextend\SmartSlider3\Renderable\Item\Heading;


use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemHeadingFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    private function getHtml() {
        $owner = $this->layer->getOwner();

        $attributes = array();
        $font = $owner->addFont($this->data->get('font'), 'hover');

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        $linkAttributes = array(
            'class' => 'n2-ow'
        );
        if ($this->isEditor) {
            $linkAttributes['onclick'] = 'return false;';
        }

        $title = $this->data->get('title', '');
        if (!empty($title)) {
            $attributes['title'] = $title;
        }

        $href = $this->data->get('href', '');
        if (!empty($href) && $href != '#') {
            $linkAttributes['class'] .= ' ' . $font . $style;

            $font  = '';
            $style = '';
        }

        $linkAttributes['style'] = "display:" . ($this->data->get('fullwidth', 1) ? 'block' : 'inline-block') . ";";

        $strippedHtml = Sanitize::filter_allowed_html($owner->fill($this->data->get('heading', '')));

        return $this->heading($this->data->get('priority', 'div'), $attributes + array(
                "id"    => $this->id,
                "class" => $font . $style . " " . $owner->fill($this->data->get('class', '')) . ' n2-ss-item-content n2-ss-text n2-ow',
                "style" => "display:" . ($this->data->get('fullwidth', 1) ? 'block' : 'inline-block') . ";" . ($this->data->get('nowrap', 0) ? 'white-space:nowrap;' : '')
            ), $this->getLink(str_replace("\n", '<br>', $strippedHtml), $linkAttributes));
    }

    private function heading($type, $attributes, $content) {
        if (is_numeric($type) && $type > 0) {
            return Html::tag("h{$type}", $attributes, $content);
        }

        return Html::tag("div", $attributes, $content);
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    public function isAuto() {
        return !$this->data->get('fullwidth', 1);
    }
}