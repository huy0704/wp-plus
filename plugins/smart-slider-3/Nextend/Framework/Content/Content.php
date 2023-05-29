<?php

namespace Nextend\Framework\Content;

use Nextend\Framework\Content\Joomla\JoomlaContent;
use Nextend\Framework\Content\WordPress\WordPressContent;

class Content {

    /**
     * @var AbstractPlatformContent
     */
    private static $platformContent;

    public function __construct() {
        self::$platformContent = new WordPressContent();
    }

    public static function searchLink($keyword) {
        return self::$platformContent->searchLink($keyword);
    }

    public static function searchContent($keyword) {
        return self::$platformContent->searchContent($keyword);
    }
}

new Content();