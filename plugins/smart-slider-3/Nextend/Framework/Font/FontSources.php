<?php


namespace Nextend\Framework\Font;


use Nextend\Framework\Filesystem\Filesystem;

class FontSources {

    /** @var AbstractFontSource[] */
    private static $fontSources = array();

    public function __construct() {
        $dir = dirname(__FILE__) . '/Sources/';
        foreach (Filesystem::folders($dir) as $folder) {
            $file = $dir . $folder . '/' . $folder . '.php';
            if (Filesystem::fileexists($file)) {
                require_once($file);
            }
        }
    }

    /**
     * @param string $class
     */
    public static function registerSource($class) {

        /** @var AbstractFontSource $source */
        $source = new $class();

        self::$fontSources[$source->getName()] = $source;
    }

    /**
     * @return AbstractFontSource[]
     */
    public static function getFontSources() {
        return self::$fontSources;
    }

    public static function onFontManagerLoad($force = false) {
        foreach (self::$fontSources as $source) {
            $source->onFontManagerLoad($force);
        }
    }

    public static function onFontManagerLoadBackend() {
        foreach (self::$fontSources as $source) {
            $source->onFontManagerLoadBackend();
        }
    }
}

new FontSources();