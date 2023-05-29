<?php


namespace Nextend\SmartSlider3\Renderable\Item\Button;


use Nextend\Framework\Icon\Icon;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;

class ItemButtonFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHtml() {
        $owner = $this->layer->getOwner();

        $this->loadResources($owner);

        $font = $owner->addFont($this->data->get('font'), 'link');

        $html = Html::openTag("div", array(
            "class" => "n2-ss-button-container n2-ss-item-content n2-ow " . $font . ($this->data->get('nowrap', 1) ? ' n2-ss-nowrap' : '') . ($this->isAuto() ? ' n2-ss-button-container--non-full-width' : '')
        ));

        $content = '<div>' . Sanitize::filter_allowed_html($owner->fill($this->data->get("content"))) . '</div>';

        $attrs = array();

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        $html .= $this->getLink('<div>' . $content . '</div>', $attrs + array(
                "class" => "{$style} n2-ow " . $owner->fill($this->data->get('class', ''))
            ), true);

        $html .= Html::closeTag("div");

        return $html;
    }

    /**
     * @param AbstractRenderableOwner $owner
     */
    public function loadResources($owner) {
        $owner->addLess(self::getAssetsPath() . "/button.n2less", array(
            "sliderid" => $owner->getElementID()
        ));
    }

    public function isAuto() {
        return !$this->data->get('fullwidth', 0);
    }
}