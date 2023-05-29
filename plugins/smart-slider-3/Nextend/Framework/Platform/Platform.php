<?php


namespace Nextend\Framework\Platform;


use Nextend\Framework\Pattern\SingletonTrait;

class Platform {

    use SingletonTrait;

    /**
     * @var AbstractPlatform
     */
    private static $platform;

    public function __construct() {
        self::$platform = new WordPress\PlatformWordPress();
    }

    public static function getName() {
        return self::$platform->getName();
    }

    public static function getLabel() {
        return self::$platform->getLabel();
    }

    public static function getVersion() {
        return self::$platform->getVersion();
    }

    public static function isAdmin() {
        return self::$platform->isAdmin();
    }

    public static function setIsAdmin($isAdmin) {
        self::$platform->setIsAdmin($isAdmin);
    }

    public static function hasPosts() {
        return self::$platform->hasPosts();
    }

    public static function getSiteUrl() {
        return self::$platform->getSiteUrl();
    }

    public static function getCharset() {
        return self::$platform->getCharset();
    }

    public static function getMysqlDate() {
        return self::$platform->getMysqlDate();
    }

    public static function getTimestamp() {
        return self::$platform->getTimestamp();
    }

    public static function localizeDate($date) {
        return self::$platform->localizeDate($date);
    }

    public static function getPublicDirectory() {
        return self::$platform->getPublicDirectory();
    }

    public static function getUserEmail() {
        return self::$platform->getUserEmail();
    }

    public static function needStrongerCss() {
        return self::$platform->needStrongerCss();
    }

    public static function getDebug() {

        return self::$platform->getDebug();
    }

    public static function filterAssetsPath($assetsPath) {

        return self::$platform->filterAssetsPath($assetsPath);
    }
}

Platform::getInstance();