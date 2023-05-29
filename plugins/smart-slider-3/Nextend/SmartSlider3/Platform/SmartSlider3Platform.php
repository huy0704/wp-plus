<?php

namespace Nextend\SmartSlider3\Platform;

use Nextend\Framework\Pattern\SingletonTrait;

class SmartSlider3Platform {

    use SingletonTrait;

    /**
     * @var AbstractSmartSlider3Platform
     */
    private static $platform;

    public function __construct() {
        self::$platform = WordPress\SmartSlider3PlatformWordPress::getInstance();

        self::$platform->start();
    }

    public static function getAdminUrl() {

        return self::$platform->getAdminUrl();
    }

    public static function getAdminAjaxUrl() {

        return self::$platform->getAdminAjaxUrl();
    }

    public static function getNetworkAdminUrl() {

        return self::$platform->getNetworkAdminUrl();
    }
}