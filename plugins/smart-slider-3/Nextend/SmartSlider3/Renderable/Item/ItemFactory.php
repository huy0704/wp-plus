<?php


namespace Nextend\SmartSlider3\Renderable\Item;


use Exception;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;
use Nextend\SmartSlider3\Renderable\Item\Button\ItemButton;
use Nextend\SmartSlider3\Renderable\Item\Heading\ItemHeading;
use Nextend\SmartSlider3\Renderable\Item\Image\ItemImage;
use Nextend\SmartSlider3\Renderable\Item\Missing\ItemMissing;
use Nextend\SmartSlider3\Renderable\Item\Text\ItemText;
use Nextend\SmartSlider3\Renderable\Item\Vimeo\ItemVimeo;
use Nextend\SmartSlider3\Renderable\Item\YouTube\ItemYouTube;

class ItemFactory {

    use SingletonTrait, PluggableTrait, OrderableTrait;

    public static $i = array();
    /** @var AbstractItem[][] */
    private static $itemGroups = array();
    /**
     * @var AbstractItem[]
     */
    private static $items = array();

    /**
     * @return AbstractItem[]
     */
    public static function getItems() {

        return self::$items;
    }

    /**
     * @param $type
     *
     * @return AbstractItem
     */
    public static function getItem($type) {

        return self::$items[$type];
    }

    /**
     * @return AbstractItem[][]
     */
    public static function getItemGroups() {

        return self::$itemGroups;
    }

    /**
     * @param ComponentLayer $layer
     * @param array          $itemData
     *
     * @return AbstractItemFrontend
     * @throws Exception
     */
    public static function create($layer, $itemData) {

        if (!isset($itemData['type'])) {
            throw new Exception('Error with itemData: ' . $itemData);
        }

        $type = $itemData['type'];

        if ($type == 'missing') {
            $type = $itemData['values']['type'];
        }

        if (!isset(self::$items[$type])) {
            $itemData['values']['type'] = $type;

            $type = 'missing';
        }

        /** @var AbstractItem $factory */
        $factory = self::$items[$type];

        $elementID = $layer->getOwner()
                           ->getElementID();

        if (!isset(self::$i[$elementID])) {
            self::$i[$elementID] = 0;
        }

        self::$i[$elementID]++;
        $id = $elementID . 'item' . self::$i[$elementID];

        return $factory->createFrontend($id, $itemData['values'], $layer);
    }

    /**
     * @param AbstractRenderableOwner $slide
     * @param array                   $item
     */
    public static function getFilled($slide, &$item) {

        $type = $item['type'];
        if (isset(self::$items[$type])) {
            $item['values'] = self::$items[$type]->getFilled($slide, new Data($item['values']))
                                                 ->toArray();
        }
    }

    /**
     * @param ExportSlider                                    $export
     * @param                                                 $item
     */
    public static function prepareExport($export, $item) {

        $type = $item['type'];
        if (isset(self::$items[$type])) {
            self::$items[$type]->prepareExport($export, new Data($item['values']));
        }
    }

    /**
     * @param ImportSlider                                    $import
     * @param                                                 $item
     *
     * @return mixed
     */
    public static function prepareImport($import, $item) {

        $type = $item['type'];
        if (isset(self::$items[$type])) {
            $item['values'] = self::$items[$type]->prepareImport($import, new Data($item['values']))
                                                 ->toArray();
        }

        return $item;
    }

    public static function prepareSample($item) {

        $type = $item['type'];
        if (isset(self::$items[$type])) {
            $item['values'] = self::$items[$type]->prepareSample(new Data($item['values']))
                                                 ->toArray();
        }

        return $item;
    }

    /**
     * @param AbstractItem $item
     */
    public function addItem($item) {

        self::$items[$item->getType()] = $item;
    }

    protected function init() {

        new ItemHeading($this);
        new ItemButton($this);
        new ItemImage($this);
        new ItemText($this);
        new ItemVimeo($this);
        new ItemYouTube($this);

        $this->makePluggable('RenderableItem');

        self::uasort(self::$items);

        self::$itemGroups[n2_x('Basic', 'Layer group')] = array();
        self::$itemGroups[n2_x('Media', 'Layer group')] = array();

        foreach (self::$items as $type => $item) {
            $group = $item->getGroup();
            if (!isset(self::$itemGroups[$group])) {
                self::$itemGroups[$group] = array();
            }
            self::$itemGroups[$group][$type] = $item;
        }

        new ItemMissing($this);
    }
}

ItemFactory::getInstance();