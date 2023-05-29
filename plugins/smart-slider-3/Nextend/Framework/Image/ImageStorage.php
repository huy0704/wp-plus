<?php

namespace Nextend\Framework\Image;

use Nextend\Framework\Database\AbstractPlatformConnectorTable;
use Nextend\Framework\Database\Database;
use Nextend\Framework\Misc\Base64;

class ImageStorage {

    /**
     * @var AbstractPlatformConnectorTable
     */
    private $tableImageStorage;

    public static $emptyImage = array(
        'desktop-retina' => array(
            'image' => ''
        ),
        'tablet'         => array(
            'image' => ''
        ),
        'mobile'         => array(
            'image' => ''
        )
    );

    public function __construct() {
        $this->tableImageStorage = Database::getTable("nextend2_image_storage");
    }

    public function getById($id) {
        return $this->tableImageStorage->findByAttributes(array(
            "id" => $id
        ));
    }

    public function getByImage($image) {
        static $cache = array();

        if (!isset($cache[$image])) {
            $cache[$image] = $this->tableImageStorage->findByAttributes(array(
                "hash" => md5($image)
            ));
        }

        return $cache[$image];
    }

    public function setById($id, $value) {

        if (is_array($value)) {
            $value = Base64::encode(json_encode($value));
        }

        $result = $this->getById($id);

        if ($result !== null) {
            $this->tableImageStorage->update(array('value' => $value), array(
                "id" => $id
            ));

            return true;
        }

        return false;
    }

    public function setByImage($image, $value) {

        if (is_array($value)) {
            $value = Base64::encode(json_encode($value));
        }

        $result = $this->getByImage($image);

        if ($result !== null) {
            $this->tableImageStorage->update(array('value' => $value), array(
                "id" => $result['id']
            ));

            return true;
        }

        return false;
    }

    public function getAll() {
        return $this->tableImageStorage->findAllByAttributes(array(), array(
            "id",
            "hash",
            "image",
            "value"
        ));
    }

    public function set($image, $value) {

        if (is_array($value)) {
            $value = Base64::encode(json_encode($value));
        }

        $result = $this->getByImage($image);

        if (empty($result)) {
            return $this->add($image, $value);
        } else {
            $attributes = array(
                "id" => $result['id']
            );
            $this->tableImageStorage->update(array('value' => $value), $attributes);

            return true;
        }
    }

    public function add($image, $value) {

        if (is_array($value)) {
            $value = Base64::encode(json_encode($value));
        }

        $this->tableImageStorage->insert(array(
            "hash"  => md5($image),
            "image" => $image,
            "value" => $value
        ));

        return $this->tableImageStorage->insertId();
    }

    public function deleteById($id) {

        $this->tableImageStorage->deleteByAttributes(array(
            "id" => $id
        ));

        return true;
    }

    public function deleteByImage($image) {

        $this->tableImageStorage->deleteByAttributes(array(
            "hash" => md5($image)
        ));

        return true;
    }
}