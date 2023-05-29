<?php

namespace Nextend\Framework\Session;


use Nextend\Framework\Session\Joomla\JoomlaStorage;
use Nextend\Framework\Session\WordPress\WordPressStorage;

class Session {

    /**
     * @var $storage AbstractStorage
     */
    private static $storage = false;

    private static function getStorage() {
        if (!self::$storage) {
            self::$storage = new WordPressStorage();
        }

        return self::$storage;
    }

    public static function get($key, $default = null) {
        return self::getStorage()
                   ->get($key, $default);
    }

    public static function set($key, $value) {

        self::getStorage()
            ->set($key, $value);
    }

    public static function delete($key) {

        self::getStorage()
            ->delete($key);
    }
}