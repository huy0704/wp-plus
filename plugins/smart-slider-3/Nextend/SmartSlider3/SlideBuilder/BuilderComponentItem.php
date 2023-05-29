<?php


namespace Nextend\SmartSlider3\SlideBuilder;

use Nextend\SmartSlider3\Renderable\Item;

class BuilderComponentItem extends AbstractBuilderComponent {

    /**
     * @var Item\AbstractItem
     */
    protected $item;

    /**
     *
     * @param AbstractBuilderComponent $container
     * @param string                   $type
     */
    public function __construct($container, $type) {
        $this->item        = Item\ItemFactory::getItem($type);
        $this->defaultData = array_merge($this->defaultData, $this->item->getValues());

        $container->add($this);
    }

    public function getData() {
        return array(
            'type'   => $this->item->getType(),
            'values' => parent::getData()
        );
    }

    public function getLabel() {
        return $this->item->getTitle();
    }

    public function getLayerProperties() {
        return $this->item->getLayerProperties();
    }
}