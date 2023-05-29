<?php

namespace Nextend\Framework\Asset\Js;

use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Filesystem\Filesystem;

class Js {

    public static function addFile($pathToFile, $group) {
        AssetManager::$js->addFile($pathToFile, $group);
    }

    public static function addFiles($path, $files, $group) {
        AssetManager::$js->addFiles($path, $files, $group);
    }

    public static function addStaticGroup($file, $group) {
        AssetManager::$js->addStaticGroup($file, $group);
    }

    public static function addCode($code, $group) {
        AssetManager::$js->addCode($code, $group);
    }

    public static function addUrl($url) {
        AssetManager::$js->addUrl($url);
    }

    public static function addFirstCode($code, $unshift = false) {
        AssetManager::$js->addFirstCode($code, $unshift);
    }

    public static function addInline($code, $unshift = false) {
        AssetManager::$js->addInline($code, null, $unshift);
    }

    public static function addGlobalInline($code, $unshift = false) {
        AssetManager::$js->addGlobalInline($code, $unshift);
    }

    public static function addInlineFile($path, $unshift = false) {
        static $loaded = array();
        if (!isset($loaded[$path])) {
            AssetManager::$js->addInline(Filesystem::readFile($path), null, $unshift);
            $loaded[$path] = 1;
        }
    }

    public static function addGlobalInlineFile($path, $unshift = false) {
        static $loaded = array();
        if (!isset($loaded[$path])) {
            AssetManager::$js->addGlobalInline(Filesystem::readFile($path), $unshift);
            $loaded[$path] = 1;
        }
    }

}