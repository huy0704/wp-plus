<?php

namespace Nextend\Framework\ResourceTranslator;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Image\Image;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Settings;
use Nextend\Framework\Url\Url;

class ResourceTranslator {

    use SingletonTrait;

    /**
     * @var ResourceIdentifier[]
     */
    private static $resources = array();

    private static $isProtocolRelative = true;

    private static $resourceIdentifierKeywords = array();

    protected function init() {

        self::$isProtocolRelative = !!Settings::get('protocol-relative', 1);

        self::createResource('$', Filesystem::getBasePath(), Url::getBaseUri());

        Image::getInstance();
    }

    /**
     * @param string $keyword
     * @param string $path
     * @param string $url
     */
    public static function createResource($keyword, $path, $url) {

        $resourceIdentifier = new ResourceIdentifier($keyword, $path, self::convertUrl($url));

        array_unshift(self::$resources, $resourceIdentifier);

        self::$resourceIdentifierKeywords[] = $resourceIdentifier->getKeyword();
    }

    public static function isResource($resourcePath) {

        return preg_match(self::getResourceIdentifierRegexp(), $resourcePath);
    }

    public static function toUrl($resourcePath) {

        foreach (self::$resources as $resourceIdentifier) {

            $keyword = $resourceIdentifier->getKeyword();
            if (strpos($resourcePath, $keyword) === 0) {

                return str_replace(DIRECTORY_SEPARATOR, '/', $resourceIdentifier->getUrl() . substr($resourcePath, strlen($keyword)));
            }
        }

        return $resourcePath;
    }

    public static function toPath($resourcePath) {

        foreach (self::$resources as $resourceIdentifier) {

            $keyword = $resourceIdentifier->getKeyword();
            if (strpos($resourcePath, $keyword) === 0) {

                return str_replace('/', DIRECTORY_SEPARATOR, $resourceIdentifier->getPath() . substr($resourcePath, strlen($keyword)));
            }
        }

        return $resourcePath;
    }

    public static function urlToResource($originalUrl) {

        $url = self::convertUrl($originalUrl);

        foreach (self::$resources as $resourceIdentifier) {

            if (strpos($url, $resourceIdentifier->getUrl()) === 0) {

                return $resourceIdentifier->getKeyword() . substr($url, strlen($resourceIdentifier->getUrl()));
            }
        }

        return $originalUrl;
    }

    public static function pathToResource($path) {

        foreach (self::$resources as $resourceIdentifier) {

            if (strpos($path, $resourceIdentifier->getPath()) === 0) {

                return $resourceIdentifier->getKeyword() . substr($path, strlen($resourceIdentifier->getPath()));
            }
        }

        return $path;
    }

    /**
     * @return bool
     */
    public static function isProtocolRelative() {

        return self::$isProtocolRelative;
    }

    /**
     * @return string[]
     */
    public static function getResourceIdentifierKeywords() {

        $keywords = array();
        foreach (self::$resources as $resourceIdentifier) {

            $keywords[] = $resourceIdentifier->getKeyword();
        }

        return $keywords;
    }

    public static function getResourceIdentifierUrls() {

        $urls = array();
        foreach (self::$resources as $resourceIdentifier) {

            $urls[] = $resourceIdentifier->getUrl();
        }

        return $urls;
    }

    public static function exportData() {

        $data = array();

        foreach (self::$resources as $resourceIdentifier) {

            $data[$resourceIdentifier->getKeyword()] = $resourceIdentifier->getUrl();
        }

        return $data;
    }

    private static function convertUrl($url) {

        if (self::$isProtocolRelative) {

            return preg_replace('/^http(s)?:\/\//', '//', $url);
        }

        return $url;
    }

    private static function getResourceIdentifierRegexp() {

        return '/^' . join('|', array_map(function ($keyword) {
                return preg_quote($keyword, '/');
            }, self::$resourceIdentifierKeywords)) . '/';
    }
}

ResourceTranslator::getInstance();