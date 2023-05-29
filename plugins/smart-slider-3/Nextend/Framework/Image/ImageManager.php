<?php

namespace Nextend\Framework\Image;

use Nextend\Framework\Image\Block\ImageManager\BlockImageManager;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Pattern\VisualManagerTrait;

class ImageManager {

    use VisualManagerTrait;

    /**
     * @var ImageStorage
     */
    private static $model;

    public static $loaded = array();

    public function display() {

        $imageManagerBlock = new BlockImageManager($this->MVCHelper);
        $imageManagerBlock->display();
    }

    public static function init() {
        self::$model = new ImageStorage();
    }

    public static function hasImageData($image) {
        $image = self::$model->getByImage($image);
        if (!empty($image)) {
            return true;
        }

        return false;
    }

    public static function getImageData($image, $read = false) {
        $visual = self::$model->getByImage($image);
        if (empty($visual)) {
            if ($read) {
                return false;
            } else {
                $id     = self::addImageData($image, ImageStorage::$emptyImage);
                $visual = self::$model->getById($id);
            }
        }
        self::$loaded[] = $visual;

        return array_merge(ImageStorage::$emptyImage, json_decode(Base64::decode($visual['value']), true));
    }

    public static function addImageData($image, $value) {
        return self::$model->add($image, $value);
    }

    public static function setImageData($image, $value) {
        self::$model->setByImage($image, $value);
    }
}

ImageManager::init();