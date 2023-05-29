<?php


namespace Nextend\Framework\Asset\Fonts\Google;


use Nextend\Framework\Asset\AssetManager;

class Google {

    public static $enabled = false;

    public static $excludedFamilies = array();

    public static function addFont($family, $style = '400') {
        AssetManager::$googleFonts->addFont($family, $style);
    }

    public static function addFontExclude($family) {
        self::$excludedFamilies[] = $family;
    }

    public static function build() {
        if (self::$enabled) {
            AssetManager::$googleFonts->loadFonts();
        }
    }
}