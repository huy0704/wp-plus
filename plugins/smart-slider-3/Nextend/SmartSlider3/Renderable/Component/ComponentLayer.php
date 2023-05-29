<?php

namespace Nextend\SmartSlider3\Renderable\Component;

use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;
use Nextend\SmartSlider3\Renderable\Item\ItemFactory;

class ComponentLayer extends AbstractComponent {

    protected $type = 'layer';

    /** @var AbstractItemFrontend */
    private $item;

    public function __construct($index, $owner, $group, $data) {

        parent::__construct($index, $owner, $group, $data);


        $this->attributes['style'] = '';

        $item = $this->data->get('item');
        if (empty($item)) {
            $items = $this->data->get('items');
            $item  = $items[0];
        }

        $this->item = ItemFactory::create($this, $item);

        $this->placement->attributes($this->attributes);
    }

    public function render($isAdmin) {
        if ($this->isRenderAllowed()) {

            $this->runPlugins();

            $this->serveLocalStyle();

            $this->prepareHTML();

            if ($isAdmin) {
                $renderedItem = $this->item->renderAdmin();
            } else {
                $renderedItem = $this->item->render();
            }

            if ($renderedItem === false) {
                return '';
            }

            if ($this->item->needHeight()) {
                $this->attributes['class'] .= ' n2-ss-layer--need-height';
            }

            if ($this->item->isAuto()) {
                $this->attributes['class'] .= ' n2-ss-layer--auto';
            }

            $html = $this->renderPlugins($renderedItem);

            if ($isAdmin) {
                $this->admin();
            }

            return Html::tag('div', $this->attributes, $html);
        }

        return '';
    }

    /**
     * @param AbstractRenderableOwner $slide
     * @param array                   $layer
     */
    public static function getFilled($slide, &$layer) {
        AbstractComponent::getFilled($slide, $layer);

        if (empty($layer['item'])) {
            $layer['item'] = $layer['items'][0];
            unset($layer['items']);
        }
        ItemFactory::getFilled($slide, $layer['item']);
    }

    public static function prepareExport($export, $layer) {
        if (empty($layer['item'])) {
            $layer['item'] = $layer['items'][0];
            unset($layer['items']);
        }

        ItemFactory::prepareExport($export, $layer['item']);

    }

    public static function prepareImport($import, &$layer) {
        if (empty($layer['item'])) {
            $layer['item'] = $layer['items'][0];
            unset($layer['items']);
        }

        $layer['item'] = ItemFactory::prepareImport($import, $layer['item']);
    }

    public static function prepareSample(&$layer) {
        if (empty($layer['item'])) {
            $layer['item'] = $layer['items'][0];
            unset($layer['items']);
        }

        $layer['item'] = ItemFactory::prepareSample($layer['item']);
    }
}