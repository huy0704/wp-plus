<?php


namespace Nextend\Framework\Pattern;


use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Url\Url;

trait GetAssetsPathTrait {

    use GetPathTrait;

    public static function getAssetsPath() {

        return Platform::filterAssetsPath(self::getPath() . '/Assets');
    }

    public static function getAssetsUri() {
        return Url::pathToUri(self::getAssetsPath());
    }
}