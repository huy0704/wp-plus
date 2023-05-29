<?php


namespace Nextend\Framework\Misc;


use Nextend\Framework\Misc\StringPhp\MultiByte;
use Nextend\Framework\Misc\StringPhp\SingleByte;
use Nextend\Framework\Misc\StringPhp\StringInterface;
use Nextend\Framework\Pattern\SingletonTrait;

class Str {

    use SingletonTrait;

    /**
     * @var StringInterface
     */
    private static $engine;

    protected function init() {
        if (function_exists('mb_strpos')) {
            self::$engine = new MultiByte();
        } else {
            self::$engine = new SingleByte();
        }
    }

    public static function strpos($haystack, $needle, $offset = 0) {
        return self::$engine->strpos($haystack, $needle, $offset);
    }

    public static function substr($string, $start, $length = null) {
        return self::$engine->substr($string, $start, $length);
    }

    public static function strlen($string) {
        return self::$engine->strlen($string);
    }
}

Str::getInstance();