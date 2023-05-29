<?php

namespace Nextend\Framework\Asset\Css;

use Nextend\Framework\Asset\AssetManager;

class Css {

    public static function addFile($pathToFile, $group) {
        AssetManager::$css->addFile($pathToFile, $group);
    }

    public static function addFiles($path, $files, $group) {
        AssetManager::$css->addFiles($path, $files, $group);
    }

    public static function addStaticGroupPreload($file, $group) {
        AssetManager::$css->addStaticGroupPreload($file, $group);
    }

    public static function addStaticGroup($file, $group) {
        AssetManager::$css->addStaticGroup($file, $group);
    }

    public static function addCode($code, $group, $unshift = false) {
        AssetManager::$css->addCode($code, $group, $unshift);
    }

    public static function addUrl($url) {
        AssetManager::$css->addUrl($url);
    }

    public static function addInline($code, $name = null) {
        AssetManager::$css->addInline($code, $name);
    }
}