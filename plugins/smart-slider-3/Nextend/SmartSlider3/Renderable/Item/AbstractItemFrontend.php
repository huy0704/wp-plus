<?php


namespace Nextend\SmartSlider3\Renderable\Item;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Parser\Link;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;

abstract class AbstractItemFrontend {

    use GetAssetsPathTrait;

    /** @var AbstractItem */
    protected $item;

    protected $id;

    /** @var ComponentLayer */
    protected $layer;

    /** @var Data */
    public $data;

    protected $isEditor = false;

    /**
     *
     * @param AbstractItem   $item
     * @param string         $id
     * @param array          $itemData
     * @param ComponentLayer $layer
     */
    public function __construct($item, $id, $itemData, $layer) {
        $this->item  = $item;
        $this->id    = $id;
        $this->data  = new Data($itemData);
        $this->layer = $layer;

        $this->fillDefault($item->getValues());
    }

    private function fillDefault($defaults) {

        $this->item->upgradeData($this->data);

        $this->data->fillDefault($defaults);
    }

    public abstract function render();

    public function renderAdmin() {
        $this->isEditor = true;

        /**
         * Fix linked fonts/styles for the editor
         */
        $this->item->adminNormalizeFontsStyles($this->data);

        $rendered = $this->renderAdminTemplate();

        $json = $this->data->toJson();

        return Html::tag("div", array(
            "class"           => "n2-ss-item n2-ss-item-" . $this->item->getType(),
            "data-item"       => $this->item->getType(),
            "data-itemvalues" => $json
        ), $rendered);
    }

    protected abstract function renderAdminTemplate();

    public function needHeight() {
        return false;
    }

    public function isAuto() {
        return false;
    }

    protected function hasLink() {
        $link = $this->data->get('href', '#');
        if (($link != '#' && !empty($link))) {
            return true;
        }

        return false;
    }

    protected function getLink($content, $attributes = array(), $renderEmpty = false) {

        $link   = $this->data->get('href', '#');
        $target = $this->data->get('href-target', '#');
        $rel    = $this->data->get('href-rel', '#');
        $class  = $this->data->get('href-class', '');

        if (($link != '#' && !empty($link)) || $renderEmpty === true) {

            $link = Link::parse($this->layer->getOwner()
                                            ->fill($link), $attributes, $this->isEditor);
            if (!empty($target) && $target != '_self') {
                $attributes['target'] = $target;
            }
            if (!empty($rel)) {
                $attributes['rel'] = $rel;
            }
            if (!empty($class)) {
                $attributes['class'] = $class;
            }

            return Html::link($content, $link, $attributes);
        }

        return $content;
    }
}