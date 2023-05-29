<?php

namespace Nextend\SmartSlider3;

use Nextend\Framework\Model\StorageSectionManager;

class Settings {

    static $settings = null;

    private static $_type = "settings";

    static function getAll() {
        if (self::$settings === null) {

            self::$settings = json_decode(StorageSectionManager::getStorage('smartslider')
                                                               ->get(self::$_type), true);
            if (self::$settings === null || !is_array(self::$settings)) self::$settings = array();
        }

        return self::$settings;
    }

    static function get($key, $default = null) {
        if (self::$settings === null) self::getAll();
        if (!array_key_exists($key, self::$settings)) return $default;

        return self::$settings[$key];
    }

    static function set($key, $value) {
        self::getAll();
        self::$settings[$key] = $value;
        self::store(self::$_type, json_encode(self::$settings));
    }

    static function store($key, $value) {
        StorageSectionManager::getStorage('smartslider')
                             ->set($key, '', $value);
    }
}