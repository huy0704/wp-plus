<?php

namespace Nextend\Framework\Url;

use Nextend\Framework\Url\Joomla\JoomlaUrl;
use Nextend\Framework\Url\WordPress\WordPressUrl;

class Url {

    /**
     * @var AbstractPlatformUrl
     */
    private static $platformUrl;

    public function __construct() {
        self::$platformUrl = new WordPressUrl();
    }

    /**
     * @return AbstractPlatformUrl
     */
    public static function get() {

        return self::$platformUrl;
    }

    public static function getUris() {

        return self::$platformUrl->getUris();
    }

    public static function setBaseUri($uri) {
        self::$platformUrl->setBaseUri($uri);
    }

    public static function getSiteUri() {
        return self::$platformUrl->getSiteUri();
    }

    public static function getBaseUri() {

        return self::$platformUrl->getBaseUri();
    }

    public static function getFullUri() {

        return self::$platformUrl->getFullUri();
    }

    public static function pathToUri($path, $protocol = true) {

        return self::$platformUrl->pathToUri($path, $protocol);
    }

    public static function ajaxUri($query = '') {

        return self::$platformUrl->ajaxUri($query);
    }

    public static function fixrelative($uri) {

        return self::$platformUrl->fixrelative($uri);
    }

    public static function relativetoabsolute($uri) {

        return self::$platformUrl->relativetoabsolute($uri);
    }

    public static function addScheme($url) {

        return self::$platformUrl->addScheme($url);
    }
}

new Url();