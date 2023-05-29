<?php

namespace Nextend\Framework\Pattern;

trait PluggableFactoryTrait {

    use SingletonTrait;

    private static $types = array();

    public static function addType($name, $className) {
        self::$types[$name] = $className;
    }

    public static function getType($name) {

        if (isset(self::$types[$name])) {
            return self::$types[$name];
        }

        return false;
    }

    public static function getTypes() {

        return self::$types;
    }
}