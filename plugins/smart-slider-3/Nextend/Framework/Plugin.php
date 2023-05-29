<?php


namespace Nextend\Framework;

class Plugin {

    private static $classes = array();

    /**
     * @param          $eventName
     * @param callable $callable
     */
    public static function addAction($eventName, $callable) {
        if (!isset(self::$classes[$eventName])) self::$classes[$eventName] = array();
        self::$classes[$eventName][] = $callable;
    }

    static function addFilter($eventName, $callable) {
        if (!isset(self::$classes[$eventName])) self::$classes[$eventName] = array();
        self::$classes[$eventName][] = $callable;
    }

    public static function applyFilters($eventName, $value, $args = array()) {
        if (self::hasAction($eventName)) {
            foreach (self::$classes[$eventName] as $callable) {
                if (is_callable($callable)) {
                    $value = call_user_func_array($callable, array_merge(array($value), $args));
                }
            }
        }

        return $value;
    }

    public static function doAction($eventName, $args = array()) {
        if (self::hasAction($eventName)) {
            foreach (self::$classes[$eventName] as $callable) {
                if (is_callable($callable)) {
                    call_user_func_array($callable, $args);
                }
            }
        }
    }

    public static function hasAction($eventName) {
        if (isset(self::$classes[$eventName])) {
            return true;
        }

        return false;
    }
}